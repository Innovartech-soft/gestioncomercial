<?php

namespace App\Http\Controllers;
use App\Lista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Logs;
class ListaController extends Controller
{
    //
    public function index()
    {
        $listas = Lista::all()->where('deleted_at', null);
        return view('pages.lista.index', compact('listas'));
    }

    public function create(Request $request)
    {
        return view('pages.lista.form');
    }

    public function store(Request $request)
    {
        $lista = Lista::create([
            'nombre' => $request->nombre,
            'valor' => $request->valor,
        ]);
        $this->registrarEnLog('success', $request->nombre);
        return redirect()->route('lista.index')->with('success','La lista '.$request->nombre.' ha sido creada correctamente');
    }

    public function destroy($id)
    {
        $nombreLista = Lista::find($id)->nombre;
        $lista = Lista::find($id);
            if(!$lista->clientes()->exists()){
                $lista->update([
                    'deleted_at' => now(),
                ]);
                $this->registrarEnLog('deleted', $nombreLista);
                return redirect()->route('lista.index')->with('success','La lista '.$nombreLista.' ha sido eliminada correctamente');
            }
        return redirect()->route('lista.index')->with('error','No se puede eliminar la lista '.$nombreLista.' porque tiene clientes asociados');
    }

    public function edit($id)
    {
        $lista = Lista::find($id);
        return view('pages.lista.form', compact('lista'));
    }

    public function update(Request $request, $id)
    {
       $lista = Lista::find($id)->update([
            'nombre' => $request->nombre,
            'valor' => $request->valor,
        ]);
        $this->registrarEnLog('updated', $request->nombre);
        return redirect()->route('lista.index')->with('updated','La lista '.$request->nombre.' ha sido actualizada correctamente');
    }

    private function registrarEnLog($estado, $nombreLista){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo la lista ".$nombreLista." por el usuario ".Auth::user()->nombre;
                break;

            case 'error':
                $mensaje = "No se pudo crear la lista ".$nombreLista." por el usuario ".Auth::user()->nombre;
                break;

            case 'updated':
                $mensaje = "Se actualizo la lista ".$nombreLista." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino la lista ".$nombreLista." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }
}
