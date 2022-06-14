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
            <h1>Licencias y matriculas</h1>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Licencias y matriculas</li>
                            </ol>
                        </div>
        </div>
        <br>
        <button type="button"onclick="agregarLicenciaM()" class="btn btn-success btn-sm" >
                <i class="fas fa-pencil-alt"></i>
                Nueva licencia o matricula
            </button>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <!-- CAJA -->
        <form class="form-horizontal" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Licencias y matriculas</h3>

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

<!--Modal para agregar licencia o matriculas-->
<div class="modal fade" id="modalAgregarLM">
        <div class="modal-dialog" style="width:2000px;">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title">Agregar licencia o matrícula</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-AgregarLM"> 
            <div class="row">
              <div class="col-md-10">
              <div class="form-group">
                <label>Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required placeholder="Nombre licencia o matricula">
                        <input type="hidden" name="id" id="id" class="form-control" >
                </div>
              </div>
           
              <div class="col-md-10">
                     <div class="form-group">
                          <label>Tipo de permisos:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-10">
                                <select 
                                required
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-tipo_permiso" 
                                title="-- Selecione el tipo de permiso --"
                                 >
                                 <option value="Licencia">Licencia</option>
                                 <option value="Matrícula">Matrícula</option>
                                </select> 
                           </div>
                           <!-- finaliza asignar actividad economica-->
                        </div>
                          </div>
                <div class="col-md-10">
                <div class="form-group">
                     <label>Monto:</label>
                        <input type="number" name="monto" id="monto" class="form-control" required placeholder="Monto">
                </div>
                </div>
                <!-- /.form-group -->
                <!-- /.form-group -->  
                <div class="col-md-10">
                <div class="form-group">
                     <label>Tarifa:</label>
                        <input type="number"  id="tarifa" class="form-control" required placeholder="Tarifa">
                </div>
                </div>

                <!-- /.form-group -->
                </div>               
                 </div>
                  <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="nuevaLM()"> Guardar </button>
                  <button type="button" data-dismiss="modal" class="btn btn-default"><i class="fas fa-times-circle"></i>Cerrar</button>
                </div>
                   </div>
             
                </div>
                   </div>
                    </div>
                  </div>
                </div>
            </div>
      <!-- /.card -->
          </form>
      <!-- /form -->
       <!--Finaliza Modal para agregar licencia o matriculas-->

