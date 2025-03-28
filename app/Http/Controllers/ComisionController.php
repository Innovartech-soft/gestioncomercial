<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Response;
use App\Services\VentaService;
use App\Comision;
use App\Venta;
use App\Vendedor;
use Auth;
use DB;
use Log;
use App\Logs;

class ComisionController extends Controller
{
    protected $ventaService;

    public function __construct()
    {
        $this->middleware('administrador');
        $this->ventaService = new VentaService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $venta = Venta::with('vendedor')->findOrFail($request->id_venta);

        // Calcular la ganancia y la comisión
        $porcentaje_comision = $venta->vendedor->porcentaje_comision;
        $ganancia = VentaService::getGanancia($venta->id);
        $monto_comision = ($ganancia * $porcentaje_comision )/ 100;
        $comision = Comision::create([
            'id_venta' => $venta->id,
            'id_vendedor' => $venta->id_vendedor,
            'monto' => round($monto_comision,1),
            'fecha' => Carbon::now(),
            'ganancia_venta' => round($ganancia,1),
        ]);
        $this->ventaService->setPagada($venta->id);
        
        $this->registrarEnLog('success', $comision);
        return redirect()->back()->with('success','La Venta '.$venta->numero_venta.' ha sido Pagada y Cerrada. La comisión para el vendedor '.$comision->vendedor->nombre.' ha sido asignada correctamente.');
    }

    public function getComisionesJSON($id,$fechaDesde, $fechaHasta)
    {
        try{
            $totalGanancia = 0;
            $comisiones = Comision::with('venta')
                ->where('id_vendedor', $id)
                ->whereNotNull('id_venta')
                ->whereDate('created_at', '>=', $fechaDesde)
                ->whereDate('created_at', '<=', $fechaHasta)
                ->where('estado', 0)
                ->orderBy('id', 'DESC')
                ->get()
                ->map(function ($comision) use (&$totalGanancia) {
                    if ($comision->venta) {
                        $comision->ganancia = VentaService::getGanancia($comision->venta->id);
                        $comision->numero_venta = $comision->venta->numeroVenta;
                        $totalGanancia += $comision->ganancia;
                    
                    }
                    return $comision;
                });

                
            return response()->json([
                'comisiones' => $comisiones->toArray(),
                'total_ganancia' => round($totalGanancia,1),
            ], Response::HTTP_OK);

        } catch (\Throwable $th) 
            {
                return response()->json([
                    'message' => $th->getMessage(),
                    'comisiones' => null
                ], Response::HTTP_NOT_FOUND);
            }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $comision = Comision::findOrFail($id);

        $this->registrarEnLog('payed', $comision);

        $comision->update(['estado'=>'1','notas'=>$request->notas]);

        return redirect()->back()->with('success','La Comisión ha sido pagada correctamente.');
    }

    public function pagarComisiones(Request $request)
{
    // Verificar si se han seleccionado comisiones
    if(!$request->filled('idsComisiones') || empty($request->input('idsComisiones'))) {
        return redirect()->back()->with('error', 'No se ha seleccionado ninguna comisión para pagar.');
    }

    $idsComisiones = explode(',', $request->input('idsComisiones'));
    
    if(!$request->filled('fecha_desde') || !$request->filled('fecha_hasta')) {
        return redirect()->back()->with('error','No se ha podido procesar la solicitud por falta o invalidez de datos de ingreso.');
    }

    foreach ($idsComisiones as $idComision) {
        Comision::findOrFail($idComision)->update([
            'estado' => '1',
            'notas' => $request->input('notas'),
            'periodo' => $request->input('fecha_desde') . ' al ' . $request->input('fecha_hasta')
        ]);
    }

    $this->registrarEnLog('custom', null, 'Las Comisiones Nº [' . $request->input('idsComisiones') . '] han sido pagadas correctamente');
    
    return redirect()->back()->with('success', 'Las ' . count($idsComisiones) . ' comisiones han sido pagadas correctamente.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function registrarEnLog($estado, $comision = null, $msg = null){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo la Comisión de $".$comision->monto." para el Vendedor ".$comision->vendedor->nombre." por el usuario ".Auth::user()->nombre;
                break;

            case 'updated':
                $mensaje = "Se modificó la Comisión de $".$comision->monto." para el Vendedor ".$comision->vendedor->nombre." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se eliminó la Comisión de $".$comision->monto." para el Vendedor ".$comision->vendedor->nombre." por el usuario ".Auth::user()->nombre;
                break;

            case 'payed':
                $mensaje = "La Comisión Nº ".$comision->id." para el Vendedor ".$comision->vendedor->nombre." ha sido pagada correctamente por el usuario ".Auth::user()->nombre;
                break;
            case 'custom':
                $mensaje = $msg . ' - Usuario '.Auth::user()->nombre;;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }
}
