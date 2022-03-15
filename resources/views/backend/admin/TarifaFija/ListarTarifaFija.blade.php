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
            <h1>Lista de tarifas fijas.</h1>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Tarifas fijas</li>
                            </ol>
                        </div>
        </div>
        <br>
        <button type="button"onclick=" agregarTarifa()" class="btn btn-success btn-sm" >
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

 <!--Modal para agregar tarifa fija-->
 <div class="modal fade" id="modalAgregarTarifaFija">
        <div class="modal-dialog modal-xl">
        <div class="modal-content">
         <div class="modal-header">
         <h6 class="modal-title">Agregar tarifa fija</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-AgregarTarifaFija">
                    <div class="card card-green">
            <div class="card-header">
            <h3 class="card-title">Agregar tarifa fija</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
                    </div>
                    <div class="card-body">
             <div class="card-body">
            <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Código:</label>
                        <input type="text" name="codigo" id="codigo" class="form-control" required placeholder="Código">
                        <input type="hidden" name="id" id="id" class="form-control" >
                      </div>
                   </div>
              <!-- /.form-group -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Limite inferior:</label>
                        <input type="number" name="limite_inferior" id="limite_inferior" class="form-control" required placeholder="Limite inferior" >
                     </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Limite superior:</label>
                        <input type="number" name="limite_superior" id="limite_superior" class="form-control" required placeholder="Limite superior" >
                     </div>
                    </div>
             </div> 
            <div class="row">        
                   <div class="col-md-4">
                     <div class="form-group">
                      <label>Impuesto mensual:</label>
                         <input type="number" name="impuesto_mensual" id="impuesto_mensual" required placeholder="Impuesto mensual" class="form-control" >
                    </div>
                     </div> 
                     
            
                  <div class="col-md-4">
                     <div class="form-group">
                          <label>Rubro:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-6">
                                <select 
                                required
                                class="selectpicker"  
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-actividad_economica" 
                                title="-- Selecione la actividad --"
                                onchange="llenarSelect()"                              
                                 >
                                  @foreach($actividadeconomica as $actE)
                                  <option value="{{ $actE->id }}"> {{ $actE->rubro }}</option>
                                  @endforeach 
                                </select> 
                           </div>
                     </div>
                </div>

                <div class="col-md-4">
                     <div class="form-group" id= "actividad-especificaDIV">
                          <label>Actividad específica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-6" >
                        
                          <select class="form-control"  id="select-actividad_especifica"
                       
                        >
                         </select>
                           </div>
                       </div>
                    </div>
                  </div>    
                
                </div>
             </div>
                   
            <div class="form-group">
              <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="nuevaTarifa()"> Guardar </button>
                  <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                </div>
                </div>
           <!-- /.col -->
            </div>
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
     
      
<!--Finaliza Modal para agregar tarifa fija-->

