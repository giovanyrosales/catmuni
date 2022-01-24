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
<!-- Función para calcular la recalificación --------------------------------------------------------->

<script>
function extraeranio()
{
      /*Declaramos variables */
      var fecha_pres_balance=(document.getElementById('fecha_pres_balance').value);
      //*Capturando fecha del date
      fechaBalance=fecha_pres_balance;

      //*Extraendo el año de la fecha
      anio=parseInt(String(fechaBalance).substring(0,4));
      //*alert(anio);
      document.getElementById('año_calificacion').value=anio;
}

function calcular()
{

  var licencia=(document.getElementById('selectLicencia').value);
  var  monto_pagar=(document.getElementById('monto_pagar').value);

        if (licencia=='No')
        {
          monto_pagar='$0.00';
          matricula='$0.00';
          document.getElementById('monto_pagar').value= monto_pagar;
          document.getElementById('matricula_imp').innerHTML=matricula;

        }else if(licencia=='Si')
        {
          monto_pagar='$250.00';
          matricula='$5.00';
          document.getElementById('monto_pagar').value= monto_pagar;
          document.getElementById('matricula_imp').innerHTML=matricula;
        }
      }

function calculo()
{
    /*Declaramos variables */

    var deducciones=(document.getElementById('deducciones').value);
    var activo_total=(document.getElementById('activo_total').value);
    var anio_calificacion=(document.getElementById('año_calificacion').value);
    var deducciones_imp=(document.getElementById('deducciones').value);
    var tipo_tarifa=(document.getElementById('tipo_tarifa').value);


var formData = new FormData();

formData.append('deducciones', deducciones);
formData.append('activo_total', activo_total);

axios.post('/admin/empresas/calculo_calificacion', formData, {
        })
            .then((response) => {
              console.log(response);
                closeLoading();
                if(response.data.success ===2){
                  //toastr.error('Muuuuuuuultaaaaaaaaaaaaaaaaaaaaa');
                } 
                if(response.data.success === 1){

                        document.getElementById('activo_imponible').value=response.data.valor;
                        document.getElementById('tipo_tarifa').value=response.data.tarifa;
                        document.getElementById('act_total').innerHTML= activo_total;
                        document.getElementById('anio_calificacion').innerHTML=anio_calificacion;
                        document.getElementById('deducciones_imp').innerHTML=deducciones_imp;
                        document.getElementById('act_imponible_imp').innerHTML=response.data.valor;


                        
                }  
            })
            .catch((error) => {
                toastr.error('Error');
                closeLoading();
            });


}

</script>
<!-- Finaliza función para calcular la recalificación --------------------------------------------------------->


<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Calificar empresa</li>
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

        <form class="form-horizontal" id="formulario-GenerarCalificacion">
        @csrf

        <div class="card card-green">
          <div class="card-header">
          <h5 class="modal-title">Registrar calificación a empresa&nbsp;<span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span></h5>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          
          <div class="card-body">
            
