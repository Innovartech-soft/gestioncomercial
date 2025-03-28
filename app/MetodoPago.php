<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetodoPago extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = "metodos_pagos";

    protected $fillable = [
        'id',
        'nombre',
        'deleted_at',
    ];

        public function recibo(): BelongsToMany
        {
        return $this->belongsToMany(Recibo::class, 'recibos_metodos_pagos', 'id_metodo_pago', 'id_recibo')
            ->withPivot('valor')
            ->withTimestamps()
            ->withTrashed(); // Agregar esta línea para incluir registros eliminados en la relación;;
    }
}
