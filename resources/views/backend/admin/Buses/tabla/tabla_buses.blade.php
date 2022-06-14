
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <th style="width: 20%;">Empresa</th>
                                <th style="width: 15%;">Cantidad</th>
                                <th style="width: 15%;">Tarifa</th>
                                <th style="width: 15%;">Pago Mensual</th>
                                <th style="width: 25%;">Opciones</th>
                               
                            </tr>
                            </thead>
                            <tbody>
                           
                            @foreach($buses as $dato)
                                    <tr>
                                    <td>{{$dato->empresa}}</td>
                                    <td>{{$dato->cantidad}}</td>                           
                                    <td>${{$dato->tarifa}}</td>
                                    <td>${{$dato->monto_pagar}}</td>
                                   
                                    <td style="text-align: center;">
                                    @if($dato->estado_especificacion=='especificada')
                                        <button type="button" class="btn btn-success btn-xs">
                                        <i class="fas fa-check-circle"></i>&nbsp;Bus Específicado
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-dark btn-xs" onclick="EspecificarB({{$dato->id_buses_detalle}})">
                                        <i class="fas fa-layer-group"></i>&nbsp; Específicar buses
                                        </button>
                                    @endif
                                        <button type="button" class="btn btn-info btn-xs" onclick="VistaBus({{$dato->id_buses_detalle}})">
                                        <i class="fas fa-eye"></i>&nbsp; Ver
                                        </button>
                                        <button type="button" class="btn btn-primary btn-xs" onclick="InformacionBus({{$dato->id_buses_detalle}})">
                                         <i class="fas fa-pencil-alt" title="Editar"></i>&nbsp; Editar
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs" onclick="modalEliminarBus({{$dato->id_buses_detalle }})">
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
 
 
 