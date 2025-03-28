@extends('layout.master')

@push('plugin-styles')
<!-- Plugin css import here -->
<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- Page content here -->
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Actualizar Precios de Productos</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#formPrecios" style="margin-right: 10px;">
      <i class="mdi mdi-table-edit"></i> Precios
    </button>
    <a href="{{ route('producto.create') }}" class="btn btn-primary" style="margin-right: 10px;"><i
        class="mdi mdi-plus-circle mr-1"></i> Nuevo</a>
  </div>
</div>
<div class="">
  @include('pages.mensajesflash.index')

  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Listado de Productos</h6>
        <div class="row">
          <div class="table table-responsive">
            <table id="dataTableProductos" class="table table-hover">
              <thead>
                <tr>
                  <th>#Cod</th>
                  <th style="display:none">Codigo</th>
                  <th style="display:none">Codigo Barras</th>
                  <th>Nombre</th>
                  <th>Costo s/IVA</th>
                  <th>IVA</th>
                  <th style="display:none">Rubro</th>
                  <th style="display:none">Marca</th>
                  <th style="display:none">Proveedor</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($productos as $producto)
                <tr>
                  <td><a href="{{ route('producto.edit', $producto->id) }}">{{ $producto->codigo_interno }}</a></td>
                  <td style="display:none">{{ $producto->codigo }}</td>
                  <td style="display:none">{{ $producto->codigo_barras}}</td>
                  <td>{{ $producto->nombre }}</td>
                  <td><div class="input-group mb-3">
					    <div class="input-group-prepend">
					        <span class="input-group-text">$</span>
					    </div>
					    <input type="number" class="form-control precioCosto" id="precioCosto" name="precio" value="{{$producto->precio_costo}}" data-id-producto="{{$producto->id}}"/>
					</div></td>
                  <td>{{ (!is_numeric($producto->getTipoIva())?$producto->getTipoIva():$producto->getTipoIva().'%')}}
                  </td>
                  <td style="display:none">{{ optional($producto->rubro)->nombre}}</td>
                  <td style="display:none">{{ optional($producto->marca)->nombre}}</td>
                  <td style="display:none">{{ optional($producto->proveedor)->nombre}}</td>
                  <td class="text-end">
                    <a href="#" type="button" class="btn btn-info btn-sm btnHistorialModal prod{{$producto->id}}" data-bs-toggle="modal"
                      data-bs-target="#formHistorial" data-id-producto="{{$producto->id}}" data-precio="{{$producto->precio_costo}}"><i
                        class="mdi mdi-history"></i></a>
                    
                    <a href="{{ route('producto.edit', $producto->id) }}" class="btn btn-primary btn-sm"><i
                        class="mdi mdi-pencil"></i></a>
                    <form onsubmit="eliminarAlert(event)" action="{{ route('producto.destroy',$producto->id) }}"
                      method="POST" style="display: inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>
                  </td>
                  </form>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @include('pages/producto/stock-form-modal')
        </div>
      </div>
    </div>
  </div>

  @include('pages/producto/precios-modal')
  @include('pages/producto/historial-precios-modal')
  @endsection

  @push('plugin-scripts')
  <!-- Plugin js import here -->
  <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>

  @endpush

  @push('custom-scripts')
  <!-- Custom js here -->
  <script>
    // Este script se encargará de abrir el modal cuando hagas clic en el botón
      $(document).ready(function () {
          $('#formExportar').modal('hide'); // Esto asegura que el modal esté oculto inicialmente
          $('button.btn-success').click(function () {
              $('#formExportar').modal('show'); // Muestra el modal cuando se hace clic en el botón
          });
      });
  </script>
  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
  <script src="{{ asset('assets/js/tags-input.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
  <script src="{{ asset('assets/js/register-delete.js') }}"></script>
  <script src="{{ asset('assets/js/data-table-producto.js') }}"></script>
  <script src="{{ asset('assets/js/producto-script.js') }}"></script>
  @endpush
