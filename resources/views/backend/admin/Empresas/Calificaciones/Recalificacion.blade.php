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
<!-- Función para calcular la calificación --------------------------------------------------------->
<script type="text/javascript">
    function deseleccionarCheck()
      {
          //** Quitamos la selección del check caso especial */
          document.getElementById('gridCheck').checked=false;
          document.getElementById('tarifaAplicada').value='';
      }

    function desbloqTarifa()
      {
          if(document.getElementById('gridCheck').checked)
            {
            document.getElementById('tarifaAplicada').disabled=false;
            document.getElementById('tarifaAplicada').value='';

            }else{
                  document.getElementById('tarifaAplicada').disabled=true; 
                 }  
      }

    function f1(){
                $('#monto_tarifa').hide();
                $('#selectTarifa').hide();
                $('#tarifaAplicada').hide();
                $('#tarifa').hide();
                $('#checkCasoEspecial').hide();
                $('#btn_imprimirCalificacion').hide();
                $('#cerrarModal2').hide();
                $('#cerrarcalificacion2').hide();

                //**** Para llenar el select de año de calificación *****//
                var n = (new Date()).getFullYear()
                var select = document.getElementById("año_calificacion");
                for(var i = n; i>=1900; i--)select.options.add(new Option(i,i)); 

              
}
 
window.onload = f1;

</script>

<script>

function resetea()
{
 vacio='';
 document.getElementById('tarifaAplicada').value=vacio;
 document.getElementById('tarifaAplicadaValor').value=vacio; 

 document.getElementById('ImpuestoAnualVariableDolar_imp').innerHTML=vacio; 
 document.getElementById('fondoFPVMensualDolar_imp').innerHTML=vacio; 
 document.getElementById('fondoFPVAnualDolar_imp').innerHTML=vacio; 
 document.getElementById('ImpuestoTotalAnualDolar_imp').innerHTML=vacio;  
 document.getElementById('tarifaenColonesValor_imp').value=vacio; 
}

