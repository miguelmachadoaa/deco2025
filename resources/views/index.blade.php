
@include('partials.header')


<section class="w3l-main-slider" id="home">
    <!-- main-slider -->
    <div class="companies20-content">
      
      <div class="owl-one owl-carousel owl-theme">

            @foreach($sliders as $slider)

                <div class="item text-center">
                    <li>
                        <div class="slider-info banner-view bg bg2" data-selector=".bg.bg2" style="background: url({{url('uploads/sliders/'.$slider->imagen)}}) no-repeat center;
    background-size: cover;">
                            <div class="banner-info">
                              <div class="container">
                                  <div class="banner-info-bg mr-auto">
                                    <h5 class="">{{$slider->titulo}}</h5>
                                    <a class="btn  btn-outline-light mt-lg-5 mt-4" href="{{url('contacto')}}">{{__('main.contacto')}}</a>
                                  </div>
                              </div>
                            </div>
                        </div>
                    </li>
              </div>

            @endforeach
        
      </div>
    </div>
    <script src="{{url('frontend/assets/js/owl.carousel.js')}}"></script>
    <!-- script for -->
    <script>
      $(document).ready(function () {
        $('.owl-one').owlCarousel({
          loop: true,
          margin: 0,
          nav: false,
          dots:true,
          responsiveClass: true,
          autoplay: true,
          autoplayTimeout: 5000,
          autoplaySpeed: 1000,
          autoplayHoverPause: false,
          animateIn: 'fadeIn',
          animateOut: 'fadeOut',
          smartSpeed: 600,
          responsive: {
            0: {
              items: 1,
              nav: false
            },
            480: {
              items: 1,
              nav: false
            },
            667: {
              items: 1,
              nav: true
            },
            1000: {
              items: 1,
              nav: true
            }
          }
        })
      })
    </script>
    <!-- //script -->
    <!-- /main-slider -->
  </section>

@include('categorias_list')

@include('productos_list')

 <section class="w3l-clients" id="client">
        <div class="call-w3">
            <div class="container text-center">
                <div class="title-head">
                <h3 class="dancing-script-400">Descubre Nuevos Ambientes: Actualidad en Diseño y Protección </h3>
                <p>Desde soluciones térmicas hasta estilos decorativos, encuentra la persiana ideal para tu espacio con nuestras opciones exclusivas</p>
                <a class="btn btn-theme2 mt-lg-5 mt-4" href="{{url('contacto')}}">{{__('main.contacto')}}</a>
            </div>
              
                  </div>
            </div>
    </section>


  
@include('blogs_list')

<section class="w3l-teams-15">
      <div class="team-single-main editContent">
          <div class="container">
  
          <div  class="row">
              <div class="column2 col-lg-6">
              
                  <img src="frontend/assets/images/b1.jpg" alt="product" class="img-responsive ">
              </div>
                  <div class="nature-row  coloum4 col-lg-6">
                      <h6 class="small-title">Toldos y Persianas</h6>
                          <h3>DecoHouse 3000 C.A. </h3>
                      <p class="para editContent text ">
                        nos apasiona transformar tus espacios en lugares llenos de confort y estilo. Con 25 años de experiencia en el mercado, nos hemos consolidado como líderes en la fabricación e instalación de toldos y persianas, ofreciendo soluciones personalizadas que combinan innovación, calidad y diseño!</p>
                          <a href="{{url('/contacto')}}" class="action-button btn mt-lg-5 mt-4">{{__('main.contacto')}}</a>
                      </div>
                  </div>
          </div>
          </div>
      </div>
  </section>


       
@include('partials.footer')