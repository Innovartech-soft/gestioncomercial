<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection; 
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Venta;
use Carbon\Carbon;
use Log;
class VentaCerradaExport implements FromCollection ,WithHeadings // Implementa WithHeadings
{
    public function collection()
    {   
        
        $fechaDesde = Carbon::createFromFormat('Y-m-d', request()->input('fecha_desde'))->startOfDay(); // 2023-10-04 00:00:00
        $fechaHasta = Carbon::createFromFormat('Y-m-d', request()->input('fecha_hasta'))->endOfDay();
       
        $consulta = Venta::where('pagada', 1)
        ->select('id','total','id_vendedor','id_cliente','created_at')
        ->whereBetween('created_at', [$fechaDesde, $fechaHasta])
        ->get();

        $ventaCerrada = new Collection();
        foreach($consulta as $ventasCerradas){
            // Log::info($recibo);
            
            $ventasCerradas->fecha = Carbon::parse($ventasCerradas->created_at)->format('d/m/Y');
            $ventaCerrada->push([
                'id' => $ventasCerradas->numero_venta,
                'fecha' => $ventasCerradas->fecha,
                'monto' => $ventasCerradas->total,
                'cliente' => $ventasCerradas->cliente->razon_social,
                'vendedor' => $ventasCerradas->vendedor->nombre,
            ]);
            $total = $ventaCerrada->sum('monto');
        }
        $ventaCerrada->push([
            'id' => '',
            'fecha' => '',
            'monto' => '',
            'cliente' => '',
            'vendedor' => '',
            'total' => $total,
        ]);
        return $ventaCerrada; 
    }

    public function headings(): array
    {
        // Define aquí los nombres de las columnas que deseas en el archivo Excel
        return [
            'ID',
            'Fecha',
            'Monto',	
            'Cliente',
            'Vendedor',
        ];
    }
}



