@extends('backend.menus.superior')


@section('content-admin-css')



  <!-- Para el select live search -->
    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">
  <!--Finaliza el select live search -->

    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/main.css') }}" type="text/css" rel="stylesheet" />




@stop


<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Inspección rótulo</li>
                        </ol>
                    </div><!-- /.col -->
            </div>
        </div>
    </section>
<!-- finaliza content-wrapper-->

<!-- Inicia Formulario Crear Empresa-->
<section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->

        <form class="form-horizontal" id="formulario-GenerarRecalificacion">
        @csrf

        <div class="card card-info">
          <div class="card-header">
          <h5 class="modal-title">Realizar inspección a rótulo <span class="badge badge-warning">&nbsp; {{$rotulo->nom_rotulo}}&nbsp;</span>&nbsp;</h5>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          
          <div class="card-body">

<!-------------------------CONTENIDO (CAMPOS) ----------------------------------------------->


        <!-- Campos del formulario de inspección -->
        <div class="card border-success mb-3"><!-- Panel Datos generales de la empresa -->
            <div class="card-header text-info"><label>I.DATOS DE LA INSPECCIÓN</label></div>
                <div class="card-body"><!-- Card-body -->
        
            <div class="row"><!-- /.ROW1 -->

             <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>FECHA DE INSPECCIÓN:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date"  value=" " name="fecha_inspeccion"  id="fecha_inspeccion" class="form-control" required >
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

            <div class="col-md-3">
                <div class="form-group">
                    <label>HORA:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="time"  value=" " name="hora_inspeccion"  id="hora_inspeccion" class="form-control" required >
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

            <!-- /.form-group -->
            <div class="col-md-3">
                <div class="form-group">
                    <label>NOMBRE DEL RÓTULO:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Nombre de Rótulo -->
            <div class="col-md-3">
                <div class="form-group">  
                   <input type="text"  value="{{$rotulo->nom_rotulo}}" name="nom_rotulo" disabled id="nom_rotulo" class="form-control" required >
                   <input type="hidden"  value="{{ $rotulo->id }}" name="nom_rotulo" disabled id="nom_rotulo" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Nombre del Rótulo-->
            <!-- /.form-group -->

             <!-- /.form-group -->
            <div class="col-md-3">
                <div class="form-group">
                    <label>ACTIVIDAD ECONÓMICA:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Actividad Económica -->
            <div class="col-md-3">
                <div class="form-group">  
                   <input type="text"  value="{{$rotulo->actividad_economica}}" name="actividad_economica" disabled id="actividad_economica" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Actividad Económica-->
            <!-- /.form-group -->

            <!-- /.form-group -->
            <div class="col-md-3">
                <div class="form-group">
                    <label>DIRECCIÓN:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Dirección -->
            <div class="col-md-3">
                <div class="form-group">  
                   <input type="text"  value="{{$rotulo->direccion}}" name="direccion" disabled id="direccion" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Dirección-->
            <!-- /.form-group -->

            <!-- /.form-group -->
            <div class="col-md-3">
                <div class="form-group">
                    <label>FECHA DE APERTURA:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha Apertura -->
            <div class="col-md-3">
                <div class="form-group">  
                   <input type="date"  value="{{$rotulo->fecha_apertura}}" name="fecha_apertura" disabled id="fecha_apertura" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Fecha Apertura-->
            <!-- /.form-group -->

            <!-- /.form-group -->
            <div class="col-md-3">
                <div class="form-group">
                    <label>REPRESENTANTE LEGAL:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Representante Legal -->
            <div class="col-md-3">
                <div class="form-group">  
                   <input type="text"  value="{{$rotulo->contribuyente}}&nbsp;{{$rotulo->apellido}}" name="contribuyente" disabled id="contribuyente" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Representante Legal-->
            <!-- /.form-group -->

             <!-- /.form-group -->
             <div class="col-md-3">
                <div class="form-group">
                    <label>EMPRESA:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-3">
                <div class="form-group">  
                   <input type="text"  value="{{$rotulo->empresas}}" name="empresa" disabled id="empresa" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->
            </div>
                </div>
            </div>

        <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
        <div class="card-header text-info"><label>II. DATOS GENERALES</label></div>
        <div class="card-body"><!-- Card-body -->
        <div class="row"><!-- /.ROW1 -->

        <div class="row">
            <!-- /.form-group -->
            <div class="col-md-6">
                <div class="form-group">
                    <p>En esta fecha se realizó la inspección para apertura de:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                   <input type="text"  value="{{$rotulo->nom_rotulo}}" disabled name="empresa" id="empresa" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

            <!-- /.form-group -->
            <div class="col-md-6">
                <div class="form-group">
                    <p>que es propiedad de:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                   <input type="text"  value="{{$rotulo->empresas}}" disabled name="empresa" id="empresa" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

             <!-- /.form-group -->
             <div class="col-md-6">
                <div class="form-group">
                    <p>el rótulo posee las siguiente medidas:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                <input type="text" style="WIDTH: 485px; HEIGHT: 70px; text-align:right" value="{{$rotulo->medidas}}" disabled name="medidas" id="medidas" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

            <!-- /.form-group -->
            <div class="col-md-12">
                <div class="form-group">
                    <p>por lo que se procede a realizar la inscripcióny se anexa copia de Documentación Personal del Representante Legal</p>
                </div>
            </div><!-- /.col-md-6 -->

            <!-- /.form-group -->
            <div class="col-md-6">
                <div class="form-group">
                    <p>Coordenadas Geodésicas:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                   <input type="text"  value="" name="coordenadas" id="coordenadas" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

             <!-- /.form-group -->
            <div class="col-md-6">
                <div class="form-group">
                    <p>se anexa foto del rótulo</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                   <input type="file"  value="" name="empresa" id="empresa" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->
          
        
            </div>
          </div>
        </div>
       </div>
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
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/jquery.simpleaccordion.js') }}"></script>



    <script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });


       
    </script>


@stop