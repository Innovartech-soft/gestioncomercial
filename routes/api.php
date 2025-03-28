<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RubroApiController;
use App\Http\Controllers\Api\MarcaApiController;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\Api\ClienteApiController;
use App\Http\Controllers\Api\ProveedorApiController;
use Illuminate\Support\Facades\Routes;
use App\Http\Middleware\Cors;
use App\Http\Controllers\Api\ListaApiController;
use App\Http\Controllers\Api\ParametroApiController;
use App\Http\Controllers\Api\VendedorApiController;
use App\Http\Controllers\Api\ChequeApiController;
use App\Http\Controllers\Api\VentaApiController;
use App\Http\Controllers\Api\MetodoDePagoApiController;
use App\Http\Controllers\Api\DiarioCajaApiController;

Route::get('register', [AuthController::class, 'register']);


Route::post('login', [AuthController::class, 'login'])->middleware('throttle:'.env('THROTTLE_STORE_TIME'));

Route::middleware('cors')->group(function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('user', [AuthController::class, 'userPerfile']);
        Route::get('listadorubros', [RubroApiController::class, 'listadoRubros']);
        Route::get('rubro/{id}', [RubroApiController::class, 'obtenerRubroId']);

        Route::get('listadomarcas', [MarcaApiController::class, 'listadoMarcas']);
        Route::get('marca/{id}', [MarcaApiController::class, 'obtenerMarcaId']);

        Route::get('listadoproductos', [ProductoApiController::class, 'listadoProductos']);
        Route::get('producto/{id}', [ProductoApiController::class, 'obtenerProductoId']);
        Route::get('productos/stockstatus', [ProductoApiController::class, 'obtenerStockDeProducto']);

        Route::get('listadoclientes', [ClienteApiController::class, 'listadoClientes']);
        Route::get('cliente/{id}', [ClienteApiController::class, 'obtenerClienteId']);
        Route::get('clientesDeudores', [ClienteApiController::class, 'countClientesDeudores']);

        Route::get('listadoproveedores', [ProveedorApiController::class, 'listadoProveedores']);
        Route::get('proveedor/{id}', [ProveedorApiController::class, 'obtenerProveedorId']);

        Route::get('listadolistas', [ListaApiController::class, 'listadoListas']);
        Route::get('lista/{id}', [ListaApiController::class, 'obtenerListaId']);

        Route::get('listadovendedores', [VendedorApiController::class, 'listadoVendedores']);
        Route::get('vendedor/{id}', [VendedorApiController::class, 'obtenerVendedorId']);

        Route::get('parametros', [ParametroApiController::class, 'obtenerParametros']);

        Route::get('listadocheques', [ChequeApiController::class, 'listadoCheques']);
        Route::get('cheque/{id}', [ChequeApiController::class, 'obtenerChequeId']);
        Route::get('chequesByCliente/{id}', [ChequeApiController::class, 'getChequesByClienteId']);
        Route::post('storeCheque', [ChequeApiController::class, 'storeCheque']);
        Route::get('getchequesDisponibles', [ChequeApiController::class, 'countChequesDisponibles'])->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::get('listadometodosdepago', [MetodoDePagoAPiController::class, 'listadoMetodosDePago']);

        Route::post('storeventa/{venta}', [VentaApiController::class, 'storeVenta'])->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::get('getTipoVentas', [VentaApiController::class, 'getTipoVentas']);
        Route::get('getVenta/{id}', [VentaApiController::class, 'getVenta']);
        Route::get('exportpdf/{id}', [VentaApiController::class, 'exportVentaPdf']);
        Route::post('setAbrirCaja', [DiarioCajaApiController::class, 'setAbrirCaja'])->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
    });


    Route::middleware('auth:sanctum')->group(function () {
        //Route::post('storeVenta',[VentaApiController::class,'storeVenta'])->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('test',[ChequeApiController::class, 'test']);
    });
});
