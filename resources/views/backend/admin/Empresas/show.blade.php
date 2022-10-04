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

 <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>



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

<!-- Vista detallada inicia aquí-->

<div id="divcontenedor" style="display: none">  
    <section class="content-header">
      <div class="container-fluid">
       <div class="row mb-2">
         <div class="col-sm-6">

           </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active">Vista empresa</li>
                  </ol>
                </div>
        </div>
        <br>
        </section>
      <div class="col-md-12">
        <div class="card card-info">
          <div class="card-header card-header-success">
            <h5 class="card-category-">
              <i class="fas fa-laptop-house"></i> &nbsp;Vista detallada de la empresa <span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span>&nbsp; 
              @if($estado_de_solvencia==0)
              <img src="{{ asset('/images/solvente3.svg') }}"class="avatar">
              @elseif($estado_de_solvencia==1)
              <img src="{{ asset('/images/mora2.svg') }}" class="avatar">
              @endif
            </h5>
          </div>
      <!--body-->
      <div class="card-body">
      <!-- Cajitas para estadísticas inicia aquí -->
      <section class="content">
           <br><br>
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="m-0 row justify-content-center" >
    
          <div class="col-lg-3 col-8">
            <!-- small box -->
            <div class="small-box bg-default">
              <div class="inner" style="text-align: center;">
                <div class="col-auto">
                  <i class="fas fa-exclamation-circle " style="color:EBEBEB;float:right;font-size: 7vh;"></i>
                </div>
                <p class="font-weight text-primary">
                  Avisos: &nbsp;<span class="badge badge-pill badge-primary">{{$alerta_aviso}}</span>
                </p>
                 
              </div>
              <div class="icon">
                <i class="ion ion-ios-paper"></i>
              </div>
              <a class="small-box-footer"><i class="icon ion-pie-graph"></i></a>
            </div>
          </div>
           <!-- ./col -->
           <div class="col-lg-3 col-8">
            <!-- small box -->
            <div class="small-box bg-default" >
              
              <div class="inner " style="text-align: center;">
                <div class="col-auto">
                  <i class="fas fa-bell" style="color:EBEBEB;float:right;font-size: 7vh;"></i>
                </div>
                <p class="font-weight text-primary">
                  Notificaciones: <span class="badge badge-pill badge-primary">{{$alerta_notificacion}}</span>
                </p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-paper"></i>
              </div>
              <a class="small-box-footer"><i class="icon ion-pie-graph"></i></a>
            </div>
          </div>

        <!-- /.content-wrapper -->
       
      </div>
    </section>
    <!-- Cajitas para estadísticas termina aquí -->
