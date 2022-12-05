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

        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title"><i class="far fa-plus-square"></i> &nbsp;Formulario de datos de la empresa.</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <div class="card border-info mb-3"><!-- Panel Asignar empresa -->
          <div class="card-header text-info"><label>DATOS GENERALES</label></div>
            <div class="card-body"><!-- Card-body -->
        
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
                    <input type="text" name="direccionE" id="direccionE" class="form-control" required placeholder="Dirección de la empresa"  >
                  </div> </div>   
                <!-- /.form-group -->

      <!-- Asignar Representante-->  
       <!-- /.form-group -->
                 <div class="row">     
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
                                  data-show-subtext="true" 
                                  data-live-search="true"   
                                  id="select-actividad_economica" 
                                  title="-- Selecione la actividad --"
                                  onchange="excepciones_especificas()"
                                  >
                                    @foreach($actividadeseconomicas as $actEc)
                                    <option value="{{ $actEc->id }}" data-actividad="{{ $actEc->codigo_atc_economica }}"> {{ $actEc->rubro }} ({{ $actEc->codigo_atc_economica }})</option>
                                    @endforeach 
                                  </select> 
                            </div>
                            <!-- finaliza asignar actividad economica-->
                          </div>
                      </div>
              <!-- /.form-group -->
                  <!-- /.form-group -->
                  <div class="col-md-6">
                        <div class="form-group">
                          <label>Giro comercial:</label>
                              <!-- Select Giro Comercial -live search -->
                                  <div class="input-group mb-9">
                                  <select 
                                  required 
                                  class="selectpicker"
                                  data-show-subtext="true" 
                                  data-live-search="true"  
                                  id="select-giro_comercial" 
                                  title="--  Selecione un giro  --"
                                  onchange="detallematricula(),llenardetalle_matriculas(),resetmatriz()"
                                  required
                                  >
                                    @foreach($giroscomerciales as $giro)
                                    <option value="{{ $giro->id }}" data-matricula="{{ $giro->matricula }}"> {{ $giro->nombre_giro }}
                                    </option>
                                    @endforeach 
                                  </select> 
                                  </div>
                            <!-- finaliza select Giro Comercial-->
                        </div>
                    </div>
               </div> <!-- /.ROW -->
               <!-- /.form-group -->
            </div> 
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
                  <div class="col-md-6">
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
              <!-- /.form-group -->
            <div class="row"> 
                    <div class="col-md-6" id="Excepcion_especifica">
                          <div class="form-group">
                              <label>¿Caso Especial?</label>
                                  <br>
                                      <label class="switch" style="margin-top:10px">
                                          <input type="checkbox" id="toggle-excepcion_especifica">
                                            <div class="slider round">
                                                <span class="on">SI</span>
                                                <span class="off">NO</span>
                                            </div>
                                        </label>
                          </div>
                    </div>

               </div>
            <!-- /.form-group -->
            
            <!-- Fin /.col -->
            </div>
          <!-- /.row -->
          </div>
        </div>
      </div>
          
  <!-------------------------------- PANEL LLENAR CANTIDAD MATRICRULAS Y SU ESPECIFICACIÓN ------------------------------------- -->

        <div class="card border-info mb-3" id="detalle_matricula"><!-- Panel Asignar empresa -->
          <div class="card-header text-success"><label>DETALLE Y ESPECIFICACIÓN DE LA MATRICULA</label></div>
            <div class="card-body"><!-- Card-body -->
              <div class="row"><!-- /.ROW1 -->

                          <!--Campos de la especificacion y detalle-->
                                    <table class="table" id="Matriculas" style="border: 80px" data-toggle="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 30%; text-align: center">Cantidad</th>
                                        <th style="width: 30%; text-align: center">Total Matrículas</th>
                                        <th style="width: 30%; text-align: center">Pago Mensual</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <td align="center">
                                    <input  id='cantidad' class='form-control' disabled min='1' style='max-width: 250px; text-align:center;' type='text' value='' onchange="calculo_cantidad_mat()" />
                                    <input type="hidden" id='id_matriculas'/>
                                    </td>                         
                                    <td align="center">
                                    <input  id='monto' class='form-control' disabled min='1' style='max-width: 250px;text-align:center;' type='text' value=''/>
                                    <input type="hidden" id='monto_hidden'/>
                                    <input type="hidden" id='Total_monto_hidden'/>
                                    </td>
                                    <td align="center">
                                    <input  id='pago_mensual' class='form-control' disabled min='1' style='max-width: 250px;text-align:center;' type='text' value=''/>
                                    <input type="hidden" id='pago_mensual_hidden' />
                                    <input type="hidden" id='Total_pago_mensual_hidden' />
                                    </td>
                                    </tr>
                                        </tbody>
                                        </table>
                                  

