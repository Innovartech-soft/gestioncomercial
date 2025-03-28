<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lista;
use Illuminate\Http\Response;

class ListaApiController extends Controller
{
    
    public function listadoListas(){
        try {
            $listas = Lista::whereNull('deleted_at')->get();
            return response()->json(
                $listas->toArray()
            , Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Lista de listas no encontrada',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function obtenerListaId($id){
        try {
            $lista = Lista::where('deleted_at',null)->find($id);
            return response()->json(
                $lista->toArray()
            , Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Lista no encontrada',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
