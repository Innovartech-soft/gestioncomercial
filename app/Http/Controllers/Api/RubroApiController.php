<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\Api\Response;
use Illuminate\Http\Response;
use App\Rubro;
use Log;

class RubroApiController extends Controller
{
    
    public function listadoRubros()
    {
        try 
        {
            $rubros = Rubro::whereNull('deleted_at')->get();
            return response()->json(
                $rubros->toArray()
                , Response::HTTP_OK);
        } catch (\Throwable $th) 
            {
                return response()->json([
                    'message' => 'Lista de rubros no encontrada',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
            }
        }

    public function obtenerRubroId($id)
    {
        try {
            $rubro = Rubro::where('deleted_at',null)->find($id);
        return response()->json(
            
                $rubro->toArray()
             
        , Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Rubro no encontrado',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
       
    }

}
