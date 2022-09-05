
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <th style="width: 15%;">N° Placa</th>
                                <th style="width: 20%;">Nombre Bus</th>
                                <th style="width: 10%;">Ruta</th>                              
                                <th style="width: 15%;">Opciones</th>
                               
                            </tr>
                            </thead>
                            <tbody>
                           
                            @foreach($listado as $dato)
                                <tr>
                                    <td>{{$dato->placa}}</td>
                                    <td>{{$dato->nombre}}</td>
                                    <td>{{$dato->ruta}}</td>       
                                    <td class="text-center" style="width: 30%;margin-top:100px" >
                                      
                                        <input class="form-check-input" type="checkbox" value="" name="buses_seleccionados" id="buses_seleccionados">
                                                <label class="form-check-label" for="flexCheckDefault"></label>
                                        
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
 
 
 