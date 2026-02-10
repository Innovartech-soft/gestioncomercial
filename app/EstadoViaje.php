<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoViaje extends Model
{
    use HasFactory;

    protected $table = 'estado_viaje';
    protected $fillable = [
        'nombre',
    ];
    public function Viajes()
    {
        return $this->hasMany(Viaje::class, 'estado_viaje_id');
    }
}
