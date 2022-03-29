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
                            <li class="breadcrumb-item active">Calificación Rótulos</li>
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

        <form class="form-horizontal" id="formulario-GenerarInspeccion">
        @csrf

        <div class="card card-success">
          <div class="card-header">
          <h5 class="modal-title">Realizar calificación a rótulo <span class="badge badge-warning">&nbsp; {{$rotulo->nom_rotulo}}&nbsp;</span>&nbsp;</h5>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          
          <div class="card-body">

<!-------------------------CONTENIDO (CAMPOS) ----------------------------------------------->


        <!-- Campos del formulario de inspección -->
        <div class="card border-success mb-3"><!-- Panel Datos generales de la empresa -->
            <div class="card-header text-info"><label>I.REGISTRO DE CUENTAS CORRIENTES</label></div>
                <div class="card-body"><!-- Card-body -->
        
            <div class="row"><!-- /.ROW1 -->

             <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>NÚMERO DE FICHA:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="text" value="{{$rotulo->num_tarjeta}} " name="num_tarjeta"  id="num_tarjeta" class="form-control" required >
                    <input type="hidden" name="estado_calificacion" id="estado_calificacion" class="form-control" value="calificado">
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

            <div class="col-md-3">
                <div class="form-group">
                    <label>FECHA DE APERTURA:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="text"  value="{{$rotulo->fecha_apertura}} " disabled id="hora_inspeccion" class="form-control" required >
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->
            </div>
                </div>
        </div>

          

        <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
        <div class="card-header text-info"><label>II. DATOS GENERALES DEL REPRESENTANTE</label></div>
        <div class="card-body"><!-- Card-body -->
        
        
        <div class="row"><!-- /.ROW1 -->
     
            <div class="col-md-2">
                <div class="form-group">
                    <label>NOMBRE EMPRESA:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-4">
                <div class="form-group">  
                    <input type="text"  value="{{$emp}} " disabled id="hora_inspeccion" class="form-control" required >
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->
           
            <div class="col-md-2">
                <div class="form-group">
                    <label>REPRESENTANTE:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-4">
                <div class="form-group">  
                    <input type="text"  value="{{$emp2}} " disabled id="contribuyente" class="form-control" required >
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

            <div class="col-md-2">
                <div class="form-group">
                    <label>DIRECCION:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-4">
                <div class="form-group">  
                    <input type="text"  value="{{$emp1}} " disabled id="empresa" class="form-control" required >
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->
           
           
            </div>
         </div>
        </div>
       

            <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
            <div class="card-header text-info"><label>III. DESCRIPCIÓN DE RÓTULO O VALLA PUBLICITARIAS</label></div>
            <div class="card-body"><!-- Card-body -->
            <div class="row"><!-- /.ROW1 -->
         
                <div class="col-md-12">
                <div id="tablaDatatable">

                
                <table class="table" id="matrizMatriculas" style="border: 80px" data-toggle="table">
                        <thead>
                           <tr>
                            <th style="width: 25%; text-align: center">Dirección</th>
                            <th style="width: 25%; text-align: center">Medidas </th>
                            <th style="width: 25%; text-align: center">Caras</th>
                            <th style="width: 15%; text-align: center">Tarifa</th>
                            <th style="width: 15%; text-align: center">Pago Mensual</th>
                            <th style="width: 15%; text-align: center">Opciones</th>                         
                           </tr>
                        </thead>
                        <tbody>
                            <td>
                            <input  id='direccion' onchange='' class='form-control' min='1' style='max-width: 250px' type='number' value=''/>
                            </td>

                        <td>
                        <input  id='medidas' onchange='' class='form-control' min='1' style='max-width: 250px' type='number' value=''/>
                        </td>

                        <td>
                        <input  id='caras' class='form-control'  min='1' style='max-width: 250px' type='text' value=''/>
                        </td>

                        <td>
                        <input  id='monto_tarifa' class='form-control'  min='1' style='max-width: 250px' type='text' value=''/>
                        </td>

                        <td>
                        <input  id='pago_mensual' class='form-control'  min='1' style='max-width: 250px' type='text' value=''/>
                        </td>

                        <td>
                        <button type='button' class='btn btn-block btn-success'  id="btnAdd" onclick='verificar()'>
                            <i class="far fa-plus-square"></i> 
                            &nbsp;Agregar
                        </button>
                        </td>

                        </tr>
                            </tbody>
                            </table>
                            </div>
                  
               <!-- Inicia Select Giro Comercial -->
                 
          
          </div>
            </div>
          </div>
        </div>




          <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="">Generar Calificación</button>
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">Cancelar</button>
          </div>

        </div>
       </div>
      </div>
    </div>
 
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
            var id={{$id}};
            var ruta = "{{ url('/admin/rotulos/calificaciones/tablarotulo') }}/"+id;
            $('#matrizMatriculas').load(ruta);
            
            document.getElementById("divcontenedor").style.display = "block";
         
        });


     
      
    </script>

    @stop