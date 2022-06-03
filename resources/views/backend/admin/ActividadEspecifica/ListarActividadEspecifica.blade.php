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
            <h1>Actividad específica</h1>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Actividad específica</li>
                            </ol>
                        </div>
        </div>
        <br>
        <button type="button"onclick="agregarActividadE()" class="btn btn-success btn-sm" >
                <i class="fas fa-pencil-alt"></i>
                Nueva actividad específica
            </button>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <!-- CAJA -->
        <form class="form-horizontal" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Actividad Específica</h3>

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

   <!--Modal para agregar tactividad especifica-->
   
   <div class="modal fade" id="modalAgregarActividadEspecifica">
        <div class="modal-dialog dtr-modal-content">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title">Agregar actividad específica</h4>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
                </div>
               <div class="modal-body">
            <form id="formulario-AgregarActividadEspecifica">
        
            <div class="card-body">
     
            <div class="row">
                   <div class="col-md-10">
                     <div class="form-group">
                        <label>Actividad específica:</label>
                      <input type="text" name="nom_actividad_especifica" id="nom_actividad_especifica" class="form-control" required placeholder="Actividad específica">
                        <input type="hidden" name="id" id="id" class="form-control" >
                      </div>
                   </div>
            </div>

            <div class="row">
                <!-- /.form-group -->
                  <div class="col-md-10">
                     <div class="form-group">
                          <label>Actividad económica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-10">
                                <select 
                                required
                                class="selectpicker"
                              
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-actividad_economica" 
                                title="-- Selecione la actividad --"
                                 >
                                  @foreach($actividadeconomica as $actE)
                                  <option value="{{ $actE->id }}"> {{ $actE->rubro }}</option>
                                  @endforeach 
                                </select> 
                           </div>
                           <!-- finaliza asignar actividad economica-->
                        </div>
                          </div>
              </div>
                           <!-- finaliza select Asignar Representante-->
           
        
            </div>
                   
            <div class="form-group">
              <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="nuevaActividadE()"> Guardar </button>
                  <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                </div>
                </div>
           <!-- /.col -->
        
          </div>
        </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>  
         </div>
        </div>
        </div>
        </div>
      
    <!--Finaliza Modal para agregar tarifa variable-->


   <!--Modal para editar tarifa fija-->
    <div class="modal fade" id="modalEditarActividadEspecifica">
     <div class="modal-dialog dtr-modal-content">
      <div class="modal-content">
        <div class="modal-header">
         <h4 class="modal-title">Actualizar actividad específica</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
              <form id="formulario-EditarActividadEspecifica">
              <div class="card-body">
              <div class="row">
                   <div class="col-md-10">
                     <div class="form-group">
                        <label>Actividad específica:</label>
                        <input type="text" name="nom_actividad_especifica-editar" id="nom_actividad_especifica-editar" class="form-control" required placeholder="Actividad específica">
                        <input type="hidden" name="id" id="id-editar" class="form-control" >
                      </div>
                   </div>
              </div>
                
               <div class="row">
                <div class="col-md-10">
                     <div class="form-group">
                          <label>Rubro:</label>
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

            <div class="form-group">

                </div>
           <!-- /.col -->
            </div>
            <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="actualizarActividadE()"> Guardar </button>
                  <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                </div>
        </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>  
         </div>
        </div>
        </div>
      </div>
      
