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
        <button type="button"onclick="location.href='{{ url('/admin/DetalleActividadEconomica/Crear') }}'" class="btn btn-success btn-sm" >
                <i class="fas fa-pencil-alt"></i>
                Nuevo detalle de actividad económica
            </button>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <!-- CAJA -->
        <form class="form-horizontal" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Detalle Actividad Económica</h3>

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

    <!-- /Modal ver datos del detalle-->

    <div class="modal fade" id="modalVerDetalles">
        <div class="modal-dialog" style="width:1300px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Datos detalle actividad económica</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-verDetalles">
                        <div class="card-body">
                                                          
                <!--inicia los campos del formulario ver-->
                 <!-- /.card-header -->
                 <div class="card-body">
                  <div class="row">
                        <div class="col-md-8">
                        <div class="form-group"> 
                         <label>Limite inferior:</label>
                        <input type="text" name="limite_inferior" id="limite_inferior-ver" disabled class="form-control" >
                        <input type="hidden" name="id" id="id-ver"  >
                      </div>
                <!-- /.form-group -->
                <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                          <label>Fijo:</label>
                          <input type="number" name="fijo" id="fijo-ver" disabled class="form-control" >
                  </div></div>
                <div class="col-md-8">
                  <div class="form-group">
                          <label>Categoria:</label>
                          <input type="number" name="categoria" id="categoria-ver" disabled class="form-control" >
                     </div>
                    </div>
                  </div>
                <!-- /.form-group -->
                <div class="form-group">
                    <label>Millar:</label>
                    <input type="text" name="millar" id="millar-ver" disabled class="form-control">
                  </div>
                <!-- /.form-group --> 
                <div class="col-md-14">
                          <div class="form-group">
                          <label>Actividad económica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-9">
                                <select 
                                required
                                disabled
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-actividad_economica-ver" 
                                 >
                                  @foreach($actividadeconomica as $actEc)
                                  <option value="{{ $actEc->id }}"> {{ $actEc->rubro }}</option>
                                  @endforeach 
                                </select>  
                           </div>
                        </div>
                <!-- /.form-group -->  
                     </div>
                    </div>
                  </div>
               </div>
                   <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
                    
                    </div>
              <!--finaliza los campos del formulario-->
                     </div>
                     </form>
                    </div>
                     </div>
                  </div>
                </div>
              </div>
      <!--Finaliza Modal ver datos del detalle -->

     
     
      <!-- /Modal editar datos del detalle-->

    <div class="modal fade" id="modalEditarDetalles">
        <div class="modal-dialog" style="width:1300px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Datos detalle actividad económica</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-EditarDetalles">
                        <div class="card-body">
                                                          
                <!--inicia los campos del formulario ver-->
                 <!-- /.card-header -->
                 <div class="card-body">
                  <div class="row">
                        <div class="col-md-8">
                        <div class="form-group"> 
                         <label>Limite inferior:</label>
                        <input type="text" name="limite_inferior" id="limite_inferior-editar" class="form-control" >
                        <input type="hidden" name="id" id="id-editar"  >
                      </div>
                <!-- /.form-group -->
                <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                          <label>Fijo:</label>
                          <input type="number" name="fijo" id="fijo-editar" class="form-control" >
                  </div></div>
                <div class="col-md-8">
                  <div class="form-group">
                          <label>Categoria:</label>
                          <input type="number" name="categoria" id="categoria-editar" class="form-control" >
                     </div>
                    </div>
                  </div>
                <!-- /.form-group -->
                <div class="form-group">
                    <label>Millar:</label>
                    <input type="text" name="millar" id="millar-editar" class="form-control">
                  </div>
                <!-- /.form-group --> 
                <div class="col-md-14">
                          <div class="form-group">
                          <label>Actividad económica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-9">
                                <select 
                                required
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-actividad_economica-editar" 
                                 >
                                  @foreach($actividadeconomica as $actEc)
                                  <option value="{{ $actEc->id }}"> {{ $actEc->rubro }}</option>
                                  @endforeach 
                                </select>  
                           </div>
                        </div>
                <!-- /.form-group -->  
                     </div>
                    </div>
                  </div>
               </div>
                   <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick = "editarD()" data-dismiss="modal">Aceptar</button>
                    </div>
                    <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
              <!--finaliza los campos del formulario-->
                     </div>
                     </form>
                    </div>
                     </div>
                  </div>
                </div>
              </div>
      <!--Finaliza Modal editar datos del detalle -->



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
            var ruta = "{{ url('/admin/DetalleActividadEconomica/tabla') }}";
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";
        });
