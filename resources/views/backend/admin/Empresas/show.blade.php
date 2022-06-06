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
        <div class="card card-green">
          <div class="card-header card-header-success">
            <h5 class="card-category-">Vista detallada de la empresa <span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span>&nbsp; </h5>
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
            <div class="small-box bg-info">
              <div class="inner">
                <h3> </h3>
                <p> Avisos: <span class="badge badge-pill badge-light">{{$alerta_aviso}}</span></p>
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
            <div class="small-box bg-warning">
              <div class="inner">
                <h3> </h3>
                <p>Notificaciones: <span class="badge badge-pill badge-light">{{$alerta_notificacion}}</span></p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-paper"></i>
              </div>
              <a class="small-box-footer"><i class="icon ion-pie-graph"></i></a>
            </div>
          </div>
  
          <div class="col-lg-3 col-8">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3> </h3>
                <p>Multas por balance: <span class="badge badge-pill badge-light">{{$Cantidad_multas}}</span></p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-paper"></i>
              </div>
              <a class="small-box-footer"><i class="icon ion-pie-graph"></i></a>
            </div>
          </div>
        <!-- /.row -->
        <!-- /.content-wrapper -->
      </div>
    </section>
    <!-- Cajitas para estadísticas termina aquí -->
<hr>
<!-- Cajas para Menu aquí -->
    <div class="row">

  
        <div class="col-md-4 col-sm-8">
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
                              <div class="widget-body clearfix bg-secondary">
                                  <div class="pull-left">
                                      <h3 class="widget-title text-white">Registrar Calificación</h3>
                                  </div>
                                  <span class="pull-right big-icon watermark"><i class="fas fa-edit"></i>&nbsp;<i class="fas fa-star-half"></i></span>
                              </div>
                          </div><!-- .widget -->
                        </a>
                    @else 
                      @if($calificaciones->estado_calificacion == '')
                        <a href="#" onclick="CrearCalificacion({{$empresa->id}} )" >
                            <div class="widget stats-widget">
                              <div class="widget-body clearfix bg-dark">
                                  <div class="pull-left">
                                      <h3 class="widget-title text-white">Registrar Calificación</h3>
                                  </div>
                                  <span class="pull-right big-icon watermark"><i class="fas fa-edit"></i>&nbsp;<i class="fas fa-star-half"></i></span>
                              </div>
                          </div><!-- .widget -->
                          </a>
                      @elseif($calificaciones->estado_calificacion == 'calificado')
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
          @endif      
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
          <a href="#" onclick="reporteAviso({{$empresa->id}})">
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
                <a href="#" onclick="reporte_notificacion({{$empresa->id}})">
                  <div class="widget stats-widget">
                    <div class="widget-body clearfix bg-purple">
                     <div class="pull-left">
                     <h3 class="widget-title text-white">Generar notificación</h3>
                     <input type="hidden" id="fechahoy" value="{{$fechahoy}}" class="form-control" >
                        <input type="hidden" id="f1" value="{{$ultimoCobroEmpresa}}" class="form-control" >
                    </div>
                    <span class="pull-right big-icon watermark"><i class="fas fa-envelope-open-text"></i></span>
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
                          <h3 class="widget-title text-white">Registrar Cobro</h3>
                      </div>
                      <span class="pull-right big-icon watermark"><i class="far fa-money-bill-alt"></i></span>
                  </div>
              </div><!-- .widget -->
          </a>
    @else
                  @if($detectorNull== '0')
                  <a href="#"  onclick="NoCobrar()" id="btnmodalCobro">
                  <div class="widget stats-widget">
                              <div class="widget-body clearfix bg-success">
                                  <div class="pull-left">
                                      <h3 class="widget-title text-white">Registrar Cobro</h3>
                                  </div>
                                  <span class="pull-right big-icon watermark"><i class="far fa-money-bill-alt"></i></span>
                              </div>
                          </div><!-- .widget -->
                      </a>
                  @else
                      <a href="#"  onclick="Cobros({{$empresa->id}})" id="btnCobro">
                          <div class="widget stats-widget">
                              <div class="widget-body clearfix bg-success">
                                  <div class="pull-left">
                                      <h3 class="widget-title text-white">Registrar Cobro</h3>
                                  </div>
                                  <span class="pull-right big-icon watermark"><i class="far fa-money-bill-alt"></i></span>
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
                          <th>Nombre</th>
                          <td >{{$empresa->nombre}}</td>
                        </tr>
                        <tr>
                          <th>Matricula de comercio</th>
                          <td>{{$empresa->matricula_comercio}}</td>
                        </tr>
                        <tr>
                          <th>NIT</th>
                          <td> {{$empresa->nit}} </span></td>
                        </tr>
                        <tr>
                          <th>Tipo de comerciante</th>
                          <td id="tipo_operaciones-ver">{{$empresa->tipo_comerciante}} </td>
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
                          <th>Actividad Específica</th>
                          <td>{{$empresa->nom_actividad_especifica}}</span></td>
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
            <button id="btnguardar" type="button"  class="btn btn-success float-right"  onclick="ListarEmpresas()"><i class="fa fa-print"></i>&nbsp;Imprimir</button>
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
                          <img src="{{ asset('/img/avatar4.png') }}" alt="Avatar" class="avatar">
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
                    <div class="card-footer">
                     <button id="btnguardar" type="button"  class="btn btn-success float-right"  onclick="ListarEmpresas()"><i class="fa fa-print"></i>&nbsp;Imprimir</button>
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

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Termina vista detallada-->


