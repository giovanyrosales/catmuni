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
                  <li class="breadcrumb-item active">Vista Buses</li>
                  </ol>
                </div>
        </div>
        <br>
    </section>

    <div class="col-md-12">
        <div class="card card-green">
          <div class="card-header card-header-success">
    
          <h5 class="card-category-">RÓTULOS <span class="badge badge-warning"></span>&nbsp; </h5>
      
          </div>
      <!--body-->
        </div>

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
                  Avisos: &nbsp;<span class="badge badge-pill badge-primary"></span>
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
                  Notificaciones: <span class="badge badge-pill badge-primary"></span>
                </p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-paper"></i>
              </div>
              <a class="small-box-footer"><i class="icon ion-pie-graph"></i></a>
            </div>
          </div>
        </div>
      </div>
      </section>
        </div>
  
        <div class="row">
    <div class="col-md-4 col-sm-8">

    @if($detectorNull == '0')
        <a href="#" onclick="CrearCalificacion({{$rotulos->id_rotulos_detalle}})" >
                      <div class="widget stats-widget">
                        <div class="widget-body clearfix bg-info">
                            <div class="pull-left">
                                <h3 class="widget-title text-white">Realizar calificación</h3>
                            </div>
                            <span class="pull-right big-icon watermark"><i class="fas fa-people-arrows"></i>&nbsp;<i class="fas fa-star-half"></i></span>
                        </div>
                    </div><!-- .widget -->
        </a>
     
      @elseif ($calificacionRotulos->estado_calificacion == 'calificado')
        <a href="#" onclick="">
            <div class="widget stats-widget">
               <div class="widget-body clearfix bg-info">
                  <div class="pull-left">
                         <h3 class="widget-title text-white">Calificación realizada &nbsp; {{$calificacionRotulos->fecha_calificacion}}

                         </span></h3>
                  </div>
                  <span class="pull-right big-icon watermark"><i class="far fa-newspaper"></i> &nbsp; <i class="fas fa-check-double"></i></span>
                </div>
            </div><!-- .widget -->
        </a>
      @endif
  
    </div>
   
    <div class="col-md-4 col-sm-8">
        <a href="#" onclick="cierreytraspasoBus()" >
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

  
  @if($detectorNull == '0')

    <div class="col-md-4 col-sm-8">
      <a href="#"  onclick="NoCobrar()" id="btnmodalCobro">
            <div class="widget stats-widget">
                <div class="widget-body clearfix bg-green">
                    <div class="pull-left">
                        <h3 class="widget-title text-white">Registrar Cobro</h3>
                    </div>
                    <span class="pull-right big-icon watermark"><i class="far fa-money-bill-alt"></i>&nbsp;<i class="fas fa-building"></i></span>
                </div>
            </div><!-- .widget -->
        </a>
    </div>
       
  @else
    
    <div class="col-md-4 col-sm-8">
        <a href="#" onclick="CobrosB( )" >
            <div class="widget stats-widget">
                <div class="widget-body clearfix bg-green">
                    <div class="pull-left">
                        <h3 class="widget-title text-white">Registrar Cobro</h3>
                    </div>
                    <span class="pull-right big-icon watermark"><i class="far fa-money-bill-alt"></i>&nbsp;<i class="fas fa-building"></i></span>                   
                </div>
            </div><!-- .widget -->
        </a>       
    </div>  

  @endif
  
    <div class="col-md-4 col-sm-8">

        <a href="#" onclick="Aldia()">      
 
        <a href="#" onclick="reporteeAviso()">
                   
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
             
          <a href="#" onclick="Aldia()">
                  
          <a href="#" onclick="reporte_notificacion_bus()">
                 
              <div class="widget stats-widget">
                <div class="widget-body clearfix bg-purple">
                  <div class="pull-left">
                     <h3 class="widget-title text-white">Generar notificación</h3>
                     <input type="hidden" id="fechahoy" value="" class="form-control" >
                        <input type="hidden" id="f1" value="" class="form-control" >
                    </div>
                    <span class="pull-right big-icon watermark"><i class="fas fa-bell"></i></span>
              </div>
          </a>
          </a>
    </div>
    
</div>
</div> 
    
<!-- Cuadro para datos del rótulo inicia aquí ----------------------------------------------> 
<!-- seccion frame -->
<section class="content">

