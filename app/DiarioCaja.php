<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DiarioCaja extends Model
{
    use HasFactory;

    protected $table = "diario_cajas";

    protected $fillable = [
        'id',
        'fecha',
        'caja_apertura',
        'caja_actual',
        'caja_cierre',
        'deleted_at',
    ];
/*
    static function getEstadoCaja(){
        $estado = DiarioCaja::whereDate('created_at',Carbon::today())->whereNotNull('caja_apertura')->first();
        
        if(is_null($estado))
            return false;
        else
            return true;
    }
    */
}
