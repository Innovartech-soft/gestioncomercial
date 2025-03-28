<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    use HasFactory;
    protected $table = "parametros";

    protected $fillable = [
        'id',
        'nombre_empresa',
        'cuit',
        'dir_1',
        'dir_2',
        'tel_1',
        'tel_2',
        'aux_1',
        'aux_2',
        'multimoneda',
        'dolar',
        'deleted_at',
        
    ];
}
