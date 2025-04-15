<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ListaGanancia;
use App\Logs;
use Illuminate\Support\Facades\Auth;

class ListaGananciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listasGanancia = ListaGanancia::all();
        return view('pages.listaganancia.index', compact('listasGanancia'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.listaganancia.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $listaGanancia = ListaGanancia::create([
            'nombre' => $request->nombre,
            'ganancia' => $request->ganancia,
        ]);
        $this->registrarEnLog('success', $request->nombre);
        return redirect()->route('listaganancia.index')->with('success','La lista de ganancia '.$request->nombre.' ha sido creada correctamente');
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
        $listaGanancia = ListaGanancia::find($id);
        return view('pages.listaganancia.form', compact('listaGanancia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $listaGanancia = ListaGanancia::find($id)->update([
            'nombre' => $request->nombre,
            'ganancia' => $request->ganancia,
        ]);
        $this->registrarEnLog('updated', $request->nombre);
        return redirect()->route('listaganancia.index')->with('updated','La lista de ganancia '.$request->nombre.' ha sido actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $nombreLista = ListaGanancia::find($id)->nombre;
        $listaGanancia = ListaGanancia::find($id);
            if(!$listaGanancia->productos()->exists()){
                $listaGanancia->update([
                    'deleted_at' => now(),
                ]);
                $this->registrarEnLog('deleted', $nombreLista);
                return redirect()->route('listaganancia.index')->with('success','La lista de ganancia '.$nombreLista.' ha sido eliminada correctamente');
            }
        return redirect()->route('listaganancia.index')->with('error','No se puede eliminar la lista de ganancia '.$nombreLista.' porque tiene productos asociados');
    }

    private function registrarEnLog($estado, $nombreLista){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo la lista de ganancia ".$nombreLista." por el usuario ".Auth::user()->nombre;
                break;

            case 'error':
                $mensaje = "No se pudo crear la lista de ganancia ".$nombreLista." por el usuario ".Auth::user()->nombre;
                break;

            case 'updated':
                $mensaje = "Se actualizo la lista de ganancia ".$nombreLista." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino la lista de ganancia ".$nombreLista." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }
}
