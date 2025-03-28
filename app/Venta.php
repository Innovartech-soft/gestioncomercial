<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = "ventas";
    public $numero;

    protected $fillable = [
        'id',
        'fecha',
        'fecha_pago',
        'direccion',
        'dni_cuit',
        'telefono',
        'email',
        'total',
        'descuento',
        'nombre_cliente',
        'notas',
        'pagada',
        'id_usuario',
        'id_cliente',
        'id_vendedor',
        'id_tipo_venta',
        'deleted_at',

    ];

    public function usuario(): BelongsTo {
        return $this->belongsTo(User::class,'id_usuario');
    }

    public function cliente(): BelongsTo {
        return $this->belongsTo(Cliente::class,'id_cliente');
    }

    public function vendedor(): BelongsTo {
        return $this->belongsTo(Vendedor::class,'id_vendedor');
    }

    public function tipoVenta(): BelongsTo {
        return $this->belongsTo(TipoVenta::class,'id_tipo_venta');
    }

    public function recibo(): HasMany {
        return $this->hasMany(Recibo::class,'id_recibo');
    }

    public function detalleVenta(): HasMany {
        return $this->hasMany(DetalleVenta::class,'id_venta');
    }

    public function comision(): HasMany {
        return $this->hasMany(Comision::class,'id_venta');
    }
    /*
    public function ventaReciboPago(): HasMany {
        return $this->hasMany(VentaReciboPago::class,'id_venta');
    }
    */
    public function ventaReciboPago()
    {
        return $this->belongsToMany(Recibo::class, 'ventas_recibos_pagos', 'id_venta', 'id_recibo')
            ->using(VentaReciboPago::class);
    }

    public function getNumeroVentaAttribute()
    {
         return str_pad($this->id, 8, '0', STR_PAD_LEFT);
    }

    //Retorna true si la venta fue actualizada, false en el caso contrario
    public function isUpdated($id){
        $venta = Venta::where('id', $id)
                  ->whereColumn('updated_at', '>', 'created_at')
                  ->first();

        if($venta!=null)
            return true;
        else
            return false;
    }

    public function wasModified(){

        return $this->created_at != $this->updated_at;

    }
}
