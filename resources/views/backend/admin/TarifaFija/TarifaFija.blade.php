@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/responsive.bootstrap4.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons.bootstrap4.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" type="text/css" rel="stylesheet" />

    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">

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
                            <li class="breadcrumb-item active">Agregar tarifa fija</li>
                        </ol>
                    </div><!-- /.col -->
            </div>
        </div>
    </section>

<!-- Inicia Formulario Tarifa Fija-->
<section class="content">
     <div class="container-fluid" style="margin-left: 10px">
        <form class="col-md-10" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Tarifas fijas.</h3>

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
                      <label>Actividad económica:</label>
                      <input type="number" name="nombre_actividad" id="nombre_actividad" class="form-control" required placeholder="Actividad económica">
                      <input type="hidden" name="id" id="id" class="form-control" >
                </div>
              </div>
                 
                <div class="col-md-6">
                  <div class="form-group">
                   <label>Limite inferior:</label>
                   <input type="number" name="limite_inferior" id="limite_inferior" class="form-control" required placeholder="limite_inferior" >
                  </div>
                </div>
            </div>     
                
            <div class="row">
                <div class="col-md-6">
                     <div class="form-group">
                      <label>Limite superior:</label>
                         <input type="number" name="limite_superior" id="limite_superior" required placeholder="Limite superior" class="form-control" >
                    </div>
                     </div> 
                                   
                   <div class="col-md-3">
                     <div class="form-group">
                    <label>Impuesto mensual:</label>
                    <input type="number" name="impuesto_mensual" id="impuesto_mensual" class="form-control" placeholder="Impuesto mensual"  >
                  </div>
                 </div>
            </div>
            
                      </div>
                           <!-- finaliza select Asignar Representante-->
                   </div>
            
            <div class="form-group">
              <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="nuevaAct()"> Guardar </button>
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">Cancelar</button>
                </div>
                </div></div>
            </div>
           <!-- /.col -->
            </div>
          </div>
        </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>  
    <!-- /.container-fluid -->
    </section>
<!-- Finaliza Formulario Tarifa Fija-->

@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script src="{{ asset('js/bootstrap-select.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/jquery.simpleaccordion.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

@stop    