function calculo(id_act_economica)
{   //** Bloqueamos el input de la tarifa */
    document.getElementById('tarifaAplicada').disabled=true;

    /*Declaramos variables */
    var  licencia=(document.getElementById('selectLicencia').value);
    var  matricula=(document.getElementById('matriculaValorTotal').value);

    var deducciones=(document.getElementById('deducciones').value);
    var activo_total=(document.getElementById('activo_total').value);
    var anio_calificacion=(document.getElementById('año_calificacion').value);
    var deducciones_imp=(document.getElementById('deducciones').value);
    var tipo_tarifa=(document.getElementById('tipo_tarifa').value);
    var ValortarifaAplicada=(document.getElementById('tarifaAplicadaValor').value);
    var tarifaAplicadaCasoEspecial=(document.getElementById('tarifaAplicada').value)
    var id_actividad_especifica={{ $empresa->id_actividad_especifica}};
    var estado_calificacion=(document.getElementById('estado_calificacion').value);
    var fecha_pres_balance=(document.getElementById('fecha_pres_balance').value);
    var año_calificacion=(document.getElementById('año_calificacion').value);

    if(document.getElementById('gridCheck').checked)
          {
            CasoEspecial=1;
          }else
              {
                CasoEspecial=0;
              }

var formData = new FormData();

formData.append('CasoEspecial', CasoEspecial);
formData.append('tarifaAplicadaCasoEspecial', tarifaAplicadaCasoEspecial);
formData.append('deducciones', deducciones);
formData.append('activo_total', activo_total);
formData.append('ValortarifaAplicada', ValortarifaAplicada);
formData.append('licencia', licencia);
formData.append('matricula', matricula);
formData.append('id_act_economica', id_act_economica);
formData.append('id_actividad_especifica', id_actividad_especifica);
formData.append('estado_calificacion', estado_calificacion);
formData.append('fecha_pres_balance', fecha_pres_balance);
formData.append('año_calificacion', año_calificacion);

axios.post('/admin/empresas/calculo_calificacion', formData, {
        })
            .then((response) => {
              console.log(response);
                closeLoading();
                if(response.data.success ===2){
                  //toastr.error('Error');
                } 
                if(response.data.success === 1){

           //Impresiones en tabla licencias y permisos.
           document.getElementById('pagolicenciaMatricula_imp').innerHTML=response.data.licenciaMatriculaSigno;
            document.getElementById('pagolicenciaMatriculaValor_imp').value=response.data.licenciaMatricula;
            document.getElementById('fondoFM_imp').innerHTML=response.data.fondoFLMSigno;
            document.getElementById('fondoFMValor_imp').value=response.data.fondoFLM,
            document.getElementById('PagoAnualLicencias_imp').innerHTML=response.data.PagoAnualLicenciasSigno;
            document.getElementById('PagoAnualPermisos_imp').value=response.data.PagoAnualLicenciasValor;
 	          document.getElementById('licencia_imp').innerHTML=response.data.licenciaSigno;
            document.getElementById('monto_pagar_matricula_imp').innerHTML='{{$montoMatriculaValor}}';
            document.getElementById('multaBalance_imp').innerHTML=response.data.multabalance; 
            document.getElementById('monto_pagar_matriculaValor_imp').value='{{$monto}}';
            document.getElementById('monto_pagar_licenciaValor_imp').value=response.data.licencia;
            //Terminan Impresiones en tabla licencias y permisos.
              
                        document.getElementById('activo_imponible').value=response.data.valor;
                        document.getElementById('tipo_tarifa').value=response.data.tarifa;
                        document.getElementById('act_total').innerHTML= activo_total;
                        document.getElementById('anio_calificacion').innerHTML=anio_calificacion;
                        document.getElementById('deducciones_imp').innerHTML=deducciones_imp;
                        document.getElementById('act_imponible_imp').innerHTML=response.data.valor;
                        document.getElementById('act_imponibleValor_imp').value=response.data.activo_imponible;

                        if(activo_total==='')
                        {                     
                          $("#monto_tarifa").hide();
                          $('#tarifaAplicada').hide();
                          $('#tarifa').hide();

                          document.getElementById('tipo_tarifa').value='';
                          document.getElementById('activo_imponible').value='';
                          document.getElementById('tarifaAplicada').value='';
                          document.getElementById('tarifaAplicadaValor').value='';
                        }
                        if(deducciones==='')
                        {
                          $("#monto_tarifa").hide();
                          $('#tarifaAplicada').hide();
                          $('#tarifa').hide();
                          vacio='';
                          document.getElementById('tipo_tarifa').value=vacio;
                          document.getElementById('activo_imponible').value=vacio;
                          document.getElementById('tarifaAplicada').value=vacio;
                          document.getElementById('tarifaAplicadaValor').value=vacio;
                          
                        }
                       
                        if(response.data.tarifa==='Fija')
                        {
                          //**Estos dos campos son versatiles ya que se usan tambien en fija *//
                          document.getElementById('tarifaAplicadaMensualValor_imp').value=response.data.tarifaFijaDolar; 
                          document.getElementById('Total_ImpuestoValor_imp').value=response.data.Total_ImpuestoFijoDolarValor;

                       
                        //** Se cargan los datos en el formulario *//
                        document.getElementById('tarifaAplicada').value=response.data.tarifaFijaMensualDolarSigno;
                        document.getElementById('tarifaAplicadaValor').value=response.data.tarifaFijaDolar; 
                        
                        //** Se cargan los datos en el modal generado de la calificación *//
                        document.getElementById('FondoF_imp').innerHTML=response.data.FondoF; 
                        document.getElementById('Total_Impuesto_imp').innerHTML=response.data.Total_ImpuestoFijoDolarSigno;
                        document.getElementById('tarifaenColonesSigno_imp').innerHTML=response.data.tarifaenColonesSigno; 
                        document.getElementById('tarifaenColonesValor_imp').value=response.data.impuesto_mensualFijo;                      
                        document.getElementById('tarifaAplicadaMensual_imp').innerHTML=response.data.tarifaFijaMensualDolarSigno;
                        document.getElementById('tarifaAplicada_imp').innerHTML=response.data.tarifaFijaMensualDolarSigno;
                        document.getElementById('codigoTarifa_imp').innerHTML=response.data.codigo;
                        

                          $("#monto_tarifa").show();
                          $('#tarifaAplicada').show();
                          $('#tarifa').show();
                          $("#Div_Fija").show();
                          $("#Div_Variable").hide();
                          $("#Div_Rotulos").hide();
                          $('#checkCasoEspecial').show();
                          
                        }
                        else if(response.data.tarifa==='Variable')
                        {
                          
                          document.getElementById('tarifaAplicada').value=response.data.ImpuestoMensualVariableDolarSigno;
                          document.getElementById('tarifaAplicadaValor').value=response.data.ImpuestoMensualVariableDolar;
                          document.getElementById('fondoFPVMensualDolar_imp').innerHTML=response.data.fondoFPVMensualDolar;
                          document.getElementById('fondoFPVAnualDolar_imp').innerHTML=response.data.fondoFPVAnualDolar;
                          document.getElementById('ImpuestoAnualVariableDolar_imp').innerHTML=response.data.ImpuestoAnualVariableDolar;
                          document.getElementById('ImpuestoMensualVariableDolar_imp').innerHTML=response.data.ImpuestoMensualVariableDolar;
                          document.getElementById('ImpuestoTotalMensualDolar_imp').innerHTML=response.data.ImpuestoTotalMensualDolar;
                          document.getElementById('ImpuestoTotalAnualDolar_imp').innerHTML=response.data.ImpuestoTotalAnualDolar;
                         
                          //**Estos dos campos son versatiles ya que se usan tambien en fija *//
                          document.getElementById('Total_ImpuestoValor_imp').value=response.data.ImpuestoTotalMensualDolar;
                          document.getElementById('tarifaAplicadaMensualValor_imp').value=response.data.ImpuestoMensualVariableDolar;

                          $("#monto_tarifa").hide();
                        
                          $('#tarifaAplicada').show();
                          $('#tarifa').show();
                          $("#Div_Variable").show();
                          $("#Div_Fija").hide();
                          $("#Div_Rotulos").hide();
                          $('#checkCasoEspecial').hide();
                      
                        }                  
                }  
            })
            .catch((error) => {
                toastr.error('Error');
                closeLoading();
            });


}

