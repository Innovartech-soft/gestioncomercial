<?php

namespace App\Livewire\Venta;

use Livewire\Component;
// Asegurate de usar el namespace correcto de tus modelos; si tus modelos están en App\Models, usar App\Models\...
use App\Producto;
use App\Cliente;
use App\Vendedor;
use App\Marca;
use App\Rubro;
use App\Proveedor;
use App\TipoVenta;
use Illuminate\Support\Collection;

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

        // inicializar productos vacía para que la vista no muestre null
        $this->productos = collect();
    }

    // Livewire llama automáticamente updated{Property} cuando cambia la property
    public function updatedBuscador($value)
    {
        $this->buscarProductos();
    }

    public function updatedFiltroMarca($value)
    {
        $this->buscarProductos();
    }

    public function updatedFiltroProveedor($value)
    {
        $this->buscarProductos();
    }

    public function updatedFiltroRubro($value)
    {
        $this->buscarProductos();
    }

    public function buscarProductos()
    {
        // Si no hay nada en el buscador y no hay filtros, limpiamos resultados
        if (trim($this->buscador) === '' && !$this->filtroMarca && !$this->filtroProveedor && !$this->filtroRubro) {
            $this->productos = collect();
            return;
        }

        $query = Producto::query();

        // Buscador: buscar por nombre, codigo_interno o codigo
        if ($this->buscador) {
            $term = '%' . $this->buscador . '%';
            $query->where(function($q) use ($term) {
                $q->where('nombre', 'like', $term)
                  ->orWhere('codigo_interno', 'like', $term)
                  ->orWhere('codigo', 'like', $term);
            });
        }

        // Filtros - tolerante a distintos nombres de columnas:
        if ($this->filtroMarca) {
            // intenta con marca_id o id_marca
            if (SchemaHasColumn('productos', 'id_marca')) {
                $query->where('id_marca', $this->filtroMarca);
            } else {
                // fallback: where relation (si existe relacion marca)
                $query->whereHas('marca', function($q) {
                    $q->where('id', $this->filtroMarca);
                });
            }
        }

        if ($this->filtroProveedor) {
            if (SchemaHasColumn('productos', 'id_proveedor')) {
                $query->where('id_proveedor', $this->filtroProveedor);
            } else {
                $query->whereHas('proveedor', function($q) {
                    $q->where('id', $this->filtroProveedor);
                });
            }
        }

        if ($this->filtroRubro) {
            if (SchemaHasColumn('productos', 'id_rubro')) {
                $query->where('id_rubro', $this->filtroRubro);
            } else {
                $query->whereHas('rubro', function($q) {
                    $q->where('id', $this->filtroRubro);
                });
            }
        }

        // Limitar resultados razonablemente (paginación/scroll infinito se puede añadir luego)
        $this->productos = $query->orderBy('nombre')->limit(50)->get();
    }

    public function seleccionarProducto($id)
    {
        $this->productoSeleccionado = Producto::find($id);
        $this->cantidad = 1;
        $this->descuento = 0;
    }

    public function agregarCarrito()
    {
        $this->validate();

        if (!$this->productoSeleccionado) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Seleccione un producto primero.']);
            return;
        }

        $precio = $this->productoSeleccionado->precio ?? $this->productoSeleccionado->precio_venta ?? 0;
        $subtotal = $precio * $this->cantidad;
        if ($this->descuento > 0) {
            $subtotal -= ($subtotal * ($this->descuento / 100));
        }

        $this->carrito[] = [
            'id' => $this->productoSeleccionado->id,
            'nombre' => $this->productoSeleccionado->nombre,
            'cantidad' => (int)$this->cantidad,
            'precio' => (float)$precio,
            'descuento' => (float)$this->descuento,
            'subtotal' => round($subtotal, 2),
        ];

        // limpiar selección
        $this->productoSeleccionado = null;
        $this->cantidad = 1;
        $this->descuento = 0;

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Producto agregado al carrito.']);
    }

    public function eliminarItem($index)
    {
        if (isset($this->carrito[$index])) {
            array_splice($this->carrito, $index, 1);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Item eliminado.']);
        }
    }

    public function calcularTotal()
    {
        return number_format(array_sum(array_column($this->carrito, 'subtotal')), 2, '.', '');
    }

    public function render()
    {
        return view('livewire.venta.gestion-venta', [
            'productos' => $this->productos,
        ]);
    }
}

/**
 * Helper: comprobar rápidamente si la tabla tiene una columna (evita excepciones si no existe)
 * (lo definimos fuera de la clase para no depender de Schema facade en cada condicional)
 */
if (! function_exists('SchemaHasColumn')) {
    function SchemaHasColumn($table, $column) {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
