@extends('layout.master')

@push('plugin-styles')
<!-- Plugin css import here -->
@endpush

@section('content')
<!-- Page content here -->
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">{{isset($listaGanancia)?'Editar Lista Ganancia':'Nueva Lista Ganancia'}}</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <a href="{{url('listaganancia/index')}}" class="menu-icon">
      <i class="mdi mdi-backburger"></i>
    </a>
  </div>
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <h6 class="card-title"></h6>

        <form id="formularioLista"method="POST" action="{{isset($listaGanancia)?'update':'store'}}">
          @csrf
          <div class="mb-3">
            <label for="nombre" class="form-label required">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombre" autocomplete="off"
              value="{{ isset($listaGanancia)?$listaGanancia->nombre : '' }}" placeholder="Ingrese un nombre de lista..." required>
          </div>
          <div class="mb-3">
            <label for="ganancia" class="form-label required">Ganancia</label>
            <input type="number" class="form-control" name="ganancia" id="ganancia" autocomplete="off"
              value="{{ isset($listaGanancia)?$listaGanancia->ganancia : '' }}" placeholder="Ingrese un valor % ..." required>
          </div>
          <button type="submit" class="btn btn-primary me-2">{{isset($listaGanancia)?'Guardar':'Crear'}}</button>
          <button type="button" class="btn btn-secondary me-2" id="cancelButton">Cancelar</button>
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

<script src="{{ asset('assets/js/form-validation-lista.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script src="{{ asset('assets/js/tags-input.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
@endpush