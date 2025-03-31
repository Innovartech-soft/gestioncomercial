@extends('layout.master')

@push('plugin-styles')
<!-- Plugin css import here -->
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- Page content here -->
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Listas de Ganancia</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <a href="{{ route('listaganancia.create') }}" class="btn btn-primary mr-3 mr-md-2"><i class="mdi mdi-plus-circle mr-1"></i>
      Nuevo</a>
  </div>
</div>

<div class="row">
  @include('pages.mensajesflash.index')
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Listado De Ganancia</h6>
        {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables
            Documentation </a>for a full list of instructions and other options.</p> --}}
        <div class="table-responsive">
          <table id="dataTableListasGanancia" class="table">
            <thead>
              <tr>
                <th>#ID</th>
                <th>Nombre</th>
                <th>Ganancia</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($listasGanancia as $lista)
              <tr>
                <td>{{ $lista->id }}</td>
                <td>{{ $lista->nombre }}</td>
                <td>{{ $lista->ganancia }}%</td>
                <td class="text-end">
                  <a href="{{ route('listaganancia.edit',$lista->id) }}" class="btn btn-primary btn-sm"><i
                      class="mdi mdi-pencil"></i></a>

                  <form onsubmit="eliminarAlert(event)" action="{{ route('listaganancia.destroy',$lista->id) }}" method="POST"
                    style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection

@push('plugin-scripts')
<!-- Plugin js import here -->
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>

@endpush

@push('custom-scripts')
<!-- Custom js here -->
<script src="{{ asset('assets/js/data-table-lista.js') }}"></script>
<script src="{{ asset('assets/js/listaganancia-delete.js') }}"></script>
@endpush