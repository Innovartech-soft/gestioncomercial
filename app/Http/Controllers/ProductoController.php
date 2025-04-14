<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\ProductoExportExcel;
use App\Exports\StockExportExcel;
use App\Exports\StockHistorialProductoExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Producto;
use App\Rubro;
use App\Marca;
use App\ProductoPrecioHistorico;
use App\Proveedor;
use App\ListaGanancia;
use DB;
use App\Logs;
use Illuminate\Support\Carbon;
use Illuminate\Http\Response;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::whereNull('deleted_at')->get();
        $listas = ListaGanancia::all();
        $rubros = Rubro::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $marcas = Marca::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $proveedores = Proveedor::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        return view('pages.producto.index',compact('productos','listas','rubros','marcas','proveedores'));
    }

    public function indexBuscar(Request $request)
    {   $sText = $request->input('buscar');
        $aBusqueda=array();
        //Si se ingreso más de una palabra, creo un array de las mismas
        if($sText!=""){
            $aBusqueda = explode(" ",$sText);
        }

        $requestAll = $request->all();
            //Si se escribio solo una palabra...
        if(count($aBusqueda)==1){
            $productos = Producto::where([['deleted_at',"=",NULL],['codigo','=',$sText]])
            ->orWhere([['codigo_interno','LIKE','%'.$sText.'%'],['deleted_at',"=",NULL]])
            ->orWhere([['nombre','LIKE','%'.$sText.'%'],['deleted_at',"=",NULL]])
            ->orWhere([['codigo_barras','LIKE',$sText],['deleted_at',"=",NULL]])->simplePaginate(25);
        }elseif(count($aBusqueda)>0){
            //Si escribo mas de una palabra, asumo que estoy buscando un "nombre"
                $query = Producto::query();
                foreach($aBusqueda as $nombre){
                    $query->where('nombre', 'like', '%' . $nombre . '%', 'and');
                }
                $productos = $query->whereNull('deleted_at')->get();
        }else{
            //Si no hubo palabras ingresadas, devolver el total de los registros
            $productos = Producto::whereNull('deleted_at')->get();
        }
        $productos->appends($requestAll);

        return view('pages.producto.index',compact('productos'));

    }

    public function indexByStockMinimo()
    {
        $productos = Producto::whereColumn('stock','<=','stock_minimo')->whereNull('deleted_at')->get();
        return view('pages.producto.index-stock',compact('productos'));
    }

    public function indexUpdate()
    {
        $productos = Producto::whereNull('deleted_at')->get();
        $listas = ListaGanancia::all();
        $rubros = Rubro::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $marcas = Marca::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $proveedores = Proveedor::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        return view('pages.producto.index-update',compact('productos','listas','rubros','marcas','proveedores'));
    }

    public function create()
    {
        $rubros = Rubro::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $marcas = Marca::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $listas = ListaGanancia::all();
        $proveedores = Proveedor::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $ivas = Producto::TIPO_IVA;

        return view('pages.producto.form',compact('rubros','marcas','listas','proveedores','ivas'));
    }

    public function store(Request $request)
    {
        $request->merge(['codigo_interno'=>Producto::generarCodigoInterno($request['id_rubro'])]);
        $request['es_dolar'] = ($request->es_dolar?1:0);
        $request['en_oferta'] = ($request->en_oferta?1:0);
        $request['no_comisionable'] = ($request->no_comisionable?1:0);

        $producto = Producto::create($request->all());
        $this->registrarEnLog('success', $producto->nombre);
        return redirect()->route('producto.index')->with('success','El Producto '.$producto->nombre.' ha sido creado correctamente');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $rubros = Rubro::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $marcas = Marca::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $listas = ListaGanancia::all();
        $proveedores = Proveedor::whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        $ivas = Producto::TIPO_IVA;

        return view('pages.producto.form',compact('rubros','marcas','proveedores','producto','ivas','listas'));
    }

    public function update(Request $request, $id)
    {
        $request['es_dolar'] = ($request->es_dolar?1:0);
        $request['en_oferta'] = ($request->en_oferta?1:0);
        $request['no_comisionable'] = ($request->no_comisionable?1:0);
        $producto = Producto::findOrFail($id);
        if($producto->id_rubro != $request['id_rubro'] ){
            $request->merge(['codigo_interno'=>$producto->updateCodigoInterno($request['id_rubro'])]);
        }
        $producto->update($request->all());
        $this->registrarEnLog('updated', $producto->nombre);

        return redirect()->route('producto.index')->with('updated','El Producto '.$producto->nombre.' ha sido modificado correctamente');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        if ($producto->delete()) {
            $this->registrarEnLog('deleted', $producto->nombre);
            return back()->with('success','El Producto '.$producto->nombre.' ha sido eliminado correctamente');
        } else {
            return back()->withError('El Producto '.$producto->nombre.' no pudo ser eliminado porque se encuentra asociado a otro elemento [Venta, Presupuesto, Nota Credito, Nota Debito]');
        }

    }

    //METODOS CUSTOM
    //Actualizar stock de un producto por su id
    public function updateStock(Request $request, $id){
        try {
            $producto = Producto::findOrFail($id);
            //$totalStock = $producto->stock + $request->cantidad;
            if(is_numeric($request->cantidad)){
                //$producto->stock = $totalStock;
                //$producto->update();

                $producto->updateStock($producto->id, $request->cantidad, 'Operacion: Ingreso manual', null, Auth::user()->id);

                $this->registrarEnLog('updatedStock', $producto->nombre);
                return redirect()->back()->with('success','El Stock del Producto '.$producto->nombre.' ha sido actualizado a '.$producto->stock);
            }
            return redirect()->back()->with('error','El Stock del Producto '.$producto->nombre.' no ha podido ser actualizado');

        } catch (\Throwable $th) {
           Log::alert('No se pudo actualizar el stock. ERROR: ' . $th->getMessage());
        }
    }

    public function updatePrecio(Request $request, $id){
        try {
            $producto = Producto::findOrFail($id);
            $precio = $request->precio;
            if($producto->precio_costo!=$precio&&is_numeric($precio)&&$precio>=0){
                $producto->precio_costo = $precio;
                $producto->save();
                $this->registrarEnLog('updatedPrecio', $producto->nombre.' a $'.$producto->precio_costo);
                return response()->json([
                    $producto->toArray(),
                    'precioActualizado' => true,
                    'precio_costo' => $producto->precio_costo,
                ], Response::HTTP_OK);
            }elseif(!is_numeric($precio)||$precio<0){
                return response()->json([
                     $producto->toArray(),
                     'precioActualizado' => false,
                     'precio_costo' => $producto->precio_costo,
                ], Response::HTTP_NOT_FOUND);
            }

        } catch (\Throwable $th) {
               Log::alert('No se pudo actualizar el precio. ERROR: ' . $th->getMessage());
               return response()->json([
                'message' => 'No se pudo actualizar el precio',
                'data' => null
                ], Response::HTTP_NOT_FOUND);
        }
    }
    //Actualiza precios en base al porcentaje ingresado y los filtros seleccionados
    public function ajustarPrecios(Request $request){
        if((request()->input('id_marca')!='' || request()->input('id_rubro')!='' || request()->input('id_proveedor')!='' ) && $request->input('valor')!='' && $request->input('valor')!=0 )
        {
            $consulta = Producto::when(request()->has('id_marca') && request()->input('id_marca') != '', function ($query) {
                $idMarca = request()->input('id_marca');
                $query->where('id_marca', $idMarca);
            })
            ->when(request()->has('id_rubro') && request()->input('id_rubro') != '', function ($query) {
                $idRubro = request()->input('id_rubro');
                $query->where('id_rubro', $idRubro);
            })
            ->when(request()->has('id_proveedor') && request()->input('id_proveedor') != '', function ($query) {
                $idProveedor = request()->input('id_proveedor');
                $query->where('id_proveedor', $idProveedor);
            });

            $porcentaje = $request->input('valor');
            if($porcentaje>0){
                $cantActualizadas = $consulta->update([
                    'precio_costo' => \DB::raw("precio_costo * (1 + $porcentaje / 100)")
                ]);
            }elseif($porcentaje<0){
                $cantActualizadas = $consulta->update([
                    'precio_costo' => \DB::raw("precio_costo / (1 - $porcentaje / 100)")
                ]);
            }
            return redirect()->back()->with('success','Los precios de '.$cantActualizadas .' productos han sido actualizados correctamente.');
        }

        return redirect()->back()->with('error','Por favor elija al menos un parámetro para la actualización de precios.');

    }

    public function getHistorialByProductoJSON($id){
        $data = ProductoPrecioHistorico::select('precio','productos_precios_historicos.created_at as created_at','usuarios.nombre as nombre')
                                        ->leftJoin('usuarios','productos_precios_historicos.user_id','usuarios.id')
                                        ->leftJoin('productos','productos_precios_historicos.id_producto','productos.id')
                                        ->where('id_producto',$id)
                                        ->orderBy('productos_precios_historicos.id','DESC')
                                        ->get();
        return response()->json($data);
    }

    public function exportarExcel()
    {
        return Excel::download(new ProductoExportExcel, Carbon::now()->toDateString().'-Productos-Lista'.request()->input('id_lista').'.xlsx');
    }

    public function exportarStockExcel()
    {
        return Excel::download(new StockExportExcel, Carbon::now()->toDateString().'-Productos-StockMinimo.xlsx');
    }

    public function exportarHistoricoStockExcel($idProducto)
    {
        return Excel::download(new StockHistorialProductoExcel($idProducto), Carbon::now()->toDateString().'-'.$idProducto.'-Historial-Stock-.xlsx');
    }

    private function registrarEnLog($estado, $producto){
        switch ($estado) {
            case 'success':
                $mensaje = "Se creo el producto ".$producto." por el usuario ".Auth::user()->nombre;
                break;

            case 'error':
                $mensaje = "No se pudo crear el producto ".$producto." por el usuario ".Auth::user()->nombre;
                break;

            case 'updated':
                $mensaje = "Se actualizo el producto ".$producto." por el usuario ".Auth::user()->nombre;
                break;

            case 'updatedStock':
                $mensaje = "Se actualizo el stock del producto ".$producto." por el usuario ".Auth::user()->nombre;
                break;

            case 'updatedPrecio':
                $mensaje = "Se actualizo el precio del producto ".$producto." por el usuario ".Auth::user()->nombre;
                break;

            case 'deleted':
                $mensaje = "Se elimino el producto ".$producto." por el usuario ".Auth::user()->nombre;
                break;
        }

        $log = Logs::create([
            'mensaje' => $mensaje,
            'id_usuario' => Auth::user()->id,
        ]);
    }
}
