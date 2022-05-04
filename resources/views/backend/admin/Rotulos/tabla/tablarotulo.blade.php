<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                                <tr>    
                                    <th style="width: 15%;">Nombre</th>
                                    <th style="width: 18%;">Dirección</th>
                                    <th style="width: 13%;">Medidas m²</th>
                                    <th style="width: 13%;">Caras</th>
                                    <th style="width: 15%;">Tarifa</th>
                                    <th style="width: 15%;">Impuesto total</th>
                                    
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($calificacion as $rotulo)
                                <tr>
                                    <td>{{$rotulo->nom_rotulo}}</td>
                                    <td>{{$rotulo->direccion}}</td>
                                    <td>
                                    <input  id="total_medidas" class='form-control' disabled min='1' style='max-width: 250px' type="text" value="{{$rotulo->total_medidas}} "/></td>
                                    <td>
                                    <input  id="total_caras" class='form-control' disabled  min='1' style='max-width: 250px' type='text' value='{{$rotulo->total_caras}}'/></td>
                                    <td>                                        
                                    <input  id="tarifa_mensual" class='form-control' min='1' style='max-width: 250px' type='text' value='${{$rotulo->monto}}'/>
                                    </td>
                                    <td>                                        
                                    <input  id="total_impuesto" class='form-control' min='1' style='max-width: 250px' type='text' value='${{$rotulo->total_impuesto}}'/>
                                    </td>
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