<!--Modal para actualizar licencia o matriculas-->
       <div class="modal fade" id="modalEditarLM">
        <div class="modal-dialog" style="width:2000px;">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title">Actualizar licencia o matricula</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-EditarLM"> 
              <div class="row">
               <div class="col-md-10">
                <div class="form-group">
                  <label>Nombre:</label>
                        <input type="text" name="nombre" id="nombre-editar" class="form-control" required placeholder="Nombre licencia o matricula">
                        <input type="hidden" name="id" id="id-editar" class="form-control" >
                </div>
              </div>
              
                <div class="col-md-10">
                     <div class="form-group">
                          <label>Actividad económica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-10">
                                <select 
                                required
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-tipo_permiso-editar" 
                                 >
                                
                                 <option value="Licencia">Licencia</option>
                                 <option value="Matrícula">Matrícula</option>
                               
                                </select>  
                           </div>
                        </div>
                  </div>
                <!-- /.form-group -->  
                   
                <div class="col-md-10">
                <div class="form-group">
                     <label>Monto Permiso:</label>
                        <input type="number" name="monto" id="monto-editar" class="form-control" required placeholder="Monto">
                </div>
                </div>
            
                <!-- /.form-group -->

                <!-- /.form-group -->  
                <div class="col-md-10">
                <div class="form-group">
                     <label>Tarifa:</label>
                        <input type="number" name="tarifa" id="tarifa-editar" class="form-control" required placeholder="Tarifa">
                </div>
                </div>

                <!-- /.form-group -->
                
                </div>    
                <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="actualizarLM()"> Actualizar </button>
                  <button type="button" data-dismiss="modal" class="btn btn-default"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                </div>           
                </div>
                 </div>
                   </div>
                    </div>
                  </div>
                </div>
            </div>
            
      <!-- /.card -->
          </form>
      <!-- /form -->
       <!--Finaliza Modal para actualizar licencia o matriculas-->

       
        <!-- Inicia Modal Borrar licencia o matricula-->

 <div class="modal fade" id="modalEliminarLM">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar Tasa de Interés</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-BorrarLM">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>¿Realmente desea eliminar la siguiente información seleccionada?"</p>

                                    <div class="form-group">
                                        <input type="hidden" id="idborrar">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>Cerrar</button>
                    <button type="button" class="btn btn-danger" onclick="eliminarLM()">Borrar</button>
                </div>
            </div>
        </div>
    </div>

        <!--Finaliza Modal Borrar licencia o matricula-->
     


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
            var ruta = "{{ url('/admin/LicenciaMatricula/tabla') }}";
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
     var ruta = "{{ url('/admin/LicenciaMatricula/tabla') }}";
     $('#tablaDatatable').load(ruta);
    }
        
    function agregarLicenciaM(id)
    {
        document.getElementById("formulario-AgregarLM").reset();
            $('#modalAgregarLM').modal('show');
    }

    function nuevaLM(id)
    {
        
        var nombre = document.getElementById('nombre').value;
        var monto = document.getElementById('monto').value;
        var tarifa = document.getElementById('tarifa').value;
        var tipo_permiso = document.getElementById('select-tipo_permiso').value;
        

        if(nombre === '')
        {
            toastr.error('El nombre de la licencia o matricula es requerido');
            return;
        }

        if(monto === '')
        {
            toastr.error('El monto es requerido');
            return;
        }
        if(tarifa === '')
        {
            toastr.error('La tarifa es requerida');
            return;
        }
        if(tipo_permiso === '')
        {
            toastr.error('El tipo de permiso es requerido');
            return;
        }
     
        openLoading();
      var formData = new FormData();
      formData.append('nombre', nombre);
      formData.append('monto', monto);
      formData.append('tarifa', tarifa);
      formData.append('tipo_permiso', tipo_permiso);

      axios.post('/admin/LicenciaMatricula/Nuevas', formData,
       {
            })

            .then((response) => {
              closeLoading();
          if (response.data.success === 1)
          {
            Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Datos registrados correctamente!',
                          showConfirmButton: false,
                          timer: 2000
                        })
            $('#modalAgregarLM').modal('hide');
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

    function informacionLicenciaM(id)
    {
            openLoading();
            document.getElementById("formulario-EditarLM").reset();

            axios.post('/admin/LicenciaMatricula/informacion',{
                'id': id
            })
                .then((response) => {
                    // console.log(response);
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditarLM').modal('show');

                        $('#id-editar').val(response.data.licencia_matricula.id);
                        $('#nombre-editar').val(response.data.licencia_matricula.nombre);
                        $('#monto-editar').val(response.data.licencia_matricula.monto);
                        $('#tarifa-editar').val(response.data.licencia_matricula.tarifa);


                        if(response.data.licencia_matricula.tipo_permiso == "Licencia"){
                          document.getElementById("select-tipo_permiso-editar").selectedIndex = 0;
                        }else{
                          document.getElementById("select-tipo_permiso-editar").selectedIndex = 1;
                        }

                    }else{
                        toastr.error('La información solicitada no se encuentra');
                    }
                
                })   
           .catch((error) => {
              closeLoading();
              toastr.error('Información no encontrada');
           });
       
    }

    function actualizarLM()
    {
            var id = document.getElementById('id-editar').value;
            var nombre = document.getElementById('nombre-editar').value;
            var monto = document.getElementById('monto-editar').value;
            var tarifa = document.getElementById('tarifa-editar').value;
            var tipo_permiso = document.getElementById('select-tipo_permiso-editar').value;
           
            openLoading()

            var formData = new FormData();
            formData.append('id', id);
            formData.append('nombre', nombre);  
            formData.append('monto', monto);
            formData.append('tarifa', tarifa);
            formData.append('tipo_permiso', tipo_permiso);

            axios.post('/admin/LicenciaMatricula/editar', formData, {
            })

                .then((response) => {
                  ///  console.log(response);
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
                        $('#modalEditarLM').modal('hide');
                        recargar();
                    }
                    else 
                    {
                        toastMensaje('Error al actualizar');
                        $('#modalEditarLM').modal('hide');
                        recargar();
                    }
                })
                .catch((error) => {
                  closeLoading()
                    toastMensaje('error', 'Error');
                });
    }

    function modalEliminarLM(id)
        {
          $('#idborrar').val(id);
          $('#modalEliminarLM').modal('show');
        }

    function eliminarLM()
    {
       openLoading()
        
            // se envia el ID del contribuyente
            var id = document.getElementById('idborrar').value;

            var formData = new FormData();
            formData.append('id', id);

            axios.post('/admin/LicenciaMatricula/eliminar', formData, {
            })
                .then((response) => {
                    closeLoading()
                    $('#modalEliminarLM').modal('hide');
                    
                    if(response.data.success === 1){
                      Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Información eliminada correctamente!',
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