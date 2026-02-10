<?php
namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\ReciboService;
use Illuminate\Support\Collection;
use App\Recibo;
use App\Comision;
use App\Cliente;
use App\Proveedor;
use App\CuentaCorriente;
use App\Venta;
use App\DetalleVenta;
use App\Producto;
use App\Vendedor;
use App\TipoVenta;
use App\Lista;
use App\Marca;
use App\Rubro;
use App\VentaReciboPago;
use App\Logs;
use App\ProductoHistorialStock;
use Auth;
use DB;
use Log;

class VentaService
{
    protected $reciboService;
    protected $afectarStock;
    protected $afectarCC;

    public function __construct(ReciboService $reciboService = null){
        $this->reciboService = $reciboService;
    }

    protected function getDependencias(){
        //Obtiene los registros de los modelos frecuentes relacionados con una Venta
        $clientes = Cliente::whereNull('deleted_at')->orderBy('razon_social','ASC')->get();
        $proveedores = Proveedor::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $productos = Producto::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $vendedores = Vendedor::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $tipoVentas = TipoVenta::all();
        $listas = Lista::all();
        $marcas = Marca::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $rubros = Rubro::whereNull('deleted_at')->orderBy('nombre','ASC')->get();

        return [
            'clientes' => $clientes,
            'proveedores' => $proveedores,
            'productos'=> $productos,
            'vendedores' => $vendedores,
            'tipoVentas' => $tipoVentas,
            'listas' => $listas,
            'marcas' => $marcas,
            'rubros' => $rubros,
        ];
    }

    public function listAll(){
        $ventas = Venta::whereNull('deleted_at')
            ->with([
                'tipoVenta:id,nombre',
                'vendedor:id,nombre',
                'usuario:id,nombre',
            ])
            ->orderBy('id','DESC')
            ->get();
        $data = $this->getDependencias();
        $data['ventas'] = $ventas;

        return $data;
    }

    public function listAllAbiertas(){
        $ventas = Venta::select('ventas.*')
            ->selectRaw('IFNULL((SELECT 1 FROM ventas_recibos_pagos vrp WHERE vrp.id_venta = ventas.id LIMIT 1), 0) as tiene_pago')
            ->whereNull('ventas.deleted_at')
            ->with([
                'tipoVenta:id,nombre',
                'vendedor:id,nombre',
                'usuario:id,nombre',
            ])
            ->where(function ($query) {
                $query->whereNull('ventas.pagada')
                    ->orWhere('ventas.pagada', 0);
            })
            ->orderBy('ventas.id', 'DESC')
            ->get();

        $data = $this->getDependencias();
        $data['ventas'] = $ventas;

        return $data;
    }

    /**
     * Obtiene las ventas abiertas asociadas a un cliente
     * idCliente
     *
    **/
    public function getAllAbiertasByClienteId($idCliente){
        return Venta::whereNull('deleted_at')
                ->where('id_cliente',$idCliente)
                ->where('id_tipo_venta',3) //Hardcode - 3 => "Venta"
                ->with([
                    'vendedor:id,nombre,porcentaje_comision',
                ])
                ->where(function ($query) {
                    $query->whereNull('pagada')
                        ->orWhere('pagada', 0);
                })
                //->orderBy('total', 'ASC')
                ->orderBy('id', 'ASC')
                ->get();
    }

    public function listAllCerradas(){
        return [
            'view_cerradas' => true,
            'ventas' => collect(),
        ];
    }

    public function getCerradasQuery(){
        return Venta::query()
            ->select([
                'ventas.id',
                'ventas.fecha',
                'ventas.fecha_pago',
                'ventas.total',
                'ventas.nombre_cliente',
                'ventas.id_vendedor',
                'ventas.id_usuario',
                'ventas.id_tipo_venta',
            ])
            ->selectRaw('IFNULL((SELECT 1 FROM ventas_recibos_pagos vrp WHERE vrp.id_venta = ventas.id LIMIT 1), 0) as tiene_pago')
            ->leftJoin('vendedores', 'vendedores.id', '=', 'ventas.id_vendedor')
            ->leftJoin('usuarios', 'usuarios.id', '=', 'ventas.id_usuario')
            ->addSelect([
                'vendedores.nombre as vendedor_nombre',
                'usuarios.nombre as usuario_nombre',
            ])
            ->whereNull('ventas.deleted_at')
            ->where('ventas.pagada', 1);
    }


    public function listAllAnuladas(){
        return [
            'view_cerradas' => true,
            'ventas' => collect(),
        ];
    }

