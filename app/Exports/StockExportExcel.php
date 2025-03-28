<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Producto;
// use App\Lista;
// use App\Parametro;
use Log;
class StockExportExcel implements FromCollection ,WithHeadings // Implementa WithHeadings
{
    public function collection()
    {   
        // Log::info(request()->input('id_vendedor'));
        $consulta = Producto::whereColumn('stock','<=','stock_minimo')
        ->select('id','nombre','codigo','codigo_interno','stock','stock_minimo')->get();
        
        $productos = new Collection();
        foreach($consulta as $producto){
            
            $productos->push($producto);
        }
        return $productos; 
    }

    public function headings(): array
    {
        // Define aquí los nombres de las columnas que deseas en el archivo Excel
        return [
            'ID',
            'nombre',
            'codigo',
            'codigo_interno',
            'stock',
            'stock_minimo',	
                  
            // Agrega más columnas según tus necesidades
        ];
    }
}



