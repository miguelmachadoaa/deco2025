 <section class="w3l-covers-18">
          <div class="covers-main editContent">
              <div class="container">
                <div class="main-titles-head ">
                  <h3 class="header-name">{{ __('Productos Destacados') }}  
                  </h3>
                </div>
                  <div class="gallery-image row">

                    @foreach($productos as $producto)

                    <div class="col-lg-4 col-md-6 mb-4">
                      <div class="img-box">
                        <a href="{{url('productos/'.$producto->slug)}}"><img style="height: 300px; width:100%" src="{{url('uploads/productos/'.$producto->imagenes()->first()->imagen)}}" alt="{{$producto->titulo}}" class="img-responsive "></a>
                        <h5 class="my-2">
                          <a href="{{url('productos/'.$producto->slug)}}">{{$producto->titulo}}</a>
                        </h5>
                        @if($configuracion->show_precio == 1)
                        <div class="blog-date"> 
                          <p class="pos-date"><span class="fa fa-money mr-1"></span>{{$producto->precio}} </p> <p class="pos-date text-right"></p>
                        </div>
                        @endif
                          <p class="para">{{substr(strip_tags($producto->descripcion), 0, 90).'...'}}</p>
                          <div class=" pt-3 " style="border-top: 1px solid rgba(0,0,0,0.1)">
                            <!-- h5>from $525</!-->
                            <a href="{{url('productos/'.$producto->slug)}}" class="  btn btn-primary">Ver Detalle<span class="fa fa-chevron-right"></span></a>
                          </div>
                        </div>
                    </div>

                    @endforeach

                    </div>
                  </div>
              </div>
          </div>
      </section>