<div class="row">
    @foreach($imagenes as $i)
        <div class="col-sm-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Imagen {{$loop->iteration}}</h5>
                </div>
                <img style="width: 240px;     height: 240px;" class="card-img-top " src="{{url('uploads/productos/'.$i->imagen)}}" alt="Imagen del producto">
                <div class="card-body">
                
                    @if($i->principal)
                        <a href="{{url('admin/productos/imagenes/setprincipal/'.$i->id)}}" class="btn btn-secondary btn-sm float-left">Desmarcar Principal</a>
                    @else
                        <a href="{{url('admin/productos/imagenes/setprincipal/'.$i->id)}}" class="btn btn-success btn-sm float-left">Marcar Principal</a>
                    @endif
                
                        <a href="{{url('admin/products/'.$i->id.'/delimagenes')}}" class="btn btn-danger btn-sm float-right"><i class="fa fa-trash"></i></a>
                    
                    
                </div>
            
            </div>
        
        </div>
    @endforeach
</div>