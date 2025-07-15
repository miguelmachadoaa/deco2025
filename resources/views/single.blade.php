@include('partials.header')

<!-- breadcrumbs -->
<section class="w3l-inner-banner-main">
    <div class="about-inner blog-single editContent">
        <div class="breadcrumbs-sub"> 
            <ul class="breadcrumbs-custom-path">
                <li class="right-side propClone"><a href="{{url('/')}}" class="editContent">Inicio <span class="fa fa-angle-right" aria-hidden="true"></span></a> <p></li>
                <li class="active editContent">{{$producto->titulo}}</li>
            </ul>
            </div>
</div>
</div>
</section>
<!-- breadcrumbs //-->

        
        <div class="showcase-grid">
            <div class="container-fluid row mt-4">
                <div class="col-md-8 showcase">
                    <div class="flexslider">
                          <ul class="slides">
                            @foreach($producto->imagenes as $imagen)
                            <li data-thumb="{{url('uploads/productos/'.$imagen->imagen)}}">
                                <div class="thumb-image">
                                    <img
                                    src="{{url('uploads/productos/'.$imagen->imagen)}}"
                                    alt="{{$producto->titulo}}"
                                    title="{{$producto->titulo}}"
                                    data-imagezoom="true"
                                    class="img-responsive">
                                </div>
                            </li>
                            @endforeach
                           
                          </ul>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-md-4 showcase m-0 p-0 ">
                    <div class="showcase-rt-top mb-4">
                        <div class="pull-left shoe-name mb-4">
                            <p class="mb-2">
                                @foreach($producto->categorias as $cp)
                                    <a
                                    target="_blank"
                                    href="{{url('categorias/'.$cp->slug)}}"
                                    >
                                    <span style="border-radius: 3em;" class="badge badge-secondary p-2 text-white">{{$cp->titulo}}</span>
                                        
                                    </a>
                                @endforeach
                            </p>

                            <h3 class="mb-4">{{$producto->titulo}}</h3>
                            @if($configuracion->show_precio)
                                <h4>&#36;{{$producto->precio}}</h4>
                            @endif

                             <div class="showcase-last mb-4">
                                <p>
                                    {!! $producto->descripcion !!}
                                </p>
                            </div>

                            <!-- Selector de color -->
                        <div class="mb-3">
                            <label class="form-label">Colores disponibles:</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="color" id="color-rojo" autocomplete="off" checked>
                                <label class="btn rounded-circle border" for="color-rojo" style="width: 40px; height: 40px; background-color: #d9534f;"></label>

                                <input type="radio" class="btn-check" name="color" id="color-azul" autocomplete="off">
                                <label class="btn rounded-circle border" for="color-azul" style="width: 40px; height: 40px; background-color: #0275d8;"></label>

                                <input type="radio" class="btn-check" name="color" id="color-verde" autocomplete="off">
                                <label class="btn rounded-circle border" for="color-verde" style="width: 40px; height: 40px; background-color: #5cb85c;"></label>

                                <input type="radio" class="btn-check" name="color" id="color-negro" autocomplete="off">
                                <label class="btn rounded-circle border" for="color-negro" style="width: 40px; height: 40px; background-color: #000;"></label>
                            </div>
                        </div>

                        <!-- Selector de Material -->
                        <div class="mb-3">
                            <label class="form-label">Materiales disponibles:</label>
                            <div class="d-flex flex-wrap gap-2">
                                <input type="radio" class="btn-check" name="material" id="material-tela" autocomplete="off" checked>
                                <label class="btn btn-outline-secondary" for="material-tela">Tela</label>

                                <input type="radio" class="btn-check" name="material" id="material-aluminio" autocomplete="off">
                                <label class="btn btn-outline-secondary" for="material-aluminio">Aluminio</label>

                                <input type="radio" class="btn-check" name="material" id="material-pvc" autocomplete="off">
                                <label class="btn btn-outline-secondary" for="material-pvc">PVC</label>

                                <input type="radio" class="btn-check" name="material" id="material-madera" autocomplete="off">
                                <label class="btn btn-outline-secondary" for="material-madera">Madera</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <a class="btn btn-success " href="https://wa.me/?text={{ urlencode(request()->fullUrl()) }}" target="_blank"><i class="fa fa-whatsapp"></i> Mas informacion</a>
                        </div>


                        </div>
                        
                        <div class="clearfix"></div>
                    </div>
                   
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        
        <div class="specifications">
            <div class="container">
              
                <div class="detai-tabs mt-4 mb-4">

                  <nav class="nav nav-pills nav-fill">
                    <a class="nav-link active"  href="#home" aria-controls="home" role="tab" data-toggle="tab">Caracteristcas</a>
                    <a class="nav-link" href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Condiciones</a>
                    <a class="nav-link" href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Detalles</a>
                    <a class="nav-link disabled">Disabled</a>
                </nav>
                    <!-- Nav tabs -->
                   

                    <!-- Tab panes -->
                    <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">
                    <p> {!! $producto->caracteristicas !!}</p>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="profile">
                    <p> {!! $producto->Condiciones !!}.</p>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="messages">
                        {!! $producto->descripcion !!}
                    </div>
                    </div>
                </div>
            </div>
        </div>

        
        
       @include('productos_list')


@include('partials.footer')