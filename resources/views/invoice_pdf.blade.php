<html lang="es">

<head>
    <title>Hereled</title>
</head>
<style >

    header {
        position: fixed;
        top: 20px;
        /* Cambia el valor según sea necesario */
        left: 0px;
        right: 0px;
        background-color: rgba(173, 216, 230, 0);
        height: 20px;
    }

    footer {
        position: fixed;
        bottom: 50px;
        left: 0px;
        right: 0px;
        background: #F4F4F4;
        height: 100px;
    }

    body {
        font-family: 'Roboto Condensed', sans-serif;
        font-size: 14px;
        margin-top: 240px;
        margin-bottom: 100px;
        margin-left: 5px;
        margin-right: 5px;

    }

    /*.page-num:before {*/
    /*    content: counter(page);*/
    /*}*/

    /*.page-num {*/
    /*    margin-top: 5px;*/
    /*}*/

    .page-break {
        page-break-after: always;
    }



    .m-0 {
        margin: 0px;
    }

    .p-0 {
        padding: 0px;
    }

    .pt-5 {
        padding-top: 5px;
    }

    .mt-10 {
        margin-top: 10px;

    }

    .text-center {
        text-align: center !important;
    }

    .w-100 {
        width: 100%;
    }

    .w-50 {
        width: 50%;
    }

    .w-35 {
        width: 35%;
    }

    .w-30 {
        width: 30%;
    }

    .w-85 {
        width: 85%;
    }

    .w-15 {
        width: 15%;
    }

    .logo img {
        width: 180px;
        height: 80px;
        margin-left: 40%;
    }

    .gray-color {
        color: #5D5D5D;
    }

    .text-bold {
        font-weight: bold;
    }

    .border {
        border: 1px solid black;
    }

    table tr,
    th,
    td {
        /* border-bottom: 1px solid #d2d2d2; */
        /* border: 1px solid #d2d2d2; */
        border-collapse: collapse;
        padding: 7px 8px;
    }

    table tr th {
        /* border-bottom: 1px solid #d2d2d2; */
        background: #F4F4F4;
        font-size: 15px;
    }

    table tr td {
        font-size: 11px;
        /* border-bottom: 1px solid #d2d2d2; */
    }
    .table-prod tr {
        border-top: 1px solid gray;
    }

    footer {
        border-top: 1px solid gray;
    }
    table {

        border-collapse: collapse;
        /* border-bottom: 1px solid #d2d2d2; */
    }

    .box-text p {
        line-height: 11px;
        font-size: 12px;
    }

    .float-left {
        float: left;
    }

    .total-part {
        font-size: 16px;
        line-height: 5px;
    }

    .total-right p {
        padding-right: 5px;
    }

    @page {
        margin: 15px;
    }

    .page-number:before {
        content: "Página " counter(page);
    }

    .total-pages:after {
        content: counter(page);
    }

    .footer {
        display: table;
        width: 100%; /* Span the full width of the container */
        padding: 10px; /* Add padding for spacing */
        padding-bottom: 40px;
    }

.notas {
    display: table-cell;
    float: left; /* Float the notes section to the left */
    width: 40%; /* Set the width */
}

.notas p {
    margin: 0; /* Remove default paragraph margins */
}

.datos-factura {
    display: table-cell;
    float: right; /* Float the data section to the right */
    width: 59%; /* Set the width to 49% */
    text-align: right; /* Align text to the right */
    margin-right: 5px;
}

.datos-factura p {
    margin: 0; /* Remove default paragraph margins */
}

.datos-factura span {
    padding-top: 1px;
    display: block;
    margin-bottom: 5px; /* Add spacing between data items */
}

.datos-factura span.titulo {
    font-size: 14px; /* Make titles bold */
}
.datos-factura span.titulo2 {
    font-size: 16px; /* Make titles bold */
}

