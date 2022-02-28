
                       <table id="tabla" class="table table-bordered table-striped" style="border: 80px" data-toggle="table" width="80%">

                            <thead>
                                <tr>
                                <th style="width: 25%;">Tipo de Matricula</th>
                                <th style="width: 15%;">Cantidad</th>
                                <th style="width: 15%;">Monto</th>
                                <th style="width: 15%;">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($matriculas as $dato)
                                <tr>
                                    <td>{{$dato->tipo_matricula}}</td>
                                    <td>{{$dato->cantidad}}</td>
                                    <td>${{$dato->monto}}</td>
                                    <td style="text-align: center;"> 
                                        <button type="button" class="btn btn-primary btn-xs" onclick="InformacionMatricula({{$dato->id }})">
                                         <i class="fas fa-pencil-alt" title="Editar"></i>&nbsp; Editar
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs" onclick="modalEliminarMatricula({{$dato->id }})">
                                         <i class="fas fa-trash" title="Eliminar"></i>&nbsp; Eliminar
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                      
<script>
    $(function () {
        $("#tabla").DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": true,

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