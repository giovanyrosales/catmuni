@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    @stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="col-sm-12">
            <h1>&nbsp; </h1>
        </div>
        <br>
        <button type="button" onclick="modalAgregar()" class="btn btn-success btn-sm">
            <i class="fas fa-plus-square"></i> &nbsp;
            Agregar un período
        </button>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Dispensa e intereses</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                    <div class="col-auto  p-5 text-center" id="tabla_dispensas"></div>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalEditar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="far fa-plus-square"></i>&nbsp;Editar período de dispensa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-editar">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                    <!-- /.form-group -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Inicio del período:</label>
                                            <input type="date" name="inicio_periodo_editar" id="inicio_periodo_editar" required class="form-control" >
                                        </div>
                                    </div>
                                    <!-- /.form-group -->   
                                    <!-- /.form-group -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Finalización del período:</label>
                                            <input type="date" name="fin_periodo_editar" id="fin_periodo_editar" required class="form-control" >
                                        </div>
                                    </div>
                                    <!-- /.form-group -->  
                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Estado del período</label><br>
                                        <label class="switch" style="margin-top:10px">
                                            <input type="checkbox" id="toggle-editar">
                                            <div class="slider round">
                                                <span class="on">Activo</span>
                                                <span class="off">Finalizado</span>
                                            </div>
                                        </label>
                                      </div>
                                    </div>
                                    <!-- /.form-group -->  
                                </div>
                            </div>
                        </div>
                    
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                <button type="button" class="btn btn-success" onclick="editar_periodo_dispensa()"><i class="fas fa-plus-square"></i>&nbsp;Actualizar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAgregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="far fa-plus-square"></i>&nbsp;Nuevo período de dispensa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-nuevo">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                    <!-- /.form-group -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Inicio del período:</label>
                                            <input type="date" name="inicio_periodo" id="inicio_periodo" required class="form-control" >
                                        </div>
                                    </div>
                                    <!-- /.form-group -->   
                                    <!-- /.form-group -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Finalización del período:</label>
                                            <input type="date" name="fin_periodo" id="fin_periodo" required class="form-control" >
                                        </div>
                                    </div>
                                    <!-- /.form-group -->                            
                                </div>
                            </div>
                        </div>
                    
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                <button type="button" class="btn btn-success" onclick="nuevo_periodo_dispensa()"><i class="fas fa-plus-square"></i>&nbsp;Agregar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBorrar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="far fa-minus-square"></i>&nbsp;Borrar Permiso</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-borrar">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center;">
                                <p>Esta acción eliminara el período seleccionado.</p>
                                <div>
                                    <input type="hidden" id="idborrar">
                                </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                <button type="button" class="btn btn-danger" onclick="borrar()"><i class="fas fa-trash-alt"></i>&nbsp;Borrar</button>
            </div>
        </div>
    </div>
</div>





@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}" type="text/javascript"></script>

    <!-- incluir tabla -->
    <script type="text/javascript">
        $(document).ready(function(){

            var ruta = "{{ url('/admin/tabla/historico/dispensas') }}";
            $('#tabla_dispensas').load(ruta);

        });
    </script>

    <script>

    function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function nuevo_periodo_dispensa(){

            var inicio_periodo = document.getElementById('inicio_periodo').value;
            var fin_periodo = document.getElementById('fin_periodo').value;

            var formData = new FormData();
            formData.append('inicio_periodo', inicio_periodo);
            formData.append('fin_periodo', fin_periodo);

            axios.post(url+'/dispensa/nuevo/periodo', formData, {
            })
                .then((response) => {
                    closeLoading()
                    $('#modalAgregar').modal('hide');

                    if(response.data.success === 1){
                        toastMensaje('success', 'Nuevo período de dispensa agregado');
                        recargar();
                    }else if(response.data.success === 3){

                        modalMensaje('Denegado', 'Ya hay un período de dispensa activo, no se puede agregar uno nuevo');
                    }
                    else{
                        toastMensaje('error', 'Error al agregar período de dispensa');
                    }
                })
                .catch((error) => {
                    closeLoading()
                    toastMensaje('error', 'Error al agregar');
                });
            
        }


    function  recargar(){

        var ruta = "{{ url('/admin/tabla/historico/dispensas') }}";
            $('#tabla_dispensas').load(ruta);
    }

    function modalBorrar(id){
            $('#idborrar').val(id);
            $('#modalBorrar').modal('show');
        }

        function borrar(){
            openLoading()
            // se envia el ID del permiso
            var id_dispensa = document.getElementById('idborrar').value;


            var formData = new FormData();
            formData.append('id_dispensa', id_dispensa);

            axios.post(url+'/dispensa/borrar/periodo', formData, {
            })
                .then((response) => {
                    closeLoading()
                    $('#modalBorrar').modal('hide');

                    if(response.data.success === 1){
                        toastMensaje('success', 'Período de dispensa eliminado');
                        recargar();
                    }else{
                        toastMensaje('error', 'Error al eliminar el período seleccionado');
                    }
                })
                .catch((error) => {
                    closeLoading()
                    toastMensaje('error', 'Error al eliminar');
                });
        }

        function verInformacion(id){
        console.log(id);
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post(url+'/dispensa/infoperiodo',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditar').modal('show');
                        $('#inicio_periodo_editar').val(response.data.info.fecha_inicio_periodo);
                        $('#fin_periodo_editar').val(response.data.info.fecha_fin_periodo);

                   //     document.getElementById("rol-editar").options.length = 0;

                       if(response.data.info.activo === 0){
                           $("#toggle-editar").prop("checked", false);
                       }else{
                          $("#toggle-editar").prop("checked", true);
                        }

                    }else{
                        toastMensaje('error', 'Información no encontrado');
                    }

                })
                .catch((error) => {
                    closeLoading()
                    toastMensaje('error', 'Información no encontrado');
                });
        }


    function modalMensaje(titulo, mensaje){
            Swal.fire({
                title: titulo,
                text: mensaje,
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {

                }
            });
            
        }
        
    </script>




@stop
