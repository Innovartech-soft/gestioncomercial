
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
 <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">{{ isset($producto)?"Editar Producto":"Nuevo Producto"}}</h4>

        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{url('producto/index')}}" class="menu-icon">
            <i class="mdi mdi-backburger"></i>
          </a>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            {!! isset($producto)?'<small>Última vez editado: '.date('d/m/Y', strtotime($producto->updated_at)).'</small>':'' !!}
            <h6 class="card-title"></h6>

            <form class="forms-sample" method="POST" action="{{ isset($producto)?route('producto.update',$producto->id):route('producto.store')}}">
              @csrf
              <div class="row">
                <div class="col-sm-6">
                  <div class="mb-3">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="text" class="form-control" name="codigo" id="codigo"
                      value="{{isset($producto)?$producto->codigo:""}}" autocomplete="off" placeholder="Ingrese un código...">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="mb-3">
                    <label for="codigo_interno" class="form-label">Código Interno</label>
                    <input type="text" class="form-control" name="codigo_interno" id="codigo_interno"
                      value="{{isset($producto)?$producto->codigo_interno:''}}"autocomplete="off" placeholder="" disabled>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                <div class="mb-3">
                  <label for="codigo_barras" class="form-label">Código de Barras</label>
                  <input type="text" class="form-control" name="codigo_barras" id="codigo_barras"
                    value="{{isset($producto)?$producto->codigo_barras:''}}" autocomplete="off" placeholder="Ingrese un código de barras...">
                </div>
                </div>
                <div class="col-sm-6">
                </div>
              </div>

              <div class="row">
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="id_rubro" class="form-label required">Rubro</label>
                    <select class="js-select2-rubro form-select" name="id_rubro" required>
                      <option value="">Seleccione un rubro...</option>
                      @foreach ($rubros as $rubro )
                        @if(isset($producto))
                          <option id="id_rubro{{$rubro->id}}" {{$producto->rubro->id==$rubro->id?"selected":""}} value="{{$rubro->id}}">{{$rubro->nombre}}</option>
                        @else
                          <option id="id_rubro{{$rubro->id}}" value="{{$rubro->id}}">{{$rubro->nombre}}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="id_marca" class="form-label required">Marca</label>
                    <select class="js-select2-marca form-select" name="id_marca" required>
                      <option value="">Seleccione una marca...</option>
                      @foreach ($marcas as $marca )
                        @if(isset($producto))
                          <option id="id_marca{{$marca->id}}" {{$producto->marca->id==$marca->id?"selected":""}} value="{{$marca->id}}">{{$marca->nombre}}</option>
                        @else
                          <option id="id_marca{{$marca->id}}" value="{{$marca->id}}">{{$marca->nombre}}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="id_proveedor" class="form-label required">Proveedor</label>
                    <select class="js-select2-proveedor form-select" name="id_proveedor" required>
                      <option value="">Seleccione un proveedor...</option>
                      @foreach ($proveedores as $proveedor )
                        @if(isset($producto))
                          <option id="id_proveedor{{$proveedor->id}}" {{$producto->proveedor->id==$proveedor->id?"selected":""}} value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                        @else
                          <option id="id_proveedor{{$proveedor->id}}" value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="nombre" class="form-label required">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre"
                  value="{{isset($producto)?$producto->nombre:''}}" autocomplete="off" placeholder="Ingrese un nombre..." required>
              </div>
              <div class="mb-3">
                <label for="detalle" class="form-label">Detalle</label>
                <textarea class="form-control" name="detalle" id="detalle" rows="5" placeholder="Ingrese un detalle...">{{isset($producto)?$producto->detalle:""}}</textarea>
              </div>

                <div class="mb-3">
                    <label for="notas" class="form-label">Notas</label>
                    <textarea class="form-control" name="notas" id="notas" rows="2" placeholder="Ingrese una nota..." maxlength="255">{{isset($producto)?$producto->notas:""}}</textarea>
                </div>

              <div class="row">
                <div class="col-sm-2">
                  <label for="precio_costo" class="form-label required">Precio Costo</label>
                  <input type="number" step="any" min="0" class="form-control" name="precio_costo" id="precio_costo"
                    value="{{isset($producto)?$producto->precio_costo:''}}" autocomplete="off" placeholder="Precio costo..." required>
                </div>
                <div class="col-sm-2">
                  <label for="lista" class="form-label required">Lista Ganancia</label>
                  <select class="form-select" name="id_lista_ganancia" required>
                      @foreach ($listas as $lista )
                        @if(isset($producto))
                          <option id="id_lista_ganancia" {{$producto->id_lista_ganancia==$lista->id?"selected":""}} value="{{$lista->nombre}}">{{$lista->ganancia}} %</option>
                        @else
                          <option id="id_lista_ganancia" value="{{$lista->nombre}}">{{$lista->ganancia}} %</option>
                        @endif
                      @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                  <label for="precio_venta" class="form-label required">Precio Venta</label>
                  <input type="number" step="any" min="0" class="form-control" name="precio_venta" id="precio_venta"
                    value="{{isset($producto)?$producto->precio_venta:''}}" autocomplete="off" placeholder="Precio venta..." >
                {{--  -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --  --}}
                </div>
                <div class="col-sm-2">
                  <label for="tipo_iva" class="form-label required">IVA</label>
                  <select class="form-select" name="tipo_iva" required>
                      @foreach ($ivas as $ivaIndex=>$ivaValor )
                        @if(isset($producto))
                          <option id="id_iva" {{$producto->tipo_iva==$ivaIndex?"selected":""}} value="{{$ivaIndex}}">{{$ivaValor}} %</option>
                        @else
                          <option id="id_iva" value="{{$ivaIndex}}">{{$ivaValor}} %</option>
                        @endif
                      @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                  <label for="precio_venta_iva" class="form-label required">Precio Venta c/IVA</label>
                  <input type="number" step="any" min="0" class="form-control" name="precio_venta_iva" id="precio_venta_iva"
                    value="{{isset($producto)?$producto->precio_venta:''}}" autocomplete="off" placeholder="Precio venta..." readonly>
                {{--  -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --  --}}
                </div>
              </div>
              <div class="form-check form-switch mb-3">
                @if(isset($producto))
                  <input type="checkbox" class="form-check-input" id="es_dolar" name="es_dolar" {{$producto->es_dolar==1?"checked":""}}>
                @else
                  <input type="checkbox" class="form-check-input" id="es_dolar" name="es_dolar">
                @endif
                <label class="form-check-label" for="es_dolar">Precio en Dolares</label>
              </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label for="oferta_fecha_desde" class="form-label">Fecha Desde</label>
                        <div class="input-group flatpickr" id="flatpickr-date-oferta">
                            <input name="oferta_fecha_desde" type="date" class="form-control"
                                   placeholder="Seleccione una fecha desde" data-input
                                   value="{{ isset($producto) ? 
                                          ($producto->oferta_fecha_desde ? date('Y-m-d', strtotime($producto->oferta_fecha_desde)) : date('Y-m-d'))
                                      :date('Y-m-d') }}" >
                            
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label for="oferta_fecha_hasta" class="form-label">Fecha Hasta</label>
                        <div class="input-group flatpickr" id="flatpickr-date-oferta">
                            <input name="oferta_fecha_hasta" type="date" class="form-control"
                                   placeholder="Seleccione una fecha hasta" data-input
                                   value="{{ isset($producto) ? 
                                          ($producto->oferta_fecha_hasta ? date('Y-m-d', strtotime($producto->oferta_fecha_hasta)) : date('Y-m-d'))
                                      :date('Y-m-d') }}" >
                         
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label for="precio_costo_oferta" class="form-label">Precio Oferta</label>
                        <input type="number" step="any" min="0" class="form-control" name="precio_costo_oferta" id="precio_costo_oferta"
                               value="{{isset($producto)?$producto->precio_costo_oferta:''}}" autocomplete="off" placeholder="Precio costo oferta..." >
                        <small>Precio en pesos</small>
                    </div>
                </div>
                <div class="form-check form-switch mb-3">
                    @if(isset($producto))
                        <input type="checkbox" class="form-check-input" id="en_oferta" name="en_oferta" {{$producto->en_oferta==1?"checked":""}}>
                    @else
                        <input type="checkbox" class="form-check-input" id="en_oferta" name="en_oferta">
                    @endif
                    <label class="form-check-label" for="en_oferta">En Oferta</label>
                </div>
              <div class="row mb-4">
                <div class="col-sm-4">
                  <label for="stock" class="form-label">Stock</label>
                  <input type="number" class="form-control" name="stock" id="stock"
                    value="{{isset($producto)?$producto->stock:''}}" autocomplete="off" placeholder="Stock...">
                </div>
                <div class="col-sm-4">
                  <label for="stock_minimo" class="form-label">Stock Mínimo</label>
                  <input type="number" class="form-control" name="stock_minimo" id="stock_minimo"
                    value="{{isset($producto)?$producto->stock_minimo:''}}" autocomplete="off" placeholder="Stock mínimo...">
                </div>
                <div class="col-sm-4">
                  <label for="bulto" class="form-label">Cantidad por Bulto</label>
                  <input type="number" class="form-control" name="bulto" id="bulto"
                    value="{{isset($producto)?$producto->bulto:''}}" autocomplete="off" placeholder="Cantidad x bulto...">
                </div>
              </div>
                <div class="form-check form-switch mb-3">
                    @if(isset($producto))
                        <input type="checkbox" class="form-check-input" id="no_comisionable" name="no_comisionable" {{$producto->no_comisionable==1?"checked":""}}>
                    @else
                        <input type="checkbox" class="form-check-input" id="no_comisionable" name="no_comisionable">
                    @endif
                    <label class="form-check-label" for="no_comisionable">No Comisionable</label>
                </div>
              @if(isset($producto))
                <button type="submit" class="btn btn-primary me-2">Guardar</button>
              @else
                <button type="submit" class="btn btn-primary me-2">Crear</button>
              @endif
                <a href="{{url('producto/index')}}" class="btn btn-secondary me-2">Cancelar</a>
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
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>

