<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VentaReciboPago extends Model
{
    use HasFactory;
    protected $table = "ventas_recibos_pagos";
    protected $fillable = [
        'monto',
        'id_venta',
        'id_recibo',
        'updated_at',
        'deleted_at'
    ];
    
    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class,'id_venta');
    }
    public function recibo(): BelongsTo
    {
        return $this->belongsTo(Recibo::class,'id_recibo');
    }

}
