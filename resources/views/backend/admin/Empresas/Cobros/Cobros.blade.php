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
    function f1()
        {
           document.getElementById('select_interes').disabled=true;
           document.getElementById('select_interesMesas').disabled=true;
           $('#periodo').hide();
           $('#periodoMesas').hide();
           $('#periodoMaquinas').hide();
           $('#periodoSinfonolas').hide();
           $('#periodoAparatos').hide();
           
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
                        document.getElementById('ultimo_cobroMesas').value=response.data.ultimo_cobroMesas;
                        document.getElementById('ultimo_cobroMesas').disabled=true;

                    }
                    if(response.data.ultimo_cobroMaquinas!=null)
                    {
                        document.getElementById('ultimo_cobroMaquinas').value=response.data.ultimo_cobroMaquinas;
                        document.getElementById('ultimo_cobroMaquinas').disabled=true;
                    }
                    if(response.data.ultimo_cobroSinfonolas!=null)
                    {
                        document.getElementById('ultimo_cobroSinfonolas').value=response.data.ultimo_cobroSinfonolas;
                        document.getElementById('ultimo_cobroSinfonolas').disabled=true;
                    }
                    if(response.data.ultimo_cobroAparatos!=null)
                    {
                        document.getElementById('ultimo_cobroAparatos').value=response.data.ultimo_cobroAparatos;
                        document.getElementById('ultimo_cobroAparatos').disabled=true;
                    }

                  }  
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 

        }//Termina funcion info_cobros_matriculas

