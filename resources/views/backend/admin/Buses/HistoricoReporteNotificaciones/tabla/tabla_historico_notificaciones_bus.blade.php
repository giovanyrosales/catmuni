<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    <h4>
                        <img src="{{ asset('/img/historial_aviso.png') }}" style="width: 3%; height:3%;" >
                        Historial de avisos generados
                    </h4>
                        <table id="tabla_avisos" class="table table-bordered table-striped">
                            <thead>
                                <tr>   
                                    <th style="width: 10%;">N° Registro</th>
                                    <th style="width: 50%;">Contribuyente</th>
                                    <th style="width: 15%;">Teléfono</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($historico_avisos_bus as $dato)
                                <tr> 
                                    <td><span class="badge badge-dark">{{$dato->registro_comerciante}}</span></td>
                                    <td>{{$dato->nombre}}&nbsp;{{$dato->apellido}}</td>
                                    <td>{{$dato->telefono}}</td>                                  
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
        $("#tabla_avisos").DataTable({
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