</script>
<!-- Finaliza función para calcular la calificación --------------------------------------------------------->


<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h4><i class="far fa-plus-square"></i>&nbsp;Agregar Calificaciones</h4>
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Recalificar empresa</li>
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

        <form class="form-horizontal" id="formulario-GenerarRecalificacion">
        @csrf

        <div class="card card-info">
          <div class="card-header">
          <h5 class="modal-title">Registrar recalificación a empresa&nbsp;<span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span></h5>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          
          <div class="card-body">
            
<!-------------------------CONEDIDO (CAMPOS) ----------------------------------------------->


        <!-- Campos del formulario de recalificación -->
         <div class="card border-success mb-3"><!-- Panel Datos generales de la empresa -->
         <div class="card-header text-info"><label>I.DATOS DE LA RECALIFICACIÓN</label></div>
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
                        <input  type="date" class="form-control text-info" name="fecha_pres_balance" id="fecha_pres_balance" class="form-control" required >
       
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-3">
                  <div class="form-group">
                        <label>ACT. ESPECIFICA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="form-group">
                  <textarea  required class="form-control" disabled id="nom_actividad_especifica" rows="2">{{ $empresa->nom_actividad_especifica }}</textarea>                               
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
               <!-- /.form-group -->
               <div class="col-md-3">
                <div class="form-group">
                      <label>AÑO CALIFICACIÓN:</label>
                </div>
              </div><!-- /.col-md-6 -->
              <div class="col-md-2">
                <div class="form-group">
                    <select name="año_calificacion" id="año_calificacion" 
                    class="selectpicker"
                    data-style="btn-info"
                    data-show-subtext="true" 
                    data-live-search="true" 
                    title="-- Seleccione el año --"
                    >       
                    </select>
                 </div>
              </div><!-- /.col-md-6 -->  
              <!-- /.form-group -->
            </div> <!-- /.ROW1 -->

            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->



            <div class="card"><!--  II. LICENCIAS  -->
             <div class="card-header text-info"><label> II. LICENCIAS </label></div>
             <div class="card-body"><!-- Card-body -->
               <div class="row"><!-- /.ROW1 -->
            

              <!-- /.form-group -->
              <div class="col-md-3">
                  <div class="form-group">
                        <label>LICENCIA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select Giro Comercial -->
               <div class="col-md-6">
                      <div class="form-group">
                            <!-- Select LICENCIA -live search -->
                                <div class="input-group mb-9">
                                <select 
                                required 
                                class="selectpicker"
                                data-style="btn-info"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="selectLicencia" 
                                title="-- Seleccione licencia --"
                                required
                                >
                                <option value=" " >Ninguna</option>
                                @foreach($licencia as $dato)
                                    <option value="{{$dato->monto}}">{{ $dato->nombre}} (${{$dato->monto}})</option>                                  
                                @endforeach
                                </select>                   
                              </div>
                          </div>
                  </div>
              <!-- finaliza select CARGAR MULTA-->
               <!-- /.form-group -->
               </div> <!-- /.ROW1 -->
               <hr>
               @if($detectorNull== '1')
               <label>MATRICULAS:</label>
                                <section class="content-header" id="aviso">
                                    <div class="container-fluid">
                                        <div>
                                       
                                            <br>
                                            <div class="callout callout-info">
                                                <h5><i class="fas fa-info"></i> Nota:</h5>
                                                <h5> No hay matrículas registradas para <span class="badge badge-warning">{{$empresa->nombre}}</span></h5> 
                                                <h6><i>(Puede agregarlas en la vista detallada de la empresa, sección</i> <span class="badge badge-dark">Matrículas</span>)</h6>                                                 
                                                <input type="hidden" value="{{$monto}}" id="matriculaValorTotal" class="form-control" required >                      
                                              </div>
                                        </div>
                                    </div>
                                </section>
              @else 
               <div class="row"><!-- /.ROW1 -->
               <!-- /.form-group -->
               <div class="col-md-12">
                     <div class="form-group">
                        <label>MATRICULAS REGISTRADAS:</label>
                           
                                <!-- Tabla matriculas -->
                                <div class="col-auto  p-5 text-center" > <!-- id="tablaDatatable" --->
                                <input type="hidden" value="{{$monto}}" id="matriculaValorTotal" class="form-control" required > 
                                <table id="tabla" class="table table-bordered table-striped" border= "1" data-toggle="table" width="80%">

                                  <thead>
                                      <tr>
                                      <th style="width: 35%;">Tipo de Matricula</th>
                                      <th style="width: 20%;">Cantidad</th>
                                      <th style="width: 25%;">Total Matrículas</th>
                                      <th style="width: 35%;">Pago Mensual</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @foreach($matriculas as $dato)
                                      <tr>
                                          <td>{{$dato->tipo_matricula}}</td>
                                          <td>{{$dato->cantidad}}</td>
                                          <td value="{{$dato->monto}}">${{$dato->monto}}</td>
                                          <td value="{{$dato->pago_mensual}}">${{$dato->pago_mensual}}</td>
                                      </tr>
                                      @endforeach
                                  </tbody>
                                  </table>


                                </div> <!--div de cierre tabla --->
                               
                      </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select Giro Comercial -->
                 
              <!-- finaliza select MATRICULA-->
               <!-- /.form-group -->
               </div> <!-- /.ROW1 -->
               @endif

            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel Multa -->
        
            <div class="card"><!--  II. Panel Tarifas  -->
            <div class="card-header text-info"><label> III. TARIFAS </label></div>
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
                        <input type="number" onchange="calculo({{$empresa->id_act_economica}}),resetea();" placeholder="$00,000.00"  name="activo_total" id="activo_total" class="form-control" required >
                       
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
                        <input type="number" onchange="calculo({{$empresa->id_act_economica}}),resetea();" placeholder="$00,000.00"  name="deducciones" id="deducciones" class="form-control" required >
                       
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

             </div><!-- ROW FILA1 -->

             <div class="row"><!-- /.ROW FILA2 -->

  
              <!-- /.form-group -->
              <div class="col-md-3">
                  <div class="form-group">
                        <label name="tarifa" id="tarifa" >TARIFA: </label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select ACTIVIDAD -->
               <div class="col-md-3">
                  <div class="form-group">
                        <input type="text" disabled placeholder="$00,000.00" name="tarifaAplicada" id="tarifaAplicada" class="form-control text-success" required > 
                        <input type="hidden" disabled  name="tarifaAplicadaValor" id="tarifaAplicadaValor" class="form-control" required > 
                    </div>
                </div>
              <!-- finaliza select ACTIVIDAD-->
              <!-- /.form-group -->
              <div class="col-md-3">
                    <div class="form-group">
                      <div class="form-check" id="checkCasoEspecial">
                        <input class="form-check-input" type="checkbox" id="gridCheck" onchange="desbloqTarifa()">
                        <label class="form-check-label" for="gridCheck">
                          Caso especial
                        </label>
                      </div>
                    </div>
               </div><!-- /.col-md-6 -->
              </div><!-- ROW FIL4 -->   
   
              </div><!-- /.SUCCESS -->
            </div><!-- /.Panel Tarifas -->
 
  <!-- Finaliza campos del formulario de recalificación -->


