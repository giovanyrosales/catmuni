@extends('backend.menus.superior')

@section('content-admin-css')
    <!-- Para el select live search -->
    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">
    <!-- Finaliza el select live search -->
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
@stop
<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
</style>

<!-- Contenido Frame Principal -->
<div id="divcontenedor" style="display: none">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Lista de detalles de actividad económica.</h1>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Detalle de Actividad Económica</li>
                            </ol>
                        </div>
        </div>
        <br>
        <button type="button"onclick="agregarDetalles()" class="btn btn-success btn-sm" >
                <i class="fas fa-pencil-alt"></i>
                Nueva tarifa fija
            </button>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <!-- CAJA -->
        <form class="form-horizontal" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Tarifa Fija</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
            <div class="col-md-6">
            </div>
            </div>
            <!-- /.col -->
            <div id="tablaDatatable"></div>
            </div>
            </div>
          <!-- /.row -->
          </div>
         <!-- /.card-body -->
        <div class="card-footer">          
        </div>
        <!-- /.card-footer -->
         </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
</div>
<!--Termina Contenido Frame Principal -->


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


    <!-- incluir tabla -->
 <script type="text/javascript">
        $(document).ready(function(){
            var ruta = "{{ url('/admin/TarifaFija/tabla') }}";
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";
        });
</script>

<script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });

    function recargar()
    {
     var ruta = "{{ url('/admin/TarifaFija/tabla') }}";
     $('#tablaDatatable').load(ruta);
    }


</script>
@stop