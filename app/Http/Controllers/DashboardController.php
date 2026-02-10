<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Parametro;
use App\Recibo;
use App\TipoRecibo;
use App\Cliente;
use App\Proveedor;
use App\MetodoPago;
use App\ReciboMetodoPago;
use App\CuentaCorriente;
use App\Cheque;
use App\Venta;
use App\DiarioCaja;
use Auth;
use DB;
use Log;
use App\Producto;
use App\Services\ReciboService;
use App\Services\DiarioCajaService;
// use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $reciboService; 
    protected $diarioCajaService;

    public function __construct(ReciboService $reciboService,
                                DiarioCajaService $diarioCajaService){
        $this->reciboService = $reciboService;
        $this->diarioCajaService = $diarioCajaService;
    }

    public function index()
    {
        $parametro = Parametro::get();
        $tiposRecibos = TipoRecibo::all();
        $clientes = Cliente::whereNull('deleted_at')->orderBy('razon_social','ASC')->get();
        $proveedores = Proveedor::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $metodosPago = MetodoPago::all();
        $cheques = Cheque::whereNull('deleted_at')->orderBy('fecha_emision','ASC')->orderBy('estado','DESC')->get();
        $chequesDisponibles = Cheque::whereNull('deleted_at')->where('estado',1)->orderBy('fecha_emision','ASC')->get();
        $totalChequesDisponibles = Cheque::where([['deleted_at',null],['estado',1]])->count();
        $totalClientesDeudores = Cliente::where('deleted_at',null)->where('estado_cuenta',0)->count();
        $totalProductosStockMinimo = Producto::where('deleted_at',null)->whereColumn('stock','<=','stock_minimo')->count();
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();
        $cajaDiaria = DiarioCaja::whereDate('fecha', Carbon::today())->orderByDesc('id')->first();
        $estadoCaja = $this->diarioCajaService->getEstadoCaja();
        $totalVentasUltimosSieteDias = DB::table('ventas')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $recibosHoy = $this->reciboService->searchByFechaHoy();
        $totalRecibosHoy = $this->reciboService->getTotalesByFechaHoy();
        $totalRecibosEfectivoHoy = $this->reciboService->getTotalesEfectivoByFechaHoy();
        $totalCajaHoyEfectivo = 0;
        $totalCajaHoy = 0;
        if(isset($cajaDiaria)){
            $totalCajaHoy = $cajaDiaria->caja_apertura + $totalRecibosHoy;
            $totalCajaHoyEfectivo = $cajaDiaria->caja_apertura + $totalRecibosEfectivoHoy;
        }
        Log::info('totalRecibosHoy hoy => '.$totalRecibosHoy.' totalCajaHoy=>'.$totalCajaHoy.' totalRecibosEfectivoHoy => '.$totalRecibosEfectivoHoy.' totalCajaHoyEfectivo=> '.$totalCajaHoyEfectivo);
            
        $data = [
            'parametro' => $parametro,
            'tiposRecibos'=> $tiposRecibos,
            'clientes' => $clientes,
            'proveedores' => $proveedores,
            'metodosPago' => $metodosPago,
            'cheques' => $cheques,
            'chequesDisponibles' => $chequesDisponibles,
            'totalVentasUltimosSieteDias' => $totalVentasUltimosSieteDias,
            'totalChequesDisponibles' => $totalChequesDisponibles,
            'totalClientesDeudores' => $totalClientesDeudores,
            'totalProductosStockMinimo' => $totalProductosStockMinimo,
            'recibosHoy' => $recibosHoy,
            'cajaDiaria' => $cajaDiaria,  
            'estadoCaja' => $estadoCaja,
            'totalRecibosHoy' => $totalRecibosHoy,
            'totalCajaHoy' => $totalCajaHoy,
            'totalCajaHoyEfectivo' => $totalCajaHoyEfectivo,
        ];
        return view('dashboard',$data);
    }
}