    public function getAnuladasQuery(){
        return Venta::onlyTrashed()
            ->select([
                'ventas.id',
                'ventas.fecha',
                'ventas.total',
                'ventas.nombre_cliente',
                'ventas.id_vendedor',
                'ventas.id_usuario',
            ])
            ->leftJoin('vendedores', 'vendedores.id', '=', 'ventas.id_vendedor')
            ->leftJoin('usuarios', 'usuarios.id', '=', 'ventas.id_usuario')
            ->addSelect([
                'vendedores.nombre as vendedor_nombre',
                'usuarios.nombre as usuario_nombre',
            ]);
    }

    public function getByClienteId($id){
       $ventas = Venta::select('ventas.*')
            ->join('clientes as c', 'ventas.id_cliente', '=', 'c.id')
            ->whereNull('ventas.deleted_at')
            ->where('ventas.id_tipo_venta',3)
            ->where(function ($query) {
                $query->whereNull('ventas.pagada')
                    ->orWhere('ventas.pagada', 0);
            })
            ->where('ventas.id_cliente', $id)
            ->get();

        return $ventas;
    }

    public function getById($id){
        $venta = Venta::with(['detalleVenta.producto'])->findOrFail($id);
        $this->getHistoricoProductos($venta);
        return $venta;
    }
    //Setea los valores de los productos con el precio historico de la venta
    private function getHistoricoProductos(Venta $venta){
        foreach ($venta->detalleVenta as $detalle) {
            $detalle->producto->precio_venta_final_impresion = $this->getPrecioVentaUnitario($detalle);
            $detalle->producto->precio_venta_final = $detalle->precio;
            $detalle->producto->precio_sin_descuento = $this->getPrecioVentaUnitarioSinDescuento($detalle);
        }
    }

    //Calcula el precio de venta del producto en base al historico
    private function getPrecioVentaUnitario($detalleVenta){
        //Calculo para preparar el precio de venta unitario
        $auxPrecio = $detalleVenta->precio;
        $auxPrecio = ($auxPrecio +(( $auxPrecio*$detalleVenta->lista_valor)/100));
        if($detalleVenta->descuento_porcentual>0){
            $auxPrecio =  $auxPrecio - ($auxPrecio*$detalleVenta->descuento_porcentual)/100;
        }

//        Log::info('precio unitario: '.$auxPrecio);
        return round($auxPrecio,1);
    }

    public function getTotalSinDescuento($venta){

//        Log::debug($venta);
        $auxPrecio = 0;
        foreach ($venta->detalleVenta as $detalle){
            //Calculo para preparar el precio de venta unitario
            $precioSinDescuento = $detalle->precio;
            $auxPrecio += ($precioSinDescuento +(( $precioSinDescuento*$detalle->lista_valor)/100))* $detalle->cantidad;

        }
       return round( $auxPrecio,1);
    }

    private function getPrecioVentaUnitarioSinDescuento($detalleVenta){
        //Calculo para preparar el precio de venta unitario
        $auxPrecio = $detalleVenta->precio;
        $auxPrecio = ($auxPrecio +(( $auxPrecio*$detalleVenta->lista_valor)/100));
//        Log::info('precio unitario: '.$auxPrecio);
        return round($auxPrecio,1);
    }

    public function setPagada($id){
        $venta = Venta::findOrFail($id);
    
        $this->registrarEnLog('closed', $venta);
    
        return $venta->update(['pagada' => 1, 'fecha_pago' => Carbon::now()]);            
    }

    public function setProformaToVenta($id){
        return Venta::findOrFail($id)->update(['id_tipo_venta' => 3]);
    }

    /**
     * Generar comision
     * Genera comisiones para un vendedor para una operacion especifica
     * Ingresar coeficiente negativo para crear una comision menor a 0
     * @param venta 
     * @param coeficiente
     */
    protected function generarComision($operacion, $coeficiente){
        return $this->crearComisionParaVenta($operacion, $coeficiente);
    }

    /**
     * Crea una comisión para una venta si aún no existe.
     */
    public function crearComisionParaVenta(Venta $venta, $coeficiente = 1){
        if(!$venta->vendedor){
            Log::warning('No se pudo crear comisión: venta sin vendedor. Venta ID: '.$venta->id);
            return null;
        }

        $comisionExistente = Comision::where('id_venta', $venta->id)->exists();
        if($comisionExistente){
            Log::info('Comisión ya existente para venta ID: '.$venta->id);
            return null;
        }

        $ganancia = $this->getGanancia($venta->id);
        $vendedor = $venta->vendedor;
        $comision = $vendedor->comision()->create([
            'fecha' => Carbon::now(),
            'monto' => round(($ganancia * $vendedor->porcentaje_comision / 100) * $coeficiente,1),
            'estado' => 0,
            'periodo' => "",
            'ganancia_venta' => round($ganancia * $coeficiente,1),
            'id_venta' => $venta->id,
            'notas' => '',
        ]);
        Log::info('Comision asignada: '.json_encode($comision));
        return $comision;
    }
    
