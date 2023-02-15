@extends('backend.menus.superior')

@section('content-admin-css')
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
            <h1> </h1>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Listado de contribuyentes</li>
                            </ol>
                        </div>
        </div>
        <br>
            <button type="button" class="btn btn-success btn-sm" onclick="location.href='{{ url('/admin/nuevo/contribuyentes/Crear') }}'">
                <i class="fas fa-pencil-alt"></i>
                Nuevo Contribuyente
            </button>
      </div>
    </section>
    <section class="content">
    <div class="container-fluid">
        <!-- CAJA -->
        <form class="form-horizontal" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title"> <i class="fas fa-th-list"></i> &nbsp;Lista de contribuyentes</h3>

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

            <!-- Aqui termina-->
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

 <!-- /Modal Actualizar contribuyente-->

    <div class="modal fade" id="modalEditar">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="far fa-edit"></i>&nbsp;Actualizar Contribuyente</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-editar">
                        <div class="card-body">
                        <div class="card card-green">
                        <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-server"></i> &nbsp;Datos del Contribuyente</h3>

                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                          <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                      </div>     
                <!--inicia los campos del formulario-->
                 <!-- /.card-header -->
                 <div class="card-body">
                  <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                         <label>Nombre:</label>
                        <input type="text" name="nombre" id="nombre-editar" class="form-control" required placeholder="Nombre del propietario">
                        <input type="hidden" name="id" id="id-editar"  >
                      </div>
                <!-- /.form-group -->
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                          <label>NIT:</label>
                          <input type="number" name="nit" id="nit-editar" class="form-control" required placeholder="0000-000000-000-0" >
                  </div></div>
                <div class="col-md-6">
                  <div class="form-group">
                          <label>DUI:</label>
                          <input type="number" name="dui" id="dui-editar" required placeholder="00000000-0" class="form-control" >
                      </div>
                  </div>
                </div>
                <!-- /.form-group -->
                <div class="form-group">
                    <label>Dirección:</label>
                    <input type="text" name="direccion" id="direccion-editar" class="form-control" placeholder="Dirección de la empresa">
                  </div>
                <!-- /.form-group --> 
                <div class="form-group">
                    <label>Correo Electrónico:</label>
                    <input type="email" name="email" id="email-editar" class="form-control" placeholder="Correo@dominio.com"  >
                  </div>
                <!-- /.form-group -->  
              </div>
                <!-- Inicia Segunda Columna de campos-->
              <!-- /.col -->
              <div class="col-md-5">
              <div class="form-group">
                        <label>Apellido:</label>
                        <input type="text" name="apellido" id="apellido-editar" class="form-control" required placeholder="Apellido del propietario" >
                      </div>
                <!-- /.form-group -->
                <div class="form-group">
                <label>Registro de Comerciante:</label>
                          <input type="number" name="registro_comerciante-editar" id="registro_comerciante-editar"  class="form-control" required placeholder="0000000"  >
                      </div>
                <!-- /.form-group -->
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Teléfono:</label>
                          <input type="number" name="telefono" id="telefono-editar" class="form-control" required placeholder="7777-7777">
                  </div></div>
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Fax:</label>
                          <input type="number" name="fax" id="fax-editar" required placeholder="0000-0000" class="form-control" >
                     </div>
                   </div>
                  </div>
                </div>
             </div>
            
              <!--finaliza los campos del formulario-->
                     </div>
                     </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">
                      <i class="fas fa-times-circle"></i>&nbsp;Cancelar</button>
                        <button type="button" class="btn btn-success  float-right" onclick="actualizar()">
                        <i class="fas fa-save"></i>&nbsp;Actualizar</button>
                    </div>
                   </div>
                  </div>
                </div>
              </div>
           </div>

        <!--Finaliza Modal Actualizar contribuyente -->

        <!-- /Modal ver datos del contribuyente-->

    <div class="modal fade" id="modalVer">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-server"></i> &nbsp;Datos del contribuyente</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-ver">         
                <!--inicia los campos del formulario ver-->
                 <!-- /.card-header -->
                 <div class="card-body">
                  <div class="row">
                        <div class="col-md-6">
                        <div class="form-group"> 
                         <label>Nombre:</label>
                        <input type="text" name="nombre" id="nombre-ver" disabled class="form-control" >
                        <input type="hidden" name="id" id="id-ver"  >
                      </div>
                <!-- /.form-group -->
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                          <label>NIT:</label>
                          <input type="number" name="nit" id="nit-ver" disabled class="form-control" >
                  </div></div>
                <div class="col-md-6">
                  <div class="form-group">
                          <label>DUI:</label>
                          <input type="number" name="dui" id="dui-ver" disabled class="form-control" >
                     </div>
                    </div>
                  </div>
                <!-- /.form-group -->
                <div class="form-group">
                    <label>Dirección:</label>
                    <input type="text" name="direccion" id="direccion-ver" disabled class="form-control">
                  </div>
                <!-- /.form-group --> 
                <div class="form-group">
                    <label>Correo Electrónico:</label>
                    <input type="email" name="email" id="email-ver" disabled class="form-control" >
                  </div>
                <!-- /.form-group -->  
              </div>
                <!-- Inicia Segunda Columna de campos-->
              <!-- /.col -->
              <div class="col-md-5">
              <div class="form-group">
                        <label>Apellido:</label>
                        <input type="text" name="apellido" id="apellido-ver" disabled class="form-control" >
                      </div>
                <!-- /.form-group -->
                <div class="form-group">
                <label>Registro de Comerciante:</label>
                    <input type="number" name="registro_comerciante-ver" id="registro_comerciante-ver" disabled required class="form-control" >
                      </div>
                <!-- /.form-group -->
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Teléfono:</label>
                          <input type="number" name="telefono" id="telefono-ver" disabled class="form-control" required >
                  </div></div>
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Fax:</label>
                          <input type="number" name="fax" id="fax-ver" disabled required class="form-control" >
                  </div>
                </div>
             </div>
            
              <!--finaliza los campos del formulario-->
                     </div>
                     </form>
                    </div>
                    <div class="card-footer">
                         <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i> &nbsp;Cerrar</button>
                    </div>
                   </div>
                  </div>
                </div>
              </div>
           </div>
           

        <!--Finaliza Modal ver datos del contribuyente -->

 <!-- Inicia Modal Borrar Contribuyente-->

 <div class="modal fade" id="modalEliminar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="far fa-trash-alt"></i>&nbsp;Eliminar Contribuyente</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-borrar">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>¿Realmente desea eliminar el contribuyente seleccionado?"</p>

                                    <div class="form-group">
                                        <input type="hidden" id="idborrar">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fas fa-times-circle"></i> &nbsp;Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="eliminarC()">
                    <i class="fas fa-trash-alt"></i> &nbsp;Borrar</button>
                </div>
            </div>
        </div>
    </div>

        <!--Finaliza Modal Borrar Contribuyente-->
