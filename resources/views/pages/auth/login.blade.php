@extends('layout.master2')

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-4 pe-md-0">
            <div class="auth-side-wrapper">
              <img style="height: 452px; width: 299px;"
                src="{{ url('assets/images/banner_vertical.png') }}" alt="">
            </div>
          </div>
          <div class="col-md-8 ps-md-0">
            <div class="auth-form-wrapper px-4 py-5">
              <a href="#" class="noble-ui-logo d-block mb-2">BIENVENIDO<span></span></a>
              <form class="forms-sample" method="POST" action="login">
                @csrf
                <div class="mb-3">
                  <label for="nombre" class="form-label">Usuario</label>
                  <input type="text" class="form-control" id="nombre" name="nombre"
                    placeholder="Ingrese un nombre de usuario...">
                </div>
                <div class="mb-3">
                  <label for="userPassword" class="form-label">Contraseña</label>
                  <input type="password" class="form-control" name="password" id="userPassword"
                    autocomplete="current-password" placeholder="Ingrese su contraseña...">
                </div>
                <div class="form-check mb-3">
                  <input type="checkbox" class="form-check-input" id="authCheck">
                  <label class="form-check-label" for="authCheck">
                    Recordarme
                  </label>
                </div>
                <div>
                  <button type="submit" class="btn btn-primary me-2 mb-2 mb-md-0">
                    Ingresar</button>

                </div>
                {{-- <a href="{{ url('/auth/register') }}" class="d-block mt-3 text-muted">Aún no estás registrado?
                  Únete</a> --}}
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection