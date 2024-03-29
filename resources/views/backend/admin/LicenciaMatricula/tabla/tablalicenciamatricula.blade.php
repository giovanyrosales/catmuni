<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                
                                <th style="width: 15%;">Nombre</th>
                                <th style="width: 15%;">Tipo permiso</th>
                                <th style="width: 15%;">Monto permiso</th>
                                <th style="width: 15%;">Tarifa</th>
                                <th style="width: 15%;">Acción</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($licencia as $licencia)
                                <tr>
                                    <td>{{$licencia->nombre}}
                                    <td>{{$licencia->tipo_permiso}} </td>
                                    <td>${{$licencia->monto}} </td>
                                    <td>${{$licencia->tarifa}} </td>
                                                                     
                                    <td style="text-align: center;">
                                                                   
                                    <button type="button" class="btn btn-primary btn-xs" onclick="informacionLicenciaM({{ $licencia->id }})">
                                    <i class="fas fa-pencil-alt" title="Editar"></i>&nbsp; Editar
                                    </button>

                                    <button type="button" class="btn btn-danger btn-xs" onclick="modalEliminarLM ({{ $licencia->id }})">
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