<!-----------------------------------Inicia Contenido Matrícuila específica------------------------------------------->

                             
                                <table class="table" id="matrizMatriculas" style="border: 100px" data-toggle="table">
                                <thead>
                                <tr>                           
                                    <th style="width: 25%; text-align: center">Código Municipal</th>
                                    <th style="width: 15%; text-align: center">Código</th>
                                    <th style="width: 20%; text-align: center">N° serie</th>
                                    <th style="width: 35%; text-align: center">Dirección</th>
                                    <th style="width: 15%; text-align: center">&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody>
                             
                                </tbody>
                                </table>
                                <br>
                                     <button type="button"  class="btn btn-block btn-success"   id="btnAddmatriculaEspecifica"><i class="fas fa-plus"></i> &nbsp; Especificar Matrícula</button>             
                                <br>
                                </div>

                        <!--/.Campos de la especificacion y detalle-->

               </div><!-- /.col-md-6 -->
            <!-- Finaliza Empresa-->
         </div>
        


<!--------------------------- FINALIZA PANEL LLENAR CANTIDAD MATRICRULAS Y SU ESPECIFICACIÓN ------------------------------- -->

         </div>
                  <!-- /.card-body -->
                  <div class="card-footer"> 
                  <button type="button" onclick="location.href='{{ url('/panel')}}'" class="btn btn-default">
                  <i class="fas fa-times-circle"></i>&nbsp;Cancelar</button>
                  <button type="button" class="btn btn-info float-right" onclick="nuevo()">
                  <i class="fas fa-save"></i>&nbsp;Guardar</button>
          </div>
         <!-- /.card-footer -->
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
 

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


    <script type="text/javascript">

      //** Las funciones que se llaman aquí inician automaticamente */
      $(document).ready(function()
        {
            document.getElementById("divcontenedor").style.display = "block";
           // document.getElementById("select-actividad_especifica").style.backgroundColor = 'green';
           
           window.cantidadMatricula=0;

           $('#actividad-especificaDIV').hide();
           $('#detalle_matricula').hide();
           $('#Excepcion_especifica').hide();
           

          }); //** Las funciones que se llaman aquí inician automaticamente */

          
    $("#btnAddmatriculaEspecifica").on("click", function () {

              //agrega las filas dinamicamente

              var markup = "<tr>"+

                  "<td>"+
                  "<input name='cod_municipal[]'  onchange='matricula_especificas(this)' class='form-control' min='1' style='max-width: 250px' type='Text' value=''/>"+
                  "</td>"+

                  "<td>"+
                  "<input name='codigo[]'  class='form-control'  min='1' style='max-width: 150px' type='number' value=''/>"+
                  "</td>"+

                  
                  "<td>"+
                  "<input name='num_serie[]'  class='form-control'  min='1' style='max-width: 200px' type='text' value=''/>"+
                  "</td>"+

                  "<td>"+
                  "<input name='direccionM[]' class='form-control'  min='1' style='max-width: 350px' type='text' value=''/>"+
                  "</td>"+

                  "<td>"+
                  "<button type='button' class='btn btn-block btn-danger' onclick='borrarFila(this)'><i class='fas fa-trash'></i></button>"+
                  "</td>"+

                  "</tr>";

                    // $("tbody").append(markup);
                    $("#matrizMatriculas tbody").append(markup);

                    cantidadMatricula=cantidadMatricula+1;

                    $('#cantidad').val(cantidadMatricula);

                    var monto_matricula=document.getElementById("monto_hidden").value;
                    var tarifa=document.getElementById("pago_mensual_hidden").value;

                    //Operación
                    var Total_pago_mensual=tarifa*cantidadMatricula;
                    var monto_total=monto_matricula*cantidadMatricula;

                    //Imprimiendo resultado
                    $('#monto').val('$'+monto_total);
                    $('#pago_mensual').val('$'+Total_pago_mensual);      

                    //resultado sin signo $
                    $('#Total_monto_hidden').val(monto_total);
                    $('#Total_pago_mensual_hidden').val(Total_pago_mensual);       

                    });

    </script>