<!-------------------------CONEDIDO (CAMPOS) ----------------------------------------------->


        <!-- Campos del formulario de cobros -->
         <div class="card border-success mb-3"><!-- Panel Datos generales de la empresa -->
         <div class="card-header text-success"><label>I.DATOS DE LA CALIFICACIÓN</label></div>
          <div class="card-body"><!-- Card-body -->
            <div class="row"><!-- /.ROW1 -->
            

              <!-- /.form-group -->
              <div class="col-md-3">
                  <div class="form-group">
                        <label>ACTIVIDAD COMERCIAL:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select Giro Comercial -->
               <div class="col-md-3">
                      <div class="form-group">  
                        <input type="text"  value="{{ $empresa->nombre_giro }}" name="giro_comercial" disabled id="giroc_comercial" class="form-control" required >
                      </div>
                </div>
              <!-- finaliza select Giro Comercial-->
               <!-- /.form-group -->

                <!-- /.form-group -->
                <div class="col-md-3">
                  <div class="form-group">
                        <label>NUMERO DE FICHA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-2">
                  <div class="form-group">
                        <input type="text"  value="{{ $empresa->num_tarjeta }}" name="num_tarjeta" disabled id="num_tarjeta" class="form-control" required >
                  </div>
               </div><!-- /.col-md-6 -->

               <!-- /.form-group -->
               <div class="col-md-3">
                  <div class="form-group">
                        <label>F. PRES. BALANCE:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="form-group">
                        <input  type="date" onchange="extraeranio();" class="form-control text-success" name="fecha_pres_balance" id="fecha_pres_balance" class="form-control" required >
       
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->

              <!-- /.form-group -->
                            <!-- /.form-group -->
                            <div class="col-md-3">
                  <div class="form-group">
                        <label>ACTIVIDAD ECONOMICA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select actividad_economica -->
               <div class="col-md-3">
                      <div class="form-group">
                        <input type="text"  value="{{ $empresa->rubro }}" name="rubro" disabled id="rubro" class="form-control" required >
                      </div>
                </div>
              <!-- finaliza select Giro Comercial-->
               <!-- /.form-group -->
              
            </div> <!-- /.ROW1 -->

            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->



            <div class="card"><!--  II. LICENCIAS  -->
             <div class="card-header text-success"><label> II. LICENCIAS </label></div>
             <div class="card-body"><!-- Card-body -->
               <div class="row"><!-- /.ROW1 -->
            

              <!-- /.form-group -->
              <div class="col-md-3">
                  <div class="form-group">
                        <label>LICENCIA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select Giro Comercial -->
               <div class="col-md-3">
                      <div class="form-group">
                            <!-- Select LICENCIA -live search -->
                                <div class="input-group mb-9">
                                <select 
                                required 
                                onchange="calcular();"
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="selectLicencia" 
                                title="-- Seleccione --"
                                required
                                >
                                    <option value="No">No</option>
                                    <option value="Si">Si</option>
                                </select> 
                                </div>
                          </div>
                  </div>
              <!-- finaliza select CARGAR MULTA-->
               <!-- /.form-group -->

                <!-- /.form-group -->
                <div class="col-md-3">
                  <div class="form-group">
                        <label>MONTO A PAGAR:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="form-group">
                        <input type="text"  disabled placeholder="$0.00" name="monto_pagar" id="monto_pagar" class="form-control" required >
                    
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->  
     
              
            </div> <!-- /.ROW1 -->

            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel Multa -->
        
            <div class="card"><!--  II. Panel Tarifas  -->
            <div class="card-header text-success"><label> III. TARIFAS </label></div>
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

               <!-- /.form-group -->
               <div class="col-md-3">
                  <div class="form-group">
                        <label>ACTIVO TOTAL:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="form-group">
                        <input type="number" onchange="calculo();" placeholder="$00,000.00"  name="activo_total" id="activo_total" class="form-control" required >
                       
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
             
              <!-- /.form-group -->
              <div class="col-md-3">
                  <div class="form-group">
                        <label>DEDUCCIONES:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="form-group">
                        <input type="number" onchange="calculo();" placeholder="$00,000.00"  name="deducciones" id="deducciones" class="form-control" required >
                       
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
             </div><!-- ROW FILA1 -->

             <div class="row"><!-- /.ROW FILA1 -->

               <!-- /.form-group -->
               <div class="col-md-3">
                  <div class="form-group">
                        <label>TIPO DE TARIFA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="form-group">
                        <input type="text" placeholder="Fija o Variable" onchange="select();" disabled name="tipo_tarifa" id="tipo_tarifa" class="form-control" required >
                       
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
     
              <!-- /.form-group -->
                <!-- /.form-group -->
                <div class="col-md-3">
                <div class="form-group">
                      <label>AÑO CALIFICACIÓN:</label>
                </div>
              </div><!-- /.col-md-6 -->
              <div class="col-md-3">
                <div class="form-group">
                       <input type="text" disabled placeholder="0000" name="año_calificacion" id="año_calificacion" class="form-control" required >
                </div>
              </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
             </div><!-- ROW FILA1 -->

             <div class="row"><!-- /.ROW FILA2 -->
                <!-- /.form-group -->
                <div class="col-md-3">
                <div class="form-group">
                      <label>ACTIVO IMPONIBLE:</label>
                </div>
              </div><!-- /.col-md-6 -->
              <div class="col-md-3">
                <div class="form-group">
                       <input type="text" disabled placeholder="$00,000.00" name="activo_imponible" id="activo_imponible" class="form-control" required >
                      
                </div>
              </div><!-- /.col-md-6 -->
            <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>ACTIVIDAD ESPECIFICA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select ACTIVIDAD -->
               <div class="col-md-3">
                      <div class="form-group">
                            <!-- Select ACTIVIDAD -live search -->
                                <div class="input-group mb-9">
                                <select 
                                required 
                                onchange="calcular();"
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="selectLicencia" 
                                title="-- Seleccione un registro--"
                                required
                                >
                                    <option value="No">No</option>
                                    <option value="Si">Si</option>
                                </select> 
                                </div>
                          </div>
                  </div>
              <!-- finaliza select ACTIVIDAD-->
               <!-- /.form-group -->

               
              <!-- /.form-group -->
              <div class="col-md-3">
                  <div class="form-group">
                        <label name="monto_tarifa" id="monto_tarifa">TARIFA: </label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select ACTIVIDAD -->
               <div class="col-md-3">
                      <div class="form-group">
                            <!-- Select ACTIVIDAD -live search -->
                                <div class="input-group mb-9">
                                <select 
                                required 
                                onchange="calcular();"
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="selectLicencia" 
                                title="-- Seleccione un registro--"
                                required
                                >
                                    <option value="No">No</option>
                                    <option value="Si">Si</option>
                                </select> 
                                </div>
                          </div>
                  </div>
              <!-- finaliza select ACTIVIDAD-->
              </div><!-- ROW FILA3 -->        
              </div><!-- /.SUCCESS -->
            </div><!-- /.Panel Tarifas -->
 
  <!-- Finaliza campos del formulario de calificación -->


