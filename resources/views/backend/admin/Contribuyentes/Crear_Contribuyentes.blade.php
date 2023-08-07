@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/responsive.bootstrap4.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons.bootstrap4.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" type="text/css" rel="stylesheet" />
@stop

<!-- Formulario-->

<div style="display: none; padding-bottom:30px;" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Agregar nuevo Contribuyente</li>
                        </ol>
                    </div><!-- /.col -->
            </div>
        </div>
    </section>

<!-- finaliza content-wrapper-->

<!-- Inicia Formulario Contribuyente-->
<section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <form class="form-horizontal" id="form1">
            <div class="card card-navy">
              <div class="card-header">
                <h3 class="card-title">Formulario de datos del propietario.</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
              </div>
                    <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                                <label>Nombre o razón social:</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del propietario o razón social">
                                <input type="hidden" name="id" id="id" class="form-control" >
                      </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Apellido:</label>
                            <input type="text" name="apellido" id="apellido" class="form-control" placeholder="Apellido del Contribuyente" >
                        </div>
                    </div>
                </div>
                <!-- /.form-group -->
                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                              <label>NIT (Opcional):</label>
                              <input type="number" name="nit" id="nit" class="form-control" placeholder="0000-000000-000-0" >
                      </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                              <label>DUI o Pasaporte:</label>
                              <input type="number" name="dui" id="dui" placeholder="00000000-0" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- /.form-group -->
                        <div class="form-group">
                            <label>Registro de Contribuyente:</label>
                            <input type="number" name="registro_comerciante" id="registro_comerciante" class="form-control" placeholder="0000000"  >
                        </div>
                    </div>
                </div>
                  <div class="row">
                      <div class="col-md-6">
                        <!-- /.form-group -->
                        <div class="form-group">
                            <label>Dirección:</label>
                            <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Dirección de la empresa"  >
                        </div>
                      </div>
                      <div class="col-md-4">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Correo Electrónico:</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Correo@dominio.com"  >
                            </div>
                      </div>
                  </div>

                <!-- /.form-group -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                              <label>Teléfono Celular:</label>
                              <input type="number" name="telefono" id="telefono" class="form-control"  placeholder="7777-7777">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                              <label>Teléfono Fijo (Opcional):</label>
                              <input type="number" name="fax" id="fax"  placeholder="2222-2222" class="form-control" >
                        </div>
                    </div>

                </div>
              </div>
         <!-- /.card-body -->
                        <div class="card-footer">
                          <button type="button" class="btn btn-Primary float-right" onclick="nuevo()">
                          <i class="fas fa-save"></i> &nbsp;Guardar </button>
                          <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">
                          <i class="fas fa-times-circle"></i>&nbsp;Cancelar</button>
                        </div>
         <!-- /.card-footer -->
         </div>
          <!-- /.card -->
        </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
<!-- Finaliza Formulario Contribuyente-->





<!--Final del formulario -->

@extends('backend.menus.footerjs')
@section('archivos-js')

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

<script type="text/javascript">
      function nuevo()
      {
        var nombre = document.getElementById('nombre').value;
        var apellido = document.getElementById('apellido').value;
        var direccion = document.getElementById('direccion').value;
        var dui = document.getElementById('dui').value;
        var nit = document.getElementById('nit').value;
        var registro_comerciante = document.getElementById('registro_comerciante').value;
        var telefono = document.getElementById('telefono').value;
        var email = document.getElementById('email').value;
        var fax = document.getElementById('fax').value;

//validaciones

       if (nombre === '')
       {
        toastr.error('Nombre es requerido');
          return;
       }
       if(nombre.length > 20) {
           toastr.error('máximo 20 caracteres para nombre');
           return;
       }

       openLoading();
       var formData = new FormData();
       formData.append('nombre', nombre);
       formData.append('apellido', apellido);
       formData.append('direccion', direccion);
       formData.append('dui', dui);
       formData.append('nit', nit);
       formData.append('registro_comerciante', registro_comerciante);
       formData.append('telefono', telefono);
       formData.append('email', email);
       formData.append('fax', fax);

       axios.post(url+'/Contribuyentes/Crear_Contribuyentes', formData, {
            })

        .then((response) => {
          closeLoading();
          if(response.data.success === 0){
                    toastr.error(response.data.message);

                }
          if (response.data.success === 1)
          {

            Swal.fire({
                          icon: 'success',
                          title: '¡Contribuyente registrado correctamente!',
                          showConfirmButton: true,
            }).then((result) => {
                        if (result.isConfirmed) {

                          location.reload();
                        }
                      });

          }
          else
          {
            Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: '¡Error al registrar contribuyente!',
                          showConfirmButton: true,
                        }).then((result) => {
                        if (result.isConfirmed)
                        {
                          closeLoading();
                        }
                      });
          }
        })
           .catch((error) => {
            Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Error al registrar!',
                        })
               closeLoading();
          });
      }

</script>

@stop