<!-------------------------FINALIZA CONTEDIDO (CAMPOS) ----------------------------------------------->


            <!-- Fin /.col -->
            </div>
            <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="button" class="btn btn-info float-right" onclick="GenerarCalificacion(), calculo({{$empresa->id_act_economica}});"><i class="fas fa-envelope-open-text"></i>
                    &nbsp;Generar Recalificación&nbsp;</button>
                    <button type="button" class="btn btn-default" onclick="VerEmpresa({{$empresa->id}} )"><i class="fas fa-chevron-circle-left"></i> &nbsp;Volver</button>
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

<!--Inicia Modal Registrar Recalificación--------------------------------------------------------------->

<div class="modal fade" id="modalRecalificacion">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Registrar recalificación a empresa&nbsp;<span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span></h5>
            <button type="button" class="close" onclick="deseleccionarCheck()"  data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <button type="button" class="close bg-warning" onclick="listarEmpresas()" data-dismiss="modal" aria-label="Close" id="cerrarModal2">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formulario-recalificacion1">
              <div class="card-body">

  <!-- Inicia Formulario Calificacion--> 
   <section class="content">
      <div class="container-fluid">
        <form class="form-horizontal" id="formulario-recalificacion">
        @csrf

          <div class="card card-info">
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
         <div class="card border-info mb-3"><!-- Panel ubicación del negocio -->
           <div class="card-header text-info"><label>I. UBICACIÓN DEL NEGOCIO</label></div>
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

         <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
         <div class="card-header text-info"><label>II. DATOS GENERALES DE LA EMPRESA</label></div>
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
                        <input type="hidden"  value="{{ $empresa->id }}" name="id_empresa" disabled id="id_empresa" class="form-control" required >
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
                        <input  type="date" class="form-control text-info" disabled value="{{$empresa->inicio_operaciones}}" name="created_at" id="created_at" class="form-control" required >
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
                        <input type="hidden" name="estado_calificacion" id="estado_calificacion" class="form-control" value="recalificado">
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
              
            </div> <!-- /.ROW1 -->

            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->
          
            <!-- /.col1 -->
          <div class="card border-info mb-3"><!-- Panel III. LICENCIAS Y PERMISOS -->
              <div class="card-header text-info"><label>III. LICENCIAS Y PERMISOS</label></div>
                <div class="card-body">

               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                  <table border="1" width:1080px;>
                          <tr>
                            <th scope="col">MARTRICULAS</th>
                            <th scope="col">CANT.</th>
                            <th scope="col">MONTO</th>
                            <th scope="col">P. MENSUAL</th>
                            <th scope="col">T. LICENCIAS</th>
                            <th scope="col">T. MATRICULAS</th>
                            <th scope="col">T. MAT. O PER.</th>
                          </tr>

                          <tr>
                          @if($detectorNull==1)
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><h6 name="licencia_imp" id="licencia_imp"> </h6> <input type="hidden" class="form-control" required disabled  id="monto_pagar_licenciaValor_imp" > </td>
                            <td><h6 name="monto_pagar_matricula_imp" id="monto_pagar_matricula_imp"> </h6><input type="hidden" class="form-control" required disabled id="monto_pagar_matriculaValor_imp"></td>
                            <td><h6 name="pagolicenciaMatricula_imp" id="pagolicenciaMatricula_imp"> </h6></h6><input type="hidden"  disabled id="pagolicenciaMatriculaValor_imp"></td>
                          </tr>
                          @else
                          @foreach($matriculas as $dato)
                            <td>{{$dato->tipo_matricula}}</td>
                            <td align="center" >{{$dato->cantidad}}</td>
                            <td><h6>${{$dato->monto}}</h6></td>
                            <td><h6>${{$dato->pago_mensual}}</h6></td>
                            <td><h6 name="licencia_imp" id="licencia_imp"> </h6> <input type="hidden" class="form-control" required disabled  id="monto_pagar_licenciaValor_imp" > </td>
                            <td><h6 name="monto_pagar_matricula_imp" id="monto_pagar_matricula_imp"> </h6><input type="hidden" class="form-control" required disabled id="monto_pagar_matriculaValor_imp"></td>
                            <td><h6 name="pagolicenciaMatricula_imp" id="pagolicenciaMatricula_imp"> </h6><input type="hidden"  disabled id="pagolicenciaMatriculaValor_imp"></td>
                          </tr>
                          @endforeach
                          @endif
                          <tr>
                            <td rowspan="2" colspan="5"></td>
                            <td><strong>Fondo F. P. </strong></td>
                            <td><h6 name="fondoFM_imp" id="fondoFM_imp"></h6><input type="hidden" disabled id="fondoFMValor_imp"></h6></td>
                          </tr>
                          <tr>
                            <td><strong>Pago Anual </strong></td>
                            <td><h6 name="PagoAnualLicencias_imp" id="PagoAnualLicencias_imp"></h6><input type="hidden" name="PagoAnualPermisos_imp" id="PagoAnualPermisos_imp"></td>
                          </tr>
                        </table>
                      
                      
                   </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
                </div> <!-- /.card-header text-success -->
              </div> <!-- /.Panel III. LICENCIAS Y PERMISOS -->
        

          <div class="card border-info mb-3" id="Div_Fija"><!-- Panel IV. CALIFICACION DE LA EMPRESA - TARIFA FIJA -->
           <div class="card-header text-info"><label>IV. CALIFICACION DE LA EMPRESA - TARIFA FIJA</label></div>
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
                        <td align="center">{{$empresa->nombre}}</td>
                        <td> <h6 id="codigoTarifa_imp"></h6> </td>
                        <td align="center">1</td>
                        <td><h6 id="tarifaenColonesSigno_imp"></h6> <input type="hidden" id="tarifaenColonesValor_imp"> </td>
                        <td><h6 id="tarifaAplicada_imp"> </h6></td>
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
                        <td><strong>Fondo F. P. 5% </strong></td>
                        <td><strong>TOTAL IMPUESTO</strong></td>
                      </tr>

                      <tr>
                        <th scope="row">MENSUAL</th>
                        <td colspan="2"><label name="tarifaAplicadaMensual_imp" id="tarifaAplicadaMensual_imp"></label><input type="hidden" name="tarifaAplicadaMensualValor_imp" id="tarifaAplicadaMensualValor_imp"></td>
                        <td><label>$<label name="FondoF_imp" id="FondoF_imp"> </label></label></td>
                        <td><label name="Total_Impuesto_imp" id="Total_Impuesto_imp"></label><input type="hidden" name="Total_ImpuestoValor_imp" id="Total_ImpuestoValor_imp"></td>
                      </tr>
                    </table>
                    </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div> <!-- /.card-header text-success -->
          </div><!-- /.Panel IV. CALIFICACION DE LA EMPRESA - TARIFA FIJA -->

          <div class="card border-info mb-3" id="Div_Variable"><!-- Panel V. CALIFICACION DE LA EMPRESA - TARIFA VARIABLE -->
           <div class="card-header text-info"><label>IV. CALIFICACION DE LA EMPRESA - TARIFA VARIABLE</label></div>
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
                        <td align="center"><label name="act_imponible_imp" id="act_imponible_imp"> </label><input type="hidden" id="act_imponibleValor_imp"></td>
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
                        <td><label>$<label name="ImpuestoMensualVariableDolar_imp" id="ImpuestoMensualVariableDolar_imp"></label></label></td>
                        <td><label>$<label name="ImpuestoAnualVariableDolar_imp" id="ImpuestoAnualVariableDolar_imp"></label></label></td>
                      </tr>

                      <tr>
                        <td rowspan="2"></td>
                        <td colspan="2">Fondo Fiestas Patronales 5%</td>
                        <td><label>$<label name="fondoFPVMensualDolar_imp" id="fondoFPVMensualDolar_imp"></label></label></td>
                        <td><label>$<label name="fondoFPVAnualDolar_imp" id="fondoFPVAnualDolar_imp"></label></label></td>
                      </tr>

                      <tr>
                        <td colspan="2"><label> IMPUESTO</label></td>
                        <td><label>$<label name="ImpuestoTotalMensualDolar_imp" id="ImpuestoTotalMensualDolar_imp"></label></label></td>
                        <td><label>$<label name="ImpuestoTotalAnualDolar_imp" id="ImpuestoTotalAnualDolar_imp"></label></label></td>
                      </tr>
                    </table>
                        </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div> <!-- /.card-header text-success -->
          </div><!-- /.Panel V. CALIFICACION DE LA EMPRESA - TARIFA VARIABLE -->

          <div class="card border-info mb-3" id="Div_Rotulos"><!-- PanelVI. ROTULOS -->
           <div class="card-header text-info"><label>V. ROTULOS</label></div>
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

          <div class="card border-info mb-3" id="Div_Multas"><!-- VII. MULTAS -->
           <div class="card-header text-info"><label>V. MULTAS</label></div>
            <div class="card-body">

               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">

                    <table border="1" width:760px;>
                      <tr align="center">
                        <th scope="col"> &nbsp; GIRO ECONOMICO &nbsp;</th>
                        <th scope="col">&nbsp;MULTA A PAGAR&nbsp;</th>
                        <th scope="col">&nbsp;BASE LEGAL&nbsp;</th>
                      </tr>
                      <tr align="center">
                        <td>{{$empresa->id_act_economica}}</td>
                        <td><label>$<label id="multaBalance_imp"></label></label></td>
                        <td>&nbsp;ART. 21, LEY DE IMPUESTOS MUNICIPALES&nbsp;</td>
                      </tr>
                    </table>

                      </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div> <!-- /.card-header text-success -->
          </div><!-- /.Panel VII. MULTAS -->


  <!-- Finaliza campos del formulario de calificación -->


         <!-- /.card-body -->
         <div class="card-footer">
         <button type="button" class="btn btn-secondary" onclick="ImprimirCalificacion({{ $empresa->id }})" id="btn_imprimirCalificacion">
         <i class="fa fa-print"></i>&nbsp; Imprimir Calificación&nbsp;</button>
         <button type="button" class="btn btn-info float-right" onclick="nuevo()"><i class="fas fa-edit">
         </i> &nbsp;Registrar Calificación&nbsp;</button>
         <br><br><button type="button" class="btn btn-default" onclick="deseleccionarCheck()" id="cerrarcalificacion1" data-dismiss="modal">Cerrar</button>
         <button type="button" class="btn btn-warning" onclick="listarEmpresas()" id="cerrarcalificacion2" data-dismiss="modal">Cerrar</button>
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
     </div> <!-- /.modalRecalificacion -->
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
function agregarTarifaFija(){
            
          //  document.getElementById("formulario-Recalificacion").reset();
            $('#modalAsignarTarifaFija').modal('show');
        }

