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
<!-- Función para calcular el periodo de pago 

<script>
function periodo(valor){
/*Declaramos variables */
var fechaPagara=valor.value;

/* Muestro valor en label */
document.getElementById('hasta').innerHTML= fechaPagara ;
}

</script>--------------------------------------------------------->

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
                        <label>Fecha de último pago:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                    @if($detectorCobro=='0')
                                <input  type="text" disabled  name="ultimo_cobro" id="ultimo_cobro" class="form-control" required >
                    @else
                                <input  type="text" value="{{ $ultimo_cobro->fecha_pago }}" disabled id="ultimo_cobro" name="ultimo_cobro" class="form-control text-success" required >
                    @endif
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
               <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Fecha hasta donde pagará:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input  type="date"  onchange="CantMeses(this);" class="form-control text-success" name="fecha_hasta_donde_pagara" id="fecha_hasta_donde_pagara" class="form-control" required >   
                  </div>
               </div><!-- /.col-md-6 -->
              <!-- /.form-group -->
              <!-- /.form-group -->
                <div class="col-md-6">
                  <div class="form-group">
                        <label>Giro Comercial:</label>
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
                        <label>Tasa de interes:</label>
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
                        <label>Fecha del interes moratorio:</label>
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
                        <label>Cantidad de meses a pagar:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input type="text" disabled name="cant_meses" id="cant_meses" class="form-control" >
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
                          <label class="text-success"> 00/00/0000 </label>
                           @else
                             <label class="badge badge-info">{{ $ultimo_cobro->fecha_pago }} </label>
                           @endif 
                          &nbsp; al &nbsp;<label class="badge badge-success" name="hasta" id="hasta"></label>
                        </h3>
                  </div>
              <!-- /.form-group -->
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

function CantMeses(valor){
  
/*Declaramos variables */
var ultimo_cobro=(document.getElementById('ultimo_cobro').value);

var fechaPagara=valor.value;

var formData = new FormData();

formData.append('fechaPagara', fechaPagara);
formData.append('ultimo_cobro', ultimo_cobro);

axios.post('/admin/empresas/fechapagara', formData, {
        })
            .then((response) => {
              console.log(response);
                closeLoading();
                if(response.data.success !=1){
                  toastr.error('La fecha selecionada no puede ser menor a la del ultimo pago');
                  document.getElementById('hasta').innerHTML= '';
                  document.getElementById('cant_meses').value='';
                } 
                if(response.data.success === 1){
                  document.getElementById('hasta').innerHTML= fechaPagara;
                  document.getElementById('cant_meses').value=response.data.cantidadMeses;
                }  
            })
            .catch((error) => {
                toastr.error('Error');
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

