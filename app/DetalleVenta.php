<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleVenta extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "detalles_ventas";

    protected $fillable = [
        'id',
        'cantidad',
        'descuento',
        'precio',
        'oferta_aplicada',
        'id_venta',
        'id_producto',
        'id_lista',
        'deleted_at',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function lista()
    {
        return $this->belongsTo(Lista::class, 'id_lista');
    }
}
