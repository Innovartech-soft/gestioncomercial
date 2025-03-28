<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $table = "proveedores";

    protected $fillable = [
       'nombre',
       'cuit',
       'contacto',
       'deleted_at',

    ];

    public function productos()
    {
        return $this->hasMany(Producto::class,'id_rubro');
    }
}
