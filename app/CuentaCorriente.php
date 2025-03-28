<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Recibo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuentaCorriente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = "cuentas_corrientes";

    protected $fillable = [
        'id',
        'fecha',
        'monto',
        'saldo',
        'id_cliente',
        'id_recibo',
        'deleted_at',        
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class,'id_cliente');
    }

    public function recibo(): BelongsTo
    {
        return $this->belongsTo(Recibo::class,'id_recibo');
    }

    //Retorna un array de registros de la cuenta corriente de un cliente 
    static function getCuentasByClienteObject(Cliente $cliente){
        return CuentaCorriente::where('id_cliente',$cliente->id)->whereNull('deleted_at')->orderby('id','ASC')->get();
    }

    //Calcula el saldo de un cliente
    static function calcularSaldo(Cliente $cliente){
        $aCuentas = CuentaCorriente::getCuentasByClienteObject($cliente);
        $saldo = 0;
        foreach ($aCuentas as $cuenta) {
            $saldo = $saldo + ($cuenta->monto);
        }
        return $saldo;
    }
}
