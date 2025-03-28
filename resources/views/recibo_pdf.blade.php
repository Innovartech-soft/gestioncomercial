<html>

<head>
    <title>How To Generate Invoice PDF In Laravel 9 - Techsolutionstuff</title>
</head>
<style type="text/css">
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
        bottom: 100px;
        left: 0px;
        right: 0px;
        /* background-color: lightblue; */

        height: 10px;
    }

    body {
        font-family: 'Roboto Condensed', sans-serif;
        font-size: 14px;
        margin-top: 500px;
        margin-bottom: 100px;
        /* margin-left: 50px;
        margin-right: 50px; */

    }

    .page-num:before {
        content: counter(page);
    }

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

    .w-85 {
        width: 85%;
    }

    .w-15 {
        width: 15%;
    }

    .logo img {
        width: 200px;
        height: 60px;
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
        border: 1px solid #d2d2d2;
        border-collapse: collapse;
        padding: 7px 8px;
    }

    table tr th {
        background: #F4F4F4;
        font-size: 15px;
    }

    table tr td {
        font-size: 13px;
    }

    table {
        border-collapse: collapse;
    }

    .box-text p {
        line-height: 10px;
    }

    .float-left {
        float: left;
    }

    .total-part {
        font-size: 16px;
        line-height: 12px;
    }

    .total-right p {
        padding-right: 20px;
    }
</style>
<header>
    <div class="head-title">
        <h1 class="text-center m-0 p-0">Recibo </h1>
    </div>
    <div class="add-detail mt-10">
        <div class="w-50 float-left mt-10">
            <p class="m-0 pt-5 text-bold w-100">Invoice Id - <span class="gray-color">#1</span></p>
            <p class="m-0 pt-5 text-bold w-100">Order Id - <span class="gray-color">AB123456A</span></p>
            <p class="m-0 pt-5 text-bold w-100">Order Date - <span class="gray-color">{{now()}}</span></p>
        </div>
        <div class="w-50 float-left logo mt-10">
            <img src="{{ public_path('assets/images/Logo_hereled.png') }}" alt="Logo" width="90%" height="10%">
        </div>
        <div style="clear: both;"></div>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 ">
            <tr>
                <th class="w-50">Comprador</th>
                <th class="w-50">Vendedor</th>
            </tr>
            <tr>
                <td>
                    <div class="box-text">
                        <p>Nombre: {{$data['buyer']['nombre']}}</p>
                        <p>Direccion: {{$data['buyer']['direccion']}},</p>
                        <p>Ciudad: {{$data['buyer']['ciudad']}}</p>
                        <p>Localidad: {{$data['buyer']['localidad']}}</p>
                        <p>Codigo Postal: {{$data['buyer']['codigo_postal']}}</p>
                        <p>Pais: {{$data['buyer']['pais']}}</p>
                    </div>
                </td>
                <td>
                    <div class="box-text">
                        <p>Nombre: {{$data['seller']['nombre']}}</p>
                        <p>Direccion: {{$data['seller']['direccion']}}</p>
                        <p>Ciudad: {{$data['seller']['ciudad']}}</p>
                        <p>Localidad: {{$data['seller']['localidad']}}</p>
                        <p>Telefono: {{$data['seller']['telefono']}}</p>
                        <p>Codigo Postal: {{$data['seller']['codigo_postal']}}</p>
                        <p>Localidad: {{$data['seller']['pais']}}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-100">Metodo De Pago</th>
                {{-- <th class="w-50">Shipping Method</th> --}}
            </tr>
            <tr>
                <td>Cash On Delivery</td>
                {{-- <td>Free Shipping - Free Shipping</td> --}}
            </tr>
        </table>
    </div>
</header>

<body>
    <!-- ... (contenido del cuerpo) ... -->
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-50">Descripcion</th>
                <th class="w-50">Codigo</th>
                <th class="w-50">Unidades</th>
                <th class="w-50">Cant.</th>
                <th class="w-50">Precio</th>
                <th class="w-50">Descuento</th>
                <th class="w-50">Sub Total</th>
            </tr>
            <tr align="center">
                <td>M101</td>
                <td>Andoid Smart Phone</td>
                <td>$500.2</td>
                <td>3</td>
                <td>$1500</td>
                <td>$50</td>
                <td>$1550.20</td>
            </tr>
            <tr align="center">
                <td>M102</td>
                <td>Andoid Smart Phone</td>
                <td>$250</td>
                <td>2</td>
                <td>$500</td>
                <td>$50</td>
                <td>$550.00</td>
            </tr>
            <tr align="center">
                <td>T1010</td>
                <td>Andoid Smart Phone</td>
                <td>$1000</td>
                <td>5</td>
                <td>$5000</td>
                <td>$500</td>
                <td>$5500.00</td>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="total-part">
                        <div class="total-left w-85 float-left" align="right">
                            <p>Sub Total</p>
                            <p>Tax (18%)</p>
                            <p>Total Payable</p>
                        </div>
                        <div class="total-right w-15 float-left text-bold" align="right">
                            <p>$7600</p>
                            <p>$400</p>
                            <p>$8000.00</p>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- ... (contenido del cuerpo) ... -->
    <footer>
        <span class="page-num"></span>
    </footer>
    <div class="page-break"></div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-50">Descripcion</th>
                <th class="w-50">Codigo</th>
                <th class="w-50">Unidades</th>
                <th class="w-50">Cant.</th>
                <th class="w-50">Precio</th>
                <th class="w-50">Descuento</th>
                <th class="w-50">Sub Total</th>
            </tr>
            <tr align="center">
                <td>M101</td>
                <td>Andoid Smart Phone</td>
                <td>$500.2</td>
                <td>3</td>
                <td>$1500</td>
                <td>$50</td>
                <td>$1550.20</td>
            </tr>
            <tr align="center">
                <td>M102</td>
                <td>Andoid Smart Phone</td>
                <td>$250</td>
                <td>2</td>
                <td>$500</td>
                <td>$50</td>
                <td>$550.00</td>
            </tr>
            <tr align="center">
                <td>T1010</td>
                <td>Andoid Smart Phone</td>
                <td>$1000</td>
                <td>5</td>
                <td>$5000</td>
                <td>$500</td>
                <td>$5500.00</td>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="total-part">
                        <div class="total-left w-85 float-left" align="right">
                            <p>Sub Total</p>
                            <p>Tax (18%)</p>
                            <p>Total Payable</p>
                        </div>
                        <div class="total-right w-15 float-left text-bold" align="right">
                            <p>$7600</p>
                            <p>$400</p>
                            <p>$8000.00</p>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <footer>
        <span class="page-num"></span>
    </footer>
</body>


</html>