<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle image-profile" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>Rumbero</p>
        <a href="#"><i class="fa fa-circle text-success"></i>Admin</a>
      </div>
    </div>
    <!-- search form -->
    <div class="sidebar-form">

    </div>
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">Dashboard</li>

      @if( Auth::user()->tipo_usuario == 1 )
        <li class="" id="li-link-eventos">
          <a href="{{url('admin/eventos')}}">
            <i class="fa fa-calendar"></i> <span>Eventos</span>
          </a>
        </li>
        <!--
        <li class="" id="li-link-blogs">
          <a href="{{url('admin/blogs')}}">
            <i class="fa fa-commenting"></i> <span>Blogs</span>
          </a>
        </li>
        <li class="" id="li-link-usuarios">
          <a href="{{url('admin/usuarios')}}">
            <i class="fa fa-group"></i> <span>Usuarios</span>
          </a>
        </li>
        <li class="" id="li-link-academias">
          <a href="{{url('admin/academias')}}">
            <i class="fa fa-bank"></i> <span>Academias</span>
          </a>
        </li>
        <li class="" id="li-link-marcas">
          <a href="{{url('admin/marcas')}}">
            <i class="fa fa-cubes"></i> <span>Marcas</span>
          </a>
        </li>
        <li class="" id="li-link-enlaces">
          <a href="{{url('admin/enlaces')}}">
            <i class="fa fa-link"></i> <span>Enlaces</span>
          </a>
        </li>
        <li class="" id="li-link-buzon">
          <a href="{{url('admin/buzon')}}">
            <i class="fa fa-envelope"></i> <span>Buzón</span>
          </a>
        </li>
      -->  
      @endif

      @if( Auth::user()->tipo_usuario == 2 )
        <li class="" id="li-link-eventos">
          <a href="{{url('admin/eventos')}}">
            <i class="fa fa-calendar"></i> <span>Eventos</span>
          </a>
        </li>
        <li class="" id="li-link-academias">
          <a href="{{url('admin/academias')}}">
            <i class="fa fa-bank"></i> <span>Academias</span>
          </a>
        </li>
      @endif

      <li class="" id="li-link-contacto">
        <a href="{{url('contacto')}}">
          <i class="fa fa-question-circle"></i> <span>Ayuda</span>
        </a>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
