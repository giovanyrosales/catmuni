<table id="tab_traspasos_empresas" class="table table-bordered table-hover">
        <thead>             
            <tr>
                <th style="width: 20%;">Anterior</th>   
                <th style="width: 20%;">Nuevo</th>   
                <th style="width: 20%;">Fecha</th>                          
                <th style="width: 30%;">Resoluciones</th>                           
            </tr>
        </thead>
                <tbody>     
                    @foreach($historico_traspasos as $dato)
                    <tr>
                        <td>{{ $dato-> propietario_anterior }}</td>
                        <td>{{ $dato-> propietario_nuevo }}</td>
                        <td>{{ $dato-> fecha_a_partir_de }}</td> 
                        <td>
                            <center>
                            <a class="btn btn-warning btn-xs" onclick="resolucion_traspaso_historico({{$dato->id}})" target="frameprincipal">
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
$("#tab_traspasos_empresas").DataTable({
"paging": true,
"lengthChange": true,
"searching": true,
"ordering": false,
"info": true,
"autoWidth": true,

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