function calculo(id)
{
    /*Declaramos variables */
    var fechaPagara=(document.getElementById('fecha_hasta_donde_pagara').value);
    var ultimo_cobro=(document.getElementById('ultimo_cobro').value);
    var tasa_interes=(document.getElementById('select_interes').value);
    var fecha_interesMoratorio=(document.getElementById('fecha_interes_moratorio').value);


var formData = new FormData();

formData.append('id', id);
formData.append('fechaPagara', fechaPagara);
formData.append('ultimo_cobro', ultimo_cobro);
formData.append('tasa_interes', tasa_interes);
formData.append('fecha_interesMoratorio', fecha_interesMoratorio);

 axios.post('/admin/empresas/calculo_cobros_empresa', formData, {
        })
        .then((response) => {
                console.log(response);
                  closeLoading();
                  if(response.data.success !=1){
                    toastr.error('La fecha selecionada no puede ser menor a la del ultimo pago');
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
                    document.getElementById('hasta').innerHTML=response.data.PagoUltimoDiaMes;
                    document.getElementById('cant_meses').value=response.data.Cantidad_MesesTotal;
                    document.getElementById('fondoFP_imp').innerHTML=response.data.fondoFP;
                    document.getElementById('totalPago_imp').innerHTML=response.data.totalPago;
                    document.getElementById('fechahasta_imp').innerHTML=fechaPagara;
                    document.getElementById('multa_balanceImp').innerHTML=response.data.multas_balance;
                    document.getElementById('impuestos_mora_imp').innerHTML=response.data.impuestos_mora_Dollar;
                    document.getElementById('impuesto_año_actual_imp').innerHTML=response.data.impuesto_año_actual_Dollar;
                    document.getElementById('fechaInicioPago_imp').innerHTML=response.data.InicioPeriodo;
                    document.getElementById('multaPagoExtemporaneo_imp').innerHTML=response.data.multaPagoExtemporaneoDollar;
                    document.getElementById('InteresTotal_imp').innerHTML=response.data.InteresTotalDollar;
                      if(response.data.totalMultaPagoExtemporaneos>=2.86)
                      {
                        document.getElementById('select_interes').disabled=false;
                        modalMensaje('Multa', 'Esta empresa tiene una multa por pago extemporaneo');
                      }
                  }  
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 


}
//** Inicia cálculo para cobrar mesas de billar */
function calculo_cobros_mesas(id)
{
    /*Declaramos variables */
    var id_matriculadetalleMesas=(document.getElementById('id_matriculadetalleMesas').value);
    var fechaPagaraMesas=(document.getElementById('fecha_hasta_donde_pagaraMesas').value);
    var ultimo_cobroMesas=(document.getElementById('ultimo_cobroMesas').value);
    var tasa_interesMesas=(document.getElementById('select_interesMesas').value);
    var fecha_interesMoratorioMesas=(document.getElementById('fecha_interes_moratorioMesas').value);


var formData = new FormData();

formData.append('id', id);
formData.append('id_matriculadetalleMesas', id_matriculadetalleMesas);
formData.append('fechaPagaraMesas', fechaPagaraMesas);
formData.append('ultimo_cobroMesas', ultimo_cobroMesas);
formData.append('tasa_interesMesas', tasa_interesMesas);
formData.append('fecha_interesMoratorioMesas', fecha_interesMoratorioMesas);

 axios.post('/admin/empresas/calculo_cobros_mesas', formData, {
        })
        .then((response) => {
                console.log(response);
                  closeLoading();
                  if(response.data.success !=1){
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
                    document.getElementById('multa_MartriculaMesasImp').innerHTML='$-';            
                    document.getElementById('totalPagoMesas_imp').innerHTML='$-';

                    

                  } 
                  if(response.data.success === 1){
                    $('#periodoMesas').show();
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
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 


}

//** Termina cálculo para mesas de billar */

//** Inicia cálculo para cobrar MÁQUINAS ELECTRÓNICAS */
function calculo_cobros_maquinas(id)
{
    /*Declaramos variables */
    var id_matriculadetalleMaquinas=(document.getElementById('id_matriculadetalleMaquinas').value);
    var fechaPagaraMaquinas=(document.getElementById('fecha_hasta_donde_pagaraMaquinas').value);
    var ultimo_cobroMaquinas=(document.getElementById('ultimo_cobroMaquinas').value);
    var tasa_interesMaquinas=(document.getElementById('select_interesMaquinas').value);
    var fecha_interesMoratorioMaquinas=(document.getElementById('fecha_interes_moratorioMaquinas').value);


var formData = new FormData();

formData.append('id', id);
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
                  if(response.data.success !=1){
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
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 


}

//** Termina cálculo para máquinas electrónicas */

//** Inicia cálculo para cobrar SINFONOLAS */
function calculo_cobros_sinfonolas(id)
{
    /*Declaramos variables */
    var id_matriculadetalleSinfonolas=(document.getElementById('id_matriculadetalleSinfonolas').value);
    var fechaPagaraSinfonolas=(document.getElementById('fecha_hasta_donde_pagaraSinfonolas').value);
    var ultimo_cobroSinfonolas=(document.getElementById('ultimo_cobroSinfonolas').value);
    var tasa_interesSinfonolas=(document.getElementById('select_interesSinfonolas').value);
    var fecha_interesMoratorioSinfonolas=(document.getElementById('fecha_interes_moratorioSinfonolas').value);


var formData = new FormData();

formData.append('id', id);
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
                  if(response.data.success !=1){
                    toastr.error('La fecha selecionada no puede ser menor a la del ultimo pago');
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
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 


}

//** Termina cálculo para Sinfonolas */

//** Inicia cálculo para cobrar Aparatos Parlantes */
function calculo_cobros_aparatos(id)
{
    /*Declaramos variables */
    var id_matriculadetalleAparatos=(document.getElementById('id_matriculadetalleAparatos').value);
    var fechaPagaraAparatos=(document.getElementById('fecha_hasta_donde_pagaraAparatos').value);
    var ultimo_cobroAparatos=(document.getElementById('ultimo_cobroAparatos').value);



var formData = new FormData();

formData.append('id', id);
formData.append('id_matriculadetalleAparatos', id_matriculadetalleAparatos);
formData.append('fechaPagaraAparatos', fechaPagaraAparatos);
formData.append('ultimo_cobroAparatos', ultimo_cobroAparatos);

 axios.post('/admin/empresas/calculo_cobros_aparatos', formData, {
        })
        .then((response) => {
                console.log(response);
                  closeLoading();
                  if(response.data.success !=1){
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
                    document.getElementById('fechaInicioPagoAparatos_imp').innerHTML=response.data.InicioPeriodoAparatos; 
                    document.getElementById('hastaAparatos').innerHTML= response.data.PagoUltimoDiaMesAparatos;
                    document.getElementById('MatriculaAparatos_imp').innerHTML=response.data.monto_pago_PmatriculaDollarAparatos;  
                    document.getElementById('fondoFPAparatos_imp').innerHTML=response.data.fondoFPAparatos;    
                    document.getElementById('multa_MartriculaAparatos_Imp').innerHTML=response.data.multa_por_matricula;            
                    document.getElementById('totalPagoAparatos_imp').innerHTML=response.data.totalPagoAparatos;
               
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
        <li class="nav-item">
          <a class="nav-link" data-toggle="pill" href="#pills-licencia_licor" role="tab" aria-controls="pills-profile" aria-selected="false"><i class="fas fa-file-invoice-dollar"></i>&nbsp;Licencia Licor</a>
        </li>
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
         
          <div class="row" id="cobros"><!-- /.ROWPADRE -->
          <!-- Campos del formulario de cobros -->
          <div class="col-sm-7 float-left"><!-- Panel Datos generales de la empresa -->
          <div class="card card">
          <div class="card-header text-success"><b>DATOS GENERALES</b>.</div>
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
                                <input  type="text" value="{{ $calificaciones->inicio_operaciones }}" disabled  name="ultimo_cobro" id="ultimo_cobro" class="form-control" required >
                                  <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                  </div>
                                @else
                                <input  type="text" value="{{ $ultimo_cobro->fecha_pago }}" disabled id="ultimo_cobro" name="ultimo_cobro" class="form-control text-success" required >
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
                        <input  type="date"  onchange="calculo({{$empresa->id}});" class="form-control text-success" name="fecha_hasta_donde_pagara" id="fecha_hasta_donde_pagara" class="form-control" required >   
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
                                 <option value="Ninguno">Ninguno</option>
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
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
              
              
            </div> <!-- /.ROW1 -->
            </div> <!-- /.card card-success -->
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->


        
         <div  class="col-sm-5 float-right"><!-- Panel Tarifas -->
         <div class="card-header text-success"> <label> IMPUESTOS APLICADOS.</label> </div>
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6 id="periodo">
                          Periodo del: 
                          @if($detectorNull=='0')
                          <label id="fechaInicioPago_imp" class="text-success">  </label> 
                           @else
                             <label class="badge badge-info">{{ $ultimo_cobro->fecha_pago }} </label>
                           @endif 
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
                            <td>-   </td>
                          </tr>

                          <tr>
                            <th scope="row">TOTAL</th>
                            <td><label>$<label name="FondoF_imp" id="FondoF_imp"> </label></label></td>
                            <td><label name="totalPago_imp" id="totalPago_imp"></label><label</td>
                          </tr>
                        </table>
                      <hr>
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
         
         <div class="row" id="cobros"><!-- /.ROWPADRE -->
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
                  <input type="hidden"  value="" id="id_matriculadetalleLicor" disabled >
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
                        <input  type="date" class="form-control text-success" id="ultimo_cobroLicor"> 
                        
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
                        <input  type="date"  onchange="calculo_cobros_Licor({{$empresa->id}});" class="form-control text-success"  id="fecha_hasta_donde_pagaraLicor" class="form-control" required >   
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
                        <input type="text" disabled value="{{ $empresa->nombre_giro }}" id="giroc_comercialLicor" class="form-control" required >
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
                                id="select_interesLicor" 
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
                        <input type="text" value="{{ $date}}"  id="fecha_interes_moratorioLicor" class="form-control" disabled >
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
                        <input type="text" disabled   id="cant_mesesLicor" class="form-control" >
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
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
              
              
            </div> <!-- /.ROW1 -->
            </div> <!-- /.card card-success -->
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->


        
         <div  class="col-sm-5 float-right"><!-- Panel Tarifas -->
         <div class="card-header text-secondary"> <label> IMPUESTOS APLICADOS.</label> </div>
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6 id="periodoLicor">
                          Periodo del: 
                          @if($detectorNull=='0')
                          <label id="fechaInicioPagoLicor_imp" class="text-success">  </label> 
                           @else
                             <label class="badge badge-info">{{ $ultimo_cobro->fecha_pago }} </label>
                           @endif 
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
                            <td class="table-light">IMPUESTO MORA</td>
                            <td class="table-light">{{$empresa->mora}}</td>
                            <td class="table-light"><p id="impuestos_moraLicor_imp"></td>
                          </tr>

                          <tr>
                            <td>IMPUESTOS</td>
                            <td>{{$empresa->codigo_atc_economica}}</td>
                            <td><h6 id="impuesto_año_actualLicor_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>INTERESES MORATORIOS</td>
                            <td>15302</td>
                            <td><h6 id="InteresTotalLicor_imp"></td>
                          </tr>

                          <tr>
                            <td>MULTAS</td>
                            <td>15313</td>
                            <td><h6 id="multaPagoExtemporaneoLicor_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>MATRÍCULA</td>
                            <td>12210</td>
                            <td><h6 id="MatriculaLicor_imp"></td>
                          </tr>

                          <tr>
                            <td>FONDO F. PATRONALES 5%</td>
                            <td>12114</td>
                            <td><h6 id="fondoFPLicor_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>MUL. MATRICULA</td>
                            <td>15313</td>
                            <td><h6 id="multa_MartriculaLicor_Imp"></h6> </td>
                          </tr>

                          <tr>
                            <th scope="row">TOTAL</th>
                            <td><label>$<label id="FondoFLicor_imp"> </label></label></td>
                            <td><label name="totalPagoLicor_imp" id="totalPagoLicor_imp"></label><label</td>
                          </tr>
                        </table>
                      <hr>
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
         
         <div class="row" id="cobros"><!-- /.ROWPADRE -->
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
                        <input  type="date" class="form-control text-success" id="ultimo_cobroMesas"> 
                        
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
                        <input  type="date"  onchange="calculo_cobros_mesas({{$empresa->id}});" class="form-control text-success"  id="fecha_hasta_donde_pagaraMesas" class="form-control" required >   
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
                        <input type="text" disabled value="{{ $empresa->nombre_giro }}" id="giroc_comercialMesas" class="form-control" required >
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
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>CANTIDAD DE MESES A PAGAR:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="input-group mb-3">
                        <input type="text" disabled   id="cant_mesesMesas" class="form-control" >
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
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
              
              
            </div> <!-- /.ROW1 -->
            </div> <!-- /.card card-success -->
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->


        
         <div  class="col-sm-5 float-right"><!-- Panel Tarifas -->
         <div class="card-header text-primary"> <label> IMPUESTOS APLICADOS.</label> </div>
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6 id="periodoMesas">
                          Periodo del: 
                          @if($detectorNull=='0')
                          <label id="fechaInicioPagoMesas_imp" class="text-success">  </label> 
                           @else
                             <label class="badge badge-info">{{ $ultimo_cobro->fecha_pago }} </label>
                           @endif 
                         &nbsp; al &nbsp;<label class="badge badge-success" id="hastaMesas"></label>
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
                            <td><label>$<label id="FondoFMesas_imp"> </label></label></td>
                            <td><label name="totalPagoMesas_imp" id="totalPagoMesas_imp"></label><label</td>
                          </tr>
                        </table>
                      <hr>
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
                        <input  type="date" class="form-control text-success" id="ultimo_cobroMaquinas"> 
                        
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
                        <input  type="date"  onchange="calculo_cobros_maquinas({{$empresa->id}});" class="form-control text-success"  id="fecha_hasta_donde_pagaraMaquinas" class="form-control" required >   
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
               <div class="col-md-3">
                  <div class="input-group mb-3">
                        <input type="text" disabled   id="cant_mesesMaquinas" class="form-control" >
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
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
              
              
            </div> <!-- /.ROW1 -->
            </div> <!-- /.card card-success -->
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->


        
         <div  class="col-sm-5 float-right"><!-- Panel Tarifas -->
         <div class="card-header text-warning"> <label> IMPUESTOS APLICADOS.</label> </div>
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6 id="periodoMaquinas">
                          Periodo del: 
                          @if($detectorNull=='0')
                          <label id="fechaInicioPagoMaquinas_imp" class="text-success">  </label> 
                           @else
                             <label class="badge badge-info">{{ $ultimo_cobro->fecha_pago }} </label>
                           @endif 
                         &nbsp; al &nbsp;<label class="badge badge-success" id="hastaMaquinas"></label>
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
                            <td><label>$<label id="FondoFMaquinas_imp"> </label></label></td>
                            <td><label name="totalPagoMaquinas_imp" id="totalPagoMaquinas_imp"></label><label</td>
                          </tr>
                        </table>
                      <hr>
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
                        <input  type="date" class="form-control text-success" id="ultimo_cobroAparatos"> 
                        
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
               <div class="col-md-5">
                  <div class="form-group">
                        <input  type="date"  onchange="calculo_cobros_aparatos({{$empresa->id}});" class="form-control text-success"  id="fecha_hasta_donde_pagaraAparatos" class="form-control" required >   
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
               <div class="col-md-4">
                <div class="input-group mb-3">  
                <!-- finaliza select estado-->
                        <input type="text" disabled value="{{ $empresa->nombre_giro }}" id="giroc_comercialAparatos" class="form-control" required >
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
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
              
              
            </div> <!-- /.ROW1 -->
            </div> <!-- /.card card-success -->
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->


        
         <div  class="col-sm-5 float-right"><!-- Panel Tarifas -->
         <div class="card-header text-info"> <label> IMPUESTOS APLICADOS.</label> </div>
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6 id="periodoAparatos">
                          Periodo del: 
                          @if($detectorNull=='0')
                          <label id="fechaInicioPagoAparatos_imp" class="text-success">  </label> 
                           @else
                             <label class="badge badge-info">{{ $ultimo_cobro->fecha_pago }} </label>
                           @endif 
                         &nbsp; al &nbsp;<label class="badge badge-success" id="hastaAparatos"></label>
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
                            <td><label>$<label id="FondoFAparatos_imp"> </label></label></td>
                            <td><label name="totalPagoAparatos_imp" id="totalPagoAparatos_imp"></label><label</td>
                          </tr>
                        </table>
                      <hr>
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
         
 <div class="row" id="cobrosSinfonolas"><!-- /.ROWPADRE -->
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
                        <input  type="date" class="form-control text-success" id="ultimo_cobroSinfonolas"> 
                        
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
                        <input  type="date"  onchange="calculo_cobros_sinfonolas({{$empresa->id}});" class="form-control text-success"  id="fecha_hasta_donde_pagaraSinfonolas" class="form-control" required >   
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
               <div class="col-md-3">
                  <div class="input-group mb-3">
                        <input type="text" disabled   id="cant_mesesSinfonolas" class="form-control" >
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
                        <br>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
              
              
            </div> <!-- /.ROW1 -->
            </div> <!-- /.card card-success -->
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->


        
         <div  class="col-sm-5 float-right"><!-- Panel Tarifas -->
         <div class="card-header text-danger"> <label> IMPUESTOS APLICADOS.</label> </div>
            <div class="card-body">

              <div class="row"><!-- /.ROW FILA1 -->

                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6 id="periodoSinfonolas">
                          Periodo del: 
                          @if($detectorNull=='0')
                          <label id="fechaInicioPagoSinfonolas_imp" class="text-success">  </label> 
                           @else
                             <label class="badge badge-info">{{ $ultimo_cobro->fecha_pago }} </label>
                           @endif 
                         &nbsp; al &nbsp;<label class="badge badge-success" id="hastaSinfonolas"></label>
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
                            <td><label>$<label id="FondoFSinfonolas_imp"> </label></label></td>
                            <td><label name="totalPagoSinfonolas_imp" id="totalPagoSinfonolas_imp"></label><label</td>
                          </tr>
                        </table>
                      <hr>
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
            <button type="button" class="btn btn-success float-right" onclick="GenerarCobro()"><i class="fas fa-envelope-open-text"></i>
            &nbsp;Generar Cobro&nbsp;</button>
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

<!--Inicia Modal Registrar Cobros--------------------------------------------------------------->

<div class="modal fade" id="modalregistrarCobros">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Registrar cobro a empresa&nbsp;<span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formulario-registrarCobro">
              <div class="card-body">

  <!-- Inicia Formulario Calificacion--> 
   <section class="content">
      <div class="container-fluid">
        <form class="form-horizontal" id="formulario-registrarCobros">
        @csrf

          <div class="card card-green">
            <div class="card-header">
            <h3 class="card-title">MANDAMIENTO DE PAGO.</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->


          <!-- Campos del formulario de cobros -->

         <div class="card border-success mb-3"><!-- Panel Datos generales de la empresa -->
         <div class="card-header text-success"><label>II. DATOS GENERALES DE LA EMPRESA</label></div>
          <div class="card-body"><!-- Card-body -->
            <div class="row"><!-- /.ROW1 -->
             
             <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Empresa:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input type="text"  value="{{ $empresa->nombre }}" name="nombre" disabled id="nombre_empresa" class="form-control" required >
                        <input type="hidden"  value="{{ $empresa->id }}" name="id_empresa" disabled id="id_empresa" class="form-control" required >
                  </div>
               </div><!-- /.col-md-6 -->
                <!-- /.form-group -->
              <div class="col-md-6">
                  <div class="form-group">
                        <label>Número de tarjeta:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-3">
                  <div class="form-group">
                        <input type="number"  value="{{ $empresa->num_tarjeta }}" name="num_tarjeta" disabled id="num_tarjeta" class="form-control" required >
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
             
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Contribuyente:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input type="text" disabled value="{{ $empresa->contribuyente }}&nbsp;{{ $empresa->apellido }}" name="contribuyente" id="contribuyente" class="form-control" >
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
                <!-- /.form-group -->
                <div class="col-md-12">
                  <div class="form-group">
                        <h6>
                          Periodo del: 
                          @if($detectorNull=='0')
                          <label id="fechaInicioPago_imp"> </label> 
                           @else
                             <label>{{ $ultimo_cobro->fecha_pago }} </label>
                           @endif 
                          &nbsp; al &nbsp;<label   id="fechahasta_imp"></label>
                        </h6>
                  </div>
              <!-- /.form-group -->
              
      
              <table class="table table-hover table-sm table-striped" border="1" width:760px;>
                    <tr class="table-success">
                      <td class="table-light">
                      <table class="table table-hover table-sm table-striped" border="1" width:760px;>
                          <tr class="table-secondary">
                            <th scope="col">IMPUESTOS</th>
                            <th scope="col"></th> 
                            <th scope="col"></th>
                          </tr>

                          <tr class="table-light">
                            <td class="table-light">IMPUESTO MORA</td>
                            <td class="table-light">32201</td>
                            <td class="table-light">$aquí</td>
                          </tr>

                          <tr class="table-success">
                            <td>IMPUESTO</td>
                            <td>11804</td>
                            <td> </h6></td>
                          </tr>

                          <tr class="table-light">
                            <td>INTERESES MORATORIOS</td>
                            <td>15302</td>
                            <td>$aquí</td>
                          </tr>

                          <tr class="table-success">
                            <td>MULTA</td>
                            <td>15313</td>
                            <td>$aquí</td>
                          </tr>

                          <tr class="table-light">
                            <td></td>
                            <td></td>
                            <td>$-   </td>
                          </tr>

                          <tr class="table-success">
                            <td>FONDO F. PATRONALES 5%</td>
                            <td>12114</td>
                            <td><h6 name="fondoFP_imp" id="fondoFP_imp"></h6></td>
                          </tr>

                          <tr class="table-light">
                            <td></td>
                            <td></td>
                            <td>$-   </td>
                          </tr>

                          <tr class="table-success">
                            <td></td>
                            <td></td>
                            <td>$-   </td>
                          </tr>

                          <tr class="table-secondary">
                            <th scope="row">TOTAL</th>
                            <td><label>$ </label></td>
                            <td><label name="totalPago_imp" id="totalPago_imp"></label><label</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>


            </div> <!-- /.ROW1 -->
            </div> 
            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->
          
            <!-- /.col1 -->

        

  <!-- Finaliza campos del formulario de calificación -->


         <!-- /.card-body -->
         <div class="card-footer">
            <button type="button" class="btn btn-secondary" id="btImprimirCobro" onclick="ImpimirCobro()"><i class="fa fa-print">
            </i>&nbsp; Impimir Calificación&nbsp;</button>
            <button type="button" class="btn btn-success float-right" onclick="nuevoCobro()"><i class="fas fa-edit">
            </i> &nbsp;Registrar Cobro&nbsp;</button>
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

       </form> <!-- /.formulario-Modal Registrar Cobro -->
      </div> <!-- /.Card-body -->
     </div> <!-- /.modalModalRegistrarCobro -->
   </div> <!-- /.modal-dialog modal-xl -->
  </div> <!-- /.modal-content -->
 </div> <!-- /.modal-body -->

<!-- Finaliza Modal Registrar Cobro--------------------------------------------------------->


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

