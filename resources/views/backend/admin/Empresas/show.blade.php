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
<script>
function hasta(){

var fecha_pagara=(document.getElementById('fecha_hasta_donde_pagara').value);

hasta_donde_pagara=fecha_pagara;

alert(hasta_donde_pagara);

document.getElementById('hasta').value=hasta_donde_pagara;

}
</script>

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
            <h4> </h4>
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
        <div class="row">
          <div class="col-lg-4 col-8">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3> </h3>
                <p> Avisos: 0</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-paper"></i>
              </div>
              <a class="small-box-footer"><i class="icon ion-pie-graph"></i></a>
            </div>
          </div>
           <!-- ./col -->
           <div class="col-lg-4 col-8">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3> </h3>
                <p>Notificaciones: 0</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-paper"></i>
              </div>
              <a class="small-box-footer"><i class="icon ion-pie-graph"></i></a>
            </div>
          </div>
  
          <div class="col-lg-4 col-8">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3> </h3>
                <p>Multas: 0</p>
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
    <div class="row">

    <div class="col-md-4 col-sm-8">
        <a href="{{url('client')}}">
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
        <a href="{{url('simulator')}}">
            <div class="widget stats-widget">
                <div class="widget-body clearfix bg-purple">
                    <div class="pull-left">
                        <h3 class="widget-title text-white">Generar notificación</h3>
                    </div>
                    <span class="pull-right big-icon watermark"><i class="fas fa-envelope-open-text"></i></span>
                </div>
            </div><!-- .widget -->
        </a>

    </div>
    <div class="col-md-4 col-sm-8">
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
        <div class="col-md-4 col-sm-8">
   

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
                      @elseif($calificaciones->estado_calificacion == 'creado')
                      <a href="#" onclick="CalificacionCreada()" >
                                <div class="widget stats-widget">
                                    <div class="widget-body clearfix bg-dark">
                                        <div class="pull-left">
                                            <h3 class="widget-title text-white">Calificación creada  {{$calificaciones->fecha_calificacion}}</h3>
                                        </div>
                                        <span class="pull-right big-icon watermark"><i class="far fa-newspaper"></i> &nbsp; <i class="fas fa-check-double"></i></span>
                                    </div>
                                </div><!-- .widget -->
                                </a>
                      @endif
 
              @endif
       
        </div>
        <div class="col-md-4 col-sm-8">
        @if($detectorNull== '0')
        <a href="#"  onclick="NoCalificar()" id="btnmodalCalificar">
            <div class="widget stats-widget">
                <div class="widget-body clearfix bg-info">
                    <div class="pull-left">
                        <h3 class="widget-title text-white">Registrar Recalificación</h3>
                    </div>
                    <span class="pull-right big-icon watermark"><i class="fas fa-newspaper"></i>&nbsp;<i class="fas fa-chart-line"></i></span>
                </div>
            </div><!-- .widget -->
        </a>
      @else
        <a href="#" data-toggle="modal" data-target="#modalRecalificacion" >
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
        </div>
        <div class="col-md-4 col-sm-8">
        <a href="#" data-toggle="modal" data-target="#modalCierresTraspasos" >
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
    </div><!-- .ROW -->


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
  </div>
</section>
<!-- /.section -->
<!-- seccion frame -->
<!-- Cuadro para datos del contribuyente termina aquí ------------------------------------------>
<div class="card-footer">
            <button type="button" onclick="ListarEmpresas()" class="btn btn-default">Volver</button>
          </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Termina vista detallada-->

<!--Inicia Modal Cierres y Traspasos--------------------------------------------------------------->

<div class="modal fade" id="modalCierresTraspasos">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Cierre y traspaso de empresa&nbsp;<span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formulario-Calificacion">
            @csrf
              <div class="card-body">
