<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Cliente;
use App\Recibo;
class Cheque extends Model
{
    use HasFactory;
    protected $table = "cheques";

    protected $fillable = [
        'id',
        'banco_emisor',
        'numero',
        'fecha_pago',
        'fecha_emision',
        'serie',
        'importe',
        'cuenta',
        'titular_librador',
        'endosado',
        'cruzado',
        'nombre_beneficiario',
        'id_cliente',
        'id_recibo',
        'deleted_at',
        'estado',
    ];
    
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class,'id_cliente');
    }

    public function recibo(): BelongsTo
    {
        return $this->belongsTo(Recibo::class,'id_recibo');
    }
    
    public function estadoDeCheques(){
        if($this->estado==1){
            $this->estado = "Disponible";
            return $this;
        }else {
            $this->estado = 'No Disponible';
            return $this;
        }
    }

    
}
