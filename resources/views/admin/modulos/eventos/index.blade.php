@extends('admin.template.main')

@section('title', 'Eventos')

@section('custom-css')
  <link rel="stylesheet" href="{!! asset('/plugins/datatables/dataTables.bootstrap.css') !!}">
  <link rel="stylesheet" href="{!! asset('/plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css') !!}">
  <link rel="stylesheet" href="{!! asset('/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css') !!}">
@endsection

@section('main-content')
  <div class="box box-primary">
    <div class="container-fluid">
      <h3>Reporte de eventos</h3><br><br>
      <div class="row" align="center">
        <a href="{{url('admin/eventos/create')}}">
          <button type="button" class="btn bg-maroon">
            <i class="fa fa-plus"></i> Nuevo Evento
          </button>
        </a>
      </div>
      <hr>
      <div class="table-responsive">
        <table id="table-list" class="table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th>Folio</th>
              <th>Evento</th>
              <th>Organizador</th>
              <th>Ambiente</th>
              <th>Tipo evento</th>
              <th>Fecha</th>
              <th></th>
            </tr>
          </thead>

          <tbody>
            @foreach($eventos as $evento)
              <tr>
                <td>{{$evento->id}}</td>
                <td>{{$evento->titulo}}</td>
                <td>{{$evento->organizador}}</td>
                <td>@lang('enums.vibes.' . $evento->ambiente)</td>
                <td>@lang('enums.periodicity_types.' . $evento->tipo_frecuencia)</td>
                <td>
                  @if($evento->tipo_frecuencia == \App\Enums\Periodicity_types::REGULAR)
                    @lang('enums.weekdays.' . $evento->dia)
                  @else
                    {{$evento->fecha_inicio}}
                  @endif
                </td>
                <td>
                  <div class="btn-group">
                    <a title='Ver evento' class="btn btn-sm btn-info" href="{{url('eventos/show/' . $evento->id)}}"><i class="fa fa-eye"></i></a>
                    <a title='Editar evento' class="btn btn-sm btn-warning" href="{{url('admin/eventos/' . $evento->id . '/edit')}}"><i class="fa fa-edit"></i></a>
                    <a title='Crear desde este evento' class="btn btn-sm btn-success" href="{{url('admin/eventos/' . $evento->id . '/copy')}}"><i class="fa fa-copy"></i></a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>Folio</th>
              <th>Evento</th>
              <th>Organizador</th>
              <th>Ambiente</th>
              <th>Tipo evento</th>
              <th>Fecha</th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

@endsection

@section('custom-js')

    <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/extensions/Buttons/js/buttons.bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/extensions/Buttons/js/buttons.flash.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/extensions/Buttons/js/jszip.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/extensions/Buttons/js/pdfmake.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/extensions/Buttons/js/vfs_fonts.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/datatables/extensions/Buttons/js/buttons.print.min.js') }}" type="text/javascript"></script>

  {{-- Custom --}}
  <script type="text/javascript" src="{{asset('/js/admin/table.js')}}"></script>
@endsection
