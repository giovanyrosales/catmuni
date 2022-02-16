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
                            <li class="breadcrumb-item active">Agregar nueva empresa</li>
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
            <h3 class="card-title">Formulario de datos de la empresa.</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
              <div class="form-group">
                        <label>Nombre del negocio:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required placeholder="Nombre del negocio">
                        <input type="hidden" name="id" id="id" class="form-control" >
                      </div>
                <!-- /.form-group -->
                <div class="row">
                  <div class="col-md-6">
                   <div class="form-group">
                          <label>NIT de la Empresa:</label>
                          <input type="number" name="nit" id="nit" class="form-control"  placeholder="0000-000000-000-0" >
                        </div></div>
                  <div class="col-md-6">
                    <div class="form-group">
                          <label>N° de Tarjeta:</label>
                          <input type="number" name="num_tarjeta" id="num_tarjeta" required placeholder="0000" class="form-control" >
                    </div>
                 </div>
                </div>
                <!-- /.form-group -->
                <div class="col-md-14">
                <div class="form-group">
                    <label>Dirección:</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" required placeholder="Dirección de la empresa"  >
                  </div> </div>   
                <!-- /.form-group -->

      <!-- Asignar Representante-->  
       <!-- /.form-group -->
       <div class="row"> 
            <div class="col-md-6">
                      <div class="form-group">
                      <label>Asignar representante legal:</label>
                              <!-- Select live search -->
                              <div class="input-group mb-14">
                                <select 
                                required
                                class="selectpicker show-tick" 
                                data-style="btn-success"
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
              <!-- /.form-group -->
                        <!--asignar actividad economica -->
                        <div class="col-md-6">
                     <div class="form-group">
                          <label>Actividad económica:</label>
                          
                          <!-- Select estado - live search -->
                          <div class="input-group mb-9">
                                <select 
                                required
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-actividad_economica" 
                                title="-- Selecione la actividad --"
                                 >
                                  @foreach($actividadeseconomicas as $actEc)
                                  <option value="{{ $actEc->id }}"> {{ $actEc->rubro }}</option>
                                  @endforeach 
                                </select> 
                           </div>
                           <!-- finaliza asignar actividad economica-->
                        </div>
                    </div>
               </div> 
               <!-- /.form-group -->
            </div> <!-- /.ROW -->
              <!-- /.col -->

              <div class="col-md-6">
              <!-- /.form-group --> 
              <div class="row"> 
                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Tipo de Comerciante:</label>
                        <input type="text" name="tipo_comerciante" id="tipo_comerciante" class="form-control" placeholder="Tipo de comerciante" >
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                       <label>Referencia Catastral:</label>
                       <input type="text" name="referencia_catastral" id="referencia_catastral" class="form-control"  required placeholder="000-00-000-0000P00"  >
                      </div>
                  </div>
                </div>
              <!-- /.form-group -->
                <!-- /.form-group -->
                <div class="row"> 
                <div class="col-md-6">
                      <div class="form-group">
                          <label>Inicio de Operaciones:</label>
                          <input type="date" name="inicio_operaciones" id="inicio_operaciones" required class="form-control" >
                      </div>
                      </div>
              <!-- /.form-group --> 
                  <div class="col-md-6">
                      <div class="form-group">
                          <label>Teléfono:</label>
                          <input type="number" name="telefono" id="telefono" class="form-control"  required placeholder="7777-7777"  >
                      </div>
                  </div>
                
                </div>
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Matricula de Comercio:</label>
                        <input type="number" name="matricula_comercio" id="matricula_comercio" class="form-control"  placeholder="Matricula de Comercio">
                      </div>
                  </div>
                  
                  <div class="col-md-4">
                     <div class="form-group">
                          <label>Actividad específica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-6">
                                <select 
                                required
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-actividad_especifica" 
                                title="-- Selecione la actividad --"
                                 >
                                  @foreach($actividadespecifica as $actEsp)
                                  <option value="{{ $actEsp->id }}"> {{ $actEsp->nom_actividad_especifica }}</option>
                                  @endforeach 
                                </select> 
                           </div>
                     </div>
                </div>
                </div>
              <!-- /.form-group -->
            <div class="row"> 
            <div class="col-md-6">
                      <div class="form-group">
                        <label>Giro comercial:</label>
                            <!-- Select Giro Comercial -live search -->
                                <div class="input-group mb-9">
                                <select 
                                required 
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"  
                                id="select-giro_comercial" 
                                title="--  Selecione un giro  --"
                                required
                                >
                                  @foreach($giroscomerciales as $giro)
                                  <option value="{{ $giro->id }}"> {{ $giro->nombre_giro }}
                                  </option>
                                  @endforeach 
                                </select> 
                                </div>
                           <!-- finaliza select Giro Comercial-->
                      </div>
                  </div>
              <!-- /.form-group -->
                  <!-- /.form-group -->
                    <div class="col-md-6">
                     <div class="form-group">
                          <label>Estado:</label>
                          
                          <!-- Select estado - live search -->
                          <div class="input-group mb-9">
                                <select 
                                required
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-estado_empresa" 
                                title="-- Selecione el estado  --"
                                 >
                                  @foreach($estadoempresas as $estado)
                                  <option value="{{ $estado->id }}"> {{ $estado->estado }}</option>
                                  @endforeach 
                                </select> 
                           </div>
                           <!-- finaliza select estado-->
                        </div>
                    </div>
               </div>
            <!-- /.form-group -->
            
            <!-- Fin /.col -->
            </div>
          <!-- /.row -->
          </div>
         <!-- /.card-body -->
         <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="nuevo()">Guardar</button>
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


    <script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>


