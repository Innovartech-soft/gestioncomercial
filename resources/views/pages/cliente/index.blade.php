@extends('layout.master')

@push('plugin-styles')
<!-- Plugin css import here -->

@endpush

@section('content')
<!-- Page content here -->
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Clientes</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <a href="{{ route('cliente.create') }}" class="btn btn-primary mr-3 mr-md-2"><i
        class="mdi mdi-plus-circle mr-1"></i> Nuevo</a>
  </div>
</div>
<div class="row">
  @include('pages.mensajesflash.index')
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Listado De Clientes</h6>
        {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables
            Documentation </a>for a full list of instructions and other options.</p> --}}
        <div class="table-responsive">
          <table id="dataTableClientes" class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Razon Social</th>
                <th>DNI/CUIT</th>
                <th>Direccion</th>
                <th>Telefono</th>
                <th>Email</th>
                <th>Vendedor</th>
                <th>Lista</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($clientes as $cliente)
              <tr>
                <td><a href="{{ route('cliente.edit', $cliente->id) }}">{{ $cliente->codigo }}</a></td>
                <td>{{ $cliente->razon_social }}</td>
                <td>{{ $cliente->dni_cuit }}</td>
                <td>{{ $cliente->direccion }}</td>
                <td>{{ $cliente->tel_1 }}</td>
                <td>{{ $cliente->email }}</td>
                <td>{{ $cliente->vendedor?$cliente->vendedor->nombre:'-'}}</td>
                <td>{{ $cliente->lista->nombre }}</td>
                <td class="text-end">
                  <a href="{{ route('cliente.edit', $cliente->id) }}" class="btn btn-primary btn-sm"><i
                      class="mdi mdi-pencil"></i></a>
                  <form onsubmit="eliminarAlert(event)" action="{{ route('cliente.destroy',$cliente->id) }}"
                    method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>
                    {{-- <a href="{{ route('cliente.destroy', $cliente->id) }}" class="btn btn-danger btn-sm"><i
                        class="mdi mdi-delete"></i></a> --}}
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
<script src="{{ asset('assets/js/data-table-cliente.js') }}"></script>
<script src="{{ asset('assets/js/cliente-delete.js') }}"></script>
@endpush