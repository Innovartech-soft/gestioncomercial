<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVenta extends Model
{
    use HasFactory;

    protected $table = "tipos_ventas";

    protected $fillable = [
        'id',
        'nombre',
        'deleted_at',
    ];
}
