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
                                <th style="width: 10%;">N° Tarjeta</th>
                                <th style="width: 20%;">Dirección</th>
                                <th style="width: 10%;">Teléfono</th>
                                <th style="width: 15%;">Inicio de operaciones</th>
                                <th style="width: 15%;">Acción</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($empresas as $empresa)
                                <tr>
                                    <td>{{$empresa->nombre}} </td>
                                    <td>{{$empresa->num_tarjeta}}</td>
                                    <td>{{$empresa->direccion}}</td>
                                    <td>{{$empresa->telefono}}</td>
                                    <td>{{$empresa->inicio_operaciones}}</td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" >
                                        <i class="fas fa-search" title="Ver Registro"></i>&nbsp; Ver 
                                        </button>
                                                            
                                        <button type="button" class="btn btn-primary btn-xs" >
                                        <i class="fas fa-pencil-alt" title="Editar"></i>&nbsp; Editar
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
