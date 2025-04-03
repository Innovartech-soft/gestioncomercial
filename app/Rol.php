<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;
    protected $table = "roles";

    protected $fillable = [
        'id',
        'nombre',
        'descripcion'
    ];
}
