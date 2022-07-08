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
                  <li class="breadcrumb-item active">Vista rótulos</li>
                  </ol>
                </div>
        </div>
        <br>
    </section>

    <div class="col-md-12">
        <div class="card card-green">
          <div class="card-header card-header-success">
            <h5 class="card-category-">Vista detallada del rótulo <span class="badge badge-warning">&nbsp; {{$lista->nom_rotulo}}&nbsp;</span>&nbsp; </h5>
          </div>
      <!--body-->
        </div>
  
        <div class="row">
    <div class="col-md-4 col-sm-8">

    @if($detectorNull== '0')             
             <a href="#" onclick="CrearCalificacion({{$lista->id}} )" >
                 <div class="widget stats-widget">
                   <div class="widget-body clearfix bg-info">
                       <div class="pull-left">
                           <h3 class="widget-title text-white">Realizar calificación</h3>
                       </div>
                       <span class="pull-right big-icon watermark"><i class="fas fa-people-arrows"></i>&nbsp;<i class="fas fa-star-half"></i></span>
                   </div>
               </div><!-- .widget -->
             </a>

   @else 

           @if($calificacion->estado_calificacion == '')

        <a href="#" onclick="CrearCalificacion({{$lista->id}})" >
            <div class="widget stats-widget">
                <div class="widget-body clearfix bg-info">
                    <div class="pull-left">
                        <h3 class="widget-title text-white">Calificación</h3>
                    </div>
                    <span class="pull-right big-icon watermark"><i class="fas fa-people-arrows"></i>&nbsp;<i class="fas fa-building"></i></span>
                </div>
            </div><!-- .widget -->
        </a>

        @elseif($calificacion->estado_calificacion == 'calificado')
                      <a href="#" onclick="">
                                <div class="widget stats-widget">
                                    <div class="widget-body clearfix bg-info">
                                        <div class="pull-left">
                                            <h3 class="widget-title text-white">Calificación realizada &nbsp;{{$calificacion->fecha_calificacion}} </span></h3>
                                        </div>
                                        <span class="pull-right big-icon watermark"><i class="far fa-newspaper"></i> &nbsp; <i class="fas fa-check-double"></i></span>
                                    </div>
                                </div><!-- .widget -->
                                </a>
                      @endif
 
              @endif
    </div>
   
    <div class="col-md-4 col-sm-8">
        <a href="#" onclick="cierreytraspaso({{$lista->id}})" >
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

    @if($detectorNull== '0')
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
        <a href="#" onclick="CobrosR({{$lista->id}})" >
            <div class="widget stats-widget">
                <div class="widget-body clearfix bg-green">
                    <div class="pull-left">
                        <h3 class="widget-title text-white">Cobros</h3>
                    </div>
                    <span class="pull-right big-icon watermark"><i class="far fa-money-bill-alt"></i>&nbsp;<i class="fas fa-building"></i></span>                   
                </div>
            </div><!-- .widget -->
        </a>
        @endif
    </div>

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
          <h3 class="card-title">Reporte datos del Rótulo</h3>
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
                        <th>Nombre</th>
                        <td >{{$lista->nom_rotulo}}</td>
                      </tr>

                      <tr>
                        <th>Actividad económica</th>
                        <td>{{$lista->actividad_economica}}</td>
                      </tr>

                      <tr>
                        <th>Empresa</th>
                        <td>{{$lista->empresas}}</td>
                      </tr>

                      <tr>
                        <th>Contribuyente</th>
                        <td>{{$lista->contribuyente}}</td>
                      </tr>
                      
                      <xtr>
                        <th>Fecha apertura</th>
                        <td>{{$lista->fecha_apertura}} </td>
                      </tr>

                      <tr>
                        <th>Permiso Instalación</th>
                        <td>{{$lista->permiso_instalacion}}</span></td>
                      </tr>

                      <tr>
                        <th>Dirección del rótulo</th>
                        <td>{{$lista->direccion}}</span></td>
                      </tr>

                      <tr>
                        <th>Estado</th>
                        <td>{{$lista->medidas}}</span></td>
                      </tr>

                      <tr>
                        <th>Total Medidas</th>
                        <td>{{$lista->total_medidas}}m²</span></td>
                      </tr>

                      <tr>
                        <th>Caras del rótulo</th>
                        <td>{{$lista->total_caras}}</span></td>
                      </tr>

                      <tr>
                        <th>Coordenadas</th>
                        <td>{{$lista->coordenadas}}</span></td>
                      </tr>

                      <tr>
                        <th>Inspección realizada por</th>
                        <td>{{$lista->nom_inspeccion}}</span></td>
                      </tr>

                      <tr>
                        <th>Cargo</th>
                        <td>{{$lista->cargo_inspeccion}}</span></td>
                      </tr>

                      <tr>
                        <th>Imagen</th>
                        <td><img src="{{ asset('archivos/' .$lista->imagen) }}"/></span></td>
                      </tr>

                    </tbody>
                  </form>
                  </table>
        </div> <!--end third-->
 <!-- Termina sección cargar datos rótulo -->
            <div class="card-footer">
            <button type="button" class="btn btn-default" onclick="VerListaRotulo()" data-dismiss="modal">Volver</button>
                  <button type="button" class="btn btn-success  float-right" onclick="">Imprimir</button>
          </div>
        </div>
      </form>
    </div>
