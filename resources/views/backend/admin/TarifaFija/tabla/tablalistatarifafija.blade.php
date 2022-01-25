<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <th style="width: 15%;">Actividad económica</th>
                                <th style="width: 15%;">Limite inferior</th>
                                <th style="width: 15%;">Limite superior</th>
                                <th style="width: 15%;">Impuesto mensual</th>
                                <th style="width: 15%;">Acción</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tarifa_fija as $tarifa_fija)
                                <tr>
                                    <td>{{$tarifa_fija->nombre_actividad}} </td>
                                    <td>{{$tarifa_fija->limite_inferior}} </td>
                                    <td>{{$tarifa_fija->limite_superior}} </td>
                                    <td>{{$tarifa_fija->impuesto_mensual}} </td>
                                  
                                    <td style="text-align: center;">
                                                                   
                                    <button type="button" class="btn btn-primary btn-xs" onclick="editarTarifa({{ $tarifa_fija->id }})">
                                    <i class="fas fa-pencil-alt" title="Editar"></i>&nbsp; Editar
                                    </button>

                                    <button type="button" class="btn btn-danger btn-xs" onclick=" modalEliminarTarifa({{ $tarifa_fija->id }})">
                                    <i class="fas fa-trash" title="Eliminar"></i>&nbsp; Eliminar
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