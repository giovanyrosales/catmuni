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
            <h1>Tarifa variable.</h1>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Tarifa variable</li>
                            </ol>
                        </div>
        </div>
        <br>
        <button type="button"onclick="agregarTarifaV()" class="btn btn-success btn-sm" >
                <i class="fas fa-pencil-alt"></i>
                Nueva tarifa variable
            </button>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <!-- CAJA -->
        <form class="form-horizontal" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table"></i>&nbsp;Tabla variable según su giro empresarial</h3>

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

<!-- /Modal ver datos de tarifa variable-->

    <div class="modal fade" id="modalVerTarifaVariable">
        <div class="modal-dialog" style="width:1300px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-donate"></i>&nbsp;Tarifa variable</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-verTarifaVariable">
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
                        <label>Limite superior:</label>
                        <input type="number" name="limite_superior" id="limite_superior-ver" class="form-control" disabled required placeholder="Limite superior" >
                     </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Fijo:</label>
                        <input type="number" name="fijo" id="fijo-ver" class="form-control" disabled required placeholder="Fijo" >
                     </div>
                    </div>
               
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Excedente:</label>
                        <input type="number" name="excedente" id="excedente-ver" class="form-control" disabled required placeholder="Fijo" >
                     </div>
                    </div>
                </div>

                <div class="row"> 
                   <div class="col-md-6">
                     <div class="form-group">
                      <label>Categoria:</label>
                         <input type="number" name="categoria" id="categoria-ver" disabled required placeholder="Categoria" class="form-control" >
                    </div>
                     </div> 
              
                     <div class="col-md-6">
                     <div class="form-group">
                    <label>Millar:</label>
                    <input type="number" name="millar" id="millar-ver" class="form-control" disabled placeholder="Millar"  >
                  </div>
                 </div>
                </div>

                 <div class="row"> 
                <div class="col-md-6">
                     <div class="form-group">
                          <label>Giro empresarial:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-9">
                                <select 
                                required
                                disabled
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-actividad_giroempresarial-ver" 
                                 >
                                  @foreach($giro_empresariales as $dato)
                                  <option value="{{ $dato->id }}"> {{ $dato->nombre_giro_empresarial }}</option>
                                  @endforeach 
                                </select>  
                           </div>
                        </div>
             
                     </div>
             </div>
                           <!-- finaliza asignar actividad economica-->
                        
                      </div>
                   <div>
                    <button type="button" class="btn btn-default float-left" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                    
                    </div>
              <!--finaliza los campos del formulario-->
                     </div>
                     </form>
                    </div>
                     </div>
                  </div>
                </div>
              </div>
      <!--Finaliza Modal ver datos tarifa variable -->


      <!--Modal para agregar tarifa variable-->
      <div class="modal fade" id="modalAgregarTarifaVariable">
        <div class="modal-dialog dtr-modal-content">
        <div class="modal-content">
         <div class="modal-header">
         <h4 class="modal-title"><i class="far fa-plus-square"></i>&nbsp;Agregar tarifa variable</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-AgregarTarifaVariable">
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
                        <label>Limite superior:</label>
                        <input type="number" name="limite_superior" id="limite_superior" class="form-control" required placeholder="Limite superior" >
                     </div>
                    </div>
            </div> 
            <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Fijo:</label>
                        <input type="number" name="fijo" id="fijo" class="form-control" required placeholder="Fijo" >
                     </div>
                    </div>          
          
            <div class="col-md-6">
                     <div class="form-group">
                    <label>Millar:</label>
                    <input type="number" name="millar" id="millar" class="form-control" placeholder="Millar"  >
                  </div>
                 </div>
            </div>

            <div class="row">
                 <div class="col-md-6">
                     <div class="form-group">
                      <label>Categoria:</label>
                         <input type="number" name="categoria" id="categoria" required placeholder="Categoria" class="form-control" >
                    </div>
                     </div> 

                     <div class="col-md-6">
                      <div class="form-group">
                        <label>Excedente:</label>
                        <input type="number" name="excedente" id="excedente" class="form-control" required placeholder="Excedente" >
                     </div>
                    </div>
            </div>              
                <!-- /.form-group -->
                <div class="row"> 
                <div class="col-md-6">
                     <div class="form-group">
                          <label>Giro empresarial:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-6">
                                <select 
                                required
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-giro_empresarial" 
                                title="-- Selecione la actividad --"
                                 >
                                  @foreach($giro_empresariales as $dato)
                                  <option value="{{ $dato->id }}"> {{ $dato->nombre_giro_empresarial }}</option>
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
                  <button type="button" class="btn btn-success float-right" onclick="nuevaTarifaV()"> 
                  <i class="fas fa-save"></i> &nbsp;Guardar </button>
                  <button type="button" data-dismiss="modal" class="btn btn-default">
                  <i class="fas fa-times-circle"></i>&nbsp;Cancelar</button>
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
      
       <!--Finaliza Modal para agregar tarifa variable-->
     
     
    <!-- /Modal editar datos del detalle-->

    <div class="modal fade" id="modalEditarTarifaVariable">
        <div class="modal-dialog" style="width:1300px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="far fa-edit"></i>&nbsp;Actualizar tarifa variable</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-EditarTarifaVariable">
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
                        <label>Limite superior:</label>
                        <input type="number" name="limite_superior" id="limite_superior-editar" class="form-control" required placeholder="Limite superior" >
                      </div>
                     </div>    
                </div>

                <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Fijo:</label>
                        <input type="number" name="fijo" id="fijo-editar" class="form-control" required placeholder="Fijo" >
                      </div>
                     </div>
                  
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Excedente:</label>
                        <input type="number" name="excedente" id="excedente-editar" class="form-control" required placeholder="Excedente" >
                     </div>
                    </div>
              </div> 

              <div class="row">
                   <div class="col-md-6">
                     <div class="form-group">
                      <label>Categoria:</label>
                         <input type="number" name="categoria" id="categoria-editar" required placeholder="Categoria" class="form-control" >
                      </div>
                     </div> 
                   
                   <div class="col-md-6">
                     <div class="form-group">
                    <label>Millar:</label>
                    <input type="number" name="millar" id="millar-editar" class="form-control" placeholder="Millar"  >
                  </div>
                 </div>
              </div>
                <!-- /.form-group -->
                <div class="row">
                <div class="col-md-6">
                     <div class="form-group">
                          <label>Giro empresarial:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-9">
                                <select 
                                required
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-giro_empresarial-editar" 
                                 >
                                  @foreach($giro_empresariales as $dato)
                                  <option value="{{ $dato->id }}"> {{ $dato->nombre_giro_empresarial}}</option>
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
                      <button type="button" class="btn btn-default" data-dismiss="modal">
                      <i class="fas fa-times-circle"></i>&nbsp;Cancelar</button>
                        <button type="button" class="btn btn-success  float-right" onclick="actualizarTarifaV()">
                        <i class="far fa-edit"></i>&nbsp;Actualizar</button>
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
                    <h4 class="modal-title"><i class="far fa-minus-square"></i>&nbsp;Eliminar tarifa variable</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-borrar">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>¿Realmente desea eliminar la tarifa variable?"</p>

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
                    <button type="button" class="btn btn-danger" onclick="eliminarD()">
                    <i class="far fa-trash-alt"></i>&nbsp;Borrar</button>
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
            var ruta = "{{ url('/admin/TarifaVariable/tabla') }}";
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
     var ruta = "{{ url('/admin/TarifaVariable/tabla') }}";
     $('#tablaDatatable').load(ruta);
    }

    function agregarTarifaV(id)
    {
        document.getElementById("formulario-AgregarTarifaVariable").reset();
            $('#modalAgregarTarifaVariable').modal('show');
    }

   function editarTarifaV(id)
    {
        document.getElementById("formulario-EditarTarifaVariable").reset();
            $('#modalEditarTarifaVariable').modal('show');
    }

    function informacionTarifa(id)
    {
         openLoading();
           document.getElementById("formulario-EditarTarifaVariable").reset();

            axios.post('/admin/TarifaVariable/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditarTarifaVariable').modal('show');

                        $('#id-editar').val(response.data.tarifa_variable.id);
                        $('#limite_inferior-editar').val(response.data.tarifa_variable.limite_inferior);
                        $('#limite_superior-editar').val(response.data.tarifa_variable.limite_superior);
                        $('#fijo-editar').val(response.data.tarifa_variable.fijo);
                        $('#excedente-editar').val(response.data.tarifa_variable.excedente);
                        $('#categoria-editar').val(response.data.tarifa_variable.categoria);
                        $('#millar-editar').val(response.data.tarifa_variable.millar);
                        //$('#actividad_economica-ver').val(response.data.actividad_economica.actividad_economica);
                     
                        document.getElementById("select-giro_empresarial-editar").options.length = 0;
                        $.each(response.data.giro_empresariales, function( key, val ){
                            if(response.data.idact_gico == val.id){
                                $('#select-giro_empresarial-editar').append('<option value="' +val.id +'" selected="selected">'+val.nombre_giro_empresarial+'</option>');
                            }else{
                                $('#select-giro_empresarial-editar').append('<option value="' +val.id +'">'+val.nombre_giro_empresarial+'</option>');
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
          
    function actualizarTarifaV()        
    {
        var id = document.getElementById('id-editar').value;
        var giro_empresarial = document.getElementById('select-giro_empresarial-editar').value;
        var limite_inferior = document.getElementById('limite_inferior-editar').value;
        var limite_superior = document.getElementById('limite_superior-editar').value;
        var fijo = document.getElementById('fijo-editar').value;
        var excedente = document.getElementById('excedente-editar').value;
        var categoria = document.getElementById('categoria-editar').value;
        var millar = document.getElementById('millar-editar').value;

        openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('giro_empresarial', giro_empresarial);
            formData.append('limite_inferior', limite_inferior);
            formData.append('limite_superior', limite_superior);
            formData.append('fijo',fijo);
            formData.append('excedente',excedente);
            formData.append('categoria',categoria);
            formData.append('millar',millar)
            
            axios.post('/admin/TarifaVariable/editar', formData, {
            })
            .then((response) => {
                closeLoading();
                if (response.data.success === 1) 
                    {
                      Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Tarifa Variable actualizada correctamente!',
                          showConfirmButton: false,
                          timer: 2000
                        })
                        $('#modalEditarTarifaVariable').modal('hide');
                        recargar();
                    }
                    else 
                    {
                      toastMensaje('Error');
                      $('#modalEditarTarifaVariable').modal('hide');
                         recargar();
                    }
                  })
            .catch((error) => {
              closeLoading();
                toastr.error('Error al actualizar');
             
            });
    }

    
    function verTarifaV(id){
             openLoading();
             document.getElementById("formulario-verTarifaVariable").reset();

            axios.post('/admin/TarifaVariable/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalVerTarifaVariable').modal('show');

                        $('#id-ver').val(response.data.tarifa_variable.id);
                        $('#limite_inferior-ver').val(response.data.tarifa_variable.limite_inferior);
                        $('#limite_superior-ver').val(response.data.tarifa_variable.limite_superior);
                        $('#fijo-ver').val(response.data.tarifa_variable.fijo);
                        $('#excedente-ver').val(response.data.tarifa_variable.excedente);
                        $('#categoria-ver').val(response.data.tarifa_variable.categoria);
                        $('#millar-ver').val(response.data.tarifa_variable.millar);
                        //$('#actividad_economica-ver').val(response.data.actividad_economica.actividad_economica);
             
                        document.getElementById("select-actividad_giroempresarial-ver").selectedIndex;


                        $.each(response.data.actividad_economica, function( key, val ){
                            if(response.data.idact_eco == val.id){
                                $('#select-actividad_giroempresarial-ver').append('<option value="' +val.id +'" selected="selected">'+val.rubro+'</option>');
                            }else{
                                $('#select-actividad_giroempresarial-ver').append('<option value="' +val.id +'">'+val.rubro+'</option>');
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

            axios.post('/admin/TarifaVariable/eliminar_detalles', formData, {
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

      

        function nuevaTarifaV(id)
      {
        
        var giro_empresarial = document.getElementById('select-giro_empresarial').value;
        var limite_inferior = document.getElementById('limite_inferior').value;
        var limite_superior = document.getElementById('limite_superior').value;
        var fijo = document.getElementById('fijo').value;
        var excedente = document.getElementById('excedente').value;
        var categoria = document.getElementById('categoria').value;
        var millar = document.getElementById('millar').value;

        if(limite_inferior === ''){
            toastr.error('El limite inferior es requerido');
            return;
        }
        
        if(limite_superior === ''){
            toastr.error('El limite superior es requerido');
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

        if(giro_empresarial === ''){
            toastr.error('El Giro empresarial es requerido');
            return;
        }

        openLoading();
      var formData = new FormData();
      formData.append('giro_empresarial', giro_empresarial);
      formData.append('limite_inferior', limite_inferior);
      formData.append('limite_superior', limite_superior);
      formData.append('fijo', fijo);
      formData.append('excedente', excedente);
      formData.append('categoria', categoria);
      formData.append('millar', millar);

      axios.post('/admin/TarifaVariable/Detalle-Act', formData,
       {
            })

            .then((response) => {
              closeLoading();
          if (response.data.success === 1)
          {
            Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: '¡Tarifa Variable agregada correctamente!',
                    showConfirmButton: false,
                    timer: 2000
                        })
            $('#modalAgregarTarifaVariable').modal('hide');
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