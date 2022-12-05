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
<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                      <h5 class="modal-title"><i class="fas fa-history"></i>&nbsp;Histórico Solvencias.</span>
                      </h5>
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Histórico solvencias.</li>
                        </ol>
                    </div><!-- /.col -->
            </div>
        </div>
    </section>
<!-- finaliza content-wrapper-->

<!--Inicia card-projectcard-project-->
<div class="card card-projectcard-project" style="width: 98%; height:35%; margin: 0 auto; -webkit-border-radius: 5px 5px 5px 5px;border-radius: 5px 5px 5px 5px;">
      <div class="progress" style="margin: 0 auto;width: 100%;height:5px;">
        <div class="progress-bar bg-warning" role="progressbar" style="width: 25%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
  <div class="card-body">

                <div class="row" style="display: flex;justify-content: center;">
                  
                        <div class="col-lg-3 col-6">
            
                        <div class="small-box bg-warning">
                            <div class="inner" style="text-align: center;">
                                <h3></h3>
                                <p><b>Histórico Solvencias Simples</b></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"><i class="fas fa-file-contract"></i></i>
                            </div>
                            <a onclick="cargar_historico_cs()" class="small-box-footer" style="cursor: pointer">Cargar <i class="fas fa-arrow-circle-down"></i></a>
                            </div>
                        </div>
            
                        <div class="col-lg-3 col-6" >
            
                        <div class="small-box bg-success">
                            <div class="inner" style="text-align: center;">
                                <h3></h3>
                                    <p><b>Histórico Solvencias Global</b></p>
                             </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"><i class="fas fa-file-invoice"></i></i>
                                </div>
                                <a onclick="cargar_historico_cg()" class="small-box-footer" style="cursor: pointer">Cargar <i class="fas fa-arrow-circle-down"></i></a>
                        </div>
                    </div>
                </div>

  </div>
</div>
 <!-- Finaliza Contenido card-project-->

<!-- Inicia Contenido IMG-->
    <div class="card" style="margin: 5 auto;width: 98%;" id="contenido_historico_solvencias">
      <div class="progress" style="margin: 0 auto;width: 100%;height:5px;">
        <div class="progress-bar bg-secondary" role="progressbar" style="width:10%; height:100%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
        <div class="card-body"  >
        <!-- Inicia contenido--> 

        <img src="{{ asset('/img/constancias_historial.png') }}" id="img_solvente" style="display: block;margin: 0px auto;width: 30%; height:30%;" > 
            <!-- Inclución de tabla -->
            <div class="m-0 row justify-content-center" id="div_historico_cs">
                    <div class="col-auto  p-5 text-center" id="tablahistoricocs"></div>
            </div>
            <!-- Inclución de tabla -->
            <div class="m-0 row justify-content-center" id="div_historico_cg">
                    <div class="col-auto  p-5 text-center" id="tablahistoricocg"></div>
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

    function cargar_historico_cs()
    { 
        $('#div_historico_cs').show();
        $('#div_historico_cg').hide();
        $('#img_solvente').hide();
        var ruta = "{{ url('/admin/contribuyentes/tabla/historicocs') }}";
            $('#tablahistoricocs').load(ruta);
    }

    
    function cargar_historico_cg()
    {   
        $('#div_historico_cs').hide();
        $('#div_historico_cg').show();
        $('#img_solvente').hide();
        var ruta = "{{ url('/admin/contribuyentes/tabla/historicocg') }}";
            $('#tablahistoricocg').load(ruta);
    }

</script>
@stop