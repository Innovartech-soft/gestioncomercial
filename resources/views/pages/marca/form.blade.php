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
    <h4 class="mb-3 mb-md-0">{{isset($marca)?'Editar Marca':'Nueva Marca'}}</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <a href="{{url('marca/index')}}" class="menu-icon">
      <i class="mdi mdi-backburger"></i>
    </a>
  </div>
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <h6 class="card-title"></h6>

        <form id="formularioMarca" method="POST" action="{{ isset($marca) ? 'update' : 'store' }}">
          @csrf
          <div class="mb-3">
              <label for="nombre" class="form-label required">Nombre</label>
              <input type="text" class="form-control" name="nombre" id="nombre" autocomplete="off"
                  value="{{ isset($marca) ? $marca->nombre : '' }}" placeholder="Ingrese una marca..." required>
          </div>
          <button type="submit" class="btn btn-primary me-2">{{ isset($marca) ? 'Guardar' : 'Crear' }}</button>
          <button type="button" class="btn btn-secondary" id="cancelButton">Cancelar</button>
      </form>


      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
<!-- Plugin js import here -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>

@endpush

@push('custom-scripts')
<!-- Custom js here -->
<script src="{{ asset('assets/js/form-validation-marca.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script src="{{ asset('assets/js/tags-input.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
@endpush