<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoPrecioHistorico extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'productos_precios_historicos';

    protected $fillable = [
        'id_producto',
        'precio',
        'user_id',
        // Otros campos si es necesario
    ];

    // Relación con el modelo Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
