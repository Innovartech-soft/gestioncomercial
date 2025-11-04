@extends('layout.master')

@push('plugin-styles')
<!-- Plugin css import here -->
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
@endpush


@section('content')
    @livewire('venta.gestion-venta')
@endsection

@push('plugin-scripts')
  @livewireScripts
<!-- Plugin js import here -->
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script>
    // Aquí puedes agregar la lógica JS para manejar los eventos y la lógica dinámica
    // Puedes usar jQuery o vanilla JS para manejar selects, inputs, etc.
</script>
@endpush