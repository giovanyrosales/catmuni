@extends('backend.menus.superior')

@section('content-admin-css')
    <!-- Para el select live search -->
    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">
    <!-- Finaliza el select live search -->
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/main.css') }}" type="text/css" rel="stylesheet" />
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
            <h1></h1>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Listado de empresas</li>
                            </ol>
                        </div>
        </div>
        <br>
        <button type="button"onclick="location.href='{{ url('/admin/nuevo/empresa/index') }}'" class="btn btn-info btn-sm" >
                <i class="fas fa-pencil-alt"></i>
                Nueva empresa
            </button>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <!-- CAJA -->
        <form class="form-horizontal" id="form1">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-th-list"></i> &nbsp;Lista de empresas registradas.</h3>

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


<!-- modal editar empresa -->
<div class="modal fade" id="modalEditar">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><i class="far fa-edit"></i> &nbsp;Editar datos de la empresa</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formulario-editar">
              <div class="card-body">

<!--modal editar aquí -->
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
                        <input type="text" name="nombre" id="nombre-editar" class="form-control" required placeholder="Nombre del negocio">
                        <input type="hidden" name="id" id="id-editar" class="form-control" >
                      </div>
                <!-- /.form-group -->
                <div class="row">
                  <div class="col-md-6">
                   <div class="form-group">
                          <label>NIT de la Empresa:</label>
                          <input type="number" name="nit" id="nit-editar" class="form-control"  placeholder="0000-000000-000-0" >
                        </div></div>
                  <div class="col-md-6">
                    <div class="form-group">
                          <label>N° de Tarjeta:</label>
                          <input type="number" name="num_tarjeta" id="num_tarjeta-editar" required placeholder="0000" class="form-control" >
                    </div>
                 </div>
                </div>
                <!-- /.form-group -->
                <div class="col-md-14">
                <div class="form-group">
                    <label>Dirección:</label>
                    <input type="text" name="direccion" id="direccion-editar" class="form-control" required placeholder="Dirección de la empresa"  >
                  </div> </div>   
                <!-- /.form-group -->
            <!-- inicia row telefono estado -->
                <div class="row">
                <div class="col-md-6">
                      <div class="form-group">
                          <label>Teléfono:</label>
                          <input type="number" name="telefono" id="telefono-editar" class="form-control"  required placeholder="7777-7777"  >
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Giro comercial:</label>
                            <!-- Select Giro Comercial -live search -->
                                <div class="input-group mb-9">
                                <select 
                                required 
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"  
                                id="select-giro_comercial-editar" 
                                required
                                >
                                  @foreach($giroscomerciales as $giro)
                                  <option value="{{ $giro->id }}"> {{ $giro->nombre_giro }}
                                  </option>
                                  @endforeach 
                                </select> 
                                </div>
                          </div>
                  </div>
           <!-- cierra div row-->       
                </div>
      <!-- Asignar Representante-->  
            <div class="col-md-14">
                      
                    <!-- finaliza select Asignar Representante-->

                    <!--asignar actividad economica -->
                        <div class="col-md-14">
                          <div class="form-group">
                          <label>Actividad económica:</label>
                          <!-- Select estado - live search -->
                          <div class="input-group mb-9">
                                <select 
                                required
                                class="form-control" 
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select-actividad_economica-editar" 
                                onchange="excepciones_especificas()"
                                 >

                                </select> 
                           </div>
                        </div>
                    </div>
               </div> 
               <!-- finaliza asignar actividad economica-->

             </div> <!-- /.ROW -->
              <!-- /.col -->

              <div class="col-md-6">
                <!-- /.form-group --> 
                      <div class="form-group">
                        <label>Tipo de Comerciante:</label>
                        <input type="text" name="tipo_comerciante" id="tipo_comerciante-editar" class="form-control" placeholder="Tipo de comerciante" >
                      </div>
                <!-- /.form-group --> 
                      <div class="form-group">
                       <label>Referencia Catastral:</label>
                       <input type="text" name="referencia_catastral" id="referencia_catastral-editar" class="form-control"  required placeholder="000-00-000-0000P00"  >
                      </div>
                <!-- /.form-group -->
                <!-- /.form-group -->
                      <div class="form-group">
                          <label>Inicio de Operaciones:</label>
                          <input type="date" name="inicio_operaciones" id="inicio_operaciones-editar" required class="form-control" >
                      </div>
                <!-- /.form-group --> 
                      <div class="form-group">
                        <label>Matricula de Comercio:</label>
                        <input type="number" name="matricula_comercio" id="matricula_comercio-editar" class="form-control"  placeholder="Matricula de Comercio">
                      </div>
              <!-- /.form-group -->
               <!-- /.form-group -->
            <div class="row"> 
                    <div class="col-md-6" id="Excepcion_especifica">
                          <div class="form-group">
                              <label>¿Excepción especifica?</label>
                                  <br>
                                      <label class="switch" style="margin-top:10px">
                                          <input type="checkbox" id="toggle-excepcion_especifica-editar">
                                            <div class="slider round">
                                                <span class="on">SI</span>
                                                <span class="off">NO</span>
                                            </div>
                                        </label>
                          </div>
                    </div>

               </div>
            <!-- /.form-group -->
        
      <!-- cierra div de row-->       
                  
        </div>
              </div>
            <!-- Fin /.col -->
            </div>
          <!-- /.row -->
          </div>
         <!-- /.card-body -->
         <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="editar()">
                  <i class="fas fa-save"></i>&nbsp;Guardar</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">
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
<!--Finaliza modal Editar empresa -->



