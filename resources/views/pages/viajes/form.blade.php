@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0"></h4>
  </div>

  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <a href="{{ route('viaje.index') }}" class="menu-icon">
      <i class="mdi mdi-backburger"></i>
    </a>
  </div>

  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        @include('pages.mensajesflash.index')
        <form action="{{ isset($viaje) ? route('viaje.update', $viaje->id) : route('viaje.store') }}" method="POST">
          @csrf
          @if(isset($viaje))
          @method('PUT')
          @endif
          {{-- @dd($viaje->detalleViajes) --}}
          <div class="row mb-4">
            <div class="col-md-6">
              <label for="repartidor" class="form-label">Repartidor</label>
              <select class="form-select" id="repartidor" name="repartidor" required>
                <option value="">Seleccione un repartidor</option>
                @foreach($repartidores as $repartidor)
                <option value="{{ $repartidor->id }}" {{ isset($viaje) && $viaje->id_repartidor == $repartidor->id
                  ? 'selected' : '' }}>
                  {{ $repartidor->nombre }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="buscar_venta" class="form-label">Buscar Boleta</label>
              <select class="form-select" id="buscar_venta" name="buscar_venta">
                <option value="">Seleccione una boleta</option>
                @foreach($ventas as $venta)
                <option value="{{ $venta->id }}" data-venta="{{ $venta }}"
                  data-vendedorId="{{ $venta->vendedor ? $venta->vendedor->id : '' }}"
                  data-clientevendedor="{{ $venta->cliente ? ($venta->cliente->getCodigoAttribute()). '-' . ($venta->vendedor ? $venta->vendedor->nombre : '') : '' }}"
                  data-tipo-venta="{{ $venta->id_tipo_venta }}">
                  {{ $venta->id }} - Boleta: {{ $venta->nombre_cliente }} -
                  Vendedor: {{ $venta->vendedor ? $venta->vendedor->nombre : 'No asignado' }}
                </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table " id="tablaPlanilla">
              <thead>
                <tr>
                  <th>Nº</th>
                  <th>Cliente</th>
                  <th>Nº Boleta</th>
                  <th>Importe</th>
                  {{-- <th>TO</th>
                  <th>E</th>
                  <th>TE</th>
                  <th>NP</th> --}}
                  <th>MONTO</th>
                  <th>SALDO</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- Los registros se agregarán dinámicamente -->
              </tbody>
            </table>
          </div>

          <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">
              {{ isset($Viaje) ? 'Actualizar' : 'Guardar' }}
            </button>
            <button type="button" class="btn btn-secondary me-2" id="cancelButton">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
<script>
  $("#cancelButton").click(function () {
const modifiedURL = window.location.href.replace(/\/viaje\/.*$/, "/");
window.location.href = modifiedURL;
});

  document.addEventListener('DOMContentLoaded', function() {
    // Array para mantener registro de ventas agregadas
    const ventasAgregadas = new Set();

    // Si estamos en modo edición, pre-cargar las ventas existentes
    @if(isset($viaje))
        @foreach($viaje->detalleViajes as $detalle)
        
            ventasAgregadas.add({{ $detalle->venta_id }});
            agregarFilaExistente({
                numero: '{{ $detalle->cliente ? $detalle->cliente->getCodigoAttribute() : "" }}-{{ $detalle->vendedor ? $detalle->vendedor->nombre : "" }}',
                vendedorId: '{{ $detalle->vendedor_id }}',
                clienteNombre: '{{ $detalle->cliente ? $detalle->cliente->razon_social : "" }}',
                clienteId: '{{ $detalle->cliente_id }}',
                venta_id: '{{ $detalle->venta_id }}',
                importe: '{{ $detalle->importe }}',
                monto: '{{ $detalle->monto }}',
                saldo: '{{ $detalle->saldo }}'
            });
        @endforeach
    @endif

    // Inicializar Select2 para repartidor
    $('#repartidor').select2({
        placeholder: 'Seleccione un repartidor...',
        allowClear: true,
        width: '100%',
        language: 'es'
    });

    // Inicializar Select2 para buscar venta
    $('#buscar_venta').select2({
        placeholder: 'Buscar boleta...',
        allowClear: true,
        width: '100%',
        language: 'es'
    });

    // Inicializar Select2
    $('#buscar_venta').select2({
        placeholder: 'Buscar boleta...',
        allowClear: true,
        width: '100%',
        language: 'es',
        dropdownParent: $('#buscar_venta').closest('.d-flex')
    }).on('select2:select', function(e) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            const ventaData = JSON.parse(selectedOption.dataset.venta);
            
            const venta = {
                numero: selectedOption.dataset.clientevendedor,
                vendedorId: selectedOption.dataset.vendedorid || '',
                clienteNombre: ventaData.nombre_cliente,
                clienteId: ventaData.id_cliente,
                venta_id: ventaData.id,
                importe: ventaData.id_tipo_venta == 4 ? -Math.abs(ventaData.total) : ventaData.total,
                tipoVenta: ventaData.id_tipo_venta
            };

            // Solo limpiar el select si la venta se agregó exitosamente
            if (agregarFila(venta)) {
                $('#buscar_venta').val('').trigger('change');
            }
        }
    });

    // Función para agregar una fila a la tabla
    function agregarFila(venta) {
        // Validar si la venta ya fue agregada
        if (ventasAgregadas.has(venta.venta_id)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Esta venta ya ha sido agregada a la planilla de viaje',
                confirmButtonText: 'Aceptar'
            });
            return false;
        }

        const tbody = document.querySelector('#tablaPlanilla tbody');
        const row = document.createElement('tr');
        // Si es nota de crédito (tipo 4), agregar clase para diferenciarlo visualmente
        if (venta.tipoVenta == 4) {
        row.classList.add('nota-credito');
        }
        row.innerHTML = `
            <td>${venta.numero}</td>
            <td>
                <input type="hidden" name="items[${tbody.children.length}][vendedor_id]" value="${venta.vendedorId}">
                <input type="hidden" name="items[${tbody.children.length}][cliente_id]" value="${venta.clienteId}">
                <input type="text" class="form-control" value="${venta.clienteNombre}" readonly>
            </td>
            <td>${venta.venta_id}</td>
            <td>
                <input type="hidden" name="items[${tbody.children.length}][venta_id]" value="${venta.venta_id}">
                <input type="number" class="form-control" name="items[${tbody.children.length}][importe]" value="${venta.importe}" readonly>
            </td>
            <td>
                <input type="number" class="form-control" name="items[${tbody.children.length}][monto]" value="0">
            </td>
            <td>
                <input type="number" class="form-control" name="items[${tbody.children.length}][saldo]" value="0">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm eliminar-fila" data-venta_id="${venta.venta_id}">
                    <i class="mdi mdi-delete"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(row);
        
        // Agregar la venta al registro de ventas agregadas
        ventasAgregadas.add(venta.venta_id);
        return true;
    }

    // Función para agregar una fila existente (en modo edición)
    function agregarFilaExistente(venta) {
        const tbody = document.querySelector('#tablaPlanilla tbody');
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>${venta.numero}</td>
            <td>
                <input type="hidden" name="items[${tbody.children.length}][vendedor_id]" value="${venta.vendedorId}">
                <input type="hidden" name="items[${tbody.children.length}][cliente_id]" value="${venta.clienteId}">
                <input type="text" class="form-control" value="${venta.clienteNombre}" readonly>
            </td>
            <td>${venta.venta_id}</td>
            <td>
                <input type="hidden" name="items[${tbody.children.length}][venta_id]" value="${venta.venta_id}">
                <input type="number" class="form-control" name="items[${tbody.children.length}][importe]" value="${venta.importe}" readonly>
            </td>
            <td>
                <input type="number" class="form-control" name="items[${tbody.children.length}][monto]" value="${venta.monto}">
            </td>
            <td>
                <input type="number" class="form-control" name="items[${tbody.children.length}][saldo]" value="${venta.saldo}">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm eliminar-fila" data-venta_id="${venta.venta_id}">
                    <i class="mdi mdi-delete"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(row);
    }

    // Modificar el evento de eliminar fila para quitar la venta del registro
    document.addEventListener('click', function(e) {
        if(e.target.closest('.eliminar-fila')) {
            const fila = e.target.closest('tr');
            const venta_id = fila.querySelector('.eliminar-fila').dataset.venta_id;
            ventasAgregadas.delete(venta_id); // Eliminar del registro
            fila.remove();
        }
    });

    // Evento para agregar venta
    document.getElementById('btnBuscarVenta').addEventListener('click', function() {
        const select = document.getElementById('buscar_venta');
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption && selectedOption.value) {
            const ventaData = JSON.parse(selectedOption.dataset.venta);
            
            const venta = {
                numero: selectedOption.dataset.clientevendedor,
                vendedorId: selectedOption.dataset.vendedorid || '',
                clienteNombre: ventaData.nombre_cliente,
                clienteId: ventaData.id_cliente,
                venta_id: ventaData.id,
                importe: ventaData.total
            };

            // Solo limpiar el select si la venta se agregó exitosamente
            if (agregarFila(venta)) {
                $('#buscar_venta').val('').trigger('change');
            }
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor seleccione una venta',
                confirmButtonText: 'Aceptar'
            });
        }
    });

    // Evento para el botón cancelar
    document.getElementById('cancelButton').addEventListener('click', function() {
        window.location.href = "{{ route('viaje.index') }}";
    });
});
</script>
@endpush

@push('custom-styles')
<style>
  .input-group .select2-container {
    flex: 1 1 auto;
    width: auto !important;
  }

  .input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
  }
</style>
@endpush

@push('custom-styles')
<style>
  .select2-container {
    width: 100% !important;
  }

  .d-flex .select2-container .select2-selection--single {
    height: 38px;
    padding: 8px;
  }

  .d-flex .btn {
    height: 38px;
    display: flex;
    align-items: center;
  }

  .select2-container--default .select2-selection--single {
    display: flex;
    align-items: center;
  }

  .me-2 {
    margin-right: 0.5rem !important;
  }
</style>
@endpush

@push('custom-styles')
<style>
  .select2-container {
    width: 100% !important;
  }
</style>
@endpush

@push('custom-styles')

@endpush