<!--Finaliza Modal para editar Tarifa-->

 <!-- Inicia Modal Borrar Tarifa-->

 <div class="modal fade" id="modalEliminarActividadEspecifica">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar actividad específica</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-EliminarActividadEspecifica">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>¿Realmente desea eliminar la actividad específica seleccionada?"</p>

                                    <div class="form-group">
                                        <input type="hidden" id="idborrar">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger" onclick="eliminarActividadE()">Borrar</button>
                </div>
            </div>
        </div>
    </div>

        <!--Finaliza Modal Borrar Tarifa-->
     
     



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
            var ruta = "{{ url('/admin/ActividadEspecifica/tabla') }}";
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
     var ruta = "{{ url('/admin/ActividadEspecifica/tabla') }}";
     $('#tablaDatatable').load(ruta);
    }


    function agregarActividadE(id)
    {
        document.getElementById("formulario-AgregarActividadEspecifica").reset();
            $('#modalAgregarActividadEspecifica').modal('show');
    }

    
    function nuevaActividadE(id)
      {
        
        var actividad_economica = document.getElementById('select-actividad_economica').value;
        var nom_actividad_especifica = document.getElementById('nom_actividad_especifica').value;
            
        if(nom_actividad_especifica === ''){
            toastr.error('La actividad específica es requerida');
            return;
        }

        if(actividad_economica === ''){
            toastr.error('Actividad económica es requerida');
            return;
        }

        openLoading();
      var formData = new FormData();
      formData.append('actividad_economica', actividad_economica);
      formData.append('nom_actividad_especifica', nom_actividad_especifica);
   

      axios.post('/admin/ActividadEspecifica/NuevaM', formData,
       {
            })

            .then((response) => {
              closeLoading();
          if (response.data.success === 1)
          {
            Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: '¡Registro guardado correctamente!',
            showConfirmButton: false,
            timer: 2000
          })
           // toastr.success('Guardado exitosamente');
            $('#modalAgregarActividadEspecifica').modal('hide');
           recargar();
          }
          else
          {
            toastr.error('¡Error al guardar!');
          }
             })

           .catch((error) => {
              toastr.error('Error al registrar');
               closeLoading();
          });

         }

         function infoActividadE(id)
    {
      openLoading();
            document.getElementById("formulario-EditarActividadEspecifica").reset();

            axios.post('/admin/ActividadEspecifica/informacion',{
                'id': id
            })
                .then((response) => {
//console.log(response)
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditarActividadEspecifica').modal('show');

                        $('#id-editar').val(response.data.actividad_especifica.id);
                        $('#nom_actividad_especifica-editar').val(response.data.actividad_especifica.nom_actividad_especifica);
                       
                        document.getElementById("select-actividad_economica-editar").options.length = 0;
                        $.each(response.data.actividad_economica, function( key, val ){
                            if(response.data.idact_eco == val.id){
                                $('#select-actividad_economica-editar').append('<option value="' +val.id +'" selected="selected">'+val.rubro+'</option>');
                            }else{
                                $('#select-actividad_economica-editar').append('<option value="' +val.id +'">'+val.rubro+'</option>');
                            }
                        });
                       
                    }else{
                        toastr.error('Información solicitada no fue encontrada');
                    }
                    
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
       
    }

    function actualizarActividadE()
    {
            var id = document.getElementById('id-editar').value;
            var nom_actividad_especifica = document.getElementById('nom_actividad_especifica-editar').value;
            var actividad_economica = document.getElementById('select-actividad_economica-editar').value;
      
            openLoading()

           var formData = new FormData();
              formData.append('id', id);
              formData.append('nom_actividad_especifica', nom_actividad_especifica);
              formData.append('actividad_economica', actividad_economica);
             
            axios.post('/admin/ActividadEspecifica/editar', formData, {
            })

                .then((response) => {
                ///   console.log(response);
                    closeLoading()

                   if (response.data.success === 1) 
                    {
                          Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Datos actualizados correctamente!',
                          showConfirmButton: false,
                          timer: 2000
                        })
                        $('#modalEditarActividadEspecifica').modal('hide');
                        recargar();
                    }
                    else 
                    {
                        toastMensaje('Error al actualizar');
                        $('#modalEditarActividadEspecifica').modal('hide');
                               recargar();
                    }
                })
                .catch((error) => {
                    closeLoading()
                    toastMensaje('error', 'Error');
                });

    }

    function modalEliminarActividadEspec(id)
    {
        $('#idborrar').val(id);
        $('#modalEliminarActividadEspecifica').modal('show');
    }

    function eliminarActividadE(){
      openLoading()
          // se envia el ID de la tarifa
      var id = document.getElementById('idborrar').value;

      var formData = new FormData();
      formData.append('id', id);

            axios.post('/admin/ActividadEspecifica/eliminar', formData, {
            })
              .then((response) => {
                closeLoading()
                    $('#modalEliminarActividadEspecifica').modal('hide');
                    
               if(response.data.success === 1){
                Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Datos eliminados correctamente!',
                          showConfirmButton: false,
                          timer: 2000
                        })
                  recargar();
               }else{
                  toastMensaje('error', 'Error al borrar');
           
                    }
                })
                
           .catch(function (error) {
              closeLoading()
              toastr.error("Error de Servidor!");
               }); 
        }

   
</script>
  
@stop    