</script>

<script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });

    function recargar(){
     var ruta = "{{ url('/admin/DetalleActividadEconomica/tabla') }}";
     $('#tablaDatatable').load(ruta);
   }

    function informacionD(id){
         openLoading();
           document.getElementById("formulario-EditarDetalles").reset();

            axios.post('/admin/DetalleActividadEconomica/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditarDetalles').modal('show');

                        $('#id-editar').val(response.data.detalle_actividad_economica.id);
                        $('#limite_inferior-editar').val(response.data.detalle_actividad_economica.limite_inferior);
                        $('#fijo-editar').val(response.data.detalle_actividad_economica.fijo);
                        $('#categoria-editar').val(response.data.detalle_actividad_economica.categoria);
                        $('#millar-editar').val(response.data.detalle_actividad_economica.millar);
                        //$('#actividad_economica-ver').val(response.data.actividad_economica.actividad_economica);
                     
                        document.getElementById("select-actividad_economica-editar").options.length = 0;
                        $.each(response.data.actividad_economica, function( key, val ){
                            if(response.data.idact_eco == val.id){
                                $('#select-actividad_economica-editar').append('<option value="' +val.id +'" selected="selected">'+val.rubro+'</option>');
                            }else{
                                $('#select-actividad_economica-editar').append('<option value="' +val.id +'">'+val.rubro+'</option>');
                            }
                        });
                     
                    }else{
                        toastr.error('Información ');
                    }

                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
       
            }


    function editarD()        
    {
        var id = document.getElementById('id-editar').value;
        var actividad_economica = document.getElementById('select-actividad_economica-editar').value;
        var limite_inferior = document.getElementById('limite_inferior-editar').value;
        var fijo = document.getElementById('fijo-editar').value;
        var categoria = document.getElementById('categoria-editar').value;
        var millar = document.getElementById('millar-editar').value;

        openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('actividad_economica', actividad_economica);
            formData.append('limite_inferior', limite_inferior);
            formData.append('fijo',fijo);
            formData.append('categoria',categoria);
            formData.append('millar',millar)
            
            axios.post('/admin/DetalleActividadEconomica/editar', formData, {
            })
            .then((response) => {
                closeLoading();
                if (response.data.success === 1) 
                    {
                        toastr.success('Detalle actualizado');
                    }
                    else 
                    {
                      toastMensaje('Error');
                      $('#modalEditar').modal('hide');
                         recargar();
                    }
                  })
            .catch((error) => {
              closeLoading();
                toastr.error('Error al actualizar');
             
            });
    }

    
    function verDetalles(id){
             openLoading();
             document.getElementById("formulario-verDetalles").reset();

            axios.post('/admin/DetalleActividadEconomica/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalVerDetalles').modal('show');

                        $('#id-ver').val(response.data.detalle_actividad_economica.id);
                        $('#limite_inferior-ver').val(response.data.detalle_actividad_economica.limite_inferior);
                        $('#fijo-ver').val(response.data.detalle_actividad_economica.fijo);
                        $('#categoria-ver').val(response.data.detalle_actividad_economica.categoria);
                        $('#millar-ver').val(response.data.detalle_actividad_economica.millar);
                        //$('#actividad_economica-ver').val(response.data.actividad_economica.actividad_economica);
                     
                        document.getElementById("select-actividad_economica-ver").options.length = 0;
                        $.each(response.data.actividad_economica, function( key, val ){
                            if(response.data.idact_eco == val.id){
                                $('#select-actividad_economica-ver').append('<option value="' +val.id +'" selected="selected">'+val.rubro+'</option>');
                            }else{
                                $('#select-actividad_economica-ver').append('<option value="' +val.id +'">'+val.rubro+'</option>');
                            }
                        });
                     

                    }else{
                        toastr.error('No se encuentra la información solicitada');
                    }

                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
       
            }

</script>


@stop