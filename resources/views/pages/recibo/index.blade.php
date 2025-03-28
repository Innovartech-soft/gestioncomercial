@extends('layout.master')

@push('plugin-styles')
<!-- Plugin css import here -->
<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
@endpush

@section('content')
<!-- Page content here -->
@include('pages/recibo/form-modal')

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  @include('pages.mensajesflash.index')
  <div>
    <h4 class="mb-3 mb-md-0">Recibos</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    {{-- etiqueta a para exportar archivos --}}
    <button type="button" class="btn btn-success mr-3 mr-md-2" data-bs-toggle="modal" data-bs-target="#formExportar"
      style="margin-right: 10px;">
      <i class="mdi mdi-file-excel mr-1"></i> Exportar
    </button>
    {{-- etiqueta a para exportar archivos --}}
    <button type="button" class="btn btn-primary mr-3 mr-md-2" data-bs-toggle="modal" data-bs-target="#formCrearRecibo"
      style="margin-right: 10px;">
      <i class="mdi mdi-plus-circle mr-1"></i> Recibo
    </button>
  </div>

  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Listado de Recibos</h6>
        <div class="row">
          <div class=" table table-responsive">
            <table id="dataTableRecibos" class="table table-hover">
              <thead>
                  <tr>
                      <th>Codigo</th>
                      <th>Fecha</th>
                      <th>Tipo</th>
                      <th>Monto</th>
                      <th>Metodo Pago</th>
                      <th>Cliente</th>
                      <th>Proveedor</th>
                      <th>Usuario</th>
                      <th>Acciones</th>
                  </tr>
              </thead>
            
          </table>
          

          </div>
        </div>
      </div>

    </div>

  </div>
  @include('pages.recibo.egresosexport')

</div>

@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
  var urlData = "{{ route('recibo.data') }}";
</script>
<script src="{{ asset('assets/js/recibo-script.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script src="{{ asset('assets/js/tags-input.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
<script src="{{ asset('assets/js/register-delete.js') }}"></script>
<script src="{{ asset('assets/js/data-table-recibo.js') }}"></script>
  <script src="{{ asset('assets/js/select2-cliente.js') }}"></script>
@endpush