@extends('backend.menus.footerjs')
@section('archivos-js')
  <!-- Para el select live search -->
    <script src="{{ asset('js/bootstrap-select.min.js') }}" type="text/javascript"></script>
  <!-- Finaliza el select live search -->

  <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/jquery.simpleaccordion.js') }}"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    
<script>
function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

</script>

 <!-- incluir tabla -->
 <script type="text/javascript">


$(document).ready(function(){

      var ruta = "{{ url('/admin/empresas/tabla') }}";
      $('#tablaDatatable').load(ruta);
      document.getElementById("divcontenedor").style.display = "block";
});

</script>




<script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });
function recargar(){
     var ruta = "{{ url('/admin/empresas/tabla') }}";
     $('#tablaDatatable').load(ruta);
   }


function VerEmpresa(id){
  openLoading();
        window.location.href="{{ url('/admin/empresas/show') }}/"+id;

        }
        
// Para show empresa


// Para informacion empresa

function excepciones_especificas(){
  var sel = document.getElementById("select-actividad_economica-editar");  
  var selected = sel.options[sel.selectedIndex];
  var Codigo_Act=selected.getAttribute('data-actividad');

  if(Codigo_Act== 1)
                {   
                         $('#Excepcion_especifica').show();
                }else{
                        $('#Excepcion_especifica').hide();
                     }

}

function informacion(id){
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post('/admin/empresas/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                   
                        $('#modalEditar').modal('show');
                        $('#id-editar').val(response.data.empresa.id);
                        $('#nombre-editar').val(response.data.empresa.nombre);
                        $('#matricula_comercio-editar').val(response.data.empresa.matricula_comercio);
                        $('#nit-editar').val(response.data.empresa.nit);
                        $('#referencia_catastral-editar').val(response.data.empresa.referencia_catastral);
                        $('#num_tarjeta-editar').val(response.data.empresa.num_tarjeta);
                        $('#tipo_comerciante-editar').val(response.data.empresa.tipo_comerciante);
                        $('#inicio_operaciones-editar').val(response.data.empresa.inicio_operaciones);
                        $('#direccion-editar').val(response.data.empresa.direccion);
                        $('#telefono-editar').val(response.data.empresa.telefono);
                        

                        document.getElementById("select-giro_comercial-editar").options.length = 0;
                        document.getElementById("select-actividad_economica-editar").options.length = 0;

                        

                        $.each(response.data.giro_comercial, function( key, val ){
                            if(response.data.idgiro_co == val.id){
                                $('#select-giro_comercial-editar').append('<option value="' +val.id +'" selected="selected">'+val.nombre_giro+'</option>');
                            }else{
                                $('#select-giro_comercial-editar').append('<option value="' +val.id +'">'+val.nombre_giro+'</option>');
                            }
                        }); 

                        $.each(response.data.actividad_economica, function( key, val ){
                            if(response.data.idact_eco == val.id){
                                $('#select-actividad_economica-editar').append('<option value="' +val.id +'"data-actividad="' +val.codigo +'" selected="selected">'+val.rubro+'&nbsp;'+'('+val.codigo_atc_economica+')'+'</option>');
                            }else{
                                $('#select-actividad_economica-editar').append('<option value="' +val.id +'"data-actividad="' +val.codigo+'">'+val.rubro+'&nbsp;'+'('+val.codigo_atc_economica+')'+'</option>');
                            }
                        });
                     

                        if(response.data.empresa.excepciones_especificas == 'NO'){
                            $("#toggle-excepcion_especifica-editar").prop("checked", false);
                        }else{
                            $("#toggle-excepcion_especifica-editar").prop("checked", true);
                        }
                        excepciones_especificas();

                            
                    }else{
                        toastr.error('Información no encontrada');
                    }

                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }
