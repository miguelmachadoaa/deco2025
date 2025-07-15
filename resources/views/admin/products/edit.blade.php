@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<link rel="stylesheet" href="{{url('css/uploadfile.css')}}">
<link href="{{url('/Trumbowyg/src/ui/trumbowyg.css')}}" rel="stylesheet">

<style>
    /* Estilo base para los botones de color */
#colorOptions .form-check label {
  border: 2px solid transparent;
  transition: border 0.2s ease;
}

/* Estilo cuando el checkbox está seleccionado */
#colorOptions .form-check-input:checked + label {
  border: 2px solid #333; /* Puedes cambiar el color del borde */
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
}
</style>


@endsection

@section('content')

  <div class="container-fluid revisar">
    <div class="row">
      <div class="col">

        
        <div class="card card_border py-2 mb-4">
          <div class="cards__heading">
              <h3>Editar Producto <span></span></h3>
          </div>
          <div class="card-body">


                 
            <form action="{{route('admin.products.update')}}" method="post" id="formproducts" class="">

                @csrf

                <input type="hidden" id="id" name="id" value="{{$producto->id}}">
                <input type="hidden" id="id_product" name="id_product" value="{{$producto->id}}">
                
                <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label input__label">Titulo</label>
                      <div class="col-sm-10">
                          <input
                          required
                          type="text"
                          class="form-control input-style"
                          id="titulo"
                          name="titulo"
                          value="{{$producto->titulo}}"
                          placeholder="Titulo">
                      </div>
                </div>


                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label input__label">Precio</label>
                    <div class="col-sm-10">
                        <input required type="number" step="0.01" min="0" class="form-control input-style" id="precio" name="precio" value="{{$producto->precio}}" placeholder="Precio">
                    </div>
                </div>

                
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label input__label">Descripcion</label>
                    <div class="col-sm-10">
                        <textarea
                        required
                        class="form-control"
                        id="descripcion"
                        name="descripcion"
                        rows="3">{{$producto->descripcion}}</textarea>
                        
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label input__label">Caracteristicas</label>
                    <div class="col-sm-10">
                        <textarea
                        
                        class="form-control"
                        id="caracteristicas"
                        name="caracteristicas"
                        rows="3">{{$producto->caracteristicas}}</textarea>
                        
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label input__label">Condiciones</label>
                    <div class="col-sm-10">
                        <textarea
                        
                        class="form-control"
                        id="condiciones"
                        name="condiciones"
                        rows="3">{{$producto->condiciones}}</textarea>
                        
                    </div>
                </div>

              


                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label input__label">Marca</label>
                    <div class="col-sm-10">
                        <input
                        required
                        type="text"
                        class="form-control input-style" 
                        id="marca"
                        name="marca"
                        value="{{$producto->marca}}" 
                        placeholder="Marca">
                    </div>
                </div>


                <div class="form-group row mb-4">

                    <label for="inputEmail3" class="col-sm-2 col-form-label input__label">Categorias </label>

                    <div class="col-sm-10 p-0 m-0">

                        @foreach ($categorias  as $c)

                        <div class=" form-check">
                            <input
                            class="form-check-input"
                            type="checkbox"
                            @if(in_array($c->id, $categorias_poroducto ))
                            checked
                            @endif
                            value="{{$c->id}}"
                            id="categoria_{{$c->id}}"
                            name="categoria_{{$c->id}}"
                            >
                            <label class="form-check-label" for="flexCheckDefault">
                                {{$c->titulo}}
                            </label>
                        </div>

                    @endforeach

                    </div>

                </div>



                <div class="form-group row">
                    <label class="col-sm-2 col-form-label input__label">Colores</label>
                    <div class="col-sm-5">
                        <div id="colorOptions" class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($colores as $color)
                            <div class="form-check">
                                <input 
                                class="form-check-input d-none" 
                                type="checkbox" 
                                value="{{ $color->id }}" 
                                id="color_{{ $color->id }}" 
                                name="colors[]"
                                {{ in_array($color->id, old('colores', $producto->colores->pluck('id')->toArray())) ? 'checked' : '' }}
    
                                >
                                <label class="btn btn-light border rounded-circle" for="color_{{ $color->id }}" title="{{ $color->nombre }}" alt="{{ $color->nombre }}"  style="background-color: {{ $color->hex }}; width: 40px; height: 40px;" ></label>
                            </div>
                        @endforeach
                        </div>

                        
                    </div>
                    <div class="mb-2 mt-2 col-sm-5" style="padding: 2em;
                            background: #eee;
                            border-radius: 1em;">
                            <h6 class="col-sm-12 mb-2">Si desea prueba agregar un nuevo color </h6>
                            <input type="text" class="form-control mb-2 " id="newColorName" placeholder="Nombre del color">
                            <input type="color" class="form-control mb-2 " id="newColorHex" placeholder="#FFFFFF">
                            <button type="button" class="btn btn-primary " onclick="addColorOption()">Agregar nuevo color</button>
                        </div>
                </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label input__label">Materiales</label>
                        <div class="col-sm-5">
                            <div id="materialOptions" class="mb-3">
                            @foreach($materiales as $m)
                                <div class="form-check mb-2">
                                <input 
                                class="form-check-input" 
                                type="checkbox" 
                                value="{{ $m->id }}" 
                                id="material_{{ $m->id }}" 
                                name="materials[]"
                                {{ in_array($m->id, old('materiales', $producto->materiales->pluck('id')->toArray())) ? 'checked' : '' }}
   
   
                                >
                                <label class="form-check-label" for="material_{{ $m->id }}">
                                    <strong>{{ $m->nombre }}</strong><br>
                                    <small>{{ $m->descripcion }}</small>
                                    <small>{{ $m->codigo }}</small>
                                </label>
                                </div>
                            @endforeach
                            </div>

                            
                        </div>
                        <div class="border p-3 rounded bg-light col-sm-5">
                                <h6>Agregar nuevo material</h6>
                                <input type="text" class="form-control mb-2" id="newMaterialCodigo" placeholder="Codigo del material">
                                <input type="text" class="form-control mb-2" id="newMaterialTitulo" placeholder="Título del material">
                                <textarea class="form-control mb-2" id="newMaterialDescripcion" rows="2" placeholder="Descripción"></textarea>
                                <button type="button" class="btn btn-primary" onclick="addMaterialOption()">Agregar material</button>
                            </div>
                    </div>









                <div class="form-group row">
                    <div class="col-sm-2">Cargar Imagenes</div>
                    <div class="col-sm-10">
                        
                        <div id="fileuploader">Upload</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">Imagenes Cargadas</div>
                    <div class="col-sm-10">
                        <div id="listimagenes">
                            @include('admin.products.imagenes')
                            
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label input__label">Disponible</label>
                    <div class="col-sm-10">
                        <select required
                        class="form-select form-control"
                        id="estatus" name="estatus"
                        aria-label="Default select example">
                            <option selected>Seleccione</option>
                            <option @if($producto->estatus=="1") Selected @endif value="1">Si</option>
                            <option @if($producto->estatus=="0") Selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>

                  <div class="form-group row">
                      <div class="col-sm-10">
                          <button type="submit" class="btn btn-primary btn-style">Actualizar</button>
                          <a href="{{url('admin/products')}}" class="btn btn-info btn-style">Volver</a>
                      </div>
                  </div>
              </form>

           
          </div>
      </div>

      </div>
    </div>
  </div>

