@extends('backend.menus.superior')

@section('content-admin-css')
    <!-- Para el select live search -->
    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">
    <!-- Finaliza el select live search -->
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="sweetalert2.min.css">
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
            <h5>Lista De Rótulos Registrados</h1>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Listado de rótulos</li>
                            </ol>
                        </div>
        </div>
        <br>
        <button type="button"onclick="location.href='{{ url('/admin/nuevo/rotulos/Crear') }}'" class="btn btn-success btn-sm" >
                <i class="fas fa-pencil-alt"></i>
                Nuevo rótulo
            </button>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <!-- CAJA -->
        <form class="form-horizontal" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Rótulos</h3>

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

<!-- /Modal ver rótulos-->
<div class="modal fade" id="modalVerRotulos">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-VerRotulos">
                      <div class="card-body">
                      <div class="card card-green">
                  <div class="card-header">
                      <h3 class="card-title">Ver Rótulos</h3>

                  <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                  </div>
                  </div>

                <!--inicia los campos del formulario ver-->
                 <!-- /.card-header -->
                 <div class="card-body">
                  <div class="row">
                        <div class="col-md-3">
                        <div class="form-group"> 
                        <label>Número de tarjeta:</label>
                        <input type="number" name="num_tarjeta" id="num_tarjeta-ver" class="form-control" disabled required placeholder="Número de tarjeta">
                        <input type="hidden" name="id" id="id-ver" class="form-control" >
                      </div>
                        </div>
                <!-- /.form-group -->
                <div class="col-md-9">
                      <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" name="nom_rotulo" id="nom_rotulo-ver" class="form-control" disabled required placeholder="Nombre" >
                     </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Fecha de apertura:</label>
                        <input type="text" name="fecha_apertura" id="fecha_apertura-ver" class="form-control" disabled required placeholder="Fecha de apertura" >
                     </div>
                    </div>
               
                    <div class="col-md-4">
                     <div class="form-group">
                          <label>Actividad Económica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-10">
                                <select 
                                required
                                disabled
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"  
                                id="select-actividad_economica-ver" 
                                title="-- Selecione actividad económica --"
                                 >
                                 <option value="Valla publicitaria">Valla publicitaria</option>
                                 </select> 
                           </div>
                           <!-- finaliza asignar actividad economica-->
                        </div>
                          </div>

                <div class="col-md-4">
                   <div class="form-group">
                      <label>Permiso de instalación:</label>
                        <!-- Select estado - live search -->
                        <div class="input-group mb-10">
                            <select 
                            required
                            disabled
                            class="form-control" 
                            data-style="btn-success"
                            data-show-subtext="true" 
                            data-live-search="true" 
                            id="select-permiso_instalacion-ver" 
                            title="-- Selecione el tipo de instalación --"
                            >
                            <option value="Temporal">Temporal</option>
                            <option value="Permanente">Permanente</option>
                            </select> 
                        </div>
                           <!-- finaliza asignar actividad economica-->
                        </div>
               </div> 
             </div>

                <div class="row"> 
                   <div class="col-md-6">
                      <div class="form-group">
                      <label>Asignar empresa:</label>
                              <!-- Select live search -->
                              <div class="input-group mb-14">
                                <select 
                                disabled
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"
                                id="select-empresa-ver" 
                                title="-- Seleccione un registro --"
                                
                                >
                                  @foreach($empresas as $empresa)
                                  <option value="{{ $empresa->id }}"> {{ $empresa->nombre }}</option>
                                  @endforeach 
                                </select> 
                                </div>
                           <!-- finaliza select Asignar Representante-->
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                      <label>Asignar representante legal:</label>
                              <!-- Select live search -->
                              <div class="input-group mb-14">
                                <select 
                                required
                                disabled
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"
                                id="select-contribuyente-ver" 
                                title="-- Seleccione un registro --"
                                
                                >
                                  @foreach($contribuyentes as $contribuyente)
                                  <option value="{{ $contribuyente->id }}"> {{ $contribuyente->nombre }}&nbsp;{{ $contribuyente->apellido }}</option>
                                  @endforeach 
                                </select> 
                                </div>
                           <!-- finaliza select Asignar Representante-->
                      </div>
                  </div>
               </div>

               <div class="row">   
               <div class="col-md-10">
                      <div class="form-group">
                        <label>Dirección:</label>
                        <input type="text" name="direccion" id="direccion-ver" class="form-control" required placeholder="Fecha de apertura" >
                     </div>
                    </div>
               </div>

              <div class="row">            
                <div class="col-md-10">
                  <div class="form-group"> 
                  <label for="medidas" class="form-label">Medidas</label>
                  <textarea class="form-control" id="medidas-ver" rows="3" disabled></textarea>
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
  <!--Finaliza Modal ver rótulos -->