// Para editar empresa

function editar(){
        var id = document.getElementById('id-editar').value;
        var giro_comercial = document.getElementById('select-giro_comercial-editar').value;
        var actividad_economica = document.getElementById('select-actividad_economica-editar').value;
        var nombre = document.getElementById('nombre-editar').value;
        var matricula_comercio = document.getElementById('matricula_comercio-editar').value;
        var nit = document.getElementById('nit-editar').value;
        var referencia_catastral = document.getElementById('referencia_catastral-editar').value;
        var tipo_comerciante = document.getElementById('tipo_comerciante-editar').value;
        var inicio_operaciones = document.getElementById('inicio_operaciones-editar').value;
        var direccion = document.getElementById('direccion-editar').value;
        var num_tarjeta = document.getElementById('num_tarjeta-editar').value;
        var telefono = document.getElementById('telefono-editar').value;
        var t = document.getElementById('toggle-excepcion_especifica-editar').checked;
            var toggle = t ? 1 : 0;

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
       
        if(actividad_economica === ''){
            toastr.error('La actividad económica de la empresa es requerido');
            return;
        }

        if(giro_comercial === ''){
            toastr.error('El giro comercial de la empresa es requerido');
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
              formData.append('id', id);
              formData.append('giro_comercial', giro_comercial);
              formData.append('actividad_economica', actividad_economica);
              formData.append('nombre', nombre);
              formData.append('matricula_comercio', matricula_comercio);
              formData.append('nit', nit);
              formData.append('referencia_catastral', referencia_catastral);
              formData.append('tipo_comerciante', tipo_comerciante);
              formData.append('inicio_operaciones', inicio_operaciones);
              formData.append('direccion', direccion);
              formData.append('num_tarjeta', num_tarjeta);
              formData.append('telefono', telefono);
              formData.append('toggle', toggle);

            axios.post('/admin/empresas/editar', formData, {
            })
            .then((response) => {
            //* codigo de Vanessa y esta maloo no sabemos que hace.... 
            //*   var actividad_economica = document.getElementById("select-actividad_economica");
            //*    for (i = 0; i < Object.keys(resp.data).length; i++) {
            //*      var option = document.createElement('option');
            //*     option.value = resp.data[i].id;
            //*      option.text = resp.data[i].apeynom;
            //*      actividad_economica.appendChild(option);
            //*  }
            //.codigo de Vanessa y esta maloo no sabemos que hace.... 

                closeLoading();
                if(response.data.success === 0){
                    toastr.error(response.data.message);
                }
                if(response.data.success === 1){
                  Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: 'Registro Actualizado!',
                          showConfirmButton: true,
                        }).then((result) => {
                        if (result.isConfirmed) {

                          $('#modalEditar').modal('hide');
                          recargar();
                        }
                      });
                }
            })
            .catch((error) => {
              Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Error al actualizar empresa!', 
                          showConfirmButton: true,
                        }).then((result) => {
                        if (result.isConfirmed) 
                        {
                          closeLoading();
                        }
                      });
            });
        }



</script>


@stop