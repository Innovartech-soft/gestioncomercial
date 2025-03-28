<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReciboMetodoPago extends Model
{
    use HasFactory;
    protected $table = 'recibos_metodos_pagos';

    protected $fillable = [
        'id_recibo',
        'id_metodo_pago',
        'valor'
    ];

    public function recibo(): BelongsTo
    {
        return $this->belongsTo(Recibo::class,'id_recibo');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class,'id_metodo_pago');
    }
}
