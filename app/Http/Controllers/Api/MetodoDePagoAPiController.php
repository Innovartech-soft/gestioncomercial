<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MetodoPago;
use Illuminate\Http\Response;

class MetodoDePagoAPiController extends Controller
{
    public function listadoMetodosDePago()
    {
        try 
        {
            $metodosDePago = MetodoPago::all();

            return response()->json(
                $metodosDePago->toArray()
                , Response::HTTP_OK);
        } catch (\Throwable $th) 
            {
                return response()->json([
                    'message' => 'Lista de metodos de pago no encontrada',
                    'data' => null
                ], Response::HTTP_NOT_FOUND);
        }
    }
}
