<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lista extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "listas_descuento";

    protected $fillable = [
        'id',
        'nombre',
        'deleted_at',
        'valor',

    ];

    // Definir la relación con los clientes
    public function clientes()
    {
        return $this->hasMany(Cliente::class,'id_lista');
    }
}
