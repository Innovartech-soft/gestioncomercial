<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Comision extends Model
{
    use HasFactory;
    protected $table = "comisiones";

    protected $fillable = [
        'id',
        'fecha',
        'monto',
        'estado',
        'periodo',
        'ganancia_venta',
        'id_vendedor',
        'id_venta',
        'notas',
        'deleted_at',

    ];
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Vendedor::class, 'id_vendedor');
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    public function estado($estado)
    {
        switch ($this->estado) {
            case '0':
                return 'Pendiente';
                break;
            case '1':
                return 'Pagado';
                break;
            default:
                return 'Desconocido';
        }
    }
}
