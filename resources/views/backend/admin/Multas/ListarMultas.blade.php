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
            <h1>Multas</h1>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Multas</li>
                            </ol>
                        </div>
        </div>
        <br>
        <button type="button"onclick="agregarMultas()" class="btn btn-success btn-sm" >
                <i class="fas fa-pencil-alt"></i>
                Nueva multa
            </button>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <!-- CAJA -->
        <form class="form-horizontal" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i>&nbsp;Lista de tipos de multas</h3>

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

<!--Modal para agregar multas-->
    <div class="modal fade" id="modalAgregarMultas">
        <div class="modal-dialog" style="width:2000px;">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title"><i class="far fa-plus-square"></i>&nbsp;Agregar multas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-AgregarMultas"> 
            <div class="row">
              <div class="col-md-12">
              <div class="form-group">
                <label>Código:</label>
                        <input type="number" name="codigo" id="codigo" class="form-control" required placeholder="Código">
                        <input type="hidden" name="id" id="id" class="form-control" >
                </div>
              </div>
                         
              <div class="col-md-12">
                     <div class="form-group">
                          <label>Tipo de multas:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-12">
                                <select 
                                required
                                
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-tipo_multa" 
                                title="-- Selecione el tipo de multa --"
                                 >
                                 <option value="Ingresos">Ingresos</option>
                                 <option value="Egresos">Egresos</option>
                                </select> 
                           </div>
                           <!-- finaliza asignar actividad economica-->
                        </div>
                          </div>
                <div class="col-md-12">
                <div class="form-group">
                     <label>Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required placeholder="Nombre">
                </div>
                </div>
                <!-- /.form-group -->
                </div>               
                 </div>
                  <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="nuevaM()"> 
                  <i class="fas fa-save"></i> &nbsp;Guardar </button>
                  <button type="button" data-dismiss="modal" class="btn btn-default">
                  <i class="fas fa-times-circle"></i>&nbsp;Cancelar</button>
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
<!--Finaliza Modal para agregar multas-->