function ImprimirCalificacion(id){
  window.open("{{ URL::to('/admin/reporte/calificacion/pdf') }}/" + id );
}
function GenerarCalificacion(){
            /*Declaramos variables */
            var fecha_pres_balance=(document.getElementById('fecha_pres_balance').value);
            var activo_total=(document.getElementById('activo_total').value);
            var deducciones=(document.getElementById('deducciones').value);
            var rubro=(document.getElementById('rubro').value);
            var tarifaAplicada_imp=(document.getElementById('tarifaAplicada').value);
            var año_calificacion=(document.getElementById('año_calificacion').value);
          
            
            if(fecha_pres_balance === ''){
                    toastr.error('La fecha que presenta el balance es requerida.');
                    deseleccionarCheck()
                    return;
                }
            
            if(año_calificacion === ''){
                    toastr.error('El año a recalificar es requerido.');
                    deseleccionarCheck()
                    return;
                }


            if(activo_total === ''){
                    toastr.error('El dato activo activo total es requerido.');
                    deseleccionarCheck()
                    return;
                }
              
              if(deducciones === ''){
                    toastr.error('El dato deducciones es requerido.');
                    deseleccionarCheck()
                    return;
                }

                if(tarifaAplicada_imp === ''){
                    toastr.error('No ha asignado una tarifa fija.');
                    deseleccionarCheck()
                    return;
                }


 
            document.getElementById('fechabalanceodjurada').value=fecha_pres_balance;
            document.getElementById('actividad_economica').innerHTML=rubro; 
            $('#modalRecalificacion').modal({backdrop: 'static', keyboard: false})
      
          // $('#modalRecalificacion').modal('show');
        }

