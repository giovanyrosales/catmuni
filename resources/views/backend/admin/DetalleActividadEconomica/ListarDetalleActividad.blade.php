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
        <button type="button"onclick="agregarDetalles()" class="btn btn-success btn-sm" >
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
                        <div class="col-md-6">
                        <div class="form-group"> 
                        <label>Limite Inferior:</label>
                        <input type="number" name="limite_inferior" id="limite_inferior-ver" class="form-control" disabled required placeholder="Limite Inferior">
                        <input type="hidden" name="id" id="id-ver" class="form-control" >
                      </div>
                        </div>
                <!-- /.form-group -->
                   
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Fijo:</label>
                        <input type="number" name="fijo" id="fijo-ver" class="form-control" disabled required placeholder="Fijo" >
                     </div>
                    </div>
                  </div>


                    <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Excedente:</label>
                        <input type="number" name="excedente" id="excedente-ver" class="form-control" disabled required placeholder="Fijo" >
                     </div>
                    </div>

                   <div class="col-md-6">
                     <div class="form-group">
                      <label>Categoria:</label>
                         <input type="number" name="categoria" id="categoria-ver" disabled required placeholder="Categoria" class="form-control" >
                    </div>
                     </div> 
                  </div> 
                  
            
             <div class="row">
                   <div class="col-md-6">
                     <div class="form-group">
                    <label>Millar:</label>
                    <input type="number" name="millar" id="millar-ver" class="form-control" disabled placeholder="Millar"  >
                  </div>
                 </div>
                  
                <div class="col-md-6">
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
             
                     </div>
             </div>
                           <!-- finaliza asignar actividad economica-->
                        
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


      <!--Modal para agregar actividad economica-->
      <div class="modal fade" id="modalAgregarDetalles">
        <div class="modal-dialog" style="width:2000px;">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title">Agregar detalle actividad económica</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-AgregarDetalles">
                        <div class="card-body">
                        <div class="card-body">
            <div class="row">
                   <div class="col-md-6">
                     <div class="form-group">
                        <label>Limite Inferior:</label>
                        <input type="number" name="limite_inferior" id="limite_inferior" class="form-control" required placeholder="Limite Inferior">
                        <input type="hidden" name="id" id="id" class="form-control" >
                      </div>
                   </div>
                <!-- /.form-group -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Fijo:</label>
                        <input type="number" name="fijo" id="fijo" class="form-control" required placeholder="Fijo" >
                     </div>
                    </div>
            </div>
            
            <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Excedente:</label>
                        <input type="number" name="excedente" id="excedente" class="form-control" required placeholder="Excedente" >
                     </div>
                    </div>
                
                   <div class="col-md-6">
                     <div class="form-group">
                      <label>Categoria:</label>
                         <input type="number" name="categoria" id="categoria" required placeholder="Categoria" class="form-control" >
                    </div>
                     </div> 
            </div> 
                 
            
            <div class="row">
                   <div class="col-md-6">
                     <div class="form-group">
                    <label>Millar:</label>
                    <input type="number" name="millar" id="millar" class="form-control" placeholder="Millar"  >
                  </div>
                 </div>
                 
                <!-- /.form-group -->
                
                <div class="col-md-6">
                     <div class="form-group">
                          <label>Actividad económica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-6">
                                <select 
                                required
                                class="selectpicker"
                                data-style="btn-success"
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
            </div>
                   
            <div class="form-group">
              <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="nuevaAct()"> Guardar </button>
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">Cancelar</button>
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
      
       <!--Finaliza Modal para agregar actividad economica-->
     
     
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
                        <div class="col-md-6">
                        <div class="form-group"> 
                        <label>Limite Inferior:</label>
                        <input type="number" name="limite_inferior" id="limite_inferior-editar" class="form-control" required placeholder="Limite Inferior">
                        <input type="hidden" name="id" id="id-editar" class="form-control" >
                      </div>
                        </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Fijo:</label>
                        <input type="number" name="fijo" id="fijo-editar" class="form-control" required placeholder="Fijo" >
                      </div>
                     </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Excedente:</label>
                        <input type="number" name="excedente" id="excedente-editar" class="form-control" required placeholder="Excedente" >
                     </div>
                    </div>
                   <div class="col-md-6">
                     <div class="form-group">
                      <label>Categoria:</label>
                         <input type="number" name="categoria" id="categoria-editar" required placeholder="Categoria" class="form-control" >
                      </div>
                     </div> 
                  </div> 
                 
                  <div class="row">
                   <div class="col-md-6">
                     <div class="form-group">
                    <label>Millar:</label>
                    <input type="number" name="millar" id="millar-editar" class="form-control" placeholder="Millar"  >
                  </div>
                 </div>
                 
                <!-- /.form-group -->
                
                <div class="col-md-6">
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
                           <!-- finaliza asignar actividad economica-->
                        </div>
                        </div>
                      </div>
                           <!-- finaliza select Asignar Representante-->
                           <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success  float-right" onclick="editarD()">Actualizar</button>
                    </div>
                  <!--finaliza los campos del formulario-->
                     </div>
                     </form>
                    </div>
                     </div>
                  </div>
                
      <!--Finaliza Modal editar datos del detalle -->

    <!-- Inicia Modal Borrar Detalle-->

 <div class="modal fade" id="modalEliminar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar detalles actividad economica</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-borrar">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>¿Realmente desea eliminar el detalle seleccionado?"</p>

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

        <!--Finaliza Modal Borrar Detalle-->

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

    function recargar()
    {
     var ruta = "{{ url('/admin/DetalleActividadEconomica/tabla') }}";
     $('#tablaDatatable').load(ruta);
    }

    function agregarDetalles(id)
    {
        document.getElementById("formulario-AgregarDetalles").reset();
            $('#modalAgregarDetalles').modal('show');
    }

   function editarDetalles(id)
    {
        document.getElementById("formulario-EditarDetalles").reset();
            $('#modalEditarDetalles').modal('show');
    }

    function informacionD(id)
    {
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
                        $('#excedente-editar').val(response.data.detalle_actividad_economica.excedente);
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
                        toastr.error('La información solicitada no fue encontrada ');
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
        var excedente = document.getElementById('excedente-editar').value;
        var categoria = document.getElementById('categoria-editar').value;
        var millar = document.getElementById('millar-editar').value;

        openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('actividad_economica', actividad_economica);
            formData.append('limite_inferior', limite_inferior);
            formData.append('fijo',fijo);
            formData.append('excedente',excedente);
            formData.append('categoria',categoria);
            formData.append('millar',millar)
            
            axios.post('/admin/DetalleActividadEconomica/editar', formData, {
            })
            .then((response) => {
                closeLoading();
                if (response.data.success === 1) 
                    {
                        toastr.success('Detalle actualizado');
                        $('#modalEditarDetalles').modal('hide');
                        recargar();
                    }
                    else 
                    {
                      toastMensaje('Error');
                      $('#modalEditarDetalles').modal('hide');
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
                        $('#excedente-ver').val(response.data.detalle_actividad_economica.excedente);
                        $('#categoria-ver').val(response.data.detalle_actividad_economica.categoria);
                        $('#millar-ver').val(response.data.detalle_actividad_economica.millar);
                        //$('#actividad_economica-ver').val(response.data.actividad_economica.actividad_economica);
             
                        document.getElementById("select-actividad_economica-ver").selectedIndex;


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
            function modalEliminar(id)
            {
                $('#idborrar').val(id);
                $('#modalEliminar').modal('show');
            }

            function eliminarD(){
            openLoading()
        
            // se envia el ID del contribuyente
            var id = document.getElementById('idborrar').value;

            var formData = new FormData();
            formData.append('id', id);

            axios.post('/admin/DetalleActividadEconomica/eliminar_detalles', formData, {
            })
                .then((response) => {
                    closeLoading()
                    $('#modalEliminar').modal('hide');
                    
                    if(response.data.success === 1){
                      toastMensaje('success', 'Detalle eliminado');
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

      

        function nuevaAct(id)
      {
        
        var actividad_economica = document.getElementById('select-actividad_economica').value;
        var limite_inferior = document.getElementById('limite_inferior').value;
        var fijo = document.getElementById('fijo').value;
        var excedente = document.getElementById('excedente').value;
        var categoria = document.getElementById('categoria').value;
        var millar = document.getElementById('millar').value;

        if(limite_inferior === ''){
            toastr.error('El limite inferior es requerido');
            return;
        }
        
        if(fijo === ''){
            toastr.error('Fijo es requerido');
            return;
        }

        if(excedente === ''){
            toastr.error('Excedente es requerido');
            return;
        }
       
        if(categoria === ''){
            toastr.error('Categoria es requerida');
            return;
        }
        
        if(millar === ''){
            toastr.error('Millar es requerido');
            return;
        }

        if(actividad_economica === ''){
            toastr.error('Actividad económica es requerida');
            return;
        }

        openLoading();
      var formData = new FormData();
      formData.append('actividad_economica', actividad_economica);
      formData.append('limite_inferior', limite_inferior);
      formData.append('fijo', fijo);
      formData.append('excedente', excedente);
      formData.append('categoria', categoria);
      formData.append('millar', millar);

      axios.post('/admin/DetalleActividadEconomica/Detalle-Act', formData,
       {
            })

            .then((response) => {
              closeLoading();
          if (response.data.success === 1)
          {
            toastr.success('Guardado exitosamente');
            $('#modalEliminar').modal('hide');
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

</script>

@stop