<!--Modal para editar tarifa fija-->
        <div class="modal fade" id="modalEditarTarifaFija">
        <div class="modal-dialog modal-xl">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title">Actualizar tarifa fija</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-EditarTarifaFija">
                        <div class="card-body">
                        <div class="card-body">

               <div class="row">
                   <div class="col-md-4">
                     <div class="form-group">
                        <label>Código:</label>
                        <input type="text" name="codigo" id="codigo-editar" class="form-control" required placeholder="Código">
                        <input type="hidden" name="id" id="id-editar" class="form-control" >
                      </div>
                   </div>

                <!-- /.form-group -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Limite inferior:</label>
                        <input type="number" name="limite_inferior" id="limite_inferior-editar" class="form-control" required placeholder="Limite inferior" >
                     </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Limite superior:</label>
                        <input type="number" name="limite_superior" id="limite_superior-editar" class="form-control" required placeholder="Limite superior" >
                     </div>
                    </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Impuesto mensual:</label>
                         <input type="number" name="impuesto_mensual" id="impuesto_mensual-editar" required placeholder="Impuesto mensual" class="form-control" >
                    </div>
                  </div> 
                              
                <div class="col-md-4">
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
                                onchange="llenarSelectEditar()"
                                 >
                                  @foreach($actividadeconomica as $actEc)
                                  <option value="{{ $actEc->id }}"> {{ $actEc->rubro }}</option>
                                  @endforeach 
                                </select>  
                           </div>
                        </div>
                <!-- /.form-group -->  
                 </div>

                <div class="col-md-4">
                   <div class="form-group">
                          <label>Actividad específica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-6">
                                <select 
                                required
                                class="form-control"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-actividad_especifica-editar" 
                                title="-- Selecione la actividad --"
                                 >
                                  @foreach($actividadespecifica as $actEsp)
                                  <option value="{{ $actEsp->id }}"> {{ $actEsp->nom_actividad_especifica }}</option>
                                  @endforeach 
                                </select> 
                           </div>
                     </div>
                </div>
               </div>     

            <div class="form-group">
              <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="actualizarTarifa()"> Guardar </button>
                  <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                </div>
                </div>
           <!-- /.col -->
            </div>
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

 <div class="modal fade" id="modalEliminarTarifa">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar tarifa fijas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-EliminarTarifa">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>¿Realmente desea eliminar la tarifa fija seleccionada?"</p>

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
                    <button type="button" class="btn btn-danger" onclick="eliminarD()">Borrar</button>
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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


    <!-- incluir tabla -->
 <script type="text/javascript">
        $(document).ready(function(){
            var ruta = "{{ url('/admin/TarifaFija/tabla') }}";
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";
            $('#actividad-especificaDIV').hide();
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

    function agregarTarifa(id)
    {
        document.getElementById("formulario-AgregarTarifaFija").reset();
            $('#modalAgregarTarifaFija').modal('show');
    }

    function nuevaTarifa(id)
    {
        var codigo = document.getElementById('codigo').value;
        var actividad_economica = document.getElementById('select-actividad_economica').value;
        var actividad_especifica = document.getElementById('select-actividad_especifica').value;
        var limite_inferior = document.getElementById('limite_inferior').value;
        var limite_superior = document.getElementById('limite_superior').value;
        var impuesto_mensual = document.getElementById('impuesto_mensual').value;

        if(codigo === '')
        {
            toastr.error('El código es requerido');
            return;
        }
                
        if(actividad_especifica === '')
        {
            toastr.error('La actividad específica es requerida');
            return;
        }
        
        if(impuesto_mensual === '')
        {
            toastr.error('Impuesto mensual es requerido');
            return;
        }
        
        if(actividad_economica === '')
        {
            toastr.error('Actividad económica es requerida');
            return;
        }
        openLoading();
      var formData = new FormData();
          formData.append('codigo', codigo);
          formData.append('actividad_economica', actividad_economica);
          formData.append('actividad_especifica', actividad_especifica);
          formData.append('limite_inferior', limite_inferior);
          formData.append('limite_superior', limite_superior);
          formData.append('impuesto_mensual', impuesto_mensual);
     
      axios.post('/admin/TarifaFija/NuevaT', formData,
        {
            })

            .then((response) => {
              console.log(response)
              closeLoading();
          if (response.data.success === 1)
          {
            Swal.fire({
                  position: 'top-end',
                  icon: 'success',
                  title: '¡Tarifa fija registrada correctamente!',
                  showConfirmButton: false,
                  timer: 2000
                     })
            $('#modalAgregarTarifaFija').modal('hide');
            location.reload();
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


    function editarTarifa(id)
    {
      openLoading();
            document.getElementById("formulario-EditarTarifaFija").reset();

            axios.post('/admin/TarifaFija/informacion',{
                'id': id
            })
                .then((response) => {
                //console.log(response)
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditarTarifaFija').modal('show');

                        $('#id-editar').val(response.data.tarifa_fija.id);
                        $('#codigo-editar').val(response.data.tarifa_fija.codigo);
                        $('#limite_inferior-editar').val(response.data.tarifa_fija.limite_inferior);
                        $('#limite_superior-editar').val(response.data.tarifa_fija.limite_superior);
                        $('#impuesto_mensual-editar').val(response.data.tarifa_fija.impuesto_mensual);

                        document.getElementById("select-actividad_economica-editar").options.length = 0;
                        document.getElementById("select-actividad_especifica-editar").options.length = 0;

                        $.each(response.data.actividad_economica, function( key, val ){
                            if(response.data.idact_eco == val.id){
                                $('#select-actividad_economica-editar').append('<option value="' +val.id +'" selected="selected">'+val.rubro+'</option>');
                            }else{
                                $('#select-actividad_economica-editar').append('<option value="' +val.id +'">'+val.rubro+'</option>');
                            }
                        });

                        $.each(response.data.actividad_especifica, function( key, val ){
                            if(response.data.idact_esp == val.id){
                                $('#select-actividad_especifica-editar').append('<option value="' +val.id +'" selected="selected">'+val.nom_actividad_especifica+'</option>').select2();
                            }else{
                                $('#select-actividad_especifica-editar').append('<option value="' +val.id +'">'+val.nom_actividad_especifica+'</option>').select2();
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

    function actualizarTarifa()
    {
            var id = document.getElementById('id-editar').value;
            var codigo = document.getElementById('codigo-editar').value;
            var actividad_economica = document.getElementById('select-actividad_economica-editar').value;
            var actividad_especifica = document.getElementById('select-actividad_especifica-editar').value;
            var limite_inferior = document.getElementById('limite_inferior-editar').value;
            var limite_superior = document.getElementById('limite_superior-editar').value;
            var impuesto_mensual = document.getElementById('impuesto_mensual-editar').value;
           
            openLoading()

          var formData = new FormData();
              formData.append('id', id);
              formData.append('codigo', codigo);
              formData.append('actividad_economica', actividad_economica);
              formData.append('actividad_especifica', actividad_especifica);
              formData.append('limite_inferior', limite_inferior);
              formData.append('limite_superior', limite_superior);
              formData.append('impuesto_mensual', impuesto_mensual);
           
            axios.post('/admin/TarifaFija/editar', formData, {
            })

                .then((response) => {
                ///   console.log(response);
                    closeLoading()

                   if (response.data.success === 1) 
                    {
                      Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Tarifa fija actualizada correctamente!',
                          showConfirmButton: false,
                          timer: 2000
                        })
                        $('#modalEditarTarifaFija').modal('hide');
                        recargar();
                    }
                    else 
                    {
                        toastMensaje('Error al actualizar');
                        $('#modalEditarTarifaFija').modal('hide');
                               recargar();
                    }
                })
                .catch((error) => {
                    closeLoading()
                    toastMensaje('error', 'Error');
                });

    }

    function modalEliminarTarifa(id)
    {
        $('#idborrar').val(id);
        $('#modalEliminarTarifa').modal('show');
    }

    function eliminarD()
    {
      openLoading()
        
            // se envia el ID de la tarifa
      var id = document.getElementById('idborrar').value;

      var formData = new FormData();
          formData.append('id', id);

            axios.post('/admin/TarifaFija/eliminar', formData, {
            })
              .then((response) => {
                closeLoading()
                    $('#modalEliminarTarifa').modal('hide');
                    
               if(response.data.success === 1){
                Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Tarifa fija eliminada correctamente!',
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

    // Función para llenar select

    function llenarSelect()
    {
             var id_select = document.getElementById('select-actividad_economica').value;
                     
             var formData = new FormData();
             formData.append('id_select', id_select);
             
             axios.post('/admin/TarifaFija/buscar', formData, {
              })
              .then((response) => {
            
               document.getElementById("select-actividad_especifica").options.length = 0;
               $('#actividad-especificaDIV').show();
          
            
                $.each(response.data.actividad_especifica, function( key, val ){
                       $('#select-actividad_especifica').append('<option value="' +val.id +'">'+val.nom_actividad_especifica+'</option>').select2();
                     
                            
                    });

               })
            .catch((error) => {
               // toastr.error('Error al registrar empresa');
               
            });
            
             
    }
// Termina función

function llenarSelectEditar()
    {
            var id_select = document.getElementById('select-actividad_economica-editar').value;
                     
            var formData = new FormData();
                formData.append('id_select', id_select);
             
             axios.post('/admin/TarifaFija/buscar', formData, {
              })
              .then((response) => {
            
               document.getElementById("select-actividad_especifica-editar").options.length = 0;
              
          
            
               $.each(response.data.actividad_especifica, function( key, val ){
                            if(response.data.idact == val.id){
                                $('#select-actividad_especifica-editar').append('<option value="' +val.id +'" selected="selected">'+val.nom_actividad_especifica+'</option>').select2();
                            }else{
                                $('#select-actividad_especifica-editar').append('<option value="' +val.id +'">'+val.nom_actividad_especifica+'</option>').select2();
                            }
                        });

              })
            .catch((error) => {
               // toastr.error('Error al registrar empresa');
               
            });
            
             
    }
// Termina función

</script>

@stop