@endpush

@push('custom-scripts')
  <!-- Custom js here -->
   <script src="{{ asset('assets/js/form-validation.js') }}"></script>
   <script src="{{asset('assets/js/select2-proveedor.js')}}"></script>
   <script src="{{asset('assets/js/select2-rubro.js')}}"></script>
   <script src="{{asset('assets/js/select2-marca.js')}}"></script>
  <script src="{{ asset('assets/js/flatpickr.js') }}"></script>

  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
  <script src="{{ asset('assets/js/tags-input.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
  <script>
      $(document).ready(function() {
        // INICIO SCRIPT OFERTA
          const enOfertaCheckbox = $('#en_oferta');
          const fechaDesdeInput = $('input[name="oferta_fecha_desde"]');
          const fechaHastaInput = $('input[name="oferta_fecha_hasta"]');
          const precioOfertaInput = $('#precio_costo_oferta');

          // Función para habilitar o deshabilitar los campos de fecha y precio de oferta según el estado del checkbox
          function toggleCamposOferta() {
              const estadoOferta = enOfertaCheckbox.prop('checked');
              fechaDesdeInput.prop('disabled', !estadoOferta);
              fechaHastaInput.prop('disabled', !estadoOferta);
              precioOfertaInput.prop('disabled', !estadoOferta);
              // Si la oferta está activada, establecer los atributos "required" en los campos de fecha y precio de oferta
              if (estadoOferta) {
                  fechaDesdeInput.prop('required', true);
                  fechaHastaInput.prop('required', true);
                  precioOfertaInput.prop('required', true);
              } else {
                  // Si la oferta está desactivada, eliminar los atributos de validación "required"
                  fechaDesdeInput.prop('required', false);
                  fechaHastaInput.prop('required', false);
                  // Si el campo precio de oferta está vacío, eliminar el atributo de validación "required"
                  if (!precioOfertaInput.val()) {
                      precioOfertaInput.prop('required', false);
                  }
              }
          }

          // Llama a la función al cargar la página para establecer el estado inicial
          toggleCamposOferta();

          // Agrega un evento change al checkbox para que los campos se activen o desactiven según sea necesario
          enOfertaCheckbox.on('change', toggleCamposOferta);

          // Agrega un evento input al campo de precio de oferta para actualizar el atributo "required"
          precioOfertaInput.on('input', function() {
              // Si el campo precio de oferta tiene un valor, establecer el atributo "required"
              if ($(this).val()) {
                  $(this).prop('required', true);
              } else {
                  // Si el campo precio de oferta está vacío, eliminar el atributo "required"
                  $(this).prop('required', false);
              }
          });
          // FIN SCRIPT OFERTA


      });
      
      //INICIO SCRIPT PRECIO
      let precioVentaEditado = false; // Bandera para detectar edición manual

