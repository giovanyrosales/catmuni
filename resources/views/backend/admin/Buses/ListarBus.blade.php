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
            <h5>BUSES REGISTRADOS</h5>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Listado de buses</li>
                            </ol>
                        </div>
        </div>
        <br>
        <button type="button"onclick="location.href='{{ url('/admin/nuevo/bus/Agregar') }}'" class="btn btn-success btn-sm" >
                <i class="fas fa-pencil-alt"></i>
                Nuevo bus
            </button>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <!-- CAJA -->
        <form class="form-horizontal" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Listado Buses Agregados</h3>

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
</div><!--Termina Contenido Frame Principal -->


<!-- /Modal editar Buses-->
<div class="modal fade" id="modalEditarBus">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form id="formulario-EditarBus">
                      <div class="card-body">
                      <div class="card card-green">
                   
          <div class="card-header">
            <h3 class="card-title">Actualizar Buses</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>

          <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
        <div class="card-header text-info"><label>I. DATOS DEL RÓTULO</label></div>
        <div class="card-body"><!-- Card-body -->

        <div class="card-body">
            <div class="row">
              <!-- /.form-group -->
                   <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>FECHA DE APERTURA:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date" value=" "  id="fecha_inicio-editar" class="form-control" required >
                    <input type="text" hidden value=""  id="id-editar" class="form-control" required >        
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

           <!-- /.form-group -->
           <div class="col-md-3">
                <div class="form-group">
                    <label>NOMBRE DEL BUS:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Nombre de Rótulo -->
            <div class="col-md-3">
                <div class="form-group">  
                   <input type="text"  id="nom_bus-editar" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Nombre del Rótulo-->
            <!-- /.form-group -->
            <div class="col-md-3">
                    <div class="form-group">
                        <label>PLACA:</label>
                    </div>
                </div><!-- /.col-md-6 -->
                <!-- Inicia Nombre de Rótulo -->
                <div class="col-md-3">
                <div class="form-group">  
                   <input type="text"  name="" id="placa-editar" class="form-control" required >
                </div>
            </div>

            <div class="col-md-3">
                      <div class="form-group">
                        <label>RUTA:</label>
                    </div>
                </div><!-- /.col-md-6 -->
                <!-- Inicia Nombre de Rótulo -->
                <div class="col-md-3">
                <div class="form-group">  
                   <input type="text"  name="" id="ruta-editar" class="form-control" required >
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>ASIGNAR EMPRESA:</label>
                </div>
              </div><!-- /.col-md-6 -->            
                    <div class="col-md-3">
                      <div class="form-group">
                              <!-- Select live search -->
                              <div class="input-group mb-14">
                                <select 
                                class="form-control" 
                                data-show-subtext="true" 
                                data-live-search="true" 
                                id="select-empresa-editar" 
                                title="-- Seleccione una empresa --"
                                
                                >
                                  @foreach($contribuyentes as $contribuyente)
                                  <option value="{{ $contribuyente->id }}"> {{ $contribuyente->nombre }} {{$contribuyente->apellido}}</option>
                                  @endforeach 
                                </select> 
                                </div>
                           <!-- finaliza select Asignar Representante-->
                      </div>
                  </div>

              <div class="col-md-3">
                <div class="form-group">
                    <label>ASIGNAR EMPRESA:</label>
                </div>
              </div><!-- /.col-md-6 -->            
                    <div class="col-md-3">
                      <div class="form-group">
                              <!-- Select live search -->
                              <div class="input-group mb-14">
                                <select 
                                class="form-control" 
                                data-show-subtext="true" 
                                data-live-search="true" 
                                id="select-empresa-editar" 
                                title="-- Seleccione una empresa --"
                                
                                >
                                  @foreach($empresas as $empresa)
                                  <option value="{{ $empresa->id }}"> {{ $empresa->nombre }}</option>
                                  @endforeach 
                                </select> 
                                </div>
                           <!-- finaliza select Asignar Representante-->
                      </div>
                  </div>
    
                  <div class="col-md-3">
                      <div class="form-group">
                        <label>TELÉFONO:</label>
                    </div>
                </div><!-- /.col-md-6 -->
                <!-- Inicia Nombre de Rótulo -->
                <div class="col-md-3">
                    <div class="form-group">  
                    <input type="text"  name="" id="telefono-editar" class="form-control" required >
                    </div>
               </div>

            </div>
            <!-- /.row -->
            </div>
         <!-- Fin /.col -->
            </div>
          </div>
            <!-- /.card-body -->
         <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="actualizarBus()">Guardar</button>
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">Cancelar</button>
          </div>
         <!-- /.card-footer -->
         </div>
        </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
<!-- Finaliza Modal Editar Bus-->

    
 
