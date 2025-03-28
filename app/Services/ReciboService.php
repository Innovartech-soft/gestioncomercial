<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Recibo;
use App\TipoRecibo;
use App\Cliente;
use App\Proveedor;
use App\Comision;
use App\MetodoPago;
use App\ReciboMetodoPago;
use App\VentaReciboPago;
use App\CuentaCorriente;
use App\Cheque;
use App\Venta;
use App\Services\DiarioCajaService;
use App\Services\VentaService;
use App\Logs;
use Auth;
use DB;
use Log;

class ReciboService
{   
    protected $diarioCajaService;
    protected $ventaService;

    public function __construct(DiarioCajaService $diarioCajaService)
    {
        $this->diarioCajaService = $diarioCajaService;
    }

    public function getDependencias(){
        //Obtiene los registros de los modelos frecuentes relacionados con un Recibo
        $tiposRecibos = TipoRecibo::select('id', 'nombre')->get();
        $clientes = Cliente::select('id', 'razon_social')->whereNull('deleted_at')->orderBy('razon_social','ASC')->get();
        $proveedores = Proveedor::select('id', 'nombre')->whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $metodosPago = MetodoPago::select('id', 'nombre')->get();
        $cheques = Cheque::whereNull('deleted_at')->orderBy('fecha_emision','ASC')->get();
        $chequesDisponibles = Cheque::whereNull('deleted_at')->where('estado',1)->orderBy('fecha_emision','ASC')->get();

        return [
            'tiposRecibos'=> $tiposRecibos,
            'clientes' => $clientes,
            'proveedores' => $proveedores,
            'metodosPago' => $metodosPago,
            'cheques' => $cheques,
            'chequesDisponibles' => $chequesDisponibles,
        ];
    }

    public function listAll(){
        $recibos = Recibo::whereNull('deleted_at')->get();
    
        // Ordenar el array de recibos por la columna 'created_at' de forma descendente
        $recibos = $recibos->sortByDesc('created_at');
    
        // Obtener las dependencias
        $data = $this->getDependencias();
        
        $data['recibos'] = $recibos;
    
        return $data;
    }

    public function getById($id){
        $recibo = Recibo::findOrFail($id);
        $data = $this->getDependencias();
        $data['recibo'] = $recibo;
         
        return $data;
    }

    public function searchByFechaHoy(){
        $fechaHoy = Carbon::today();

        $recibos = Recibo::whereNull('deleted_at')
            ->whereDate('created_at', $fechaHoy)
            ->orderBy('id', 'desc')
            ->get();
        return $recibos;
    }
    /**
     *  Obtiene el total sumado de los pagos y cobros realizados en el día de la fecha 
     * 
    */
    public function getTotalesByFechaHoy() {
        $fechaHoy = Carbon::today();

        $total = Recibo::whereNull('deleted_at')
            ->whereDate('created_at', $fechaHoy)
            ->where('afectar_caja',1)
            ->sum(\DB::raw('CASE WHEN es_cobro = 1 THEN monto ELSE -monto END'));

        return $total;
    }
    /**
     *  Obtiene el total sumado de los pagos en efectivo realizados en el día de la fecha 
     * 
    */
    public function getTotalesEfectivoByFechaHoy() {

        $fechaHoy = Carbon::today();
        $totalEfectivo = Recibo::withTrashed()->from('recibos as r')
        ->join('recibos_metodos_pagos as rm', 'r.id', '=', 'rm.id_recibo')
        ->join('metodos_pagos as mp', 'mp.id', '=', 'rm.id_metodo_pago')
        ->whereDate('r.created_at', $fechaHoy)
        ->whereNull('r.deleted_at')
        ->where('r.afectar_caja',1)
        ->where(function($query) {
            $query->where('mp.nombre', 'Efectivo')
                ->orWhere('mp.nombre', 'Cheque');
        })
        ->sum(\DB::raw('CASE WHEN es_cobro = 1 THEN rm.valor ELSE -rm.valor END'));

            return $totalEfectivo;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        Log::info('Contenido Request - Store Recibo: ' . json_encode($request->all()));
        $this->setVentaService($this);
        if($this->diarioCajaService->getEstadoCaja()){//Verifica si la caja se encuentra abierta
        $user = Auth::user();
        $request['id_usuario'] = Auth::id();
        $mensaje = "";
        $tipoRecibo = TipoRecibo::find($request->id_tipo_recibo);
        if($tipoRecibo)//Puede llegar id_tipo_recibo sin valor valido si proviene desde la emisión de una venta
            $request['es_cobro'] = $tipoRecibo->activo_pasivo;
        $request['afectar_caja'] = ($request->afectar_caja=="on"&&$request->input('id_cliente')?0:1); //Si esta activado, no afectar la caja

        $recibo = Recibo::create($request->all());
        //registro el monto en la caja diaria
        if($recibo->afectar_caja!=0)
            $this->diarioCajaService->updateCajaActual($recibo->es_cobro==1?$recibo->monto:$recibo->monto*-1);

        $cliente = Cliente::find($request->input('id_cliente'));
        $request['id_recibo'] = $recibo->id;

        if($tipoRecibo){ //Si no proviene de una venta, asigno los metodos de pago                
            
            $resMP = $this->storeMetodoDePago($request);
            $resCheque = $this->storeCheques($request,$recibo);
            if(!is_null($request->input('id_venta'))&&$tipoRecibo->id==2/*PAGO*/){ //Si el recibo tiene una venta asociada afecto al estado de la misma
                $resVenta = $this->ventaService->updateEstado($recibo->id,
                                                                $recibo->id_venta,
                                                                (isset($request['from_venta'])&&$request['from_venta']==1?1:null));
                if(count($resVenta)>0){
                    $mensaje = 'Las Ventas con ID: '.json_encode($resVenta->pluck('id')).' han sido Cerradas automaticamente.';
                }
            }elseif($tipoRecibo->id==2/*PAGO*/){
                $resVenta = $this->ventaService->updateEstado($recibo->id);
                 if(count($resVenta)>0){
                    $mensaje = 'Las Ventas con ID: '.json_encode($resVenta->pluck('id')).' han sido Cerradas automaticamente.';
                }
            }
            if($resMP){//Si hay un cliente asociado al recibo, se genera movimiento en la cuenta corriente
                if($recibo){
                    if($recibo->cliente){
                        if($tipoRecibo->id==1)//en este IF se realiza un tratamiento especial para el caso de que se le este "pagando" a un cliente
                            $recibo->es_cobro=1;

                        $this->storeCuentaCorriente($recibo);
                    }
                } else{
                    $recibo->delete($recibo->id);
                    return;
                }
            }
        }else{ //Si es una venta afecto a la cc pero no ingreso pagos en este movimiento
            
            $this->storeCuentaCorriente($recibo);
        }
        
        $this->registrarEnLog('success', $recibo->id);
        $recibo->mensaje = $mensaje;
        return $recibo;
        }
        return false;
    }