    /**
     * Actualiza el estado de la venta a cerrada si corresponde, y relaciona pagos
     * idVenta, idRecibo (nullable)
     *
    **/
    public function updateEstado($idRecibo,$idVenta=null,$noCerrar=null){
        //Por el momento, se considera que solo habra cierre de ventas y asignación automatica de pagos cuando exista un recibo emitido como pago
        if($idRecibo){
            $vPagos = new Collection();
            $vCerradas = new Collection();
            $recibo = Recibo::findOrFail($idRecibo);
            $saldoPago = $recibo->monto;
            if($idVenta){
                $venta = Venta::findOrFail($idVenta);
                $vendedor = $venta->vendedor;
                //Si el recibo esta asociado especificamente a una venta, creo el registro de pago
                if($idVenta == $recibo->id_venta){
                    //Obtengo el valor restante a pagar de la venta
                    $montoRestante = $this->getMontoRestante($idVenta);

                    Log::info('[Venta Asignada] MontoRestante: '.$montoRestante);
                    if($montoRestante>0){
                        //Calculo el pago a realizar respecto al monto ingresado y el restante
                        if($montoRestante > $recibo->monto)
                            $montoPago = $recibo->monto;
                        elseif($montoRestante == $recibo->monto)
                            $montoPago = $montoRestante;
                        else
                            $montoPago = $montoRestante;

                        $saldoPago = $recibo->monto - $montoRestante;
                        $pago = VentaReciboPago::create([
                            'id_venta' => $venta->id,
                            'id_recibo' => $recibo->id,
                            'monto' => $montoPago,
                            'created_at' => Carbon::now(),
                        ]);
                        if($pago)
                            $vPagos->push($pago);

                        if($montoPago >= 0 && is_null($noCerrar)&&($montoRestante<=$montoPago)){
                            //Si se cubrio con el pago el total del valor de la venta, esta pasa a "pagada"
                            try{
                                $this->setPagada($venta->id);
                                $this->crearComisionParaVenta($venta);

                                $vCerradas->push($venta);

                                Log::info('[Venta Asignada] Se actualizo el estado de la venta => '.$venta->id.' con un pago de $'.$montoPago);
                            } catch (\Throwable $th) {
                                Log::alert('No se actualizo el estado de la venta => '.$th->getMessage());
                            }
                        }
                    }

                }
                }
                // Si no hay venta asociada, se intentará asignar pagos a todas las ventas abiertas del cliente.
                $ventasAbiertas = $this->getAllAbiertasByClienteId($recibo->id_cliente);
                $montoRestante = !isset($venta)?$recibo->monto:$saldoPago; //!!! Tener en cuenta que se van a cerrar ventas q esten abiertas aunq no se asigne el pago
                Log::info('ventas abiertas '.count($ventasAbiertas).' Monto Restante: '.$montoRestante);
                // Proceso similar al anterior para asignar pagos automáticamente a cada venta abierta.
                foreach ($ventasAbiertas as $ventaAbierta) {
                        if($idVenta!=$ventaAbierta->id){//Aplicar a todas las ventas, excepto a la que ya fue ingresada por parametro (ya fue operada)
                            $montoRestanteVenta = $this->getMontoRestante($ventaAbierta->id);
                            $vendedor = $ventaAbierta->vendedor;

                            $montoPagoVenta = min($montoRestanteVenta, $montoRestante);
                            if($montoPagoVenta > 0){
                                Log::info('Monto pago venta: '.$montoPagoVenta);
                                $pago = VentaReciboPago::create([
                                    'id_venta' => $ventaAbierta->id,
                                    'id_recibo' => $recibo->id,
                                    'monto' => $montoPagoVenta,
                                    'created_at' => Carbon::now(),
                                ]);
                                $montoRestante -= $montoPagoVenta;

                                if($pago)
                                    $vPagos->push($pago);
                            }
                            if ($montoPagoVenta >= 0 && ($montoRestanteVenta-$montoPagoVenta)<=0) {
                                try {
                                    $this->setPagada($ventaAbierta->id);
                                    $this->crearComisionParaVenta($ventaAbierta);

                                $vCerradas->push($ventaAbierta);

                                Log::info('Se actualizo el estado de la venta => '.$ventaAbierta->id.' con un pago de $'.$montoPagoVenta);
                                } catch (\Throwable $th) {
                                    Log::alert('No se actualizo el estado de la venta => '.$th->getMessage());
                                }

                            }
                        }
                    }
            return $vCerradas;
        }
        return false;
    }
    /**
    * Obtiene el saldo restante a pagar de una venta_id
    *
    **/
    private function getMontoRestante($idVenta){
        $valorResultante = Venta::where('ventas.id', $idVenta)
                        ->selectRaw('total - IFNULL(SUM(monto), 0) AS valor_resultante')
                        ->leftJoin('ventas_recibos_pagos', 'ventas.id', '=', 'ventas_recibos_pagos.id_venta')
                        ->groupBy('ventas.id', 'total')
                        ->first();

        if ($valorResultante)
            return $valorResultante->valor_resultante;
        else
            return -1;
    }

