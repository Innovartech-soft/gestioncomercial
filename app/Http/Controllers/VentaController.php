<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\VentaService;
use App\Exports\VentaCerradaExport;
use Maatwebsite\Excel\Facades\Excel;

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

    //Rutas testing no funcionales
    public function getGanancia($id){
        return $this->ventaService->getGanancia($id);
    }
}
