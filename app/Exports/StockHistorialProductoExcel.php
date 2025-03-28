<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Carbon;
use App\Producto;
use App\ProductoHistorialStock;
use Log;

class StockHistorialProductoExcel implements FromCollection ,WithHeadings
{
    protected $idProducto;

    public function __construct($idProducto)
    {
        $this->idProducto = $idProducto;
    }

    public function collection()
    {   
        $producto = Producto::findOrFail($this->idProducto);

        $consulta = ProductoHistorialStock::where('historial_stock_productos.id_producto', $this->idProducto)
                                            ->leftJoin('ventas', 'historial_stock_productos.id_venta', '=', 'ventas.id')
                                            ->leftJoin('usuarios', 'historial_stock_productos.id_usuario', '=', 'usuarios.id')
                                            ->orderBy('historial_stock_productos.id', 'DESC')
                                            ->select(
                                                'historial_stock_productos.created_at', 
                                                'historial_stock_productos.tipo', 
                                                'historial_stock_productos.cantidad', 
                                                'ventas.id as id_venta', 
                                                'historial_stock_productos.motivo', 
                                                'usuarios.nombre as usuario_nombre'
                                            )
                                            ->get();


        $historial = new Collection();
        $totalCantidad = $producto->stock;
        $cont = 0;

        foreach($consulta as $his){
            // Formatear la fecha
            $formattedDate = Carbon::parse($his->created_at)->format('d/m/Y h:m:s');
            $cont++;
            
            // Añadir los datos formateados a la colección
            $historial->push([
                'nombre' => ($cont==1? $producto->nombre:''),
                'fecha' => $formattedDate,
                'tipo' => $his->tipo,
                'cantidad' => $his->cantidad,
                'stock' => $totalCantidad,
                'id_venta' => optional($his->venta)->id,
                'motivo' => $his->motivo,
                'usuario' => $his->usuario_nombre
            ]);

             // Sumar las cantidades
             $totalCantidad += $his->cantidad*-1;
        }
        return $historial; 
    }

    public function headings(): array
    {
        // Define aquí los nombres de las columnas que deseas en el archivo Excel
        return [
            'nombre',
            'fecha',
            'tipo',
            'cantidad',
            'stock',
            'operacion',
            'motivo',
            'id_usuario',	
              
            // Agrega más columnas según tus necesidades
        ];
    }
}



