<style>
        body{
            font-family: 'Calibri';
        }

        #letra_pequeña{
                font-size: 12px;
        }

        #Dos{
                font-size: 13px;
        }
        
</style>

<div>
     <a class="btn btn-danger float-left" onclick="imp_historial_cobros_sinfonolas()"  target="frameprincipal">
     <i class="fas fa-print"></i>&nbsp; Imprimir</a>
</div>
<table id="tab_historial_cobros_sinfonolas" class="table table-bordered table-hover" > 
              <thead>             
                <tr id="letra_pequeña">  
                    <th style="width: 20%;">Fecha pago</th> 
                    <th style="width: 8%;">Meses</th> 
                    <th style="width: 18%;">Periodo inicio</th>                          
                    <th style="width: 15%;">Periodo fin</th>      
                    <th style="width: 15%;">Multa por matrícula</th>
                    <th style="width: 10%;">Multas</th>  
                    <th style="width: 15%;">Fondo fiestas</th>                          
                    <th style="width: 10%;">Total</th>                         
                </tr>
                    </thead>
                    <tbody>     
                    @foreach($ListaCobrosSinfonolas as $dato)
                <tr>
                        <td>{{ $dato-> fecha_cobro }}</td>
                        <td>{{ $dato-> cantidad_meses_cobro }}</td>
                        @if($dato-> periodo_cobro_inicio==null)
                        <td>{{ $dato-> periodo_cobro_inicioMatricula }}</td>
                        <td>{{ $dato-> periodo_cobro_finMatricula }}</td>
                        @else
                        <td>{{ $dato-> periodo_cobro_inicio }}</td>
                        <td>{{ $dato-> periodo_cobro_fin }}</td>
                        @endif
                        <td>${{ $dato-> monto_multa_matricula }}</td>
                        <td>${{ $dato-> monto_multaPE }}</td>
                        <td>${{ $dato-> fondo_fiestasP }}</td>
                        <td>${{ $dato-> pago_total }}</td>                        
                    </tr>
                        @endforeach  
                    </tbody>            
            </table> 

            
<script>
$(function () {
    $("#tab_historial_cobros_sinfonolas").DataTable({
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