<!--Inicia Modal Recalificacion--------------------------------------------------------------------->

<div class="modal fade" id="modalRecalificacion">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Registrar cobro a empresa&nbsp;<span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formulario-Recalificacion">
              <div class="card-body">

  <!-- Inicia Formulario Recalificación--> 
   <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->

        <form class="form-horizontal" id="formulario-Recalificacion">
        @csrf

          <div class="card card-green">
            <div class="card-header">
            <h3 class="card-title">Datos generales.</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <!-- Campos del formulario de recalificación -->
          <div class="card-body"><!-- Card-body -->
            <div class="row"><!-- /.ROW1 -->
            
             <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Número de tarjeta:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="form-group">
                        <input type="number"  value="{{ $empresa->num_tarjeta }}" name="num_tarjeta" disabled id="num_tarjeta" class="form-control" required >
                        <input type="hidden" name="id" id="id-editar" class="form-control" >
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>Fecha de último pago:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                  @if($detectorCobro=='0')
                        <input  type="text" disabled  name="ultimo_cobro" class="form-control" required >
                        <input type="hidden" name="id" id="id-editar" class="form-control text-success" >
                  @else
                              <input  type="text" value="{{ $ultimo_cobro->fecha_pago }}" disabled  name="ultimo_cobro" class="form-control text-success" required >
                              <input type="hidden" name="id" id="id-editar" class="form-control" >
                  @endif
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Fecha hasta donde pagará:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input  type="date" name="nombre" id="nombre-editar" class="form-control" required >
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>Giro Comercial:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select Giro Comercial -->
               <div class="col-md-6">
                      <div class="form-group">
                            <!-- Select Giro Comercial -live search -->
                                <div class="input-group mb-9">
                                <select 
                                required 
                                disabled
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"  
                                id="select-giro_comercial-editar" 
                                required
                                >
                                  @foreach($giroscomerciales as $giro)
                                  <option value="{{ $giro->id }}"> {{ $giro->nombre_giro }}
                                  </option>
                                  @endforeach 
                                </select> 
                                </div>
                          </div>
                  </div>
              <!-- finaliza select Giro Comercial-->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Tasa de interes:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="form-group">
                        <input type="text" name="nombre" id="nombre-editar" class="form-control" >
                        <input type="hidden" name="id" id="id-editar" class="form-control" >
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>Fecha del interes moratorio:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input type="date" name="nombre" id="nombre-editar" class="form-control" >
                        <input type="hidden" name="id" id="id-editar" class="form-control" >
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Cantidad de meses a pagar:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="form-group">
                        <input type="number" value="cant_meses" name="cant_meses" id="cant_meses" class="form-control" >
                        <input type="hidden" name="id" id="id-editar" class="form-control" >
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
              
            </div> <!-- /.ROW1 -->
          <!-- /.col1 -->
          </div> <!-- /.Card-body -->

        <!-- Finaliza campos del formulario Recalificacion -->
         <!-- /.card-body -->
          <div class="card-footer">
            <button type="button" class="btn btn-success float-right" onclick="RegistrarCobro()"><i class="far fa-money-bill-alt"></i>&nbsp;Registrar Cobro&nbsp;</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
         <!-- /.card-footer -->
         </div>
        </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>

     </form> <!-- /.formulario-Recalificacion -->
    </div> <!-- /.Card-body -->
   </div> <!-- /.modalRecalificacion -->
  </div> <!-- /.modal-dialog modal-xl -->
 </div> <!-- /.modal-content -->
</div> <!-- /.modal-body -->

<!-- Finaliza Modal Recalificacion------------------------------------------------------------------>




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
        });

    function recargar()
    {
     var ruta = "{{ url('/admin/empresas/tabla') }}";
     $('#tablaDatatable').load(ruta);
    }

    

</script>
<script>

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
    
   

  window.open("{{ URL::to('/admin/generar_aviso/pdf') }}/" + id );

}


function reporte_notificacion(id){
    
    var f1=(document.getElementById('f1').value);

    var f2=(document.getElementById('fechahoy').value);

    var ti={{$Tasainteres}};
    var f3=(document.getElementById('fechahoy').value);


  window.open("{{ URL::to('/admin/generar_notificacion/pdf') }}/" + f1 + "/" + f2 + "/" + ti + "/" + f3 + "/" + id );

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