</style>
<header>

    {{-- <div class="head-title">
        <h1 class="text-center m-0 p-0">Comprobante De Tipo : </h1>
    </div> --}}
    <div class="add-detail mt-10">
        <div class="w-50 float-left mt-10">
            <p class="m-0 pt-5 text-bold w-100">{{strtoupper($data['comprobante_tipo'])}}
                {{-- <span class="gray-color">#1</span></p> --}}
            <p class="m-0 pt-5 text-bold w-100">Nº - <span
                    class="gray-color">{{$data['invoice_number']}}</span></p>
            <p class="m-0 pt-5 text-bold w-100">Fecha - <span class="gray-color">{{$data['created_at']}}</span></p>
            @if($data['modificado'])
            <p class="m-0 pt-5 text-bold w-100"><span>Modificado</span></p>
            @endif
        </div>
        <div class="w-50 float-left logo mt-10">
            <img src="{{ public_path('assets/images/Logo_hereled2.png') }}" alt="Logo" width="90%" height="10%">
        </div>
        <div style="clear: both;"></div>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">

        <table class="table table-prod w-100 float-left">
            <tr>
                <td>
                    <div class="box-text w-100">
                        <p>Razon Social: <strong>{{$data['buyer']['codigo'].' '.$data['buyer']['nombre']}}</strong></p>
                        <p>Direccion: {{$data['buyer']['direccion']??''}}</p>
                        <p>Cuit: {{$data['buyer']['cuit']??''}}</p>
                        <p>Telefono: {{$data['buyer']['telefono']??''}}</p>
                    </div>
                </td>
                <td>
                    <div class="box-text w-100">
                        <p>Vendedor: {{$data['seller']['vendedor']}}</p>
                        <p>Notas: {{$data['buyer']['notas']}}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</header>

<body>
    <!-- ... (contenido del cuerpo) ... -->

            {{-- @dd($data) --}}
            @php
                $pageBreakCounter = 1;
                $countProducts = 0;
            @endphp

            <div class="table-section bill-tbl w-100 mt-10">
            @foreach($data['product'] as $key => $value)

                @if ($countProducts ==  0)<!--Si se va a listar la primera tanda de productos, genero la cabecera-->

                <table class="table table-prod w-100 mt-10">
                    <tr>
                        <th class="w-15">Codigo</th>
                        <th class="w-30">Producto</th>
                        <th class="w-15">Cant</th>
                        <th class="w-10">P. un s/Desc</th>
                        <th class="w-15">Desc</th>
                        {{--<th class="w-10">Dto$</th>--}}
                        <th class="w-20">Subtotal</th>
                    </tr>
                @endif
                <tr align= "center">
                    <td>{{$value['codigo']}}</td>
                    <td  align= "left">{{$value['nombre']}}</td>
                    <td>{{$value['cantidad']}}</td>
                    <td>${{$value['precio_sin_descuento']}}</td>
                    <td>{{($value['descuento_porcentual']>0?$value['descuento_porcentual'].'%':'')}}</td>
                    <td>${{$value['subtotal']}}</td>
                </tr>
                @php
                    $totalPages = ceil(count($data['product']) / 17); // División total de productos por página
                    $countProducts++;
                @endphp

            @if ($countProducts ==  17)

                @php
                    $countProducts = 0;
                @endphp

                <footer>

                    <div class="total-part" >
                        <div class="total-left w-50 float-left box-text" align="left">
                            <span>
                                <p> &nbsp;</p>
                            </span>
                        </div>
                        <div class="total-left w-35 float-left" align="right">
                            <p>&nbsp; </p>
                        </div>
                        <div class="total-right w-15 float-left text-bold" align="right">
                            <p>&nbsp;</p>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div>
                        <span class="box-text">
                            <p> &nbsp;</p>
                        </span>
                    </div>
                    <div class="page-number"> de
                        {{$totalPages}}
                    </div>
                    <br>
                        <!--Si tiene mas de 17 productos a mostrar, creo un nuevo corte de pagina-->
                        @if(count($data['product'])>17)
                            <div class="page-break"></div>
                        @endif
                </footer>

                @endif
                @if ($countProducts ==  0)<!--Si finalizo el listado de la primera tanda de productos, cierro la tabla-->
                    </table>
                @endif

            @endforeach

                <footer>
                <div class="footer">
                    <div class="notas">
                        <p><b>Notas:</b> <?php echo $data['notas']; ?></p>
                    </div>
                    <div class="datos-factura">
                        <span class="titulo"><b>Subtotal S/Desc:</b>  $<?php echo $data['subtotal']; ?></span>
                        <span class="titulo"><b>Descuento:</b>  $<?php echo $data['total_descuento']; ?></span>
                        <span class="titulo2"><b>Total:  $<?php echo $data['total']; ?></b></span>
                    </div>
            <br>
{{--            Página <span class="page-num"> </span>--}}
                </div>
                <div class="page-number"> de
                    {{$totalPages}}
                </div>
{{--            </br></br>--}}
           
        </footer>
    </div>
</body>

</html>



