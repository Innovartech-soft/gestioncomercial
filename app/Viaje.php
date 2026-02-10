<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Viaje extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'Viajes';
    
    protected $fillable = [
        'fecha',
        'id_estado',
        'id_cliente',
        'id_vendedor',
        'id_repartidor',
        'deleted_at',
        
    ];
    public function estadoViaje()
    {
        return $this->belongsTo(EstadoViaje::class, 'id_estado');
    }
    public function repartidor()
    {
        return $this->belongsTo(Repartidor::class, 'id_repartidor');
    }
    
    public function detalleViajes()
    {
        return $this->hasMany(DetalleViaje::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}
