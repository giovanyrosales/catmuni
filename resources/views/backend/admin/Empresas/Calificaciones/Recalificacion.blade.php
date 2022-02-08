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


    function f1(){
                $('#monto_tarifa').hide();
                $('#selectTarifa').hide();
                $('#tarifaAplicada').hide();
                $('#tarifa').hide();
               
}
 
window.onload = f1;

</script>

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
function resetea()
{
 vacio='';
 document.getElementById('tarifaAplicada').value=vacio;
 document.getElementById('tarifaAplicadaValor').value=vacio; 
}

function calculo(id_act_economica)
{
    /*Declaramos variables */
    var  licencia=(document.getElementById('selectLicencia').value);
    var  matricula=(document.getElementById('selectMatricula').value);

    var deducciones=(document.getElementById('deducciones').value);
    var activo_total=(document.getElementById('activo_total').value);
    var anio_calificacion=(document.getElementById('año_calificacion').value);
    var deducciones_imp=(document.getElementById('deducciones').value);
    var tipo_tarifa=(document.getElementById('tipo_tarifa').value);
    var ValortarifaAplicada=(document.getElementById('tarifaAplicadaValor').value);
    var id_actividad_especifica={{$empresa->id_actividad_especifica}};

var formData = new FormData();

formData.append('deducciones', deducciones);
formData.append('activo_total', activo_total);
formData.append('ValortarifaAplicada', ValortarifaAplicada);
formData.append('licencia', licencia);
formData.append('matricula', matricula);
formData.append('id_act_economica', id_act_economica);
formData.append('id_actividad_especifica', id_actividad_especifica);

axios.post('/admin/empresas/calculo_calificacion', formData, {
        })
            .then((response) => {
              console.log(response);
                closeLoading();
                if(response.data.success ===2){
                  //toastr.error('Muuuuuuuultaaaaaaaaaaaaaaaaaaaaa');
                } 
                if(response.data.success === 1){

            //Impresioines en tabla licencias y permisos.
            document.getElementById('pagolicenciaMatricula_imp').innerHTML=response.data.licenciaMatriculaSigno;
            document.getElementById('fondoFM_imp').innerHTML=response.data.fondoFLMSigno;
            document.getElementById('PagoAnualLicencias_imp').innerHTML=response.data.PagoAnualLicenciasSigno;
            document.getElementById('PagoAnualPermisos_imp').value=response.data.PagoAnualLicenciasValor;
 	          document.getElementById('licencia_imp').innerHTML=response.data.licencia;
            document.getElementById('monto_pagar_matricula_imp').innerHTML=response.data.matricula; 
            //Terminan Impresioines en tabla licencias y permisos.
              
                        document.getElementById('activo_imponible').value=response.data.valor;
                        document.getElementById('tipo_tarifa').value=response.data.tarifa;
                        document.getElementById('act_total').innerHTML= activo_total;
                        document.getElementById('anio_calificacion').innerHTML=anio_calificacion;
                        document.getElementById('deducciones_imp').innerHTML=deducciones_imp;
                        document.getElementById('act_imponible_imp').innerHTML=response.data.valor;
                       

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
                        //**Estos dos campos son versatiles ya que se usan tambien en variable *//
                        var ValortarifaAplicadaImp=(document.getElementById('tarifaAplicadaValor').value);
                        document.getElementById('tarifaAplicadaMensualValor_imp').value=ValortarifaAplicadaImp;
                        document.getElementById('Total_ImpuestoValor_imp').value=response.data.Total_ImpuestoFijoDolarSigno;

                        document.getElementById('FondoF_imp').innerHTML=response.data.FondoF; 
                        document.getElementById('Total_Impuesto_imp').innerHTML=response.data.Total_ImpuestoFijoDolarSigno;
                        document.getElementById('tarifaenColonesSigno_imp').innerHTML=response.data.tarifaenColonesSigno;
                        
                        document.getElementById('tarifaAplicada').value=response.data.Total_ImpuestoFijoDolarSigno;
                        document.getElementById('tarifaAplicadaValor').value=response.data.Total_ImpuestoFijoDolarValor; 

                          $("#monto_tarifa").show();
                          $('#tarifaAplicada').show();
                          $('#tarifa').show();
                          $("#Div_Fija").show();
                          $("#Div_Variable").hide();
                          $("#Div_Rotulos").hide();
                          $("#Div_Multas").hide();
                          
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
                          $("#Div_Multas").hide();
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


        <!-- Campos del formulario de cobros -->
         <div class="card border-success mb-3"><!-- Panel Datos generales de la empresa -->
         <div class="card-header text-success"><label>I.DATOS DE LA RECALIFICACIÓN</label></div>
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
               <div class="col-md-3">
                  <div class="form-group">
                        <label>ACT. ESPECIFICA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="form-group">
                  <input type="text"  value="{{ $empresa->nom_actividad_especifica }}" disabled id="nom_actividad_especifica" class="form-control" required >                               
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
                                onchange="calculo({{$empresa->id_act_economica}});"
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="selectLicencia" 
                                title="-- Seleccione matrícula --"
                                required
                                >
                                <option value=" " >Ninguna</option>
                                @foreach($licencia as $dato)
                                    <option value="{{$dato->monto}}">{{ $dato->nombre}} ${{$dato->monto}}</option>                                  
                                @endforeach
                                </select> 
                                <input type="hidden" class="form-control" required disabled name="monto_pagar" id="monto_pagar" > 
                                </div>
                          </div>
                  </div>
              <!-- finaliza select CARGAR MULTA-->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-3">
                  <div class="form-group">
                        <label>MATRICULA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select Giro Comercial -->
               <div class="col-md-3">
                      <div class="form-group">
                            <!-- Select MATRICULA -live search -->
                                <div class="input-group mb-9">
                                <select 
                                required 
                                onchange="calculo({{$empresa->id_act_economica}});"
                                class="selectpicker"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="selectMatricula" 
                                title="-- Seleccione licencia --"
                                required
                                >
                                <option value=" " >Ninguna</option>
                                @foreach($matricula as $dato)
                                    <option value="{{$dato->monto}}" >{{ $dato->nombre}} ${{$dato->monto}}</option>                                  
                                @endforeach
                                </select> 
                                <input type="hidden" class="form-control" required disabled name="monto_pagar_matricula" id="monto_pagar_matricula">
                                </div>
                          </div>
                  </div>
              <!-- finaliza select MATRICULA-->
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
     
              <!-- /.form-group -->
                <!-- /.form-group -->
                <div class="col-md-3">
                <div class="form-group">
                      <label>AÑO RECALIFICACIÓN:</label>
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
              </div><!-- ROW FIL4 -->   
   
              </div><!-- /.SUCCESS -->
            </div><!-- /.Panel Tarifas -->
 
  <!-- Finaliza campos del formulario de calificación -->


<!-------------------------FINALIZA CONTEDIDO (CAMPOS) ----------------------------------------------->


            <!-- Fin /.col -->
            </div>
            <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="button" class="btn btn-success float-right" onclick="GenerarCalificacion(), calculo({{$empresa->id_act_economica}});"><i class="fas fa-envelope-open-text"></i>
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

<!--Inicia Modal Registrar Recalificación--------------------------------------------------------------->

<div class="modal fade" id="modalCalificacion">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Registrar Recalificación a empresa&nbsp;<span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span></h5>
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
                        <input type="hidden" name="estado_calificacion" id="estado_calificacion" class="form-control" value="recalificado">
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
                            <td align="center">{{$empresa->nombre}}</td>
                            <td>1</td>
                            <td><h6 name="licencia_imp" id="licencia_imp"> </h6></td>
                            <td><h6 name="monto_pagar_matricula_imp" id="monto_pagar_matricula_imp"> </h6></td>
                            <td><h6 name="pagolicenciaMatricula_imp" id="pagolicenciaMatricula_imp"> </h6></td>
                          </tr>

                          <tr>
                            <td> </td>
                            <td rowspan="3" colspan="2">&nbsp; </td>
                            <td colspan="2">&nbsp;</td>
                          </tr>

                          <tr>
                            <td> </td>
                            <td><strong>Fondo F. P. </strong></td>
                            <td><h6 name="fondoFM_imp" id="fondoFM_imp"></h6></td>
                          </tr>
                          <tr>
                            <th scope="row"> </th>
                            <td><strong>Pago Anual </strong></td>
                            <td><h6 name="PagoAnualLicencias_imp" id="PagoAnualLicencias_imp"></h6><input type="hidden" name="PagoAnualPermisos_imp" id="PagoAnualPermisos_imp"></td>
                          </tr>
                        </table>
                      
                   </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
                </div> <!-- /.card-header text-success -->
              </div> <!-- /.Panel III. LICENCIAS Y PERMISOS -->
        

          <div class="card border-success mb-3" id="Div_Fija"><!-- Panel IV. CALIFICACION DE LA EMPRESA - TARIFA FIJA -->
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
                        <td align="center">{{$empresa->nombre}}</td>
                        <td> 13623 </td>
                        <td>1</td>
                        <td> <h6 name="tarifaenColonesSigno_imp" id="tarifaenColonesSigno_imp"> </td>
                        <td><h6 name="tarifaAplicada_imp" id="tarifaAplicada_imp"> </h6></td>
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

          <div class="card border-success mb-3" id="Div_Variable"><!-- Panel V. CALIFICACION DE LA EMPRESA - TARIFA VARIABLE -->
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

          <div class="card border-success mb-3" id="Div_Rotulos"><!-- PanelVI. ROTULOS -->
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

          <div class="card border-success mb-3" id="Div_Multas"><!-- VII. MULTAS -->
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
         </i>&nbsp; Impimir Recalificación&nbsp;</button>
         <button type="button" class="btn btn-success float-right" onclick="nuevo()"><i class="fas fa-edit">
         </i> &nbsp;Registrar Recalificación&nbsp;</button>
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
function agregarTarifaFija(){
            
          //  document.getElementById("formulario-Recalificacion").reset();
            $('#modalAsignarTarifaFija').modal('show');
        }
function GenerarCalificacion(){
            /*Declaramos variables */
            var fecha_pres_balance=(document.getElementById('fecha_pres_balance').value);
            var activo_total=(document.getElementById('activo_total').value);
            var deducciones=(document.getElementById('deducciones').value);
            var rubro=(document.getElementById('rubro').value);
            var tarifaAplicada_imp=(document.getElementById('tarifaAplicada').value);
          
            
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

                if(tarifaAplicada_imp === ''){
                    toastr.error('No ha asignado una tarifa fija.');
                    return;
                }


 
            document.getElementById('fechabalanceodjurada').value=fecha_pres_balance;
            document.getElementById('actividad_economica').innerHTML=rubro; 
            document.getElementById('tarifaAplicada_imp').innerHTML=tarifaAplicada_imp;
            document.getElementById('tarifaAplicadaMensual_imp').innerHTML=tarifaAplicada_imp;
        

            $('#modalCalificacion').modal('show');
        }

function VerEmpresa(id){
      window.location.href="{{ url('/admin/empresas/show') }}/"+id;
}

function nuevo(){
  
  var id_empresa = document.getElementById('id_empresa').value;
  var fecha_calificacion = document.getElementById('fechabalanceodjurada').value;
  var tipo_tarifa = document.getElementById('tipo_tarifa').value;
  var tarifa = document.getElementById('tarifaAplicadaValor').value;
  var estado_calificacion = document.getElementById('estado_calificacion').value;
  var licencia = document.getElementById('licencia_imp').innerHTML;
  var matricula = document.getElementById('monto_pagar_matricula_imp').innerHTML;
  var año_calificacion = document.getElementById('año_calificacion').value;
  var pago_mensual = document.getElementById('tarifaAplicadaMensualValor_imp').value;
  var total_impuesto = document.getElementById('Total_ImpuestoValor_imp').value;
  var pago_anual_permisos = document.getElementById('PagoAnualPermisos_imp').value;

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
  

  axios.post('/admin/empresas/calificacion/nueva', formData, {
  })
      .then((response) => {
          closeLoading();
          if(response.data.success === 0){
              toastr.error(response.data.message);
          }
          if(response.data.success === 1){
              toastr.success('Calificación registrada correctamente.');
              window.location.href="{{ url('/admin/nuevo/empresa/listar') }}/";
          }
         
      })
      .catch((error) => {
          toastr.error('Error al registrar la calificación.');
          closeLoading();
      });
}


</script> 


<script>

    $(function () {
        $("#tabla").DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
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