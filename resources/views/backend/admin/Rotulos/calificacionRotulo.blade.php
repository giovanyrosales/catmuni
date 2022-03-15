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
                            <li class="breadcrumb-item active">Calificación rótulo</li>
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

        <div class="card card-success">
          <div class="card-header">
          <h5 class="modal-title">Realizar calificación a rótulo <span class="badge badge-warning">&nbsp; {{$rotulo->nom_rotulo}}&nbsp;</span>&nbsp;</h5>

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
            <div class="card-header text-info"><label>I.REGISTRO DE CUENTAS CORRIENTES</label></div>
                <div class="card-body"><!-- Card-body -->
        
            <div class="row"><!-- /.ROW1 -->

             <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>NÚMERO DE FICHA:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date" value=" " name="fecha_inspeccion"  id="fecha_inspeccion" class="form-control" required >
                    <input type="hidden" name="estado_inspeccion" id="estado_inspeccion" class="form-control" value="realizado">
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

            
            
            <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
            <div class="card-header text-info"><label>II. DATOS GENERALES DEL RÓTULO</label></div>
            <div class="card-body"><!-- Card-body -->
            <div class="row"><!-- /.ROW1 -->

              <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>NOMBRE:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date" value=" " name="fecha_inspeccion"  id="fecha_inspeccion" class="form-control" required >
                    <input type="hidden" name="estado_inspeccion" id="estado_inspeccion" class="form-control" value="realizado">
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

              <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>GIRO ECONÓMICO:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date" value=" " name="fecha_inspeccion"  id="fecha_inspeccion" class="form-control" required >
                    <input type="hidden" name="estado_inspeccion" id="estado_inspeccion" class="form-control" value="realizado">
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->
        
            <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>fECHA INICIO DE OPERACIONES:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date" value=" " name="fecha_inspeccion"  id="fecha_inspeccion" class="form-control" required >
                    <input type="hidden" name="estado_inspeccion" id="estado_inspeccion" class="form-control" value="realizado">
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

              <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>DIRECCIÓN:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date" value=" " name="fecha_inspeccion"  id="fecha_inspeccion" class="form-control" required >
                    <input type="hidden" name="estado_inspeccion" id="estado_inspeccion" class="form-control" value="realizado">
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

              <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>REPRESENTANTE LEGAL:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date" value=" " name="fecha_inspeccion"  id="fecha_inspeccion" class="form-control" required >
                    <input type="hidden" name="estado_inspeccion" id="estado_inspeccion" class="form-control" value="realizado">
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->
            
              <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>RAZÓN SOCIAL:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date" value=" " name="fecha_inspeccion"  id="fecha_inspeccion" class="form-control" required >
                    <input type="hidden" name="estado_inspeccion" id="estado_inspeccion" class="form-control" value="realizado">
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->


            <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
            <div class="card-header text-info"><label>III. DESCRIPCIÓN DE RÓTULOS O VALLAS PUBLICITARIAS</label></div>
            <div class="card-body"><!-- Card-body -->
            <div class="row"><!-- /.ROW1 -->

