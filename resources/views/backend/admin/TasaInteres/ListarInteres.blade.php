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
            <h1>Lista Tasas de Interés.</h1>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Tasas de interés</li>
                            </ol>
                        </div>
        </div>
        <br>
        <button type="button"onclick="agregarInteres()" class="btn btn-success btn-sm" >
                <i class="fas fa-pencil-alt"></i>
                Nueva tasa de interés
            </button>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <!-- CAJA -->
        <form class="form-horizontal" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Tasa de interés</h3>

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

<!--Modal para agregar interes-->
      <div class="modal fade" id="modalAgregarInteres">
        <div class="modal-dialog" style="width:2000px;">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title">Agregar Interés</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-AgregarInteres"> 
            <div class="row">
              <div class="col-md-10">
              <div class="form-group">
                     <label>Fecha de inicio:</label>
                        <input type="date" id="fecha_inicio" class="form-control" required placeholder="Fecha de inicio">
                      </div>
              
                     <div class="form-group">
                     <label>Monto interés:</label>
                        <input type="number" name="monto_interes" id="monto_interes" class="form-control" required placeholder="Monto">
                        <input type="hidden" name="id" id="id" class="form-control" >
                      </div>

                    <div class="form-group">
                     <label>Fecha de expiración:</label>
                        <input type="date" id="fecha_fin" class="form-control" required placeholder="Fecha de expiración">
                      </div>
                <!-- /.form-group -->
                </div>               
                 </div>
                   </div>
              <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="nuevoInteres()"> Guardar </button>
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">Cancelar</button>
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
       <!--Finaliza Modal para agregar interes-->

       <!--Modal para editar interes-->
      <div class="modal fade" id="modalEditarInteres">
        <div class="modal-dialog" style="width:2000px;">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title">Editar Interés</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-EditarInteres"> 
            <div class="row">
              <div class="col-md-10">
              <div class="form-group">
                     <label>Fecha de inicio:</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio-editar" class="form-control" required placeholder="Fecha de inicio">
                    </div>

              <div class="form-group">
                     <label>Monto interés:</label>
                        <input type="number" name="monto_interes" id="monto_interes-editar" class="form-control" required placeholder="Monto">
                        <input type="hidden" name="id" id="id-editar" class="form-control" >
                      </div>

              <div class="form-group">
                     <label>Fecha de expiración:</label>
                        <input type="date" name="fecha_fin" id="fecha_fin-editar" class="form-control" required placeholder="Fecha de inicio">
                     </div>
                <!-- /.form-group -->
                </div>               
                 </div>
                   </div>
              <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="actualizarInteres()"> Guardar </button>
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">Cancelar</button>
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
       <!--Finaliza Modal para editar interes-->

        <!-- Inicia Modal Borrar Interes-->

 <div class="modal fade" id="modalEliminarInteres">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar Tasa de Interés</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-BorrarInteres">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>¿Realmente desea eliminar el interés seleccionado?"</p>

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
                    <button type="button" class="btn btn-danger" onclick="eliminarTasa()">Borrar</button>
                </div>
            </div>
        </div>
    </div>

        <!--Finaliza Modal Borrar Interes-->
     

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
            var ruta = "{{ url('/admin/TasaInteres/tabla') }}";
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
     var ruta = "{{ url('/admin/TasaInteres/tabla') }}";
     $('#tablaDatatable').load(ruta);
    }

    function agregarInteres(id)
    {
        document.getElementById("formulario-AgregarInteres").reset();
            $('#modalAgregarInteres').modal('show');
    }

    function nuevoInteres(id)
    {
        var fecha_inicio = document.getElementById('fecha_inicio').value;
        var monto_interes = document.getElementById('monto_interes').value;
        var fecha_fin = document.getElementById('fecha_fin').value;

        if(fecha_inicio === '')
        {
            toastr.error('La fecha inicio es requerida');
            return;
        }
        if(monto_interes === '')
        {
            toastr.error('El Monto es requerido');
            return;
        }
        if(fecha_fin === '')
        {
            toastr.error('La fecha de expiracion es requerida');
            return;
        }
     
        openLoading();
      var formData = new FormData();
      formData.append('fecha_inicio', fecha_inicio);
      formData.append('monto_interes', monto_interes);
      formData.append('fecha_fin', fecha_fin);
      

      axios.post('/admin/nuevo/TasaInteres/nuevo', formData,
       {
            })

            .then((response) => {
              closeLoading();
          if (response.data.success === 1)
          {
            toastr.success('Guardado exitosamente');
            $('#modalAgregarInteres').modal('hide');
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

    function informacionTasas(id)
    {
            openLoading();
            document.getElementById("formulario-EditarInteres").reset();

            axios.post('/admin/TasaInteres/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditarInteres').modal('show');

                        $('#id-editar').val(response.data.interes.id);
                        $('#fecha_inicio-editar').val(response.data.interes.fecha_inicio);
                        $('#monto_interes-editar').val(response.data.interes.monto_interes);
                        $('#fecha_fin-editar').val(response.data.interes.fecha_fin);

                    }else{
                        toastr.error('Información no encontrada');
                    }
                    
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
       
    }

    function actualizarInteres()
    {
            var id = document.getElementById('id-editar').value;
            var fecha_inicio = document.getElementById('fecha_inicio-editar').value;
            var monto_interes = document.getElementById('monto_interes-editar').value;
            var fecha_fin = document.getElementById('fecha_fin-editar').value;
           
            openLoading()

            var formData = new FormData();
            formData.append('id', id);
            formData.append('fecha_inicio', fecha_inicio);
            formData.append('monto_interes', monto_interes);
            formData.append('fecha_fin', fecha_fin);  
            
            axios.post('/admin/TasaInteres/editar', formData, {
            })

                .then((response) => {
                  ///  console.log(response);
                    closeLoading()

                   if (response.data.success === 1) 
                   
                    {
                        toastr.success('Interés actualizado');
                        $('#modalEditarInteres').modal('hide');
                        recargar();
                    }
                    else 
                    {
                        toastMensaje('Error al actualizar');
                        $('#modalEditarInteres').modal('hide');
                        recargar();
                    }
                })
                .catch((error) => {
                    closeLoading()
                    toastMensaje('error', 'Error');
                });
    }

    function modalEliminarInteres(id)
            {
                $('#idborrar').val(id);
                $('#modalEliminarInteres').modal('show');
            }

    function eliminarTasa()
    {
       openLoading()
        
            // se envia el ID del contribuyente
            var id = document.getElementById('idborrar').value;

            var formData = new FormData();
            formData.append('id', id);

            axios.post('/admin/TasaInteres/eliminar', formData, {
            })
                .then((response) => {
                    closeLoading()
                    $('#modalEliminarInteres').modal('hide');
                    
                    if(response.data.success === 1){
                        toastMensaje('success', 'Interes eliminado');
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