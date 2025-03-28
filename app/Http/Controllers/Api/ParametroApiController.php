<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Parametro;
use Illuminate\Http\Response;
use Log;
class ParametroApiController extends Controller
{
    public function obtenerParametros(){
        try {
            $parametro = Parametro::first();
            $parametro = Parametro::find($parametro->id)->get();
            return response()->json(
                $parametro->toArray()
            , Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Lista de parametros no encontrada',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