    public function storeMetodoDePago(Request $request){
        //Genera impacto en la tabla de recibos metodos de pago
        $metodosPago = MetodoPago::all();
        //dd($request);
        try {
            foreach($metodosPago as $metodoPago){
                //NOTA: El campo Cuenta corriente se espera como "Cuenta_Corriente" y los valores como string
                $val = $request[str_replace(' ','_', $metodoPago->nombre)]; //reemplazar los espacios por '_' para comparar el nombre buscado
               
                if($val>0){
                    $aMP = array(
                        'id_metodo_pago' => $metodoPago->id,
                        'id_recibo' => $request->id_recibo,
                        'valor' => $val
                    );
                    
                    ReciboMetodoPago::create($aMP);
                    Log::info('Se ha creado el metodo de pago: ' . $metodoPago->nombre.' monto: '.$val);
                }
            }
            return true;
        } catch (\Throwable $th) {
            Log::alert('Error al crear metodos de pago -'.json_encode($request->all()));
            return false;
        }
    }

    public function storeCuentaCorriente(Recibo $recibo){
        //Genera impacto en la tabla de Cuentas Corrientes
        $cliente = Cliente::find($recibo->id_cliente);
        $cc = new CuentaCorriente;
        $cc->fecha = $recibo->created_at;
        $cc->id_recibo = $recibo->id;
        $cc->id_cliente = $recibo->id_cliente;
        $cc->monto = ($recibo->es_cobro == 1?$recibo->monto:$recibo->monto*-1);
        $cc->saldo = $cc->monto + CuentaCorriente::calcularSaldo($cliente);
        $cc->save();
        if($cc->saldo>=0)
            $cliente->update([
                'estado_cuenta' => 1]);
        else
            $cliente->update([
                'estado_cuenta' => 0]);
        Log::info('Detalles del recibo: ' . json_encode($recibo));
        Log::info('Se ha actualizado la cuenta corriente: ' . $cc);
        $recibo->save();
        
    }

    public function storeCheques(Request $request,Recibo $recibo){
        $chequesSeleccionados = $request->input('cheques_seleccionados');
        if($chequesSeleccionados){
            // Divide la cadena en un array utilizando la coma como delimitador
            $chequesId = explode(',', $chequesSeleccionados);

            // $cheques ahora contiene los cheques individualmente como elementos de un array
            foreach ($chequesId as $chequeId) {
                try {
                    if($recibo->es_cobro==1){
                        Cheque::find($chequeId)->update(['id_recibo' => $recibo->id]);
                        Log::info('Se ha actualizado el cheque: ' . $chequeId);
                    }else{
                        Cheque::find($chequeId)->update([
                        'estado' => 0,'id_recibo' => $recibo->id]);
                        Log::info('Se ha actualizado el estado del cheque: ' . $chequeId);
                    }
                    
                  

                } catch (Exception $e) {
                    Log::alert('No se pudo actualizar el cheque. ERROR: ' . $th->getMessage());
                }
            }
            return true;
        }
        return false;
    }