<!--Modal para actualizar multas-->
<div class="modal fade" id="modalEditarMultas">
        <div class="modal-dialog" style="width:2000px;">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title"><i class="far fa-edit"></i>&nbsp;Actualizar multas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-EditarMultas"> 
            <div class="row">
              <div class="col-md-12">
              <div class="form-group">
                <label>Código:</label>
                        <input type="number" name="codigo" id="codigo-editar" class="form-control" required placeholder="Nombre licencia o matricula">
                        <input type="hidden" name="id" id="id-editar" class="form-control" >
                </div>
              </div>
              
                <div class="col-md-12">
                     <div class="form-group">
                          <label>Tipo de multas:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-12">
                                <select 
                                required
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-tipo_multa-editar" 
                                 >
                                
                                 <option value="Ingresos">Ingresos</option>
                                 <option value="Egresos">Egresos</option>
                               
                                </select>  
                           </div>
                        </div>
                <!-- /.form-group -->  
                     </div>
                     <div class="col-md-12">
                <div class="form-group">
                     <label>Nombre:</label>
                        <input type="text" name="nombre" id="nombre-editar" class="form-control" required placeholder="Monto">
                </div>
                </div>
              </div>
                <!-- /.form-group -->
                
                </div>    
                <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="actualizarMulta()">
                  <i class="far fa-edit"></i>&nbsp; Actualizar </button>
                  <button type="button" data-dismiss="modal" class="btn btn-default">
                  <i class="fas fa-times-circle"></i>&nbsp;Cancelar</button>
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
       <!--Finaliza Modal para actualizar multas-->

       
        <!-- Inicia Modal Borrar multas-->

 <div class="modal fade" id="modalEliminarMultas">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="far fa-minus-square"></i>&nbsp;Eliminar Multa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-BorrarMultas">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fas fa-times-circle"></i>&nbsp;Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="eliminarMulta()">
                    <i class="far fa-trash-alt"></i>&nbsp;Borrar</button>
                </div>
            </div>
        </div>
    </div>

        <!--Finaliza Modal Borrar multas-->

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
            var ruta = "{{ url('/admin/Multas/tabla') }}";
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
     var ruta = "{{ url('/admin/Multas/tabla') }}";
     $('#tablaDatatable').load(ruta);
    }

        
    function agregarMultas(id)
    {
        document.getElementById("formulario-AgregarMultas").reset();
            $('#modalAgregarMultas').modal('show');
    }

    
    function nuevaM(id)
    {
        
        var codigo = document.getElementById('codigo').value;
        var tipo_multa = document.getElementById('select-tipo_multa').value;
        var nombre = document.getElementById('nombre').value;
        

        if(codigo === '')
        {
            toastr.error('El código es requerido');
            return;
        }

        if(tipo_multa === '')
        {
            toastr.error('El tipo de multa es requerido');
            return;
        }

        
        if(nombre === '')
        {
            toastr.error('El nombre es requerido');
            return;
        }
     
        openLoading();
      var formData = new FormData();
      formData.append('codigo', codigo);
      formData.append('tipo_multa', tipo_multa);
      formData.append('nombre', nombre);

      axios.post(url+'/Multas/NuevaM', formData,
       {
            })

            .then((response) => {
              closeLoading();
          if (response.data.success === 1)
          {
            Swal.fire({
                  position: 'top-end',
                  icon: 'success',
                  title: '¡Multa registrada correctamente!',
                  showConfirmButton: false,
                  timer: 2000
                        })
            $('#modalAgregarMultas').modal('hide');
            location.reload();
            //recargar();
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

    function informacionMultas(id)
    {
            openLoading();
            document.getElementById("formulario-EditarMultas").reset();

            axios.post(url+'/Multas/informacion',{
                'id': id
            })
                .then((response) => {
                    // console.log(response);
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditarMultas').modal('show');

                        $('#id-editar').val(response.data.multas.id);
                        $('#codigo-editar').val(response.data.multas.codigo);
                        $('#select-tipo_multa-editar').val(response.data.multas.tipo_multa);
                        $('#nombre-editar').val(response.data.multas.nombre);


                    }else{
                        toastr.error('La información solicitada no se encuentra');
                    }
                
                })   
           .catch((error) => {
              closeLoading();
              toastr.error('Información no encontrada');
           });
       
    }

    function actualizarMulta()
    {
            var id = document.getElementById('id-editar').value;
            var codigo = document.getElementById('codigo-editar').value;
            var tipo_multa = document.getElementById('select-tipo_multa-editar').value;
            var nombre = document.getElementById('nombre-editar').value;
           
            openLoading()

            var formData = new FormData();
            formData.append('id', id);
            formData.append('codigo', codigo);  
            formData.append('tipo_multa', tipo_multa);
            formData.append('nombre', nombre);

            axios.post(url+'/Multas/editar', formData, {
            })

                .then((response) => {
                  ///  console.log(response);
                    closeLoading()

                   if (response.data.success === 1) 
                    {
                      Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Multa actualizada correctamente!',
                          showConfirmButton: false,
                          timer: 2000
                        })
                      $('#modalEditarMultas').modal('hide');
                        recargar();
                    }
                    else 
                    {
                        toastMensaje('Error al actualizar');
                        $('#modalEditarMultas').modal('hide');
                        recargar();
                    }
                })
                .catch((error) => {
                  closeLoading()
                    toastMensaje('error', 'Error');
                });
    }

    function modalEliminarMulta(id)
        {
          $('#idborrar').val(id);
          $('#modalEliminarMultas').modal('show');
        }

    function eliminarMulta()
    {
       openLoading()
        
            // se envia el ID del contribuyente
            var id = document.getElementById('idborrar').value;

            var formData = new FormData();
            formData.append('id', id);

            axios.post(url+'/Multas/eliminar', formData, {
            })
                .then((response) => {
                    closeLoading()
                    $('#modalEliminarMultas').modal('hide');
                    
                    if(response.data.success === 1){
                      Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Multa eliminada correctamente!',
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