</div>
</div>
</div>
</div>

<!--Termina Contenido Frame Principal -->
@extends('backend.menus.footerjs')
@section('archivos-js')
    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}" type="text/javascript"></script>


 <!-- incluir tabla -->
 <script type="text/javascript">
        $(document).ready(function(){
            var ruta = "{{ url('/admin/contribuyentes/tabla') }}";
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
        var ruta = "{{ url('/admin/contribuyentes/tabla') }}";
            $('#tablaDatatable').load(ruta);
    }

        function editarContribuyentes(id){
        document.getElementById("formulario-editar").reset();
            $('#modalEditar').modal('show');
        }

       function informacionContribuyentes(id){
                  openLoading();
                 document.getElementById("formulario-editar").reset();

            axios.post(url+'/Contribuyentes/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditar').modal('show');

                        $('#id-editar').val(response.data.contribuyente.id);
                        $('#nombre-editar').val(response.data.contribuyente.nombre);
                        $('#apellido-editar').val(response.data.contribuyente.apellido);
                        $('#dui-editar').val(response.data.contribuyente.dui);
                        $('#nit-editar').val(response.data.contribuyente.nit);
                        $('#registro_comerciante-editar').val(response.data.contribuyente.registro_comerciante);
                        $('#direccion-editar').val(response.data.contribuyente.direccion);
                        $('#telefono-editar').val(response.data.contribuyente.telefono);
                        $('#email-editar').val(response.data.contribuyente.email);
                        $('#fax-editar').val(response.data.contribuyente.fax);
                    }else{
                        toastr.error('Información no encontrada');
                    }
                    
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
       
            }

            function actualizar()
            {
                 var id = document.getElementById('id-editar').value;
                 var nombre = document.getElementById('nombre-editar').value;
                 var apellido = document.getElementById('apellido-editar').value;
                 var dui = document.getElementById('dui-editar').value;
                 var nit = document.getElementById('nit-editar').value;
                 var registro_comerciante = document.getElementById('registro_comerciante-editar').value;
                 var direccion = document.getElementById('direccion-editar').value;
                 var telefono = document.getElementById('telefono-editar').value;
                 var email = document.getElementById('email-editar').value;
                 var fax = document.getElementById('fax-editar').value;

            openLoading()

                var formData = new FormData();
                formData.append('id', id);
                formData.append('nombre', nombre);
                formData.append('apellido', apellido);
                formData.append('dui', dui);
                formData.append('nit', nit);
                formData.append('registro_comerciante', registro_comerciante);
                formData.append('direccion', direccion);
                formData.append('telefono', telefono);
                formData.append('email', email);
                formData.append('fax', fax);
         
            axios.post(url+'/Contribuyentes/editar', formData, {
            })

                .then((response) => {
                  ///  console.log(response);
                    closeLoading()

                   if (response.data.success === 1) 
                   
                    {
                      Swal.fire({
                          icon: 'success',
                          title: '¡Contribuyente actualizado correctamente!',
                          showConfirmButton: true,
                      }).then((result) => {
                        if (result.isConfirmed) {

                                $('#modalEditar').modal('hide');
                                 recargar();
                        }
                      });
                                
                    }
                    else 
                    {
                        Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: '¡Error al actualizar!', 
                        })
                        $('#modalEditar').modal('hide');
                               recargar();
                    }
                })
                .catch((error) => {
                    closeLoading()
                    toastMensaje('error', 'Error');
                });
        }
 
    //se recibe el ID del contribuyente a eliminar

            function modalEliminar(id)
            {
                $('#idborrar').val(id);
                $('#modalEliminar').modal('show');
            }

        function eliminarC(){
            openLoading()
        
            // se envia el ID del contribuyente
            var id = document.getElementById('idborrar').value;

            var formData = new FormData();
            formData.append('id', id);

            axios.post(url+'/Contribuyentes/eliminar_contribuyentes', formData, {
            })
                .then((response) => {
                    closeLoading()
                    $('#modalEliminar').modal('hide');
                    
                    if(response.data.success === 1){
                      Swal.fire({
                          icon: 'success',
                          title: '¡Contribuyente eliminado correctamente!',
                          showConfirmButton: true,
                      }).then((result) => {
                        if (result.isConfirmed) {
 
                                 recargar();
                        }
                      });
                    }else if(response.data.success===2){
                       
                      Swal.fire({
                          icon: 'error',
                          title: 'Petición denegada!',
                          text: 'El contribuyente tiene obligaciones tributarias y no se puede eliminar mientras no se desligue de ellas.', 
                          showConfirmButton: true,
                        }).then((result) => {
                        if (result.isConfirmed) 
                        {
                          closeLoading();
                        }
                      });
                    }
                })
                
                .catch(function (error) {
                        closeLoading()
                        toastr.error("Error de Servidor!");
                      }); 
        }

//funcion para ver la informacion del contribuyente

        function verContribuyentes(id){
                  openLoading();
                 document.getElementById("formulario-ver").reset();

            axios.post(url+'/Contribuyentes/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalVer').modal('show');

                        $('#id-ver').val(response.data.contribuyente.id);
                        $('#nombre-ver').val(response.data.contribuyente.nombre);
                        $('#apellido-ver').val(response.data.contribuyente.apellido);
                        $('#dui-ver').val(response.data.contribuyente.dui);
                        $('#nit-ver').val(response.data.contribuyente.nit);
                        $('#registro_comerciante-ver').val(response.data.contribuyente.registro_comerciante);
                        $('#direccion-ver').val(response.data.contribuyente.direccion);
                        $('#telefono-ver').val(response.data.contribuyente.telefono);
                        $('#email-ver').val(response.data.contribuyente.email);
                        $('#fax-ver').val(response.data.contribuyente.fax);
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
@stop