<script>

function resetmatriz(){
  $("#matrizMatriculas tbody tr").remove();
  document.getElementById('cantidad').value='';
  document.getElementById('monto').value='';
  document.getElementById('pago_mensual').value='';
  window.cantidadMatricula=0;
}

function calculo_cantidad_mat(){
 
 var monto_matricula=document.getElementById("monto_hidden").value;
 var tarifa=document.getElementById("pago_mensual_hidden").value;
 var cantidadMatricula=document.getElementById("cantidad").value;

 //Operación
 var Total_pago_mensual=tarifa*cantidadMatricula;
 var monto_total=monto_matricula*cantidadMatricula;

 //Imprimiendo resultado
 $('#monto').val('$'+monto_total);
 $('#pago_mensual').val('$'+Total_pago_mensual);      

 //resultado sin signo $
 $('#Total_monto_hidden').val(monto_total);
 $('#Total_pago_mensual_hidden').val(Total_pago_mensual);       

}

function matricula_especificas(e){
var table = e.parentNode.parentNode; // fila de la tabla

       var cod_municipal = table.cells[0].children[0]; //
       var codigo = table.cells[1].children[0]; //
       var num_serie = table.cells[2].children[0];
       var direccion = table.cells[3].children[0];    
}
 
function borrarFila(elemento){

cantidadMatricula=cantidadMatricula-1;
 $('#cantidad').val(cantidadMatricula);

        var monto_matricula=document.getElementById("monto_hidden").value;
        var tarifa=document.getElementById("pago_mensual_hidden").value;

        //Operación
        var Total_pago_mensual=tarifa*cantidadMatricula;
        var monto_total=monto_matricula*cantidadMatricula;

        //Imprimiendo resultado modo vista usuario con $
        $('#monto').val('$'+monto_total);
        $('#pago_mensual').val('$'+Total_pago_mensual);  

        //resultado sin signo $
        $('#Total_monto_hidden').val(monto_total);
        $('#Total_pago_mensual_hidden').val(Total_pago_mensual);

      var tabla = elemento.parentNode.parentNode;
      tabla.parentNode.removeChild(tabla);
}

function detallematricula(){

var sel = document.getElementById("select-giro_comercial");  
var selected = sel.options[sel.selectedIndex];
var ap_matriculas=selected.getAttribute('data-matricula');


                if(ap_matriculas=== 'SI')
                {   
                     $('#detalle_matricula').show();
                }else{
                  $('#detalle_matricula').hide();
                }
}

// Función para llenar el detalle de matriculas

function llenardetalle_matriculas(){

             var id_giro_comercial = document.getElementById('select-giro_comercial').value;
          
             var formData = new FormData();
             formData.append('id_giro_comercial', id_giro_comercial);
             
             axios.post('/admin/empresas/llenar_detalle_matriculas', formData, {
              })
            .then((response) => {

             if(response.data.success === 1)
                   {
                    
                       document.getElementById('id_matriculas').value=response.data.matricula_Seleccionada.id;
                       document.getElementById('monto_hidden').value=response.data.matricula_Seleccionada.monto;
                       document.getElementById('pago_mensual_hidden').value=response.data.matricula_Seleccionada.tarifa;
                   }
               })
            .catch((error) => {
               // toastr.error('Error al registrar empresa');
               
            });
            
             
          }

