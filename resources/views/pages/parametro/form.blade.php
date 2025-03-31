
@extends('layout.master')

@push('plugin-styles')
  <!-- Plugin css import here -->
@endpush

@section('content')
  <!-- Page content here -->
   <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Parametros</h4>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
         <div class="card">
      <div class="card-body">

        <h6 class="card-title"></h6>

        <form id="formularioParametros" class="forms-sample" method="POST" action="{{isset($parametro)?'update':'store'}}">
          @csrf
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="nombre_empresa" class="form-label">Nombre de la Empresa</label>
              <input type="text" class="form-control" name="nombre_empresa" id="nombre_empresa" value="{{isset($parametro)?$parametro->nombre_empresa:''}}" autocomplete="off" placeholder="Ingrese el nombre de su empresa...">
            </div>
            <div class="col-md-6 mb-3">
                <label for="cuit" class="form-label">CUIT:</label>
                <input id="cuit" class="form-control mb-4 mb-md-0" name="cuit" type="text" value="{{isset($parametro)?$parametro->cuit:''}}" placeholder="Ingrese un CUIT..."/>
              </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Telefono 1:</label>
              <input class="form-control mb-4 mb-md-0" name="tel_1" data-inputmask-alias="(+99) 9999-999999" value="{{isset($parametro)?$parametro->tel_1:''}}" placeholder="Ingrese un numero de telefono..."/>
            </div>
            <div class="col-md-6">
              <label class="form-label">Telefono 2:</label>
              <input class="form-control mb-4 mb-md-0" name="tel_2" data-inputmask-alias="(+99) 9999-999999" value="{{isset($parametro)?$parametro->tel_2:''}}"  placeholder="Ingrese un numero de telefono..."/>
            </div>
          </div>
           <div class="row mb-3">
              <div class="col-md-6">
                <label for="dir_1" class="form-label ">Direccion 1:</label>
                <input id="dir_1" class="form-control mb-4 mb-md-0" name="dir_1" type="text" value="{{isset($parametro)?$parametro->dir_1:''}}" placeholder="Ingrese una direccion...">
              </div>
              <div class="col-md-6">
                <label for="dir_2" class="form-label">Direccion 2:</label>
                <input id="dir_2" class="form-control mb-4 mb-md-0" name="dir_2" type="text" value="{{isset($parametro)?$parametro->dir_2:''}}" placeholder="Ingrese una direccion..."/>
              </div>
            </div>
             <div class="row mb-3">
              <div class="col-md-6 custom-select">
                <label for="multimoneda" class="form-label">Multimoneda:</label>
                  <select class="form-select" id="multimoneda" name="multimoneda">
                  @if(isset($parametro))
                    @if($parametro->multimoneda == 1)
                      <option value="1" selected>Sí</option>
                      <option value="0">No</option>
                    @else
                      <option value="1">Sí</option>
                      <option value="0" selected>No</option>
                    @endif
                  @else
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                  @endif
                  </select>
              </div>
              <div class="col-md-6">
                  <label class="form-label">Cotizacion USD:</label>
                    <input class="form-control mb-4 mb-md-0" name="dolar" value="{{isset($parametro)?$parametro->dolar:''}}" data-inputmask="'alias': 'currency' ,'prefix':'$'"/>
                  </div>

            </div>
          <button type="submit" class="btn btn-primary me-2">Guardar</button>
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
  <script src="{{ asset('assets/js/form-validation-parametro.js') }}"></script>
  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
  <script src="{{ asset('assets/js/tags-input.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
@endpush
