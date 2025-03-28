<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoHistorialStock extends Model
{
    use HasFactory;

    protected $table = 'historial_stock_productos';

    protected $fillable = ['id_producto', 'cantidad', 'tipo', 'id_venta', 'motivo', 'id_usuario'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Método para registrar un movimiento de stock
    public static function registrarMovimiento($idProducto, $cantidad, $tipo, $motivo = null, $idVenta = null, $idUsuario = null)
    {
        return self::create([
            'id_producto' => $idProducto,
            'cantidad' => $cantidad,
            'tipo' => $tipo,
            'motivo' => $motivo,
            'id_venta' => $idVenta,
            'id_usuario' => $idUsuario
        ]);
    }

    // Método para registrar un movimiento de stock
    public static function eliminarByVenta($idVenta)
    {
        try{
            $venta = Venta::findOrFail($idVenta);
            // Elimina los registros de historial de stock relacionados con esta venta
            self::where('id_venta', $idVenta)->delete();

            return true;

        } catch (\Exception $e) {
            // Registrar el error en los logs y mostrar un mensaje de error
            Log::error('Error al borrar los registros: ' . $e->getMessage());
            return false;
        }
    }
    
}
