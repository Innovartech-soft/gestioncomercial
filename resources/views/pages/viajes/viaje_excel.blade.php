<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 5px;
        text-align: left;
    }

    /* Anchos específicos para cada columna */
    .col-num {
        width: 100%;
        colspan: 5;
    }

    .col-cliente {
        width: 100%;
        colspan: 5;
    }

    .col-venta {
        width: 100%;
        colspan: 5;
    }

    .col-importe {
        width: 100%;
        colspan: 5;
    }

    .col-to {
        width: 5%;
        colspan: 5;
    }

    .col-e {
        width: 5%;
        colspan: 5;
    }

    .col-te {
        width: 5%;
        colspan: 5;
    }

    .col-np {
        width: 5%;
        colspan: 5;
    }

    .col-monto {
        width: 100%;
        colspan: 5;
    }

    .col-saldo {
        width: 100%;
        colspan: 5;
    }
</style>

<table style="border: 2px solid black;">
    <thead>
        <tr>
            <th colspan="5" style="font-weight: bold; border: 2px solid black;">Repartidor: {{
                $viaje->repartidor->nombre }}</th>
            <th colspan="5" style="font-weight: bold; border: 2px solid black;">Fecha: {{
                \Carbon\Carbon::now()->format('d/m/Y') }}</th>
        </tr>
        <tr>
            <th colspan="2" class="col-num" style="font-weight: bold; border: 2px solid black;">N°</th>
            <th colspan="5" class="col-cliente" style="font-weight: bold; border: 2px solid black;">CLIENTE</th>
            <th class="col-venta" style="font-weight: bold; border: 2px solid black;">N° venta</th>
            <th colspan="4" class="col-importe" style="font-weight: bold; border: 2px solid black;">IMPORTE</th>
            <th class="col-to" style="font-weight: bold; border: 2px solid black;">TO</th>
            <th class="col-e" style="font-weight: bold; border: 2px solid black;">E</th>
            <th class="col-te" style="font-weight: bold; border: 2px solid black;">TE</th>
            <th class="col-np" style="font-weight: bold; border: 2px solid black;">NP</th>
            <th colspan="2" class="col-monto" style="font-weight: bold; border: 2px solid black;">MONTO</th>
            <th colspan="3" class="col-saldo" style="font-weight: bold; border: 2px solid black;">SALDO</th>
        </tr>
    </thead>
    <tbody>
        @php
        $currentCliente = null;
        // Modificamos el agrupamiento para agrupar por primera letra del vendedor
        $detallesAgrupados = $detalles->sortBy(function($detalle) {
        // Ordenar primero por primera letra del vendedor y luego por ID de cliente
        $letraVendedor = strtoupper(substr($detalle['vendedor'], 0, 1));
        return $letraVendedor . str_pad($detalle['cliente_id'], 10, '0', STR_PAD_LEFT);
        })->groupBy(function($detalle) {
        // Agrupar por la primera letra del vendedor
        return strtoupper(substr($detalle['vendedor'], 0, 1));
        });
        @endphp

        @foreach($detallesAgrupados as $letraVendedor => $grupo)
        {{-- Mostrar todos los registros del mismo grupo de letra --}}
        @foreach($grupo->sortBy('cliente_id') as $detalle)
        <tr>
            <td colspan="2" class="col-num" style="border: 2px solid black;">
                {{ $detalle['cliente_id'] }} - {{ strtoupper(substr($detalle['vendedor'], 0, 1)) }}
            </td>
            <td colspan="5" class="col-cliente" style="border: 2px solid black;">{{ $detalle['cliente'] }}</td>
            <td class="col-venta" style="border: 2px solid black;">{{ $detalle['venta_id'] }}</td>
            <td colspan="4" class="col-importe" style="border: 2px solid black; font-weight: bold;">${{
                number_format($detalle['importe'], 2) }}</td>
            <td class="col-to" style="border: 2px solid black;">{{ $detalle['to'] }}</td>
            <td class="col-e" style="border: 2px solid black;">{{ $detalle['e'] }}</td>
            <td class="col-te" style="border: 2px solid black;">{{ $detalle['te'] }}</td>
            <td class="col-np" style="border: 2px solid black;">{{ $detalle['np'] }}</td>
            <td colspan="2" class="col-monto" style="border: 2px solid black;">{{ $detalle['monto'] ? '' : '' }}
            </td>
            <td colspan="3" class="col-saldo" style="border: 2px solid black;">-${{ number_format($detalle['saldo'],
                2)
                }}
            </td>
        </tr>
        @endforeach

        <!-- Agregar espacio entre grupos de letras diferentes -->
        <tr>
            <td colspan="10" style="border: 2px solid black;">&nbsp;</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="font-weight: bold; border: 2px solid black;">Fecha De Creacion De Viaje:</td>
            <td colspan="7" style="border: 2px solid black;">{{ $viaje->created_at->format('d/m/Y') }}</td>
        </tr>
    </tfoot>
</table>