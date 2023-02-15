@extends('adminTemplate.main')

@section('title', 'Alta de Evento')

@section('custom-css')
  <link href="{{asset('/public/plugins/alert/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('/public/plugins/datepicker/datetimepicker.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('/public/plugins/select/select2.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('main-content')
  <div class="container">
    <form id="form_create" action="{{url('admin/eventos/store')}}" method="post" accept-charset="UTF-8" name="form_eventos" enctype="multipart/form-data">
      {{ csrf_field() }}

      <div class="box box-warning">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <h2>Crear Evento</h2>
              <hr>
              <h3>Información general</h3>
              <br>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-4">
              <label for="titulo">Título del evento *</label>
              <input type="text" class="form-control" name="titulo" required maxlength="100">
            </div>
            <div class="form-group col-md-4">
              <label for="fecha_inicio">Fecha inicio * <i class="fa fa-info-circle tooltip-icon" data-toggle="tooltip"
                data-placement="top" title="Fecha de inicio del evento"></i></label>
              <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <div class="form-group col-md-4">
              <label for="fecha_final">Fecha final <i class="fa fa-info-circle tooltip-icon" data-toggle="tooltip"
                data-placement="top" title="Fecha de termino del evento"></i></label>
              <input type="text" class="form-control" id="fecha_final" name="fecha_final">
            </div>
          </div>
                
          <div class="row">
            <div class="form-group col-md-4">
              <label for="cover">Cover * <i class="fa fa-info-circle tooltip-icon" data-toggle="tooltip"
                data-placement="top" title="Costo de entrada"></i></label>
                <input type="number" class="form-control" name="cover" min="0" required>
            </div>
            <div class="form-group col-md-4">
              <label for="preventa">Preventa <i class="fa fa-info-circle tooltip-icon" data-toggle="tooltip"
                data-placement="top" title="Costo de preventa"></i></label>
                <input type="number" class="form-control" name="preventa" min="0">
            </div>
            <div class="form-group col-md-4">
              <label for="contacto">Modo de reserva <i class="fa fa-info-circle tooltip-icon" data-toggle="tooltip"
                data-placement="top" title='Liga a la que se va a redireccionar al usuario cuando seleccione "reservar", por ejemplo, link de whatsapp, paypal, etc.'></i></label>
                <a href="https://crear.wa.link/" target="blank" style="right: 15px;position: absolute;">Crear link de WhatsApp</a>
                <input type="text" class="form-control" name="contacto">
            </div>
          </div>
                      
          <div class="row">
            <div class="form-group col-md-4">
              <label for="tipo_evento">Tipo de evento *</label>
              <select name="tipo_evento">
                @foreach($tipos_eventos as $item)
                <option value="{{$item->id}}">{{$item->text}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-4">
              <label for="foto">Foto / Promocional</label>
              <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="form-group col-md-4">
              <label for="generos">Géneros del evento</label>
              <select name="generos[]" multiple>
                @foreach($generos as $item)
                  <option value="{{$item->id}}">{{$item->text}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-12">
              <label for="descripcion">Descripción del evento*</label>
              <textarea name="descripcion" class="form-control" required rows="3" cols="80"></textarea>
            </div>
          </div>

        </div>
      </div>
    
      <div class="box box-warning">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <h3>Ubicación del evento</h3>
              <br>
            </div>
          </div>
          <input type="hidden" name="latitud" required id="latitud">
          <input type="hidden" name="longitud" required id="longitud">
          
          <div class="row" id="direction_form">
            <div class="form-group col-md-6">
              <label for="direccion">Dirección del evento *</label>
              <input type="text" class="form-control" name="direccion" maxlength="255" required id="direccion">
            </div>
            <div class="form-group col-md-3">
              <label for="colonia">Colonia *</label>
              <input type="text" class="form-control" name="colonia" maxlength="255" required id="colonia">
            </div>
            <div class="form-group col-md-3">
              <label for="ciudad">Ciudad del evento *</label>
              <select name="ciudad">
                @foreach($ciudades as $item)
                  <option value="{{$item->id}}">
                    {{$item->text}}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="box box-warning">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <h3>Organizadores e Invitados</h3>
              <br>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-12">
              <label for="organizadores">Organizadores del evento </label>
              <select class="users" name="organizadores[]" multiple>
                @if($organizador != null)
                  <option value="{{$organizador->usuario_id}}" selected>{{$organizador->nombre}} - {{$organizador->username}}</option>
                @endif
              </select>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-12">
              <label for="invitados">Invitados especiales del evento </label>
              <select class="users" name="invitados[]" multiple>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-12">
              <label for="red_social_id">Red social del evento </label>
              <div class="row">
                <div class="col-md-6">
                  <select name="red_social_id">
                    @foreach($redes as $item)
                      <option value="{{$item->id}}">{{$item->text}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <input type="text" name="red_social" class="form-control" maxlength="255" placeholder="ejemplo: '/eventos/123456'">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>   

      <div class="text-right">
        <a href="{{url('admin/eventos')}}" class="btn btn-default btn-lg" >
          <i class="fa fa-chevron-left"></i> Cancelar
        </a>
        <button type="submit" class="btn bg-maroon btn-lg" id="enviar">
          <i class="fa fa-check"></i> Guardar
        </button>
      </div>
    </form>
  </div>
@endsection

@section('custom-js')
  <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyCDJZrP0A2PzFrHQXOR7YsBJaeWrOaW0tg " async defer></script>
  <script type="text/javascript" src="{{asset('/public/plugins/alert/sweetalert2.js')}}"></script>
  <script type="text/javascript" src="{{asset('/public/plugins/validator/jquery.validate.js')}}"></script>
  <script type="text/javascript" src="{{asset('/public/plugins/select/select2.full.js')}}"></script>
  <script type="text/javascript" src="{{asset('/public/plugins/datepicker/datetimepicker.full.js')}}"></script>

  <script type="text/javascript" src="{{asset('/public/plugins/validator/localization/messages_es.js')}}"></script>
  <script type="text/javascript" src="{{asset('/public/plugins/select/localization/es.js')}}"></script>
  {{-- Custom --}}
  <script type="text/javascript" src="{{asset('/public/js/modulos/eventos/create.js')}}"></script>

@endsection
