<?php

namespace App\Http\Controllers;
use App\CuentaCorriente;
use App\TipoRecibo;
use App\Cliente;
use App\Proveedor;
use App\MetodoPago;
use App\Cheque;
use App\Exports\CuentaCorrienteExportExcel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\View\View;


class CuentaCorrienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cuentasCorrientes = CuentaCorriente::whereNull('deleted_at')->orderBy('id','DESC')->paginate(30);
        $tiposRecibos = TipoRecibo::all();
        $clientes = Cliente::whereNull('deleted_at')->orderBy('razon_social','ASC')->get();
        $proveedores = Proveedor::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $metodosPago = MetodoPago::all();
        $cheques = Cheque::whereNull('deleted_at')->orderBy('fecha_emision','ASC')->orderBy('estado','DESC')->get();
        $chequesDisponibles = Cheque::whereNull('deleted_at')->where('estado',1)->orderBy('fecha_emision','ASC')->get();
        $cliente = null;

        return view('pages.cuentacorriente.index',compact('cuentasCorrientes','tiposRecibos','clientes','cliente','proveedores','metodosPago','cheques','chequesDisponibles'));

    }

    public function indexByCliente(Request $request){
        $cuentasCorrientes = CuentaCorriente::where('id_cliente',$request->id_cliente)->whereNull('deleted_at')->get();
        $cuentaCorriente = CuentaCorriente::where('id_cliente', $request->id_cliente)
        ->whereNull('deleted_at')
        ->orderBy('id', 'desc')
        ->first();
        $tiposRecibos = TipoRecibo::all();
        $clientes = Cliente::whereNull('deleted_at')->orderBy('razon_social','ASC')->get();
        $proveedores = Proveedor::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $metodosPago = MetodoPago::all();
        $cheques = Cheque::whereNull('deleted_at')->orderBy('fecha_emision','ASC')->orderBy('estado','DESC')->get();
        $chequesDisponibles = Cheque::whereNull('deleted_at')->where('estado',1)->orderBy('fecha_emision','ASC')->get();
        $cliente = Cliente::find($request->id_cliente);
        return view('pages.cuentacorriente.index',compact('cuentasCorrientes','cuentaCorriente','tiposRecibos','clientes','cliente','proveedores','metodosPago','cheques','chequesDisponibles'));
    }
    //Se utiliza para retonar a una vista que proviene desde un post, pero usando un get
    public function indexByClienteReturn($id){
        $request = new Request(['id_cliente' => $id]);
        return $this->indexByCliente($request);
    }

    public function exportarCuentaCorrienteExcel(Request $request){

        return Excel::download(new CuentaCorrienteExportExcel, now().'CuentaCorriente-Cliente.xlsx');

    }
}
