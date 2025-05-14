<?php
use App\Http\Controllers\LogController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\RubroController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ComisionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParametroController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\DiarioCajaController;
use App\Http\Controllers\ImportacionController;
use App\Http\Controllers\ListaGananciaController;
use App\Http\Controllers\CuentaCorrienteController;
use App\Http\Controllers\ImpresionDeFacturaController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>'auth'], function(){
    Route::group(['prefix' => '/'], function(){
        Route::get('/',[DashboardController::class , 'index'])->name('dashboard.index');
    });
    Route::get('searchByFechaHoy',[DashboardController::class , 'searchByFechaHoy'])->name('dashboard.searchByFechaHoy');

    //Caja Diaria routes Start
    Route::post('setAbrirCaja', ['as' => 'diariocaja.setAbrirCaja', 'uses' => 'App\Http\Controllers\Api\DiarioCajaApiController@setAbrirCaja']);
    Route::get('getEstadoCaja', ['as' => 'diariocaja.getEstadoCaja', 'uses' => 'App\Http\Controllers\Api\DiarioCajaApiController@getEstadoCaja']);
    Route::get('getReaperturaCaja', ['as' => 'diariocaja.getReaperturaCaja', 'uses' => 'App\Http\Controllers\Api\DiarioCajaApiController@getReaperturaCaja']);
    Route::get('setCerrarCaja', ['as' => 'diariocaja.setCerrarCaja', 'uses' => 'App\Http\Controllers\Api\DiarioCajaApiController@setCerrarCaja']);
    Route::group(['prefix' => 'cajadiaria'],function(){

        Route::get('index',[DiarioCajaController::class, 'index'])->name('cajadiaria.index');
        Route::post('/exportarcajadiariaexcel', [DiarioCajaController::class,'exportarExcel'])->name('cajadiaria.exportarExcel');


    });
    //Rubro routes Start
    Route::group(['prefix' => 'rubro'], function(){
        Route::get('index',[RubroController::class , 'index'])->name('rubro.index');
        Route::get('create',[RubroController::class , 'create'])->name('rubro.create');
        Route::get('{id}/edit',[RubroController::class , 'edit'])->name('rubro.edit');
        Route::post('store',[RubroController::class , 'store'])->name('rubro.store')->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('{id}/update',[RubroController::class , 'update'])->name('rubro.update');
        Route::delete('delete/{id}',[RubroController::class , 'destroy'])->name('rubro.destroy');

        Route::get('getByAcronimoJSON/{id}/{acronimo}','RubroController@getByAcronimoJSON')->name('rubro.getByAcronimoJSON');
    });
    //Rubro routes End

    //Producto routes Start
    Route::group(['prefix' => 'producto'],function(){
        Route::get('index',[ProductoController::class , 'index'])->name('producto.index');
        Route::get('create',[ProductoController::class , 'create'])->name('producto.create');
        Route::get('{id}/edit',[ProductoController::class , 'edit'])->name('producto.edit');
        Route::post('store',[ProductoController::class , 'store'])->name('producto.store')->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('{id}/update',[ProductoController::class , 'update'])->name('producto.update');
        Route::delete('producto/{id}',[ProductoController::class , 'destroy'])->name('producto.destroy');
        Route::get('indexBuscar',[ProductoController::class , 'indexBuscar'])->name('producto.indexBuscar');
        Route::get('indexUpdate',[ProductoController::class , 'indexUpdate'])->name('producto.indexUpdate');
        Route::get('indexByStockMinimo',[ProductoController::class , 'indexByStockMinimo'])->name('producto.indexByStockMinimo');
        Route::post('updateStock/{id}',[ProductoController::class , 'updateStock'])->name('producto.updateStock');
        Route::post('updatePrecio/{id}',[ProductoController::class , 'updatePrecio'])->name('producto.updatePrecio');
        Route::get('findProductoById/{id}', ['as' => 'producto.findProductoById', 'uses' => 'App\Http\Controllers\Api\ProductoApiController@findProductoById']);
        Route::post('/ExportarProductosExcel', 'ProductoController@exportarExcel')->name('producto.exportarExcel');
        Route::get('exportarstockexcel', [ProductoController::class,'exportarStockExcel'])->name('producto.exportarstockexcel');
        Route::get('/exportarHistoricoStockExcel/{idProducto}', [ProductoController::class,'exportarHistoricoStockExcel'])->name('producto.exportarHistoricoStockExcel');
        Route::post('ajustarPrecios', 'ProductoController@ajustarPrecios')->name('producto.ajustarPrecios');

        Route::get('getHistorialByProductoJSON/{id}',[ProductoController::class , 'getHistorialByProductoJSON'])->name('venta.getHistorialByProductoJSON');
    });

    //Producto routes End

    //Proveedor routes Start
    Route::group(['prefix' => 'proveedor'],function(){
        Route::get('index',[ProveedorController::class , 'index'])->name('proveedor.index');
        Route::get('create',[ProveedorController::class , 'create'])->name('proveedor.create');
        Route::get('{id}/edit',[ProveedorController::class , 'edit'])->name('proveedor.edit');
        Route::post('store',[ProveedorController::class , 'store'])->name('proveedor.store')->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('{id}/update',[ProveedorController::class , 'update'])->name('proveedor.update');
        Route::delete('proveedor/{id}',[ProveedorController::class , 'destroy'])->name('proveedor.destroy');
    });
    //Proveedor routes End

    //Cliente routes Start
    Route::group(['prefix' => 'cliente'],function(){
        Route::get('index',[ClienteController::class , 'index'])->name('cliente.index');
        Route::get('create',[ClienteController::class , 'create'])->name('cliente.create');
        Route::get('{id}/edit',[ClienteController::class , 'edit'])->name('cliente.edit');
        Route::post('store',[ClienteController::class , 'store'])->name('cliente.store')->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('{id}/update',[ClienteController::class , 'update'])->name('cliente.update');
        Route::delete('cliente/{id}',[ClienteController::class , 'destroy'])->name('cliente.destroy');
        Route::get('indexBuscar',[ClienteController::class , 'indexBuscar'])->name('cliente.indexBuscar');
    });

    //Cliente routes End

    //Marca routes Start
    Route::group(['prefix' => 'marca'], function(){
        Route::get('index',[MarcaController::class , 'index'])->name('marca.index');
        Route::get('create',[MarcaController::class , 'create'])->name('marca.create');
        Route::get('{id}/edit',[MarcaController::class , 'edit'])->name('marca.edit');
        Route::post('store',[MarcaController::class , 'store'])->name('marca.store')->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('{id}/update',[MarcaController::class , 'update'])->name('marca.update');
        Route::delete('delete/{id}',[MarcaController::class , 'destroy'])->name('marca.destroy');
    });
    //Marca routes End

    //Cuenta Corriente routes Start
    Route::group(['prefix' => 'cuentacorriente'], function(){
            Route::get('index',[CuentaCorrienteController::class , 'index'])->name('cuentacorriente.index');
            Route::get('create',[CuentaCorrienteController::class , 'create'])->name('cuentacorriente.create');
            Route::get('{id}/edit',[CuentaCorrienteController::class , 'edit'])->name('cuentacorriente.edit');
            Route::post('store',[CuentaCorrienteController::class , 'store'])->name('cuentacorriente.store');
            Route::post('{id}/update',[CuentaCorrienteController::class , 'update'])->name('cuentacorriente.update');
            Route::delete('delete/{id}',[CuentaCorrienteController::class , 'destroy'])->name('cuentacorriente.destroy');
            Route::post('indexByCliente',[CuentaCorrienteController::class , 'indexByCliente'])->name('cuentacorriente.indexByCliente');
            Route::get('indexByClienteReturn/{id}',[CuentaCorrienteController::class , 'indexByClienteReturn'])->name('cuentacorriente.indexByClienteReturn');
            Route::post('/exportarcuentacorriente', [CuentaCorrienteController::class,'exportarCuentaCorrienteExcel'])->name('cuentacorriente.exportarExcel');
    });
    //Cuenta Corriente routes End

    //Recibo routes Start
    Route::group(['prefix' => 'recibo'], function(){
        Route::get('index',[ReciboController::class , 'index'])->name('recibo.index');
        Route::get('create',[ReciboController::class , 'create'])->name('recibo.create');
        Route::get('{id}',[ReciboController::class , 'show'])->name('recibo.show');
        Route::get('{id}/edit',[ReciboController::class , 'edit'])->name('recibo.edit');
        Route::post('store',[ReciboController::class , 'store'])->name('recibo.store')->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('{id}/update',[ReciboController::class , 'update'])->name('recibo.update');
        Route::delete('delete/{id}',[ReciboController::class , 'destroy'])->name('recibo.destroy');
        Route::get('/recibo/data', [ReciboController::class, 'getData'])->name('recibo.data');
        Route::post('exportreciboexcel', [ReciboController::class,'exportarExcel'])->name('recibo.exportarexcel');
        Route::post('exportarreciboegresoexcel', [ReciboController::class,'exportarReciboEgresoExcel'])->name('recibo.exportreciboegresoexcel');
    });
    //Recibo routes End

    //Venta routes Start
    Route::group(['prefix' => 'venta'], function(){
        Route::get('index',[VentaController::class , 'index'])->name('venta.index');
        Route::get('gestionVentas',[VentaController::class , 'gestionVenta'])->name('venta.gestionVenta');
        Route::get('indexCerradas',[VentaController::class , 'indexCerradas'])->name('venta.indexCerradas');
        Route::get('indexAnuladas',[VentaController::class , 'indexAnuladas'])->name('venta.indexAnuladas');
        Route::get('create',[VentaController::class , 'create'])->name('venta.create');
        Route::get('{id}/edit',[VentaController::class , 'edit'])->name('venta.edit');
        Route::get('{id}',[VentaController::class , 'show'])->name('venta.show');
        Route::post('store',[VentaController::class , 'store'])->name('venta.store')->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('{id}/update',[VentaController::class , 'update'])->name('venta.update');
        Route::delete('delete/{id}',[VentaController::class , 'destroy'])->name('venta.destroy');
        Route::post('ventacerradaexport', [VentaController::class,'exportarVentaCerrada'])->name('venta.cerrada.export');
        Route::post('imprimirpdfventa/{id}',[ImpresionDeFacturaController::class , 'descargarFactura'])->name('venta.imprimirventa');
        Route::post('exportarFactura',[ImpresionDeFacturaController::class , 'exportarFactura'])->name('venta.exportarventa');
        Route::get('verpdfventa/{id}',[ImpresionDeFacturaController::class , 'verFactura'])->name('venta.verpdfventa');
        Route::get('getVentasByCliente/{id}', ['as' => 'venta.getVentasByCliente', 'uses' => 'App\Http\Controllers\Api\VentaApiController@getVentasByCliente']);
        Route::get('getPagosByVentaJSON/{id}',[VentaController::class , 'getPagosByVentaJSON'])->name('venta.getPagosByVentaJSON');

    });
    //Venta routes End

    Route::group(['prefix'=> 'parametros'],function(){
        Route::get('edit',[ParametroController::class , 'edit'])->name('parametros.edit');
        Route::post('store',[ParametroController::class , 'store'])->name('parametros.store');
        Route::post('update',[ParametroController::class , 'update'])->name('parametros.update');
    });

    Route::group(['prefix' => 'lista'],function(){
        Route::get('index',[ListaController::class , 'index'])->name('lista.index');
        Route::get('create',[ListaController::class , 'create'])->name('lista.create');
        Route::get('{id}/edit',[ListaController::class , 'edit'])->name('lista.edit');
        Route::post('store',[ListaController::class , 'store'])->name('lista.store')->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('{id}/update',[ListaController::class , 'update'])->name('lista.update');
        Route::delete('delete/{id}',[ListaController::class , 'destroy'])->name('lista.destroy');
    });
    
    Route::group(['prefix' => 'listaganancia'],function(){
        Route::get('index',[ListaGananciaController::class , 'index'])->name('listaganancia.index');
        Route::get('create',[ListaGananciaController::class , 'create'])->name('listaganancia.create');
        Route::get('{id}/edit',[ListaGananciaController::class , 'edit'])->name('listaganancia.edit');
        Route::post('store',[ListaGananciaController::class , 'store'])->name('listaganancia.store')->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('{id}/update',[ListaGananciaController::class , 'update'])->name('listaganancia.update');
        Route::delete('delete/{id}',[ListaGananciaController::class , 'destroy'])->name('listaganancia.destroy');
    });

    Route::group(['prefix' => 'vendedor'],function(){
        Route::get('index',[VendedorController::class , 'index'])->name('vendedor.index');
        Route::get('create',[VendedorController::class , 'create'])->name('vendedor.create');
        Route::get('{id}/edit',[VendedorController::class , 'edit'])->name('vendedor.edit');
        Route::post('store',[VendedorController::class , 'store'])->name('vendedor.store')->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('{id}/update',[VendedorController::class , 'update'])->name('vendedor.update');
        Route::delete('delete/{id}',[VendedorController::class , 'destroy'])->name('vendedor.destroy');
        Route::post('exportarvendedorexcel', [VendedorController::class,'exportarExcel'])->name('vendedor.exportarexcel');
    });

    Route::group(['prefix' => 'cheque'],function(){
        Route::get('index',[ChequeController::class , 'index'])->name('cheque.index');
        Route::get('create',[ChequeController::class , 'create'])->name('cheque.create');
        Route::get('{id}/edit',[ChequeController::class , 'edit'])->name('cheque.edit');
        Route::post('store',[ChequeController::class , 'store'])->name('cheque.store')->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('{id}/update',[ChequeController::class , 'update'])->name('cheque.update');
        Route::delete('delete/{id}',[ChequeController::class , 'destroy'])->name('cheque.destroy');
        Route::get('findChequeById/{id}', ['as' => 'cheque.findChequeById', 'uses' => 'App\Http\Controllers\Api\ChequeApiController@obtenerChequeId']);
    });

    Route::group(['prefix' => 'comision'],function(){
        Route::get('{id}/edit',[ComisionController::class , 'edit'])->name('comision.edit');
        Route::post('store',[ComisionController::class , 'store'])->name('comision.store')->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
        Route::post('{id}/update',[ComisionController::class , 'update'])->name('comision.update');
        Route::delete('delete/{id}',[ComisionController::class , 'destroy'])->name('comision.destroy');
        Route::get('getComisionesJSON/{id}/{fechaDesde}/{fechaHasta}',[ComisionController::class , 'getComisionesJSON'])->name('comision.getComisionesJSON');
        Route::post('pagarComisiones',[ComisionController::class , 'pagarComisiones'])->name('comision.pagarComisiones');
    });

    Route::group(['prefix' => 'logs'],function(){
        Route::get('index',[LogController::class , 'index'])->name('logs.index');
        Route::get('data', [LogController::class, 'getData'])->name('logs.data');
    });

    Route::group(['prefix' => 'importar'],function(){
        Route::get('index',[ImportacionController::class , 'index'])->name('importar.index');
        Route::post('store',[ImportacionController::class , 'store'])->name('importar.store');
    });
    Route::group(['prefix' => 'auth'], function(){
        //Route::get('login', function () { return view('pages.auth.login'); })->name('login');
        Route::get('register', function () { return view('pages.auth.register'); })->name('register');
        Route::get('{id}/edit',[LoginController::class , 'edit'])->name('auth.edit');
        Route::delete('delete/{id}',[LoginController::class , 'destroy'])->name('auth.destroy');
        Route::get('create',[LoginController::class , 'create'])->name('auth.create');
        Route::post('{id}/update',[LoginController::class , 'update'])->name('auth.update');
        Route::post('logout',[LoginController::class , 'logout'])->name('auth.logout');
        Route::get('index',[LoginController::class , 'index'])->name('auth.index');
        Route::post('register',[RegisterController::class , 'register'])->middleware('throttle:'.env('THROTTLE_STORE_TIME'));
    });

    //Testing - Rutas no funcionales
    Route::get('getGanancia/{id}',[VentaController::class , 'getGanancia'])->name('venta.getGanancia');

});



Route::group(['prefix' => 'auth'], function(){
    Route::get('login', function () { return view('pages.auth.login'); })->name('login');
    // Route::get('register', function () { return view('pages.auth.register'); })->name('register');
    Route::post('login', [LoginController::class , 'login']);
});



Route::group(['prefix' => 'error'], function(){
    Route::get('404', function () { return view('pages.error.404'); });
    Route::get('500', function () { return view('pages.error.500'); });
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// 404 for undefined routes
Route::any('/{page?}',function(){
    return View::make('pages.error.404');
})->where('page','.*');
