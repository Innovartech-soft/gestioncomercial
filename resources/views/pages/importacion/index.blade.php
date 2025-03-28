@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
  <div class="col-md-12 stretch-card grid-margin grid-margin-md-0">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Importacion</h6>
        <p class="text-muted mb-3">Importa tu listado de productos actualizado.</p>
        <form action="store" class="dropzone" method="post" id="importacionFiles" enctype="multipart/form-data">
        @csrf
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('plugin-scripts')
  <!-- Plugin js import here -->

<script src="{{ asset('assets/plugins/dropzone/dropzone.min.js') }}"></script>
  

@endpush

@push('custom-scripts')
  <!-- Custom js here -->
  <script src="{{ asset('assets/js/importacion.js') }}"></script>
@endpush