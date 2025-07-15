<section class="w3l-specification-6">
        <div class="specification-layout editContent">
            <div class="container-fluid">

                <div class="row text-left img-grids">
                   

                    @foreach($categorias as $categoria)

                        @if($loop->iteration == '1')

                        <div class="col-sm-12 p-0">
                            <div class="row ">
                                <div class="col-sm-4 p-0 m-0">
                                    <div class="ser-bg{{$loop->iteration%4==0?'4':$loop->iteration%4}}" style="background: url({{url('uploads/categorias/'.$categoria->imagen)}}) no-repeat center;  background-size: cover;">
                                        <div class="p-md-5 p-3">
                                            <h4><a class="libre-baskerville-regular" href="{{url('categorias/'.$categoria->slug)}}">{{$categoria->titulo}}</a></h4>
                                            <p class="libre-baskerville-regular" >{{substr(strip_tags($categoria->descripcion), 0, 90).'...'}} </p>
            
                                        </div>
                                    </div>
                                </div>


                        @elseif($loop->iteration =='3' )

                                <div class="col-sm-4 p-0 m-0">
                                    <div class="ser-bg{{$loop->iteration%4==0?'4':$loop->iteration%4}}" style="background: url({{url('uploads/categorias/'.$categoria->imagen)}}) no-repeat center;  background-size: cover;">
                                        <div class="p-md-5 p-3">
                                            <h4><a class="libre-baskerville-regular" href="{{url('categorias/'.$categoria->slug)}}">{{$categoria->titulo}}</a></h4>
                                            <p class="libre-baskerville-regular" >{{substr(strip_tags($categoria->descripcion), 0, 90).'...'}} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 p-0">
                            <div class="row">

                        @elseif($loop->iteration == '6')

                                <div class="col-sm-4 p-0 m-0">
                                    <div class="ser-bg{{$loop->iteration%4==0?'4':$loop->iteration%4}}" style="background: url({{url('uploads/categorias/'.$categoria->imagen)}}) no-repeat center;  background-size: cover;">
                                        <div class="p-md-5 p-3">
                                            <h4><a class="libre-baskerville-regular" href="{{url('categorias/'.$categoria->slug)}}">{{$categoria->titulo}}</a></h4>
                                            <p class="libre-baskerville-regular" >{{substr(strip_tags($categoria->descripcion), 0, 90).'...'}} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @else

                        <div class="col-sm-4 p-0 m-0">
                            <div class="ser-bg{{$loop->iteration%4==0?'4':$loop->iteration%4}}" style="background: url({{url('uploads/categorias/'.$categoria->imagen)}}) no-repeat center;  background-size: cover;">
                                <div class="p-md-5 p-3">
                                    <h4><a class="libre-baskerville-regular" href="{{url('categorias/'.$categoria->slug)}}">{{$categoria->titulo}}</a></h4>
                                            <p class="libre-baskerville-regular">{{substr(strip_tags($categoria->descripcion), 0, 90).'...'}} </p>
        
                                </div>
                            </div>
                        </div>


                        @endif

                    @endforeach

                    
                </div>
            </div>
        </div>
    </section>
  