<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cliente;
use App\Lista;
use App\Vendedor;
use App\Logs;



class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::whereNull('deleted_at')->get();
        return view('pages.cliente.index',compact('clientes'));
    }

    public function create()
    {
        $listas = Lista::whereNull('deleted_at')->get();
        $categoriasIva = Cliente::CATEGORIA_IVA;
        $vendedores = Vendedor::all()->where('deleted_at',null);

        return view('pages.cliente.form',compact('listas','vendedores','categoriasIva'));
    }

    public function store(Request $request)
    {
        $cliente = Cliente::create([
            'razon_social' => $request->razon_social,
            'email' => $request->email,
            'tel_1' => $request->tel_1,
            'tel_2' => $request->tel_2,
            'notas' => $request->notas,
            'dni_cuit' => $request->dni_cuit,
            'direccion' => $request->direccion,
            'id_vendedor' => $request->id_vendedor,
            'id_lista' => $request->id_lista,
            'categoria_iva' => $request->id_categoria_iva,
        ]);
        $this->registrarEnLog('success', $request->nombre);
        return redirect()->route('cliente.index')->with('success','El cliente '.$request->nombre.' ha sido creado correctamente');
    }

    public function edit($id)
    {
        $cliente = Cliente::find($id);
        $listas = Lista::whereNull('deleted_at')->get();
        $categoriasIva = Cliente::CATEGORIA_IVA;
        $vendedores = Vendedor::all()->where('deleted_at',null);
        return view('pages.cliente.form', compact('cliente','listas','vendedores','categoriasIva'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id)->update([
            'razon_social' => $request->razon_social,
            'email' => $request->email,
            'tel_1' => $request->tel_1,
            'tel_2' => $request->tel_2,
            'notas' => $request->notas,
            'dni_cuit' => $request->dni_cuit,
            'direccion' => $request->direccion,
            'id_vendedor' => $request->id_vendedor,
            'id_lista' => $request->id_lista,
            'categoria_iva' => $request->id_categoria_iva,
        ]);
        $this->registrarEnLog('updated', $request->nombre);
        return redirect()->route('cliente.index')->with('updated','El cliente '.$request->nombre.' ha sido actualizado correctamente');
    }

    public function destroy(Request $request ,$id)
    {
        $nombreCliente = Cliente::find($id)->razon_social;
        $cliente = Cliente::find($id)->update([
            'deleted_at' => now(),
        ]);
        $this->registrarEnLog('deleted', $nombreCliente);
        return redirect()->route('cliente.index')->with('deleted','El cliente '.$nombreCliente.' ha sido eliminado correctamente');
    }


    private function registrarEnLog($estado, $nombreCliente){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo el cliente ".$nombreCliente." por el usuario ".Auth::user()->nombre;
                break;

            case 'error':
                $mensaje = "No se pudo crear el cliente ".$nombreCliente." por el usuario ".Auth::user()->nombre;
                break;

            case 'updated':
                $mensaje = "Se actualizo el cliente ".$nombreCliente." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino el cliente ".$nombreCliente." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }

}