<div class="col-sm-12 float-center">
  <div class="container-fluid">
    <form class="form-horizontal" id="form1">
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Reporte de rótulos</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
          </div>
        </div>
        <div class="card-body">
    <!-- sección cargar datos rótulos -->
            <!--Start third-->
                <table class="table table-hover table-striped">
                  <form id="formulario-show">
                    <tbody>
                    
                      <tr>
                        <th>Número de ficha </th>
                        <td>{{$rotulos->num_ficha}}</td>
                      </tr>

                      <tr>
                        <th>Fecha de apertura </th>
                        <td>{{$rotulos->fecha_apertura}}</td>
                      </tr>

                      <tr>
                        <th>Cantidad de rótulos </th>
                        <td>{{$rotulos->cantidad_rotulos}}</td>
                      </tr>

                      <tr>
                        <th>Contribuyente </th>
                        <td>{{$rotulos->contribuyente}} {{$rotulos->apellido}}</td>
                      </tr>
                                 
                      <tr>
                        <th>Nombre de la empresa</th>
                        <td>{{$rotulos->nom_empresa}}</td>
                      </tr>
                      
                      <tr>
                        <th>Dirección de la empresa</th>
                        <td>{{$rotulos->dire_empresa}}</td>
                      </tr>
           
                      <tr>
                        <th>NIT empresa</th>
                        <td>{{$rotulos->nit_empresa}}</td>
                      </tr>

                      <tr>
                        <th>Teléfono </th>
                        <td>{{$rotulos->tel_empresa}}</td>
                      </tr>

                      <tr>
                        <th>Registro de comerciante</th>
                        <td>{{$rotulos->reg_comerciante}}</td>
                      </tr>
                                            
                    </tbody>
                  </form>
                  </table>
        </div> <!--end third-->
 <!-- Termina sección cargar datos rótulo -->
            <div class="card-footer">
            <button type="button" class="btn btn-default" onclick="VerListaRotulo()" data-dismiss="modal">Volver</button>
                 
             
          </div>        
  </section> 
              <!-- seccion botón flotante -->
              <div id="contenedor">
                              <input type="checkbox" id="btn-mas">
                          <div class="redes">
                         
                              <a class="fas fa-file-alt" data-toggle="tooltip" data-placement="left" title="Solvencia de Empresa" onclick="Solvencia_empresa()"></a>
                          
                              <a class="fa fa-file-import"  data-toggle="tooltip" data-placement="left" title="Resolución de Apertura" onclick="Imprimir_Resolucion_Apertura()"></a>
                        
                              <a class="fa fa-print" data-toggle="tooltip" data-placement="left" title="Reporte Rotulos" onclick="reporteRotulosDatos({{$rotulos->id_rotulos_detalle}})"></a>
                            </div>
                  <div class="btn-mas">
                      <label for="btn-mas" class="fa fa-plus"></label>
                  </div>
              </div>
              <!--Fin seccion botón flotante -->
        </div>
      </form>
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
    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}" type="text/javascript"></script>


    
    <script type="text/javascript">
        $(document).ready(function(){
         // var id_rotulos_detalle = {{$id_rotulos_detalle}};
            document.getElementById("divcontenedor").style.display = "block";

                  //** Tooltips de botón flotante */
          $('[data-toggle="tooltip"]').tooltip();
        });

    
    function recargar()
    {

        var ruta = "{{ url('/admin/Rotulos/tabla') }}";
        $('#tablaDatatable').load(ruta);

    }
    
    function cierreytraspasoBus(id)
    { 

        window.location.href="{{ url('/admin/buses/cierres_traspasosB') }}/"+id;

    }


    function CrearCalificacion(id_rotulos_detalle)
    {
        //console.log(id_rotulos_detalle)

      openLoading();
      window.location.href="{{ url('/admin/rotulos_detalle/calificacion') }}/"+id_rotulos_detalle;
      
    }
        
    
    </script>


    <script>

    function VerListaRotulo()
    {

      openLoading();
      window.location.href="{{ url('/admin/Rotulos/Listar') }}/";

    }

    
    function InspeccionRealizada()
    {
        toast.success('La inspeccion ya fue realizada');
        return;
    }

    function Aldia()
    {
      toastr.warning('Este bus se encuentra al día con sus pagos.');
      return;
    }
    
    function CobrosB(id)
    {
        openLoading();

        window.location.href="{{ url('/admin/buses/cobros') }}/"+id;
    }

    function NoCobrar()
    {

      toastr.warning('Debe registrar una calificación primero para poder generar un cobro.');
      return;
      
    }

    function NoNotificar()
    {
      toastr.warning('Esta empresa no es notificable.');
      return;
    }

    function reporteeAviso(id)
    {
        Swal.fire({
                title: '¿Realmente desea generar un aviso para este contribuyente?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Confirmar',
           
            }).then((result) => {
                if (result.isConfirmed) {
                  location.reload();
                  window.open("{{ URL::to('/admin/generar_aviso/buses/pdf') }}/" + id );
                  Swal.fire('Aviso generado con exito!', '', 'success')
                }
            });
          
    }

    function reporte_notificacion_bus(id)
    {
      
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

                  var ti="";
                
                  var f3=(document.getElementById('fechahoy').value);
                 
                    //Si es Empresa
                    window.open("{{ URL::to('/admin/generar_notificacion_bus/pdf') }}/" + f1 + "/" + f2 + "/" + ti  + "/" + id+ "/" + f3 );
                  
                    Swal.fire('Notificación generada con exito!', '', 'success')
               console.log(id,);
       
              }
              });

          }

          function reporteRotulosDatos(id)
          {

              window.open("{{ URL::to('/admin/generar/reporte/rotulos/pdf') }}/"+ id );

          }
    </script>

    <style>
      #contenedor
      {
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
      *{
          margin: 0;
          padding: 0;
          box-sizing: border-box;
      }
      #btn-mas{
          display: none;
      }
      </style>
@stop