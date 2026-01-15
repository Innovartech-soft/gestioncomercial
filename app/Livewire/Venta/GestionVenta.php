<?php

namespace App\Livewire\Venta;

use App\Lista;
use App\Marca;
use App\Rubro;
use App\Cliente;
use App\Producto;
use App\Vendedor;
use App\Proveedor;
use App\TipoVenta;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GestionVenta extends Component
{
    public $tipos, $clientes, $vendedores, $marcas, $rubros, $proveedores;
    public $productos; // colección de resultados mostrados

    // Inputs UI
    public $tipoComprobante = '';
    public $cliente = '';
    public $vendedor = '';
    public $filtroMarca = '';
    public $filtroProveedor = '';
    public $filtroRubro = '';
    public $buscador = '';

    // Carrito
    public $carrito = [];
    public $productoSeleccionado = null;
    public $cantidad = 1;
    public $descuento = 0;
    public $listaSeleccionada = null;
    public $listasDescuento = [];

    // Modal de pago
    public $pago = [
        'cuentaCorriente' => false,
        'efectivo' => 0,
        'tarjeta' => 0,
        'otro' => 0,
        'cheque' => 0,
    ];

    public $observacionesPago = '';

    // Totales discriminados
    public $subtotalSinIVA = 0;
    public $totalIVA = 0;
    public $totalConIVA = 0;

    protected $rules = [
        'cantidad' => 'integer|min:1',
        'descuento' => 'numeric|min:0'
    ];

    public function mount()
    {
        $this->tipos = TipoVenta::all();
        $this->clientes = Cliente::all();
        $this->vendedores = Vendedor::where('estado', true)->get();
        $this->marcas = Marca::all();
        $this->rubros = Rubro::all();
        $this->proveedores = Proveedor::all();
        $this->productos = collect();
        $this->listasDescuento = Lista::all();
    }

    public function updatedBuscador()
    {
        $this->buscarProductos();
    }

    public function updatedFiltroMarca()
    {
        $this->buscarProductos();
    }

    public function updatedFiltroProveedor()
    {
        $this->buscarProductos();
    }

    public function updatedFiltroRubro()
    {
        $this->buscarProductos();
    }

    public function updatedCliente($value)
    {
        $clienteObj = Cliente::find($value);

        if ($clienteObj && $clienteObj->lista) {
            $this->listaSeleccionada = $clienteObj->lista->id;
        } else {
            $this->listaSeleccionada = null;
        }
    }



    public function getTotalConIVAProperty()
    {
        return collect($this->carrito)->sum('subtotal_con_iva');
    }


    public function buscarProductos()
    {
        if (trim($this->buscador) === '' && !$this->filtroMarca && !$this->filtroProveedor && !$this->filtroRubro) {
            $this->productos = collect();
            return;
        }

        $term = trim($this->buscador);
        $query = Producto::query();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('nombre', 'like', "%{$term}%")
                    ->orWhere('codigo', 'like', "%{$term}%")
                    ->orWhere('codigo_interno', 'like', "%{$term}%")
                    ->orWhere('codigo_barras', $term);
            });
        }

        if ($this->filtroMarca) {
            if (SchemaHasColumn('productos', 'id_marca')) {
                $query->where('id_marca', $this->filtroMarca);
            }
        }
        if ($this->filtroProveedor) {
            if (SchemaHasColumn('productos', 'id_proveedor')) {
                $query->where('id_proveedor', $this->filtroProveedor);
            }
        }
        if ($this->filtroRubro) {
            if (SchemaHasColumn('productos', 'id_rubro')) {
                $query->where('id_rubro', $this->filtroRubro);
            }
        }

        $this->productos = $query->orderBy('nombre')->limit(100)->get();

        // Si el término coincide con código de barras, seleccionar automáticamente
        if ($term && $this->productos->count() === 1 && $this->productos->first()->codigo_barras === $term) {
            $this->seleccionarProducto($this->productos->first()->id);
        }
    }

    public function seleccionarProducto($id)
    {
        $this->productoSeleccionado = Producto::find($id);
        $this->cantidad = 1;
        Log::info('Producto seleccionado - id: ' . $id);
        if ($this->listaSeleccionada) {

            $idLista = (int) $this->listaSeleccionada;

            $lista = collect($this->listasDescuento)->first(fn($l) => (string)$l->id === (string)$this->listaSeleccionada);

            if ($lista) {
                $this->descuento = (float) $lista->valor;
            }
        } else {
            $this->descuento = 0;
        }
    }


    public function updatedListaSeleccionada($value)
    {
        if (!$value) {
            return;
        }
        Log::info('Lista seleccionada - nombre: ' . $value);
        // FORZAR que la comparación no falle por tipo
        $lista = collect($this->listasDescuento)
                    ->first(function($l) use ($value) {
                        return (string)$l->id === (string)$value;
                    });

        if ($lista) {
            $this->descuento = floatval($lista->valor);
            Log::info('Lista valor - precio: ' . $this->descuento);
        }
    }



    public function agregarCarrito()
{
    if (!$this->productoSeleccionado) return;

    $idProd = $this->productoSeleccionado->id;

    // Precios base
    $precioConIVA = $this->productoSeleccionado->PrecioPesosConIva;
    $precioSinIVA  = $this->productoSeleccionado->getPrecioEnPesos();

    // Buscar si ya existe en el carrito
    $index = collect($this->carrito)->search(fn($p) => $p['id'] === $idProd);

    // ============================================================
    //   CASO 1: EL PRODUCTO YA ESTÁ EN EL CARRITO → SUMAR CANTIDAD
    // ============================================================
    if ($index !== false) {

        // Sumar cantidad
        $this->carrito[$index]['cantidad'] += (int) $this->cantidad;

        $cantidadTotal = $this->carrito[$index]['cantidad'];
        $descuento = $this->carrito[$index]['descuento'];

        // Recalcular totales
        $subConIVA = $precioConIVA * $cantidadTotal;
        $subSinIVA = $precioSinIVA * $cantidadTotal;

        if ($descuento > 0) {
            $factor = (1 - ($descuento / 100));
            $subConIVA *= $factor;
            $subSinIVA *= $factor;
        }

        // Actualizar carrito
        $this->carrito[$index]['subtotal_con_iva'] = round($subConIVA, 2);
        $this->carrito[$index]['subtotal_sin_iva'] = round($subSinIVA, 2);
        $this->carrito[$index]['subtotal']         = round($subConIVA, 2); // compatibilidad

    }
    // ============================================================
    //   CASO 2: PRODUCTO NUEVO → AÑADIRLO AL CARRITO
    // ============================================================
    else {

        $subtotalConIVA = $precioConIVA * $this->cantidad;
        $subtotalSinIVA = $precioSinIVA * $this->cantidad;

        if ($this->descuento > 0) {
            $factor = (1 - ($this->descuento / 100));
            $subtotalConIVA *= $factor;
            $subtotalSinIVA *= $factor;
        }

        $this->carrito[] = [
            'id' => $idProd,
            'nombre' => $this->productoSeleccionado->nombre,
            'precio' => $precioConIVA,
            'precio_sin_iva' => $precioSinIVA,
            'cantidad' => (int) $this->cantidad,
            'descuento' => (float) $this->descuento,
            'subtotal_con_iva' => round($subtotalConIVA, 2),
            'subtotal_sin_iva' => round($subtotalSinIVA, 2),
            'subtotal' => round($subtotalConIVA, 2), // compatibilidad
        ];
    }

    // Reset selección
    $this->productoSeleccionado = null;
    $this->cantidad = 1;
    $this->descuento = 0;

    // Recalcular totales generales
    $this->actualizarTotales();
    }

    public function actualizarItem($index)
    {
    if (!isset($this->carrito[$index])) return;

    $item = $this->carrito[$index];

    $cantidad  = max(1, (int)$item['cantidad']);
    $precio    = max(0, (float)$item['precio']);
    $descuento = max(0, min(100, (float)$item['descuento']));

    // Subtotal base
    $subConIVA = $precio * $cantidad;
    $subSinIVA = ($item['precio_sin_iva'] ?? ($precio / 1.21)) * $cantidad;

    // Aplicar descuento
    if ($descuento > 0) {
        $factor = (1 - ($descuento / 100));
        $subConIVA *= $factor;
        $subSinIVA *= $factor;
    }

    // Asignar totales
    $this->carrito[$index]['subtotal_con_iva'] = round($subConIVA, 2);
    $this->carrito[$index]['subtotal_sin_iva'] = round($subSinIVA, 2);
    $this->carrito[$index]['subtotal']         = round($subConIVA, 2);

    // Recalcular totales generales
    $this->actualizarTotales();
    }

    public function eliminarItem($index)
    {
        unset($this->carrito[$index]);
        $this->carrito = array_values($this->carrito);
        $this->actualizarTotales();
    }

    public function actualizarTotales()
    {
        $this->subtotalSinIVA = collect($this->carrito)->sum('subtotal_sin_iva');
        $this->totalConIVA = collect($this->carrito)->sum('subtotal_con_iva');
        $this->totalIVA = $this->totalConIVA - $this->subtotalSinIVA;
    }

    public function calcularTotal()
    {
        return $this->totalConIVA;
    }

    public function getTotalPagoProperty()
    {
        return collect($this->pago)->only(['efectivo', 'tarjeta', 'otro', 'cheque'])->sum();
    }

    public function abrirModalPago()
    {
        $this->dispatch('show-modal-pago');
    }

    public function cerrarModalPago()
    {
        $this->dispatch('hide-modal-pago');
    }

    public function confirmarPago()
    {
        Log::info('Pago registrado (pendiente de guardado).', [
            'pago' => $this->pago,
            'total_pago' => $this->totalPago,
        ]);

        $this->dispatch('hide-modal-pago');
    }

    public function aplicarPagoTotal($metodo)
    {
        $metodo = (string) $metodo;
        if (!in_array($metodo, ['efectivo', 'tarjeta', 'cheque', 'otro'], true)) {
            return;
        }

        if ($this->pago['cuentaCorriente']) {
            return;
        }

        $this->pago[$metodo] = round($this->totalConIVA, 2);
    }

    public function updatedPagoCuentaCorriente($value)
    {
        if ($value) {
            $this->pago['efectivo'] = 0;
            $this->pago['tarjeta'] = 0;
            $this->pago['cheque'] = 0;
            $this->pago['otro'] = 0;
        }
    }

    public function render()
    {
        return view('livewire.venta.gestion-venta', [
            'productos' => $this->productos,
        ]);
    }
}

// Helper fuera de la clase
if (! function_exists('SchemaHasColumn')) {
    function SchemaHasColumn($table, $column) {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
