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
                            <li class="breadcrumb-item active">Calificación Buses</li>
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
      @if($nom_empresa == '')
          <h5 class="modal-title">Realizar calificación a buses de <span class="badge badge-warning">&nbsp;{{$contribuyente}} {{$apellido}} &nbsp;</span>&nbsp;</h5>

      @else
          <h5 class="modal-title">Realizar calificación <span class="badge badge-warning">&nbsp;{{$nom_empresa}} &nbsp;</span>&nbsp;</h5>

      @endif
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

            <div class="col-md-3">
                <div class="form-group">
                    <label>NÚMERO DE FICHA:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="text"  value="{{$ficha}} " disabled id="nFicha" class="form-control" required >                    
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
                    <input type="text"  value="{{$fecha}} " disabled id="hora_inspeccion" class="form-control" required >
                    <input type="hidden" name="estado_calificacion" id="estado_calificacion" class="form-control" value="calificado">
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

            
             <!-- /.form-group -->
             <div class="col-md-3">
                  <div class="form-group">
                        <label>FECHA DE CALIFICACIÓN:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date" value="" name="fecha_calificacion"  id="fecha_calificacion" class="form-control" required >
                   
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
                    <input type="text"  value="{{$nom_empresa}} " disabled id="hora_inspeccion" class="form-control" required >
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
                    <input type="text"  value="{{$contribuyente}} {{$apellido}}" disabled id="contribuyente" class="form-control" required >
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
                    <input type="text"  value="" disabled id="empresa" class="form-control" required >
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

                  

              
          </div>
         
            </div>          
          </div>        
        </div>
     
          <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="calcularCalificacion()">Generar Calificación</button>
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">Cancelar</button>
          </div>

        </div>
       </div>
      </div>
    </div>

