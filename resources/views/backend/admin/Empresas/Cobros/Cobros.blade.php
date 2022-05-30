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

<script>
  function f1_disabled()
  {
    if(document.getElementById('CheckMesas').checked){
               
          document.getElementById('fecha_hasta_donde_pagaraMesas').disabled=true; 
              
        }else{
              document.getElementById('fecha_hasta_donde_pagaraMesas').disabled=false; 
             }

    if(document.getElementById('CheckMaquinas').checked){
               
           document.getElementById('fecha_hasta_donde_pagaraMaquinas').disabled=true; 
                   
        }else{
            document.getElementById('fecha_hasta_donde_pagaraMaquinas').disabled=false; 
             }

    
    if(document.getElementById('CheckSinfonolas').checked){
               
               document.getElementById('fecha_hasta_donde_pagaraSinfonolas').disabled=true; 
                       
            }else{
                document.getElementById('fecha_hasta_donde_pagaraSinfonolas').disabled=false; 
                 }
    
  }//** FIN- funcion para desabilitar boton hasta donde pagará */

    function f1()
        {
           document.getElementById('select_interesMesas').disabled=true;
           $('#periodo').hide();
           $('#periodoLicor').hide();
           $('#periodoMesas').hide();
           $('#periodoMaquinas').hide();
           $('#periodoSinfonolas').hide();
           $('#periodoAparatos').hide();
           $('#estado_de_cuentaIMP').hide();
           $('#estado_de_cuenta_licorIMP').hide();
           $('#estado_de_cuenta_aparatosIMP').hide();
           $('#estado_de_cuenta_sinfonolasIMP').hide();
           $('#estado_de_cuenta_maquinasIMP').hide();
           $('#estado_de_cuenta_mesasIMP').hide();
          }
        function recuperariD($id)//*** Para recuperar los ID de las matriculas detalle */
        {
         var id=$id; 
         document.getElementById('id_matriculadetalleMesas').value=id;
         document.getElementById('id_matriculadetalleMaquinas').value=id;
         document.getElementById('id_matriculadetalleAparatos').value=id;
         document.getElementById('id_matriculadetalleSinfonolas').value=id;
         
        }
        
window.onload = f1;

</script>

