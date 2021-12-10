@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/responsive.bootstrap4.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons.bootstrap4.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
@stop

<!-- Formulario-->

<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Agregar nuevo Contribuyente</li>
                        </ol>
                    </div><!-- /.col -->
            </div>
        </div>
    </section>

<!-- finaliza content-wrapper-->

<!-- Inicia Formulario Contribuyente-->
<section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <form class="form-horizontal" id="form1">
        <div class="card card-blue">
          <div class="card-header">
            <h3 class="card-title">Formulario de datos del propietario.</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
                    <!-- /.card-header -->
                    <div class="card-body">
            <div class="row">
              <div class="col-md-6">
              <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required placeholder="Nombre del propietario" value="">
                        <input type="hidden" name="id" id="id" class="form-control" value="">
                      </div>
                <!-- /.form-group -->
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                          <label>NIT:</label>
                          <input type="text" name="nit" id="nit" class="form-control" required placeholder="0000-000000-000-0" value="">
                  </div></div>
                <div class="col-md-6">
                  <div class="form-group">
                          <label>DUI:</label>
                          <input type="text" name="dui" id="dui" required placeholder="00000000-0" class="form-control" value="">
                </div>
                </div>
                </div>
                <!-- /.form-group -->
                <div class="form-group">
                    <label>Dirección:</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Dirección de la empresa"  value="">
                  </div>
                <!-- /.form-group --> 
                <div class="form-group">
                    <label>Correo Electrónico:</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="Correo@dominio.com"  value="">
                  </div>
                <!-- /.form-group -->  
              </div>
<!-- Inicia Segunda Columna de campos-->
              <!-- /.col -->
              <div class="col-md-5">
              <div class="form-group">
                        <label>Apellido:</label>
                        <input type="text" name="apellido" id="apellido" class="form-control" required placeholder="Apellido del propietario" value="">
                      </div>
                <!-- /.form-group -->
                <div class="form-group">
                <label>Registro de Comerciante:</label>
                          <input type="text" name="fecha" id="fecha" required class="form-control" placeholder="00000"  value="">
                      </div>
                <!-- /.form-group -->
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Teléfono:</label>
                          <input type="text" name="telefono" id="telefono" class="form-control" required placeholder="7777-7777" value="">
                  </div></div>
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Fax:</label>
                          <input type="text" name="fax" id="fax" required placeholder="0000" class="form-control" value="">
                </div>
                </div>

                <!-- /.form-group -->  
                <div class="form-group">
                <br>
                
                </div></div>
            <!-- /.col -->
            </div>
          <!-- /.row -->
          </div>
         <!-- /.card-body -->
         <div class="card-footer">
                  <button type="button" class="btn btn-Primary float-right" onclick="actualizarRegistro();"> Guardar </button>
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">Cancelar</button>
                </div>
         <!-- /.card-footer -->
         </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
<!-- Finaliza Formulario Contribuyente-->





<!--Final del formulario -->

@extends('backend.menus.footerjs')
@section('archivos-js')

<script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>


@stop