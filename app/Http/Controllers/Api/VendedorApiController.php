<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Vendedor;
class VendedorApiController extends Controller
{
    
    public function listadoVendedores(){
        try
        {
            $vendedores = Vendedor::whereNull('deleted_at')->get();
            return response()->json(
                $vendedores->toArray()
            , Response::HTTP_OK);

        }catch (\Throwable $th) 
        {
           return response()->json([
               
                'message' => 'Lista de vendedores no encontrada',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
    }
    
        public function obtenerVendedorId($id)
        {
            try {
                $vendedor = Vendedor::where('deleted_at',null)->find($id);
            return response()->json(
                
                    $vendedor->toArray()
                 
            , Response::HTTP_OK);
    
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Vendedor no encontrado',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            }
           
        }

}