<hr>
<!-- Cajas para Menu aquí -->
    <div class="row">

  
        <div class="col-md-4 col-sm-8">
      @if($pase_recalificacion_mat=='1')
      <a href="#" onclick="CrearRecalificacion({{$empresa->id}} )" >
                      <div class="widget stats-widget">
                          <div class="widget-body clearfix bg-info">
                              <div class="pull-left">
                                  <h3 class="widget-title text-white">Registrar Recalificación</h3>
                              </div>
                              <span class="pull-right big-icon watermark"><i class="fas fa-newspaper"></i>&nbsp;<i class="fas fa-chart-line"></i></span>
                            
                            </div>
                      </div><!-- .widget -->
                      </a>
      @else <!-- Si no hay calificaición de matriculas. -->
          @if($CE==1)
                    <a href="#" onclick="NoCalificarCE()" >
                            <div class="widget stats-widget">
                              <div class="widget-body clearfix bg-light">
                                  <div class="pull-left">
                                      <h3 class="widget-title text-black">No requiere calificación</h3>
                                  </div>
                                  <span class="pull-right big-icon watermark"><i class="fas fa-shield-alt"></i>&nbsp;<i class="fas fa-lock"></i></span>
                              </div>
                          </div><!-- .widget -->
                        </a>
          @else              
              @if($detectorNull== '0')
                        <a href="#" onclick="CrearCalificacion({{$empresa->id}} )" >
                            <div class="widget stats-widget">
                              <div class="widget-body clearfix" style="background-color:#066287; color: #FFFFFF;">
                                  <div class="pull-left">
                                      <h3 class="widget-title text-white">Registrar Calificación</h3>
                                  </div>
                                  <span class="pull-right big-icon watermark"><i class="fas fa-edit"></i>&nbsp;<i class="fas fa-star-half"></i></span>
                              </div>
                          </div><!-- .widget -->
                        </a>
                    @else 
                      
                      <a href="#" onclick="CrearRecalificacion({{$empresa->id}} )" >
                      <div class="widget stats-widget">
                          <div class="widget-body clearfix bg-info">
                              <div class="pull-left">
                                  <h3 class="widget-title text-white">Registrar Recalificación</h3>
                              </div>
                              <span class="pull-right big-icon watermark"><i class="fas fa-newspaper"></i>&nbsp;<i class="fas fa-chart-line"></i></span>
                            
                            </div>
                      </div><!-- .widget -->
                      </a>
              @endif
          @endif 
      @endif <!-- Cierre if de comprobar si hay que recalificar matricula.  -->   
        </div>
        <div class="col-md-4 col-sm-8">
        <a href="#" onclick="cierreytraspaso({{$empresa->id}})" >
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
          @if($CE==0)
                        @if($pase_matriculas==0) 
                              <a href="#" onclick="NoMartriculas()" >
                                      <div class="widget stats-widget">
                                          <div class="widget-body clearfix bg-secondary">
                                              <div class="pull-left">
                                                  <h3 class="widget-title text-white">Matrículas</h3>
                                              </div>
                                              <span class="pull-right big-icon watermark"><i class="fas fa-list-alt"></i>&nbsp; <i class="fas fa-file-signature"></i></span>
                                          </div>
                                      </div><!-- .widget -->
                                  </a>
                                  @else
                                  <a href="#" onclick="matriculas()" >
                                      <div class="widget stats-widget">
                                          <div class="widget-body clearfix bg-warning">
                                              <div class="pull-left">
                                                  <h3 class="widget-title text-black">Matrículas</h3>
                                              </div>
                                              <span class="pull-right big-icon watermark"><i class="fas fa-list-alt"></i>&nbsp; <i class="fas fa-file-signature"></i></span>
                                          </div>
                                      </div><!-- .widget -->
                                  </a>
                                  @endif
            @else
            <a href="#" onclick="NoMartriculas()" >
                  <div class="widget stats-widget">
                      <div class="widget-body clearfix bg-light">
                          <div class="pull-left">
                              <h3 class="widget-title text-black">Matrículas</h3>
                          </div>
                          <span class="pull-right big-icon watermark"><i class="fas fa-file-signature"></i>&nbsp;<i class="fas fa-lock"></i></span>
                      </div>
                  </div><!-- .widget -->
              </a>
              @endif
          </div>
        <div class="col-md-4 col-sm-8">
            @if($NoNotificar==1)
              <a href="#" onclick="Aldia()">
            @else 
              <a href="#" onclick="reporteAviso({{$empresa->id}})">
            @endif
            <div class="widget stats-widget">
                <div class="widget-body clearfix bg-primary">
                    <div class="pull-left">
                        <h3 class="widget-title text-white">Generar aviso</h3>
                    </div>
                    <span class="pull-right big-icon watermark"><i class="fas fa-exclamation-circle"></i></span>
                </div>
            </div><!-- .widget -->
        </a>
    </div>
   
    <div class="col-md-4 col-sm-8">
              @if($CE==0)
                    @if($NoNotificar==1)
                  <a href="#" onclick="Aldia()">
                    @else 
                  <a href="#" onclick="reporte_notificacion({{$empresa->id}})">
                    @endif
                  <div class="widget stats-widget">
                    <div class="widget-body clearfix bg-purple">
                     <div class="pull-left">
                     <h3 class="widget-title text-white">Generar notificación</h3>
                     <input type="hidden" id="fechahoy" value="{{$fechahoy}}" class="form-control" >
                        <input type="hidden" id="f1" value="{{$ultimoCobroEmpresa}}" class="form-control" >
                    </div>
                    <span class="pull-right big-icon watermark"><i class="fas fa-bell"></i></span>
                </div>
              @else
                <a href="#" onclick="NoNotificar()">          
                 <div class="widget stats-widget">
                  <div class="widget-body clearfix bg-light">
                    <div class="pull-left">
                    <h3 class="widget-title text-black">Generar notificación</h3>
                        <input type="hidden" id="fechahoy" value="{{$fechahoy}}" class="form-control" >
                        <input type="hidden" id="f1" value="{{$ultimoCobroEmpresa}}" class="form-control" >
                    </div>
                    <span class="pull-right big-icon watermark"><i class="fas fa-lock"></i></span>
                </div>
                @endif
            </div><!-- .widget -->
      </a>
    </div>
           
    <div class="col-md-4 col-sm-8">
    @if($CE==1)
      <a href="#"  onclick="Cobros({{$empresa->id}})" id="btnCobro">
              <div class="widget stats-widget">
                  <div class="widget-body clearfix bg-success">
                      <div class="pull-left">
                          <h3 class="widget-title text-white">Generar Cobro</h3>
                      </div>
                      <span class="pull-right big-icon watermark"><i class="fas fa-hand-holding-usd"></i></span>
                  </div>
              </div><!-- .widget -->
          </a>
    @else
                  @if($detectorNull== '0')
                      @if($pase_cobro_mat== '1')
                            <a href="#"  onclick="Cobros({{$empresa->id}})" id="btnCobro">
                                <div class="widget stats-widget">
                                    <div class="widget-body clearfix bg-success">
                                        <div class="pull-left">
                                            <h3 class="widget-title text-white">Generar Cobro</h3>
                                        </div>
                                        <span class="pull-right big-icon watermark"><i class="fas fa-hand-holding-usd"></i></span>
                                    </div>
                                </div><!-- .widget -->
                            </a>
                          @else
                            <a href="#"  onclick="NoCobrar()" id="btnmodalCobro">
                            <div class="widget stats-widget">
                                      <div class="widget-body clearfix bg-success">
                                          <div class="pull-left">
                                              <h3 class="widget-title text-white">Generar Cobro</h3>
                                          </div>
                                          <span class="pull-right big-icon watermark"><i class="fas fa-hand-holding-usd"></i></span>
                                      </div>
                                  </div><!-- .widget -->
                              </a>
                          @endif
                  @else
                      <a href="#"  onclick="Cobros({{$empresa->id}})" id="btnCobro">
                          <div class="widget stats-widget">
                              <div class="widget-body clearfix bg-success">
                                  <div class="pull-left">
                                      <h3 class="widget-title text-white">Generar Cobro</h3>
                                  </div>
                                  <span class="pull-right big-icon watermark"><i class="fas fa-hand-holding-usd"></i></span>
                              </div>
                          </div><!-- .widget -->
                      </a>
                  @endif
                      </div>
      @endif
    </div><!-- .ROW -->
    <hr>

