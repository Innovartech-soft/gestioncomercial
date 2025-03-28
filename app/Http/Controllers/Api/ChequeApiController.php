<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\Api\Response;
use Illuminate\Http\Response;
use App\Cliente;
use App\Cheque;
use Log;
class ChequeApiController extends Controller
{
    public function listadoCheques()
    {
        try
        {
            $cheques = Cheque::whereNull('deleted_at')->get();
            return response()->json(
                $cheques->toArray()
            , Response::HTTP_OK);

        }catch (\Throwable $th) 
        {
           return response()->json([
               
                'message' => 'Lista de cheques no encontrada',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
       
    }

    public function obtenerChequeId($id)
    {
        try
        {
            $cheque = Cheque::where('deleted_at',null)->find($id);

            return response()->json(
                    $cheque->toArray()
                    
            , Response::HTTP_OK);

        } catch (\Throwable $th)
        {
            return response()->json([

                'message' => 'Cheque no encontrado',
                'data' => $th->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getChequesByClienteId($id)
    {
        try
        {
            $cheques = Cheque::where([['deleted_at',null],['estado',1],['id_cliente',$id]])->orderBy('fecha_emision','ASC')->get();

            return response()->json(
                    $cheques->toArray()
                    
            , Response::HTTP_OK);

        } catch (\Throwable $th)
        {
            return response()->json([

                'message' => 'Cheques no encontrados',
                'data' => $th->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function storeCheque(Request $request){
       $data = $request->json()->all();
        Log::debug($data);

        $cheque = Cheque::create([
            'banco_emisor' => $data['banco_emisor'],
            'numero' => $data['numero'],
            'fecha_pago' => $data['fecha_pago'],
            'fecha_emision' => $data['fecha_emision'],
            'serie' => $data['serie'],
            'importe' => $data['importe'],
            'cuenta' => $data['cuenta'],
            'titular_librador' => $data['titular_librador'],
            'endosado' => $data['endosado'],
            'cruzado' => $data['cruzado'],
            'nombre_beneficiario' => $data['nombre_beneficiario'],
            'id_cliente' => $data['id_cliente'],
            'estado' => 1
        ]);



        return response()->json([

                'message' => 'Cheque guardado',
                'data' => $cheque
            ], Response::HTTP_OK);
    }

    public function countChequesDisponibles(){
    
        return response()->json([

                'message' => 'Cheques encontrados',
                'response' => Cheque::where([['deleted_at',null],['estado',1]])->count()
            ], Response::HTTP_OK);
    }

    public function test(Request $request){
        try
        {
            return response()->json([
                'message' => 'LLEGUE CHE!!',
            ], Response::HTTP_OK);

        } catch (\Throwable $th)
        {
            return response()->json([

                'message' => 'LLEGUE PERO CON ERROR!',
                'data' => $th->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

}
