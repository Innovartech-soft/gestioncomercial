<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repartidor extends Model
{
    use HasFactory;

    protected $table = 'repartidores';

    protected $fillable = [
        'nombre',
        'contacto',
        'disponible',
        'notas',
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    public function viajes()
    {
        return $this->hasMany(Viaje::class,'id_repartidor');
    }
}
