<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rubro;
use Session;
use App\Logs;
use Illuminate\Http\Response;

class RubroController extends Controller
{

    public function index()
    {
        $rubros = Rubro::all()->where('deleted_at',null);
        return view('pages.rubro.index', compact('rubros'));
    }

    public function create()
    {
        return view('pages.rubro.form');
    }

    public function store(Request $request)
    {
        if(!$request->nombre == null && !$request->acronimo == null){

            //TODO - CHECKEAR QUE ACRONIMO NO ESTE REPETIDO

            $rubro = new Rubro();
            $rubro->nombre = $request->nombre;
            $rubro->acronimo = strtoupper($request->acronimo);
            $rubro->save();

            $this->registrarEnLog('success', $request->nombre);

            return redirect()->route('rubro.index')->with('success','El rubro '.$request->nombre.' ha sido creado correctamente');
        }

        $this->registrarEnLog('error', $request->nombre);

        return redirect()->route('rubro.index')->with('error','El rubro '.$request->nombre.' no ha sido creado');
    }

    public function edit($id)
    {
        $rubro = Rubro::find($id);
        return view('pages.rubro.form', compact('rubro'));
    }

    public function update(Request $request, $id)
    {
        //TODO - CHECKEAR SI EL ACRONIMO CAMBIO, SI LO HIZO, ACTUALIZAR A LOS PRODUCTOS ASOCIADOS
        if(!$request->nombre == null && !$request->acronimo == null){
            $rubro = Rubro::find($id)->update([
                'nombre' => $request->nombre,
                'acronimo' => strtoupper($request->acronimo),
            ]);
        return redirect()->route('rubro.index')->with('updated','El rubro '.$request->nombre.' ha sido actualizado correctamente');
        }

        return redirect()->route('rubro.index')->with('error','El rubro '.$request->nombre.' no ha sido modificado');
    }
    public function getByAcronimoJSON($id, $acronimo)
    {
        try{
            if($id!=0){
                $rubro = Rubro::where('id','!=',$id)->where('acronimo','LIKE',$acronimo)->get();
            }else{
                $rubro = Rubro::where('acronimo',$acronimo)->get();
            }
            if(count($rubro)>0){
                return response()->json([
                    'encontrado' => true,
                ], Response::HTTP_OK);
            }
            return response()->json([
                'encontrado' => false,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'mensaje' => 'No se pudo obtener el rubro. ERROR: ' . $th->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }

    }
    public function destroy($id)
    {
        $nombreRubro = Rubro::find($id)->nombre;
        $rubro = Rubro::find($id);
        if(!$rubro->productos->isNotEmpty()){
        $rubro->update([
            'deleted_at' => now(),
        ]);
        return redirect()->route('rubro.index')->with('deleted','El rubro '.$nombreRubro.' ha sido eliminado correctamente');
        }
                return redirect()->route('rubro.index')->with('error','No se puede eliminar el rubro '.$nombreRubro.' porque tiene productos asociados');

        }

    private function registrarEnLog($estado, $nombreRubro){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo el rubro ".$nombreRubro." por el usuario ".Auth::user()->nombre;
                break;

            case 'error':
                $mensaje = "No se pudo crear el rubro ".$nombreRubro." por el usuario ".Auth::user()->nombre;
                break;

            case 'updated':
                $mensaje = "Se actualizo el rubro ".$nombreRubro." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino el rubro ".$nombreRubro." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }
}
