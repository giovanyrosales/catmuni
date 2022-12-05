
                       <table id="tabla" class="table table-bordered table-striped" border= "1" data-toggle="table" width="80%">

                            <thead>
                                <tr>
                                <th style="width: 50%;">Tipo de Matricula</th>
                                <th style="width: 25%;">Cantidad</th>
                                <th style="width: 25%;">Monto</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($matriculas as $dato)
                                <tr>
                                    <td>{{$dato->tipo_matricula}}</td>
                                    <td>{{$dato->cantidad}}</td>
                                    <td>${{$dato->monto}}</td>
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
"responsive": true, "lengthChange": false, "autoWidth": true,
});
});

</script>