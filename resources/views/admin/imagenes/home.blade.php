@extends('layouts.app')

@section('css')

@endsection

@section('content')

  <div class="container-fluid revisar">
    <div class="row">
      <div class="col">


        

        <div class="welcome-msg pt-3 pb-4">
          <h1>Listado de destinos</h1>
          <a class="btn btn-primary" href="{{route('admin.destinos.add')}}">Crear</a>
        </div>

        <div class="data-tables">
          <div class="row">
            <div class="col-lg-12 mb-4">
              <div class="card card_border p-4">
                <div class="table-responsive">
                  <div id="example_wrapper" class="dataTables_wrapper no-footer">
                    <table descriptio="" id="example" class="table table-striped" style="width:100%">
                      <thead>
                          <tr>
                              <th>Id</th>
                              <th>Valor</th>
                              <th>Fecha</th>
                              <th>Fecha Creado</th>
                              <th>Acciones</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($destinos as $destino)

                            <tr>
                                <td>{{$destino->id}}</td>
                                <td>{{$destino->titulo}}</td>
                                <td>{{$destino->imagen}}</td>
                              
                                <td>{{$destino->created_at}}</td>
                                <td>
                                  <a
                                  href= "{{url('admin/destinos/'.$destino->id."/edit")}}"
                                  class="btn btn-primary">
                                    <i class="fa fa-pencil"></i>
                                  </a>
                                  
                                </td>
                            </tr>

                          @endforeach
                         
                      </tbody>
                      <tfoot>
                          <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Imagen</th>
                            <th>Fecha Creado</th>
                            <th>Acciones</th>
                          </tr>
                      </tfoot>
                  </table>
                 
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>

@endsection


@section('js')

<script>

  $(document).ready(function(){
    $('#example').DataTable();
  })
</script>

@endsection