    public function delete($id)
    {
       try {
            $recibo = Recibo::findOrFail($id);

            if(!$recibo->cliente){//Si no tiene cliente asociado, no tiene Cuenta Corriente relacionada
                $recibo->update([
                    'deleted_at' => Carbon::now()->toDateTimeString()
                ]);
                $cheques = Cheque::where('id_recibo',$recibo->id)->update(['estado' => 1]);//Restauro el estado del cheque
                ReciboMetodoPago::where('id_recibo',$recibo->id)->delete();
                return $recibo;
            }
            //Si tiene un cliente asociado, hay q alterar la cuenta corriente.
            $cliente = $recibo->cliente;
            $CC = CuentaCorriente::where('id_recibo','=',$recibo->id)->get();
            $ccAux1; //Utilizada para guardar una cuenta posterior a la que esta siendo operada
            $ccAux2; //Utilizada para guardar una cuenta anterior a la que esta siendo operada
            $CCId = 0;
            $saldoAnt = 0;
            $flagAux1 = false;
            //Calculo el saldo anterior al recibo que se esta eliminado
            foreach ($CC as $c) {
                if(!$flagAux1){
                    //En el primer registro de C.C consultada, guardo el maximo ID y su saldo
                    $flagAux1 = true;
                    $CCId = $c->id;
                    if($c->monto<0)
                        $saldoAnt = $c->saldo + ($c->monto*-1);
                    else
                        $saldoAnt = $c->saldo - $c->monto;
                }else{
                    //En el proximo registro utilizo el valor de "saldo anterior" para calcular el nuevo saldo
                    if($c->monto<0)
                        $saldoAnt = $saldoAnt + ($c->monto*-1);
                    else
                        $saldoAnt = $saldoAnt - $c->monto;
                }
                $c->deleted_at = Carbon::now()->toDateTimeString();
                $c->save(); // TODO - rework a update
            }
            //Luego de eliminar el registro en la cuenta corriente, elimino el recibo correspondiente
            $recibo->update([
                    'deleted_at' => Carbon::now()->toDateTimeString()
                ]);
            $cheques = Cheque::where('id_recibo',$recibo->id)->update(['id_recibo' => null]);//Desasocio el cheque del recibo eliminado
            ReciboMetodoPago::where('id_recibo',$recibo->id)->delete();

            $ventasRecibo = VentaReciboPago::where('id_recibo', $recibo->id)->get();
            foreach ($ventasRecibo as $registro) {
                if($registro->venta->pagada==1){
                    //Eliminar la comision asociada al cierre de la venta
                    $comisiones = Comision::where('id_venta', $registro->venta->id)->get();
                    foreach($comisiones as $comision){
                        try {
                            $comision->delete();
                        } catch (Exception $th) {
                            Log::alert('Ha ocurrido un error al eliminar una comision. ERROR: ' . $th->getMessage());
                        }
                    }
                }
                
                $registro->venta->update(['pagada' => 0]);
                $registro->delete();
            }

            //Obtengo el listado de las cuentas corrientes del cliente
            $CCCliente = CuentaCorriente::getCuentasByClienteObject($cliente);

            //Actualizo el campo Saldo en todos los movimientos que siguen en la cuenta corriente (movimientos posteriores)
            foreach ($CCCliente as $cc) {
                //solo actualizo el saldo de los movimientos siguientes al eliminado
                if($cc->id>$CCId){
                    $cc->saldo = $saldoAnt + $cc->monto;
                    $saldoAnt = $cc->saldo;
                    $cc->save();
                    $ccAux1 = $cc;
                }else{
                    //En el ultimo loop que ingrese al else, se guarda el CC anterior a la actual en un Auxiliar.
                    $ccAux2 = $cc;
                }
            }

            //Si el cliente tiene cuentas corrientes existentes...
            if(sizeof($CCCliente)>0){
                //Vuelvo a setear el estado de la cuenta del cliente en base al saldo final
                if(!isset($ccAux1)){
                    if($ccAux2->saldo>=0)
                        $cliente->estado_cuenta = 1;
                    else
                        $cliente->estado_cuenta = 0;
                }else{
                    if($ccAux1->saldo>=0)
                        $cliente->estado_cuenta = 1;
                    else
                        $cliente->estado_cuenta = 0;
                }
                
            }else{
                $cliente->estado_cuenta = 1;
            }
            $cliente->save();

            $this->registrarEnLog('deleted', $recibo->id);
            return $recibo;

       } catch (Exception $e) {
            Log::alert('Ha ocurrido un error al eliminar un recibo. ERROR: ' . $th->getMessage());
           return null;
       }
    }

    private function registrarEnLog($estado, $recibo){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo el recibo ".$recibo." por el usuario ".Auth::user()->nombre;
                break;
            
            case 'updated':
                $mensaje = "Se actualizo el recibo ".$recibo." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino el recibo ".$recibo." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }

    public function setVentaService(ReciboService $reciboService) {
        $this->ventaService = new VentaService($reciboService);
    }
}