<script>

function nuevo(){
  

        var contribuyente = document.getElementById('select-contribuyente').value;
        var estado_empresa = document.getElementById('select-estado_empresa').value;
        var giro_comercial = document.getElementById('select-giro_comercial').value;
        var actividad_economica = document.getElementById('select-actividad_economica').value;
        var actividad_especifica = document.getElementById('select-actividad_especifica').value;
        var nombre = document.getElementById('nombre').value;
        var matricula_comercio = document.getElementById('matricula_comercio').value;
        var nit = document.getElementById('nit').value;
        var referencia_catastral = document.getElementById('referencia_catastral').value;
        var tipo_comerciante = document.getElementById('tipo_comerciante').value;
        var inicio_operaciones = document.getElementById('inicio_operaciones').value;
        var direccion = document.getElementById('direccion').value;
        var num_tarjeta = document.getElementById('num_tarjeta').value;
        var telefono = document.getElementById('telefono').value;


            
    
           

        if(nombre === ''){
            toastr.error('El nombre de la empresa es requerido');
            return;
        }

        if(nombre.length > 50){
            toastr.error('El nombre no puede contener más de 50 caracteres');
            return;
        }
        
        if(num_tarjeta === ''){
            toastr.error('El número de tarjeta de la empresa es requerido');
            return;
        }
                
        if(inicio_operaciones === ''){
            toastr.error('El inicio de operaciones de la empresa es requerido');
            return;
        }
        if(direccion === ''){
            toastr.error('La dirección de la empresa es requerido');
            return;
        }

        if(telefono === ''){
            toastr.error('El número de teléfono de la empresa es requerido');
            return;
        }
        if(telefono.length > 8){
            toastr.error('El número de teléfono no puede contener más de 8 digitos');
            return;
        }
        if(telefono.length < 8){
            toastr.error('El número de teléfono no puede contener menos de 8 digitos');
            return;
        }
        
        if(contribuyente === ''){
            toastr.error('El dato contribuyente es requerido');
            return;
        }

        if(actividad_economica === ''){
            toastr.error('La actividad económica de la empresa es requerido');
            return;
        }

        if(actividad_especifica === ''){
            toastr.error('La actividad específica de la empresa es requerido');
            return;
        }

        if(giro_comercial === ''){
            toastr.error('El giro comercial de la empresa es requerido');
            return;
        }

        if(estado_empresa === ''){
            toastr.error('El estado de la empresa es requerido');
            return;
        }

        if(matricula_comercio  != ''){
          if(matricula_comercio.length < 0){
              toastr.error('El número de matricula no puede contener números negativos');
              return;
          }
          if(matricula_comercio.length < 10){
              toastr.error('El número de matricula no puede contener menos de 10 números');
              return;
          }
          if(matricula_comercio.length > 10){
              toastr.error('El número de matricula no puede contener más de 10 números');
              return;
          }
        }
        var reglaNumeroDecimal = /^[0-9]\d*(\.\d+)?$/;
       
        if(nit  != ''){

                  if(nit.length > 14 ) 
                        {
                          toastr.error('El NIT no puede contener más de 14 números');
                          return;
                        }
                   if(nit.length< 14 ) 
                        {
                          toastr.error('El NIT debe contener 14 números');
                          return;
                        }
                  if(nit.length < 0)
                  {
                          toastr.error('El NIT no puede tener números negativos');
                          return;
                  }
         }

        if(!telefono.match(reglaNumeroDecimal)) {
            toastr.error('El número de teléfono debe ser un número entero');
            return;
        }

        if(telefono < 0){
            toastr.error('El número de teléfono no puede tener números negativos');
            return;
        }

        if(num_tarjeta < 0){
            toastr.error('El número de tarjeta no puede tener números negativos');
            return;
        }

       
        openLoading();
        var formData = new FormData();
        formData.append('contribuyente', contribuyente);
        formData.append('estado_empresa', estado_empresa);
        formData.append('giro_comercial', giro_comercial);
        formData.append('actividad_economica', actividad_economica);
        formData.append('actividad_especifica', actividad_especifica);
        formData.append('nombre', nombre);
        formData.append('matricula_comercio', matricula_comercio);
        formData.append('nit', nit);
        formData.append('referencia_catastral', referencia_catastral);
        formData.append('tipo_comerciante', tipo_comerciante);
        formData.append('inicio_operaciones', inicio_operaciones);
        formData.append('direccion', direccion);
        formData.append('num_tarjeta', num_tarjeta);
        formData.append('telefono', telefono);

        axios.post('/admin/empresa/nueva', formData, {
        })
            .then((response) => {
                closeLoading();
                if(response.data.success === 0){
                    toastr.error(response.data.message);
          
                }
            //       else {
            //            toastr.error('Error al registrar');
            //            }
                if(response.data.success === 1){
             
                  Swal.fire(
                            'Empresa registrada correctamente!',
                            'Presiona el botón Ok!',
                            'success'
                          )
                          location.reload();
                   // toastr.success('Empresa registrada correctamente');
                    
                }
               
            })
            .catch((error) => {
               // toastr.error('Error al registrar empresa');
                Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Error al registrar empresa!', 
                        })
                
            });
 }



</script> 

    
@endsection

