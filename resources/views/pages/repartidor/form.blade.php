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
    <h4 class="mb-3 mb-md-0">{{ isset($repartidor) ? 'Editar Repartidor' : 'Nuevo Repartidor' }}</h4>
  </div>

  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <a href="{{ route('repartidor.index') }}" class="menu-icon">
      <i class="mdi mdi-backburger"></i>
    </a>
  </div>

  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <h6 class="card-title"></h6>

        <form id="formularioRepartidor" class="forms-sample" method="POST"
          action="{{isset($repartidor)?'update':'store'}}">
          @csrf
          @if (isset($repartidor))
            @method('PUT')
          @endif

          <div class="mb-3">
            <label for="nombre" class="form-label required">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre"
                  value="{{ old('nombre', $repartidor->nombre ?? '') }}" required>
          </div>
          <div class="mb-3">
            <label for="contacto" class="form-label required">Contacto</label>
                <input type="text" class="form-control" id="contacto" name="contacto"
                      value="{{ old('contacto', $repartidor->contacto ?? '') }}">
          </div>
          <div class="mb-3">
            <label for="notas" class="form-label">Notas</label>
                <textarea class="form-control" id="notas" name="notas" rows="4">{{ old('notas', $repartidor->notas ?? '') }}</textarea>
          </div>
          <div class="mb-3">
            <label for="disponible" class="form-label">¿Disponible?</label>
                <select class="form-control" id="disponible" name="disponible">
                    <option value="1" {{ old('disponible', $repartidor->disponible ?? '') == 1 ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ old('disponible', $repartidor->disponible ?? '') == 0 ? 'selected' : '' }}>No</option>
                </select>
          </div>
          <button type="submit" class="btn btn-primary">
              {{ isset($repartidor) ? 'Guardar' : 'Crear' }}
          </button>
          <button type="button" class="btn btn-secondary me-2" id="cancelButton">Cancelar</button>
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
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/form-validation-repartidor.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
@endpush
