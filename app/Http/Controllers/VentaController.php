<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\VentaService;
use App\Exports\VentaCerradaExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class VentaController extends Controller
{
    protected $ventaService; 

    public function __construct(VentaService $ventaService){
        $this->ventaService = $ventaService;
        $this->middleware('administrador')->only('destroy');
        /*
        $this->middleware('administrador',['except' => ['index','getReaperturaCaja','indexCerradas','indexAnuladas','setProformaToVenta','getPagosByVentaJSON','exportarVentaCerrada']]);
        */
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->ventaService->listAllAbiertas();
        return view('pages.venta.index',$data);
    }

    /**
     * Display a listing of the resource closed.
     */
    public function indexCerradas()
    {
        $data = $this->ventaService->listAllCerradas();
        return view('pages.venta.indexCerradas',$data);
    }

    public function indexAnuladas()
    {
        $data = $this->ventaService->listAllAnuladas();
        return view('pages.venta.indexAnuladas',$data);
    }

    public function setProformaToVenta($id){
        $data = $this->ventaService->setPresupuestoToVenta($id);
        return redirect()->back()->with('success','El Presupuesto ha sido convertido a Venta correctamente.');
    }

    public function getPagosByVentaJSON($id){
        $data = $this->ventaService->getPagosByVenta($id);
        return response()->json($data);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $data = $this->ventaService->delete($id);

        if($data){
            if (strpos($request->from_view, 'cuentacorriente') !== false) {
                if($data->id_cliente)
                    return redirect()->route('cuentacorriente.indexByClienteReturn', ['id'=>$data->id_cliente])->with('success','El Registro ['.$data->id.'] ha sido eliminado correctamente. '.$data->mensaje);
            }else{
                return redirect()->back()->with('success','El registro ha sido eliminado correctamente.');
            }
        }else{
            return redirect()->back()->with('error','Ha ocurrido un error al eliminar el registro.');
        }
    }

    public function exportarVentaCerrada(){

        return Excel::download(new VentaCerradaExport, now().'Ventas_Cerradas.xlsx');

    }

    public function getDataCerradas(Request $request)
    {
        $ventas = $this->ventaService->getCerradasQuery();

        return DataTables::of($ventas)
            ->addColumn('numero_venta', function ($venta) {
                return str_pad($venta->id, 8, '0', STR_PAD_LEFT);
            })
            ->editColumn('fecha', function ($venta) {
                return $this->formatFecha($venta->fecha);
            })
            ->editColumn('fecha_pago', function ($venta) {
                return $this->formatFecha($venta->fecha_pago);
            })
            ->editColumn('total', function ($venta) {
                return $this->formatMonto($venta->total);
            })
            ->addColumn('cliente', function ($venta) {
                return $venta->nombre_cliente;
            })
            ->addColumn('vendedor', function ($venta) {
                if ($venta->id_vendedor && $venta->vendedor_nombre) {
                    return '<a href="' . route('vendedor.edit', $venta->id_vendedor) . '">' . $venta->vendedor_nombre . '</a>';
                }

                return '-';
            })
            ->addColumn('usuario', function ($venta) {
                return $venta->usuario_nombre ?? '-';
            })
            ->addColumn('acciones', function ($venta) {
                $botones = '';

                if ((int) $venta->id_tipo_venta === 3 && (int) $venta->tiene_pago === 1) {
                    $montoTotal = $this->formatMonto($venta->total);
                    $botones .= '<a href="#" title="Ver Pagos" type="button" class="btn btn-info btn-sm btnPagos" data-bs-toggle="modal" data-bs-target="#formPagos" data-id-venta="' . $venta->id . '" data-total="' . $venta->total . '" data-monto="' . $montoTotal . '"><i class="mdi mdi-cash-clock"></i></a>';
                }

                $botones .= '<form action="' . route('venta.imprimirventa', $venta->id) . '" method="POST" style="display: inline-block;">'
                    . csrf_field()
                    . '<button type="submit" title="Imprimir" class="btn btn-secondary btn-sm"><i class="mdi mdi-printer"></i></button>'
                    . '</form>';

                $botones .= '<a href="#" title="Ver Comprobante" type="button" class="btn btn-data btn-sm btnVerComprobante" data-bs-toggle="modal" data-bs-target="#detalleVenta" data-id-venta="' . $venta->id . '"><i class="mdi mdi-receipt-text-outline"></i></a>';

                $botones .= '<a href="#" title="Ver" id="' . $venta->id . '" onclick="setCookieAndVentaId(event, \'' . e(config('app.cors_allow_origin')) . '\',\'' . $venta->id . '\')" class="btn btn-primary btn-sm"><i class="mdi mdi-pencil"></i></a>';

                return $botones;
            })
            ->filterColumn('numero_venta', function ($query, $keyword) {
                $query->where('ventas.id', 'like', "%{$keyword}%");
            })
            ->filterColumn('vendedor', function ($query, $keyword) {
                $query->where('vendedores.nombre', 'like', "%{$keyword}%");
            })
            ->filterColumn('usuario', function ($query, $keyword) {
                $query->where('usuarios.nombre', 'like', "%{$keyword}%");
            })
            ->rawColumns(['vendedor', 'acciones'])
            ->make(true);
    }

    public function getDataAnuladas(Request $request)
    {
        $ventas = $this->ventaService->getAnuladasQuery();

        return DataTables::of($ventas)
            ->addColumn('numero_venta', function ($venta) {
                return str_pad($venta->id, 8, '0', STR_PAD_LEFT);
            })
            ->editColumn('fecha', function ($venta) {
                return $this->formatFecha($venta->fecha);
            })
            ->editColumn('total', function ($venta) {
                return $this->formatMonto($venta->total);
            })
            ->addColumn('cliente', function ($venta) {
                return $venta->nombre_cliente;
            })
            ->addColumn('vendedor', function ($venta) {
                if ($venta->id_vendedor && $venta->vendedor_nombre) {
                    return '<a href="' . route('vendedor.edit', $venta->id_vendedor) . '">' . $venta->vendedor_nombre . '</a>';
                }

                return '-';
            })
            ->addColumn('usuario', function ($venta) {
                return $venta->usuario_nombre ?? '-';
            })
            ->filterColumn('numero_venta', function ($query, $keyword) {
                $query->where('ventas.id', 'like', "%{$keyword}%");
            })
            ->filterColumn('vendedor', function ($query, $keyword) {
                $query->where('vendedores.nombre', 'like', "%{$keyword}%");
            })
            ->filterColumn('usuario', function ($query, $keyword) {
                $query->where('usuarios.nombre', 'like', "%{$keyword}%");
            })
            ->rawColumns(['vendedor'])
            ->make(true);
    }

    //Rutas testing no funcionales
    public function getGanancia($id){
        return $this->ventaService->getGanancia($id);
    }

    private function formatFecha(?string $fecha): string
    {
        return $fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A';
    }

    private function formatMonto($monto): float
    {
        return round((float) $monto, 1);
    }
}
