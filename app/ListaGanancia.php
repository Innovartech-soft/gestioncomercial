<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaGanancia extends Model
{
    use HasFactory;
    protected $table = "listas_ganancia";

    protected $fillable = [
        'id',
        'nombre',
        'ganancia'
    ];

    // Definir la relación con los productos
    public function productos()
    {
        return $this->hasMany(Producto::class,'id_lista_ganancia');
    }
}
