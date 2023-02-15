@extends('auth.main')

@section('title', 'Inicio de Sesión')

@section('main-content')

    <div class="limiter">
        <div class="container-login100" style="background-image: url('https://media.istockphoto.com/videos/loopable-color-gradient-background-animation-video-id1182636595?s=640x640');">
            <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
                <form class="login100-form validate-form" method="POST" action="{{route('login')}}">
                    {{ csrf_field() }}
                    <span class="login100-form-title p-b-49">
                        Inicio de sesión
                    </span>

                    <span class="label-input100">Correo *</span>
                    <div class="wrap-input100 m-b-23">
                        <i class="fa fa-user icon"></i>
                        <input class="input100" type="email" placeholder="Introduce tu correo" required autocomplete="email" name="email">
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong class="m-b-23">{{$message}}</strong>
                        </span>
                    @enderror

                    <span class="label-input100">Contraseña *</span>
                    <div class="wrap-input100">
                        <i class="fa fa-lock icon"></i>
                        <input class="input100" type="password" placeholder="Introduce tu contraseña" required autocomplete="current-password" name="password">
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{$message}}</strong>
                        </span>
                    @enderror
                    
                    <div class="text-right p-t-8 p-b-31">
                        <a href="#">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                    
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn" type="submit">
                                Iniciar sesión
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