    public function getPagosByVenta($idVenta){
        $pagos = VentaReciboPago::where('id_venta',$idVenta)->get();
        return $pagos;
    }

    //Calcula la ganancia de una venta al dia de la fecha y retorna el valor acumulado de ganancias totales (puede retornar ganancias negativas)
    static function getGanancia($id){
        $venta = Venta::findOrFail($id);
        $costos = 0;
        $gananciaAcum = 0;

        foreach($venta->detalleVenta as $detalle){
            $producto = $detalle->producto;
            Log::info('Detalle venta: '.json_encode($detalle));
            if(!$producto->no_comisionable){
                $pVentaHist = $detalle->precio + ( $detalle->precio * $detalle->lista_valor / 100);
                $pVentaHist = $pVentaHist - ($pVentaHist * $detalle->descuento_porcentual / 100);
                if($detalle->oferta_aplicada&&$producto->isOffer()&& Carbon::parse($producto->oferta_fecha_desde)->lessThanOrEqualTo(Carbon::parse($venta->created_at))){
                    $pCostoActual = $producto->precio_costo_oferta;
                }else{
                    if($producto->es_dolar == 1){
                        $pCostoActual = $producto->getPrecioPesosConIva();
                    }else{
                        $pCostoActual = $producto->precio_costo;
                    }
                }
                $ganancia = $pVentaHist - $pCostoActual;
                $gananciaAcum = $gananciaAcum + ($ganancia * $detalle->cantidad);
            }

        }
        return $gananciaAcum;
    }

    /**
     *  Creacion y guardado de una nueva operacion comercial
     */
    public function store(Request $request)
    {
        try {
            // Iniciar una transacción de base de datos
            DB::beginTransaction();

            $venta = $this->crearVenta($request);

            // Código para crear detalles de venta y actualizar stock
            $this->crearDetallesVentaYActualizarStock($request, $venta);

            // Código para crear recibo y cuentas corrientes
            if ($this->afectarCC) {
                $this->crearReciboYCuentasCorrientes($request, $venta);
            }

            if($venta->tipoVenta){
                if(strcmp( $venta->tipoVenta->nombre, 'Nota Credito' )==0){
                    $this->generarComision($venta,-1);
                }
            }
            
            DB::commit();

            Log::info('Venta creada correctamente: ' . $venta->id);
            $this->registrarEnLog('success',$venta);

            return $venta;

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            Log::error('Error al crear la venta: ' . $e->getMessage());

            throw new \Exception('Error al crear la venta: ' . $e->getMessage());

            return null;
        }
    }

    private function crearVenta(Request $request)
    {        
        $this->afectarStock;
        $tipoVenta = TipoVenta::find($request->id_tipo_venta);
        Log::info('Tipo venta: '.$tipoVenta->nombre);
        $this->afectarCC = true;
        //En base al tipo de Operación, determino el tipo de afectación sobre el stock
        if(strcmp( $tipoVenta->nombre, 'Presupuesto' )==0 || strcmp( $tipoVenta->nombre, 'Proforma' )==0){
            $this->afectarCC = false;
            $this->afectarStock = 0;
        }elseif(strcmp( $tipoVenta->nombre, 'Venta' )==0 || strcmp( $tipoVenta->nombre, 'Nota Debito' )==0){
            $request['es_cobro'] = 0; //Movimiento negativo al cliente
            $this->afectarStock = -1;
        }elseif(strcmp( $tipoVenta->nombre, 'Nota Credito' )==0){
            $this->afectarStock = 1;
            $request['es_cobro'] = 1; //Movimiento positivo al cliente
        }else{
            return false;
        }
        $cliente = Cliente::findOrFail($request['id_cliente']);
        //Creación de venta
        $operacion = new Venta();
        $operacion->id_usuario = $request->id_usuario;
        $operacion->id_vendedor = $request->id_vendedor;
        $operacion->id_cliente = $cliente->id;
        $operacion->id_tipo_venta = $request->id_tipo_venta;
        $operacion->nombre_cliente = $request->nombre_cliente;
        $operacion->fecha = Carbon::now()->toDateString();
        $operacion->direccion = $request->direccion;
        $operacion->dni_cuit = $request->dni_cuit;
        $operacion->telefono = $cliente->telefono;
        $operacion->email = $request->email;
        $operacion->descuento = $request->descuento;
        $operacion->notas = $request->notas;
        $operacion->total = $request->total;
        $operacion->save();

        return $operacion;
    }

