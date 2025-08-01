<div class="card card_border py-2 mb-2">
    <div class="cards__heading mb-0 ">
        <h3>Cliente <span></span></h3>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col">
                <p>Nombres</p>
                <h4>{{$cliente->nombre}}</h4>

                <p>Apellidos</p>
                <h4>{{$cliente->apellido}}</h4>

                <p>Cedula de Identidad</p>
                <h4>{{$cliente->tipo_documento.$cliente->documento}}</h4>

                <p>fecha de nacimiento</p>
                <h4>{{$cliente->fecha_nacimiento}}</h4>

                <p>Saldo Disponible</p>
                <h4>{{$cliente->saldo_cliente}}$</h4>
            </div>
            <div class="col text-center">
                @if($cliente->archivo!==NULL)

                    <img 
                    src=" {{url('uploads/clientes/'.$cliente->id.'/'.$cliente->archivo)}}" 
                    alt="{{$cliente->nombre}}"
                    class="rounded-circle ">
                @else
                    <img 
                    src="https://ui-avatars.com/api/?name={{ $cliente->nombre.' '.$cliente->apellido }}&background=0D8ABC&color=fff&size=128
                    " 
                    class="rounded-circle " 
                    alt="{{$cliente->nombre.' '.$cliente->apellido}}" />
                @endif
               <button class="btn btn-primary updatePhoto"> Cambiar Foto </button>

            </div>
        </div>

        <!-- div class="row">
            <div class="col">
                <p>Enlace de acceso directo Cliente:</p>
                <p>https://app.Migtours.com.ve/token/{{$cliente->user->token}}</p>
            </div>
        </!-->

        <div class="row">
            @if($cliente->facebook)
            <div class="col">
                <a target="_blank" href="https://facebook.com/{{$cliente->facebook}}">
                    <img src="{{url('/images/facebook.jpg')}}" alt=""></a>
            </div>
            @endif

            @if($cliente->twitter)
            <div class="col">
                <a target="_blank" href="https://twitter.com/{{$cliente->twitter}}">
                <img src="{{url('/images/twitter.jpg')}}" alt=""></a>
            </div>
            @endif

            @if($cliente->instagram)
            <div class="col">
                <a target="_blank" href="https://instagram.com/{{$cliente->instagram}}">
                    <img src="{{url('/images/instagram.jpg')}}" alt=""></a>
            </div>
            @endif

            @if($cliente->tiktok)
            <div class="col">
                <a target="_blank" href="https://tiktok.com/{{'@'.$cliente->tiktok}}">
                    <img src="{{url('/images/tiktok.jpg')}}" alt=""></a>
            </div>
            @endif
        </div>


    </div>
    <div class="card-footer bg-transparent ">

        <a
        href="{{route('admin.clientes.contract', $cliente->id)}}"
        class="btn btn-success mt-2"><i class="fa fa-document"> Crear Contrato</i> </a>

        <a
            href="{{route('admin.abonos.add', $cliente->id)}}"
            class="btn btn-dark mt-2"><i class="fa fa-document"> Agregar Abono</i> </a>

        
        
        <a
        href="{{route('admin.clientes.index')}}"
        class="btn btn-primary mt-2"> <i class="fa fa-back"> Volver al Listado</i> </a>
    </div>
</div>