<!-- Cuadro para datos de la empresa inicia aquí ----------------------------------------------> 
<!-- seccion frame -->
<section class="content">

  <div class="col-sm-7 float-left">
    <div class="container-fluid">
      <form class="form-horizontal" id="form1">
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Reporte datos de la empresa</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <div class="card-body">
            <!-- sección cargar datos empresa -->
              <!--Start third-->
              
                    <table class="table table-hover table-striped">
                    <form id="formulario-show">
                      <tbody>
                      <tr>
                          <th>
                              <img src="{{ asset('/img/edificio.gif') }}" alt="Avatar" class="avatar">
                          </th>
                          <td >
                              <a href="#">
                                <h6>
                                  @if($estado_de_solvencia==0)
                                  <img src="{{ asset('/images/solvente3.svg') }}"class="avatar">Solvente
                                  @elseif($estado_de_solvencia==1)
                                  <img src="{{ asset('/images/mora2.svg') }}" class="avatar">En mora
                                  @endif
                                </h6>
                              </a>
                          </td>
                        </tr>
                        <tr>
                          <th>Nombre</th>
                          <td >{{$empresa->nombre}}</td>
                        </tr>
                        <tr>
                          <th>Matricula de comercio</th>
                          @if($empresa->matricula_comercio=='')
                          <td>Ninguna</td>
                          @else
                          <td>{{$empresa->matricula_comercio}}</td>
                          @endif
                        </tr>
                        <tr>
                          <th>NIT</th>
                          @if($empresa->nit=='')
                          <td> ----- </td>
                          @else
                          <td> {{$empresa->nit}} </td>
                          @endif
                        </tr>
                        <tr>
                          <th>Tipo de comerciante</th>
                          @if($empresa->tipo_comerciante=='')
                          <td>-----</td>
                          @else
                          <td id="tipo_operaciones-ver">{{$empresa->tipo_comerciante}} </td>
                          @endif
                        </tr>
                        <tr>
                          <th>Inicio de Operaciones</th>
                          <td id="inicio_operaciones-ver"><a href="#" target="_blank"> </a>{{$empresa->inicio_operaciones}}</td>
                        </tr>
                        <tr>
                          <th>Estado</th>
                              @if($empresa->estado == 'Activo')
                              <td> <span class="badge bg-success">Activo</span></td>
                               @elseif($empresa->estado == 'Cerrado')
                              <td> <span class="badge bg-danger">Cerrado</span></td>
                              @else
                              <td> <span class="badge bg-Warning">En Mora</span></td>
                              @endif
                        </tr>
                        <tr>
                          <th>Giro Comercial</th>
                          <td>{{$empresa->nombre_giro}}</span></td>
                        </tr>
                        <tr>
                          <th>Actividad Económica</th>
                          <td>{{$empresa->rubro}}</span></td>
                        </tr>
                        <tr>
                          <th>N° de tarjeta</th>
                          <td id="num_tarjeta-ver">{{$empresa->num_tarjeta}}</td>
                        </tr>
                        <tr>
                          <th>Teléfono</th>
                          <td id="telefono-ver">{{$empresa->telefono}}</td>
                        </tr>
                        <tr>
                          <th>Dirección de la empresa</th>
                          <td id="direccion-ver">{{$empresa->direccion}}</td>
                        </tr>

                      </tbody>
                    </form>
                    </table>
          </div> <!--end third-->

          <!-- Termina sección cargar datos empresa -->
        
          <div class="card-footer">  
           <!-- <button id="btnguardar" type="button"  class="btn btn-success float-left"  onclick="reporteEmpresaDatos({{$empresa->id}})"><i class="fa fa-print"></i>&nbsp;Imprimir</button> -->
          </div>
        </div>
      </form>
    </div>
	</div>

