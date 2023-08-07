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
<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
    /** CSS para btn flotante */
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
#btn-mas{
    display: none;
}
#contenedor{
    position: fixed;
    bottom: 20px;
    right: 20px;
    float: left;
}
.redes a, .btn-mas label{
    display: block;
    text-decoration: none;
    background: #08BE4D;
    color: #fff;
    width: 55px;
    height: 55px;
    line-height: 55px;
    text-align: center;
    border-radius: 50%;
    box-shadow: 0px 1px 10px rgba(0,0,0,0.4);
    transition: all 500ms ease;
}
.redes a:hover{
    background: #fff;
    color: #C20E0E;
}
.redes a{
    margin-bottom: -15px;
    opacity: 0;
    visibility: hidden;
}
#btn-mas:checked~ .redes a{
    margin-bottom: 10px;
    opacity: 1;
    visibility: visible;
}
.btn-mas label{
    cursor: pointer;
    background: #118EE5; /** Color del botón */
    font-size: 23px;
}
#btn-mas:checked ~ .btn-mas label{
    transform: rotate(135deg);
    font-size: 25px;
}

</style>
<!-- Inicia content-wrapper-->
<div class=" " style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                      <h5 class="modal-title"><i class="fas fa-history"></i>&nbsp;Historial de notificaciones</span>
                      </h5>
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Historial de notificaciones</li>
                        </ol>
                    </div><!-- /.col -->
            </div>
        </div>
    </section>
<!-- finaliza content-wrapper-->

<!--Inicia card-projectcard-project-->
<div class="card card-projectcard-project" style="width: 98%; height:35%; margin: 0 auto; -webkit-border-radius: 5px 5px 5px 5px;border-radius: 5px 5px 5px 5px;">
      <div class="progress" style="margin: 0 auto;width: 100%;height:5px;">
        <div class="progress-bar bg-primary" role="progressbar" style="width: 25%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
  <div class="card-body">

                <div class="row" style="display: flex;justify-content: center;">

                        <div class="col-lg-3 col-6">

                        <div class="small-box bg-info">
                            <div class="inner" style="text-align: center;">
                                <h3><span class="badge badge-pill badge-light">{{$cantidad_avisos}}</span></h3>
                                <p>Historial Avisos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"><i class="fas fa-exclamation-circle "></i></i>
                            </div>
                            <a onclick="cargar_historico_avisos()" class="small-box-footer" style="cursor: pointer">Cargar <i class="fas fa-arrow-circle-down"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6" >

                        <div class="small-box bg-primary">
                            <div class="inner" style="text-align: center;">
                                <h3><span class="badge badge-pill badge-light">{{$cantidad_notificaciones}}</span></h3>
                                    <p>Historial Notificaciones</p>
                             </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"><i class="fas fa-bell"></i></i>
                                </div>
                                <a onclick="cargar_historico_notificaciones()" class="small-box-footer" style="cursor: pointer">Cargar <i class="fas fa-arrow-circle-down"></i></a>
                        </div>
                    </div>
                </div>

  </div>
</div>
 <!-- Finaliza Contenido card-project-->

<!-- Inicia Contenido IMG-->
    <div class="card" style="margin: 5 auto;width: 98%;" id="contenido_historico_notificaciones">
      <div class="progress" style="margin: 0 auto;width: 100%;height:5px;">
        <div class="progress-bar bg-secondary" role="progressbar" style="width:10%; height:100%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
        <div class="card-body"  >
        <!-- Inicia contenido-->
        <!--<img src="{{ asset('/img/notificacion.gif') }}" id="img_notificacion" style="display: block;margin: 0px auto;width: 15%; height:15%;" >-->
        <img src="{{ asset('/img/notificacion.png') }}" id="img_notificacion" style="display: block;margin: 0px auto;width: 25%; height:25%;" >
        <!-- Inclución de tabla -->
            <div class="m-0 row justify-content-center" id="div_historico_avisos">
                    <div class="col-auto  p-5 text-center" id="tablahistoricoavisos"></div>
            </div>
            <!-- Inclución de tabla -->
            <div class="m-0 row justify-content-center" id="div_historico_notificaciones">
                    <div class="col-auto  p-5 text-center" id="tablahistoriconotificaciones"></div>
            </div>


        <!-- Finaliza contenido-->
        </div>
      </div>
    </div>
<!-- Finaliza Contenido IMG-->


<!-- seccion botón flotante -->
<div id="contenedor">
    <input type="checkbox" id="btn-mas">
    <div class="redes">
        <a class="fas fa-file-signature" id="solvencia"  data-toggle="tooltip" data-placement="left" title="Generar solvencia" onclick="Generar_solvencia()"></a>
        <a class="fas fa-file-invoice" id="constancia_simple" data-toggle="tooltip" data-placement="left" title="Generar constancia simple" onclick="Generar_constancia_simple()"></a>
    </div>
    <div class="btn-mas">
        <label for="btn-mas" class="fa fa-plus"></label>
    </div>
</div>
<!--Fin seccion botón flotante -->


<!-- Cerrando el content-wrapper-->
      </div>
      </div>
       </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
<!-- Finaliza Formulario -->
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

 <!-- incluir tabla -->
 <script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
            $('#contenedor').hide();
            //$('#contenido_historico_solvencias').hide();
        });
</script>


<script type="text/javascript">

    function cargar_historico_avisos()
    {
        $('#div_historico_avisos').show();
        $('#div_historico_notificaciones').hide();
        $('#img_notificacion').hide();
        var ruta = "{{ url('/admin/empresa/tabla/historico/avisos') }}";
            $('#tablahistoricoavisos').load(ruta);
    }


    function cargar_historico_notificaciones()
    {
        $('#div_historico_avisos').hide();
        $('#div_historico_notificaciones').show();
        $('#img_notificacion').hide();
        var ruta = "{{ url('/admin/empresa/tabla/historico/notificaciones') }}";
            $('#tablahistoriconotificaciones').load(ruta);
    }

</script>
@stop
