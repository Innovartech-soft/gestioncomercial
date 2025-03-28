<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;
use Illuminate\Support\Facades\Auth;
use App\Logs;
class ProveedorController extends Controller
{
    //
    public function index()
    {
        $proveedores = Proveedor::all()->where('deleted_at',null);
        return view('pages.proveedor.index',compact('proveedores'));
    }

    public function create()
    {

        return view('pages.proveedor.form');
    }

    public function store(Request $request)
    {
        //validate request inputs

        if(!$request->nombre == null){
            $proveedor = Proveedor::create([
                'nombre' => $request->nombre,
                'cuit' => $request->cuit,
                'contacto' => $request->contacto,
            ]);
            $this->registrarEnLog('success', $request->nombre);
            return redirect()->route('proveedor.index')->with('success','El proveedor '.$request->nombre.' ha sido creado correctamente');
        }
        $this->registrarEnLog('error', $request->nombre);
       return redirect()->route('proveedor.index')->with('error','El proveedor '.$request->nombre.' no ha sido creado');
    }

    public function edit($id)
    {
        $proveedor = Proveedor::find($id);
        return view('pages.proveedor.form', compact('proveedor'));
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::find($id)->update([
            'nombre' => $request->nombre,
            'cuit' => $request->cuit,
            'contacto' => $request->contacto,
        ]);
        $this->registrarEnLog('updated', $request->nombre);
        return redirect()->route('proveedor.index')->with('updated','El proveedor '.$request->nombre.' ha sido actualizado correctamente');
    }

    public function destroy($id)
    {
        $nombreProveedor = Proveedor::find($id)->nombre;
        $proveedor = Proveedor::find($id);
        if(!$proveedor->productos->isNotEmpty()){
        $proveedor->update([
            'deleted_at' => now(),
        ]);
        $this->registrarEnLog('deleted', $nombreProveedor);
        return redirect()->route('proveedor.index')->with('deleted','El proveedor '.$nombreProveedor.' ha sido eliminado correctamente');
        }
        return redirect()->route('proveedor.index')->with('error','No se puede eliminar el proveedor '.$nombreProveedor.' porque tiene producos asociados');

    }


    private function registrarEnLog($estado, $nombreProveedor){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo el proveedor ".$nombreProveedor." por el usuario ".Auth::user()->nombre;
                break;

            case 'error':
                $mensaje = "No se pudo crear el proveedor ".$nombreProveedor." por el usuario ".Auth::user()->nombre;
                break;

            case 'updated':
                $mensaje = "Se actualizo el proveedor ".$nombreProveedor." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino el proveedor ".$nombreProveedor." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }
}
