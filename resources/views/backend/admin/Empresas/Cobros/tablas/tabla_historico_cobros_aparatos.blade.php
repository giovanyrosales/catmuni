<style>
        body{
            font-family: 'Calibri';
        }

        #letra_pequeña{
                font-size: 14px;
        }

        #Dos{
                font-size: 14px;
        }
        
</style>
<div>
    @if (count($ListaCobrosMatriculas) > 0)
        <a class="btn btn-info float-left" onclick="imp_historial_cobros_aparatos()"  target="frameprincipal">
        <i class="fas fa-print"></i>&nbsp; Imprimir</a>
    @endif
</div>
<table id="tab_historial_cobros_aparatos" class="table table-bordered table-hover" > 
              <thead>             
                <tr id="letra_pequeña">  
                    <th style="width: 15%;">Fecha pago</th> 
                    <th style="width: 10%;">Meses</th> 
                    <th style="width: 10%;">Matrícula</th>    
                    <th style="width: 20%;">Multa por matrícula</th>
                    <th style="width: 15%;">Periodo Inicio</th> 
                    <th style="width: 15%;">Periodo Fin</th>  
                    <th style="width: 15%;">Fondo fiestas</th>                          
                    <th style="width: 10%;">Total</th>                           
                </tr>
                    </thead>
                    <tbody>     
                    @foreach($ListaCobrosMatriculas as $dato)
                <tr id="Dos">
                        <td>{{ $dato-> fecha_cobro }}</td>
                        <td>{{ $dato-> cantidad_meses_cobro }}</td>
                        <td>${{ $dato-> matricula_12210 }}</td>
                        <td>${{ $dato-> multa_matricula_15313 }}</td>
                        <td>{{ $dato-> periodo_cobro_inicio }}</td>
                        <td>{{ $dato-> periodo_cobro_fin }}</td>
                        <td>${{ $dato-> fondo_fiestasP_12114 }}</td>
                        <td>${{ $dato-> pago_total }}</td>                   
                    </tr>
                        @endforeach  
                    </tbody>            
            </table> 

            
<script>
$(function () {
    $("#tab_historial_cobros_aparatos").DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth":true,

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