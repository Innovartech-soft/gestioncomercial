<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Comision;
use Carbon\Carbon;
use DateTime;
// use App\Lista;
// use App\Parametro;

use Log;
class VendedorExportExcel implements FromCollection ,WithHeadings // Implementa WithHeadings
{
    public function collection()
    {
        $fechaDesde = Carbon::createFromFormat('d-m-Y', request()->input('fecha_desde'))->startOfDay();
        $fechaHasta = Carbon::createFromFormat('d-m-Y', request()->input('fecha_hasta'))->endOfDay();
        
        $consulta = Comision::with('venta', 'vendedor') // Carga relaciones
                    ->where('id_vendedor', request()->input('id_vendedor'))
                    ->select('id', 'fecha', 'monto', 'estado', 'id_vendedor', 'id_venta', 'notas', 'created_at')
                    ->whereNotNull('id_venta')
                    ->whereBetween('created_at', [$fechaDesde, $fechaHasta]);
    
        $estado = request()->input('estado');
        if ($estado !== null && in_array($estado, [0, 1])) {
            $consulta->where('estado', $estado);
        }
    
        $consulta = $consulta->get();
        Log::debug(request()->input('estado'));
        Log::debug(json_encode($consulta));
    
        $comisiones = new Collection();
        foreach ($consulta as $comision) {
            if($comision->venta){
                $comisiones->push([
                    'numeroVenta' => $comision->venta->numero_venta,
                    'fecha' => Carbon::parse($comision->fecha)->format('d/m/Y'),
                    'monto' => $comision->monto,
                    'notas' => $comision->notas,
                    'origen' => optional($comision->venta->tipoVenta)->nombre,
                    'id_vendedor' => $comision->vendedor ? $comision->vendedor->nombre : 'N/C',
                    'cliente' => $comision->venta->cliente ? $comision->venta->cliente->razon_social : 'N/C',
                    'estado' => $comision->estado($comision->estado)
                ]);
            }           
        }
    
        return $comisiones;
    }
    

    public function headings(): array
    {
        // Define aquí los nombres de las columnas que deseas en el archivo Excel
        return [
            'Numero Operacion',
            'Fecha',
            'Monto',
            'Notas',
            'Origen',
            'Vendedor',
            'Cliente',
            'Estado'

        ];
    }
}



