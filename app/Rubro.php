<?php

namespace App;

use App\Producto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
//use App\Producto;

class Rubro extends Model
{
    use HasApiTokens,HasFactory;
    protected $table = "rubros";

   protected $fillable = [
       'id',
       'nombre',
       'acronimo',
       'deleted_at',
    ];


    protected static function boot(): void
    {
        parent::boot();


        static::updated(callback: function ($model){
            if($model->isDirty('acronimo')){
                $productos = Producto::where('id_rubro', '=' ,$model->id)->get();
                foreach($productos as $producto){
                    $producto->codigo_interno = $producto->updateCodigoInterno($model->id);
                    $producto->save();
                }
            }
        });
    }

    public function productos()
    {
        return $this->hasMany(Producto::class,'id_rubro');
    }
}
