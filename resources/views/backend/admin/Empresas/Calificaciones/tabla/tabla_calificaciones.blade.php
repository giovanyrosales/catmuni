
<table id="tabla" class="table table-bordered table-striped"  data-toggle="table" width="100%">

<thead>
    <tr>
    <th style="width: 25%;">Año</th>
    <th style="width: 25%;">Tipo tarifa</th>
    <th style="width: 25%;">Licencia</th>
    <th style="width: 25%;">Activo total</th>
    <th style="width: 25%;">Deducciones</th>
    <th style="width: 25%;">Activo Imponible</th>
    <th style="width: 25%;">Tarifa</th>
    <th style="width: 25%;">Multa balance</th>
    <th style="width: 25%;">Eliminar</th>
</tr>
</thead>
<tbody>
@foreach($calificaciones as $dato)
    <tr>
        <td>{{$dato->año_calificacion}}</td>
        <td>{{$dato->tipo_tarifa}}</td>
        <td>${{$dato->licencia}}</td>
        <td>${{$dato->activo_total}}</td>
        <td>${{$dato->deducciones}}</td>
        <td>${{$dato->activo_imponible}}</td>
        <td>${{$dato->tarifa}}</td>
        <td>${{$dato->multa_balance}}</td>
        <td>
            <button type="button" class="btn btn-danger btn-xs" onclick="modalEliminarCalidicación({{$dato->id}})">
            <i class="fas fa-trash" title="Eliminar"></i>&nbsp; 
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
"searching": true,
"ordering": true,
"info": true,
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
"responsive": true, "lengthChange": false, "autoWidth": true,
});
});

</script>