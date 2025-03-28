<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\Api\Response;
use Illuminate\Http\Response;
use App\Proveedor;

class ProveedorApiController extends Controller
{
    public function listadoProveedores()
    {
        try {
             $proveedores = Proveedor::whereNull('deleted_at')->get();

        return response()->json(
             $proveedores->toArray()
            
        , Response::HTTP_OK);
       
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Lista de proveedores no encontrada',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
       
    }


    public function obtenerProveedorId($id)
    {
        try {
            $proveedor = Proveedor::where('deleted_at',null)->find($id);

            return response()->json(
                
                    $proveedor->toArray()
                
            , Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Proveedor no encontrado',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
   

    }

}