</div>

<!-- Modal cierre de rótulo -->
<div class="modal fade" id="modalCierreRotulos">
    <div class = "modal-dialog modal-xl">
        <div class = "modal-content">
            <div class = "modal-header">
              <h5 class="modal title">Cierre de rótulos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class = "modal-body">
                <form id = "formulario-CierreRotulo">
                @csrf
                  <div class="card-body">
                 <!-- Inicia Formulario Cierre -->
                    <section class="content">
                      <div class = "container-fluid">
                        <div class = "card card-green">
                          <div class = "card-header">
                            <h3 class = "card-title">FORMULARIO CIERRE DE RÓTULOS</h3>

                            <div class = "card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                            </div>
                            </div> 

                            <div class = "card border-success mb-3">
                              <div class = "card-header text-success"><label>I. CIERRE DE RÓTULO</label></div>
                              <div class = "card-body">

                                <div class = "row">
                                  <div class = "col-md-6">
                                    <div class = "form-group">
                                      <label>Nombre del Rótulo: <span class="badge badge"> {{$lista->nom_rotulo}}&nbsp;</span></label>
                                      <label>Dirección del rótulo: <span class="badge badge"> {{$lista->direccion}}&nbsp;</span>&nbsp;</label>
                                      
                                     </div>
                                   </div>
                                </div>
                                     
                            <div class = "row">
                                <div class="col-md-6">
                                   <div class="form-group">
                                   <label>ESTADO DE LA EMPRESA:</label>
                                  
                                   </div>
                                </div><!-- /.col-md-6 -->
                    <!-- /.form-group -->

                              <div class="col-md-4">
                                 <div class="form-group">
                                    <div class="input-group mb-10">
                                          <select 
                                          required
                                          class="form-control" 
                                          data-show-subtext="true" 
                                          data-live-search="true"   
                                          id="select-estado-cierre" 
                                          title="-- Selecione estado --"
                                          >
                                          <option value="Activo">Activo</option>
                                          <option value="Cerrado">Cerrado</option>
                                          </select> 
                                       </div>
                                    <!-- finaliza asignar actividad economica-->
                                     </div>
                                    </div>
                                  </div><!-- /.form-group -->
                                 
                           <div class = "row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Fecha de cierre:</label>
                                </div>
                              </div>
                                
                              <div class = "col-md-4">
                                <div class="form-group">
                                  <input type="date" name="fecha_cierre" id="fecha_cierre" class="form-control" required placeholder="Fecha de apertura" >
                                  <input type="hidden" name="id" id="id" class="form-control" >
                                 </div>
                                </div>                             
                           </div><!--  /.ROW2 -->

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Imprimir</button>
                                <button type="button" class="btn btn-success" onclick="guardarCierre()">Guardar Cierre</button>
                            </div>
                               </div>
                            </div>

                            <div class="card border-success mb-3"><!-- Panel TRASPASO DE EMPRESA -->
                         <div class="card-header text-success"><label>II. TRASPASO DE RÓTULO</label></div>
                       <div class="card-body">

                       <div class="row"><!-- /.ROW2 -->

                          <!-- /.form-group -->
                          <div class="col-md-6">
                              <div class="form-group">
                                    <label>TRASPASO A NOMBRE DE:</label>
                              </div>
                            </div><!-- /.col-md-6 -->
                    <!-- /.form-group -->

                              <div class="col-md-6">
                                  <div class="form-group">
                                    <!-- Select estado - live search -->
                                      <div class="input-group mb-9">
                                            <select 
                                            required
                                            class="form-control"
                                            data-style="btn-success"
                                            data-show-subtext="true" 
                                            data-live-search="true"   
                                            id="select-contribuyente-traspaso" 
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
                                            <button type="button"  onclick="guardarTraspaso()" class="btn btn-success btn-sm float-right" ><i class="fa fa-print"></i>
                                            &nbsp; Guardar Traspaso &nbsp;</button>
                                          <!-- /.Botón Guardar Traspaso -->
                                      </div>
                                    </div><!-- /.col-md-6 -->
                                  <!-- /.form-group -->
                                  </div><!-- /.ROW3 -->

                              </div><!--  /.card-header text-success -->
                            </div> <!-- /.Panel CIERRE DE EMPRESA --> 
                         </div>
                      </div>
                    </section>
                  </div>       
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Termina modal cierre de rótulo -->


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
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });

    function recargar()
    {
     var ruta = "{{ url('/admin/Rotulos/tabla') }}";
     $('#tablaDatatable').load(ruta);
    }
    
    function CrearInspeccion(id)
    {
      openLoading();
      window.location.href="{{ url('/admin/Rotulos/inspeccion') }}/"+id;
    }

    function cierreytraspaso(id)
    { 
    window.location.href="{{ url('/admin/rotulos/cierres_traspasos/') }}/"+id;
    }

    function CrearCalificacion(id)
    {
      openLoading();
      window.location.href="{{ url('/admin/Rotulos/calificacion') }}/"+id;
    }
        
    function informacionCierre(id)
    {
      
            openLoading();
            document.getElementById("formulario-CierreRotulo").reset();

            axios.post('/admin/Rotulos/vista/inf-cierre',{
                'id': id
            })
            .then((response) => {
              console.log(response);
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalCierreRotulos').modal('show');
                        
                        $('#fecha_cierre').val(response.data.rotulos.fecha_cierre);
                        $('#select-estado-cierre').val(response.data.rotulos.estado);
                       
                        document.getElementById("select-contribuyente-traspaso").options.length = 0;

                        $.each(response.data.contribuyente, function( key, val ){
                            if(response.data.idcont == val.id){
                                $('#select-contribuyente-traspaso').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'&nbsp;'+val.apellido+'</option>');
                            }else{
                                $('#select-contribuyente-traspaso').append('<option value="' +val.id +'">'+val.nombre+'&nbsp;'+val.apellido+'</option>');
                            }
                        });

                      }else{
                        toastr.error('Información no encontrada');
                    }

                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
    
    }
    </script>


    <script>

    function VerListaRotulo()
    {

      openLoading();
      window.location.href="{{ url('/admin/Rotulos/Listar') }}/";

    }

    function guardarCierre()
    {
      //Llamar la variable id desde el controlador
      var id = {{$id}};
      var estado = document.getElementById('select-estado-cierre').value;
      var fecha_cierre = document.getElementById('fecha_cierre').value;
    
      if(estado === '')
      {
          toastr.error('El estado requerido');
          return;
      }

        openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('estado', estado);
            formData.append('fecha_cierre', fecha_cierre);

            axios.post('/admin/Rotulos/vista/cierre', formData, {
            })
            .then((response) => {          
                closeLoading();

                if (response.data.success === 1) 
                   
                   {
                    Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Datos actualizados correctamente!',
                          showConfirmButton: false,
                          timer: 3000
                        })
                       $('#modalCierreRotulos').modal('hide');
                       location.reload();
                   }
                   else 
                   {
                       toastMensaje('Error al actualizar');
                       $('#modalCierreRotulos').modal('hide');
                              recargar();
                   }
             
            })
            .catch((error) => {
                toastr.error('Error al actualizar empresa');
                closeLoading();
            });
    }
    
    function guardarTraspaso()
    {
     
      var id = {{ $id}};
      var contribuyente = document.getElementById('select-contribuyente-traspaso').value;

      if(contribuyente === ''){
            toastr.error('El dato contribuyente es requerido');
            return;
        }

        openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('contribuyente', contribuyente);

            axios.post('/admin/Rotulos/vista/traspaso', formData, {
            })
            .then((response) => {          
                closeLoading();

                if (response.data.success === 1) 
                   
                   {
                       toastr.success('¡Propietario actualizado!');
                       $('#modalCierresTraspasos').modal('hide');
                       location.reload();
                   }
                   else 
                   {
                       toastMensaje('Error al actualizar');
                       $('#modalCierresTraspasos').modal('hide');
                              recargar();
                   }
             
            })
            .catch((error) => {
                toastr.error('Error al actualizar empresa');
                closeLoading();
            });
    }

    function InspeccionRealizada()
    {
      toast.success('La inspeccion ya fue realizada');
      return;
    }

    function CobrosR(id)
    {
      openLoading();

      window.location.href="{{ url('/admin/rotulos/cobros') }}/"+id;
    }

    function NoCobrar()
    {
    toastr.warning('Debe registrar una calificación primero para poder generar un cobro.');
    return;
    }

    function VerCalificacion()
    {
      
    }

    </script>
@stop