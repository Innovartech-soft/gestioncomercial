<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\Api\Response;
use Illuminate\Http\Response;
use App\Cliente;
use App\Lista;

class ClienteApiController extends Controller
{
    public function listadoClientes()
    {
        try
        {
            $clientes = Cliente::whereNull('deleted_at')->get();
            $clientes = $clientes->map(function ($cliente) {
                $cliente->razon_social = $cliente->codigo.' '.$cliente->razon_social;
                return $cliente;
            });

        // Convierte la colección de clientes a un array
        $clientesArray = $clientes->toArray();

            return response()->json(
                $clientesArray
            , Response::HTTP_OK);

        }catch (\Throwable $th) 
        {
           return response()->json([
               
                'message' => 'Lista de clientes no encontrada',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
       
        
    }


    public function obtenerClienteId($id)
    {
        try
        {
            $cliente = Cliente::where('deleted_at',null)->find($id);

            return response()->json(
                    $cliente->toArray(),
                     $cliente->lista->valor
            , Response::HTTP_OK);

        } catch (\Throwable $th)
        {
            return response()->json([

                'message' => 'Cliente no encontrado',
                'data' => $th->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function countClientesDeudores(){
        $clientesDeudores = Cliente::where('deleted_at',null)->where('estado_cuenta',0)->count();
        return response()->json([
            'message' => 'Cheques encontrados',
            'response' => $clientesDeudores
        ], Response::HTTP_OK);

    }
    
}