<!-------------------------FINALIZA CONTEDIDO (CAMPOS) ----------------------------------------------->


            <!-- Fin /.col -->
            </div>
            <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="button" class="btn btn-success float-right" onclick="GenerarCalificacion()"><i class="fas fa-envelope-open-text"></i>
                    &nbsp;Generar Calificación&nbsp;</button>
                    <button type="button" class="btn btn-default" onclick="VerEmpresa({{$empresa->id}} )">Volver</button>
                  </div>
            <!-- /.card-footer -->
          <!-- /.row -->
          </div>
         </div>
        </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
<!-- Finaliza Formulario Calificar Empresa-->

<!--Inicia Modal Registrar Calificación--------------------------------------------------------------->

<div class="modal fade" id="modalCalificacion">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Registrar calificación a empresa&nbsp;<span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formulario-Calificacion1">
              <div class="card-body">

  <!-- Inicia Formulario Calificacion--> 
   <section class="content">
      <div class="container-fluid">
        <form class="form-horizontal" id="formulario-Calificacion">
        @csrf

          <div class="card card-green">
            <div class="card-header">
            <h3 class="card-title">Datos generales.</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->


          <!-- Campos del formulario de cobros -->

         <br>
         <div class="card border-success mb-3"><!-- Panel ubicación del negocio -->
           <div class="card-header text-success"><label>I. UBICACIÓN DEL NEGOCIO</label></div>
            <div class="card-body">

              <div class="row"><!-- /.ROW1 -->

               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Número de ficha:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="form-group">
                        <input type="number"  value="{{ $empresa->num_tarjeta }}" name="num_tarjeta" disabled id="num_tarjeta" class="form-control" required >
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              </div><!-- /.ROW1 -->

            </div>
          </div><!-- /.Panel ubicación del negocio -->

         <div class="card border-success mb-3"><!-- Panel Datos generales de la empresa -->
         <div class="card-header text-success"><label>II. DATOS GENERALES DE LA EMPRESA</label></div>
          <div class="card-body"><!-- Card-body -->
            <div class="row"><!-- /.ROW1 -->
            
             <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Nombre del negocio:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input type="text"  value="{{ $empresa->nombre }}" name="nombre" disabled id="nombre_empresa" class="form-control" required >
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
                            <!-- /.form-group -->
                            <div class="col-md-6">
                  <div class="form-group">
                        <label>Giro Comercial:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input type="text"  value="{{ $empresa->nombre_giro }}" name="nombre_giro" disabled id="nombre_giro" class="form-control" required >
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>Fecha de inicio de operaciones:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input  type="date" class="form-control text-success" disabled value="{{$empresa->inicio_operaciones}}" name="created_at" id="created_at" class="form-control" required >
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Dirección:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input  type="text" disabled value="{{ $empresa->direccion }}" name="direccion" id="direccion" class="form-control" required >
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->

               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Representante legal:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input type="text" disabled value="{{ $empresa->contribuyente }}&nbsp;{{ $empresa->apellido }}" name="contribuyente" id="contribuyente" class="form-control" >
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>Fecha de pre.  balance o d. jurada:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input type="text" disabled name="fechabalanceodjurada" id="fechabalanceodjurada" class="form-control" >
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
              
            </div> <!-- /.ROW1 -->

            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->
          
            <!-- /.col1 -->
          <div class="card border-success mb-3"><!-- Panel III. LICENCIAS Y PERMISOS -->
              <div class="card-header text-success"><label>III. LICENCIAS Y PERMISOS</label></div>
                <div class="card-body">

               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
      
                  <table border="1" width:760px;>
                          <tr>
                            <th scope="col">ACTIVIDAD ECONOMICA </th>
                            <th scope="col">BASE IMPONIBLE</th>
                            <th scope="col">LICENCIA</th>
                            <th scope="col">MATRICULA</th>
                            <th scope="col">PAGO POR MAT. O PER.</th>
                          </tr>

                          <tr>
                            <td> </td>
                            <td>1</td>
                            <td><h6 name="licencia_imp" id="licencia_imp"> </h6></td>
                            <td><h6 name="matricula_imp" id="matricula_imp"> </h6></td>
                            <td>$365.00</td>
                          </tr>

                          <tr>
                            <td> </td>
                            <td rowspan="3" colspan="2">&nbsp; </td>
                            <td colspan="2">&nbsp;</td>
                          </tr>

                          <tr>
                            <td> </td>
                            <td>Fondo F. P. </td>
                            <td>$0.00</td>
                          </tr>
                          <tr>
                            <th scope="row"> </th>
                            <td><strong>Pago Anual </strong></td>
                            <td>$365.00</td>
                          </tr>
                        </table>
                      
                   </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
                </div> <!-- /.card-header text-success -->
              </div> <!-- /.Panel III. LICENCIAS Y PERMISOS -->
        

          <div class="card border-success mb-3"><!-- Panel IV. CALIFICACION DE LA EMPRESA - TARIFA FIJA -->
           <div class="card-header text-success"><label>IV. CALIFICACION DE LA EMPRESA - TARIFA FIJA</label></div>
            <div class="card-body">

               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
                  <table border="1" width:760px;>
                      <tr>
                        <th scope="col">ACTIVIDAD ECONOMICA</th>
                        <th scope="col"> </th>
                        <th scope="col"> BASE IMPONIBLE </th>
                        <th scope="col">TARIFA (COLONES)</th> 
                        <th scope="col">TARIFA (DOLARES)</th>
                      </tr>

                      <tr>
                        <td>Venta de licor</td>
                        <td> 13623 </td>
                        <td>1</td>
                        <td> ₡50.00 </td>
                        <td> $5.71</td>
                      </tr>

                      <tr>
                        <td></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td>$0.00</td>
                      </tr>

                      <tr>
                        <td></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td>$0.00</td>
                      </tr>

                      <tr>
                        <td></td>
                        <td colspan="2"> </td>
                        <td>Fondo F. P. 5% </td>
                        <td>TOTAL IMPUESTO</td>
                      </tr>

                      <tr>
                        <th scope="row">MENSUAL</th>
                        <td colspan="2"> </td>
                        <td><strong>$ 0.29</strong></td>
                        <td><strong>$6.00</strong></td>
                      </tr>
                    </table>
                    </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div> <!-- /.card-header text-success -->
          </div><!-- /.Panel IV. CALIFICACION DE LA EMPRESA - TARIFA FIJA -->

          <div class="card border-success mb-3"><!-- Panel V. CALIFICACION DE LA EMPRESA - TARIFA VARIABLE -->
           <div class="card-header text-success"><label>V. CALIFICACION DE LA EMPRESA - TARIFA VARIABLE</label></div>
            <div class="card-body">

               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
                    <table border="1" width:860px;>
                      <tr>
                        <th scope="col">EMPRESA</th>
                        <th scope="col">ACTIVO TOTAL</th>
                        <th scope="col">DEDUCCIONES</th>
                        <th scope="col">ACTIVO IMPONIBLE</th> 
                        <th scope="col">EJERCICIO</th>
                      </tr>

                      <tr>
                        <td align="center"><label> {{$empresa->nombre}} </label></td>
                        <td align="center"><label>$<label name="act_total" id="act_total"> </label></label> </td>
                        <td align="center"><label>$<label name="deducciones_imp" id="deducciones_imp"></label></label> </td>
                        <td align="center"><label name="act_imponible_imp" id="act_imponible_imp"> </label></td>
                        <td align="center"><label name="anio_calificacion" id="anio_calificacion"></label></td>
                      </tr>

                      <tr>
                        <td>ACTIVIDAD ECONOMICA / TARIFA </td>
                        <td>CODIGO</td>
                        <td>IMPUESTO:</td>
                        <td>MENSUAL</td>
                        <td>ANUAL</td>
                      </tr>

                      <tr>
                        <td align="center"><h6 name="actividad_economica" id="actividad_economica"> </h6></td>
                        <td align="center">{{$empresa->id_act_economica}}</td>
                        <td> </td>
                        <td>$</td>
                        <td>$0.00</td>
                      </tr>

                      <tr>
                        <td rowspan="2"></td>
                        <td colspan="2">Fondo Fiestas Patronales 5%</td>
                        <td>$ </td>
                        <td>$</td>
                      </tr>

                      <tr>
                        <td colspan="2"><label> IMPUESTO</label></td>
                        <td><strong>$ </strong></td>
                        <td><strong>$</strong></td>
                      </tr>
                    </table>
                        </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div> <!-- /.card-header text-success -->
          </div><!-- /.Panel V. CALIFICACION DE LA EMPRESA - TARIFA VARIABLE -->

          <div class="card border-success mb-3"><!-- PanelVI. ROTULOS -->
           <div class="card-header text-success"><label>VI. ROTULOS</label></div>
            <div class="card-body">

               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
                  <table border="1" width:760px;>
                        <tr>
                          <th scope="col">EMPRESA</th>
                          <th scope="col">ACTIVO TOTAL</th>
                          <th scope="col">DEDUCCIONES</th>
                          <th scope="col">ACTIVO IMPONIBLE</th> 
                          <th scope="col"> EJERCICIO</th>
                        </tr>

                        <tr>
                          <td>Venta de licor</td>
                          <td> </td>
                          <td> </td>
                          <td>$</td>
                          <td>2022</td>
                        </tr>

                        <tr>
                          <td>ACTIVIDAD ECONOMICA / TARIFA </td>
                          <td>CODIGO</td>
                          <td>IMPUESTO:</td>
                          <td>MENSUAL</td>
                          <td>ANUAL</td>
                        </tr>

                        <tr>
                          <td>Comercio</td>
                          <td>2</td>
                          <td> </td>
                          <td>$</td>
                          <td>$0.00</td>
                        </tr>

                        <tr>
                          <td rowspan="2"></td>
                          <td colspan="2">Fondo Fiestas Patronales 5%</td>
                          <td>$ </td>
                          <td>$</td>
                        </tr>

                        <tr>
                          <td colspan="2">TOTAL IMPUESTO</td>
                          <td><strong>$ </strong></td>
                          <td><strong>$</strong></td>
                        </tr>
                      </table>
                      </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div> <!-- /.card-header text-success -->
          </div><!-- /.Panel VI. ROTULOS -->

          <div class="card border-success mb-3"><!-- VII. MULTAS -->
           <div class="card-header text-success"><label>VII. MULTAS</label></div>
            <div class="card-body">

               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
                  <table border="1" width:760px;>
                      <tr>
                        <th scope="col">BASE IMPONIBLE</th>
                        <th scope="col">TARIFA</th>
                        <th scope="col">P.MENSUAL</th>
                        <th scope="col" colspan="2">PERIODO</th> 

                      </tr>

                      <tr>
                        <td>&nbsp;</td>
                        <td> </td>
                        <td> </td>
                        <td>  </td>
                        <td></td>
                      </tr>

                      <tr>
                        <td>&nbsp;</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                      </tr>

                      <tr>
                        <td>&nbsp;</td>
                        <td> </td>
                        <td> </td>
                        <td></td>
                        <td></td>
                      </tr>

                      <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>$</td>
                        <td>$</td>
                        <td>Fondo F. P. 5%</td>
                      </tr>

                      <tr>
                        <td>&nbsp;</td>
                        <td>PAGO MENSUAL</td>
                        <td> $0.00</td>
                        <td><strong>$ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
                        <td><strong>$</strong></td>
                      </tr>
                    </table>
                  
                      </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div> <!-- /.card-header text-success -->
          </div><!-- /.Panel VII. MULTAS -->


  <!-- Finaliza campos del formulario de calificación -->


         <!-- /.card-body -->
         <div class="card-footer">
         <button type="button" class="btn btn-secondary" onclick="ImpimirCalificacion()"><i class="fa fa-print">
         </i>&nbsp; Impimir Calificación&nbsp;</button>
         <button type="button" class="btn btn-success float-right" onclick="RegistrarCobro()"><i class="fas fa-edit">
         </i> &nbsp;Registrar Calificación&nbsp;</button>
         <br><br><button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
         <!-- /.card-footer -->
         </div>
        </div>
      <!-- /.card -->
      </form>
      <!-- /form1 -->
      </div>
    <!-- /.container-fluid -->
    </section>

       </form> <!-- /.formulario-Calificacion2 -->
      </div> <!-- /.Card-body -->
     </div> <!-- /.modalCalificacion -->
   </div> <!-- /.modal-dialog modal-xl -->
  </div> <!-- /.modal-content -->
 </div> <!-- /.modal-body -->

