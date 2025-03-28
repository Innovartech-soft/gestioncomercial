<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\VentaService;
use App\Venta;
use App\TipoVenta;
use App\Cliente;
use App\DetalleVenta;
use App\Producto;
use App\CuentaCorriente;
use Log;
use Auth;
use App\Logs;
use App\Parametro;
// use App\Venta;

class VentaApiController extends Controller
{
    protected $ventaService;

    public function __construct(VentaService $ventaService){
        $this->ventaService = $ventaService;
    }

    public function getTipoVentas(){
        try {
              $tipoVentas = TipoVenta::all();

        return response()->json(
             $tipoVentas->toArray()

        , Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Lista de tipo de ventas no encontrada',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }

    }

    public function storeVenta(Request $request)
    {
    try{
        if (
            $request->has('tipo') &&
            $request->has('idVendedor') &&
            $request->has('cliente') &&
            $request->has('productos') &&
            count($request->input('productos')) > 0
        ) {
            // Obtención de valores generales
            $pagoArray = json_decode(json_encode($request->input('pago')), true);
            $request->merge([
                'direccion' => $request->input('cliente.direccion', ''),
                'dni_cuit' => $request->input('cliente.cuit', ''),
                'telefono' => $request->input('cliente.telefono1', ''),
                'email' => $request->input('cliente.email', ''),
                'total' => $request->input('total'),
                'nombre_cliente' => $request->input('cliente.razonSocial'),
                'notas' => $request->input('notas', ''),
                'pagada' => $request->input('pagada', null),
                'id_usuario' => Auth()->user()->id,
                'id_cliente' => $request->input('cliente.id'),
                'id_vendedor' => $request->input('idVendedor'),
                'id_tipo_venta' => $request->input('tipo'),
                'pago' => is_array($pagoArray) ? $request->input('pago') : 0,
            ]);

            // Obtención de listado de productos
            $productos = [];
            foreach ($request->input('productos') as $producto) {
                $newProducto = [
                    'id_lista' => $producto['idLista'],
                    'id_producto' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'descuento' => $producto['descuentoNominal'],
                    'descuento_porcentual' => $producto['descuentoPorcentual'],
                    'precio' => $producto['precioUnitario'],
                    'oferta_aplicada' => $producto['ofertaAplicada'],
                ];
                $productos[] = $newProducto;
            }

            $request->merge([
                'productos' => $productos,
            ]);

            // Obtención de listado de Métodos de Pago
            $pagos = [];
            if (count($pagoArray) > 0 && $request->has('pago')) {
                $pagos['Cuenta_Corriente'] = $request->input('pago.cuentaCorriente', null);
                $pagos['Efectivo'] = strval($request->input('pago.efectivo', null));
                $pagos['Tarjeta'] = strval($request->input('pago.tarjeta', null));
                $pagos['Otro'] = strval($request->input('pago.otro', null));
                $pagos['Cheque'] = strval($request->input('pago.cheque', null));
                $pagos['chequeId'] = $request->input('pago.chequeIds', null);
                $pagos['monto'] = $request->input('pago.totalPago', null);
                $request->merge([
                    'pagos' => $pagos,
                ]);
            }

            if ($request->has('idVenta')) {
                // Actualizar la venta
                $data = $this->ventaService->update($request, $request->input('idVenta'));
                if($data)
                    $this->registrarEnLog('updated', $data);

            } else {
                // Crear la venta
                $data = $this->ventaService->store($request);
                if($data)
                    $this->registrarEnLog('success', $data);
            }

            Log::info('Operación Concretada - id: ' . $data->id);

            return response()->json([
                'message' => 'Venta creada con éxito',
                'data' => $data?$data->id:$request->input('idVenta'),
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Faltan datos para crear una venta',
                'data' => $request->all(),
            ], Response::HTTP_NOT_FOUND);
        }
    } catch (\Throwable $th)
    {
        return response()->json([

            'message' => 'Ocurrió un error al generar una venta',
            'data' => $th->getMessage()
        ], Response::HTTP_NOT_FOUND);
    }
    }


    public function exportVentaPdf($id){

        $idVenta = $id;
        Log::info('ID Venta: ' . $idVenta);
        $venta = $this->ventaService->getById($idVenta);
        $parametro = Parametro::first()->orderBy('id', 'desc')->get();
        $detalleVentaArray = [];

        foreach ($venta->detalleVenta as $detalle) {
            $detalleArray = $detalle->toArray();
            $detalleArray['nombre'] = $detalle->producto->nombre;
            $detalleArray['precio'] = $detalle->producto->precio_venta_final_impresion;
            $detalleArray['codigo'] = $detalle->producto->codigo_interno;
            $detalleArray['precio_sin_descuento'] = $detalle->producto->precio_sin_descuento;
            $detalleArray['subtotal'] = ($detalle->producto->precio_venta_final_impresion * $detalle->cantidad) - $detalle->descuento; //Corregir este calculo (no incluye los descuentos)
            $detalleVentaArray[] = $detalleArray;
        }
        $buyer = new Collection([
            'nombre' => $venta->cliente->razon_social,
            'codigo' => $venta->cliente->codigo,
            'cuit'=> $venta->cliente->dni_cuit,
            'direccion' => $venta->cliente->direccion,
            'telefono' => $venta->cliente->tel_1,
            'notas' => $venta->cliente->notas,
        ]);

        $seller = new Collection([
            'nombre' => $parametro[0]->nombre_empresa,
            'vendedor' => $venta->vendedor->nombre,
            'cuit'=> $parametro[0]->cuit,
            'direccion' => $parametro[0]->dir_1,
            'telefono' => $parametro[0]->tel_1,
        ]);

        $data =  Array(
            'buyer' => $buyer,
            'seller' => $seller,
            'modificado' => $venta->wasModified(),
            'invoice_number' => $venta->numero_venta,
            'comprobante_tipo' => $venta->tipoVenta->nombre,
            'comprobante_tipo_id' => $venta->id_tipo_venta,
            'invoice_date' => '2020-10-10',
            'due_date' => '2020-10-10',
            'product' => $detalleVentaArray, //Si esto no anda, hacerle un to_array
            'subtotal' => round($this->ventaService->getTotalSinDescuento($venta),1),
            'discount' =>$venta->descuento,
            'tax' => '0',
            'total' => $venta->id_tipo_venta==4?round($venta->total,1)*-1:round($venta->total,1), //Si es Nota de Credito (4) lo paso a negativo
            'paid' => $venta->pagada,
            'balance' => '300',
            'notas'=> $venta->notas,
            'created_at' => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $venta->created_at)->format('d/m/Y'),
            'total_descuento' => round($this->ventaService->getTotalSinDescuento($venta) - $venta->total,1),
        );

        $pdf = PDF::loadView('invoice_pdf', compact('data'));

        return  $pdf->download('Venta'.now().'.pdf');
    }

    public function getVenta($id){
        try
        {
            $venta = $this->ventaService->getById($id);
            $datosVentaFrontend = [
                'tipo' => $venta->id_tipo_venta,
                'idVendedor' => $venta->id_vendedor,
                'pagada' => $venta->pagada,
                'cliente' => [
                    'id' => $venta->cliente->id,
                    'direccion' => $venta->direccion,
                    'dni_cuit' => $venta->dni_cuit,
                    'tel_1' => $venta->telefono,
                    'email' => $venta->email,
                ],
                'productos' => [],
                'total' => $venta->total,
                'created_at' => $venta->created_at,
                'updated_at' => $venta->updated_at,
            ];

            foreach ($venta->detalleVenta as $detalle) {
                $datosProducto = [
                    'idLista' => $detalle->id_lista,
                    'id' => $detalle->id_producto,
                    'cantidad' => $detalle->cantidad,
                    'descuentoNominal' => $detalle->descuento,
                    'descuentoPorcentual' => $detalle->descuento_porcentual,
                    'precioUnitario' => $detalle->producto->precio_venta_final,
                    'ofertaAplicada' => $detalle->oferta_aplicada,
                ];
                $datosVentaFrontend['productos'][] = $datosProducto;
            }

            // Si tienes datos de pago relacionados en $venta->pago, puedes agregarlos
            if (!empty($venta->pago)) {
                $datosVentaFrontend['pago'] = [
                    'cuentaCorriente' => $venta->pago->Cuenta_Corriente,
                    'efectivo' => $venta->pago->Efectivo,
                    'tarjeta' => $venta->pago->Tarjeta,
                    'otro' => $venta->pago->Otro,
                    'cheque' => $venta->pago->Cheque,
                    'chequeIds' => '',//$venta->pago->chequeId,
                    'totalPago' => $venta->pago->monto,
                ];
            }

            return response()->json(
                    $datosVentaFrontend

            , Response::HTTP_OK);

        } catch (\Throwable $th)
        {
            return response()->json([

                'message' => 'Venta no encontrada',
                'data' => $th->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getVentasByCliente($id)
    {
        $ventas = $this->ventaService->getByClienteId($id);

        $saldo = CuentaCorriente::calcularSaldo(Cliente::findOrFail($id));

        $ventasArray = $ventas->map(function ($venta) {
            $venta->numero_venta = $venta->numeroVenta;
            return $venta;
        });

        $responseArray = [
            'ventas' => $ventasArray->toArray(),
            'saldo' => round($saldo,1),
        ];
        return response()->json($responseArray);
    }

    private function registrarEnLog($estado, Venta $venta){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo la Operacion Nº ".$venta->numero_venta." por el usuario ".Auth::user()->nombre;
                break;

            case 'updated':
                $mensaje = "Se modificó la Operacion Nº ".$venta->numero_venta." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se eliminó la Operacion Nº ".$venta->numero_venta." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }
}
