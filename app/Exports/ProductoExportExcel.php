<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Producto;
use App\Lista;
use App\Parametro;
use Log;
class ProductoExportExcel implements FromCollection ,WithHeadings // Implementa WithHeadings
{
    public function collection()
    {
        $consulta = Producto::when(request()->has('id_marca') && request()->input('id_marca') != '', function ($query) {
            $idMarca = request()->input('id_marca');
            $query->where('id_marca', $idMarca);
        })
        ->when(request()->has('id_rubro') && request()->input('id_rubro') != '', function ($query) {
            $idRubro = request()->input('id_rubro');
            $query->where('id_rubro', $idRubro);
        })
        ->when(request()->has('id_proveedor') && request()->input('id_proveedor') != '', function ($query) {
            $idProveedor = request()->input('id_proveedor');
            $query->where('id_proveedor', $idProveedor);
        })
        ->select('codigo','codigo_interno','nombre','detalle','precio_costo','es_dolar')
        ->get();
        // Log::info($consulta);
        $lista = Lista::findorfail(request()->input('id_lista'));
        $productosActualizados = new Collection();
        foreach($consulta as $producto){
            $producto->precio_pesos_con_iva = $producto->getPrecioPesosConIva();

            $producto->precio_venta_lista = round($producto->precio_pesos_con_iva * (1 + ($lista->valor / 100)), 1);
            unset($producto->precio_pesos_con_iva);
            unset($producto->precio_costo);
            unset($producto->es_dolar);
            unset($producto->detalle);
            unset($producto->codigo_barras);
            $productosActualizados->push(new Collection($producto));
            // Log::info( $producto);
        }


        // Log::info($productos);
        return $productosActualizados;
    }

    public function headings(): array
    {
        return [
            'Codigo',
            'Codigo_Interno',
            'Nombre',
            // 'Detalle',
            'Precio_Venta'
        ];
    }
}



