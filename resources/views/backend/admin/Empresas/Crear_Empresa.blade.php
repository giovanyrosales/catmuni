@extends('backend.menus.superior')

@section('content-admin-css')
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
                            <li class="breadcrumb-item active">Agregar nueva empresa</li>
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
        <form class="form-horizontal" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Formulario de datos de la empresa.</h3>

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
                        <label>Nombre del negocio:</label>
                        <input type="text" name="nombre" id="nombre_negocio" class="form-control" required placeholder="Nombre del negocio" value="">
                        <input type="hidden" name="id" id="id" class="form-control" value="">
                      </div>
                <!-- /.form-group -->
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                          <label>NIT de la Empresa:</label>
                          <input type="text" name="nit_empresa" id="dui" class="form-control" required placeholder="0000-000000-000-0" value="">
                  </div></div>
                <div class="col-md-6">
                  <div class="form-group">
                          <label>N° de Tarjeta:</label>
                          <input type="text" name="tel" id="tel" required placeholder="0000" class="form-control" value="">
                </div>
                </div>
                </div>
                <!-- /.form-group -->
                <div class="col-md-14">
                <div class="form-group">
                    <label>Dirección:</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Dirección de la empresa"  value="">
                  </div> </div>   
                <!-- /.form-group -->

           <!-- Asignar-->      
            <!-- /.form-group -->
                <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                          <label>Asignar representante legal:</label>
                          <input type="text" name="contribuyente" id="contribuyente" required placeholder="Contribuyente" class="form-control" value="">
                  </div></div>
                <div class="col-md-6">
       

                          <button type="button" onclick="abrirModalAgregar()" class="btn btn-success float-right" style="margin-top:30px;">Asignar representante</button>
   
                </div>
                </div>

           <!--Final Asignar-->  
              </div>
              <!-- /.col -->
              <div class="col-md-5">
              <div class="form-group">
                        <label>Tipo de Comeciante:</label>
                        <input type="text" name="tipo_comerciante" id="apellido" class="form-control" required placeholder="Tipo de Comerciante" value="">
                      </div>
                <!-- /.form-group -->
                <div class="form-group">
                <label>Inicio de Operaciones:</label>
                          <input type="date" name="inicio_operaciones" id="inicio_operaciones" required class="form-control" value="">
                      </div>
                <!-- /.form-group -->  
                <div class="col-md-6">
                    <label>Teléfono:</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" placeholder="7777-7777"  value="">
                  </div>
              </div>
              <!-- /.form-group -->  
            <!-- /.col -->
            </div>
          <!-- /.row -->
          </div>
         <!-- /.card-body -->
         <div class="card-footer">
                  <button type="button" class="btn btn-success float-right" onclick="">Guardar</button>
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">Cancelar</button>
                </div>
         <!-- /.card-footer -->
         </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
<!-- Finaliza Formulario Crear Empresa-->




<!-- modal asignar empresa -->
<div class="modal fade" id="modalAgregar">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Asignar representante legal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formulario">
              <div class="card-body">
                <div class="row">  
                  <div class="col-md-6"> 
                    <div class="form-group">
                      <label>Propietarios registrados:</label>
                      <div class="col-12">
                        <!-- Cambie el nombre del select para hacerlo live search itemName = mexamen -->
                        <select class="itemName form-control" id="mdescripcion"  name="itemName"></select>
                      </div>
                    </div>
                    
                  </div>
                </div> 
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="add" >Agregar</button>
          </div>
          
        </div>        
      </div>      
    </div>

<!--Finaliza modal asignar empresa -->


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


@endsection