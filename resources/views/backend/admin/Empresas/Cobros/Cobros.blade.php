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
<!-- Función para calcular el pago momentaneo------------------------------------------>
<script> 

function calculo(id)
{
    /*Declaramos variables */
    var fechaPagara=(document.getElementById('fecha_hasta_donde_pagara').value);
    var ultimo_cobro=(document.getElementById('ultimo_cobro').value);
    var CantidadMesesAPagar=(document.getElementById('cant_meses').value);


var formData = new FormData();

formData.append('fechaPagara', fechaPagara);
formData.append('ultimo_cobro', ultimo_cobro);
formData.append('CantidadMesesAPagar', CantidadMesesAPagar);

 axios.post('/admin/empresas/calculo_cobros'+id, formData, {
        })
        .then((response) => {
                console.log(response);
                  closeLoading();
                  if(response.data.success !=1){
                    toastr.error('La fecha selecionada no puede ser menor a la del ultimo pago');
                    document.getElementById('hasta').innerHTML= '';
                    document.getElementById('cant_meses').value='';
                    document.getElementById('impuestos_imp').innerHTML='$-';
                    document.getElementById('fondoFP_imp').innerHTML='$-';
                    document.getElementById('totalPago_imp').innerHTML='$-';
                  } 
                  if(response.data.success === 1){
                    document.getElementById('hasta').innerHTML= fechaPagara;
                    document.getElementById('cant_meses').value=response.data.cantidadMeses;
                    document.getElementById('impuestos_imp').innerHTML=response.data.impuestos;
                    document.getElementById('fondoFP_imp').innerHTML=response.data.fondoFP;
                    document.getElementById('totalPago_imp').innerHTML=response.data.totalPago;
                  }  
              })
              .catch((error) => {
                  toastr.error('Error');
                  closeLoading();
              }); 


}

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

        <div class="card card-green">
          <div class="card-header">
          <h5 class="modal-title">Registrar cobro a empresa&nbsp;<span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span></h5>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          
          <div class="card-body">
            
<!-------------------------CONEDIDO (CAMPOS) ----------------------------------------------->

        <div class="row"><!-- /.ROWPADRE -->

        <!-- Campos del formulario de cobros -->
         <div class="col-sm-7 float-left"><!-- Panel Datos generales de la empresa -->
         <div class="card card-success">
         <div class="card-header">DATOS GENERALES.</div>
          <div class="card-body"><!-- Card-body -->
            <div class="row"><!-- /.ROW1 -->
            
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>NÚMERO DE TARJETA:</label>
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
                        <label>FECHA DE ÚLTIMO PAGO:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                    @if($detectorCobro=='0')
                                <input  type="text" value="{{ $calificaciones->fecha_calificacion }}" disabled  name="ultimo_cobro" id="ultimo_cobro" class="form-control" required >
                    @else
                                <input  type="text" value="{{ $ultimo_cobro->fecha_pago }}" disabled id="ultimo_cobro" name="ultimo_cobro" class="form-control text-success" required >
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
                      <div class="form-group">  
                        <input type="text"  value="{{ $empresa->nombre_giro }}" name="giro_comercial" disabled id="giroc_comercial" class="form-control" required >
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
               <div class="col-md-6">
                  <div class="form-group">
                        <input type="text" name="tasa_interes" id="tasa_interes" class="form-control" >
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>FECHA DEL INTERÉS MORATORIO:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input type="text" value="{{ $date}}" name="fecha_interes_moratorio" id="fecha_interes_moratorio" class="form-control" disabled >
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>CANTIDAD DE MESES A PAGAR:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input type="text" disabled  name="cant_meses" id="cant_meses" class="form-control" >
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
                        <h5>
                          Periodo del: 
                          @if($detectorNull=='0')
                          <label class="text-success"> {{ $calificaciones->fecha_calificacion }} </label> 
                           @else
                             <label class="badge badge-info">{{ $ultimo_cobro->fecha_pago }} </label>
                           @endif 
                          &nbsp; al &nbsp;<label class="badge badge-success" name="hasta" id="hasta"></label>
                        </h3>
                  </div>
              <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
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
                            <td class="table-light">$297.18</td>
                          </tr>

                          <tr class="table-success">
                            <td>IMPUESTO</td>
                            <td>11804</td>
                            <td><h6 name="impuestos_imp" id="impuestos_imp"></h6></td>
                          </tr>

                          <tr class="table-light">
                            <td>INTERESES MORATORIOS</td>
                            <td>15302</td>
                            <td>$79.83</td>
                          </tr>

                          <tr class="table-success">
                            <td>MULTA</td>
                            <td>15313</td>
                            <td>$29.64</td>
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
                            <td><label>$<label name="FondoF_imp" id="FondoF_imp"> </label></label></td>
                            <td><label name="totalPago_imp" id="totalPago_imp"></label><label</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                    </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div><!-- ROW FILA3 -->        

            </div><!-- /.Panel Tarifas -->
 
  <!-- Finaliza campos del formulario de calificación -->


<!-------------------------FINALIZA CONTEDIDO (CAMPOS) ----------------------------------------------->


            <!-- Fin /.col -->
            </div>
          <!-- /.row -->
          </div>
        </div><!-- /.ROWPADRE -->
       </div>

        <!-- /.card-footer -->
            <div class="card-footer">
            <button type="button" class="btn btn-success float-right" onclick="GenerarCobro()"><i class="fas fa-envelope-open-text"></i>
            &nbsp;Generar Cobro&nbsp;</button>
            <button type="button" class="btn btn-default" onclick="VerEmpresa({{$empresa->id}} )">Volver</button>
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

