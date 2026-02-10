<?php

namespace App\Http\Controllers;

use App\Repartidor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Logs;

class RepartidorController extends Controller
{
    public function index()
    {
        $repartidores = Repartidor::all();
        return view('pages.repartidor.index', compact('repartidores'));
    }

    public function create()
    {
        return view('pages.repartidor.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:120',
            'contacto' => 'nullable|string',
            'disponible' => 'boolean',
            'notas' => 'nullable|string',
        ]);

        Repartidor::create($request->all());

        return redirect()->route('repartidor.index')->with('success', 'Repartidor creado correctamente.');
    }

    public function edit($id)
    {
        $repartidor = Repartidor::find($id);
        return view('pages.repartidor.form', compact('repartidor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:120',
            'contacto' => 'nullable|string',
            'notas' => 'nullable|string',
        ]);

        $repartidor = Repartidor::findOrFail($id)->update([
            'nombre' => $request->nombre,
            'contacto' => $request->contacto,
            'disponible' => $request->disponible,
            'notas' => $request->notas,
        ]);        

        return redirect()->route('repartidor.index')->with('success', 'Repartidor actualizado correctamente.');
    }

    public function destroy($id)
    {
        $nombreRepartidor = Repartidor::find($id)->nombre;
        $repartidor = Repartidor::find($id);
            if(!$repartidor->viajes()->exists()){
                $repartidor->delete();
                //$this->registrarEnLog('deleted', $nombreRepartidor);
                return redirect()->route('repartidor.index')->with('success','El Repartidor '.$nombreRepartidor.' ha sido eliminado correctamente');
            }
        return redirect()->route('repartidor.index')->with('error','No se puede eliminar el repartidor '.$nombreRepartidor.' porque tiene viajes asociados');
    }
}
