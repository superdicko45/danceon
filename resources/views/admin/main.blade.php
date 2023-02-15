@extends('admin.template.main')

@section('title', 'Dashboard')


@section('main-content')
  <div class="box box-primary">
    <div class="container-fluid">
      <h3>Bienvenido</h3>
      <hr>
      @include('admin.components.menu', $items)
    </div>
  </div>

  <div class="box box-danger">
    <div class="container-fluid">
      <h3>Inicio</h3>
      <hr>
      <div class="box ">
        <div id="map-canvas"></div>
      </div>
    </div>
  </div>
@endsection