<!-- Finaliza Modal Registrar Calificación--------------------------------------------------------->





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



    <script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>


<script>
  
function GenerarCalificacion(){
            /*Declaramos variables */
            var fecha_pres_balance=(document.getElementById('fecha_pres_balance').value);
            var activo_total=(document.getElementById('activo_total').value);
            var deducciones=(document.getElementById('deducciones').value);
            var rubro=(document.getElementById('rubro').value);
            var licencia_imp=(document.getElementById('monto_pagar').value);

         
            if(fecha_pres_balance === ''){
                    toastr.error('La fecha que presenta el balance es requerida.');
                    return;
                }

            if(activo_total === ''){
                    toastr.error('El dato activo activo total es requerido.');
                    return;
                }
              
              if(deducciones === ''){
                    toastr.error('El dato deducciones es requerido.');
                    return;
                }
            

            document.getElementById('fechabalanceodjurada').value=fecha_pres_balance;
            document.getElementById('actividad_economica').innerHTML=rubro; 
            document.getElementById('licencia_imp').innerHTML=licencia_imp; 

            $('#modalCalificacion').modal('show');
        }

function VerEmpresa(id){

window.location.href="{{ url('/admin/empresas/show') }}/"+id;

}

function nuevo(){
  

        var contribuyente = document.getElementById('select-contribuyente').value;
        var estado_empresa = document.getElementById('select-estado_empresa').value;
        var giro_comercial = document.getElementById('select-giro_comercial').value;
        var actividad_economica = document.getElementById('select-actividad_economica').value;
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
                    toastr.success('Empresa registrada correctamente');
                    location.reload();
                }
               
            })
            .catch((error) => {
                toastr.error('Error al registrar empresa');
                closeLoading();
            });
 }



</script> 

<style>
@media screen 
    and (max-width: 760px), (min-device-width: 768px) 
    and (max-device-width: 1024px)  {

		/* Force table to not be like tables anymore */
		table, thead, tbody, th, td, tr {
			display: block;
		}

		/* Hide table headers (but not display: none;, for accessibility) */
		thead tr {
			position: absolute;
			top: -9999px;
			left: -9999px;
		}

    tr {
      margin: 0 0 1rem 0;
    }
      
    tr:nth-child(odd) {
      background: #ccc;
    }
    
		td {
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee;
			position: relative;
			padding-left: 50%;
		}

		td:before {
			/* Now like a table header */
			position: absolute;
			/* Top/left values mimic padding */
			top: 0;
			left: 6px;
			width: 45%;
			padding-right: 10px;
			white-space: nowrap;
		}



    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
  } 
</style>
    
@endsection