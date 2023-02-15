
<div style="padding-top: 20px">
  @foreach($items as $key => $menu)
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
      <a href="{{ url($menu['url']) }}" class="small-box-footer">
        <div class="small-box bg-maroon">
          <div class="inner">
            <h3>{{$key+1}}</h3>
            <p>{{$menu['titulo']}}</p>
          </div>
          <div class="icon">
            @if(isset($menu['icono']))
              <i class="fa {{$menu['icono']}}"></i>
            @else
              <i class="fa fa-keyboard-o"></i>
            @endif
          </div>
            
        </div>
      </a>
    </div>
  @endforeach
</div>
