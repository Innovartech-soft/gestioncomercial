<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cheque;
use App\Cliente;
use App\Recibo;
use Illuminate\Support\Facades\Auth;
use App\Logs;
use Carbon\Carbon;
class ChequeController extends Controller
{
    public function index (){
        $cheques = Cheque::whereNull('deleted_at')->orderBy('estado','DESC')->paginate(20);
        $clientes = Cliente::all();
        return view('pages.cheque.index',compact('cheques','clientes'));
    }

    public function create (){
        $clientes = Cliente::all();
        $recibos = Recibo::all();
        return view('pages.cheque.form',compact('clientes','recibos'));
    }

    public function store(Request $request)
    {
        try {
            if($request->input('fecha_emision')){
                $request['fecha_emision'] = Carbon::createFromFormat('d-m-Y', $request->input('fecha_emision'));
            }
            
            $cheque = Cheque::create($request->all());
            $this->registrarEnLog('success', $cheque->serie, $cheque->numero);
            return redirect()->route('cheque.index')->with('success', 'El Cheque Nº ' . $cheque->serie . '-' . $cheque->numero . ' ha sido creado correctamente.');
            
        } catch (\Exception $e) {
            return redirect()->route('cheque.index')->with('error', 'Hubo un problema al crear el cheque.'.$e->getMessage() );
        }
    }

    public function cambiarEstado($id){
        $cheque = Cheque::findOrFail($id);
        if($cheque->estado==1){
            $cheque->update(['estado' => 0]);
            return true;
        }
        return false;
    }

    public function getDisponibles($paginado=null){
        if($paginado)
            return Cheque::whereNull('deleted_at')->andWhere('estado',1)->get();
        else
            return Cheque::whereNull('deleted_at')->andWhere('estado',1)->paginate(20);

    }

    public function edit ($id){
        $cheque = Cheque::find($id);
        $clientes = Cliente::all();
        $recibos = Recibo::all()->where('id_cliente',$cheque->id_cliente);

        return view('pages.cheque.form',compact('cheque','clientes','recibos'));
    }

    public function update(Request $request, $id)
    {
        try {
            $cheque = Cheque::find($id);
            if (!$cheque) {
                $this->registrarEnLog('error',null,null);
                return redirect()->route('cheque.index')->with('error', 'No se encontró el cheque con el ID proporcionado.');
            }

            $cheque->update($request->all());
            $this->registrarEnLog('updated', $cheque->serie, $cheque->numero);
            return redirect()->route('cheque.index')->with('success', 'El Cheque Nº ' . $cheque->serie . '-' . $cheque->numero . ' ha sido modificado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('cheque.index')->with('error', 'Hubo un problema al modificar el cheque ' );
        }
    }

    public function destroy ($id){
        $cheque = Cheque::find($id);
        $cheque->update(['deleted_at' =>  Now()]);
        $this->registrarEnLog('deleted', $cheque->serie,$cheque->numero);
        return redirect()->route('cheque.index')->with('success','El Cheque Nº '.$cheque->serie.'-'.$cheque->numero.' ha sido eliminado correctamente.');
    }

    private function registrarEnLog($estado, $serie, $numero){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo el cheque serie N° ".$serie."-".$numero." por el usuario ".Auth::user()->nombre;
                break;

            case 'error':
                $mensaje = "No se pudo crear el cheque ".$serie."-".$numero." por el usuario ".Auth::user()->nombre;
                break;

            case 'updated':
                $mensaje = "Se actualizo el cheque serie N° ".$serie."-".$numero." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino el cheque serie N° ".$serie."-".$numero." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }
}