<!-- Modal Generar Calificación -->
    <div class="modal fade" id="modalCalificacion">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Registrar calificación a buses de &nbsp;<span class="badge badge-warning">&nbsp;{{$contribuyente}}{{$apellido}} &nbsp;</span></h5>
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
        
        <div class="card border-success mb-3"><!-- Panel Datos generales de la empresa -->
         <div class="card-header text-success"><label>I. DATOS GENERALES DEL BUS</label></div>
          <div class="card-body"><!-- Card-body -->
            <div class="row"><!-- /.ROW1 -->
            

              <div class="col-md-6">
                  <div class="form-group">
                        <label>Número de ficha:</label>
                  </div>
              </div><!-- /.col-md-6 -->
              <div class="col-md-6">
                  <div class="form-group">
                        <input  type="text" class="form-control text-success" disabled value="{{ $ficha }}"  id="nFicha" class="form-control" required >
                  </div>
              </div><!-- /.col-md-6 -->
               <!-- /.form-group -->

             <!-- /.form-group -->
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Nombre de la empresa:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                        <input type="text"  value="{{$nom_empresa}}" name="nombre" disabled id="nom_empresa" class="form-control" required >
                        <input type="text" hidden value="{{$contribuyentes->id}}"  disabled id="id_contribuyente" class="form-control" required >
                        <input type="text" hidden value="{{$buses->id}}"  disabled id="id_buses_detalle" class="form-control" required >
                        <input type="text" hidden value="{{$buses->nFicha}}"  disabled id="nFicha" class="form-control" required >
                        
                     
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
                        <input  type="date" class="form-control text-success" disabled value="{{ $fecha }}" name="created_at" id="created_at" class="form-control" required >
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
                        <input type="text" disabled value="{{$contribuyente}} {{$apellido}}" name="contribuyente" id="contribuyente" class="form-control" >
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->     
               
               <div class="col-md-6">
                  <div class="form-group">
                        <label>Fecha de calificación:</label>
                  </div>
               </div><!-- /.col-md-6 -->
               <div class="col-md-6">
                  <div class="form-group">
                  <input type="date" disabled name="fechacalificar" id="fechacalificar" class="form-control" >
                        <input type="hidden"  id="estado_calificacion" class="form-control" value="calificado">
                  </div>
               </div><!-- /.col-md-6 -->
               <!-- /.form-group -->
            </div> <!-- /.ROW1 -->

            </div> <!-- /.card-header text-success -->
            </div> <!-- /.Panel datos generales de la empresa -->
        
            
          <div class="card border-success mb-3" id="Div_Rotulos"><!-- PanelVI. BUSES -->
           <div class="card-header text-success"><label>VI. BUSES</label></div>
            <div class="card-body">

               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
                  <table border="1" width:760px;>
                        <tr>
                          <th align="center" scope="col">BUSES</th>
                          <th align="center" scope="col">PLACA</th>
                          <th align="center" scope="col">RUTA</th>
                          <th align="center" scope="col">TARIFA</th> 
                          <th align="center" scope="col">EJERCICIO</th>
                        </tr>

                        <tr>
                  
                        @foreach($calificacionB as $dato)

                        <td style="width: 150px;" align="center">{{$dato->nombre}}</td>
                        <td style="width: 150px;" align="center">{{$dato->placa}}</td>
                        <td style="width: 150px;" align="center">{{$dato->ruta}}</td>
                        <td style="width: 150px;" align="center">${{$Total}}</td>
                        <td style="width: 150px;" align="center">2022</td>

                        </tr>
                        @endforeach 
                        
                      
               
                        <tr>
                          <td> </td>
                          <td></td>
                          <td>IMPUESTO:</td>
                          <td align="center">MENSUAL</td>
                          <td align="center">ANUAL</td>
                        </tr>

                        <tr>
                   
                          <td align="center"></td>
                          <td></td>
                          <td> </td>                         
                          <td align="center" >${{$TotalT}}<label id= "tarifa_mensual"></label> <input type="hidden" id="tarifa_mensual"></td>                         
                          <td align="center">${{$TotalA}}</td>
                        </tr>                      
                          
                        <tr>
                          <td rowspan="2"></td>
                          <td colspan="2">Fondo Fiestas Patronales 5%</td>
                          <td align="center">${{$buses->monto_pagar}} </td>
                          <td align="center">${{$TotalAF}}</td>
                        </tr>

                        <tr>
                          <td colspan="2">TOTAL IMPUESTO</td>
                          <td align="center" ><strong>${{$buses->monto_pagar}}</strong><label id= "total_impuesto"></label> <input type="hidden"  id="total_impuesto"></td>
                          <td align="center"><strong>${{$TotalAF}}</strong></td>
                        </tr>
                        
                      </table>
                      
                      </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div> <!-- /.card-header text-success -->
          </div><!-- /.Panel VI. ROTULOS -->
 
  <!-- Finaliza campos del formulario de calificación -->


         <!-- /.card-body -->
         <div class="card-footer">
         <button type="button" class="btn btn-secondary" onclick="ImpimirCalificacion()"><i class="fa fa-print">
         </i>&nbsp; Impimir Calificación&nbsp;</button>
         <button type="button" class="btn btn-success float-right" onclick="nuevaCalificacion()"><i class="fas fa-edit">
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
            var ruta = "{{ url('/admin/buses/calificaciones/tablabus') }}/"+id;
            $('#tablaDatatable').load(ruta);
            
            document.getElementById("divcontenedor").style.display = "block";
         
        });


    function calcularCalificacion()
    {
   
      var fecha_calificacion = document.getElementById('fecha_calificacion').value;

      if (fecha_calificacion === '')
      {
        toastr.error('La fecha de la calificación es requerida');
        return;
      }

      document.getElementById('fechacalificar').value=fecha_calificacion;

        $('#modalCalificacion').modal('show');

      
    }
    

      function nuevaCalificacion()
      {
      
          var id_contribuyente = document.getElementById('id_contribuyente').value;
          var ficha = document.getElementById('nFicha').value;
          var id_buses_detalle = document.getElementById('id_buses_detalle').value;       
          var estado_calificacion = document.getElementById('estado_calificacion').value;
          var fechacalificar = document.getElementById('fechacalificar').value;

        
          openLoading();
          var formData = new FormData();
          
              formData.append('id_contribuyente', id_contribuyente);
              formData.append('id_buses_detalle', id_buses_detalle);  
              formData.append('ficha', ficha);           
              formData.append('estado_calificacion', estado_calificacion);
              formData.append('fechacalificar', fechacalificar);
            
          

        axios.post('/admin/buses/calificacion/nueva', formData, {
        })
            .then((response) => {
              console.log(response)
                closeLoading();
                if(response.data.success === 0){
                    toastr.error(response.data.message);
                }
                if(response.data.success === 1){
                  Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: '¡Calificación registrada correctamente!',
                                showConfirmButton: true,
                          
                              }).then((result) => {
                              if (result.isConfirmed) {
                                  $('#modalCalificacion').modal('hide');
                                  // window.location.href="{{ url('/admin/nuevo/empresa/listar') }}/";
                                  }
                              });
                  }
              
            })
            .catch((error) => {
              Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '¡Error al registrar la calificación!', 
                                showConfirmButton: true,
                              }).then((result) => {
                              if (result.isConfirmed) {
                                $('#modalCalificacion').modal('hide');
                                          closeLoading();
                                        }
                              });
            });
          


      }
    </script>

    @stop