    private function crearDetallesVentaYActualizarStock(Request $request, $venta)
    {
        
        $productos = $request->productos;//Se crea un Array asociativo
        // Obtén los IDs de los productos que necesitas actualizar
        $productoIds = array_column($productos, 'id_producto');
        // Realiza una consulta única para obtener los productos correspondientes a esos IDs
        $productosToUpdate = Producto::whereIn('id', $productoIds)->get();
        $listas = Lista::all();

        // Recorre los productos y crea registros en la tabla 'detalles_ventas'
        foreach ($productos as $producto) {
            try{
                $idProd = $producto['id_producto'];
                $idLista = $producto['id_lista'];
                $prodObject = $productosToUpdate->first(function($prod) use ($idProd){
                    return $prod->id == $idProd;
                });
                $listaObject = $listas->first(function($lista) use ($idLista){
                    return $lista->id == $idLista;
                });
                $detalleVenta = new DetalleVenta();
                $detalleVenta->id_venta = $venta->id;
                $detalleVenta->id_lista = $idLista;
                $detalleVenta->id_producto = $idProd;
                $detalleVenta->cantidad = $producto['cantidad'];
                $detalleVenta->descuento = $producto['descuento'];
                $detalleVenta->descuento_porcentual = $producto['descuento_porcentual'];
                $detalleVenta->precio = $producto['precio'];
                $detalleVenta->oferta_aplicada = $producto['oferta_aplicada'];
                $detalleVenta->iva_valor = is_numeric($prodObject->getTipoIva())?$prodObject->getTipoIva():0;
                $detalleVenta->lista_valor = $listaObject->valor;
                $detalleVenta->save();

                Log::info('Stock a afectar: '.$prodObject->id.' cant: '.$producto['cantidad'].' afectar? '.$this->afectarStock);
                if($this->afectarStock > 0){ //Suma stock
                    Log::info('Suma stock: '.($producto['cantidad']*$this->afectarStock));
                    $prodObject->updateStock($producto['id_producto'], $producto['cantidad']*$this->afectarStock, 'Operacion: '.$venta->tipoVenta->nombre." ".$venta->numeroVenta, $venta->id, Auth::user()->id);
                }elseif($this->afectarStock < 0){ //Baja stock
                    Log::info('Resta stock: '.($producto['cantidad']*$this->afectarStock));
                    $prodObject->updateStock($producto['id_producto'], $producto['cantidad']*$this->afectarStock, 'Operacion: '.$venta->tipoVenta->nombre." ".$venta->numeroVenta, $venta->id, Auth::user()->id);
                }
                        /*
                    //Con este codigo se eliminara de la coleccion el producto que ya fue actualizado para evitar iteraciones recurrentes
                    $idProductoAEliminar = $prod->id;
                    $productosToUpdate = $productosToUpdate->reject(function ($producto) use ($idProductoAEliminar) {
                        return $producto->id === $idProductoAEliminar;
                    });
                    */
               
            } catch (Exception $e) {
                Log::alert('Ha ocurrido un error al guardar un producto en la venta. ERROR: ' . $e->getMessage());
                throw new \Exception('Ha ocurrido un error al guardar un producto en la venta. ERROR: ' . $e->getMessage());
                return false;
            }
        }
        return true;
    }