<!-- Inicia Formulario Cierres y Traspasos--> 
<section class="content">
      <div class="container-fluid">
       
      <!-- /.card-header -->
         <div class="card card-green">
            <div class="card-header">
                <h3 class="card-title">FORMULARIO DE CIERRE Y TRASPASO.</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                  </div>
            </div>
        <!-- /.card-header -->


        <!-- Campos del formulario Cierres y Traspasos -->
        <div class="card border-success mb-3"><!-- Panel TRASPASO DE EMPRESA -->
           <div class="card-header text-success"><label>II. TRASPASO DE EMPRESA</label></div>
              <div class="card-body">

                <div class="row"><!-- /.ROW2 -->

                  <!-- /.form-group -->
                  <div class="col-md-6">
                      <div class="form-group">
                            <label>TRASPASO A NOMBRE DE:</label>
                      </div>
                    </div><!-- /.col-md-6 -->
                    <!-- /.form-group -->

                      <div class="col-md-3">
                          <div class="form-group">
                            <!-- Select estado - live search -->
                              <div class="input-group mb-9">
                                    <select 
                                    required
                                    class="selectpicker"
                                    data-style="btn-success"
                                    data-show-subtext="true" 
                                    data-live-search="true"   
                                    id="select-estado_empresa" 
                                    title="-- Seleccione un registro --"
                                    >
                                    @foreach($contribuyentes as $contribuyente)
                                    <option value="{{ $contribuyente->id }}"> {{ $contribuyente->nombre }}&nbsp;{{ $contribuyente->apellido }}</option>
                                    @endforeach
                                    </select>
                              </div>
                            <!-- finaliza select estado-->  
                      </div><!-- /.col-md-3 -->
                    </div><!-- /.form-group -->
                  <!-- /.form-group -->

                </div><!--  /.ROW2 -->

              <!-- /.form-group -->
              <div class="row"><!-- /.ROW3 -->
              <!-- /.form-group -->
              <div class="col-md-6">
                  <div class="form-group">
                       
                    <!-- Botón Imprimir Traspaso-->
                    <br>
                      <button type="button"  onclick="ImpimirTraspaso()" class="btn btn-default btn-sm" ><i class="fa fa-print"></i>
                        &nbsp; Imprimir resolución de traspaso&nbsp;</button>
                      </button>
                    <!-- /.Botón Imprimir Traspaso -->

                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                      <!-- Botón Guardar Traspaso -->
                        <br>
                        <button type="button"  onclick="GuardarTraspaso()" class="btn btn-success btn-sm float-right" ><i class="fa fa-print"></i>
                        &nbsp; Guardar Traspaso &nbsp;</button>
                      <!-- /.Botón Guardar Traspaso -->
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              </div><!-- /.ROW3 -->

          </div><!--  /.card-header text-success -->
        </div> <!-- /.Panel CIERRE DE EMPRESA --> 

     
         <div class="card border-success mb-3"><!-- Panel CIERRE DE EMPRESA -->
           <div class="card-header text-success"><label>II. CIERRE DE EMPRESA</label></div>
              <div class="card-body">

                <div class="row"><!-- /.ROW2 -->

                  <!-- /.form-group -->
                  <div class="col-md-6">
                      <div class="form-group">
                            <label>ESTADO DE LA EMPRESA:</label>
                      </div>
                    </div><!-- /.col-md-6 -->
                    <!-- /.form-group -->

                      <div class="col-md-3">
                          <div class="form-group">
                            <!-- Select estado - live search -->
                              <div class="input-group mb-9">
                                    <select 
                                    required
                                    class="selectpicker"
                                    data-style="btn-success"
                                    data-show-subtext="true" 
                                    data-live-search="true"   
                                    id="select-estado_empresa" 
                                    title="-- Selecione el estado  --"
                                    >
                                      @foreach($estadoempresas as $estado)
                                      <option value="{{ $estado->id }}"> {{ $estado->estado }}</option>
                                      @endforeach 
                                    </select>
                              </div>
                            <!-- finaliza select estado-->  
                      </div><!-- /.col-md-3 -->
                    </div><!-- /.form-group -->
                  <!-- /.form-group -->

                </div><!--  /.ROW2 -->

              <!-- /.form-group -->
              <div class="row"><!-- /.ROW3 -->
              <!-- /.form-group -->
              <div class="col-md-6">
                  <div class="form-group">
                       
                    <!-- Botón Imprimir Cierre -->
                    <br>
                      <button type="button"  onclick="ImpimirCierre()" class="btn btn-default btn-sm" ><i class="fa fa-print"></i>
                        &nbsp; Imprimir resolución de Cierre&nbsp;</button>
                      </button>
                    <!-- /.Botón Imprimir Cierre -->

                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                      <!-- Botón Guardar Traspaso -->
                        <br>
                        <button type="button"  onclick="GuardarCierre()" class="btn btn-success btn-sm float-right" ><i class="fa fa-print"></i>
                        &nbsp; Guardar Cierre &nbsp;</button>
                      <!-- /.Botón Guardar Traspaso -->
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              </div><!-- /.ROW3 -->

          </div><!--  /.card-header text-success -->
        </div> <!-- /.Panel CIERRE DE EMPRESA --> 

  <!-- Finaliza campos del formulario Cierres y Traspasos -->
  
         <!-- /.card-body -->
         <div class="card-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
         <!-- /.card-footer -->

         </div><!-- Card-body -->
        </div><!-- /.card Green-->
      </div><!-- /.container-fluid -->
    </section>

       </form> <!-- /.formulario-Calificacion2 -->
      </div> <!-- /.Card-body -->
   </div> <!-- /.modal-dialog modal-xl -->
  </div> <!-- /.modal-content -->
 </div> <!-- /.modal-body -->
 </div> <!-- /.modalCIerres y traspasos -->

<!-- Finaliza Modal Cierres y Traspasos--------------------------------------------------------->




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

function modalRecalificacion(){
            openLoading();
            document.getElementById("formulario-Recalificacion").reset();
            $('#modalRecalificacion').modal('show');
        }

function  modalCierresTraspasos(){
            openLoading();
            document.getElementById("formulario- modalCierresTraspasos").reset();
            $('#modalCierresTraspasos').modal('show');
        }

</script>

<script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });

</script>
<script>

function ListarEmpresas(){
            openLoading();
            window.location.href="{{ url('/admin/nuevo/empresa/listar') }}/";

        }

function CrearCalificacion(id){
              window.location.href="{{ url('/admin/empresas/calificacion') }}/"+id;
        }
        
function CalificacionCreada(){

toastr.success('La calificación ya fue creada.');
return;
}

function NoCobrar(){
  toastr.warning('Debe registrar una calificación primero para poder generar un cobro.');
  return;
}
function NoCalificar(){
  toastr.warning('Debe registrar una calificación primero para poder generar una recalificación.');
  return;
}

function Cobros(id){
  openLoading();

  window.location.href="{{ url('/admin/empresas/cobros') }}/"+id;
}

</script>
@stop