@extends('backend.menus.superior')

@section('content-admin-css')

    <!-- Para el select live search -->
    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">
    <!-- Finaliza el select live search -->
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />

 <!-- Para vista detallada --> 

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

 <!-- Para vista detallada fin -->

@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
    .avatar {
        vertical-align: middle;
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
</style>


<div id="divcontenedor" style="display: none">  
    <section class="content-header">
      <div class="container-fluid">
       <div class="row mb-2">
         <div class="col-sm-6">
            <h4> </h4>
           </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active">Vista Buses</li>
                  </ol>
                </div>
        </div>
        <br>
    </section>

    <div class="col-md-12">
        <div class="card card-green">
          <div class="card-header card-header-success">
            <h5 class="card-category-">Vista detallada del bus <span class="badge badge-warning">&nbsp; {{$bus->nom_bus}}&nbsp;</span>&nbsp; </h5>
          </div>
      <!--body-->
        </div>
  
        <div class="row">
    <div class="col-md-4 col-sm-8">

    @if($detectorNull == '0')

    <a href="#" onclick="CrearCalificacionBus({{$bus->id_bus}})" >
        <div class="widget stats-widget">
            <div class="widget-body clearfix bg-info">
                <div class="pull-left">
                    <h3 class="widget-title text-white">Calificación</h3>
                </div>
                <span class="pull-right big-icon watermark"><i class="fas fa-people-arrows"></i>&nbsp;<i class="fas fa-building"></i></span>
            </div>
        </div><!-- .widget -->
    </a>

    @elseif($calificacion->estado_calificacion == 'calificado')
                  <a href="#" onclick="">
                            <div class="widget stats-widget">
                                <div class="widget-body clearfix bg-info">
                                    <div class="pull-left">
                                        <h3 class="widget-title text-white">Calificación realizada &nbsp;{{$calificacion->fecha_calificacion}} </span></h3>
                                    </div>
                                    <span class="pull-right big-icon watermark"><i class="far fa-newspaper"></i> &nbsp; <i class="fas fa-check-double"></i></span>
                                </div>
                            </div><!-- .widget -->
                            </a>
                  @endif

    </div>

    <div class="col-md-4 col-sm-8">
        <a href="#" onclick="cierreytraspasoBus({{$bus->id_bus}})" >
            <div class="widget stats-widget">
                <div class="widget-body clearfix bg-dark">
                    <div class="pull-left">
                        <h3 class="widget-title text-white">Cierres y traspasos</h3>
                    </div>
                    <span class="pull-right big-icon watermark"><i class="fas fa-people-arrows"></i>&nbsp;<i class="fas fa-building"></i></span>
                </div>
            </div><!-- .widget -->
        </a>
    </div>

    <div class="col-md-4 col-sm-8">
        <a href="#" onclick="CobrosBus({{$bus->id_bus}})" >
            <div class="widget stats-widget">
                <div class="widget-body clearfix bg-green">
                    <div class="pull-left">
                        <h3 class="widget-title text-white">Cobros</h3>
                    </div>
                    <span class="pull-right big-icon watermark"><i class="far fa-money-bill-alt"></i>&nbsp;<i class="fas fa-building"></i></span>                   
                </div>
            </div><!-- .widget -->
        </a>
    </div>

</div>
</div>
</div> 
    
<!-- Cuadro para datos del rótulo inicia aquí ----------------------------------------------> 
<!-- seccion frame -->
<section class="content">

<div class="col-sm-12 float-center">
  <div class="container-fluid">
    <form class="form-horizontal" id="form1">
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Reporte datos del Rótulo</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
          </div>
        </div>
        <div class="card-body">
    <!-- sección cargar datos rótulos -->
            <!--Start third-->
                <table class="table table-hover table-striped">
                  <form id="formulario-show">
                    <tbody>
                    
                      <tr>
                        <th>Nombre</th>
                        <td>{{$bus->nom_bus}}</td>
                      </tr>
                     
                      <tr>
                        <th>Empresa</th>
                        <td>{{$bus->empresas}}</td>
                      </tr>

                      <tr>
                        <th>Contribuyente</th>
                        <td>{{$bus->contri}} {{$bus->ape}}</td>
                      </tr>
                      
                      <tr>
                        <th>Fecha apertura</th>
                        <td>{{$bus->fecha_inicio}} </td>
                      </tr>

                      <tr>
                        <th>Placa</th>
                        <td>{{$bus->placa}}</span></td>
                      </tr>

                      <tr>
                        <th>Ruta</th>
                        <td>{{$bus->ruta}}</span></td>
                      </tr>

                      <tr>
                        <th>Teléfono</th>
                        <td>{{$bus->telefono}}</span></td>
                      </tr>

                      <tr>
                        <th>Estado</th>
                        <td>{{$bus->estado_bus}}</span></td>
                      </tr>                      
                     
                    </tbody>
                  </form>
                  </table>
        </div> <!--end third-->
 <!-- Termina sección cargar datos rótulo -->
            <div class="card-footer">
            <button type="button" class="btn btn-default" onclick="VerListaRotulo()" data-dismiss="modal">Volver</button>
                  <button type="button" class="btn btn-success  float-right" onclick="">Imprimir</button>
          </div>
        </div>
      </form>
    </div>
</div>



@extends('backend.menus.footerjs')
@section('archivos-js')
  <!-- Para el select live search -->
    <script src="{{ asset('js/bootstrap-select.min.js') }}" type="text/javascript"></script>
  <!-- Finaliza el select live search -->

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}" type="text/javascript"></script>


    
    <script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });

    function recargar()
    {
     var ruta = "{{ url('/admin/bus/tabla') }}";
     $('#tablaDatatable').load(ruta);
    }
    
   
    function CrearCalificacionBus(id_bus)
    {
      openLoading();
      window.location.href="{{ url('/admin/bus/calificacion') }}/"+id_bus;
    }

    function cierreytraspasoBus(id_bus)
    { 
    window.location.href="{{ url('/admin/buses/cierres_traspasos') }}/"+id_bus;
    }

        
    
    </script>


    <script>

    function VerListaRotulo()
    {
      openLoading();
      window.location.href="{{ url('/admin/bus/Listar') }}/";

    }

    function CobrosBus(id_bus)
    {
      openLoading();

      window.location.href="{{ url('/admin/bus/cobros') }}/"+id_bus;
    }


    
    
    
    

    </script>
@stop