<!-- Cuadro para datos del contribuyente inicia aquí ---------------------------------------------->

  <div class="col-sm-5 float-right">
    <div class="container-fluid">
      <form class="form-horizontal" id="form2">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Reporte datos del contribuyente</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
                 </div>
                 <div class="card-body">
          <!-- sección cargar datos contribuyente -->

          
                        <a href="#">
                          <img src="{{ asset('/img/inversor.png') }}" alt="Avatar" class="avatar">
                          <h5 class="title mt-3"></h5>
                        </a>
                        <p class="description">
                        <span class="badge badge-pill badge-dark">Representante legal:</span><br> {{$empresa->contribuyente}}&nbsp;{{$empresa->apellido}}
                        <br>
                            @if($empresa->registro_comerciante=='')
                              <span class="badge badge-pill badge-dark">Registro Comerciante: </span> <br> Ninguno
                              <br>
                            @else
                              <span class="badge badge-pill badge-dark">Registro Comerciante: </span> <br> {{$empresa->registro_comerciante}}
                              <br>
                            @endif
                        <span class="badge badge-pill badge-dark">Teléfono: </span> <br>  {{$empresa->tel}}
                        <br>
                        <span class="badge badge-pill badge-dark">Dui: </span> <br>  {{$empresa->dui}}
                        <br>
                        <span class="badge badge-pill badge-dark">NIT: </span> <br>  {{$empresa->nitCont}}
                        <br>
                        <span class="badge badge-pill badge-dark">Correo: </span> <br>  {{$empresa->email}}
                        <br>
                            @if($empresa->fax=='')
                              <span class="badge badge-pill badge-dark">Fax: </span> <br> Ninguno
                              <br>
                            @else
                              <span class="badge badge-pill badge-dark">Fax: </span> <br> {{$empresa->fax}}
                              <br>
                            @endif
                        </p>

                    <div class="card-description">
                     <span class="badge badge-pill badge-dark">Dirección: </span> <br>  {{$empresa->direccionCont}}
                    </div>
                  </div><!--Termino ROW -->

    
          <!-- Termina sección cargar datos contribuyente -->
          </div>
         </div>
        </form>
        </div>
	    </div>
      <!-- Card-Footer -->
      <div class="card-footer">
            <button type="button" onclick="ListarEmpresas()" class="btn btn-default"><i class="fas fa-chevron-circle-left"></i> &nbsp;Volver</button>
      </div>
      <!-- /.Card-Footer -->
  </div>
