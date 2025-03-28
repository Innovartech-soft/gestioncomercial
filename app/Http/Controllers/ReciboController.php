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
        $recibos = Recibo::whereNull('deleted_at')->with([
            'tipoRecibo', // Solo selecciona los campos necesarios
            'venta',
            'venta.tipoVenta:id,nombre', // Solo selecciona los campos necesarios
            'metodoPago', // Solo selecciona los campos necesarios
            'cliente',
            'proveedor',
            'usuario'
        ])->get();
        
        return DataTables::of($recibos)
            ->addColumn('codigo', function ($recibo) {
                return '<a href="'.route('recibo.show', $recibo->id).'">'.$recibo->id.'</a>';
            })
            ->addColumn('fecha', function ($recibo) {
                return date('d/m/Y', strtotime($recibo->created_at));
            })
            ->addColumn('tipo', function ($recibo) {
                $tipoRecibo = $recibo->tipoRecibo;
                $venta = $recibo->venta;
                $tipoVenta = optional($venta)->tipoVenta;

                return $tipoRecibo ? $tipoRecibo->nombre : ($venta ? "{$tipoVenta->nombre} [{$venta->numero_venta}]" : 'Sin venta asociada');
            })
            ->addColumn('monto', function ($recibo) {
                return '$' . round($recibo->monto, 1);
            })
            ->addColumn('metodo_pago', function ($recibo) {
                return $recibo->metodoPago->pluck('nombre')->implode('<br>');
            })
            ->addColumn('cliente', function ($recibo) {
                return optional($recibo->cliente)->codigo . ' ' . optional($recibo->cliente)->razon_social;
            })
            ->addColumn('proveedor', function ($recibo) {
                return optional($recibo->proveedor)->nombre ?? '-';
            })
            ->addColumn('usuario', function ($recibo) {
                return $recibo->usuario->nombre;
            })
            ->addColumn('acciones', function ($recibo) {
                $botones = '<a href="' . route('recibo.show', $recibo->id) . '" class="btn btn-primary btn-sm me-1"><i class="mdi mdi-eye-outline"></i></a>';
                if ((!$recibo->venta || $recibo->es_cobro == 1) && !in_array(optional(optional($recibo->venta)->tipoVenta)->id, [4, 5])) {
                    $botones .= '<form onsubmit="eliminarAlert(event)" action="' . route('recibo.destroy', $recibo->id) . '" method="POST" style="display: inline-block;">' .
                                csrf_field() .
                                method_field('DELETE') .
                                '<button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>' .
                                '</form>';
                }
                return $botones;
            })
            ->rawColumns(['codigo','fecha','tipo','monto','metodo_pago', 'cliente','proveedor','usuario','acciones'])
            ->make(true);
    }
}
