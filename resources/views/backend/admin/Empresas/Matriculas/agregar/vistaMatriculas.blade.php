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

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
    .avatar {
        vertical-align: middle;
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
</style>


<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Vista matrículas</li>
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

        <form class="form-horizontal" id="formulario-GenerarRecalificacion">
        @csrf

        <div class="card card-green">
          <div class="card-header card-header-success">
            <h5 class="card-category-">Vista detallada de la matrícula <span class="badge badge-light">&nbsp; {{$matriculas->tipo_matricula}}&nbsp;</span> de la empresa <span class="badge badge-warning"> {{$matriculas->empresa}}&nbsp; </span>&nbsp; </h5>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          
          <div class="card-body">
            
<!-------------------------CONEDIDO (CAMPOS) ----------------------------------------------->
<div class="row"><!-- Menu -->
        <div class="col-md-4 col-sm-8">
                        <a href="#" onclick="CrearInspeccion( )" >
                            <div class="widget stats-widget">
                              <div class="widget-body clearfix bg-info">
                                  <div class="pull-left">
                                      <h3 class="widget-title text-white">Realizar Inspección</h3>
                                  </div>
                                  <span class="pull-right big-icon watermark"><i class="fas fa-edit"></i>&nbsp;<i class="fas fa-star-half"></i></span>
                              </div>
                          </div><!-- .widget -->
                          </a>
        </div>

</div><!-- /.Menu -->

 <!-- Cuadro para datos del rótulo inicia aquí ----------------------------------------------> 
    <!-- seccion frame -->
        <section class="content">
            <div class="card card">
              <div class="card-header">
                  <h3 class="card-title"><b>Reporte datos de la matrícula</b></h3>
                  <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                  </div>
              </div>
            <div class="card-body">
    <!-- sección cargar datos rótulos -->
            <!--Start third-->
                <table class="table table-hover table-striped">
                  <form id="formulario-show">
                    <tbody>
                    
                      <tr>
                        <th>Matrícula</th>
                        <td>{{$matriculas->tipo_matricula}}</td>
                        
                      </tr>
                      <tr>
                        <th>Empresa</th>
                        <td>{{$matriculas->empresa}}</td>
                      </tr>
                      <tr>
                        <th>Cantidad</th>
                        <td>{{$matriculas->cantidad}}</td>
                      </tr>
                      <xtr>
                        <th>Monto total</th>
                        <td>${{$matriculas->monto}} </td>
                      </tr>
                      <tr>
                        <th>Contribuyente</th>
                        <td>{{$empresa->contribuyente}}&nbsp;{{$empresa->apellido}}</td>
                      </tr>
                      
                    </tbody>
                  </form>
                  </table>
        </div> <!--end third-->
                    <!-- /.card-footer -->
                    <div class="card-footer">
                       <button type="button" class="btn btn-success  float-right" onclick=""><i class="fa fa-print"></i>&nbsp;Imprimir</button>
                    </div>
            <!-- /.card-footer -->
     </section>
 <!-- Termina sección cargar datos rótulo -->      


<!-------------------------FINALIZA CONTEDIDO (CAMPOS) ----------------------------------------------->


         </div> <!-- /.card-body -->
            <!-- /.card-footer -->
                <div class="card-footer">
                  <button type="button" class="btn btn-default" onclick="Volver()" ><i class="fas fa-chevron-circle-left"></i> &nbsp;Volver</button> 
                </div>
            <!-- /.card-footer -->
        </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>

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

    function recargar()
    {
     var ruta = "{{ url('/admin/Rotulos/tabla') }}";
     $('#tablaDatatable').load(ruta);
    }
    function Volver(id){
      var id={{$empresa->id}}
                     window.location.href="{{ url('/admin/matriculas_detalle/index') }}/"+id;
   }

    function CrearInspeccion(id)
    {
      openLoading();
      window.location.href="{{ url('/admin/Rotulos/inspeccion') }}/"+id;
    }
    
    function informacionCierre(id)
    {
      
            openLoading();
            document.getElementById("formulario-CierreRotulo").reset();

            axios.post('/admin/Rotulos/vista/inf-cierre',{
                'id': id
            })
            .then((response) => {
              console.log(response);
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalCierreRotulos').modal('show');
                        
                        $('#fecha_cierre').val(response.data.rotulos.fecha_cierre);
                        $('#select-estado-cierre').val(response.data.rotulos.estado);
                       
                        document.getElementById("select-contribuyente-traspaso").options.length = 0;

                        $.each(response.data.contribuyente, function( key, val ){
                            if(response.data.idcont == val.id){
                                $('#select-contribuyente-traspaso').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'&nbsp;'+val.apellido+'</option>');
                            }else{
                                $('#select-contribuyente-traspaso').append('<option value="' +val.id +'">'+val.nombre+'&nbsp;'+val.apellido+'</option>');
                            }
                        });

                      }else{
                        toastr.error('Información no encontrada');
                    }

                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
    
    }
    </script>


    <script>

    function VerListaRotulo()
    {

      openLoading();
      window.location.href="{{ url('/admin/Rotulos/Listar') }}/";

    }

    function guardarCierre()
    {
      //Llamar la variable id desde el controlador
  
      var estado = document.getElementById('select-estado-cierre').value;
      var fecha_cierre = document.getElementById('fecha_cierre').value;
    
      if(estado === '')
      {
          toastr.error('El estado requerido');
          return;
      }

        openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('estado', estado);
            formData.append('fecha_cierre', fecha_cierre);

            axios.post('/admin/Rotulos/vista/cierre', formData, {
            })
            .then((response) => {          
                closeLoading();

                if (response.data.success === 1) 
                   
                   {
                    Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Datos actualizados correctamente!',
                          showConfirmButton: false,
                          timer: 3000
                        })
                       $('#modalCierreRotulos').modal('hide');
                       location.reload();
                   }
                   else 
                   {
                       toastMensaje('Error al actualizar');
                       $('#modalCierreRotulos').modal('hide');
                              recargar();
                   }
             
            })
            .catch((error) => {
                toastr.error('Error al actualizar empresa');
                closeLoading();
            });
    }
    
    function guardarTraspaso()
    {
     

      var contribuyente = document.getElementById('select-contribuyente-traspaso').value;

      if(contribuyente === ''){
            toastr.error('El dato contribuyente es requerido');
            return;
        }

        openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('contribuyente', contribuyente);

            axios.post('/admin/Rotulos/vista/traspaso', formData, {
            })
            .then((response) => {          
                closeLoading();

                if (response.data.success === 1) 
                   
                   {
                       toastr.success('¡Propietario actualizado!');
                       $('#modalCierresTraspasos').modal('hide');
                       location.reload();
                   }
                   else 
                   {
                       toastMensaje('Error al actualizar');
                       $('#modalCierresTraspasos').modal('hide');
                              recargar();
                   }
             
            })
            .catch((error) => {
                toastr.error('Error al actualizar empresa');
                closeLoading();
            });
    }

    function InspeccionRealizada()
    {
      toast.success('La inspeccion ya fue realizada');
      return;
    }

    
    
    </script>
@stop