</section>
<!-- /.section -->
<!-- seccion frame -->
<!-- Cuadro para datos del contribuyente termina aquí ------------------------------------------>

              <!-- seccion botón flotante -->
              <div id="contenedor">
                  <input type="checkbox" id="btn-mas">
                          <div class="redes">
                            @if($estado_de_solvencia==0)
                              <a class="fas fa-file-alt" data-toggle="tooltip" data-placement="left" title="Solvencia de Empresa" onclick="Solvencia_empresa('{{$empresa->id}}')"></a>
                            @endif 
                            @if($pase_recalificacion_mat==1 or $detectorNull==1)
                              <a class="fa fa-file-import"  data-toggle="tooltip" data-placement="left" title="Resolución de Apertura" onclick="Imprimir_Resolucion_Apertura('{{$empresa->id}}')"></a>
                            @endif  
                              <a class="fa fa-print" data-toggle="tooltip" data-placement="left" title="Reporte Empresa" onclick="reporteEmpresaDatos('{{$empresa->id}}')"></a>
                            </div>
                  <div class="btn-mas">
                      <label for="btn-mas" class="fa fa-plus"></label>
                  </div>
              </div>
              <!--Fin seccion botón flotante -->

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Termina vista detallada-->




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
function cierreytraspaso(id){
 
  window.location.href="{{ url('/admin/empresas/cierres_traspasos') }}/"+id;
}

function modalRecalificacion(){
            openLoading();
            document.getElementById("formulario-Recalificacion").reset();
            $('#modalRecalificacion').modal('show');
        }


</script>

<script type="text/javascript">

        $(document).ready(function(){
           
            document.getElementById("divcontenedor").style.display = "block";

            //** Tooltips de botón flotante */
            $('[data-toggle="tooltip"]').tooltip();
            

        });

    function recargar()
    {
     var ruta = "{{ url('/admin/empresas/tabla') }}";
     $('#tablaDatatable').load(ruta);
    }

    

</script>
<script>
  
function Solvencia_empresa(id){

window.open("{{ URL::to('/admin/generar/solvencia/empresa/pdf') }}/" + id );

}

function Imprimir_Resolucion_Apertura(id){

window.open("{{ URL::to('/admin/reporte/resolucion_apertura/pdf') }}/" + id );

}

function ListarEmpresas(){
            openLoading();
            window.location.href="{{ url('/admin/nuevo/empresa/listar') }}/";

        }

function CrearCalificacion(id){
  openLoading();
              window.location.href="{{ url('/admin/empresas/calificacion') }}/"+id;
        }

