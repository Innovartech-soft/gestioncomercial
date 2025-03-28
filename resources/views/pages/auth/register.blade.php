@extends('layout.master')

@push('plugin-styles')
<!-- Plugin css import here -->
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-4 pe-md-0">
            <div class="auth-side-wrapper"
              style="background-image: url({{ url('assets/images/ventas_hereled_banner_vertical.png') }})">

            </div>
          </div>
          <div class="col-md-8 ps-md-0">
            <div class="auth-form-wrapper px-4 py-5">
              <a href="#" class="noble-ui-logo d-block mb-2">Hereled<span>Ventas</span></a>
              <h5 class="text-muted fw-normal mb-4">{{isset($usuario)?'Editar':'Registrar
                Usuario'}}</h5>
                <fieldset {{!Auth::user()->administrador&&Auth::user()->id!=$usuario->id?'disabled':''}}>
              <form class="forms-sample" method="POST" action="{{isset($usuario)?'update':'register'}}">
                @csrf
                <div class="mb-3">
                  <label for="nombre" class="form-label required">Nombre de Usuario</label>
                  <input type="text" class="form-control" id="nombre" autocomplete="usuario" name="nombre" minlength="3"
                    value="{{isset($usuario)?$usuario->nombre:''}}" placeholder="Ingrese un nombre..."
                    required />
                </div>
                {{-- <div class="mb-3">
                  <label for="userEmail" class="form-label">Email address</label>
                  <input type="email" class="form-control" id="userEmail" placeholder="Email">
                </div> --}}
                <div class="mt-3">
                  <label for="userPassword" class="form-label">Estado</label>
                  <select class="form-control" name="estado" id="estado" required>
                    <option value="1" {{isset($usuario) && $usuario->estado == 1 ? 'selected' : ''}}>Activo</option>
                    <option value="0" {{isset($usuario) && $usuario->estado == 0 ? 'selected' : ''}}>Inactivo</option>
                  </select>
                </div>
                <div class="mt-3">
                  <label for="userPassword" class="form-label">Administrador</label>
                  <select class="form-control" name="administrador" id="estado" required {{!Auth::user()->administrador?'disabled':''}}>
                    <option value="0" {{isset($usuario) && $usuario->estado == 0 ? 'selected' : ''}}>No</option>
                    <option value="1" {{isset($usuario) && $usuario->estado == 1 ? 'selected' : ''}}>Si</option>
                  </select>
                </div>
                @if(isset($usuario))
                <div class="mt-3">
                  <label for="userPassword" class="form-label required">Contraseña</label>
                  {{-- si el checkbox esta checked solicitaremos la contraseña nueva sino no pedimos nada --}}
                  <input type="password" class="form-control" id="userPassword" disabled autocomplete="current-password"
                    name="password" placeholder="Contraseña" minlength="6" required>
                </div>
                <div class="form-check mt-3">
                  <input type="checkbox" class="form-check-input" id="authCheck">
                  <label class="form-check-label" for="authCheck">
                    ¿Desea cambiar la contraseña?
                  </label>
                </div>
                @else
                <div class="mt-3">
                  <label for="userPassword" class="form-label required">Contraseña</label>
                  {{-- si el checkbox esta checked solicitaremos la contraseña nueva sino no pedimos nada --}}
                  <input type="password" class="form-control" id="userPassword" autocomplete="current-password"
                    name="password" placeholder="Contraseña" minlength="6" required>
                </div>
                @endif
                <div class="mt-3">
                <button type="submit" 
                  class="btn btn-primary me-2 mb-2 mb-md-0">
                  Guardar</button>
                {{-- button para volver al index de user --}}
                <button type="button" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                  {{-- <i class="btn-icon-prepend" data-feather="twitter"></i> --}}
                  <a href="{{ url('auth/index') }}">Cancelar
                  </a>
                </button>
                </div>
            </div>
            </form>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>

<script>
  document.getElementById("authCheck").addEventListener("click", function() {
    if (this.checked) {
      document.getElementById("userPassword").disabled = false;
    } else {
      document.getElementById("userPassword").disabled = true;
    }
  });
</script>

@endsection