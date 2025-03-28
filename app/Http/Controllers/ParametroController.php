<?php

namespace App\Http\Controllers;
use App\Parametro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Logs;
use Log;

class ParametroController extends Controller
{
    public function edit(){
        $parametro = Parametro::first();
        // Log::debug($parametro);
        return view('pages.parametro.form', compact('parametro'));
    }

    public function store(Request $request){
        if ($request->multimoneda == null) {
            $request->multimoneda = 0;
        }else {
            $request->multimoneda = 1;
        }
        $parametro = Parametro::create([
            'nombre_empresa' => $request->nombre_empresa,
            'cuit' => $request->cuit,
            'dir_1' => $request->dir_1,
            'dir_2' => $request->dir_2,
            'tel_1' => $request->tel_1,
            'tel_2' => $request->tel_2,
            'multimoneda' => $request->multimoneda,
            'dolar' => $request->dolar,
        ]);
        $this->registrarEnLog('success', $request->nombre_empresa);
        return redirect()->route('dashboard.index');
    }

    public function update(Request $request){
        Log::debug($request->dolar);
        $dolar =  str_replace(['$', ','], '', $request->dolar);
        $parametro = Parametro::first();
        $parametro->update([
            'nombre_empresa' => $request->nombre_empresa,
            'cuit' => $request->cuit,
            'dir_1' => $request->dir_1,
            'tel_1' => $request->tel_1,
            'tel_2' => $request->tel_2,
            'multimoneda' => $request->multimoneda,
            'dolar' => $request->dolar,
            'dolar' => $dolar,
        ]);
        $this->registrarEnLog('updated', $request->nombre_empresa);
        return redirect()->route('dashboard.index');
    }


    private function registrarEnLog($estado, $nombreParametro){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo el parametro ".$nombreParametro." por el usuario ".Auth::user()->nombre;
                break;
            
            case 'error':
                $mensaje = "No se pudo crear el parametro ".$nombreParametro." por el usuario ".Auth::user()->nombre;
                break;
            
            case 'updated':
                $mensaje = "Se actualizo el parametro ".$nombreParametro." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino el parametro ".$nombreParametro." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }
    
}
