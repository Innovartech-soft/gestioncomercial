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
            <h4 class="mb-3 mb-md-0">Logs del Sistema</h4>
        </div>
    </div>
    
    <div class="row">
    @include('pages.mensajesflash.index')
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Logs </h6>
        {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}
        <div class="table-responsive">
          <table id="dataTableLog" class="table">
            <thead>
                <tr>
                    <th>#Usuario ID</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
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
  <script>
    var urlData = "{{ route('logs.data') }}";
  </script>
  <script src="{{ asset('assets/js/data-table-log.js') }}"></script>
@endpush
