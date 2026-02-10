<?php
// app/Services/DiarioCajaService.php

namespace App\Services;

use Illuminate\Http\Request;
use App\DiarioCaja;
use Illuminate\Support\Carbon;
use App\Logs;
use Auth;
use DB;
use Log;

class DiarioCajaService
{
    protected $diarioCaja;
    protected $diarioCajaHoy;

    public function __construct()
    {
        // Obtén el registro de DiarioCaja para el día actual
        $this->diarioCaja = DiarioCaja::whereDate('fecha', Carbon::today())
            ->whereNotNull('caja_apertura')
            ->whereNull('caja_cierre')
            ->orderByDesc('id')
            ->first();
        $this->diarioCajaHoy = DiarioCaja::whereDate('fecha', Carbon::today())
            ->orderByDesc('id')
            ->first();
    }

    public function getDiarioCaja()
    {
        return $this->diarioCaja;
    }

    public function getEstadoCaja(){
       
        if(is_null($this->diarioCaja))
            return false; //caja cerrada
        else 
            return true; //caja abierta
    }

    public function getReaperturaCaja(){
       
        if(is_null($this->diarioCajaHoy))
            return true; //posible abrir caja
        else 
            return false; //imposible abrir caja
    }

    public function setAperturaCajaAutomatica()
    {
        if(is_null($this->diarioCaja)&&is_null($this->diarioCajaHoy)){
            $diarioCajaAnterior = DiarioCaja::where('fecha', '<', Carbon::today())->latest()->first();
            $diarioCaja = new DiarioCaja();
            $diarioCaja->fecha = Carbon::today();
            $diarioCaja->caja_apertura = optional($diarioCajaAnterior)->caja_cierre ? $diarioCajaAnterior->caja_cierre : 0;
            $diarioCaja->caja_actual = optional($diarioCajaAnterior)->caja_cierre ? $diarioCajaAnterior->caja_cierre : 0;
            $diarioCaja->save();
            $this->registrarEnLog('success', Carbon::now()->format('d/m/Y'));
            return $diarioCaja;
        }else{
            return $this->diarioCaja;
        }
    }
    public function setAperturaCaja($monto)
    {
        $monto = is_numeric($monto) ? (float) $monto : null;
        if (is_null($monto) || $monto < 0) {
            return false;
        }
        if(is_null($this->diarioCaja)&&is_null($this->diarioCajaHoy)){
            $diarioCaja = new DiarioCaja();
            $diarioCaja->fecha = Carbon::today();
            $diarioCaja->caja_apertura = $monto;
            $diarioCaja->caja_actual = $monto;
            $diarioCaja->save();
            $this->registrarEnLog('success', Carbon::now()->format('d/m/Y'));
            return $diarioCaja;
        }else{
            return $this->diarioCaja;
        }
    }

    public function setCierreCaja()
    {
         if(!is_null($this->diarioCaja)){
            $this->diarioCaja->caja_cierre = $this->diarioCaja->caja_actual;
            $this->diarioCaja->update();
            $this->registrarEnLog('closed', Carbon::now()->format('d/m/Y'));
            return $this->diarioCaja;
         }
         return false;
    }

    public function updateCajaActual($monto){
        if (is_null($this->diarioCajaHoy)) {
            Log::warning('Caja diaria no encontrada para actualizar.');
            return false;
        }
        $this->diarioCajaHoy->caja_actual += $monto;
        $this->diarioCajaHoy->update();
        return $this->diarioCajaHoy->caja_actual;
    }

    private function registrarEnLog($estado, $caja){
        switch ($estado) {
            case 'success':
                $mensaje = "Se abrio la caja del ".$caja." por el usuario ".Auth::user()->nombre;
                break;
            
            case 'updated':
                $mensaje = "Se actualizo la caja ".$caja." por el usuario ".Auth::user()->nombre;
                break;

            case 'closed':
                $mensaje = "Se cerro la caja del ".$caja." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }
}
