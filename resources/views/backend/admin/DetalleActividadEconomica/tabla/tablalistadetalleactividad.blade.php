<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <th style="width: 20%;">Limite inferior</th>
                                <th style="width: 10%;">Fijo</th>
                                <th style="width: 12%;">Categoria</th>
                                <th style="width: 10%;">Millar</th>
                                <th style="width: 25%;">Actividad económica</th>
                                <th style="width: 25%;">Acción</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lista as $dato)
                                <tr>
                                    <td>{{$dato->limite_inferior}} </td>
                                    <td>{{$dato->fijo}} </td>
                                    <td>{{$dato->categoria}} </td>
                                    <td>{{$dato->millar}} </td>
                                    <td>{{$dato->actividad_economica}}</td>

                                    <td style="text-align: center;">

                                    <button type="button" onclick="verDetalles({{$dato->id}} )" class="btn btn-dark btn-xs" >
                                    <i class="fas fa-search"></i>
                                    Ver
                                    </button>
                                                                    
                                    <button type="button" class="btn btn-primary btn-xs" onclick="informacionD({{$dato->id}})">
                                    <i class="fas fa-pencil-alt" title="Editar"></i>&nbsp; Editar
                                    </button>

                                    <button type="button" class="btn btn-danger btn-xs" onclick="">
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