<!-- Función para calcular el pago momentaneo------------------------------------------>
<script> 
function info_cobros_matriculas()//*** Para recuperar los ID de las matriculas detalle */
        {
        var id_matriculadetalleMesas=(document.getElementById('id_matriculadetalleMesas').value);
        var id_matriculadetalleMaquinas=(document.getElementById('id_matriculadetalleMaquinas').value);
        var id_matriculadetalleSinfonolas=(document.getElementById('id_matriculadetalleSinfonolas').value);
        var id_matriculadetalleAparatos=(document.getElementById('id_matriculadetalleAparatos').value);

        var formData = new FormData();

formData.append('id_matriculadetalleMesas', id_matriculadetalleMesas);
formData.append('id_matriculadetalleMaquinas', id_matriculadetalleMaquinas);
formData.append('id_matriculadetalleSinfonolas', id_matriculadetalleSinfonolas);
formData.append('id_matriculadetalleAparatos', id_matriculadetalleAparatos);

 axios.post('/admin/empresas/info_cobro_matriculas', formData, {
        })
        .then((response) => {
                console.log(response);
                  closeLoading();

                  if(response.data.success === 1){
                    
                    if(response.data.ultimo_cobroMesas!=null)
                    {
                        document.getElementById('ultimo_cobroMesas').value=response.data.ultimo_cobroMesas.periodo_cobro_fin;
                        document.getElementById('ultimo_cobroMesas').disabled=true;

                    }else{
                          document.getElementById('ultimo_cobroMesas').value='';
                          document.getElementById('ultimo_cobroMesas').disabled=false;
                         }

                    if(response.data.ultimo_cobroMaquinas!=null)
                    {
                        document.getElementById('ultimo_cobroMaquinas').value=response.data.ultimo_cobroMaquinas.periodo_cobro_fin;
                        document.getElementById('ultimo_cobroMaquinas').disabled=true;
                    }
                    else{
                          document.getElementById('ultimo_cobroMaquinas').value='';
                          document.getElementById('ultimo_cobroMaquinas').disabled=false;
                         }

                    if(response.data.ultimo_cobroSinfonolas!=null)
                    {
                        document.getElementById('ultimo_cobroSinfonolas').value=response.data.ultimo_cobroSinfonolas.periodo_cobro_fin;
                        document.getElementById('ultimo_cobroSinfonolas').disabled=true;
                    }else{
                          document.getElementById('ultimo_cobroSinfonolas').value='';
                          document.getElementById('ultimo_cobroSinfonolas').disabled=false;
                         }

                    if(response.data.ultimo_cobroAparatos==null)
                    {
                        document.getElementById('ultimo_cobroAparatos').value='';
                        document.getElementById('ultimo_cobroAparatos').disabled=false;

                    }else{
                          document.getElementById('ultimo_cobroAparatos').value=response.data.ultimo_cobroAparatos.periodo_cobro_fin;
                          document.getElementById('ultimo_cobroAparatos').disabled=true;
                         }

                  }  
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 

        }//Termina funcion info_cobros_matriculas

function calculo(id, valor)
{
    /*Declaramos variables */
  
    var fechaPagara=(document.getElementById('fecha_hasta_donde_pagara').value);
    var ultimo_cobro=(document.getElementById('ultimo_cobro').value);
    var tasa_interes=(document.getElementById('select_interes').value);
    var fecha_interesMoratorio=(document.getElementById('fecha_interes_moratorio').value);
    var totalPago_imp=(document.getElementById('totalPago_imp').innerHTML);
    var tarifaMes=(document.getElementById('tarifaMes').value);
    openLoading();

    //Validaciones
    if (fechaPagara=='' && valor=='0')
    {
      modalMensaje('Aviso', 'No se ha definido la fecha hasta donde pagará.');
      return;
    }

    if (totalPago_imp=='' && valor=='1')
    {
      modalMensaje('Aviso', 'No hay ningún cobro generado.');
      return;
    }
    if (tarifaMes=='' && valor=='0')
    {
      modalMensaje('Aviso', 'No se ha definido la tarifa que pagará.');
      return;
    }

var formData = new FormData();

formData.append('id', id);
formData.append('cobrar', valor);
formData.append('tarifaMes', tarifaMes);
formData.append('fechaPagara', fechaPagara);
formData.append('ultimo_cobro', ultimo_cobro);
formData.append('tasa_interes', tasa_interes);
formData.append('fecha_interesMoratorio', fecha_interesMoratorio);

 axios.post('/admin/empresas/calculo_cobros_empresa', formData, {
        })
        .then((response) => {
                console.log(response);
                  closeLoading();
                  if(response.data.success ===0){
                    toastr.error('La fecha selecionada no puede ser menor o igual a la del ultimo mes de pago.');
                    document.getElementById('hasta').innerHTML= '';
                    document.getElementById('cant_meses').value='';      
                    document.getElementById('fondoFP_imp').innerHTML='$-';
                    document.getElementById('totalPago_imp').innerHTML='$-';
                    document.getElementById('multa_balanceImp').innerHTML='$-';
                    document.getElementById('impuestos_mora_imp').innerHTML='$-';
                    document.getElementById('impuesto_año_actual_imp').innerHTML='$-';
                    document.getElementById('fechaInicioPago_imp').innerHTML='';
                    document.getElementById('multaPagoExtemporaneo_imp').innerHTML='$-';
                    document.getElementById('InteresTotal_imp').innerHTML='$-';
                  } 
                  if(response.data.success === 1){
                    $('#periodo').show();
                    $('#estado_de_cuentaIMP').show(); 
                    document.getElementById('hasta').innerHTML=response.data.PagoUltimoDiaMes;
                    document.getElementById('cant_meses').value=response.data.Cantidad_MesesTotal;
                    document.getElementById('impuestos_mora_imp').innerHTML=response.data.impuestos_mora_Dollar;
                    document.getElementById('impuesto_año_actual_imp').innerHTML=response.data.impuesto_año_actual_Dollar;
                    document.getElementById('InteresTotal_imp').innerHTML=response.data.InteresTotalDollar;
                    document.getElementById('multa_balanceImp').innerHTML=response.data.multas_balance;
                    document.getElementById('multaPagoExtemporaneo_imp').innerHTML=response.data.multaPagoExtemporaneoDollar;
                    document.getElementById('fondoFP_imp').innerHTML=response.data.fondoFP;      
                    document.getElementById('fechaInicioPago_imp').innerHTML=response.data.InicioPeriodo;            
                    document.getElementById('totalPago_imp').innerHTML=response.data.totalPago;

                  }  
                  if(response.data.success===2)
                      {
                          cobro_registrado();
                      }
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 


}
//** INICIO- cálculo para cobrar Licencia licor */
function calculo_licencia_licor(id, valor)
{
    /*Declaramos variables */
    var fechaPagaraLicor=(document.getElementById('fecha_hasta_donde_pagaraLicor').value);
    var ultimo_cobroLicor=(document.getElementById('ultimo_cobroLicor').value);

    openLoading();

    //* Validaciones **//
    if (ultimo_cobroLicor=='' && valor=='0')
    {
      modalMensaje('Aviso', 'No se ha especificado la fecha del último cobro.');
      return;
    }
    if (fechaPagaraLicor=='' && valor=='0')
    {
      modalMensaje('Aviso', 'No se ha definido la fecha hasta donde pagará.');
      return;
    }

    if (totalPagoLicor_imp=='' && valor=='1')
    {
      modalMensaje('Aviso', 'No hay ningún cobro generado.');
      return;
    }

var formData = new FormData();

formData.append('id', id);
formData.append('cobrar', valor);
formData.append('fechaPagara', fechaPagaraLicor);
formData.append('ultimo_cobro', ultimo_cobroLicor);



 axios.post('/admin/empresas/calculo_cobros_licencia_licor', formData, {
        })
        .then((response) => {
                console.log(response);
                  closeLoading();
                  if(response.data.success ===0){
                    toastr.error('La fecha selecionada no puede ser menor a la del ultimo pago');
                    document.getElementById('hastaLicor').innerHTML= '';
                    document.getElementById('LicenciaLicor_imp').innerHTML='';
                    document.getElementById('MultaLicor_imp').innerHTML='';
                    document.getElementById('totalPagoLicor_imp').innerHTML='';
                    document.getElementById('fechaInicioPagoLicor_imp').innerHTML='';
                  } 
                  if(response.data.success === 1){
                    $('#periodoLicor').show();
                    $('#estado_de_cuenta_licorIMP').show();
                    document.getElementById('hastaLicor').innerHTML=response.data.PagoUltimoDiaMesLicor;          
                    document.getElementById('LicenciaLicor_imp').innerHTML=response.data.monto_pago_licencia;
                    document.getElementById('MultaLicor_imp').innerHTML=response.data.monto_pago_multaDollar;
                    document.getElementById('totalPagoLicor_imp').innerHTML=response.data.totalPagoLicor;
                    document.getElementById('fechaInicioPagoLicor_imp').innerHTML=response.data.InicioPeriodoLicor;
                  }  
                  if(response.data.success===2)
                      {
                          cobro_registrado();
                      }
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 


}//** FIN- cálculo para cobrar Licencia licor */

//** Inicia cálculo para cobrar mesas de billar */
function calculo_cobros_mesas(id, valor)
{

    /*Declaramos variables */
    var id_matriculadetalleMesas=(document.getElementById('id_matriculadetalleMesas').value);
    var fechaPagaraMesas=(document.getElementById('fecha_hasta_donde_pagaraMesas').value);
    var ultimo_cobroMesas=(document.getElementById('ultimo_cobroMesas').value);
    var tasa_interesMesas=(document.getElementById('select_interesMesas').value);
    var fecha_interesMoratorioMesas=(document.getElementById('fecha_interes_moratorioMesas').value);

    openLoading();
    if(document.getElementById('CheckMesas').checked){
               
           var estado='On' 
          
    }else{
           var estado='Off'
         }
      //* Validaciones **//
      if (ultimo_cobroMesas=='' && valor=='0')
      {
        modalMensaje('Aviso', 'No se ha especificado la fecha del último cobro.');
        return;
      }
      if (fechaPagaraMesas=='' && valor=='0')
      {
        modalMensaje('Aviso', 'No se ha definido la fecha hasta donde pagará.');
        return;
      }

      if (totalPagoMesas_imp=='' && valor=='1')
      {
        modalMensaje('Aviso', 'No hay ningún cobro generado.');
        return;
      }
var formData = new FormData();

formData.append('id', id);
formData.append('cobrar', valor);
formData.append('id_matriculadetalleMesas', id_matriculadetalleMesas);
formData.append('fechaPagaraMesas', fechaPagaraMesas);
formData.append('ultimo_cobroMesas', ultimo_cobroMesas);
formData.append('tasa_interesMesas', tasa_interesMesas);
formData.append('fecha_interesMoratorioMesas', fecha_interesMoratorioMesas);
formData.append('estado', estado);

 axios.post('/admin/empresas/calculo_cobros_mesas', formData, {
        })
        .then((response) => {
                console.log(response);
                  closeLoading();
                  if(response.data.success ===0){
                    toastr.error('La fecha selecionada no puede ser menor a la del ultimo pago');
                    document.getElementById('fechaInicioPagoMesas_imp').innerHTML=''; 
                    document.getElementById('hastaMesas').innerHTML= '';
                    document.getElementById('cant_mesesMesas').value=''; 
                    document.getElementById('impuestos_moraMesas_imp').innerHTML='$-';
                    document.getElementById('impuesto_año_actualMesas_imp').innerHTML='$-';
                    document.getElementById('InteresTotalMesas_imp').innerHTML='$-';
                    document.getElementById('multaPagoExtemporaneoMesas_imp').innerHTML='$-';                   
                    document.getElementById('MatriculaMesas_imp').innerHTML='$-';  
                    document.getElementById('fondoFPMesas_imp').innerHTML='$-';    
                    document.getElementById('multa_MartriculaMesas_Imp').innerHTML='$-';            
                    document.getElementById('totalPagoMesas_imp').innerHTML='$-';

                    

                  } 
                  if(response.data.success === 1){
                    $('#periodoMesas').show();
                    $('#estado_de_cuenta_mesasIMP').show();
                    document.getElementById('fechaInicioPagoMesas_imp').innerHTML=response.data.InicioPeriodoMesas; 
                    document.getElementById('hastaMesas').innerHTML= response.data.PagoUltimoDiaMesMesas;
                    document.getElementById('cant_mesesMesas').value=response.data.Cantidad_MesesTotalMesas; 
                    document.getElementById('impuestos_moraMesas_imp').innerHTML=response.data.impuestos_mora_DollarMesas;
                    document.getElementById('impuesto_año_actualMesas_imp').innerHTML=response.data.impuesto_año_actual_DollarMesas;
                    document.getElementById('InteresTotalMesas_imp').innerHTML=response.data.InteresTotalDollar;
                    document.getElementById('multaPagoExtemporaneoMesas_imp').innerHTML=response.data.totalMultaPagoExtemporaneoMesas;                   
                    document.getElementById('MatriculaMesas_imp').innerHTML=response.data.monto_pago_PmatriculaDollarMesas;  
                    document.getElementById('fondoFPMesas_imp').innerHTML=response.data.fondoFPMesas;    
                    document.getElementById('multa_MartriculaMesas_Imp').innerHTML=response.data.multa_por_matricula;            
                    document.getElementById('totalPagoMesas_imp').innerHTML=response.data.totalPagoMesas;
               
                  }  
                  if(response.data.success===2)
                      {
                          cobro_registrado();
                      }
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 


}

//** Termina cálculo para mesas de billar */

//** Inicia cálculo para cobrar MÁQUINAS ELECTRÓNICAS */
function calculo_cobros_maquinas(id, valor)
{
    /*Declaramos variables */
    var id_matriculadetalleMaquinas=(document.getElementById('id_matriculadetalleMaquinas').value);
    var fechaPagaraMaquinas=(document.getElementById('fecha_hasta_donde_pagaraMaquinas').value);
    var ultimo_cobroMaquinas=(document.getElementById('ultimo_cobroMaquinas').value);
    var tasa_interesMaquinas=(document.getElementById('select_interesMaquinas').value);
    var fecha_interesMoratorioMaquinas=(document.getElementById('fecha_interes_moratorioMaquinas').value);

    openLoading();
    if(document.getElementById('CheckMaquinas').checked){
               
           var estado='On' 
          
    }else{
           var estado='Off'
                
         }

      //* Validaciones **//
       if (ultimo_cobroMaquinas=='' && valor=='0')
      {
        modalMensaje('Aviso', 'No se ha especificado la fecha del último cobro.');
        return;
      }
      if (fechaPagaraMaquinas=='' && valor=='0')
      {
        modalMensaje('Aviso', 'No se ha definido la fecha hasta donde pagará.');
        return;
      }

      if (totalPagoMaquinas_imp=='' && valor=='1')
      {
        modalMensaje('Aviso', 'No hay ningún cobro generado.');
        return;
      }

var formData = new FormData();

formData.append('id', id);
formData.append('cobrar', valor);
formData.append('estado', estado);
formData.append('id_matriculadetalleMaquinas', id_matriculadetalleMaquinas);
formData.append('fechaPagaraMaquinas', fechaPagaraMaquinas);
formData.append('ultimo_cobroMaquinas', ultimo_cobroMaquinas);
formData.append('tasa_interesMaquinas', tasa_interesMaquinas);
formData.append('fecha_interesMoratorioMaquinas', fecha_interesMoratorioMaquinas);

 axios.post('/admin/empresas/calculo_cobros_maquinas', formData, {
        })
        .then((response) => {
                console.log(response);
                  closeLoading();
                  if(response.data.success ===0){
                    toastr.error('La fecha selecionada no puede ser menor a la del ultimo pago');
                    document.getElementById('fechaInicioPagoMaquinas_imp').innerHTML=''; 
                    document.getElementById('hastaMaquinas').innerHTML= '';
                    document.getElementById('cant_mesesMaquinas').value=''; 
                    document.getElementById('impuestos_moraMaquinas_imp').innerHTML='$-';
                    document.getElementById('impuesto_año_actualMaquinas_imp').innerHTML='$-';
                    document.getElementById('InteresTotalMaquinas_imp').innerHTML='$-';              
                    document.getElementById('MatriculaMaquinas_imp').innerHTML='$-';  
                    document.getElementById('fondoFPMaquinas_imp').innerHTML='$-';    
                    document.getElementById('multa_MartriculaMaquinas_Imp').innerHTML='$-';            
                    document.getElementById('totalPagoMaquinas_imp').innerHTML='$-';

                    

                  } 
                  if(response.data.success === 1){
                    $('#periodoMaquinas').show();
                    $('#estado_de_cuenta_maquinasIMP').show();
                    document.getElementById('fechaInicioPagoMaquinas_imp').innerHTML=response.data.InicioPeriodoMaquinas; 
                    document.getElementById('hastaMaquinas').innerHTML= response.data.PagoUltimoDiaMesMaquinas;
                    document.getElementById('cant_mesesMaquinas').value=response.data.Cantidad_MesesTotalMaquinas; 
                    document.getElementById('impuestos_moraMaquinas_imp').innerHTML=response.data.impuestos_mora_DollarMaquinas;
                    document.getElementById('impuesto_año_actualMaquinas_imp').innerHTML=response.data.impuesto_año_actual_DollarMaquinas;
                    document.getElementById('InteresTotalMaquinas_imp').innerHTML=response.data.InteresTotalDollar;
                    document.getElementById('MatriculaMaquinas_imp').innerHTML=response.data.monto_pago_PmatriculaDollarMaquinas;  
                    document.getElementById('fondoFPMaquinas_imp').innerHTML=response.data.fondoFPMaquinas;    
                    document.getElementById('multa_MartriculaMaquinas_Imp').innerHTML=response.data.multaDolarMaquinas;            
                    document.getElementById('totalPagoMaquinas_imp').innerHTML=response.data.totalPagoMaquinas;
               
                  }  
                  if(response.data.success===2)
                      {
                          cobro_registrado();
                      }
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 


}

//** Termina cálculo para máquinas electrónicas */

//** Inicia cálculo para cobrar SINFONOLAS */
function calculo_cobros_sinfonolas(id, valor)
{
    /*Declaramos variables */
    var id_matriculadetalleSinfonolas=(document.getElementById('id_matriculadetalleSinfonolas').value);
    var fechaPagaraSinfonolas=(document.getElementById('fecha_hasta_donde_pagaraSinfonolas').value);
    var ultimo_cobroSinfonolas=(document.getElementById('ultimo_cobroSinfonolas').value);
    var tasa_interesSinfonolas=(document.getElementById('select_interesSinfonolas').value);
    var fecha_interesMoratorioSinfonolas=(document.getElementById('fecha_interes_moratorioSinfonolas').value);

    openLoading();
    //* Validaciones **//
    if(document.getElementById('CheckSinfonolas').checked){
               
           var estado='On' 
          
    }else{
           var estado='Off'
         }

      if (ultimo_cobroSinfonolas=='' && valor=='0')
      {
        modalMensaje('Aviso', 'No se ha especificado la fecha del último cobro.');
        return;
      }
      if (fechaPagaraSinfonolas=='' && valor=='0')
      {
        modalMensaje('Aviso', 'No se ha definido la fecha hasta donde pagará.');
        return;
      }

      if (totalPagoSinfonolas_imp=='' && valor=='1')
      {
        modalMensaje('Aviso', 'No hay ningún cobro generado.');
        return;
      }
var formData = new FormData();

formData.append('id', id);
formData.append('cobrar', valor);
formData.append('estado', estado);
formData.append('id_matriculadetalleSinfonolas', id_matriculadetalleSinfonolas);
formData.append('fechaPagaraSinfonolas', fechaPagaraSinfonolas);
formData.append('ultimo_cobroSinfonolas', ultimo_cobroSinfonolas);
formData.append('tasa_interesSinfonolas', tasa_interesSinfonolas);
formData.append('fecha_interesMoratorioSinfonolas', fecha_interesMoratorioSinfonolas);

 axios.post('/admin/empresas/calculo_cobros_sinfonolas', formData, {
        })
        .then((response) => {
                console.log(response);
                  closeLoading();
                  if(response.data.success ===0){
                    toastr.error('La fecha selecionada no puede ser menor o igual a la del ultimo pago');
                    document.getElementById('fechaInicioPagoSinfonolas_imp').innerHTML=''; 
                    document.getElementById('hastaSinfonolas').innerHTML= '';
                    document.getElementById('cant_mesesSinfonolas').value=''; 
                    document.getElementById('impuestos_moraSinfonolas_imp').innerHTML='$-';
                    document.getElementById('impuesto_año_actualSinfonolas_imp').innerHTML='$-';
                    document.getElementById('InteresTotalSinfonolas_imp').innerHTML='$-';
                    document.getElementById('multaPagoExtemporaneoSinfonolas_imp').innerHTML='$-';                   
                    document.getElementById('MatriculaSinfonolas_imp').innerHTML='$-';  
                    document.getElementById('fondoFPSinfonolas_imp').innerHTML='$-';    
                    document.getElementById('multa_MartriculaSinfonolas_Imp').innerHTML='$-';            
                    document.getElementById('totalPagoSinfonolas_imp').innerHTML='$-';

                    

                  } 
                  if(response.data.success === 1){
                    $('#periodoSinfonolas').show();
                    $('#estado_de_cuenta_sinfonolasIMP').show();
                    document.getElementById('fechaInicioPagoSinfonolas_imp').innerHTML=response.data.InicioPeriodoSinfonolas; 
                    document.getElementById('hastaSinfonolas').innerHTML= response.data.PagoUltimoDiaMesSinfonolas;
                    document.getElementById('cant_mesesSinfonolas').value=response.data.Cantidad_MesesTotalSinfonolas; 
                    document.getElementById('impuestos_moraSinfonolas_imp').innerHTML=response.data.impuestos_mora_DollarSinfonolas;
                    document.getElementById('impuesto_año_actualSinfonolas_imp').innerHTML=response.data.impuesto_año_actual_DollarSinfonolas;
                    document.getElementById('InteresTotalSinfonolas_imp').innerHTML=response.data.InteresTotalDollar;
                    document.getElementById('multaPagoExtemporaneoSinfonolas_imp').innerHTML=response.data.totalMultaPagoExtemporaneoSinfonolas;                   
                    document.getElementById('MatriculaSinfonolas_imp').innerHTML=response.data.monto_pago_PmatriculaDollarSinfonolas;  
                    document.getElementById('fondoFPSinfonolas_imp').innerHTML=response.data.fondoFPSinfonolas;    
                    document.getElementById('multa_MartriculaSinfonolas_Imp').innerHTML=response.data.multa_por_matricula;            
                    document.getElementById('totalPagoSinfonolas_imp').innerHTML=response.data.totalPagoSinfonolas;
               
                  }  
                  if(response.data.success===2)
                      {
                          cobro_registrado();
                      }
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 


}

//** Termina cálculo para Sinfonolas */

//** Inicia cálculo para cobrar Aparatos Parlantes */
function calculo_cobros_aparatos(id, valor)
{
    /*Declaramos variables */
    var id_matriculadetalleAparatos=(document.getElementById('id_matriculadetalleAparatos').value);
    var ultimo_cobroAparatos=(document.getElementById('ultimo_cobroAparatos').value);
    var fecha_pagaraAparatos=(document.getElementById('fecha_hasta_donde_pagaraAparatos').value);
    openLoading();
    if (totalPagoAparatos_imp=='' && valor=='1')
    {
      modalMensaje('Aviso', 'No hay ningún cobro generado.');
      return;
    }

var formData = new FormData();

formData.append('id', id);
formData.append('cobrar', valor);
formData.append('id_matriculadetalleAparatos', id_matriculadetalleAparatos);
formData.append('ultimo_cobroAparatos', ultimo_cobroAparatos);
formData.append('fecha_pagaraAparatos', fecha_pagaraAparatos);
 axios.post('/admin/empresas/calculo_cobros_aparatos', formData, {
        })
        .then((response) => {
                console.log(response);
                  closeLoading();
                  if(response.data.success ===0){
                    toastr.error('La fecha selecionada no puede ser menor a la del ultimo pago');
                    document.getElementById('fechaInicioPagoAparatos_imp').innerHTML=''; 
                    document.getElementById('hastaAparatos').innerHTML= '';                
                    document.getElementById('MatriculaAparatos_imp').innerHTML='$-';  
                    document.getElementById('fondoFPAparatos_imp').innerHTML='$-';    
                    document.getElementById('multa_MartriculaAparatos_Imp').innerHTML='$-';            
                    document.getElementById('totalPagoAparatos_imp').innerHTML='$-';

                    

                  } 
                  if(response.data.success === 1){
                    $('#periodoAparatos').show();
                    $('#estado_de_cuenta_aparatosIMP').show();
                    document.getElementById('fechaInicioPagoAparatos_imp').innerHTML=response.data.InicioPeriodoAparatos; 
                    document.getElementById('hastaAparatos').innerHTML= response.data.PagoUltimoDiaMesAparatos;
                    document.getElementById('MatriculaAparatos_imp').innerHTML=response.data.monto_pago_PmatriculaDollarAparatos;  
                    document.getElementById('fondoFPAparatos_imp').innerHTML=response.data.fondoFPAparatos;    
                    document.getElementById('multa_MartriculaAparatos_Imp').innerHTML=response.data.multa_por_matricula;            
                    document.getElementById('totalPagoAparatos_imp').innerHTML=response.data.totalPagoAparatos;
               
                  }  
                  if(response.data.success ===2)
                      {
                          cobro_registrado();
                      }
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 


}

//** Termina cálculo para Aparatos Parlantes */

</script>


<!-- Finaliza función para calcular el pago momentaneo --------------------------------------------------------->


<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Registrar cobros</li>
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

        <form class="form-horizontal" id="formulario-Cobros">
        @csrf

        <div class="card card">
          <div class="card-header">
          <h5 class="modal-title"><i class="far fa-edit">&nbsp;</i>Registrar cobro a empresa&nbsp;<span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span></h5>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          
          <div class="card-body">
            
<!-------------------------CONEDIDO (CAMPOS) ----------------------------------------------->
      <!-- Nav.Items--> 
    <!--  <h5><i class="fas fa-cash-register"></i> &nbsp;Tipo de cobro: </h5>--> 
 
      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">       
        <li class="nav-item">
          <a class="nav-link active"  data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><i class="fas fa-hand-holding-usd"></i> &nbsp;Impuestos</a>
        </li>
        @if($licencia>'0.00')
        <li class="nav-item">
          <a class="nav-link" data-toggle="pill" href="#pills-licencia_licor" role="tab" aria-controls="pills-profile" aria-selected="false"><i class="fas fa-file-invoice-dollar"></i>&nbsp;Licencia Licor</a>
        </li>
        @endif
        @if($MatriculasNull== '0')
        @foreach($matriculasRegistradas as $dato)
        <li class="nav-item">
          <a class="nav-link"  data-toggle="pill" href="#pills-{{$dato->slug}}" onclick="recuperariD({{$dato->id}}),info_cobros_matriculas()" role="tab" aria-controls="pills-contact" aria-selected="false"><i class="fas fa-coins"></i>&nbsp;{{$dato->tipo_matricula}}</a>
        </li>
        @endforeach
        @endif
      </ul>
      <div class="tab-content" id="pills-tabContent">
      <hr>
<!------------------------------------------------------- Nav.Items ( Impuestos Empresa )------------------------------------------------------------------------------------------------------>  
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        
          <div class="row" id="cobros_empresa"><!-- /.ROWPADRE -->
          <!-- Campos del formulario de cobros -->
          <div class="col-sm-7 float-left"><!-- Panel Datos generales de la empresa -->
          <div class="card card">
          <div class="card-header text-success"><b>DATOS GENERALES</b>.</div>
            <div class="card-body"><!-- Card-body -->
            <form action="/admin/estado_cuenta/pdf" method="POST" id="formularioCalculo">
           @csrf
             <div class="row"><!-- /.ROW1 -->
            
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>NÚMERO DE TARJETA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="input-group mb-3">
                        <input type="number"  value="{{ $empresa->num_tarjeta }}" name="num_tarjeta" disabled id="num_tarjeta" class="form-control" required >
                        
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                        </div>
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DE ÚLTIMO PAGO:</label>            
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                    @if($detectorCobro=='0')
                                <input  type="text" value="{{ $empresa->inicio_operaciones }}" disabled  name="ultimo_cobro" id="ultimo_cobro" class="form-control" required >
                                  <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                  </div>
                                @else
                                <input  type="text" value="{{ $ultimo_cobro->periodo_cobro_fin }}" disabled id="ultimo_cobro" name="ultimo_cobro" class="form-control text-success" required >
                                  <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                  </div>
                    @endif
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA HASTA DONDE PAGARÁ:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input  type="date" class="form-control text-success" name="fecha_hasta_donde_pagara" id="fecha_hasta_donde_pagara" class="form-control" required >   
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>GIRO COMERCIAL:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select Giro Comercial -->
               <div class="col-md-6">
                <div class="input-group mb-3">  
                           <!-- finaliza select estado-->
                        <input type="text" disabled value="{{ $empresa->nombre_giro }}" name="giro_comercial"  id="giroc_comercial" class="form-control" required >
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-network-wired"></i></span>
                            </div> 
                      </div>
                </div>
              <!-- finaliza select Giro Comercial-->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>TASA DE INTERÉS:</label>
                  </div>
               </div><!-- /.col-md-6 -->
                <!-- /.form-group -->
                <div class="col-md-5">
                  <div  class="input-group mb-3">
                          <!-- Select estado - live search -->                        
                                <select 
                                required
                                class="form-control"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select_interes" 
                                title="-- Seleccione un interés  --"
                                 >
                                  @foreach($tasasDeInteres as $dato)
                                  <option value="{{ $dato->monto_interes }}"> {{ $dato->monto_interes }}</option>
                                  @endforeach 
                                </select> 
                                  <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                  </div>
                           <!-- finaliza select estado-->
                      </div>
               </div><!-- /.col-md-6 -->
               @if($CE==1)
                <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>TARIFA DEL MES:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input type="number"  id="tarifaMes" class="form-control" placeholder="$0.00">
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-hand-holding-usd"></i></span>
                          </div>
                    </div>
               </div><!-- /.col-md-6 -->
               @else
               <input type="hidden"  id="tarifaMes" class="form-control" value="hidden">
                @endif
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DEL INTERÉS MORATORIO:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input type="text" value="{{ $date}}" name="fecha_interes_moratorio" id="fecha_interes_moratorio" class="form-control" disabled >
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-calendar-minus"></i></span>
                          </div>
                    </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>CANTIDAD DE MESES A PAGAR:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="input-group mb-3">
                        <input type="text" disabled  name="cant_meses" id="cant_meses" class="form-control" >
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                           </div>
                      </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
                  <!-- /.form-group -->
                  <div class="col-md-6">
                  <div class="form-group">
                        <br>
                        
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                     <button type="button" class="btn btn-outline-primary btn-lg" onclick="calculo({{$empresa->id}},0);">
                       <i class="fas fa-envelope-open-text"></i>
                       &nbsp;Generar Cobro &nbsp;
                      </button>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
              
              
            </div> <!-- /.ROW1 -->
            </div> <!-- /.card card-success -->
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->


        
         <div  class="col-sm-5 float-right"><!-- Panel Tarifas -->
       
         <div class="card-header text-success"> <label> IMPUESTOS APLICADOS.</label> 
            <button type="submit" class="btn btn-outline-success btn-sm float-right" 
            onclick="reporte_empresa({{$empresa->id}});" id="estado_de_cuentaIMP" >
              <i class="fas fa-print"></i> Estado cuenta
            </button> 
             
          </div>
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6 id="periodo">
                          Periodo del: 
                             <label class="badge badge-info" id="fechaInicioPago_imp"></label>
                          &nbsp; al &nbsp;<label class="badge badge-success" id="hasta"></label>
                          
                        </h6>
                        
                  </div>
                  <hr>
              <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
                
                      <table class="table table-hover table-sm table-striped"  width:760px;>
                          <tr>
                            <th scope="col">IMPUESTOS</th>
                            <th scope="col"></th> 
                            <th scope="col"></th>
                          </tr>

                          <tr>
                            <td class="table-light">IMPUESTO MORA</td>
                            <td class="table-light">{{$empresa->mora}}</td>
                            <td class="table-light"><p id="impuestos_mora_imp"></td>
                          </tr>

                          <tr>
                            <td>IMPUESTO</td>
                            <td>{{$empresa->codigo_atc_economica}}</td>
                            <td><h6 id="impuesto_año_actual_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>INTERESES MORATORIOS</td>
                            <td>15302</td>
                            <td><h6 id="InteresTotal_imp"></td>
                          </tr>

                          <tr>
                            <td>MULTAS POR BALANCE</td>
                            <td>15313</td>
                            <td><h6 id="multa_balanceImp"></h6></td>
                          </tr>

                          <tr>
                            <td>MULTAS P. EXTEMPORANEOS</td>
                            <td>15313</td>
                            <td><h6 id="multaPagoExtemporaneo_imp"></td>
                          </tr>

                          <tr>
                            <td>FONDO F. PATRONALES 5%</td>
                            <td>12114</td>
                            <td><h6 name="fondoFP_imp" id="fondoFP_imp"></h6></td>
                          </tr>

                          <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>

                          <tr>
                            <th scope="row">TOTAL</th>
                            <td> </td>
                            <td><label name="totalPago_imp" id="totalPago_imp"></label><label</td>
                          </tr>
                        </table>
                      </form>
                      <hr>
                      <button type="button" class="btn btn-primary btn-lg btn-block" onclick="verificar();">
                       <i class="fas fa-edit"></i>
                       &nbsp;Registrar Cobro &nbsp;
                      </button>
                    </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div><!-- ROW FILA3 -->        

            </div><!-- /.Panel Tarifas -->
 
        <!-- Finaliza campos del formulario de cobros -->


        <!-------------------------FINALIZA CONTEDIDO (CAMPOS) ----------------------------------->


            <!-- Fin /.col -->
            </div>
          <!-- /.row -->
          </div>
        </div><!-- /.ROWPADRE -->
          </div> <!-- pills-Home-->
<!------------------------------------------------------- Nav.Items------------------------------------------------------------------------------------------------------>
<div class="tab-pane fade" id="pills-licencia_licor" role="tabpanel" aria-labelledby="pills-contact-tab">
          
          <section class="content-header" id="tab_licencia_licor">
              <div class="container-fluid">
                  <div>
         <!--------------- Cobro licencia licor -------------------->
         
         <div class="row" id="cobros_licor"><!-- /.ROWPADRE -->
          <!-- Campos del formulario de cobros LICENCIA LICOR-->
          <div class="col-sm-7 float-left"><!-- Panel Datos generales de la empresa -->
          <div class="card card">
          <div class="card-header text-secondary"><b>DATOS GENERALES</b>.</div>
            <div class="card-body"><!-- Card-body -->
             <div class="row"><!-- /.ROW1 -->
            
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>NÚMERO DE TARJETA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="input-group mb-3">
                        <input type="number"  value="{{ $empresa->num_tarjeta }}"  disabled id="num_tarjetaLicor" class="form-control" required >
                        
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                        </div>
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DE ÚLTIMO PAGO:</label>            
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                    @if($ultimo_cobro_licor=='')
                    <input  type="date" class="form-control text-success" id="ultimo_cobroLicor"> 
                    @else
                    <input  type="date" disabled  value="{{$ultimo_cobro_licor->periodo_cobro_fin}}" id="ultimo_cobroLicor" class="form-control text-success"> 
                    @endif
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                        </div>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA HASTA DONDE PAGARÁ:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input  type="date" class="form-control text-success"  id="fecha_hasta_donde_pagaraLicor" class="form-control" required >   
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>GIRO COMERCIAL:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                <div class="input-group mb-3">  
                <!-- finaliza select estado-->
                        <input type="text" disabled value="{{ $empresa->nombre_giro }}" id="giroc_comercialLicor" class="form-control" required >
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-network-wired"></i></span>
                            </div> 
                      </div>
                </div>
               
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                      <button type="button" class="btn btn-outline-secondary btn-lg" onclick="calculo_licencia_licor({{$empresa->id}},0);">
                       <i class="fas fa-envelope-open-text"></i>
                       &nbsp;Generar Cobro &nbsp;
                      </button>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
                          
            </div> <!-- /.ROW1 -->
            </div> <!-- /.card card-success -->
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->
        
         <div  class="col-sm-5 float-right"><!-- Panel Tarifas -->
         <div class="card-header text-secondary"> <label> IMPUESTOS APLICADOS.</label> 
         <button type="submit" class="btn btn-outline-secondary btn-sm float-right" 
            onclick="reporte_licencia_licor({{$empresa->id}});" id="estado_de_cuenta_licorIMP" >
              <i class="fas fa-print"></i> Estado cuenta
            </button> 
        
        </div>
         
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6 id="periodoLicor">
                          Periodo del: 
                          <label class="badge badge-info" id="fechaInicioPagoLicor_imp" ></label>
                         &nbsp; al &nbsp;<label class="badge badge-success" id="hastaLicor"></label>
                        </h6>
                  </div>
                  <hr>
              <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
                
                      <table class="table table-hover table-sm table-striped"  width:760px;>
                          <tr>
                            <th scope="col">IMPUESTOS</th>
                            <th scope="col"></th> 
                            <th scope="col"></th>
                          </tr>

                          <tr>
                            <td>LICENCIAS</td>
                            <td>12207</td>
                            <td><h6 id="LicenciaLicor_imp"></td>
                          </tr>

                          <tr>
                            <td>MUL. LICENCIA</td>
                            <td>15313</td>
                            <td><h6 id="MultaLicor_imp"></h6> </td>
                          </tr>

                          <tr>
                            <th scope="row">TOTAL</th>
                            <td><label id="FondoFLicor_imp"></label></td>
                            <td><label name="totalPagoLicor_imp" id="totalPagoLicor_imp"></label><label</td>
                          </tr>
                        </table>
                      <hr>
                      <button type="button" class="btn btn-secondary btn-lg btn-block" onclick="verificar_pagoLicor();">
                       <i class="fas fa-edit"></i>
                       &nbsp;Registrar Cobro &nbsp;
                      </button>
                    </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div><!-- ROW FILA3 -->        

            </div><!-- /.Panel Tarifas -->

         <!-------------- /. cobro licencia licor -------------------->

                  </div>
              </div>
          </section>

</div><!-- pills-MatriculasLicencias-->

<!------------------------------------------------------- Nav.Items------------------------------------------------------------------------------------------------------>
<div class="tab-pane fade" id="pills-mesa_de_billar" role="tabpanel" aria-labelledby="pills-contact-tab">
          
          <section class="content-header" id="tab_mesa_de_billar">
        
         <!--------------- Cobro mesa de billar -------------------->
         
         <div class="row" id="cobros_mesa"><!-- /.ROWPADRE -->
          <!-- Campos del formulario de cobros mesa de billar-->
          <div class="col-sm-7 float-left"><!-- Panel Datos generales de la empresa -->
          <div class="card card">
          <div class="card-header text-primary"><b>DATOS GENERALES</b>.</div>
            <div class="card-body"><!-- Card-body -->
             <div class="row"><!-- /.ROW1 -->
            
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>NÚMERO DE TARJETA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="input-group mb-3">
                  <input type="hidden"  value="" id="id_matriculadetalleMesas" disabled >
                        <input type="number"  value="{{ $empresa->num_tarjeta }}"  disabled id="num_tarjetaMesas" class="form-control" required >
                        
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                        </div>
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DE ÚLTIMO PAGO:</label>            
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input  type="date" class="form-control text-primary" id="ultimo_cobroMesas"> 
                        
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                        </div>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA HASTA DONDE PAGARÁ:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input  type="date"   class="form-control text-primary"  id="fecha_hasta_donde_pagaraMesas" class="form-control" required >   
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>GIRO COMERCIAL:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select Giro Comercial -->
               <div class="col-md-6">
                <div class="input-group mb-3">  
                <!-- finaliza select estado-->
                        <input type="text" disabled value="{{ $empresa->nombre_giro }}" id="giroc_comercialMesas" class="form-control" required >
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-network-wired"></i></span>
                            </div> 
                      </div>
                </div>
              <!-- finaliza select Giro Comercial-->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>TASA DE INTERÉS:</label>
                  </div>
               </div><!-- /.col-md-6 -->
                <!-- /.form-group -->
                <div class="col-md-5">
                  <div  class="input-group mb-3">
                          <!-- Select estado - live search -->
                                <select 
                                required
                                class="form-control"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select_interesMesas" 
                                title="-- Seleccione un interés  --"
                                 >
                                  
                                  @foreach($tasasDeInteres as $dato)
                                  <option value="{{ $dato->monto_interes }}"> {{ $dato->monto_interes }}</option>
                                  @endforeach 
                                </select> 
                                  <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                  </div>
                           <!-- finaliza select estado-->
                      </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DEL INTERÉS MORATORIO:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input type="text" value="{{ $date}}"  id="fecha_interes_moratorioMesas" class="form-control" disabled >
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-calendar-minus"></i></span>
                          </div>
                    </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>CANTIDAD DE MESES A PAGAR:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                    <div class="input-group mb-3">
                        <input type="text" disabled   id="cant_mesesMesas" class="form-control" >
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                           </div>
                      </div>
                      <button type="button" class="btn btn-outline-primary btn-lg" onclick="calculo_cobros_mesas({{$empresa->id}},0);">
                        <i class="fas fa-envelope-open-text"></i>
                        &nbsp;Generar Cobro &nbsp;
                      </button>
               </div><!-- /.col-md-6 -->

                <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                  <br>
                  
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                 <br>
                    <div class="form-check">
                        <input type="checkbox" onchange="f1_disabled()" class="form-check-input mi_checkbox" id="CheckMesas">
                        <label class="form-check-label" for="CheckMesas">Pagar sólo <span class="badge badge-pill badge-dark"> matrícula?</span></label>
                    </div>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->

            </div> <!-- /.ROW1 -->
            </div> <!-- /.card card-success -->
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->


        
         <div  class="col-sm-5 float-right"><!-- Panel Tarifas -->
         <div class="card-header text-primary"> <label> IMPUESTOS APLICADOS.</label>
         <button type="submit" class="btn btn-outline-primary btn-sm float-right" 
            onclick="reporte_mesas({{$empresa->id}});" id="estado_de_cuenta_mesasIMP" >
              <i class="fas fa-print"></i> Estado cuenta
            </button>
        </div>
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6 id="periodoMesas">
                          Periodo del: 
                        <label class="badge badge-info" id="fechaInicioPagoMesas_imp"></label>
                         &nbsp; al &nbsp;<label class="badge badge-primary" id="hastaMesas"></label>
                        </h6>
                  </div>
                  <hr>
              <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
                
                      <table class="table table-hover table-sm table-striped"  width:760px;>
                          <tr>
                            <th scope="col">IMPUESTOS</th>
                            <th scope="col"></th> 
                            <th scope="col"></th>
                          </tr>

                          <tr>
                            <td class="table-light">IMPUESTO MORA</td>
                            <td class="table-light">{{$empresa->mora}}</td>
                            <td class="table-light"><p id="impuestos_moraMesas_imp"></td>
                          </tr>

                          <tr>
                            <td>IMPUESTOS</td>
                            <td>{{$empresa->codigo_atc_economica}}</td>
                            <td><h6 id="impuesto_año_actualMesas_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>INTERESES MORATORIOS</td>
                            <td>15302</td>
                            <td><h6 id="InteresTotalMesas_imp"></td>
                          </tr>

                          <tr>
                            <td>MULTAS</td>
                            <td>15313</td>
                            <td><h6 id="multaPagoExtemporaneoMesas_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>MATRÍCULA</td>
                            <td>12210</td>
                            <td><h6 id="MatriculaMesas_imp"></td>
                          </tr>

                          <tr>
                            <td>FONDO F. PATRONALES 5%</td>
                            <td>12114</td>
                            <td><h6 id="fondoFPMesas_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>MUL. MATRICULA</td>
                            <td>15313</td>
                            <td><h6 id="multa_MartriculaMesas_Imp"></h6> </td>
                          </tr>

                          <tr>
                            <th scope="row">TOTAL</th>
                            <td><label id="FondoFMesas_imp"></label></td>
                            <td><label name="totalPagoMesas_imp" id="totalPagoMesas_imp"></label><label</td>
                          </tr>
                        </table>
                      <hr>
                      <button type="button" class="btn btn-primary btn-lg btn-block" onclick="verificar_cobros_mesas()">
                      <i class="fas fa-edit"></i>
                      &nbsp;Registrar Cobro &nbsp;
                      </button>
                    </div> <!-- /.ROW1 -->


                  </div> <!-- /.card-body -->
              </div><!-- ROW FILA3 -->        

            </div><!-- /.Panel Tarifas -->

         <!-------------- /. cobro mesa de billar -------------------->
   
          </section>

</div><!-- pills-MatriculasLicencias-->

<!------------------------------------------------------- Nav.Items------------------------------------------------------------------------------------------------------>
<div class="tab-pane fade" id="pills-maquinas_electronicas" role="tabpanel" aria-labelledby="pills-contact-tab">
          
      <section class="content-header" id="tab_maquinas_electronicas">
        <div class="container-fluid">
                     
        <!--------------- Cobro Máquinas electrónicas -------------------->
         
         <div class="row" id="cobrosMaquinas"><!-- /.ROWPADRE -->
          <!-- Campos del formulario de cobros Máquinas electrónicas-->
          <div class="col-sm-7 float-left"><!-- Panel Datos generales de la empresa -->
          <div class="card card">
          <div class="card-header text-warning"><b>DATOS GENERALES</b>.</div>
            <div class="card-body"><!-- Card-body -->
             <div class="row"><!-- /.ROW1 -->
            
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>NÚMERO DE TARJETA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="input-group mb-3">
                  <input type="hidden"  value="" id="id_matriculadetalleMaquinas" disabled >
                        <input type="number"  value="{{ $empresa->num_tarjeta }}"  disabled id="num_tarjetaMaquinas" class="form-control" required >
                        
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                        </div>
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DE ÚLTIMO PAGO:</label>            
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input  type="date" class="form-control text-warning" id="ultimo_cobroMaquinas">                        
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                        </div>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA HASTA DONDE PAGARÁ:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input  type="date" class="form-control text-warning"  id="fecha_hasta_donde_pagaraMaquinas" class="form-control" required >   
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>GIRO COMERCIAL:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select Giro Comercial -->
               <div class="col-md-6">
                <div class="input-group mb-3">  
                <!-- finaliza select estado-->
                        <input type="text" disabled value="{{ $empresa->nombre_giro }}" id="giroc_comercialMaquinas" class="form-control" required >
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-network-wired"></i></span>
                            </div> 
                      </div>
                </div>
              <!-- finaliza select Giro Comercial-->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>TASA DE INTERÉS:</label>
                  </div>
               </div><!-- /.col-md-6 -->
                <!-- /.form-group -->
                <div class="col-md-5">
                  <div  class="input-group mb-3">
                          <!-- Select estado - live search -->
                         
                                <select 
                                required
                                class="form-control"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select_interesMaquinas" 
                                title="-- Seleccione un interés  --"
                                 >
                                  
                                  @foreach($tasasDeInteres as $dato)
                                  <option value="{{ $dato->monto_interes }}"> {{ $dato->monto_interes }}</option>
                                  @endforeach 
                                </select> 
                                  <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                  </div>
                           <!-- finaliza select estado-->
                      </div>
               </div><!-- /.col-md-6 -->
            <!-- /.form-group -->
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DEL INTERÉS MORATORIO:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input type="text" value="{{ $date}}"  id="fecha_interes_moratorioMaquinas" class="form-control" disabled >
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-calendar-minus"></i></span>
                          </div>
                    </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>CANTIDAD DE MESES A PAGAR:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input type="text" disabled   id="cant_mesesMaquinas" class="form-control" >
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                           </div>
                      </div>
                      <button type="button" class="btn btn-outline-warning btn-lg" onclick="calculo_cobros_maquinas({{$empresa->id}},0);">
                        <i class="fas fa-envelope-open-text"></i>
                        &nbsp;Generar Cobro &nbsp;
                      </button>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                    <br>
                        <div class="form-check">
                          <input type="checkbox" onchange="f1_disabled()" class="form-check-input mi_checkbox" id="CheckMaquinas">
                        <label class="form-check-label" for="CheckMaquinas">Pagar sólo <span class="badge badge-pill badge-dark"> matrícula?</span></label>
                    </div>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
              
              
            </div> <!-- /.ROW1 -->
            </div> <!-- /.card card-success -->
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->


        
         <div  class="col-sm-5 float-right"><!-- Panel Tarifas -->
         <div class="card-header text-warning"> <label> IMPUESTOS APLICADOS.</label>
         <button type="submit" class="btn btn-outline-warning btn-sm float-right" 
            onclick="reporte_maquinas({{$empresa->id}});" id="estado_de_cuenta_maquinasIMP" >
            <i class="fas fa-print"></i> Estado cuenta
         </button>
        </div>
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6 id="periodoMaquinas">
                          Periodo del: 
                          <label class="badge badge-info" id="fechaInicioPagoMaquinas_imp"></label>
                         &nbsp; al &nbsp;<label class="badge badge-warning" id="hastaMaquinas"></label> 
                        </h6>

                  </div>
                  <hr>
              <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
                
                      <table class="table table-hover table-sm table-striped"  width:760px;>
                          <tr>
                            <th scope="col">TASAS POR SERVICIO</th>
                            <th scope="col"></th> 
                            <th scope="col"></th>
                          </tr>

                          <tr>
                            <td class="table-light">TASAS POR SERVICIO MORA</td>
                            <td class="table-light">{{$empresa->mora}}</td>
                            <td class="table-light"><p id="impuestos_moraMaquinas_imp"></td>
                          </tr>

                          <tr>
                            <td>TASAS POR SERVICIO</td>
                            <td>{{$empresa->codigo_atc_economica}}</td>
                            <td><h6 id="impuesto_año_actualMaquinas_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>INTERESES MORATORIOS</td>
                            <td>15302</td>
                            <td><h6 id="InteresTotalMaquinas_imp"></td>
                          </tr>

                          <tr>
                            <td>MATRÍCULA</td>
                            <td>12210</td>
                            <td><h6 id="MatriculaMaquinas_imp"></td>
                          </tr>

                          <tr>
                            <td>FONDO F. PATRONALES 5%</td>
                            <td>12114</td>
                            <td><h6 id="fondoFPMaquinas_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>MUL. MATRICULA</td>
                            <td>15313</td>
                            <td><h6 id="multa_MartriculaMaquinas_Imp"></h6></td>
                          </tr>

                          <tr>
                            <th scope="row">TOTAL</th>
                            <td><label id="FondoFMaquinas_imp"></label></td>
                            <td><label name="totalPagoMaquinas_imp" id="totalPagoMaquinas_imp"></label><label</td>
                          </tr>
                        </table>
                      <hr>
                      <button type="button" class="btn btn-warning btn-lg btn-block" onclick="verificar_cobros_maquinas()">
                      <i class="fas fa-edit"></i>
                      &nbsp;Registrar Cobro &nbsp;
                      </button>
                    </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div><!-- ROW FILA3 -->        

            </div><!-- /.Panel Tarifas -->

         <!-------------- /. cobro Máquinas electrónicas --------------------> 
                                     
            </div>
          </section>            
     </div><!-- pills-MatriculasLicencias-->

<!------------------------------------------------------- Nav.Items------------------------------------------------------------------------------------------------------>    

<div class="tab-pane fade" id="pills-aparatos_parlantes" role="tabpanel" aria-labelledby="pills-contact-tab">
          
 <section class="content-header" id="tab_aparatos_parlantes">
 <div class="container-fluid">
                                        
  <!--------------- Cobro APARATOS PARLANTES -------------------->
         
 <div class="row" id="cobros_aparatosparlantes"><!-- /.ROWPADRE -->
          <!-- Campos del formulario de cobros APARATOS PARLANTES-->
          <div class="col-sm-7 float-left"><!-- Panel Datos generales de la empresa -->
          <div class="card card">
          <div class="card-header text-info"><b>DATOS GENERALES</b>.</div>
            <div class="card-body"><!-- Card-body -->
             <div class="row"><!-- /.ROW1 -->
            
             <!-- /.form-group -->
             <div class="col-md-6">
                  <div class="form-group">
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>NÚMERO DE TARJETA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="input-group mb-3">
                  <input type="hidden"  value="" id="id_matriculadetalleAparatos" disabled >
                        <input type="number"  value="{{ $empresa->num_tarjeta }}"  disabled id="num_tarjetaAparatos" class="form-control" required >
                        
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                        </div>
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DE ÚLTIMO PAGO:</label>            
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input  type="date" class="form-control text-info" id="ultimo_cobroAparatos"> 
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                        </div>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                    <div class="form-group">
                    <label>FECHA HASTA DONDE PAGARÁ:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input  type="date" class="form-control text-success"  id="fecha_hasta_donde_pagaraAparatos" class="form-control" required >   
                  </div>
               </div><!-- /.col-md-6 -->

              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>GIRO COMERCIAL:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select Giro Comercial -->
               <div class="col-md-4">
                <div class="input-group mb-3">  
                <!-- finaliza select estado-->
                        <input type="text" disabled value="{{ $empresa->nombre_giro }}" id="giroc_comercialAparatos" class="form-control" required >
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-network-wired"></i></span>
                            </div>
                      </div>
                </div>
                  <!-- /.form-group -->
              <div class="col-md-6">
                  <div class="form-group">
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                      <button type="button" class="btn btn-outline-info btn-lg" onclick="calculo_cobros_aparatos({{$empresa->id}},0);">
                       <i class="fas fa-envelope-open-text"></i>
                       &nbsp;Generar Cobro &nbsp;
                      </button> 
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
                 <!-- /.form-group -->
                 <div class="col-md-6">
                  <div class="form-group">
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
              
            </div> <!-- /.ROW1 -->
            </div> <!-- /.card card-success -->
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->


        
         <div  class="col-sm-5 float-right"><!-- Panel Tarifas -->
         <div class="card-header text-info"> <label> IMPUESTOS APLICADOS.</label> 
            <button type="submit" class="btn btn-outline-info btn-sm float-right" 
              onclick="reporte_aparatos({{$empresa->id}});" id="estado_de_cuenta_aparatosIMP" >
              <i class="fas fa-print"></i> Estado cuenta
            </button>         
        </div>
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6 id="periodoAparatos">
                          Periodo del: 
                        <label class="badge badge-info" id="fechaInicioPagoAparatos_imp"> </label>
                         &nbsp; al &nbsp;<label class="badge badge-info" id="hastaAparatos"></label>
                        </h6>
                  </div>
                  <hr>
              <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
                
                      <table class="table table-hover table-sm table-striped"  width:760px;>
                          <tr>
                            <th scope="col">IMPUESTOS</th>
                            <th scope="col"></th> 
                            <th scope="col"></th>
                          </tr>

                          <tr>
                            <td>MATRÍCULA</td>
                            <td>12210</td>
                            <td><h6 id="MatriculaAparatos_imp"></td>
                          </tr>

                          <tr>
                            <td>FONDO F. PATRONALES 5%</td>
                            <td>12114</td>
                            <td><h6 id="fondoFPAparatos_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>MUL. MATRICULA</td>
                            <td>15313</td>
                            <td><h6 id="multa_MartriculaAparatos_Imp"></h6> </td>
                          </tr>

                          <tr>
                            <th scope="row">TOTAL</th>
                            <td><label><label id="FondoFAparatos_imp"> </label></label></td>
                            <td><label name="totalPagoAparatos_imp" id="totalPagoAparatos_imp"></label><label</td>
                          </tr>
                        </table>
                      <hr>
                      <button type="button" class="btn btn-info btn-lg btn-block" onclick="verificar_cobro_aparatos();">
                      <i class="fas fa-edit"></i>
                      &nbsp;Registrar Cobro &nbsp;
                      </button>
                    </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div><!-- ROW FILA3 -->        

            </div><!-- /.Panel Tarifas -->

         <!-------------- /. cobro APARATOS PARLANTES -------------------->
                              
                                    </div>
                                </section>
                     
          </div><!-- pills-MatriculasLicencias-->

<!------------------------------------------------------- Nav.Items------------------------------------------------------------------------------------------------------>    

<div class="tab-pane fade" id="pills-sinfonolas" role="tabpanel" aria-labelledby="pills-contact-tab">
          
          <section class="content-header" id="tab_pills-sinfonolas">
              <div class="container-fluid">
 <!--------------- Cobro Sinfonolas -------------------->
         
 <div class="row" id="cobros_sinfonolas"><!-- /.ROWPADRE -->
          <!-- Campos del formulario de cobros Sinfonolas-->
          <div class="col-sm-7 float-left"><!-- Panel Datos generales de la empresa -->
          <div class="card card">
          <div class="card-header text-danger"><b>DATOS GENERALES</b>.</div>
            <div class="card-body"><!-- Card-body -->
             <div class="row"><!-- /.ROW1 -->
            
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>NÚMERO DE TARJETA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="input-group mb-3">
                  <input type="hidden"  value="" id="id_matriculadetalleSinfonolas" disabled >
                        <input type="number"  value="{{ $empresa->num_tarjeta }}"  disabled id="num_tarjetaSinfonolas" class="form-control" required >
                        
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                        </div>
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DE ÚLTIMO PAGO:</label>            
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input  type="date" class="form-control text-danger" id="ultimo_cobroSinfonolas"> 
                        
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                        </div>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA HASTA DONDE PAGARÁ:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input  type="date" class="form-control text-danger"  id="fecha_hasta_donde_pagaraSinfonolas" required >   
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>GIRO COMERCIAL:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select Giro Comercial -->
               <div class="col-md-6">
                <div class="input-group mb-3">  
                <!-- finaliza select estado-->
                        <input type="text" disabled value="{{ $empresa->nombre_giro }}" id="giroc_comercialSinfonolas" class="form-control" required >
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-network-wired"></i></span>
                            </div> 
                      </div>
                </div>
              <!-- finaliza select Giro Comercial-->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>TASA DE INTERÉS:</label>
                  </div>
               </div><!-- /.col-md-6 -->
                <!-- /.form-group -->
                <div class="col-md-5">
                  <div  class="input-group mb-3">
                          <!-- Select estado - live search -->
                         
                                <select 
                                required
                                class="form-control"
                                data-style="btn-success"
                                data-show-subtext="true" 
                                data-live-search="true"   
                                id="select_interesSinfonolas" 
                                title="-- Seleccione un interés  --"
                                 >
                                  
                                  @foreach($tasasDeInteres as $dato)
                                  <option value="{{ $dato->monto_interes }}"> {{ $dato->monto_interes }}</option>
                                  @endforeach 
                                </select> 
                                  <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                  </div>
                           <!-- finaliza select estado-->
                      </div>
               </div><!-- /.col-md-6 -->
            <!-- /.form-group -->
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DEL INTERÉS MORATORIO:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input type="text" value="{{ $date}}"  id="fecha_interes_moratorioSinfonolas" class="form-control" disabled >
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-calendar-minus"></i></span>
                          </div>
                    </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>CANTIDAD DE MESES A PAGAR:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input type="text" disabled   id="cant_mesesSinfonolas" class="form-control" >
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                           </div>
                      </div>
                      <button type="button" class="btn btn-outline-danger btn-lg" onclick="calculo_cobros_sinfonolas({{$empresa->id}},0);">
                        <i class="fas fa-envelope-open-text"></i>
                        &nbsp;Generar Cobro &nbsp;
                      </button>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <br>
                        <div class="form-check">
                          <input type="checkbox" onchange="f1_disabled()" class="form-check-input mi_checkbox" id="CheckSinfonolas">
                        <label class="form-check-label" for="CheckSinfonolas">Pagar sólo <span class="badge badge-pill badge-dark"> matrícula?</span></label>
                    </div>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
              
              
            </div> <!-- /.ROW1 -->
            </div> <!-- /.card card-success -->
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->


        
         <div  class="col-sm-5 float-right"><!-- Panel Tarifas -->
         <div class="card-header text-danger"> <label> IMPUESTOS APLICADOS.</label> 
         <button type="submit" class="btn btn-outline-danger btn-sm float-right" 
            onclick="reporte_sinfonolas({{$empresa->id}});" id="estado_de_cuenta_sinfonolasIMP" >
              <i class="fas fa-print"></i> Estado cuenta
            </button> 
        </div>
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6 id="periodoSinfonolas">
                          Periodo del: 
                          <label class="badge badge-info" id="fechaInicioPagoSinfonolas_imp"> </label>
                         &nbsp; al &nbsp;<label class="badge badge-danger" id="hastaSinfonolas"></label>
                        </h6>
                  </div>
                  <hr>
              <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
                
                      <table class="table table-hover table-sm table-striped"  width:760px;>
                          <tr>
                            <th scope="col">IMPUESTOS</th>
                            <th scope="col"></th> 
                            <th scope="col"></th>
                          </tr>

                          <tr>
                            <td class="table-light">IMPUESTO MORA</td>
                            <td class="table-light">{{$empresa->mora}}</td>
                            <td class="table-light"><p id="impuestos_moraSinfonolas_imp"></td>
                          </tr>

                          <tr>
                            <td>IMPUESTOS</td>
                            <td>{{$empresa->codigo_atc_economica}}</td>
                            <td><h6 id="impuesto_año_actualSinfonolas_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>INTERESES MORATORIOS</td>
                            <td>15302</td>
                            <td><h6 id="InteresTotalSinfonolas_imp"></td>
                          </tr>

                          <tr>
                            <td>MULTAS</td>
                            <td>15313</td>
                            <td><h6 id="multaPagoExtemporaneoSinfonolas_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>MATRÍCULA</td>
                            <td>12210</td>
                            <td><h6 id="MatriculaSinfonolas_imp"></td>
                          </tr>

                          <tr>
                            <td>FONDO F. PATRONALES 5%</td>
                            <td>12114</td>
                            <td><h6 id="fondoFPSinfonolas_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>MUL. MATRICULA</td>
                            <td>15313</td>
                            <td><h6 id="multa_MartriculaSinfonolas_Imp"></h6> </td>
                          </tr>

                          <tr>
                            <th scope="row">TOTAL</th>
                            <td></td>
                            <td><label name="totalPagoSinfonolas_imp" id="totalPagoSinfonolas_imp"></label><label</td>
                          </tr>
                        </table>
                      <hr>
                      <button type="button" class="btn btn-danger btn-lg btn-block" onclick="verificar_cobro_sinfonolas();">
                      <i class="fas fa-edit"></i>
                      &nbsp;Registrar Cobro &nbsp;
                      </button>
                    </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div><!-- ROW FILA3 -->        

            </div><!-- /.Panel Tarifas -->

         <!-------------- /. cobro Sinfonolas -------------------->
              </div>
          </section>

</div><!-- pills-MatriculasLicencias-->
<!------------------------------------------------------- /.Nav.Items------------------------------------------------------------------------------------------------------>
</div>
       </div>

        <!-- /.card-footer -->
            <div class="card-footer">
            <button type="button" class="btn btn-default" onclick="VerEmpresa({{$empresa->id}} )"><i class="fas fa-chevron-circle-left"></i> &nbsp; Volver</button>
          </div>
        <!-- /.card-footer -->

        </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
<!-- Finaliza Formulario Calificar Empresa-->


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
  
function VerEmpresa(id){

window.location.href="{{ url('/admin/empresas/show') }}/"+id;

}
function reporte_empresa(id){
    
    var f1=(document.getElementById('ultimo_cobro').value);
    var f2=(document.getElementById('fecha_hasta_donde_pagara').value);
    var ti=(document.getElementById('select_interes').value);
    var f3=(document.getElementById('fecha_interes_moratorio').value);
    var tf=(document.getElementById('tarifaMes').value);

  window.open("{{ URL::to('/admin/estado_cuenta/pdf') }}/" + f1 + "/" + f2 + "/" + ti + "/" + f3 + "/" + tf + "/" + id );

}
function reporte_licencia_licor(id){

    var f1=(document.getElementById('ultimo_cobroLicor').value);
    var f2=(document.getElementById('fecha_hasta_donde_pagaraLicor').value);

  window.open("{{ URL::to('/admin/estado_cuenta_licor/pdf') }}/" + f1 + "/" + f2 + "/" + id );

}

function reporte_aparatos(id){
  
var f1=(document.getElementById('ultimo_cobroAparatos').value);
var f2=(document.getElementById('fecha_hasta_donde_pagaraAparatos').value);
var ap=(document.getElementById('id_matriculadetalleAparatos').value);

window.open("{{ URL::to('/admin/estado_cuenta_aparatos/pdf') }}/" + f1 + "/" + f2 + "/" + ap + "/" + id );

}

function reporte_sinfonolas(id){
  
  var f1=(document.getElementById('ultimo_cobroSinfonolas').value);
  var f2=(document.getElementById('fecha_hasta_donde_pagaraSinfonolas').value);
  var is=(document.getElementById('id_matriculadetalleSinfonolas').value);
  var ti=(document.getElementById('select_interesSinfonolas').value);

  window.open("{{ URL::to('/admin/estado_cuenta_sinfonolas/pdf') }}/" + f1 + "/" + f2 + "/" + is + "/" + ti + "/" + id );
  
  }

  function reporte_maquinas(id){
  
  var f1=(document.getElementById('ultimo_cobroMaquinas').value);
  var f2=(document.getElementById('fecha_hasta_donde_pagaraMaquinas').value);
  var im=(document.getElementById('id_matriculadetalleMaquinas').value);
  var ti=(document.getElementById('select_interesMaquinas').value);

  window.open("{{ URL::to('/admin/estado_cuenta_maquinas/pdf') }}/" + f1 + "/" + f2 + "/" + im + "/" + ti + "/" + id );
  
  }

  function reporte_mesas(id){
  
  var f1=(document.getElementById('ultimo_cobroMesas').value);
  var f2=(document.getElementById('fecha_hasta_donde_pagaraMesas').value);
  var ime=(document.getElementById('id_matriculadetalleMesas').value);
  var ti=(document.getElementById('select_interesMesas').value);

  window.open("{{ URL::to('/admin/estado_cuenta_mesas/pdf') }}/" + f1 + "/" + f2 + "/" + ime + "/" + ti + "/" + id );
  
  }
function GenerarCobro()
        {
            $('#modalregistrarCobros').modal('show');
            $('#btImprimirCobro').hide();
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
}//Fin- Modal Mensaje. 

function verificar(){
            Swal.fire({
                title: '¿Desea guardar el Cobro?',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Guardar'
            }).then((result) => {
                if (result.isConfirmed) {
                calculo({{$empresa->id}},1);
                }
            });
        }

function verificar_cobro_aparatos(){
            Swal.fire({
                title: '¿Desea guardar el Cobro?',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Guardar'
            }).then((result) => {
                if (result.isConfirmed) {
                calculo_cobros_aparatos({{$empresa->id}},1);
                }
            });
        }
        
function verificar_pagoLicor(){
            Swal.fire({
                title: '¿Desea guardar el Cobro?',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Guardar'
            }).then((result) => {
                if (result.isConfirmed) {
                calculo_licencia_licor({{$empresa->id}},1);
                }
            });
        }
        
function verificar_cobro_sinfonolas(){
            Swal.fire({
                title: '¿Desea guardar el Cobro?',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Guardar'
            }).then((result) => {
                if (result.isConfirmed) {
                  calculo_cobros_sinfonolas({{$empresa->id}},1);
                }
            });
        } 

function verificar_cobros_maquinas(){
            Swal.fire({
                title: '¿Desea guardar el Cobro?',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Guardar'
            }).then((result) => {
                if (result.isConfirmed) {
                  calculo_cobros_maquinas({{$empresa->id}},1);
                }
            });
        } 

function verificar_cobros_mesas(){
            Swal.fire({
                title: '¿Desea guardar el Cobro?',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Guardar'
            }).then((result) => {
                if (result.isConfirmed) {
                  calculo_cobros_mesas({{$empresa->id}},1);
                }
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

                                
function cobro_registrado(){
                      Swal.fire({
                      title: 'Cobro registrado correctamente',
                      //text: "Puede modificarla en la opción [Editar]",
                      icon: 'success',
                      showCancelButton: false,
                      confirmButtonColor: '#28a745',
                      closeOnClickOutside: false,
                      allowOutsideClick: false,
                      confirmButtonText: 'Aceptar'
                      }).then((result) => {
                        if (result.isConfirmed) 
                                    {
                                     recargar({{$empresa->id}});
                                    }
                                });
                            }


  function recargar(id){
       openLoading();
       window.location.href="{{ url('/admin/empresas/show') }}/"+id;
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

