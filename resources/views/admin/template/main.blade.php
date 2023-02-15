<!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="robots" content="noindex, nofollow">
      <title>Rumbero | @yield('title')</title>

      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

      @include('admin.template.styles')
      @yield('custom-css')

    </head>
<body>
    <body class="hold-transition skin-blue sidebar-mini">

      <div class="wrapper">

        @include('admin.template.navbar')

        @include('admin.template.sidebar')

        <div class="content-wrapper">

          <section class="content">
            @yield('main-content')
          </section>

        </div>


        @include('admin.template.footer')

      </div>

      @include('admin.template.scripts')
      @yield('custom-js')
    </body>
  </html>
