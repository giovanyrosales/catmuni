
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <th style="width: 8%;">N° Ficha</th>
                                <th style="width: 27%;">Representante</th>
                                <th style="width: 12%;">Cantidad</th>
                                <th style="width: 15%;">Fecha Apertura</th>                           
                                <th style="width: 30%;">Opciones</th>
                               
                            </tr>
                            </thead>
                            <tbody>
                           
                            @foreach($rotulo as $dato)
                            
                                    <tr>
                                    <td>{{$dato->num_ficha}}</td>
                                    <td>{{$dato->contribuyente}} {{$dato->apellido}}</td>
                                    <td>{{$dato->cantidad_rotulos}}</td>                           
                                    <td>{{$dato->fecha_apertura}}</td>
                                    
                                   
                                    <td style="text-align: center;">

                                    @if($dato->estado_especificacion=='especificada')
                                        <button type="button" class="btn btn-success btn-xs">
                                            <i class="fas fa-check-circle"></i>&nbsp;Rótulos Específicado
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-dark btn-xs" onclick="EspecificarRotulo({{$dato->id_rotulos_detalle}})">
                                            <i class="fas fa-layer-group"></i>&nbsp; Específicar Rótulos
                                        </button>
                                    @endif
                                    
                                    @if($dato->estado_especificacion=='especificada')

                                        <button type="button" class="btn btn-info btn-xs" onclick="VistaBus({{$dato->id}})">
                                            <i class="fas fa-eye"></i>&nbsp; Ver
                                        </button>

                                     @else

                                        <button type="button" class="btn btn-info btn-xs" onclick="Realizar({{$dato->id}})">
                                            <i class="fas fa-eye"></i>&nbsp; Ver
                                        </button>

                                    @endif

                                        <button type="button" class="btn btn-primary btn-xs" onclick="InformacionBus({{$dato->id}})">
                                            <i class="fas fa-pencil-alt" title="Editar"></i>&nbsp; Editar
                                        </button>

                                        <button type="button" class="btn btn-danger btn-xs" onclick="modalEliminarBus({{$dato->id }})">
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

<script>
    function Realizar()
    {

        toastr.warning('Debe especificar buses para ver la vista detallada');
        return;

    }


</script>
 
 
 