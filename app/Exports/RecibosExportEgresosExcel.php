<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Comision;
use App\Recibo;
use Carbon\Carbon;
use Log;
class RecibosExportEgresosExcel implements FromCollection ,WithHeadings // Implementa WithHeadings
{
    public function collection()
    {

        $fechaDesde = Carbon::createFromFormat('d-m-Y', request()->input('fecha_desde'))->startOfDay(); // 2023-10-04 00:00:00
        $fechaHasta = Carbon::createFromFormat('d-m-Y', request()->input('fecha_hasta'))->endOfDay();
        
        if ($fechaDesde && $fechaHasta) {
            $consulta = Recibo::select('id','monto','id_tipo_recibo','id_proveedor','created_at')
                ->where('id_tipo_recibo', '!=' , null)
            ->whereBetween('created_at', [$fechaDesde, $fechaHasta])
            ->get();
        }else{
            \Log::error('Recibo export - Error en las fechas' .$fechaDesde.' '.$fechaHasta );
            return $recibos = new Collection();
            //TODO - Manejar el error
        }
        
        $recibos = new Collection();
        foreach($consulta as $recibo){
            // Log::info($recibo);
            $recibo->fecha = Carbon::parse($recibo->created_at)->format('d/m/Y');
            $recibos->push([
                'id' => $recibo->id,
                'fecha' => $recibo->fecha,
                'monto' =>'$'. $recibo->monto,
                'id_tipo_recibo' => $recibo->tipoRecibo->nombre,
                'id_proveedor' => optional($recibo->proveedor)->nombre ,
            ]);
        }
        return $recibos;
    }

    public function headings(): array
    {
        // Define aquí los nombres de las columnas que deseas en el archivo Excel
        return [
            'ID',
            'Fecha',
            'Monto',
            'Tipo',
            'Proveedor',
        ];
    }
}



