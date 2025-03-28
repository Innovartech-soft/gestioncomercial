<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoRecibo extends Model
{
    use HasFactory;

    protected $table = "tipos_recibos";

    protected $fillable = [
        'id',
        'nombre',
        'activo_pasivo',
        'deleted_at',
    ];
}
