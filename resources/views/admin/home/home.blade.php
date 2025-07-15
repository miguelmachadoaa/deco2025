@extends('layouts.app')

@section('content')

  <div class="container-fluid revisar">
    
    <div class="row ">
      <div class="col">

        <div class="statistics">
          <div class="row">
            <div class="col-xl-6 pr-xl-2">
              <div class="row">
                <div class="col-sm-6 pr-sm-2 statistics-grid">
                  <a href="{{route('admin.destinos.index')}}">
                  <div class="card card_border border-primary-top p-4">
                    <i class="lnr lnr-earth"> </i>
                    <h3 class="text-primary number">{{$destinos}}</h3>
                    <p class="stat-text">Nro. de Destinos</p>
                  </div>
                  </a>
                </div>
                <div class="col-sm-6 pl-sm-2 statistics-grid">
                  <a href="{{route('admin.paquetes.index')}}">
                  <div class="card card_border border-primary-top p-4">
                    <i class="lnr lnr-map"> </i>
                    <h3 class="text-secondary number">{{$paquetes}} </h3>
                    <p class="stat-text">Total de Paquetes $</p>
                  </div>
                  </a>
                </div>
              </div>
            </div>
            <div class="col-xl-6 pl-xl-2">
              <div class="row">
                <div class="col-sm-6 pr-sm-2 statistics-grid">
                  <a href="{{route('admin.promociones.index')}}">
                  <div class="card card_border border-primary-top p-4">
                    <i class="lnr lnr-map-marker"> </i>
                    <h3 class="text-success number">{{$promociones}} </h3>
                    <p class="stat-text">Cantidad de promociones</p>
                  </div>
                  </a>
                </div>
                <div class="col-sm-6 pl-sm-2 statistics-grid">
                  <a href="{{route('admin.blogs.index')}}">
                  <div class="card card_border border-primary-top p-4">
                    <i class="lnr lnr-pencil"> </i>
                    <h3 class="text-danger number">{{$blogs}} </h3>
                    <p class="stat-text">Cantidad de blogs</p>
                  </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>

@endsection