<!-- /Modal editar rótulos-->
<div class="modal fade" id="modalEditarRotulos">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-EditarRotulos">
                      <div class="card-body">
                      <div class="card card-green">
                  <div class="card-header">
                      <h3 class="card-title">Editar información de rótulos</h3>

                  <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                  </div>
                  </div>

                <!--inicia los campos del formulario ver-->
                 <!-- /.card-header -->
                 <div class="card-body">
                  <div class="row">
                        <div class="col-md-3">
                        <div class="form-group"> 
                        <label>Número de tarjeta:</label>
                        <input type="number" name="num_tarjeta" id="num_tarjeta-editar" class="form-control" required placeholder="Número de tarjeta">
                        <input type="hidden" name="id" id="id-editar" class="form-control" >
                      </div>
                        </div>
                <!-- /.form-group -->
                <div class="col-md-9">
                      <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" name="nom_rotulo" id="nom_rotulo-editar" class="form-control"  required placeholder="Nombre" >
                     </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Fecha de apertura:</label>
                        <input type="text" name="fecha_apertura" id="fecha_apertura-editar" class="form-control" required placeholder="Fecha de apertura" >
                     </div>
                    </div>
               
                    <div class="col-md-4">
                     <div class="form-group">
                          <label>Actividad Económica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-10">
                                <select 
                                required
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"  
                                id="select-actividad_economica-editar" 
                                title="-- Selecione actividad económica --"
                                 >
                                 <option value="Valla publicitaria">Valla publicitaria</option>
                                 </select> 
                           </div>
                           <!-- finaliza asignar actividad economica-->
                        </div>
                          </div>

                <div class="col-md-4">
                   <div class="form-group">
                      <label>Permiso de instalación:</label>
                        <!-- Select estado - live search -->
                        <div class="input-group mb-10">
                            <select 
                            required
                            class="form-control" 
                            data-style="btn-success"
                            data-show-subtext="true" 
                            data-live-search="true" 
                            id="select-permiso_instalacion-editar" 
                            title="-- Selecione el tipo de instalación --"
                            >
                            <option value="Temporal">Temporal</option>
                            <option value="Permanente">Permanente</option>
                            </select> 
                        </div>
                           <!-- finaliza asignar actividad economica-->
                        </div>
               </div> 
              </div>

              <div class="row"> 
                   <div class="col-md-6">
                      <div class="form-group">
                      <label>Asignar empresa:</label>
                              <!-- Select live search -->
                              <div class="input-group mb-14">
                                <select 
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"
                                id="select-empresa-editar" 
                                title="-- Seleccione un registro --"
                                
                                >
                                  @foreach($empresas as $empresa)
                                  <option value="{{ $empresa->id }}"> {{ $empresa->nombre }}</option>
                                  @endforeach 
                                </select> 
                                </div>
                           <!-- finaliza select Asignar Representante-->
                      </div>
                  </div> 
              </div>

              <div class="row">   
                <div class="col-md-10">
                    <div class="form-group">
                      <label>Dirección:</label>
                      <input type="text" name="direccion" id="direccion-editar" class="form-control" required placeholder="Fecha de apertura" >
                    </div>
                </div>
              </div>

              <div class="row">            
              <div class="col-md-5">
                    <div class="form-group"> 
                    <label for="medidas" class="form-label">Medidas:</label>
                     <textarea class="form-control" id="medidas-editar" rows="2"></textarea>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group"> 
                    <label for="total_medidas" class="form-label">Total metros cuadrados:</label>
                      <input type="number" name="total_medidas-editar" id="total_medidas-editar" class="form-control" required placeholder="Total metros cuadrados" >
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group"> 
                    <label for="total_caras" class="form-label">Caras del rótulo:</label>
                      <input type="text" name="total_caras-editar" id="total_caras-editar" class="form-control" required placeholder="">
                    </div>
                  </div>          
              </div>
                                               
              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-success  float-right" onclick="actualizarRotulo()">Actualizar</button>
              </div>
              <!--finaliza los campos del formulario-->
                     </div>
                     </form>
                    </div>
                     </div>
                  </div>
                </div>
              </div>
            </div>
  <!--Finaliza Modal editar rótulos -->
     
  <!-- Inicia Modal Borrar Rótulo-->

  <div class="modal fade" id="modalEliminarRotulos">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar Rótulo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-BorrarRotulos">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>¿Realmente desea eliminar el rótulo seleccionado?"</p>

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
                    <button type="button" class="btn btn-danger" onclick="borrarRotulo()">Borrar</button>
                </div>
            </div>
        </div>
    </div>
  <!--Finaliza Modal Borrar Rótulo-->


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
 
    <script src="sweetalert2.all.min.js"></script>
    <script src="sweetalert2.min.js"></script>
    
