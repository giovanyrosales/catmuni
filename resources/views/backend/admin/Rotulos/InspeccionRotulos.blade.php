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
                            <li class="breadcrumb-item active">Inspección rótulo</li>
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
          <h5 class="modal-title">Realizar inspección a rótulo <span class="badge badge-warning">&nbsp; {{$rotulo->nom_rotulo}}&nbsp;</span>&nbsp;</h5>

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
            <div class="card-header text-info"><label>I.DATOS DE LA INSPECCIÓN</label></div>
                <div class="card-body"><!-- Card-body -->
        
            <div class="row"><!-- /.ROW1 -->

             <!-- /.form-group -->
            <div class="col-md-3">
                  <div class="form-group">
                        <label>FECHA DE INSPECCIÓN:</label>
                  </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date" value=" " name="fecha_inspeccion"  id="fecha_inspeccion" class="form-control" required >
                    <input type="hidden" name="estado_inspeccion" id="estado_inspeccion" class="form-control" value="realizado">
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->

            <div class="col-md-3">
                <div class="form-group">
                    <label>HORA:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="time" onchange="cambioSelect()" value=" " name="hora_inspeccion"  id="hora_inspeccion" class="form-control" required >
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->
           
            <!-- /.form-group -->
            <div class="col-md-3">
                <div class="form-group">
                    <label>NOMBRE DEL RÓTULO:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Nombre de Rótulo -->
            <div class="col-md-3">
                <div class="form-group">  
                   <input type="text"  value="{{$rotulo->nom_rotulo}}" name="nom_rotulo" disabled id="nom_rotulo" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Nombre del Rótulo-->
            <!-- /.form-group -->
   
             <!-- /.form-group -->
            <div class="col-md-3">
                <div class="form-group">
                    <label>ACTIVIDAD ECONÓMICA:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Actividad Económica -->
            <div class="col-md-3">
                <div class="form-group">  
                   <input type="text"  value="{{$rotulo->actividad_economica}}" name="actividad_economica" disabled id="actividad_economica" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Actividad Económica-->
            <!-- /.form-group -->

            <!-- /.form-group -->
            <div class="col-md-3">
                <div class="form-group">
                    <label>FECHA DE APERTURA:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha Apertura -->
            <div class="col-md-3">
                <div class="form-group">  
                   <input type="date"  value="{{$rotulo->fecha_apertura}}" name="fecha_apertura" disabled id="fecha_apertura" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Fecha Apertura-->
            <!-- /.form-group -->

            <!-- /.form-group -->
          
            <div class="col-md-3" >
                <div class="form-group">
                    <label id="labelcontri">REPRESENTANTE LEGAL:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Representante Legal -->
            <div class="col-md-3">
                <div class="form-group" id="representante">  
                   <input type="text"  value="{{$contri}}" name="contribuyente" disabled id="contribuyente" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Representante Legal-->
            <!-- /.form-group -->
          
           
              <div class="col-md-3">
                <div class="form-group"  >
                    <label>EMPRESA:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-3">
                <div class="form-group">  
                   <input type="text"  value="{{$emp}}" name="empresa" disabled id="empresa" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

   
            <!-- /.form-group -->
             <div class="col-md-3">
                <div class="form-group">
                    <label>DIRECCIÓN:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Dirección -->
            <div class="col-md-3">
                <div class="form-group">  
                <textarea class="form-control" disabled id="direccion" rows="3">{{$rotulo->direccion}}</textarea>
                </div>
            </div>
            <!-- Finaliza Dirección-->
            <!-- /.form-group -->
            
                </div>
            </div>
        </div>

        <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
        <div class="card-header text-info"><label>II. DATOS GENERALES</label></div>
        <div class="card-body"><!-- Card-body -->
        <div class="row"><!-- /.ROW1 -->

        <div class="row">
            <!-- /.form-group -->
            <div class="col-md-6">
                <div class="form-group">
                    <p>En esta fecha se realizó la inspección para apertura de:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                   <input type="text"  value="{{$rotulo->nom_rotulo}}" disabled name="empresa" id="empresa" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

            <!-- /.form-group -->
            <div class="col-md-6">
                <div class="form-group">
                    <p>que es propiedad de:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                   <input type="text"  value="{{$emp}} - &nbsp;{{$contri}}" disabled name="empresa" id="empresa" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

             <!-- /.form-group -->
             <div class="col-md-6">
                <div class="form-group">
                    <p>el rótulo posee las siguiente medidas:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                <textarea class="form-control" disabled  id="medidas" rows="2">{{$rotulo->medidas}}</textarea>
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

            <div class="col-md-6">
                <div class="form-group">
                    <p>con un total de metros cuadrados de:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                <input type="text"  value="{{$rotulo->total_medidas}} m²" disabled name="total_medidas" id="total_medidas" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

            <div class="col-md-6">
                <div class="form-group">
                    <p>y caras:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                <input type="text"  value="{{$rotulo->total_caras}}" disabled name="total_caras" id="total_caras" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

            <!-- /.form-group -->
            <div class="col-md-12">
                <div class="form-group">
                    <p>por lo que se procede a realizar la inscripción y se anexa copia de Documentación Personal del Representante Legal</p>
                </div>
            </div><!-- /.col-md-6 -->

            <!-- /.form-group -->
            <div class="col-md-6">
                <div class="form-group">
                    <p>Coordenadas Geodésicas:</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                   <input type="text"  value="" name="coordenadas" id="coordenadas" class="form-control" required >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->

             <!-- /.form-group -->
            <div class="col-md-6">
                <div class="form-group">
                    <p>se anexa foto del rótulo</p>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Empresa -->
            <div class="col-md-6">
                <div class="form-group">  
                   <input type="file" id="imagen" class="form-control" accept="image/jpeg, image/jpg, image/png " >
                </div>
            </div>       
            <!-- Finaliza Empresa-->
            <!-- /.form-group -->
            </div>
         </div>
        </div>
        </div>

            <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
            <div class="card-header text-info"><label>III. INSPECCIÓN REALIZADA POR</label></div>
            <div class="card-body"><!-- Card-body -->
            <div class="row"><!-- /.ROW1 -->
         
            <!-- Finaliza Inspección-->
            <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label>Nombre:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Dirección -->
            <div class="col-md-11">
                <div class="form-group">  
                    <input type="text"  value="" name="nom_inspeccion" id="nom_inspeccion" class="form-control" required >
                </div>
            </div>
            <!-- Finaliza Inspección-->    
            </div>
        <div class="row">
              <!-- Finaliza Inspección-->
              <div class="col-md-6">
                <div class="form-group">
                    <label>Cargo:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Dirección -->
            <div class="col-md-10">
                <div class="form-group">  
                    <input type="text"  value="" name="cargo_inspeccion" id="cargo_inspeccion" class="form-control" required >
                </div>
            </div>
        </div>

        <div class="row">
              <!-- Finaliza Inspección-->
              <div class="col-md-8">
                <div class="form-group">
                    <label>Firma:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Dirección -->
            <div class="col-md-12">
                <div class="form-group">  
                    <input type="text"  value="" name="" id="" class="form-control" required >
                </div>
            </div>
        </div>
          
          </div>
            </div>
          </div>
        </div>

          <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="guardarInspeccion()">Guardar Inspección</button>
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
     
          document.getElementById("divcontenedor").style.display = "block";
           
         
        });

     
      
    </script>

    <script>

        function guardarInspeccion()
        {
            var id = {{$id}};
            var fecha_inspeccion = document.getElementById('fecha_inspeccion').value;
            var hora_inspeccion = document.getElementById('hora_inspeccion').value;
            var coordenadas = document.getElementById('coordenadas').value;
            var imagen = document.getElementById('imagen');
            var estado_inspeccion = document.getElementById('estado_inspeccion').value;
            var nom_inspeccion = document.getElementById('nom_inspeccion').value;
            var cargo_inspeccion = document.getElementById('cargo_inspeccion').value;

            if(imagen.files && imagen.files[0]){ // si trae doc
                if (!imagen.files[0].type.match('image/jpeg|image/jpeg|image/png')){
                    toastr.error('formato de imagen permitido: .png .jpg .jpeg');
                    return;
                }
            }

            var contribuyente = document.getElementById('contribuyente').value;
            if ($contribuyente === '')
            {
                $('#representante').hide();
                $('#labelcontri').hide();
                
            }

            openLoading();
            var formData = new FormData();
                formData.append('id_rotulos', id);
                formData.append('fecha_inspeccion', fecha_inspeccion);
                formData.append('hora_inspeccion', hora_inspeccion);
                formData.append('coordenadas', coordenadas);
                formData.append('imagen', imagen.files[0]);
                formData.append('estado_inspeccion', estado_inspeccion);
                formData.append('nom_inspeccion', nom_inspeccion);
                formData.append('cargo_inspeccion', cargo_inspeccion);

            axios.post('/admin/Rotulos/guardar-inspeccion', formData,
        {
            })

            .then((response) => {
              console.log(response)
              closeLoading();
          if (response.data.success === 1)
          {
            Swal.fire({
                  position: 'top-end',
                  icon: 'success',
                  title: '¡Inspección de Rótulo registrada correctamente!',
                  showConfirmButton: false,
                  timer: 2000
                     })
            location.reload();
          }
          else
          {
          
            toastr.error('¡Error al guardar!');
          }
             })

            .catch((error) => {
            toastr.error('Error al registrar');
             closeLoading();
          });

        }

       
    </script>


@stop