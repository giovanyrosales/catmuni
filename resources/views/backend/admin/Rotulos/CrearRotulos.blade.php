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



<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Agregar nuevo rótulo</li>
                        </ol>
                    </div><!-- /.col -->
            </div>
        </div>
    </section>
<!-- finaliza content-wrapper-->

<!-- Inicia Formulario Crear Empresa-->
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->

        <form class="form-horizontal" id="form1">
        @csrf

        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Formulario Inscripción de Rótulos</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
            <div class="card-body">
            <div class="row">
                  <div class="col-md-3">
                    <div class="form-group"> 
                      <label>Número de tarjeta:</label>
                      <input type="number" name="num_tarjeta" id="num_tarjeta" class="form-control" required placeholder="Número de tarjeta">
                      <input type="hidden" name="id" id="id-ver" class="form-control" >
                    </div>
                  </div>
                <!-- /.form-group -->
                <div class="col-md-6">
                    <div class="form-group">
                      <label>Nombre rótulo:</label>
                      <input type="text" name="nom_rotulo" id="nom_rotulo" class="form-control" required placeholder="Nombre" >
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                      <label>Fecha apertura:</label>
                      <input type="date" name="fecha_apertura" id="fecha_apertura" class="form-control" required placeholder="Fecha de apertura" >
                    </div>
                </div>
              <!-- /.row -->
            </div>

            <div class="row">
                  <div class="col-md-8">
                    <div class="form-group"> 
                      <label>Dirección :</label>
                      <input type="text" name="direccion" id="direccion" class="form-control" required placeholder="Dirección rótulo">
                    </div>
                  </div>

                  <div class="col-md-4">
                     <div class="form-group">
                          <label>Actividad Económica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-10">
                                <select 
                                required
                                class="selectpicker"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-actividad_economica" 
                                title="-- Selecione actividad económica --"
                                 >
                                 <option value="Valla publicitaria">Valla publicitaria</option>
                                 </select> 
                           </div>
                           <!-- finaliza asignar actividad economica-->
                        </div>
                          </div>
            <!-- /.row -->
            </div>
        
            <div class="row">
                  <div class="col-md-8">
                    <div class="form-group"> 
                    <label for="medidas" class="form-label">Medidas</label>
                     <textarea class="form-control" id="medidas" rows="3"></textarea>
                    </div>
                  </div>

                  <div class="col-md-4">
                      <div class="form-group">
                      <label>Asignar representante legal:</label>
                              <!-- Select live search -->
                              <div class="input-group mb-14">
                                <select 
                                required
                                class="selectpicker show-tick" 
                                data-show-subtext="true" 
                                data-live-search="true" 
                                id="select-contribuyente" 
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
                  <div class="col-md-4">
                      <div class="form-group">
                      <label>Asignar empresa:</label>
                              <!-- Select live search -->
                              <div class="input-group mb-14">
                                <select 
                                class="selectpicker show-tick" 
                                data-show-subtext="true" 
                                data-live-search="true" 
                                id="select-empresa" 
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
                  <div class="col-md-4">
                     <div class="form-group">
                          <label>Permiso de instalación:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-10">
                                <select 
                                required
                                class="selectpicker"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-tipo_permiso" 
                                title="-- Selecione el tipo de instalación --"
                                 >
                                 <option value="Temporal">Temporal</option>
                                 <option value="Permanente">Permanente</option>
                                </select> 
                           </div>
                           <!-- finaliza asignar actividad economica-->
                        </div>
                      </div>

                    <div class="col-md-4">
                     <div class="form-group">
                          <label>Estado:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-10">
                                <select 
                                required
                                class="selectpicker"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-estado" 
                                title="-- Selecione el estado --"
                                 >
                                 <option value="Activo">Activo</option>
                                 <option value="Cerrado">Cerrado</option>
                                </select> 
                           </div>
                           <!-- finaliza asignar actividad economica-->
                        </div>
                      </div>
              <!-- /.row -->
            </div>
            <!-- Fin /.col -->
            </div>
           
         
       
         <!-- /.card-body -->
         <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="agregarRotulo()">Guardar</button>
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
         
           $('#actividad-especificaDIV').hide();
        
          });


    </script>

    <script>
    
      function agregarRotulo(id)
        {
            
            var contribuyente = document.getElementById('select-contribuyente').value;
            var empresa = document.getElementById('select-empresa').value;
            var nom_rotulo = document.getElementById('nom_rotulo').value;
            var direccion = document.getElementById('direccion').value;
            var permiso_instalacion = document.getElementById('select-tipo_permiso').value;
            var medidas = document.getElementById('medidas').value;
            var fecha_apertura = document.getElementById('fecha_apertura').value;
            var num_tarjeta = document.getElementById('num_tarjeta').value;
            var actividad_economica = document.getElementById('select-actividad_economica').value;
            var estado = document.getElementById('select-estado').value;
        
                    
            if(nom_rotulo === '')
            {
              toastr.error('El nombre del rótulo es requerido');
              return;
            }
            
            if (direccion === '')
            {
              toastr.error('La dirección es requerida');
              return;
            }

            if (permiso_instalacion === '')
            {
              toastr.error('Debe seleccionar el permiso de instalación');
              return;
            }
            
            if (medidas === '')
            {
              toastr.error('La medida del rótulo es requerida');
              return;
            }

            if (fecha_apertura === '')
            {
              toastr.error('Fecha de apertura es requerida');
              return;
            }

            if (actividad_economica === '')
            {
              toastr.error('Actividad económica es requerida');
              return;
            }

            if (estado === '')
            {
              toastr.error('Estado es requerido');
              return;
            }
            
            openLoading();
            var formData = new FormData();
            formData.append('contribuyente', contribuyente);
            formData.append('empresa', empresa);
            formData.append('nom_rotulo', nom_rotulo);
            formData.append('direccion', direccion);
            formData.append('permiso_instalacion', permiso_instalacion);
            formData.append('medidas', medidas);
            formData.append('fecha_apertura', fecha_apertura);
            formData.append('num_tarjeta', num_tarjeta);
            formData.append('actividad_economica', actividad_economica);
            formData.append('estado', estado );
        

            axios.post('/admin/Rotulos/CrearRotulos', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 0){
                        toastr.error(response.data.message);
                
                    }
              
                    if(response.data.success === 1){
                
                        Swal.fire(
                                '¡Rótulo registrado correctamente!',
                                'Presiona el botón Ok!',
                                'success'
                                )
                               location.reload();
                  
                    }
                    
                })
                .catch((error) => {
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Error al registrar rótulo!', 
                            })
               
                });
        }
        
    // Función para llenar select
    </script> 

    
@endsection

