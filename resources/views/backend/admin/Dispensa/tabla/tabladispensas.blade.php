<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th style="width: 25%;">Inicio del período</th>
                                <th style="width: 25%;">Finalización del período</th>
                                <th style="width: 15%;text-align:center;">Estado</th>
                                <th style="width: 25%;">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($dispensas as $dato)
                                <tr>
                                    <td>{{ $dato->fecha_inicio_periodo }}</td>
                                    <td>{{ $dato->fecha_fin_periodo }}</td>           
                                    @if($dato->estado == 'Activo')
                                    <td align="center"> <span class="badge bg-success">Activo</span></td>
                                    @elseif($dato->estado == 'Finalizado')
                                    <td align="center"> <span class="badge bg-danger">Finalizado</span></td>
                                    @elseif($dato->estado == 'Cancelado')
                                    <td align="center"> <span class="badge bg-warning">Cancelado</span></td>         
                                    @endif
                                    <td>
                                        <button type="button" class="btn btn-primary btn-xs" onclick="verInformacion('{{$dato->id}}')">
                                            <i class="fas fa-pencil-alt" title="Editar"></i>&nbsp; Editar
                                        </button>

                                        <button type="button" class="btn btn-danger btn-xs" onclick="modalBorrar('{{$dato->id}}')">
                                        <i class="fas fa-window-close"  title="Cancelar"></i>&nbsp; Cancelar
                                        </button>
                                    </td>
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


<script>
    $(function () {
        $("#tabla").DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
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
