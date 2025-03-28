<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recibo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "recibos";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'monto',
        'es_cobro',
        'afectar_caja',
        'detalle',
        'id_venta',
        'id_cliente',
        'id_usuario',
        'id_proveedor',
        'id_tipo_recibo',
        'deleted_at',

    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class,'id_cliente');
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class,'id_venta');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class,'id_usuario');
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class,'id_proveedor');
    }
    public function tipoRecibo(): BelongsTo
    {
        return $this->belongsTo(TipoRecibo::class,'id_tipo_recibo');
    }/*
    public function ventaReciboPago(): HasMany {
        return $this->hasMany(VentaReciboPago::class,'id_recibo');
    }*/

    public function ventaReciboPago()
    {
        return $this->belongsToMany(Venta::class, 'ventas_recibos_pagos', 'id_recibo', 'id_venta')
            ->using(VentaReciboPago::class);
    }

    public function metodoPago(): BelongsToMany
    {
        return $this->belongsToMany(MetodoPago::class, 'recibos_metodos_pagos', 'id_recibo', 'id_metodo_pago')
            ->withPivot('valor')
            ->withTimestamps()
            ->withTrashed(); // Agregar esta línea para incluir registros eliminados en la relación;
    }
}
