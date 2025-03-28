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
    <h4 class="mb-3 mb-md-0">Informe de Stock</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
      <a href="{{ route('producto.exportarstockexcel') }}" class="btn btn-success" style="margin-right: 10px;"><i
          class="mdi mdi-file-excel"></i> Exportar</a>
    
    <a href="{{ route('producto.create') }}" class="btn btn-primary" style="margin-right: 10px;"><i
        class="mdi mdi-plus-circle mr-1"></i> Nuevo</a>
  </div>
</div>
<div class="">
  @include('pages.mensajesflash.index')

  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Listado de Productos en Stock Mínimo</h6>
        <div class="row">
          <div class=" table table-responsive">
            <table id="dataTableProductos" class=" table table-hover">
              <thead>
                <tr>
                  <th>Codigo Interno</th>
                  <th style="display:none">Codigo</th>
                  <th>Nombre</th>
                  <th>Stock</th>
                  <th style="display:none">Rubro</th>
                  <th>Marca</th>
                  <th>Proveedor</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($productos as $producto)
                <tr>
                  <td><a href="{{ route('producto.edit', $producto->id) }}">{{ $producto->codigo_interno }}</a></td>
                  <td style="display:none">{{ $producto->codigo }}</td>
                  <td>{{ $producto->nombre }}</td>
                  <td>{{ $producto->stock}}</td>
                  <td style="display:none">{{ optional($producto->rubro)->nombre}}</td>
                  <td>{{ $producto->marca->nombre}}</td>
                  <td>{{ optional($producto->proveedor)->nombre}}</td>
                  <td class="text-end">
                    <a href="#" type="button" class="btn btn-success btn-sm btnStockModal" data-bs-toggle="modal"
                      data-bs-target="#formGestionStock" data-id-producto="{{$producto->id}}"><i
                        class="mdi mdi-transfer"></i></a>
                  </td>
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
  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
  <script src="{{ asset('assets/js/tags-input.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
  <script src="{{ asset('assets/js/register-delete.js') }}"></script>
  <script src="{{ asset('assets/js/data-table-producto.js') }}"></script>
  <script src="{{ asset('assets/js/producto-script.js') }}"></script>
  @endpush