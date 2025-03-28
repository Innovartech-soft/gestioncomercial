@extends('layout.master')

@push('plugin-styles')
<!-- Plugin css import here -->
<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />

{{--<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />--}}

<style>
  .fila-color-rojo {
    background-color: #ffcccc !important;
    /* Cambia esto al color que desees */
  }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  @include('pages.mensajesflash.index')
  <div>
    <h4 class="mb-3 mb-md-0">Cheques</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <a href="{{ route('cheque.create') }}" class="btn btn-primary mr-3 mr-md-2"><i class="mdi mdi-plus-circle mr-1"></i>
      Nuevo</a>
  </div>
</div>
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <form id="formularioCheque" method="POST" action="store">
        @csrf
        <div class="row">
          <div class="col-sm-6">
            <div class="row">
              <div class="mb-3 col-sm-4">
                <label for="serie" class="form-label">N° De Serie</label>
                <input type="number" class="form-control" name="serie" id="serie" autocomplete="off"
                  value="{{isset($cheque)?$cheque->serie:''}}" placeholder="Ingrese un nº de serie..." >
              </div>
              <div class="mb-3 col-sm-8">
                <label for="numero" class="form-label required">Numero</label>
                <input type="number" class="form-control" name="numero" id="numero" autocomplete="off"
                  value="{{isset($cheque)?$cheque->numero:''}}" placeholder="Ingrese un numero..." required>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="mb-3">
              <label for="banco_emisor" class="form-label">Banco</label>
              <input type="text" class="form-control" name="banco_emisor" id="banco_emisor" autocomplete="off" value=""
                placeholder="Ingrese un nombre...">
            </div>
          </div>

          <div class="col-sm-6">
            <div class="mb-3">
              <label for="fecha_emision" class="form-label required">Fecha De Emisión</label>
              <div class="input-group flatpickr" id="flatpickr-date">
                <input name="fecha_emision" type="date" class="form-control"
                  placeholder="Seleccione una fecha de emision" data-input
                  value="{{isset($cheque)?$cheque->fecha_emision:''}}" required>
                <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="mb-3">
              <label for="id_cliente" class="form-label required">Cliente</label>
              <select class="js-select2-cliente form-select" name="id_cliente" required id="id_cliente">
                <option value="" selected>Seleccione un cliente...</option>
                @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}" {{ isset($cheque) && $cheque->id_cliente === $cliente->id ?
                  'selected' : '' }}>
                  {{ $cliente->codigo.' '.$cliente->razon_social }}
                </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="mb-3">
              <label for="importe" class="form-label required">Importe</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" name="importe" id="importe" autocomplete="off"
                  value="{{isset($cheque)?$cheque->importe:''}}" placeholder="Ingrese un importe..." required>
              </div>
            </div>
          </div>
        </div>
    </div>
    {{-- buttons to the right side of the card --}}
    <div class="card-footer d-flex justify-content-end">
      <button type="submit" class="btn btn-primary me-2">Añadir</button>
      <button type="button" class="btn btn-secondary me-2" id="cancelButton">Cancelar</button>
    </div>
    </form>
  </div>
</div>
</div>

<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h6 class="card-title">Listado De Cheques</h6>
      {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables
          Documentation </a>for a full list of instructions and other options.</p> --}}
      <div class="table-responsive">
        <table id="dataTablecheques" class="table table-hover">
          <thead>
            <tr>
              <th>Número</th>
              <th>Banco Emisor</th>
              <th>Fecha De Pago</th>
              <th>Fecha De Emision</th>
              <th>Titular Librador</th>
              <th>Importe</th>
              <th style="display:none">Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($cheques as $cheque)
            <tr {{ $cheque->estado == 0 ? "class=fila-color-rojo" : '' }}>
              <td>{{ $cheque->serie.' - '.$cheque->numero }}</td>
              <td>{{ $cheque->banco_emisor }}</td>
              <td>{{ date('d/m/Y', strtotime($cheque->fecha_pago)) }}</td>
              <td>{{ date('d/m/Y', strtotime($cheque->fecha_emision)) }}</td>
              <td>{{ $cheque->titular_librador }}</td>
              <td>$ {{ $cheque->importe }}</td>
              <td style="display:none">$ {{ $cheque->estado }}</td>
              <td class="text-end">
                @if($cheque->estado == 1)
                <a href="{{ route('cheque.edit',$cheque->id ) }}" class="btn btn-primary btn-sm"><i
                    class="mdi mdi-pencil"></i></a>
                <form onsubmit="eliminarAlert(event)" action="{{ route('cheque.destroy',$cheque->id) }}" method="POST"
                  style="display: inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>
                </form>
                @endif
              </td>
            </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

@endsection

@push('plugin-scripts')
<!-- Plugin js import here -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>



@endpush

@push('custom-scripts')
<!-- Custom js here -->
<script src="{{ asset('assets/js/form-validation-vendedor.js') }}"></script>
<script src="{{ asset('assets/js/data-table-cheques.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script src="{{ asset('assets/js/tags-input.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('assets/js/register-delete.js') }}"></script>
<script src="{{ asset('assets/js/select2-cliente.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
@endpush
