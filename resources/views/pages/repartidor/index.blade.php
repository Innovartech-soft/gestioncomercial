@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Repartidores</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <a href="{{ route('repartidor.create') }}" class="btn btn-primary mr-3 mr-md-2">
      <i class="mdi mdi-plus-circle mr-1"></i> Nuevo
    </a>
  </div>
</div>

<div class="row">
  @include('pages.mensajesflash.index')

  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Listado de Repartidores</h6>
        <div class="table-responsive">
          <table id="dataTableRepartidores" class="table">
            <thead>
              <tr>
                <th>#ID</th>
                <th>Nombre</th>
                <th>Contacto</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($repartidores as $repartidor)
              <tr>
                <td>{{ $repartidor->id }}</td>
                <td>{{ $repartidor->nombre }}</td>
                <td>{{ $repartidor->contacto }}</td>
                <td>{{ $repartidor->disponible == 1? 'Activo' : 'Inactivo' }}</td>
                <td class="text-end">
                  <a href="{{ route('repartidor.edit', $repartidor->id) }}" class="btn btn-primary btn-sm"><i class="mdi mdi-pencil"></i></a>
                  <form onsubmit="eliminarAlert(event,this)" action="{{ route('repartidor.destroy', $repartidor->id) }}"
                    method="POST" style="display: inline-block;">
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
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/data-table-repartidor.js') }}"></script>
<script src="{{ asset('assets/js/register-delete.js') }}"></script>
@endpush
