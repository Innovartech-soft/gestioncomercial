<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\Api\Response;
use Illuminate\Http\Response;
use App\Marca;
class MarcaApiController extends Controller
{
     public function listadoMarcas()
    {
        try {
              $marcas = Marca::whereNull('deleted_at')->get();

        return response()->json(
             $marcas->toArray()
            
        , Response::HTTP_OK);
        
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Lista de marcas no encontrada',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
      
    }

    public function obtenerMarcaId($id)
    {
        try {
            $marca = Marca::where('deleted_at',null)->find($id);
            
            return response()->json(
                
                    $marca->toArray()
                
            , Response::HTTP_OK);
        } catch (\Throwable $th) {
           return response()->json([
                'message' => 'Marca no encontrada',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
       
    }

}
