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
      <div class="container-fluid" >
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

          <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
        <div class="card-header text-info"><label>I. DATOS DEL RÓTULO</label></div>
        <div class="card-body"><!-- Card-body -->
          <!-- /.card-header -->
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
                    <input type="date" value=" " name="fecha_apertura"  id="fecha_apertura" class="form-control" required >        
                    <input type="text" hidden value="2"  id="estado_rotulo" class="form-control" required >    
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

           <!-- /.form-group -->
           <div class="col-md-3">
                <div class="form-group">
                    <label>NOMBRE DEL RÓTULO:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Nombre de Rótulo -->
            <div class="col-md-3">
                <div class="form-group">  
                   <input type="text"  name="nom_rotulo" id="nom_rotulo" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Nombre del Rótulo-->
            <!-- /.form-group -->
   
            <div class="col-md-3">
                      <div class="form-group">
                        <label>DIRECCIÓN DEL RÓTULO:</label>
                    </div>
                </div><!-- /.col-md-6 -->
                <!-- Inicia Nombre de Rótulo -->
                <div class="col-md-9">
                <div class="form-group">  
                   <input type="text"  name="" id="direccion" class="form-control" required >
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>ACTIVIDAD ECONÓMICA:</label>
                </div>
            </div><!-- /.col-md-6 -->
                <div class="col-md-3">
                     <div class="form-group">
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
                                class="selectpicker" 
                                data-show-subtext="true" 
                                data-live-search="true" 
                                id="select-empresa" 
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
                            <label>PERMISO:</label>
                        </div>
                    </div>
                      <div class="col-md-3">
                     <div class="form-group">
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
                      
                   <!-- /.form-group -->
             
                <!-- Finaliza Nombre del Rótulo-->
                <!-- /.form-group -->
    
            </div>
            <!-- /.row -->
            </div>
         <!-- Fin /.col -->
            </div>
          </div>
      

        <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
        <div class="card-header text-info"><label>II. DATOS GENERALES</label></div>
        <div class="card-body"><!-- Card-body -->
        <div class="row"><!-- /.ROW1 -->

        <div class="row">
            <!-- /.form-group -->
            <div class="col-md-6">
                <div class="form-group">
                    <p>En esta fecha se realizó la inspección para apertura de:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                   <input type="text" name="empresa" id="empresa" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

             <!-- /.form-group -->
             <div class="col-md-6">
                <div class="form-group">
                    <p>el rótulo posee las siguiente medidas:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                <textarea class="form-control" id="medidas" rows="2"></textarea>
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

            <div class="col-md-6">
                <div class="form-group">
                    <p>con un total de metros cuadrados de:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                <input type="text" name="total_medidas" id="total_medidas" class="form-control" required  > 
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

            <div class="col-md-6">
                <div class="form-group">
                    <p>y caras:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                <input type="text"  name="total_caras" id="total_caras" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

            <!-- /.form-group -->
            <div class="col-md-12">
                <div class="form-group">
                    <p>por lo que se procede a realizar la inscripción y se anexa copia de Documentación Personal del Representante Legal</p>
                </div>
            </div><!-- /.col-md-6 -->

            <!-- /.form-group -->
            <div class="col-md-6">
                <div class="form-group">
                    <p>Coordenadas Geodésicas:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                   <input type="text"  value="" name="coordenadas" id="coordenadas" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

             <!-- /.form-group -->
            <div class="col-md-6">
                <div class="form-group">
                    <p>se anexa foto del rótulo</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                   <input type="file" id="imagen" class="form-control" accept="image/jpeg, image/jpg, image/png " >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->
            </div>
         </div>
        </div>
        </div>

        <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
            <div class="card-header text-info"><label>III. INSPECCIÓN REALIZADA POR</label></div>
            <div class="card-body"><!-- Card-body -->
            <div class="row"><!-- /.ROW1 -->
         
            <!-- Finaliza Inspección-->
            <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label>Nombre:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Dirección -->
            <div class="col-md-11">
                <div class="form-group">  
                    <input type="text"  value="" name="nom_inspeccion" id="nom_inspeccion" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Inspección-->    
            </div>
        <div class="row">
              <!-- Finaliza Inspección-->
              <div class="col-md-6">
                <div class="form-group">
                    <label>Cargo:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Dirección -->
            <div class="col-md-10">
                <div class="form-group">  
                    <input type="text"  value="" name="cargo_inspeccion" id="cargo_inspeccion" class="form-control" required >
                </div>
            </div>
        </div>

        <div class="row">
              <!-- Finaliza Inspección-->
              <div class="col-md-8">
                <div class="form-group">
                    <label>Firma:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Dirección -->
            <div class="col-md-12">
                <div class="form-group">  
                    <input type="text"  value="" name="" id="" class="form-control" required >
                </div>
            </div>
        </div>
          
          </div>
            </div>
          </div>
        
    
         <!-- /.card-body -->
         <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="agregarRotulo()">
                  <i class="fas fa-save"></i>&nbsp;Guardar</button>
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">
                  <i class="fas fa-times-circle"></i>&nbsp;Cancelar</button>
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
            
         
            var empresa = document.getElementById('select-empresa').value;
            var estado_rotulo = document.getElementById('estado_rotulo').value;
            var nom_rotulo = document.getElementById('nom_rotulo').value;
            var direccion = document.getElementById('direccion').value;
            var permiso_instalacion = document.getElementById('select-tipo_permiso').value;
            var fecha_apertura = document.getElementById('fecha_apertura').value;           
            var medidas = document.getElementById('medidas').value;
            var total_medidas = document.getElementById('total_medidas').value;
            var total_caras = document.getElementById('total_caras').value;
            var coordenadas = document.getElementById('coordenadas').value;
            var imagen = document.getElementById('imagen');
            var nom_inspeccion = document.getElementById('nom_inspeccion').value;
            var cargo_inspeccion = document.getElementById('cargo_inspeccion').value;
            var actividad_economica = document.getElementById('select-actividad_economica').value;
         
        
                    
            if(nom_rotulo === '')
            {
              toastr.error('El nombre del rótulo es requerido');
              return;
            }
            
            if (permiso_instalacion === '')
            {
              toastr.error('Debe seleccionar el permiso de instalación');
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

            if (direccion === '')
            {
                toastr.error('La dirección del rótulo es requerida');
                return;
            }

            if (medidas === '')
            {
                toastr.error('Las medidas del rótulo son requeridas');
                return;
            }

            if (total_medidas === '')
            {
                toastr.error('El total de medidas es requerido');
                return;
            }

            if (total_caras === '')
            {
                toastr.error('El total de caras del rótulo es requerido');
                return;
            }

            if (coordenadas === '')
            {
                toastr.error('Las coordenadas son requeridas');
                return;
            }
            
            openLoading();
            var formData = new FormData();
           
                formData.append('empresa', empresa);
                formData.append('estado_rotulo', estado_rotulo);
                formData.append('nom_rotulo', nom_rotulo);         
                formData.append('direccion', direccion);
                formData.append('permiso_instalacion', permiso_instalacion);          
                formData.append('fecha_apertura', fecha_apertura);
                formData.append('medidas', medidas);
                formData.append('total_medidas', total_medidas);
                formData.append('total_caras', total_caras);
                formData.append('coordenadas', coordenadas);
                formData.append('imagen', imagen.files[0]);
                formData.append('nom_inspeccion', nom_inspeccion);
                formData.append('cargo_inspeccion', cargo_inspeccion);
                formData.append('actividad_economica', actividad_economica);
          
        

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

