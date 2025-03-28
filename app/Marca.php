<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $table = "marcas";

    protected $fillable = [
        'id',
        'nombre',
        'deleted_at',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class,'id_marca');
    }
}
