@extends('layout.master')

@push('plugin-styles')
<!-- Plugin css import here -->

<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- Page content here -->
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">{{isset($vendedor)?'Editar Vendedor':'Nuevo Vendedor'}}</h4>
  </div>

  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <a href="{{url('vendedor/index')}}" class="menu-icon">
      <i class="mdi mdi-backburger"></i>
    </a>

  </div>
  
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      
      <div class="card-body">
        @include('pages.mensajesflash.index')
        <h6 class="card-title"></h6>

        <form id="formularioVendedor" method="POST" action="{{isset($vendedor)?'update':'store'}}">
          @csrf
          <div class="mb-3">
            <label for="nombre" class="form-label required">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombre" autocomplete="off"
              value="{{isset($vendedor)?$vendedor->nombre:''}}" placeholder="Ingrese un nombre..." required>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Telefono</label>
              <input class="form-control mb-4 mb-md-0" name="contacto"
                value="{{isset($vendedor)?$vendedor->contacto:''}}" data-inputmask-alias="9999-999999" placeholder="Ingrese un telefono..."/>
            </div>
          </div>
          <div class="mb-3">
            <label for="exampleFormControlSelect1" class="form-label">Estado</label>

            <select class="form-select" name="estado" id="estado">
              <option value="1" {{ isset($vendedor) && $vendedor->estado == 1 ? 'selected' : ''}}> Activo </option>
              <option value="0" {{ isset($vendedor) && $vendedor->estado == 0 ? 'selected' : ''}}> Inactivo </option>
            </select>
          </div>
          <div class="mb-6">
              <div class="col-md-12">
                  <label class="form-label required">Porcentaje de Comision</label>
                  <input class="form-control mb-4 mb-md-0" type="number" required name="porcentaje_comision"  id="porcentaje_comision"
                         value="{{isset($vendedor)?$vendedor->porcentaje_comision:''}}" placeholder="Ingrese una comision..." required/>
              </div>
          </div>
          <button type="submit" class="btn btn-primary me-2">{{isset($vendedor)?'Guardar':'Crear'}}</button>
          <button type="button" class="btn btn-secondary me-2" id="cancelButton">Cancelar</button>
        </form>

      </div>
    </div>
  </div>

  @if(isset($comisiones))
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        {{-- agregar boton a la derecha de la card --}}
        <div class="row">
          <h6 class="card-title">Comisiones Del Vendedor</h6>
          
          <div class="col-md-12 d-flex justify-content-end mr-3 mb-3">
            <button type="button" class="btn btn-primary mr-3 mr-md-2" data-bs-toggle="modal" data-bs-target="#formIndexComision" style="margin-right: 10px;">
              <i class="mdi mdi-eye mr-1"></i> Ver y Pagar
            </button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#formExportar">
              <i class="mdi mdi-file-excel"></i> Exportar
            </button>
          </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableComisiones" class="table table-hover">
            <thead>
             <tr>
                  <th style="display:none">ID</th>
                  <th>Nº Operacion</th>
                  <th>Fecha</th>
                  <th>Monto</th>
                  <th>Período</th>
                  <th>Notas</th>
                  <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($comisiones as $comision) 
              <tr {{$comision->estado!=1?'class=fila-color-verde':''}}>
                  <td style="display:none">{{$comision->id}}</td>
                  <td>{{ optional($comision->venta)->numero_venta }}</td>
                  <td>{{ date('d/m/Y', strtotime($comision->fecha)) }}</td>
                  <td>$ {{ $comision->monto }}</td>
                  <td>{{$comision->periodo}}</td>
                  <td>{{ $comision->notas }}</td>
                  <td>
                  @if ($comision->estado!=1)
                  <a href="#" type="button" class="btn btn-success btn-sm btnPagarComision" data-bs-toggle="modal"
                      data-bs-target="#formPagoComision" data-id-comision="{{$comision->id}}" data-id-vendedor="{{ $comision->id_vendedor }}" data-monto="{{ round($comision->monto,1)}}"><i
                        class="mdi mdi-currency-usd"></i></a>
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
  @endif
</div>
@if(isset($vendedor))
  @include('pages.comision.pago-form-modal')
  @include('pages.comision.index-form-modal')
  @include('pages.vendedor.form-modal-export')
@endif
@endsection

@push('plugin-scripts')
<script>
  var id_vendedor = {{isset($vendedor)?$vendedor->id:''}};
</script>
<!-- Plugin js import here -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>

@endpush

@push('custom-scripts')
<!-- Custom js here -->
<script src="{{ asset('assets/js/register-delete.js') }}"></script>
<script src="{{ asset('assets/js/register-save.js') }}"></script>
<script src="{{ asset('assets/js/comision-venta-script.js') }}"></script>
<script src="{{ asset('assets/js/form-validation-vendedor.js') }}"></script>
<script src="{{ asset('assets/js/data-table-comisiones.js') }}"></script>
<script src="{{ asset('assets/js/comisiones-index.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script src="{{ asset('assets/js/tags-input.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
@endpush
