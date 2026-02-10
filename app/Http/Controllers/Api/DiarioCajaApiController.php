<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DiarioCajaService;

class DiarioCajaApiController extends Controller
{
    protected $diarioCajaService;

    public function __construct(DiarioCajaService $diarioCajaService)
    {
        $this->diarioCajaService = $diarioCajaService;
        $this->middleware('administrador',['except' => ['getEstadoCaja','getReaperturaCaja']]);
    }
    public function getEstadoCaja(){
        return response()->json($this->diarioCajaService->getEstadoCaja());
    }
    public function getReaperturaCaja(){
        return response()->json($this->diarioCajaService->getReaperturaCaja());
    }

    public function setAbrirCaja(Request $request){
        $request->validate([
            'monto' => ['required', 'numeric', 'min:0'],
        ]);
        return response()->json($this->diarioCajaService->setAperturaCaja($request['monto']));
    }

    public function setCerrarCaja(){
        return response()->json($this->diarioCajaService->setCierreCaja());
    }
}
