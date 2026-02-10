<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReciboService;
use App\Recibo;
use App\TipoRecibo;
use App\Cliente;
use App\Proveedor;
use App\MetodoPago;
use App\Exports\RecibosExportEgresosExcel;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;


class ReciboController extends Controller
{

    protected ReciboService $reciboService;
    public function __construct(ReciboService $reciboService) {
        $this->reciboService = $reciboService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->reciboService->getDependencias();
        return view('pages.recibo.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->reciboService->store($request);

        if($data){
            if (strpos($request->from_view, 'cuentacorriente') !== false) {
                // Si la solicitud vino desde la ruta "cuentacorriente"
                if($data->id_cliente)
                    return redirect()->route('cuentacorriente.indexByClienteReturn', ['id'=>$data->id_cliente])->with('success','El Recibo ['.$data->id.'] ha sido creado correctamente. '.$data->mensaje);
                else
                    return redirect()->back()->with('success','El Recibo ['.$data->id.'] ha sido creado correctamente. '.$data->mensaje);
            } else {
                return redirect()->back()->with('success','El Recibo ['.$data->id.'] ha sido creado correctamente. '.$data->mensaje);
            }
        }else{
            return redirect()->back()->with('error','Ha ocurrido un error al crear el Recibo');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->reciboService->getById($id);
        $data['show'] = true;
        return view('pages.recibo.form',$data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = $this->reciboService->getById($id);
        return view('pages.recibo.form',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function exportarReciboEgresoExcel(){
        
        return Excel::download(new RecibosExportEgresosExcel, now().'Recibos_Export_Egresos.xlsx');

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $data = $this->reciboService->delete($id);

        if (strpos($request->from_view, 'cuentacorriente') !== false) {
            // Si la solicitud vino desde la ruta "cuentacorriente"
            if($data)
                return redirect()->route('cuentacorriente.indexByClienteReturn', ['id'=>$data->id_cliente])->with('success','El Recibo ['.$data->id.'] ha sido eliminado correctamente');
            else
                return redirect()->route('cuentacorriente.indexByClienteReturn', ['id'=>$data->id_cliente])->with('error','Ha ocurrido un error al eliminar el recibo');
        } else {
            if($data)
                return redirect()->route('recibo.index')->with('success','El Recibo '.$data->id.' ha sido eliminado correctamente.');
            else
                return redirect()->back()->with('error','Ha ocurrido un error al eliminar el recibo');
            
        }

       
    }

    //Genera el listado de recibos para index

    public function getData()
    {
        try {
            \Log::info('ReciboController@getData start');

            $query = Recibo::whereNull('deleted_at')
                ->select([
                    'id',
                    'created_at',
                    'monto',
                    'es_cobro',
                    'id_cliente',
                    'id_proveedor',
                    'id_usuario',
                    'id_tipo_recibo',
                    'id_venta',
                ])
                ->with([
                    'tipoRecibo:id,nombre',
                    'venta:id,id_tipo_venta',
                    'venta.tipoVenta:id,nombre',
                    'metodoPago:id,nombre',
                    'cliente:id,razon_social',
                    'proveedor:id,nombre',
                    'usuario:id,nombre',
                ]);

            return DataTables::eloquent($query)
                ->addColumn('codigo', function ($recibo) {
                    try {
                        return '<a href="'.route('recibo.show', $recibo->id).'">'.$recibo->id.'</a>';
                    } catch (\Throwable $e) {
                        \Log::error('ReciboController@getData codigo error', ['recibo_id' => $recibo->id ?? null, 'error' => $e->getMessage()]);
                        throw $e;
                    }
                })
                ->addColumn('fecha', function ($recibo) {
                    try {
                        return date('d/m/Y', strtotime($recibo->created_at));
                    } catch (\Throwable $e) {
                        \Log::error('ReciboController@getData fecha error', ['recibo_id' => $recibo->id ?? null, 'error' => $e->getMessage()]);
                        throw $e;
                    }
                })
                ->addColumn('tipo', function ($recibo) {
                    try {
                        $tipoRecibo = $recibo->tipoRecibo;
                        $venta = $recibo->venta;
                        $tipoVenta = optional($venta)->tipoVenta;
                        $nombreTipoVenta = optional($tipoVenta)->nombre;

                        if ($tipoRecibo) {
                            return $tipoRecibo->nombre;
                        }

                        if ($venta) {
                            $numeroVenta = $venta->numeroVenta;
                            return $nombreTipoVenta ? "{$nombreTipoVenta} [{$numeroVenta}]" : "Venta #{$numeroVenta}";
                        }

                        return 'Sin venta asociada';
                    } catch (\Throwable $e) {
                        \Log::error('ReciboController@getData tipo error', ['recibo_id' => $recibo->id ?? null, 'error' => $e->getMessage()]);
                        throw $e;
                    }
                })
                ->addColumn('monto', function ($recibo) {
                    try {
                        return '$' . round($recibo->monto, 1);
                    } catch (\Throwable $e) {
                        \Log::error('ReciboController@getData monto error', ['recibo_id' => $recibo->id ?? null, 'error' => $e->getMessage()]);
                        throw $e;
                    }
                })
                ->addColumn('metodo_pago', function ($recibo) {
                    try {
                        return ($recibo->metodoPago ?? collect())->pluck('nombre')->implode('<br>');
                    } catch (\Throwable $e) {
                        \Log::error('ReciboController@getData metodo_pago error', ['recibo_id' => $recibo->id ?? null, 'error' => $e->getMessage()]);
                        throw $e;
                    }
                })
                ->addColumn('cliente', function ($recibo) {
                    try {
                        return optional($recibo->cliente)->codigo . ' ' . optional($recibo->cliente)->razon_social;
                    } catch (\Throwable $e) {
                        \Log::error('ReciboController@getData cliente error', ['recibo_id' => $recibo->id ?? null, 'error' => $e->getMessage()]);
                        throw $e;
                    }
                })
                ->addColumn('proveedor', function ($recibo) {
                    try {
                        return optional($recibo->proveedor)->nombre ?? '-';
                    } catch (\Throwable $e) {
                        \Log::error('ReciboController@getData proveedor error', ['recibo_id' => $recibo->id ?? null, 'error' => $e->getMessage()]);
                        throw $e;
                    }
                })
                ->addColumn('usuario', function ($recibo) {
                    try {
                        return optional($recibo->usuario)->nombre ?? '-';
                    } catch (\Throwable $e) {
                        \Log::error('ReciboController@getData usuario error', ['recibo_id' => $recibo->id ?? null, 'error' => $e->getMessage()]);
                        throw $e;
                    }
                })
                ->addColumn('acciones', function ($recibo) {
                    try {
                        $botones = '<a href="' . route('recibo.show', $recibo->id) . '" class="btn btn-primary btn-sm me-1"><i class="mdi mdi-eye-outline"></i></a>';
                        
                        return $botones;
                    } catch (\Throwable $e) {
                        \Log::error('ReciboController@getData acciones error', ['recibo_id' => $recibo->id ?? null, 'error' => $e->getMessage()]);
                        throw $e;
                    }
                })
                ->rawColumns(['codigo','fecha','tipo','monto','metodo_pago', 'cliente','proveedor','usuario','acciones'])
                ->make(true);
        } catch (\Throwable $e) {
            \Log::error('ReciboController@getData fatal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Error al cargar recibos'], 500);
        }
    }
}
