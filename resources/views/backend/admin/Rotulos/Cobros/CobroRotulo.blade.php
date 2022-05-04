@extends('backend.menus.superior')

@section('content-admin-css')

    <!-- Para el select live search -->
    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">
    <!-- Finaliza el select live search -->
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />

 <!-- Para vista detallada --> 

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

 <!-- Para vista detallada fin -->

@stop
<script>
   function f1()
        {
           document.getElementById('select_interes').disabled=true;
         //  document.getElementById('select_interesMesas').disabled=true;
           $('#periodo').hide();
        
        }

        window.onload = f1;
</script>

<script>
  function calculo(id)
{
    /*Declaramos variables */
    var id_empresa = (document.getElementById('id_empresa').value);
    var fechaPagara=(document.getElementById('fecha_hasta_donde_pagara').value);
    var ultimo_cobro=(document.getElementById('ultimo_cobro').value);
    var tasa_interes=(document.getElementById('select_interes').value);
    var fecha_interesMoratorio=(document.getElementById('fecha_interes_moratorio').value);


var formData = new FormData();

formData.append('id', id);
formData.append('id_empresa', id_empresa);
formData.append('fechaPagara', fechaPagara);
formData.append('ultimo_cobro', ultimo_cobro);
formData.append('tasa_interes', tasa_interes);
formData.append('fecha_interesMoratorio', fecha_interesMoratorio);

 axios.post('/admin/rotulos/calcular-Cobros', formData, {
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
                  
                    document.getElementById('impuestos_mora_imp').innerHTML='$-';
                    document.getElementById('impuesto_año_actual_imp').innerHTML='$-';
                    document.getElementById('fechaInicioPago_imp').innerHTML='';                   
                    document.getElementById('InteresTotal_imp').innerHTML='$-';
                  } 
                  if(response.data.success === 1){
                    $('#periodo').show();
                    document.getElementById('hasta').innerHTML=response.data.PagoUltimoDiaMes;
                    document.getElementById('cant_meses').value=response.data.Cantidad_MesesTotal;
                    document.getElementById('fondoFP_imp').innerHTML=response.data.fondoFP;
                    document.getElementById('totalPago_imp').innerHTML=response.data.totalPago;
                    
                
                    document.getElementById('impuestos_mora_imp').innerHTML=response.data.impuestos_mora_Dollar;
                    document.getElementById('impuesto_año_actual_imp').innerHTML=response.data.impuesto_año_actual_Dollar;
                    document.getElementById('fechaInicioPago_imp').innerHTML=response.data.InicioPeriodo;                    
                    document.getElementById('InteresTotal_imp').innerHTML=response.data.InteresTotalDollar;
                    
                  }  
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 


}

</script>


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
          <h5 class="modal-title"><i class="far fa-edit">&nbsp;</i>Registrar cobro a rótulo&nbsp;<span class="badge badge-warning">&nbsp;{{$rotulo->nom_rotulo}} &nbsp;</span></h5>

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
          <a class="nav-link active"  data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><i class="fas fa-hand-holding-usd"></i> &nbsp;Rótulos</a>
        </li>
     
       
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
                        <input type="number"  value="{{$rotulo->id}}" name="" disabled id="id_rotulos" class="form-control" required >                     
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                        </div>
                        
                  </div>
                  <input type="number"  value="{{$empresa->id_empresa}}" name="" disabled id="id_empresa" class="form-control" required >
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
                
                                <input  type="text" value="2021-04-30" disabled  name="ultimo_cobro" id="ultimo_cobro" class="form-control" required >
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
                        <input  type="date"  onchange="calculo({{$rotulo->id}});" class="form-control text-success" name="fecha_hasta_donde_pagara" id="fecha_hasta_donde_pagara" class="form-control" required >   
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>ACTIVIDAD ECONÓMICA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- Inicia Select Giro Comercial -->
               <div class="col-md-6">
                <div class="input-group mb-3">  
                           <!-- finaliza select estado-->
                        <input type="text" disabled value="{{$rotulo->actividad_economica}}" name="giro_comercial"  id="giroc_comercial" class="form-control" required >
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
            <!-- /.form-group -->
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DEL INTERÉS MORATORIO:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input type="text" value="{{$date}}" name="fecha_interes_moratorio" id="fecha_interes_moratorio" class="form-control" disabled >
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
                         
                          <label id="fechaInicioPago_imp" class="text-success">  </label>                         
                                                      
                          &nbsp; al &nbsp;<label class="badge badge-success" id="hasta"></label>
                        </h6>
                  </div>
                  <hr>           
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
                            <td class="table-light">TASAS POR SERVICIO MORA</td>
                            <td class="table-light">{{$rotulo->mora}}</td>
                            <td class="table-light"><h6 id="impuestos_mora_imp"></h6></td>
                          
                          </tr>

                          <tr>
                            <td>TASAS POR SERVICIO</td>
                            <td>12299</td>
                            <td><h6 id="impuesto_año_actual_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>INTERESES MORATORIOS</td>
                            <td>15302</td>
                            <td><h6 id="InteresTotal_imp"></td>
                          </tr>

                          <tr>
                            <td></td>
                            <td></td>
                            <td>-   </td>
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
                            <td><label>$</label></td>
                            <td><label name="totalPago_imp" id="totalPago_imp"></label><label</td>
                          </tr>
                        </table>
                      <hr>
                    </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div><!-- ROW FILA3 -->        
              <button type="button" class="btn btn-primary btn-lg btn-block" onclick="verificar();">
                       <i class="fas fa-edit"></i>
                       &nbsp;Registrar Cobro &nbsp;
                      </button>

            </div><!-- /.Panel Tarifas -->
 
        <!-- Finaliza campos del formulario de cobros -->


        <!-------------------------FINALIZA CONTEDIDO (CAMPOS) ----------------------------------->


            <!-- Fin /.col -->
            </div>
          <!-- /.row -->
          </div>
        </div><!-- /.ROWPADRE -->
          </div> <!-- pills-Home-->



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


     
    <script type="text/javascript">
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
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

    @stop