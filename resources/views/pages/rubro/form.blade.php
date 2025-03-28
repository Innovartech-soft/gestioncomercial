@extends('layout.master')

@push('plugin-styles')

@endpush

@section('content')
<!-- Page content here -->
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">{{isset($rubro)?'Editar Rubro':'Nuevo Rubro'}}</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <a href="{{url('rubro/index')}}" class="menu-icon">
      <i class="mdi mdi-backburger"></i>
    </a>
  </div>
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <h6 class="card-title"></h6>

        <form id="formularioRubro" class="forms-sample" method="POST" action="{{isset($rubro)?'update':'store'}}">
          @csrf
          <input type="hidden" name="id" id="id" value="{{isset($rubro)?$rubro->id:''}}">
          <div class="mb-3">
            <label for="nombre" class="form-label required">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombre" autocomplete="off"
              value="{{isset($rubro)?$rubro->nombre:''}}" placeholder="Ingrese un rubro..." required>
          </div>
          <div class="mb-3">
            <label for="acronimo" class="form-label required">Acronimo</label>
            <input type="text" class="form-control" name="acronimo" id="acronimo" autocomplete="off"
              value="{{isset($rubro)?$rubro->acronimo:''}}" placeholder="Ingrese un acronimo..." required>
          </div>
          <button type="submit" class="btn btn-primary me-2 save" >{{isset($rubro)?'Guardar':'Crear'}}</button>
          <button type=" button" class="btn btn-secondary me-2" id="cancelButton">Cancelar</button>
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

<script src="{{ asset('assets/js/form-validation-rubro.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script src="{{ asset('assets/js/tags-input.js') }}"></script>
<script src="{{ asset('assets/js/rubro-script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
@endpush