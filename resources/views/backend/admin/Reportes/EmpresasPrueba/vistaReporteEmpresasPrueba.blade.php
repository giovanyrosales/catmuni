@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop


<div class="content-wrapper" id="divcc" style="display: none">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">

        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info"></i> Generar Reportes Empresas Prueba</h5>
                        <div class="card" style="height: 69px;">
                            <form class="form-horizontal">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="generarPdfEmpresasPrueba();">
                                                <i class="fas fa-file-pdf"></i> Generar PDF
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="content" id="divcontenedor">
        <div class="container-fluid">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-city"></i> &nbsp;Empresas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                      </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">

                            <table class="table" id="tblEmpresas" style="border: 100px;" data-toggle="table">
                                <thead >
                                    <tr>  
                                        <th style='text-align: center; font-size:13px;'>NOMBRE EMPRESA</th>
                                        <th style='text-align: center; font-size:13px;'>CATEGORÍA</th>
                                        <th style='text-align: center; font-size:13px;'>CONTRIBUYENTE</th>
                                        <th style='text-align: center; font-size:13px;'>NUM. TARJETA</th>
                                        <th style='text-align: center; font-size:13px;'>MATRICULA</th>
                                        <th style='text-align: center; font-size:13px;'>GIRO</th>
                                        <th style='text-align: center; font-size:13px;'>RUBRO</th>
                                        <th style='text-align: center; font-size:13px;'>ESTADO</th>
                                        <th style='text-align: center; font-size:13px;'>INICIO OPERACIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datoEmpresas as $dato)
                                <tr>
                                    <td style='font-size:11px; text-align: center'> {{ $dato->empresa }} </td>
                                    <td style='font-size:11px; text-align: center'> {{ $dato->categoria }} </td>
                                    <td style='font-size:11px; text-align: center'> {{ $dato->apellido }}, {{ $dato->nombre}} </td>
                                    <td align="center"><span class="badge badge-pill badge-dark">{{$dato->num_tarjeta}}</span></td>
                                    @if ($dato->matricula == "SI")
                                    <td align="center"> <span class="badge bg-info">{{ $dato->matricula }}</span></td>
                                    @elseif ($dato->matricula == "N/A")
                                    <td align="center"> <span class="badge bg-secondary">{{ $dato->matricula }}</span></td>
                                    @else
                                    <td align="center"> <span class="badge bg-black">{{ $dato->matricula }}</span></td>
                                    @endif
                                    <td style='font-size:11px; text-align: center'> {{ $dato->nombre_giro }} </td>
                                    <td style='font-size:11px; text-align: center'> {{ $dato->rubro }} </td>
                                    @if($dato->estado == 'Activo')
                                    <td align="center"> <span class="badge bg-success">Activo</span></td>
                                    @elseif($dato->estado == 'Cerrado')
                                    <td align="center"> <span class="badge bg-danger">Cerrado</span></td>
                                    @endif
                                    <td style='font-size:11px; text-align: center'> {{ $dato->inicio_operaciones }} </td>
                                </tr>
                            @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>


@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>

    <script>
        var tablaEmpresasPrueba = null;
        $(document).ready(function() {
            document.getElementById("divcc").style.display = "block";

            $("#tblEmpresas").DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "responsive": true, "lengthChange": false, "autoWidth": false,
            });

        });

    </script>

    <script>

        function generarPdfEmpresasPrueba(){
            window.open("{{ URL::to('admin/pdf/reporte/empresas/prueba') }}");
        }

    </script>


@endsection
