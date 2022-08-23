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
  
<style>
    
#tres {
  overflow: hidden;

}
        #tabla {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-left: 20px;
            margin-right: 20px;
            margin-top: 35px;
            text-align: center;
        }

        #tabla td{
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 15px;
        }

        th{
            border: 0px solid #ddd;
            padding: 3px;
            text-align: left;
            background-color: #ccc;
            color: #1E1E1E;
        }

        #tabla th {
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: #1E1E1E;
            color: #ddd;
            text-align: center;
            font-size: 16px;
        }

        .texto{
            margin-left: 12px;
            display: block;
            margin: 2px 0 0 0;
            font-size: small;
        }
        #uno{
                font-size: 15px;
        }
        #dos{
                font-size: 13px;
        }
</style>
@stop    


<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                      <h5 class="modal-title"><i class="far fa-edit">
                      &nbsp;</i>Registrar cobros.</span>
                      </h5>
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
          <div class="col-md-6">
                  <div class="form-group">   
                        <label>NÚMERO DE TARJETA:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="input-group mb-3">
                         <input type="hidden"  value="" id="id_matriculadetalle" disabled ><!-- Para cargar id m. detalle si es necesario -->
                        <input type="number"  value=" " name="num_tarjeta" id="num_tarjeta" class="form-control" required >
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                        </div>
                        &nbsp;
                          <button type="button" class="btn btn-info float-right" onmouseover="abrir_modal()" id="info_empresa">
                          <i class="fas fa-info-circle"></i>
                        </button> 
                        <button type="button" class="btn btn-info float-right" onclick="buscar_empresa_cobro()" id="buscar_empresa_cobro">
                            <i class="fas fa-search"></i>&nbsp;Buscar            
                        </button> 
                  </div>
               </div><!-- /.col-md-6 -->
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          
          <div class="card-body">
            
<!-------------------------CONEDIDO (CAMPOS) ----------------------------------------------->


          <div class="row" id="cobros_empresa"><!-- /.ROWPADRE -->
          <!-- Campos del formulario de cobros -->
          <div class="col-sm-7 float-left"><!-- Panel Datos generales de la empresa -->
          <div class="card card">
          <div class="card-header text-success"><b>DATOS GENERALES.</b>
                <button type="button" class="btn btn-outline-success btn-sm float-right" 
                  onclick="historial_cobros_empresa();" id="Historial_cobrosIMP" >
                  <i class="fas fa-history"></i> Historial de cobros
                </button> 
          </div>
            <div class="card-body"><!-- Card-body -->
            <form action="/admin/estado_cuenta/pdf" method="POST" id="formularioCalculo">
           @csrf
             <div class="row"><!-- /.ROW1 -->
             
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DE ÚLTIMO PAGO:</label>            
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="input-group mb-3">
                    <input  type="text" value=" " disabled id="ultimo_cobro" name="ultimo_cobro" class="form-control text-success" required >
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
                        <input  type="date" class="form-control text-success" id="fecha_hasta_donde_pagara" >   
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
                        <input type="text" disabled value=" " name="giro_comercial"  id="giroc_comercial" class="form-control" required >
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
                <div class="tarifa_mes" id="tarifa_mes_ce">
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
               </div>

               <input type="hidden"  id="tarifaMes" class="form-control" value="hidden">

               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DEL INTERÉS MORATORIO:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-5">
                  <div class="input-group mb-3">
                        <input type="text" value="" name="fecha_interes_moratorio" id="fecha_interes_moratorio" class="form-control" disabled >
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
                     <button type="button" class="btn btn-outline-primary btn-lg" onclick="calculo(,0);">
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
              onclick="reporte_empresa();" id="estado_de_cuentaIMP" >
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
                            <td class="table-light">empresa->mora</td>
                            <td class="table-light"><p id="impuestos_mora_imp"></p></td>
                          </tr>

                          <tr>
                            <td>IMPUESTO</td>
                            <td>empresa->codigo_atc_economica</td>
                            <td><h6 id="impuesto_año_actual_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>INTERESES MORATORIOS</td>
                            <td>15302</td>
                            <td><h6 id="InteresTotal_imp"></h6></td>
                          </tr>

                          <tr>
                            <td>MULTAS POR BALANCE</td>
                            <td>15313</td>
                            <td><h6 id="multa_balanceImp"></h6></td>
                          </tr>

                          <tr>
                            <td>MULTAS P. EXTEMPORANEOS</td>
                            <td>15313</td>
                            <td><h6 id="multaPagoExtemporaneo_imp"></h6></td>
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


<!------------------------------------------------------- /.Nav.Items------------------------------------------------------------------------------------------------------>
    </div>
</div>


        </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
<!-- Finaliza Formulario -->



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
            $('#info_empresa').hide();
            $('#tarifa_mes_ce').hide();
            $('#cobros_empresa').hide();
            
        });
</script>

@endsection 