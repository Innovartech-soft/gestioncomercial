<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\DiarioCaja;
use Carbon\Carbon;
// use App\Lista;
// use App\Parametro;
use Log;
class CajaDiariaExportExcel implements FromCollection ,WithHeadings // Implementa WithHeadings
{
    public function collection()
    {
        // Log::info(request()->input('id_vendedor'));
        $fechaDesde = Carbon::createFromFormat('Y-m-d', request()->input('fecha_desde'))->startOfDay(); // 2023-10-04 00:00:00
        $fechaHasta = Carbon::createFromFormat('Y-m-d', request()->input('fecha_hasta'))->endOfDay(); // 2023-10-04 23:59:59
        $consulta = DiarioCaja::select('fecha','caja_apertura','caja_actual','caja_cierre')
        ->whereBetween('created_at', [$fechaDesde, $fechaHasta])->get();
        $cajaDiarias = new Collection();
        foreach($consulta as $cajaDiaria){

            $cajaDiarias->push([
                'fecha' =>  $cajaDiaria->fecha,
                'caja_apertura' => $cajaDiaria->caja_apertura,
                'caja_actual' => $cajaDiaria->caja_actual,
                'caja_cierre' => $cajaDiaria->caja_cierre,

            ]);
        }
//        Log::info($comisiones);
        return $cajaDiarias;
    }

    public function headings(): array
    {
        // Define aquí los nombres de las columnas que deseas en el archivo Excel
        return [

            'Fecha',
            'Caja Apertura',
            'Caja Actual',
            'Caja Cierre',

            // Agrega más columnas según tus necesidades
        ];
    }
}



