<?php

namespace App;

use App\Parametro;
use App\ProductoPrecioHistorico;
use App\ProductoHistorialStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Producto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "productos";
    public $precio_venta_final;
    public $precio_venta_final_impresion;
    public $precio_sin_descuento;

    protected static function boot(): void
    {
        parent::boot();

        static::retrieved(function ($model) {
            $model->param = Cache::remember('dolar', now()->addSeconds(10), function () {
                // Consulta la base de datos para obtener el valor del dólar y devuelve el resultado.
                return Parametro::select('dolar')->first();
            });

            $model->precio_venta_final = $model->getPrecioPesosConIva();
        });


        static::updated(function ($model) {
            if ($model->isDirty('precio_costo')) {
                // Guardar historial de cambio de precio en la base de datos
                $historico = ProductoPrecioHistorico::create([
                    'id_producto' => $model->id,
                    'user_id' => Auth::id(),
                    'precio' => $model->precio_costo,
                ]);
            }
        });

        static::created(function ($model) {
            $historico = ProductoPrecioHistorico::create([
                'id_producto' => $model->id,
                'user_id' => Auth::id(),
                'precio' => $model->precio_costo,
            ]);
        });

        static::deleting(function ($model) {
            // Verificar si hay detalles de venta asociados a este producto
            if ($model->detalleVenta()->exists()) {
                return false;
            }
        });
    }



    protected $param;

    protected $dates = ['oferta_fecha_desde', 'oferta_fecha_hasta'];

    protected $fillable = [
        'id',
        'nombre',
        'detalle',
        'codigo',
        'codigo_barra',
        'codigo_interno',
        'precio_costo',
        'es_dolar',
        'precio_venta',
        'url',
        'tipo_iva',
        'stock',
        'stock_minimo',
        'bulto',
        'id_rubro',
        'id_marca',
        'id_proveedor',
        'notas',
        'deleted_at',
        'oferta_fecha_desde',
        'oferta_fecha_hasta',
        'en_oferta',
        'no_comisionable',
        'precio_costo_oferta',
    ];

    const TIPO_IVA = [
        '1' => 'Sin Iva',
        '2' => '10.5',
        '3' => '21'
    ];

    public function rubro(): BelongsTo
    {
        return $this->belongsTo(Rubro::class,'id_rubro');
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class,'id_marca');
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class,'id_proveedor');
    }

    public function getTipoIva(){
        return collect(Producto::TIPO_IVA)->get($this->tipo_iva);
    }

    public function detalleVenta(){
        return $this->hasMany(DetalleVenta::class,'id_producto');
    }

    public function getPrecioConIVA(){
        if($this->tipo_iva!=1)
            return round($this->precio_costo + (($this->precio_costo*$this->getTipoIva())/100),1);
        else
            return round($this->precio_costo,1);
    }

    public function getPrecioEnPesos() {

        return round(($this->es_dolar == 1 ? $this->param->dolar * $this->precio_costo : $this->precio_costo),1);
    }

    public function getPrecioPesosConIva(){

        $precioPesos = ($this->es_dolar == 1 ? $this->param->dolar * $this->precio_costo : $this->precio_costo);

        if($this->tipo_iva!=1)
            return round($precioPesos + (($precioPesos*$this->getTipoIva())/100),1);
        else
            return round($precioPesos,1);
    }


     public function getPrecioPesosConIvaAttribute()
    {
        return $this->getPrecioPesosConIva();
    }

    /**
     * Genera un nuevo código tomando como referencia el ID del ultimo producto existente y el acronimo del rubro informado por parametro
     *
     * @param id_rubro
     * @return string
     */

    static function generarCodigoInterno($id_rubro){
        try{
            //Obtengo el ID más alto existente y genero el código
            $res = DB::table('productos')
            ->join('rubros', 'productos.id_rubro', '=', 'rubros.id')
            ->where('rubros.id',$id_rubro)
            ->select(DB::raw('MAX(productos.id) as maxid'), 'rubros.acronimo')
            ->groupBy('rubros.acronimo')
            ->first();

            if($res){
                return $res->acronimo.($res->maxid+10+1);
            }else{
                $res = DB::table('rubros')
                ->where('id',$id_rubro)
                ->select('acronimo')
                ->first();
                return $res->acronimo.(10+1);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Genera un nuevo código tomando como referencia el objeto Producto actual y el acronimo del rubro informado por parametro
     *
     * @param id_rubro
     * @return string
     */
    public function updateCodigoInterno($id_rubro){
        try{
            //Obtengo el ID más alto existente y genero el código
            $res = DB::table('rubros')
                ->where('id',$id_rubro)
                ->select('acronimo')
                ->first();

            if($res){
                return $res->acronimo.($this->id+10);
            }else{
                throw new excepcion("No se pudo generar el código del producto.");
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    // Retorna true si el producto cumple con las condiciones de oferta, y false si no.
    public function isOffer()
    {
        if ($this->en_oferta == 1) {
            $hoy = Carbon::today();

            $fecha_desde = Carbon::parse($this->oferta_fecha_desde)->startOfDay();
            $fecha_hasta = Carbon::parse($this->oferta_fecha_hasta)->startOfDay();

            // Verifica si las fechas de oferta son válidas
            if ($fecha_desde->lessThanOrEqualTo($hoy) && $fecha_hasta->greaterThanOrEqualTo($hoy) && $fecha_desde->lessThanOrEqualTo($fecha_hasta)) {
                return true;
            }
        }
        return false;
    }

    //Actualiza el stock del producto - si es negativo resta stock, positivo suma stock
    public function updateStock($idProducto, $cantidad, $motivo = null, $idVenta = null, $idUsuario = null){
        try {
            Log::info('Ingreso a updateStock(): cant'.$cantidad.' stock: '.$this->stock);
            $this->stock = $this->stock+$cantidad;
            $this->update();  //ATENCION - Alto consumo

            ProductoHistorialStock::registrarMovimiento(
                $idProducto,
                $cantidad, 
                ($cantidad<0?'egreso':'ingreso'),
                $motivo,
                $idVenta,
                $idUsuario
            );
            
            return true;
        } catch (\Exception $e) {
            Log::alert('Error al actualizar el stock: '.$e->getMessage());
            return false;
        }
    }

     //Restaura el stock del producto - si es negativo resta stock, positivo suma stock
     //No registra en el historial
     public function restoreStock($stock){
        try {
            $this->stock = $this->stock+$stock;
            $this->save();  //ATENCION - Alto consumo
        } catch (\Exception $e) {
            Log::alert('Error al actualizar el stock: '.$e->getMessage());
        }
    }


}
