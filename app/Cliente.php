<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'clientes';

    protected $fillable = [
        'id_lista',
        'id_vendedor',
        'razon_social',
        'dni_cuit',
        'direccion',
        'tel_1',
        'tel_2',
        'email',
        'notas',
        'estado_cuenta',
        'categoria_iva',
        'deleted_at',
    ];

    const CATEGORIA_IVA = [
        '1' => 'Consumidor Final',
        '2' => 'Monotributo',
        '3' => 'R. Inscripto',
        '4' => 'Exento',
        '5' => 'Otro'
    ];

    public function lista(): BelongsTo
    {
        return $this->belongsTo(Lista::class,'id_lista');
    }
    
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Vendedor::class,'id_vendedor');
    }

    public function getCategoriaIva(){
        return collect(Cliente::CATEGORIA_IVA)->get($this->categoria_iva);
    }

    public function getCodigoAttribute(){
        return $this->id+1000;
    }
}
