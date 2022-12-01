<table id="tab_cierres_empresas" class="table table-bordered table-hover" style='font-size:14px;'>
   <thead>             
     <tr>  
        <th style="width: 30%;">Fecha</th>   
        <th style="width: 30%;">Tipo de operación</th>                          
        <th style="width: 30%;">Resoluciones</th>                           
    </tr>
        </thead>
        <tbody>     
        @foreach($historico_cierres as $dato)
   <tr>
        <td>{{ $dato-> fecha_a_partir_de }}</td>
        <td>{{ $dato-> tipo_operacion }}</td>
        <td>
            <center>
            <a class="btn btn-danger btn-xs" onclick="resolucion_cierre_historico({{$dato->id}})" target="frameprincipal">
            <i class="fas fa-print"></i>&nbsp; Generar</a>
            </center>                                                                                                   
        </td>                    
    </tr>
        @endforeach  
        </tbody>            
</table>  

<script>
//Script para Organizar la tabla de datos // prueba
$(document).ready(function() {
$("#tab_cierres_empresas").DataTable({
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
"sInfo": "Registros del _START_ al _END_ de un total de _TOTAL_",
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