@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
</style>

<div class="content-wrapper" id="divcc" style="display: none">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">

        </div>
    </section>
    
    <section class="content" id="divcontenedor">
        <div class="container-fluid">
            <div class="card card">
                <div class="card-header" style="color:#FFFFFF; background:#7ECB5A">
                    <h3 class="card-title">Reporte de ingresos diarios</h3>
                </div>
                
                <div class="card-body">
                <section class="content">

                    <div class="container-fluid">

                        <div class="row">

                            <div class="col-12">
                            <div id="div_btn_cobros_pdf">
                                <button type="button" class="btn btn btn-sm" style="color:white; background:#7ECB5A" onclick="generarPdfCobrosDiarios();" id="btn_cobros_pdf">
                                    <i class="fas fa-file-pdf"></i> Generar PDF
                                </button>
                                <br><br>
                            </div>
                            <b>Fecha:</b> {{$FechaDelDia}}
                            <br><br>
                                <div class="card" id="tarjeta_tabla">
                                    <div class="card-body">
                                        <table id="tabla" class="table table-bordered table-striped">
                                            <thead>
                                                <tr style="font-size: 13px">
                                                <th style="width: 08%;">N° FICHA</th>
                                                <th style="width: 12%;">NEGOCIO</th>
                                                <th style="width: 15%;">CONTRIBUYENTE</th>
                                                <th style="width: 10%;">CODIGO</th>
                                                <th style="width: 15%;text-align:center">TOTAL PAGADO</th>
                                                <th style="width: 15%;">A PARTIR DE</th>
                                                <th style="width: 15%;">HASTA</th>
                                                <th style="width: 15%;text-align:center">VENTANILLA</th>
                                            </tr>
                                            </thead>
                                            <tbody style="font-size: 14px">
                                            @foreach($lista_cobros as $dato)
                                                <tr>
                                                    <td align="center"><span class="badge badge-pill badge-dark">{{$dato->nficha}}</span></td>
                                                    <td>{{$dato->negocio}}</td>
                                                    <td>{{$dato->contribuyente}}</td> 
                                                    <td>{{$dato->codigo}}</td>                                               
                                                    <td align="center"><span class="badge badge-success">$ {{$dato->cobro_por_empresa}}</span></td>
                                                    <td>{{$dato->apartir_de}}</td>
                                                    <td style="text-align: center;">{{$dato->hasta}}</td>
                                                    <td style="text-align: center;">{{$dato->nombre_user}}</td>
                                                </tr>
                                            @endforeach
                                            @foreach($lista_cobros_matriculas as $dato)
                                                <tr>
                                                    <td align="center"><span class="badge badge-pill badge-dark">{{$dato->nficha}}</span></td>
                                                    <td>{{$dato->negocio}}</td>
                                                    <td>{{$dato->contribuyente}}</td>
                                                    <td>{{$dato->codigo}}</td>                                                 
                                                    <td align="center"><span class="badge badge-success">$ {{$dato->cobro_por_empresa}}</span></td>
                                                    <td>{{$dato->apartir_de}}</td>
                                                    <td style="text-align: center;">{{$dato->hasta}}</td>
                                                    <td style="text-align: center;">{{$dato->nombre_user}}</td>
                                                </tr>
                                            @endforeach
                                            @foreach($lista_cobros_licencias as $dato)
                                                <tr>
                                                    <td align="center"><span class="badge badge-pill badge-dark">{{$dato->nficha}}</span></td>
                                                    <td>{{$dato->nombre}}</td>
                                                    <td>{{$dato->contribuyente}}</td>
                                                    <td>{{$dato->codigo}}</td>                                                 
                                                    <td align="center"><span class="badge badge-success">$ {{$dato->cobro_por_empresa}}</span></td>
                                                    <td>{{$dato->apartir_de}}</td>
                                                    <td style="text-align: center;">{{$dato->hasta}}</td>
                                                    <td style="text-align: center;">{{$dato->nombre_user}}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                            <td colspan="4" align="right"><strong>TOTAL GENERADO:</strong></td>
                                                <td align="center"><strong>$ {{$total_cobros_global_formateado}}</strong></td>
                                                <td colspan="3" align="right"></td>
                                            </tr>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                </div>
    </section>

    


<!-- Inicia Contenido IMG-->
    <div class="card" style="margin: 0 auto;width: 97%;" id="contenido_img">
      <div class="progress" style="margin: 0 auto;width: 100%;height:5px;">
        <div class="progress-bar bg-secondary" role="progressbar" style="width:10%; height:100%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
        <div class="card-body">
        <!-- Inicia contenido--> 

        <div class="col-auto  p-5 text-center">
         <img src="{{ asset('/img/sin_registros.png') }}" id="sin_registros" style="display: block;margin: 0px auto;width: 15%; height:15%;" >
            <h5>No se encontro ningún registro...</h5>
        </div>

        <!-- Finaliza contenido-->
        </div>
      </div>
    </div>
<!-- Finaliza Contenido IMG-->
   

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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>


    <script>
        $(document).ready(function() {
            document.getElementById("divcc").style.display = "block";
            var registros='{{$registros}}';
            if(registros==0){
                $('#contenido_img').show();
                $('#tarjeta_tabla').hide();
                $('#div_btn_cobros_pdf').hide();               
            }else{
                $('#contenido_img').hide();
                $('#tarjeta_tabla').show();  
                $('#div_btn_cobros_pdf').show();             
            }
            
        
 
           
        });

    </script>

    <script>

        function generarPdfCobrosDiarios(){

            window.open("{{ URL::to('/admin/reporte/reporte_cobros_diarios/pdf') }}");
       
        }



        function modalMensaje(titulo, mensaje){
            Swal.fire({
                title: titulo,
                text: mensaje,
                icon: 'info',
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {

                }
            });  
        }

    </script>


<script>

    $(function () {
        $("#tabla").DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "pagingType": "full_numbers",
            "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "Todo"]],

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
            "responsive": true, "lengthChange": false, "autoWidth": true,
        });
    });

</script>

@endsection
