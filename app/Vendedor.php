<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    protected $table = "vendedores";

    protected $fillable = [
        'nombre',
        'contacto',
        'estado',
        'porcentaje_comision',
        'deleted_at'
    ];

    public function comision()
    {
        return $this->hasMany(Comision::class,'id_vendedor');
    }
}