    private function crearReciboYCuentasCorrientes(Request $request, $venta)
    {
        
        //Recibos y Cuentas Corrientes
        if($this->afectarCC){
            //Creacion de recibo asociado al comprobante - Negativo o Positivo dependiendo del valor de 'es_cobro'
            Log::info('Venta id: '.$venta->id);
            $requestRecibo = new Request();
            $requestRecibo['id_tipo_recibo'] = null;
            $requestRecibo['es_cobro'] = $request['es_cobro'];
            $requestRecibo['detalle'] = 'Comprobante Operacion Nº '.$venta->numero_venta;
            $requestRecibo['monto'] = $venta->total;
            $requestRecibo['id_cliente'] = $venta->id_cliente;
            $requestRecibo['id_venta'] = $venta->id;
            $requestRecibo['id_usuario'] = $venta->id_usuario;
            $this->reciboService->store($requestRecibo);

            Log::info('Recibo creado correctamente: '.json_encode($requestRecibo->all()));
            if($request['pagos']['monto']!=0&&$request['pago']!=0&&$venta->id_tipo_venta==3/*VENTA*/){
                //Si existe un pago
                $requestRecibo = new Request();
                $requestRecibo['id_tipo_recibo'] = 2; //Hardcode venta
                $requestRecibo['detalle'] = 'Comprobante Pago Operacion Nº '.$venta->numero_venta;
                $requestRecibo['monto'] = $request['pagos']['monto'];
                $requestRecibo['id_cliente'] = $venta->id_cliente;
                $requestRecibo['id_venta'] = $venta->id;
                $requestRecibo['id_usuario'] = $venta->id_usuario;
                $requestRecibo['from_venta'] = 1;

                // Metodos de Pago - Copia los valores y las claves de $pagos en $recibo
                foreach ($request['pagos'] as $key => $value) {
                    $requestRecibo->merge([$key => $value]);
                }
                $this->reciboService->store($requestRecibo);
                return $venta;
            }else{
                return $venta;
            }
        }
        return $venta;
    }

