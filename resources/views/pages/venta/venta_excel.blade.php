<table>
    <!-- Cabecera con la información de la venta -->
    <thead>
        <tr>
            <th colspan="5" style="text-align: center;"><strong>Datos de la Venta</strong></th>
        </tr>
        <tr>
            <th>Fecha</th>
            <th>Tipo de Comprobante</th>
            <th>Número de Comprobante</th>
        </tr>
        <tr>
            <td>{{ $data['created_at'] }}</td>
            <td>{{ $data['comprobante_tipo'] }}</td>
            <td>{{ $data['invoice_number'] }}</td>
        </tr>
        <tr>
            <th>Vendedor</th>
            <th>Cliente</th>
        </tr>
        <tr>
            <td>{{ $data['seller']['vendedor'] }}</td>
            <td>{{ $data['buyer']['codigo'].' '.$data['buyer']['nombre'] }}</td>
        </tr>
    </thead>
    <tr>
        <th ></th>
    </tr>
    <!-- Detalle de productos -->
    <thead>
        <tr>
            <th><strong>Producto</strong></th>
            <th><strong>Código</strong></th>
            <th><strong>Cantidad</strong></th>
            <th><strong>Precio s/Descuento</strong></th>
            <th><strong>Descuento</strong></th>
            <th><strong>Subtotal</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['product'] as $detalle)
        <tr>
            <td>{{ $detalle['nombre'] }}</td>
            <td>{{ $detalle['codigo'] }}</td>
            <td>{{ $detalle['cantidad'] }}</td>
            <td>{{ $detalle['precio_sin_descuento'] }}</td>
            <td>{{ $detalle['descuento_porcentual'] }}</td>
            <td>{{ $detalle['subtotal'] }}</td>
        </tr>
        @endforeach
    </tbody>

    <!-- Pie de la venta -->
    <tfoot>
        <tr>
            <th colspan="4" style="text-align: right;">Subtotal</th>
            <td>{{ $data['subtotal'] }}</td>
        </tr>
        <tr>
            <th colspan="4" style="text-align: right;">Total Descuento</th>
            <td>{{ $data['total_descuento'] }}</td>
        </tr>
        <tr>
            <th colspan="4" style="text-align: right;">Total</th>
            <td>{{ $data['total'] }}</td>
        </tr>
    </tfoot>
</table>
