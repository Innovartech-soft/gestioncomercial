<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Marca;
use Illuminate\Support\Facades\Auth;
use App\Logs;
use App\Http\Controllers\ImpresionDeFacturaController;

class MarcaController extends Controller
{
    //
    public function index()
    {
        // $imprmirFactura = new ImpresionDeFacturaController();
        // $imprmirFactura->descargarFactura();

        $marcas = Marca::all()->where('deleted_at',null);
        return view('pages.marca.index',compact('marcas'));
    }

    public function create()
    {
        return view('pages.marca.form');
    }

    public function store(Request $request)
    {
        if(!$request->nombre == null){
            $marca = new Marca();
            $marca->nombre = $request->nombre;
            $marca->save();

            $this->registrarEnLog('success', $request->nombre);
            return redirect()->route('marca.index')->with('success','La marca '.$request->nombre.' ha sido creado correctamente');
        }
        $this->registrarEnLog('error', $request->nombre);
        return redirect()->route('marca.index')->with('error','La marca '.$request->nombre.' no ha sido creado');
    }

    public function edit($id)
    {
        $marca = Marca::find($id);

        return view('pages.marca.form', compact('marca'));
    }

    public function update(Request $request, $id)
    {
        $marca = Marca::find($id)->update([
            'nombre' => $request->nombre
        ]);

        $this->registrarEnLog('updated', $request->nombre);
        return redirect()->route('marca.index')->with('updated','La marca '.$request->nombre.' ha sido actualizada correctamente');
    }

    public function destroy($id)
    {
        $nombreMarca = Marca::find($id)->nombre;
        $marca = Marca::find($id);
        if(!$marca->productos->isNotEmpty()) {
            $marca->update([
                'deleted_at' => now(),
            ]);
            $this->registrarEnLog('deleted', $nombreMarca);
            return redirect()->route('marca.index')->with('deleted', 'La marca ' . $nombreMarca . ' ha sido eliminada correctamente');
        }
        return redirect()->route('marca.index')->with('error','No se puede eliminar la marca '.$nombreMarca.' porque tiene productos asociados');

    }


    private function registrarEnLog($estado, $nombreMarca){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo la marca ".$nombreMarca." por el usuario ".Auth::user()->nombre;
                break;

            case 'error':
                $mensaje = "No se pudo crear la marca ".$nombreMarca." por el usuario ".Auth::user()->nombre;
                break;

            case 'updated':
                $mensaje = "Se actualizo la marca ".$nombreMarca." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino la marca ".$nombreMarca." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }

}