function VerEmpresa(id){
      window.location.href="{{ url('/admin/empresas/show') }}/"+id;
}

function nuevo(){

  

  var total_mat_permisos = document.getElementById('pagolicenciaMatriculaValor_imp').value;
  var activo_total=document.getElementById('activo_total').value;
  var deducciones = document.getElementById('deducciones').value;
  var activo_imponible = document.getElementById('act_imponibleValor_imp').value;
  var fondofp_licencia_permisos = document.getElementById('fondoFMValor_imp').value;

  var tipo_tarifa = document.getElementById('tipo_tarifa').value;
  var id_empresa = document.getElementById('id_empresa').value;
  var fecha_calificacion = document.getElementById('fechabalanceodjurada').value;
  var tarifa = document.getElementById('tarifaAplicadaValor').value;
  var estado_calificacion = document.getElementById('estado_calificacion').value;
  var licencia = document.getElementById('monto_pagar_licenciaValor_imp').value;
  var matricula = document.getElementById('monto_pagar_matriculaValor_imp').value;
  var año_calificacion = document.getElementById('año_calificacion').value;
  var pago_mensual = document.getElementById('tarifaAplicadaMensualValor_imp').value;
  var total_impuesto = document.getElementById('Total_ImpuestoValor_imp').value;
  var pago_anual_permisos = document.getElementById('PagoAnualPermisos_imp').value;
  var multaBalance = document.getElementById('multaBalance_imp').innerHTML;

  var codigo_tarifa = document.getElementById('codigoTarifa_imp').innerHTML;
  if(codigo_tarifa===''){
    codigo_tarifa='N/A'
  }

  //**datos nuevos que no existen si la tarifa es fija */
  var pago_anualvariable = document.getElementById('ImpuestoAnualVariableDolar_imp').innerHTML;
  if(pago_anualvariable===''){
    pago_anualvariable=pago_mensual*12;
  }
  var fondo_mensualvariable = document.getElementById('fondoFPVMensualDolar_imp').innerHTML;
  if(fondo_mensualvariable===''){
    var FondoF_imp = document.getElementById('FondoF_imp').innerHTML;
    fondo_mensualvariable=FondoF_imp;
  }

  var fondo_anualvariable = document.getElementById('fondoFPVAnualDolar_imp').innerHTML;
  if(fondo_anualvariable===''){
    fondo_anualvariable=pago_anualvariable*0.05;
  }

  var total_impuesto_anualvariable = document.getElementById('ImpuestoTotalAnualDolar_imp').innerHTML;
  if(total_impuesto_anualvariable===''){
    total_impuesto_anualvariable=pago_anualvariable+fondo_anualvariable;
  }

  //**datos nuevos que no existe si la tarifa es variable */
  var tarifa_colonesFijo = document.getElementById('tarifaenColonesValor_imp').value;
  if(tarifa_colonesFijo===''){
    tarifa_colonesFijo=pago_mensual*8.75;
  }

  openLoading();
  var formData = new FormData();
  formData.append('id_empresa', id_empresa);
  formData.append('fecha_calificacion', fecha_calificacion);
  formData.append('tipo_tarifa', tipo_tarifa);
  formData.append('tarifa', tarifa);
  formData.append('estado_calificacion', estado_calificacion);
  formData.append('licencia', licencia);
  formData.append('matricula', matricula);
  formData.append('año_calificacion', año_calificacion);
  formData.append('pago_mensual', pago_mensual);
  formData.append('total_impuesto', total_impuesto);
  formData.append('pago_anual_permisos', pago_anual_permisos);
  formData.append('multaBalance', multaBalance);
  formData.append('codigo_tarifa', codigo_tarifa);
  
  formData.append('pago_anualvariable', pago_anualvariable);
  formData.append('fondo_mensualvariable', fondo_mensualvariable);
  formData.append('fondo_anualvariable', fondo_anualvariable);
  formData.append('total_impuesto_anualvariable', total_impuesto_anualvariable);
  formData.append('tarifa_colonesFijo', tarifa_colonesFijo);
  
  formData.append('total_mat_permisos', total_mat_permisos);
  formData.append('activo_total', activo_total);
  formData.append('deducciones', deducciones);
  formData.append('activo_imponible', activo_imponible);
  formData.append('fondofp_licencia_permisos',fondofp_licencia_permisos);

  axios.post('/admin/empresas/calificacion/nueva', formData, {
  })
      .then((response) => {
          closeLoading();
          if(response.data.success === 0){
              toastr.error(response.data.message);
          }
          if(response.data.success === 1){
            Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Recalificación registrada correctamente!',
                          showConfirmButton: true,
                         
                        }).then((result) => {
                        if (result.isConfirmed) {
                            $('#btn_imprimirCalificacion').show();
                            $('#cerrarModal1').hide();
                            $('#cerrarcalificacion1').hide();
                            $('#cerrarModal2').show();
                            $('#cerrarcalificacion2').show();
                            }
                        });

                       
          }
         
      })
      .catch((error) => {
        Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: '¡Error al registrar la recalificación!', 
                          showConfirmButton: true,
                        }).then((result) => {
                        if (result.isConfirmed) {
                          $('#modalRecalificacion').modal('hide');
                                    closeLoading();
                                  }
                        });
      });
     
}

function listarEmpresas(){
  window.location.href="{{ url('/admin/nuevo/empresa/listar') }}/";
}

</script> 


<script>

    $(function () {
        $("#tabla").DataTable({
            "paging": false,
            "lengthChange": true,
            "searching":false,
            "ordering": true,
            "info": false,
            "autoWidth": false,

            "language": {

                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "responsive": true, "lengthChange": false, "autoWidth": false,
        });
    });

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