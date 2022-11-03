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

<script>
  function f1()
  {
    $('#btn_imprimirCalificacion').hide();
  }

  window.onload = f1;
</script>



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
     
          <h5 class="modal-title">Calificación rótulos <span class="badge badge-warning">&nbsp;{{$rotulos->nom_empresa}}&nbsp;</span>&nbsp;</h5>

   
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
                    <input type="text"  value="{{$rotulos->num_ficha}} " disabled id="nFicha" class="form-control" required >                    
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
                    <input type="text"  value="{{$rotulos->fecha_apertura}}" disabled id="hora_inspeccion" class="form-control" required >
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
                    <input type="text"  value="{{$rotulos->nom_empresa}} " disabled id="hora_inspeccion" class="form-control" required >
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
                    <input type="text"  value="{{$rotulos->contribuyente}} {{$rotulos->apellido}}" disabled id="contribuyente" class="form-control" required >
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
                    <input type="text"  value="{{$rotulos->dire_empresa }}" disabled id="empresa" class="form-control" required >
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
            <h5 class="modal-title">Registrar calificación &nbsp;<span class="badge badge-warning">&nbsp;&nbsp;</span></h5>
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
                        <input  type="text" class="form-control text-success" disabled value="{{$rotulos->num_ficha}}"  id="nFicha" class="form-control" required >
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
                        <input type="text"  value="{{$rotulos->nom_empresa}}" name="nombre" disabled id="nom_empresa" class="form-control" required >
                        <input type="text" hidden value="{{$rotulos->id_contribuyente}}"  disabled id="id_contribuyente" class="form-control" required >
                        <input type="text" hidden value="{{$rotulos->id_rotulos_detalle}}"  disabled id="id_rotulos_detalle" class="form-control" required >
                        <input type="text" hidden value="{{$rotulos->num_ficha}}"  disabled id="nFicha" class="form-control" required >
                        
                     
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
                        <input  type="date" class="form-control text-success" disabled value="{{$rotulos->fecha_apertura}}" name="created_at" id="created_at" class="form-control" required >
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
                        <input type="text" disabled value="{{$rotulos->contribuyente}} {{$rotulos->apellido}}" name="contribuyente" id="contribuyente" class="form-control" >
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
           <div class="card-header text-success"><label>II. BUSES</label></div>
            <div class="card-body">

               <!-- /.form-group -->
               <div class="col-md-12">
                  <div class="form-group">
                        
                  <table border="1" width:760px;>
                        <tr>
                          <th align="center" scope="col">RÓTULOS</th>
                          <th align="center" scope="col">TOTAL MEDIDAS</th>
                          <th align="center" scope="col">CARAS</th>
                          <th align="center" scope="col">TARIFA</th> 
                          <th align="center" scope="col">EJERCICIO</th>
                        </tr>

                        <tr>
                  
                    @foreach($rotulosEspecificos as $dato)
                        <td style="width: 150px;" align="center">{{$dato->nombre}}</td>
                        <td style="width: 150px;" align="center">{{$dato->total_medidas}}</td>
                        <td style="width: 150px;" align="center">{{$dato->caras}}</td>
                        <td style="width: 150px;" align="center">${{$dato->tarifa}}</td>
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
                          <td align="center" >${{$suma_tarifa}}<label id= "tarifa_mensual"></label> <input type="hidden" id="tarifa_mensual"></td>                         
                          <td align="center">${{ $tarifaaño}}</td>
                        </tr>                      
                          
                        <tr>
                          <td rowspan="2"></td>
                          <td colspan="2">Fondo Fiestas Patronales 5%</td>
                          <td align="center">${{$tarifa_total}} </td>
                          <td align="center">${{$tarifat_sinF}}</td>
                        </tr>

                        <tr>
                          <td colspan="2">TOTAL IMPUESTO</td>
                          <td align="center" ><strong>${{$tarifa_total}}</strong><label id= "total_impuesto"></label> <input type="hidden"  id="total_impuesto"></td>
                          <td align="center"><strong>${{$tarifa_total_año}}</strong></td>
                        </tr>
                        
                      </table>
                      
                      </div> <!-- /.ROW1 -->
                  </div> <!-- /.card-body -->
              </div> <!-- /.card-header text-success -->
          </div><!-- /.Panel VI. ROTULOS -->
 
  <!-- Finaliza campos del formulario de calificación -->


         <!-- /.card-body -->
          <div class="card-footer">

          <button type="button" class="btn btn-secondary" onclick="imprimirCalificacion({{$rotulos->id_rotulos_detalle}})" id="btn_imprimirCalificacion">
                <i class="fa fa-print"></i>&nbsp;Calificación&nbsp;
          </button>

          <button type="button" class="btn btn-success float-right" onclick="verificarCalificacion()"><i class="fas fa-edit">
              </i> &nbsp;Registrar Calificación&nbsp;
          </button>
              <br><br>

          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar
          </button>

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
            var id_rotulos_detalle={{$id_rotulos_detalle}};
            var ruta = "{{ url('/admin/rotulos_detalle/calificaciones/tablarotulo') }}/"+id_rotulos_detalle;
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
          var id_rotulos_detalle = document.getElementById('id_rotulos_detalle').value;       
          var estado_calificacion = document.getElementById('estado_calificacion').value;
          var fechacalificar = document.getElementById('fechacalificar').value;

        
          openLoading();
          var formData = new FormData();          
              formData.append('id_contribuyente', id_contribuyente);
              formData.append('id_rotulos_detalle', id_rotulos_detalle);  
              formData.append('ficha', ficha);           
              formData.append('estado_calificacion', estado_calificacion);
              formData.append('fechacalificar', fechacalificar);
            
          

        axios.post('/admin/rotulos_detalle/calificacion/guardar', formData, {
        })
            .then((response) => {
              console.log(response)
                closeLoading();
                if(response.data.success === 0)
                {
                    toastr.error(response.data.message);
                }
                if(response.data.success === 1)
                {
                    calificacion_registrada();
                }
              
            })
            .catch((error) => {
                  fallo('Error!', 'Error al registrar la calificación');                                    

            });
 

      }
    </script>

    <script> 

        function verificarCalificacion()
        {
            Swal.fire({
                title: '¿Desea realizar la calificación?',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Guardar'
            }).then((result) => {
                if (result.isConfirmed) {
                    nuevaCalificacion()
                }
            });
        } 

        function agregado()
        {
                Swal.fire({
                    title: '¿Esta seguro de registrar la calificación?',
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#28a745',
                    closeOnClickOutside: false,
                    allowOutsideClick: false,
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                    
                          
                        
                    }
                });
        }

        function fallo(titulo, mensaje)
        {
                Swal.fire({
                    title: titulo,
                    text: mensaje,
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                    location.reload;
                    }
                });
                
                
        }

        function calificacion_registrada()
        {
              Swal.fire({
              title: 'Calificación registrada correctamente',
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
                      $('#btn_imprimirCalificacion').show();
                  }
                
                        });
        }

        function imprimirCalificacion(id)
        {
            window.open("{{ URL::to('/admin/rotulos_detalle/reporte/calificacion/pdf') }}/" + id );
        }
        
    </script>

    @stop