    /**
     * Actualiza una operacion
     */
    public function update(Request $request, $id)
    {
        //Primero, tomar los valores ingresados desde el front
        //Eliminar los elementos relacionados para volver a regenerarlos (recibos, stock)
        DB::beginTransaction();
        try {
            $operacion = Venta::findOrFail($id);

            $afectarStock;
            $conversionVenta = false;
            $tipoVenta = TipoVenta::find($request->id_tipo_venta);
            Log::info('Tipo venta: '.$tipoVenta->nombre);

            if($operacion->id_tipo_venta!=$tipoVenta->id&&strcmp( $operacion->tipoVenta->nombre, 'Proforma' )==0){
                //Se esta guardando una conversion de PROFORMA a VENTA
                $conversionVenta = true;
            }

            $afectarCC = true;
            //En base al tipo de Operación, determino el tipo de afectación sobre el stock
            if(strcmp( $tipoVenta->nombre, 'Presupuesto' )==0 || strcmp( $tipoVenta->nombre, 'Proforma' )==0){
                $afectarCC = false;
                $afectarStock = 0;
            }elseif(strcmp( $tipoVenta->nombre, 'Venta' )==0 || strcmp( $tipoVenta->nombre, 'Nota Debito' )==0){
                $request['es_cobro'] = 0; //Movimiento negativo al cliente
                $afectarStock = -1;
            }elseif(strcmp( $tipoVenta->nombre, 'Nota Credito' )==0){
                $afectarStock = 1;
                $request['es_cobro'] = 1; //Movimiento positivo al cliente
            }else{
                return false;
            }
            $cliente = Cliente::findOrFail($request['id_cliente']);
            //Creación de venta
            $operacion->id_tipo_venta = $tipoVenta->id;
            $operacion->id_vendedor = $request->id_vendedor;
            $operacion->id_cliente = $cliente->id;
            //$operacion->id_tipo_venta = $request->id_tipo_venta;
            $operacion->nombre_cliente = $request->nombre_cliente;
            //$operacion->fecha = Carbon::now()->toDateString();
            $operacion->direccion = $request->direccion;
            $operacion->dni_cuit = $request->dni_cuit;
            $operacion->telefono = $cliente->telefono;
            $operacion->email = $request->email;
            //$operacion->descuento = $request->descuento;
            $operacion->notas = $request->notas;
            $operacion->total = $request->total;
            $operacion->save();

            $listas = Lista::all();

            if(!$conversionVenta)//Si antes era una Proforma, no hay afectacion de stock
            {
                //------------Eliminacion de registros anteriores de Productos en DetalleVenta y Recuperacion de stock ---------
                $productosRemoveIds = $operacion->detalleVenta->pluck('id_producto')->toArray();
                $productosToRemove = Producto::whereIn('id', $productosRemoveIds)->get();

                $cant = 0;
                foreach($operacion->detalleVenta as $detalle){
                     foreach ($productosToRemove as $producto) {
                        if($detalle->id_producto == $producto->id){
                            if($afectarStock!=0)
                                Log::info('RestoreStock - cant: ' . $detalle->cantidad.' prod stock: '.$producto->stock);
                                $producto->restoreStock($detalle->cantidad*($afectarStock*-1));
                                $cant++;
                        }
                    }
                }
                Log::info('[Restaurar Stock] Cantidad de productos afectados: ' . $cant);
            }
            $operacion->detalleVenta()->delete();

            ProductoHistorialStock::eliminarByVenta($operacion->id);

            //-----------------FIN BLOQUE ELIMINACION Y RESTAURACION-------------

            $detalleProductos = $request->productos;//Se crea un Array asociativo
            // Obtén los IDs de los productos que necesitas actualizar
            $productoIds = array_column($detalleProductos, 'id_producto');
            // Realiza una consulta única para obtener los productos correspondientes a esos IDs
            $productosToUpdate = Producto::whereIn('id', $productoIds)->get();

            // Recorre los productos y crea registros en la tabla 'detalles_ventas'
            foreach ($detalleProductos as $producto) {
                try{
                    $idProd = $producto['id_producto'];
                    $idLista = $producto['id_lista'];
                    $prodObject = $productosToUpdate->first(function($prod) use ($idProd){
                        return $prod->id == $idProd;
                    });
                    $listaObject = $listas->first(function($lista) use ($idLista){
                        return $lista->id == $idLista;
                    });
                    $detalleVenta = new DetalleVenta();
                    $detalleVenta->id_venta = $operacion->id;
                    $detalleVenta->id_lista = $idLista;
                    $detalleVenta->id_producto = $idProd;
                    $detalleVenta->cantidad = $producto['cantidad'];
                    $detalleVenta->descuento = $producto['descuento'];
                    $detalleVenta->descuento_porcentual = $producto['descuento_porcentual'];
                    $detalleVenta->precio = $producto['precio'];
                    $detalleVenta->oferta_aplicada = $producto['oferta_aplicada'];
                    $detalleVenta->iva_valor = is_numeric($prodObject->getTipoIva())?$prodObject->getTipoIva():0;
                    $detalleVenta->lista_valor = $listaObject->valor;
                    $detalleVenta->save();

                    if($afectarStock > 0){ //Suma stock
                        Log::info('Suma stock: '.($producto['cantidad']*$afectarStock));
                        $prodObject->updateStock($producto['id_producto'], $producto['cantidad']*$afectarStock, 'Operacion: '.$operacion->tipoVenta->nombre." ".$operacion->numeroVenta, $operacion->id, Auth::user()->id);
                    }elseif($afectarStock < 0){ //Baja stock
                        Log::info('Resta stock: '.($producto['cantidad']*$afectarStock));
                        $prodObject->updateStock($producto['id_producto'], $producto['cantidad']*$afectarStock, 'Operacion: '.$operacion->tipoVenta->nombre." ".$operacion->numeroVenta, $operacion->id, Auth::user()->id);
                    }
                } catch (Exception $e) {
                    Log::alert('Ha ocurrido un error al guardar un producto en la venta. ERROR: ' . $e->getMessage());
                    return false;
                }
            }

        Log::info('Venta guardada correctamente: ' . json_encode($request->all()));
        //Recibos y Cuentas Corrientes

        if(!$conversionVenta){
            //Eliminacion de recibo relacionado anterior
            $reciboVenta = Recibo::where([['es_cobro','=',0],['id_venta','=',$operacion->id]])->whereNull('deleted_at')->first();

            if(!is_null($reciboVenta)){
                $recibo = $this->reciboService->delete($reciboVenta->id);

                if(!is_null($recibo)){
                    if($afectarCC){
                        //Creacion de recibo asociado al comprobante - Negativo o Positivo dependiendo del valor de 'es_cobro'
                        $requestRecibo = new Request();
                        $requestRecibo['id_tipo_recibo'] = null;
                        $requestRecibo['es_cobro'] = $request['es_cobro'];
                        $requestRecibo['detalle'] = 'Comprobante Operacion Nº '.$operacion->id;
                        $requestRecibo['monto'] = $operacion->total;
                        $requestRecibo['id_cliente'] = $operacion->id_cliente;
                        $requestRecibo['id_venta'] = $operacion->id;
                        $requestRecibo['id_usuario'] = $operacion->id_usuario;
                        $this->reciboService->store($requestRecibo);
                    }

                    Log::info('Recibo de Venta actualizado correctamente: ' . json_encode($request->all()));
                }
            }
        }else{
             if($afectarCC){
                    //Creacion de recibo asociado al comprobante - Negativo o Positivo dependiendo del valor de 'es_cobro'
                    $requestRecibo = new Request();
                    $requestRecibo['id_tipo_recibo'] = null;
                    $requestRecibo['es_cobro'] = $request['es_cobro'];
                    $requestRecibo['detalle'] = 'Comprobante Operacion Nº '.$operacion->id;
                    $requestRecibo['monto'] = $operacion->total;
                    $requestRecibo['id_cliente'] = $operacion->id_cliente;
                    $requestRecibo['id_venta'] = $operacion->id;
                    $requestRecibo['id_usuario'] = $operacion->id_usuario;
                    $this->reciboService->store($requestRecibo);

                    Log::info('Recibo de Venta creado correctamente: ' . json_encode($request->all()));
            }
        }

        DB::commit();

        $this->registrarEnLog('updated',$operacion);

        return $operacion;

        } catch (\Throwable $th) {
            DB::rollback();
            Log::alert('Ha ocurrido un error al actualizar la venta. ERROR: ' . $th->getMessage());
            return false;
        }

    }

