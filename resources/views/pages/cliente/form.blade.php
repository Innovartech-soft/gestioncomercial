
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
            <h4 class="mb-3 mb-md-0">{{isset($cliente)?'Editar Cliente':'Nuevo Cliente'}}</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
          <a href="{{url('cliente/index')}}" class="menu-icon">
            <i class="mdi mdi-backburger"></i>
          </a>
        </div>
      <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title"></h4>
        <p class="text-muted mb-3"></p>
        <form id="formularioCliente" method="POST" action="{{isset($cliente)?'update':'store'}}">
        @csrf
          <div class=" row mb-3">
           <div class="col-md-6">
            <label for="razon_social" class="form-label required">Razon Social</label>
            <input id="razon_social" class="form-control mb-4 mb-md-0" name="razon_social" value="{{isset($cliente)?$cliente->razon_social:''}}" type="text" placeholder="Ingrese una razon social..." required>
            </div>
            <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control mb-4 mb-md-0" name="email" value="{{isset($cliente)?$cliente->email:''}}" type="email" placeholder="Ingrese un email...">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Telefono 1</label>
              <input type="text" class="form-control mb-4 mb-md-0" name="tel_1" value="{{isset($cliente)?$cliente->tel_1:''}}" data-inputmask-alias="(+99) 9999-999999" placeholder="Ingrese un telefono..."/>
            </div>
            <div class="col-md-6">
              <label class="form-label">Telefono 2</label>
              <input type="text" class="form-control mb-4 mb-md-0" name="tel_2" value="{{isset($cliente)?$cliente->tel_2:''}}" data-inputmask-alias="(+99) 9999-999999" placeholder="Ingrese un telefono..."/>
            </div>
          </div>
             <div class="row mb-3">
              <div class="col-md-6">
                <label for="direccion" class="form-label ">Direccion</label>
                <input id="direccion" class="form-control mb-4 mb-md-0" value="{{isset($cliente)?$cliente->direccion:''}}" name="direccion" type="text" placeholder="Ingrese una direccion...">
              </div>
              <div class="col-md-6">
                <label for="dni_cuit" class="form-label">DNI/CUIT</label>
                <input id="dni_cuit" class="form-control mb-4 mb-md-0" value="{{isset($cliente)?$cliente->dni_cuit:''}}" name="dni_cuit" type="text" placeholder="Ingrese un dni/cuit...">
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="id_lista" class="form-label required">Lista</label>
                 <select class="js-select2-lista form-select" name="id_lista" id="lista" required>
                  <option value="" >Seleccione una lista...</option>
                  @foreach ($listas as $lista )
                    <option id='lista{{$lista->id}}' value="{{$lista->id}}" {{ isset($cliente) && $lista->id == $cliente->id_lista ? 'selected' : ''}} required >{{$lista->nombre}} ({{$lista->valor}} %)</option>
                  @endforeach
                  </select>
              </div>
              <div class="col-md-6">
                <label for="id_categoria_iva" class="form-label required">Categoria IVA</label>
                 <select class="js-select2-iva form-select" name="id_categoria_iva" id="categoria_iva" required>
                  <option selected  disabled>Seleccione una categoria...</option>
                  @foreach ($categoriasIva as $categoriaIndex => $categoriaValor )
                    <option value="{{$categoriaIndex}}" {{ isset($cliente) && $categoriaIndex == $cliente->categoria_iva ? 'selected' : ''}}>{{$categoriaValor}}</option>
                  @endforeach
                  </select>
              </div>
            </div>
            {{-- <div class="row"> --}}
            <div class="row mb-3">
            <div class="col-md-6">
              <label for="id_vendedor" class="form-label required">Vendedor</label>
              <select class="js-select2-vendedor form-select" name="id_vendedor" required>
                <option value="">Seleccione un Vendedor...</option>
                @foreach ($vendedores as $vendedor )
                  @if(isset($cliente))
                    <option id="id_cliente{{$vendedor->id}}" {{$cliente->vendedor?($cliente->vendedor->id==$vendedor->id?"selected":""):""}} value="{{$vendedor->id}}">{{$vendedor->nombre}}</option>
                  @else
                    <option id="id_cliente{{$vendedor->id}}" value="{{$vendedor->id}}">{{$vendedor->nombre}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
           <div class="col-lg-3">
            <label for="defaultconfig-4" class="col-form-label">Nota</label>
          </div>
          <div class="col-lg-12 mb-3">
            <textarea id="maxlength-textarea" class="form-control" name="notas"  id="defaultconfig-4" maxlength="120" rows="6" placeholder="Ingrese una nota...">{{isset($cliente)?$cliente->notas:''}}</textarea>
          </div>
          <button type="submit" class="btn btn-primary me-2">{{isset($cliente)?'Guardar':'Crear'}}</button>
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
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <!-- Custom js here -->
  <script src="{{ asset('assets/js/form-validation-cliente.js') }}"></script>
  <script src="{{ asset('assets/js/select2-vendedor.js') }}"></script>
  <script src="{{ asset('assets/js/select2-lista.js') }}"></script>
  <script src="{{ asset('assets/js/select2-iva.js') }}"></script>
  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
  <script src="{{ asset('assets/js/tags-input.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
@endpush