@endsection

@section('js')

<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="{{url('/js/jquery.uploadfile.min.js')}}"></script>
<script src="{{url('/Trumbowyg/src/trumbowyg.js')}}"></script>
<script src="{{url('/Trumbowyg/src/plugins/base64/trumbowyg.base64.js')}}"></script>
<script>

 function addMaterialOption() {
  const nombre = document.getElementById('newMaterialTitulo').value.trim();
  const descripcion = document.getElementById('newMaterialDescripcion').value.trim();
  const codigo = document.getElementById('newMaterialCodigo').value.trim();

  if (!titulo || !descripcion || !codigo) {
    alert('Por favor completa el título y la descripción.');
    return;
  }

  fetch("{{ route('materials.ajaxStore') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ nombre, descripcion, codigo })
  })
  .then(res => res.json())
  .then(data => {
    const id = 'material_' + data.id;
    const container = document.getElementById('materialOptions');

    const wrapper = document.createElement('div');
    wrapper.className = 'form-check mb-2';
    wrapper.innerHTML = `
      <input class="form-check-input" type="checkbox" value="${data.id}" id="${id}" name="materials[]" checked>
      <label class="form-check-label" for="${id}">
        <strong>${data.nombre}</strong><br>
        <small>${data.descripcion}</small>
        <small>${data.codigo}</small>
      </label>
    `;
    container.appendChild(wrapper);

    document.getElementById('newMaterialTitulo').value = '';
    document.getElementById('newMaterialDescripcion').value = '';
    document.getElementById('newMaterialCodigo').value = '';
  })
  .catch(err => console.error('Error al guardar el material:', err));
}

function addColorOption() {
  const nombre = document.getElementById('newColorName').value.trim();
  const hex = document.getElementById('newColorHex').value.trim();

  if (!nombre || !/^#[0-9A-Fa-f]{6}$/.test(hex)) {
    alert('Nombre o HEX inválido');
    return;
  }

  fetch("{{ route('colors.ajaxStore') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ nombre, hex })
  })
  .then(res => res.json())
  .then(data => {
    const id = 'color_' + data.id;
    const container = document.getElementById('colorOptions');

    const wrapper = document.createElement('div');
    wrapper.className = 'form-check';
    wrapper.innerHTML = `
      <input class="form-check-input d-none" type="checkbox" value="${data.id}" id="${id}" name="colors[]" checked>
      <label class="btn btn-light border rounded-circle" for="${id}"  title="${data.nombre}" alt="${data.nombre}" style="background-color: ${data.hex}; width: 40px; height: 40px;"></label>
    `;
    container.appendChild(wrapper);

    document.getElementById('newColorName').value = '';
    document.getElementById('newColorHex').value = '';
  })
  .catch(err => console.error('Error al guardar el color:', err));
}

$(document).ready(function()
{

    $('#descripcion')
    .trumbowyg({
    btnsDef: {
        // Create a new dropdown
        image: {
            dropdown: ['insertImage', 'base64'],
            ico: 'insertImage'
        }
    },
        // Redefine the button pane
        btns: [
            ['viewHTML'],
            ['formatting'],
            ['strong', 'em', 'del'],
            ['superscript', 'subscript'],
            ['link'],
            ['image'], // Our fresh created dropdown
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat'],
            ['fullscreen']
        ]
    });


    var id = $('#id_product').val();

	$("#fileuploader").uploadFile({
	    url:"{{url('/admin/products/uploadimg/')}}/"+id,
	    fileName:"myfile",
        onSubmit:function(files)
        {
            $("#listimagenes").html("<br/>Submitting:"+JSON.stringify(files));
            //return false;
        },
        onSuccess:function(files,data,xhr,pd)
        {

            $("#listimagenes").html(data);
            
        }
	});
});

</script>

@endsection