<!-- Inicia Modal Eliminar Bus-->
<div class="modal fade" id="modalEliminarBus">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="far fa-minus-square"></i>&nbsp;Eliminar Buses</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-EliminarBus">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>¿Realmente desea eliminar el bus seleccionado?"</p>

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
                    <button type="button" class="btn btn-danger" onclick="eliminarM()">Borrar</button>
                </div>
            </div>
        </div>
    </div>
<!--Finaliza Modal Eliminar Bus-->





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

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    
    
<script type="text/javascript">
        $(document).ready(function(){
            var ruta = "{{ url('/admin/bus/tabla') }}";
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";
        });
</script>

<script type="text/javascript">

    function recargar()
    {
        var ruta = "{{ url('/admin/bus/tabla') }}";
            $('#tablaDatatable').load(ruta);
    }

    function EditarBus(id)
    {
        document.getElementById("formulario-EditarBus").reset();
            $('#modalEditarBus').modal('show');
    }

 
</script>

<script>
  
    function informacionBuses(id)
    {
      openLoading();
        document.getElementById("formulario-EditarBus").reset();

          axios.post('/admin/bus/VerB',{
          'id': id
            })
          .then((response) => {
            console.log(response)
          closeLoading();
            if(response.data.success === 1){
            $('#modalEditarBus').modal('show');

                   
            $('#id-editar').val(response.data.buses.id);           
            $('#nom_bus-editar').val(response.data.buses.nom_bus);
            $('#fecha_inicio-editar').val(response.data.buses.fecha_inicio);
            $('#placa-editar').val(response.data.buses.placa);
            $('#ruta-editar').val(response.data.buses.ruta);
            $('#telefono-editar').val(response.data.buses.telefono);
           
          
                document.getElementById("select-empresa-editar").options.length = 0;
                document.getElementById("select-empresa-editar").options.length = 0;

                  $.each(response.data.empresa, function( key, val ){
                            if(response.data.id_emp == val.id){
                                $('#select-empresa-editar').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'</option>');
                            }else{
                                $('#select-empresa-editar').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                            }
                           
                        });
                  
                $.each(response.data.contribuyente, function( key, val ){
                if(response.data.id_contri == val.id){
                          $('#select-contribuyente-ver').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'&nbsp;'+val.apellido+'</option>');
                      }else{
                          $('#select-contribuyente-ver').append('<option value="' +val.id +'">'+val.nombre+'&nbsp;'+val.apellido+'</option>');
                      }
                });
                        

                  }
                  else{
                    toastr.error('No se encuentra la información solicitada');
                  }
                

                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
       
       
    }
   
//*** Inicia Editar buses ***//
    function actualizarBus()        
    {
        var id = document.getElementById('id-editar').value;
        var empresa = document.getElementById('select-empresa-editar').value;
        var nom_bus = document.getElementById('nom_bus-editar').value;
        var fecha_inicio = document.getElementById('fecha_inicio-editar').value;        
        var placa = document.getElementById('placa-editar').value;
        var ruta = document.getElementById('ruta-editar').value;
        var telefono = document.getElementById('telefono-editar').value;
              

        openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('empresa', empresa);
            formData.append('nom_bus', nom_bus);
            formData.append('fecha_inicio', fecha_inicio);
            formData.append('placa', placa);
            formData.append('ruta', ruta);      
            formData.append('telefono', telefono);
                       
            axios.post('/admin/bus/actualizar', formData, {
            })
            .then((response) => {
                            console.log(response)
                            closeLoading();
                            if(response.data.success === 0){
                                toastr.error(response.data.message);
                               
                            }
                            else if(response.data.success === 1){
                                agregado();
                               
                                
                                }
                                else if(response.data.success === 2){
                                modalMensaje('Empresa repetida!', 'Para agregar más o eliminarlas seleccione las opciones [Editar o Eliminar]');
                                resetbtn();
                                return;
                               
                            }
                           
                        })
                        .catch((error) => {
                            fallo('Error!', 'Error al agregar el bus');
                        
                        });              

    }

     
//*** Finaliza Editar buses ***//
  </script>

  <script>

    function VistaBuses(id_bus)
    {
   
        openLoading();
        window.location.href="{{ url('/admin/bus/vista') }}/"+id_bus;

    }
     function agregado()
        {
               Swal.fire({
                  title: 'Bus Actualizado',
                  text: 'Los datos del bus han sido actualizados',
                  icon: 'success',
                  showCancelButton: false,
                  confirmButtonColor: '#28a745',
                  closeOnClickOutside: false,
                  allowOutsideClick: false,
                  confirmButtonText: 'Aceptar'
                  
                }).then((result) => {
                    if (result.isConfirmed) {
                      
                        recargar();            
                        f4();            
                              
                        }
                    });
        }

        function recargar()
        {   
                  
            var ruta = "{{ url('/admin/bus/Listar') }}/";
                $('#tablaDatatable').load(ruta);
        }

        function f4()
        {
            location.reload();        
        }



  </script>


@stop