function calcularPrecioVenta() {
    let precioCosto = parseFloat($('#precio_costo').val()) || 0;
    let gananciaStr = $('select[name="id_lista_ganancia"] option:selected').text();
    let ganancia = parseFloat(gananciaStr.replace('%', '').trim()) || 0;

    if (!precioVentaEditado) {
        let precioVenta = precioCosto * (1 + ganancia / 100);
        $('#precio_venta').val(precioVenta.toFixed(2));
    }

    calcularPrecioVentaIva(); // Recalcular el precio con IVA
}

function calcularPrecioVentaIva() {
    let precioVenta = parseFloat($('#precio_venta').val()) || 0;
    let ivaStr = $('select[name="tipo_iva"] option:selected').text();
    let iva = parseFloat(ivaStr.replace('%', '').trim()) || 0;

    let precioVentaIva = precioVenta * (1 + iva / 100);
    $('#precio_venta_iva').val(precioVentaIva.toFixed(2));
  }

    // Detectar cuando el usuario modifica manualmente el precio de venta
    $('#precio_venta').on('input', function () {
        precioVentaEditado = true;
        calcularPrecioVentaIva(); // Solo actualiza el precio con IVA
    });

    // Si cambia el precio de costo o la ganancia, recalcular (pero sin sobrescribir el precio de venta si fue editado)
    $('#precio_costo').on('input', function () {
        precioVentaEditado = false; // Resetear la edición manual
        calcularPrecioVenta();
    });

    $('select[name="id_lista_ganancia"]').on('change', function () {
        precioVentaEditado = false; // Resetear la edición manual
        calcularPrecioVenta();
    });

    // Si cambia el IVA, solo recalculamos el precio con IVA sin tocar el precio de venta
    $('select[name="tipo_iva"]').on('change', calcularPrecioVentaIva);

    // Ejecutar una vez al cargar la página
    calcularPrecioVenta();

  //FIN SCRIPT PRECIO
  </script>

@endpush
