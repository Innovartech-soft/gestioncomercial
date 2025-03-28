<?php

namespace App\Exports;

use App\CuentaCorriente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

use Illuminate\Http\Request;

class CuentaCorrienteExportExcel implements FromCollection ,WithHeadings // Implementa WithHeadings
{
    public function collection()
    {

        try {
            
            $fechaDesde = Carbon::createFromFormat('d-m-Y', request()->input('fecha_desde'))->startOfDay();
            $fechaHasta = Carbon::createFromFormat('d-m-Y', request()->input('fecha_hasta'))->endOfDay();

            if ($fechaDesde && $fechaHasta) {
                $consulta = CuentaCorriente::select('id','fecha','monto','saldo','id_recibo','id_cliente')
                    ->where('id_cliente',request()->cliente_id)
                    ->whereBetween('created_at', [$fechaDesde, $fechaHasta])
                    ->get();
            }else{
                \Log::error('Cuenta Corriente - Error en las fechas' .$fechaDesde.' '.$fechaHasta );
                return $cuentaCorrientes = new Collection();
                //TODO - Manejar el error
            }
            $cuentaCorrientes = new Collection();
            $operacionNumero = '';
            
            foreach ($consulta as $cuentaCorriente) {
                try {
                    if ($cuentaCorriente->recibo->tipoRecibo) {
                        $operacionNumero =  $cuentaCorriente->recibo->venta
                            ? $cuentaCorriente->recibo->tipoRecibo->nombre . ' - ' . optional($cuentaCorriente->recibo->venta)->tipoVenta->nombre . ' [' . optional($cuentaCorriente->recibo->venta)->numero_venta . ']'
                            : $cuentaCorriente->recibo->tipoRecibo->nombre;
                    } else {
                        $operacionNumero =  $cuentaCorriente->recibo->venta
                            ? $cuentaCorriente->recibo->venta->tipoVenta->nombre . ' [' . optional($cuentaCorriente->recibo->venta)->numero_venta . ']'
                            : 'Sin venta asociada';
                    }

                    $cuentaCorrientes->push([
                        'fecha' => Carbon::parse($cuentaCorriente->fecha)->format('d/m/Y'),
                        'numeroDeRecibo' => $cuentaCorriente->recibo->id ?? null,
                        'operacionNumero' => $operacionNumero,
                        'debe' => $cuentaCorriente->monto < 0 ? abs($cuentaCorriente->monto) : 0,
                        'haber' => $cuentaCorriente->monto > 0 ? $cuentaCorriente->monto : 0,
                        'saldo' => $cuentaCorriente->saldo,
                    ]);
                } catch (\Exception $e) {
                    // Manejar la excepción (puedes registrarla, notificarla, etc.)
                    // Puedes agregar un mensaje al log, por ejemplo:
                    \Log::error('Error al procesar cuenta corriente: ' . $e->getMessage());
                }
            }
            
            return $cuentaCorrientes;
        } catch (\Exception $e) {
            // Manejar la excepción (puedes registrarla, notificarla, etc.)
            // Puedes agregar un mensaje al log, por ejemplo:
            \Log::error('Error al obtener cuentas corrientes: ' . $e->getMessage());
        }



    }


        public function headings(): array
        {
            // Define aquí los nombres de las columnas que deseas en el archivo Excel
            return [
//                'Id',
                'Fecha',
                'Nº De Recibo',
                'Operacion',
                ' Debe',
                ' Haber',
                'Saldo',

                // Agrega más columnas según tus necesidades
            ];
        }


    }