// Termina función
function excepciones_especificas(){
  var sel = document.getElementById("select-actividad_economica");  
  var selected = sel.options[sel.selectedIndex];
  var Codigo_Act=selected.getAttribute('data-actividad');

  if(Codigo_Act==='11802')
                {   
                     $('#Excepcion_especifica').show();
                }else{
                  $('#Excepcion_especifica').hide();
                }

}
function nuevo(){
  

        var contribuyente = document.getElementById('select-contribuyente').value;
        var giro_comercial = document.getElementById('select-giro_comercial').value;
        var actividad_economica = document.getElementById('select-actividad_economica').value;
        var nombre = document.getElementById('nombre').value;
        var matricula_comercio = document.getElementById('matricula_comercio').value;
        var nit = document.getElementById('nit').value;
        var referencia_catastral = document.getElementById('referencia_catastral').value;
        var tipo_comerciante = document.getElementById('tipo_comerciante').value;
        var inicio_operaciones = document.getElementById('inicio_operaciones').value;
        var direccionE = document.getElementById('direccionE').value;
        var num_tarjeta = document.getElementById('num_tarjeta').value;
        var telefono = document.getElementById('telefono').value;

        var t = document.getElementById('toggle-excepcion_especifica').checked;
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
        if(direccionE === ''){
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

        
        var formData = new FormData();

        //**** Validar */
        var hayregistro=0; 
        var nRegistro = $('#matrizMatriculas >tbody >tr').length;

        if(giro_comercial!='1')
               {
                //**** Datos detalle matricula */
                var tipo_matricula = document.getElementById("id_matriculas").value; 
                var cantidad = document.getElementById("cantidad").value; 

                //**** Datos especificar matricula */
                var cod_municipal = $("input[name='cod_municipal[]']").map(function(){return $(this).val();}).get();
                var codigo= $("input[name='codigo[]']").map(function(){return $(this).val();}).get();
                var num_serie = $("input[name='num_serie[]']").map(function(){return $(this).val();}).get();
                var direccionM = $("input[name='direccionM[]']").map(function(){return $(this).val();}).get();
                
                if (nRegistro <= 0){
                                    modalMensaje('Registro Vacio', 'Debe especificar al menos una matrícula');
                                    return;
                                    }


                for(var a = 0; a < cod_municipal.length; a++){
                var DatoCod_municipal = cod_municipal[a];
                if(DatoCod_municipal == ""){
                          modalMensaje('Código Municipal', 'Debe digitar un código municipal');
                          return;
                          }
                }

                for(var b = 0; b < codigo.length; b++){
                var DatoCodigo = codigo[b];
                if(DatoCodigo == ""){
                                    modalMensaje('Código', 'Debe digitar un código');
                                    return;
                                }
                }

                for(var c = 0; c < num_serie.length; c++){
                    var DatoNum_serie = num_serie[c];
                    if(DatoNum_serie == ""){
                                        modalMensaje('Número de serie', 'Debe digitar un número de serie');
                                        return;
                                    }
                    }

                  for(var e = 0; e < direccionM.length; e++){
                      var DatoDireccion =direccionM[e];
                      if(DatoDireccion == ""){
                                          modalMensaje('Dirección', 'Debe digitar una dirección');
                                          return;
                                        }

                        }       
                //**** Fin de validar */

                    formData.append('tipo_matricula', tipo_matricula);
                    formData.append('cantidad', cantidad);

                    // llenar array para enviar
                    for(var f = 0; f < num_serie.length; f++){
                    
                    formData.append('cod_municipal[]', cod_municipal[f]);
                    formData.append('codigo[]', codigo[f]);
                    formData.append('num_serie[]', num_serie[f]);
                    formData.append('direccionM[]', direccionM[f]);
                    console.log(cod_municipal[f],codigo[f],num_serie[f],direccionM[f]);

                    }
          }//**** Fin de si hay registro */
       
        openLoading();
        formData.append('contribuyente', contribuyente);
        formData.append('giro_comercial', giro_comercial);
        formData.append('actividad_economica', actividad_economica);
        formData.append('nombre', nombre);
        formData.append('matricula_comercio', matricula_comercio);
        formData.append('nit', nit);
        formData.append('referencia_catastral', referencia_catastral);
        formData.append('tipo_comerciante', tipo_comerciante);
        formData.append('inicio_operaciones', inicio_operaciones);
        formData.append('direccionE', direccionE);
        formData.append('num_tarjeta', num_tarjeta);
        formData.append('telefono', telefono);
        formData.append('toggle', toggle);

        axios.post('/admin/empresa/nueva', formData, {
        })
            .then((response) => {
                closeLoading();
                if(response.data.success === 0){
                    toastr.error(response.data.message);
          
                }
                if(response.data.success === 1){
             
                  Swal.fire({
                          position:'top-end',
                          icon: 'success',
                          title: '¡Empresa registrada correctamente!',
                          showConfirmButton: true,
                         
                        }).then((result) => {
                        if (result.isConfirmed) {
                              location.reload();
                            }
                        });
                          
                   
                }
               
            })
            .catch((error) => {
                
                Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Error al registrar empresa!', 
                        }).then((result) => {
                        if (result.isConfirmed) {
                              location.reload();
                            }
                        });
            });
 }

 function modalMensaje(titulo, mensaje){
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

</script> 

    
@endsection

