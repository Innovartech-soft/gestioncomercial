<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Logs;
use App\Vendedor;
use App\Comision;
// use App\Exports\VendedorExportExcel;
use App\Exports\VendedorExportExcel;
use Maatwebsite\Excel\Facades\Excel;
class VendedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('administrador',['except' => ['index','edit','exportarExcel','registrarEnLog']]);
    }
    public function index()
    {
        $vendedores = Vendedor::all()->where('deleted_at',null);
        return view('pages.vendedor.index',compact('vendedores'));
    }

    public function create()
    {
        return view('pages.vendedor.form');
    }

    public function store(Request $request)
    {
        $vendedor = Vendedor::create([
            'nombre' => $request->nombre,
            'contacto' => $request->contacto,
            'estado' => $request->estado,
            'porcentaje_comision' => $request->porcentaje_comision,
        ]);
        $this->registrarEnLog('success', $request->nombre);
        return redirect()->route('vendedor.index')->with('success','El vendedor '.$request->nombre.' ha sido creado correctamente');
    }

    public function edit($id)
    {
        $vendedor = Vendedor::find($id);
        $comisiones = Comision::where('deleted_at',null)->where('id_vendedor',$id)->get();
        return view('pages.vendedor.form',compact('vendedor','comisiones'));
    }

    public function update(Request $request, $id)
    {
        $vendedor = Vendedor::find($id)->update([
            'nombre' => $request->nombre,
            'contacto' => $request->contacto,
            'estado' => $request->estado,
            'porcentaje_comision' => $request->porcentaje_comision,
        ]);
        $this->registrarEnLog('updated', $request->nombre);
        return redirect()->route('vendedor.index')->with('updated','El vendedor '.$request->nombre.' ha sido actualizado correctamente');
    }

    public function destroy($id)
    {
        $nombreVendedor = Vendedor::find($id)->nombre;
        $vendedor = Vendedor::find($id)->update([
            'deleted_at' => now(),
        ]);
        $this->registrarEnLog('deleted', $nombreVendedor);
        return redirect()->route('vendedor.index')->with('deleted','El vendedor '.$nombreVendedor.' ha sido eliminado correctamente');
    }

    /**
     * Exporta comisiones en excel
     */
    public function exportarExcel(Request $request)
    {
        $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date',
        ], [
            'fecha_desde.required' => 'El campo fecha desde es obligatorio.',
            'fecha_hasta.required' => 'El campo fecha hasta es obligatorio.',
            'fecha_desde.date' => 'El campo fecha desde debe ser una fecha válida.',
            'fecha_hasta.date' => 'El campo fecha hasta debe ser una fecha válida.',
        ]);
        
        return Excel::download(new VendedorExportExcel, $request->fecha_desde.'_'.$request->fecha_hasta.'_'.$request->id_vendedor.'_Comisiones.xlsx');
    }

    private function registrarEnLog($estado, $nombreVendedor){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo el vendedor ".$nombreVendedor." por el usuario ".Auth::user()->nombre;
                break;

            case 'error':
                $mensaje = "No se pudo crear el vendedor ".$nombreVendedor." por el usuario ".Auth::user()->nombre;
                break;

            case 'updated':
                $mensaje = "Se actualizo el vendedor ".$nombreVendedor." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino el vendedor ".$nombreVendedor." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }

}
