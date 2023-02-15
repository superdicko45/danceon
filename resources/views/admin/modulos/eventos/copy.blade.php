@extends('adminTemplate.main')

@section('title', 'Copiar evento')

@section('custom-css')
  <link href="{{asset('/public/plugins/alert/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('/public/plugins/datepicker/datetimepicker.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('/public/plugins/select/select2.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('main-content')
  <div class="container">
    <form id="form_edit" action="{{url('admin/eventos/store')}}" method="post" accept-charset="UTF-8" name="form_eventos" enctype="multipart/form-data">
      <input type="hidden" name="evento_id" value="{{$evento->evento_id}}">
      {{ csrf_field() }}

      <div class="box box-warning">
        <div class="container-fluid">
          <div class="col-md-12">
            <h2>Copiar Evento</h2>
            <hr>
            <h3>Información general</h3>
            <br>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="titulo">Título del evento *</label>
              <input type="text" class="form-control" name="titulo" required maxlength="100" value="{{$evento->titulo}}">
            </div>
            <div class="form-group col-md-4">
              <label for="fecha_inicio">Fecha inicio * <i class="fa fa-info-circle tooltip-icon" data-toggle="tooltip"
                data-placement="top" title="Fecha de inicio del evento"></i></label>
                <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" required value="{{$evento->fecha_inicio}}">
            </div>
            <div class="form-group col-md-4">
              <label for="fecha_final">Fecha final <i class="fa fa-info-circle tooltip-icon" data-toggle="tooltip"
                data-placement="top" title="Fecha de termino del evento"></i></label>
                <input type="text" class="form-control" id="fecha_final" name="fecha_final" value="{{$evento->fecha_final}}">
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="cover">Cover * <i class="fa fa-info-circle tooltip-icon" data-toggle="tooltip"
                data-placement="top" title="Costo de entrada"></i></label>
                <input type="number" class="form-control" name="cover" min="0" value="{{$evento->cover}}" required>
            </div>
            <div class="form-group col-md-4">
              <label for="preventa">Preventa  <i class="fa fa-info-circle tooltip-icon" data-toggle="tooltip"
                data-placement="top" title="Costo de preventa"></i></label>
                <input type="number" class="form-control" name="preventa" min="0" value="{{$evento->preventa}}">
            </div>
            <div class="form-group col-md-4">
              <label for="contacto">Modo de reserva <i class="fa fa-info-circle tooltip-icon" data-toggle="tooltip"
                data-placement="top" title='Liga a la que se va a redireccionar al usuario cuando seleccione "reservar", por ejemplo, link de whatsapp, paypal, etc.'></i></label>
                <a href="https://crear.wa.link/" target="blank" style="right: 15px;position: absolute;">Crear link de WhatsApp</a>
              <input type="text" class="form-control" name="contacto" value="{{$evento->contacto}}">
            </div>
          </div>
                
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="tipo_evento">Tipo de evento *</label>
              <select name="tipo_evento">
                @foreach($tipos_eventos as $item)
                  <option value="{{$item->id}}" @if($item->id == $evento->tipo_evento_id) selected @endif>
                    {{$item->text}}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-4">
              <label for="foto">Foto / Promocional 
              @if($evento->promocional != null)
                <i class="fa fa-info-circle tooltip-icon" data-toggle="tooltip"
                  data-placement="top" title='Da click en "Ver imágen" para ver el promocional actual del evento'></i></label>
              @endif
              <input type="file" name="foto" class="form-control" accept="image/*">
              @if($evento->promocional != null)
                <small>
                  <a href="{{$evento->promocional}}" target="blank" style="right: 15px;position: absolute;">Ver imágen</a>
                </small>
              @endif  
            </div>
            <div class="form-group col-md-4">
              <label for="generos">Géneros del evento</label>
              <select name="generos[]" multiple>
                @foreach($generos as $item)
                <option value="{{$item->id}}" @if(in_array($item->id, $egeneros)) selected @endif>
                  {{$item->text}}
                </option>
                @endforeach
              </select>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="descripcion">Descripción del evento*</label>
              <textarea name="descripcion" class="form-control" required rows="3" cols="80">{{$evento->descripcion}}</textarea>
            </div>
            <div class="form-group col-md-2">
              <label for="status">Status</label>
              <select name="status">
                <option value="1" @if($evento->activo == 1) selected @endif>Activo</option>
                <option value="0" @if($evento->activo == 0) selected @endif>Inactivo</option>
              </select>
            </div>
          </div>
          
        </div>
      </div>

      <div class="box box-warning">
        <div class="container-fluid">
          <div class="col-md-12">
            <h3>Ubicación del evento</h3>
            <br>
          </div>
          <input type="hidden" name="latitud" required value="{{$evento->latitud}}" id="latitud">
          <input type="hidden" name="longitud" required value="{{$evento->longitud}}" id="longitud">
          
          <div class="form-row" id="direction_form">
            <div class="form-group col-md-6">
              <label for="direccion">Dirección del evento *</label>
              <input type="text" class="form-control" name="direccion" maxlength="255" required value="{{$evento->direccion}}" id="direccion">
            </div>
            <div class="form-group col-md-3">
              <label for="colonia">Colonia *</label>
              <input type="text" class="form-control" name="colonia" maxlength="255" required value="{{$evento->colonia}}" id="colonia">
            </div>
            <div class="form-group col-md-3">
              <label for="ciudad">Ciudad del evento *</label>
              <select name="ciudad">
                @foreach($ciudades as $item)
                  <option value="{{$item->id}}" @if($item->id == $evento->ciudad_id) selected @endif>
                    {{$item->text}}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group" id="direction_text">
            <div class="col-md-12">
              <label>Dirección actual del evento</label>
              <h3>{{$evento->direccion}} 
                <button type="button" class="btn bg-maroon btn-xs" id="edit_direction">
                  <i class="fa fa-pencil"></i> Editar Dirección
                </button>
              </h3>
            </div>
          </div>
          
        </div>
      </div>

      <div class="box box-warning">
        <div class="container-fluid">
          <div class="col-md-12">
            <h3>Organizadores e Invitados</h3>
            <br>
          </div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="organizadores">Organizadores del evento </label>
              <select class="users" name="organizadores[]" multiple>
                @foreach($organizadores as $item)
                  <option value="{{$item->id}}" selected>{{$item->text}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="invitados">Invitados especiales del evento </label>
              <select class="users" name="invitados[]" multiple>
                @foreach($invitados as $item)
                  <option value="{{$item->id}}" selected>{{$item->text}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12">
                <label for="red_social_id">Red social del evento </label>
                <div class="row">
                  <div class="col-md-6">
                    <select name="red_social_id">
                      @foreach($redes as $item)
                        <option value="{{$item->id}}" @if($red != null && $red->red_social_id) selected @endif>
                          {{$item->text}}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="red_social" class="form-control" maxlength="255" placeholder="ejemplo: '/eventos/123456'"
                    @if($red != null) value="{{$red->red_social}}" @endif>
                  </div>
                </div>
            </div>
          </div>
            
        </div>  
      </div>
    </form>
  </div>
    <div class="container">
      <div class="text-right">
        <a href="{{url('admin/eventos')}}" class="btn btn-default btn-lg" >
          <i class="fa fa-chevron-left"></i> Cancelar
        </a>
        <button type="submit" class="btn bg-maroon btn-lg" id="enviar">
          <i class="fa fa-check"></i> Guardar
        </button>
      </div>
    </div>
@endsection

@section('custom-js')
  <script type="text/javascript" src="{{asset('/public/plugins/alert/sweetalert2.js')}}"></script>
  <script type="text/javascript" src="{{asset('/public/plugins/validator/jquery.validate.js')}}"></script>
  <script type="text/javascript" src="{{asset('/public/plugins/select/select2.full.js')}}"></script>
  <script type="text/javascript" src="{{asset('/public/plugins/datepicker/datetimepicker.full.js')}}"></script>

  <script type="text/javascript" src="{{asset('/public/plugins/validator/localization/messages_es.js')}}"></script>
  <script type="text/javascript" src="{{asset('/public/plugins/select/localization/es.js')}}"></script>
  <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyCDJZrP0A2PzFrHQXOR7YsBJaeWrOaW0tg " async defer></script>
  {{-- Custom --}}
  <script type="text/javascript" src="{{asset('/public/js/modulos/eventos/copy.js')}}"></script>

@endsection