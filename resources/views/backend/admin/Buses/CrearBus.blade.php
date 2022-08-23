@extends('backend.menus.superior')



@section('content-admin-css')

  <!-- Para el select live search -->
    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">
  <!--Finaliza el select live search -->

    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/main.css') }}" type="text/css" rel="stylesheet" />
    
    <link rel="stylesheet" href="sweetalert2.min.css">
    
 
@stop

<script>

function f4()
{
    location.reload();        
}

</script>


<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Agregar nuevo bus</li>
                        </ol>
                    </div><!-- /.col -->
            </div>
        </div>
    </section>
<!-- finaliza content-wrapper-->

<!-- Inicia Formulario Crear Empresa-->
    <section class="content">
      <div class="container-fluid" >
        <!-- SELECT2 EXAMPLE -->

        <form class="form-horizontal" id="form1">
        @csrf

        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Formulario Inscripción de Buses</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>

          <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
        <div class="card-header text-info"><label>I. DATOS DEL BUS</label></div>
        <div class="card-body"><!-- Card-body -->
          <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
              <!-- /.form-group -->
                   <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>NÚMERO DE FICHA:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="text" value=" "  id="nFicha" class="form-control" required >
                    <input type="text" hidden value="2"  id="estado_buses" class="form-control" required >        
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

            <div class="col-md-3">
                  <div class="form-group">
                        <label>FECHA DE APERTURA:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date" value=" "  id="fecha_inicio" class="form-control" required >                        
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
                   <input type="text"  id="nom_bus" class="form-control" required >
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
                   <input type="text"  name="" id="placa" class="form-control" required >
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
                   <input type="text"  name="" id="ruta" class="form-control" required >
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
                    <input type="text"  name="" id="telefono" class="form-control" required >
                    </div>
               </div>

            </div>
            </div>
        </div>
          </div>
                <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
                <div class="card-header text-info"><label>II. DATOS DEL REPRESENTANTE LEGAL</label></div>
                <div class="card-body"><!-- Card-body -->

                <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>ASIGNAR CONTRIBUYENTE:</label>
                    </div>
                </div><!-- /.col-md-6 -->            
                    <div class="col-md-3">
                      <div class="form-group">
                              <!-- Select live search -->
                              <div class="input-group mb-14">
                                <select 
                                class="selectpicker" 
                                data-show-subtext="true" 
                                data-live-search="true" 
                                id="select-contribuyente" 
                                title="-- Seleccione un contribuyente --"
                                onchange="llenarSelect()"
                                
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
                            <label id="empresaDiv">ASIGNAR EMPRESA:</label>
                        </div>
                    </div><!-- /.col-md-6 --> 

                    <div class="col-md-3">
                        <div class="form-group" id= "asignar-empresaDIV">                          
                              <!-- Select estado - live search -->
                            <div class="input-group mb-3" >                        
                              <select class="form-control"  id="select-empresa"                         
                              >
                              </select>

                            </div>
                        </div>
                    </div>
                </div>
          
         <!-- Fin /.col -->
            </div>
          </div>
            <!-- /.card-body -->
         <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="agregarBus()">Guardar</button>
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
<!-- Finaliza Formulario Crear Empresa-->


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
            document.getElementById("divcontenedor").style.display = "block";
         
          $('#empresaDiv').hide();
          $('#asignar-empresaDIV').hide();
        
        
          });


    </script>

    <script>
    //AGREGAR NUEVO BUS
      function agregarBus(id)
        {            
         
            var contribuyente = document.getElementById('select-contribuyente').value;
            var empresa = document.getElementById('select-empresa').value;
            var nFicha = document.getElementById('nFicha').value;
            var nom_bus = document.getElementById('nom_bus').value;
            var fecha_inicio = document.getElementById('fecha_inicio').value;           
            var placa = document.getElementById('placa').value;
            var ruta = document.getElementById('ruta').value;
            var telefono = document.getElementById('telefono').value;
            var estado_buses = document.getElementById('estado_buses').value;
                   
                    
            if(nom_bus === '')
            {
              toastr.error('El nombre del bus es requerido');
              return;
            }
                                  
            if (fecha_inicio === '')
            {
              toastr.error('Fecha de apertura es requerida');
              return;
            }

            if (placa === '')
            {
              toastr.error('El número de placa del bus es requerido');
              return;
            }

            if (ruta === '')
            {
              toastr.error('La ruta del bus es requerida');
              return;
            }

            if (telefono === '')
            {
                toastr.error('El teléfono es requerido');
                return;
            }

           
            openLoading();
            var formData = new FormData();
           
                formData.append('contribuyente', contribuyente);
                formData.append('empresa', empresa);
                formData.append('nFicha', nFicha);
                formData.append('nom_bus', nom_bus);         
                formData.append('fecha_inicio', fecha_inicio);
                formData.append('placa', placa);          
                formData.append('ruta', ruta);
                formData.append('telefono', telefono);
                formData.append('estado_buses', estado_buses );
        

            axios.post('/admin/bus/CrearBuses', formData, {
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
    // TERMINA AGREGAR BUS    

      function llenarSelect()
      {
             var id_select = document.getElementById('select-contribuyente').value;
          
           
             var formData = new FormData();
             formData.append('id_select', id_select);
             
            axios.post('/admin/rotulos/buscarE', formData, {
              })
            .then((response) => {
            
               document.getElementById("select-empresa").options.length = 0;
               $('#empresaDiv').show();
               $('#asignar-empresaDIV').show();
          
            
                $.each(response.data.empresa, function( key, val ){
                       $('#select-empresa').append('<option value="' +val.id +'">'+val.nombre+'</option>').select2();
                       
                            
                    });

               })
            .catch((error) => {
               // toastr.error('Error al registrar empresa');
               
            });
            
             
      }

        
    </script> 

    <script>

        function agregado()
        {
               Swal.fire({
                  title: 'Bus Agregado',
                  text: "Puede modificarla en la opción [Editar]",
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

        function resetbtn()
        {
              document.getElementById('select-contribuyente').value="";
              document.getElementById('select-empresa').value=""; 
              document.getElementById('nom_bus').value=""; 
              document.getElementById('fecha_inicio').value=""; 
              document.getElementById('placa').value=0;
              document.getElementById('ruta').value=0;
              document.getElementById('telefono').value=0; 
        } 

        function recargar()
        {   
                  
            var ruta = "{{ url('/admin/buses/tabla') }}/";
                $('#tablaDatatable').load(ruta);
        }

        function modalMensaje(titulo, mensaje)
        {
            Swal.fire({
                title: titulo,
                text: mensaje,
                icon: 'info',
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {

                }
            });
            
        }

        function fallo(titulo, mensaje)
        {
            Swal.fire({
                title: titulo,
                text: mensaje,
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                location.reload;
                }
            });
            
            
        }


    </script>

    
@endsection