    public function delete($id){
        
        DB::beginTransaction();

        try {
            $operacion = Venta::findOrFail($id);

            $afectarStock;
            $tipoVenta = TipoVenta::find($operacion->id_tipo_venta);
            $afectarCC = true;
            //En base al tipo de Operación, determino el tipo de afectación sobre el stock
            if(strcmp( $tipoVenta->nombre, 'Presupuesto' )==0 || strcmp( $tipoVenta->nombre, 'Proforma' )==0){
                $afectarCC = false;
                $afectarStock = 0;
            }elseif(strcmp( $tipoVenta->nombre, 'Venta' )==0 || strcmp( $tipoVenta->nombre, 'Nota Debito' )==0){
                $afectarStock = -1;
            }elseif(strcmp( $tipoVenta->nombre, 'Nota Credito' )==0){
                $afectarStock = 1;
            }else{
                return false;
            }

            //------------Eliminacion de registros anteriores de Productos en DetalleVenta y Recuperacion de stock ---------
            $productosRemoveIds = $operacion->detalleVenta->pluck('id_producto')->toArray();
            $productosToRemove = Producto::whereIn('id', $productosRemoveIds)->get();

            $cant = 0;
            foreach($operacion->detalleVenta as $detalle){
                foreach ($productosToRemove as $producto) {
                    if($detalle->id_producto == $producto->id){
                        if($afectarStock!=0)
                            Log::info('RestoreStock - cant: ' . $detalle->cantidad.' prod stock: '.$producto->stock);
                            $producto->restoreStock($detalle->cantidad*($afectarStock*-1));
                            $cant++;
                    }
                }
            }
            
            Log::info('Cantidad de productos afectados: ' . $cant);
            $operacion->detalleVenta()->delete();

            ProductoHistorialStock::eliminarByVenta($operacion->id);

            $recibos = Recibo::where('id_venta',$operacion->id)->get();
            foreach($recibos as $recibo){
                try {
                    $this->reciboService->delete($recibo->id);
                } catch (Exception $th) {
                    Log::alert('Ha ocurrido un error al eliminar un recibo de venta. ERROR: ' . $th->getMessage());
                }
            }

            $comisiones = Comision::where([['id_venta',$operacion->id],['estado',0]])->get();
            foreach($comisiones as $comision){
                try {
                    $comision->delete();
                } catch (Exception $th) {
                    Log::alert('Ha ocurrido un error al eliminar una comision. ERROR: ' . $th->getMessage());
                }
            }

            $operacion->delete(); //softdelete

            DB::commit();

            $this->registrarEnLog('deleted',$operacion);

            return $operacion;
        } catch (\Throwable $th) {
            DB::rollback();
            throw new \Exception('Ha ocurrido un error al eliminar la venta. ERROR: ' . $th->getMessage());

        }
        
    }

    private function registrarEnLog($estado, $venta, $msg = null){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo la operacion Nº ".$venta->numero_venta." por el usuario ".Auth::user()->nombre;
                break;
            
            case 'updated':
                $mensaje = "Se actualizo la operacion Nº ".$venta->numero_venta." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino la operacion Nº ".$venta->numero_venta." por el usuario ".Auth::user()->nombre;
                break;
            
            case 'closed':
                $mensaje = "Se cambio el estado de la operacion Nº ".$venta->numero_venta." a cerrada y pagada por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }

}
