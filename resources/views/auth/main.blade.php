<!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="robots" content="noindex, nofollow">
      <title>Rumbero | @yield('title')</title>

      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

      @include('auth.styles')
      @yield('custom-css')

    </head>
<body>
    <body>
      <section class="content">
        @yield('main-content')
      </section>
      @include('auth.scripts')
      @yield('custom-js')
    </body>
  </html>