<script type="text/javascript">
        $(document).ready(function(){
            var ruta = "{{ url('/admin/Rotulos/tabla') }}";
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";
        });
</script>

<script type="text/javascript">
      
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });

</script>

<script type="text/javascript">

    function recargar()
    {
        var ruta = "{{ url('/admin/Rotulos/tabla') }}";
            $('#tablaDatatable').load(ruta);
    }

    function VistaRotulo(id)
    {
        openLoading();
        window.location.href="{{ url('/admin/Rotulos/vista') }}/"+id;

    }
    
    function editarRotulos(id)
    {
        document.getElementById("formulario-EditarRotulos").reset();
            $('#modalEditarRotulos').modal('show');
    }

    function verRotulos(id)
    {
        openLoading();
        document.getElementById("formulario-VerRotulos").reset();

          axios.post('/admin/Rotulos/Ver',{
          'id': id
            })
          .then((response) => {
            console.log(response)
          closeLoading();
            if(response.data.success === 1){
            $('#modalVerRotulos').modal('show');

            $('#id-ver').val(response.data.rotulos.id);
            $('#num_tarjeta-ver').val(response.data.rotulos.num_tarjeta);
            $('#nom_rotulo-ver').val(response.data.rotulos.nom_rotulo);
            $('#fecha_apertura-ver').val(response.data.rotulos.fecha_apertura);
            $('#select-actividad_economica-ver').val(response.data.rotulos.actividad_economica);
            $('#direccion-ver').val(response.data.rotulos.direccion);
            $('#medidas-ver').val(response.data.rotulos.medidas);
            $('#select-permiso_instalacion-ver').val(response.data.rotulos.permiso_instalacion);
            
                   
              document.getElementById("select-contribuyente-ver").selectedIndex;
              document.getElementById("select-empresa-ver").selectedIndex;

                $.each(response.data.contribuyente, function( key, val ){
                if(response.data.id_contri == val.id){
                $('#select-contribuyente-ver').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'&nbsp;'+val.apellido+'</option>');
                }else{
                $('#select-contribuyente-ver').append('<option value="' +val.id +'">'+val.nombre+'&nbsp;'+val.apellido+'</option>');
                  }
                });

                $.each(response.data.empresa, function( key, val ){
                if(response.data.id_empre == val.id){
                $('#select-empresa-ver').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'</option>');
                }else{
                $('#select-empresa-ver').append('<option value="' +val.id +'">'+val.nombre+'</option>');
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

    function informacionRotulos(id)
    {
      openLoading();
        document.getElementById("formulario-EditarRotulos").reset();

          axios.post('/admin/Rotulos/Ver',{
          'id': id
            })
          .then((response) => {
            console.log(response)
          closeLoading();
            if(response.data.success === 1){
            $('#modalEditarRotulos').modal('show');

            $('#id-editar').val(response.data.rotulos.id);
            $('#num_tarjeta-editar').val(response.data.rotulos.num_tarjeta);
            $('#nom_rotulo-editar').val(response.data.rotulos.nom_rotulo);
            $('#fecha_apertura-editar').val(response.data.rotulos.fecha_apertura);
            $('#select-actividad_economica-editar').val(response.data.rotulos.actividad_economica);
            $('#direccion-editar').val(response.data.rotulos.direccion);
            $('#medidas-editar').val(response.data.rotulos.medidas);
            $('#total_medidas-editar').val(response.data.rotulos.total_medidas);
            $('#total_caras-editar').val(response.data.rotulos.total_caras);
            $('#select-permiso_instalacion-editar').val(response.data.rotulos.permiso_instalacion);
          
                   
           
            document.getElementById("select-empresa-editar").selectedIndex;

                $.each(response.data.empresa, function( key, val ){
                if(response.data.id_empre == val.id){
                $('#select-empresa-editar').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'</option>');
                }else{
                $('#select-empresa-editar').append('<option value="' +val.id +'">'+val.nombre+'</option>');
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
          
    function actualizarRotulo()        
    {
        var id = document.getElementById('id-editar').value;
        var empresa = document.getElementById('select-empresa-editar').value;
        var nom_rotulo = document.getElementById('nom_rotulo-editar').value;
        var actividad_economica = document.getElementById('select-actividad_economica-editar').value;
        var direccion = document.getElementById('direccion-editar').value;
        var fecha_apertura = document.getElementById('fecha_apertura-editar').value;
        var num_tarjeta = document.getElementById('num_tarjeta-editar').value;
        var permiso_instalacion = document.getElementById('select-permiso_instalacion-editar').value;
        var medidas = document.getElementById('medidas-editar').value;
        var total_medidas = document.getElementById('total_medidas-editar').value;
        var total_caras = document.getElementById('total_caras-editar').value;
       

        openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('empresa', empresa);
            formData.append('nom_rotulo', nom_rotulo);
            formData.append('actividad_economica',actividad_economica);
            formData.append('direccion',direccion);
            formData.append('fecha_apertura',fecha_apertura);
            formData.append('num_tarjeta', num_tarjeta);
            formData.append('permiso_instalacion',permiso_instalacion);
            formData.append('medidas', medidas);
            formData.append('total_medidas', total_medidas);
            formData.append('total_caras', total_caras);
            
            
            axios.post('/admin/Rotulos/Editar', formData, {
            })
            .then((response) => {
              console.log(response)
                closeLoading();
                if (response.data.success === 1) 
                    {
                      Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Datos actualizados correctamente!',
                          showConfirmButton: false,
                          timer: 2000
                        })
                        $('#modalEditarRotulos').modal('hide');
                        recargar();
                    }
                    else 
                    {
                      toastMensaje('Error');
                      $('#modalEditarRotulos').modal('hide');
                         recargar();
                    }
                  })
            .catch((error) => {
              closeLoading();
                toastr.error('Error al actualizar');
             
            });
    }

    function modalEliminar(id)
    {
        $('#idborrar').val(id);
        $('#modalEliminarRotulos').modal('show');
    }


    function borrarRotulo()
    {
      
      openLoading()
      
     // se envia el ID del rótulo
      var id = document.getElementById('idborrar').value;

      var formData = new FormData();
      formData.append('id', id);

            axios.post('/admin/Rotulos/Borrar', formData, {
            })
              .then((response) => {
                closeLoading()
                  $('#modalEliminarRotulos').modal('hide');
                    
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