function CrearRecalificacion(id){
  openLoading();
              window.location.href="{{ url('/admin/empresas/recalificacion') }}/"+id;
        }
        
        
function CalificacionCreada(){

toastr.success('La calificación ya fue creada.');
return;
}

function NoCobrar(){
  toastr.warning('Debe registrar una calificación primero para poder generar un cobro.');
  return;
}
function NoCalificarCE(){
  toastr.warning('Esta empresa no es calificable.');
  return;
}
function NoMartriculas(){
  toastr.warning('Las matrículas no están disponibles para esta empresa.');
  return;
}

function NoCalificar(){
  toastr.warning('Debe registrar una calificación primero para poder generar una recalificación.');
  return;
}
function NoNotificar(){
  toastr.warning('Esta empresa no es notificable.');
  return;
}
function Aldia(){
  toastr.warning('Esta empresa se encuentra al día con sus pagos.');
  return;
}
function Cobros(id){
  openLoading();

  window.location.href="{{ url('/admin/empresas/cobros') }}/"+id;
}

function matriculas(){
  var id={{$id}};
  openLoading();
              window.location.href="{{ url('/admin/matriculas_detalle/index') }}/"+id;
        }


function reporteAviso(id){
  Swal.fire({
                title: '¿Realmente desea generar un aviso para esta empresa?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Confirmar',
           
            }).then((result) => {
                if (result.isConfirmed) {
                  location.reload();
                  window.open("{{ URL::to('/admin/generar_aviso/pdf') }}/" + id );
                  Swal.fire('Aviso generado con exito!', '', 'success')
                }
            });


}

function reporte_notificacion(id){
  Swal.fire({
                title: '¿Realmente desea generar una notificación para esta empresa?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Confirmar',
           
            }).then((result) => {
                if (result.isConfirmed) {
                  //location.reload();
                  var f1=(document.getElementById('f1').value);

                  var f2=(document.getElementById('fechahoy').value);

                  var ti="{{$Tasainteres}}";
                  var f3=(document.getElementById('fechahoy').value);
                  var id_giro_comercial="{{$id_giro_comercial}}";

                  if(id_giro_comercial==='1'){
                    //Si es Empresa
                    window.open("{{ URL::to('/admin/generar_notificacion/pdf') }}/" + f1 + "/" + f2 + "/" + ti + "/" + f3 + "/" + id );
                  
                  }else if(id_giro_comercial==='2'){
                    //Si es Sinfonolas   
                    window.open("{{ URL::to('/admin/generar_notificacion/sinfonolas/pdf') }}/" + f1 + "/" + f2 + "/" + ti + "/" + f3 + "/" + id ); 

                  }else if(id_giro_comercial==='3'){
                    //Si es Maquinas Electronicas
                    window.open("{{ URL::to('/admin/generar_notificacion/maquinas/pdf') }}/" + f1 + "/" + f2 + "/" + ti + "/" + f3 + "/" + id ); 
                    
                  }else if(id_giro_comercial==='4'){
                    //Si es Mesas de billar
                    window.open("{{ URL::to('/admin/generar_notificacion/mesas/pdf') }}/" + f1 + "/" + f2 + "/" + ti + "/" + f3 + "/" + id ); 
                    
                  }else if(id_giro_comercial==='5'){
                    //Si es Aparatos Parlantes
                    window.open("{{ URL::to('/admin/generar_notificacion/aparatos/pdf') }}/" + f1 + "/" + f2 + "/" + id ); 
                        
                  }
                  

                  Swal.fire('Notificación generada con exito!', '', 'success')
                }
            });

}

function reporteEmpresaDatos(id){

  window.open("{{ URL::to('/admin/generar_reporte/datos_empresa/pdf') }}/"+ id );


}
function modalMensaje(titulo, mensaje){
            Swal.fire({
                title: titulo,
                text: mensaje,
                icon: 'info',
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {

                }
            });
            
        }


</script>
@stop