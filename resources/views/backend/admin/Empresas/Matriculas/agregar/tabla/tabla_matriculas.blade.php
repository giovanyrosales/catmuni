
                       <table id="tabla" class="table table-bordered table-striped" style="border: 10px" data-toggle="table" width="100%">

                            <thead>
                                <tr id="uno">
                                <th style="width: 25%;">Tipo de Matricula</th>
                                <th style="width: 12%;">Estado</th>
                                <th style="width: 12%;">Cantidad</th>
                                <th style="width: 20%;">Total Matrículas</th>
                                <th style="width: 18%;">Pago Mensual</th>
                                <th style="width: 45%;">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($matriculas as $dato)
                                <tr id="dos">
                                    <td>{{$dato->tipo_matricula}}</td>
                                    @if($dato->id_estado_moratorio == '1')
                                    <td align="center"> <span class="badge bg-success">{{$dato->estado_moratorio}}</span></td>
                                    @elseif($dato->id_estado_moratorio == '2')
                                    <td align="center"> <span class="badge bg-warning">{{$dato->estado_moratorio}}</span></td>
                                    @endif
                                    <td>{{$dato->cantidad}}</td>
                                    <td>${{$dato->monto}}</td>
                                    <td>${{$dato->pago_mensual}}</td>
                                    <td style="text-align: center;"> 
                                    @if($dato->estado_especificacion=='especificada')
                                        <button type="button" class="btn btn-success btn-xs">
                                        <i class="fas fa-check-circle"></i>&nbsp;Matrícula Específicada
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-dark btn-xs" onclick="EspecificarM({{$dato->id_matriculas_detalle}})">
                                        <i class="fas fa-layer-group"></i>&nbsp; Específicar matrículas
                                        </button>
                                    @endif
                                        <button type="button" class="btn btn-info btn-xs" onclick="VerMatricula_especifica({{$dato->id_matriculas_detalle}})">
                                        <i class="fas fa-eye"></i>&nbsp; Ver
                                        </button>
                                        <button type="button" class="btn btn-primary btn-xs" onclick="InformacionMatricula({{$dato->id_matriculas_detalle}})">
                                         <i class="fas fa-pencil-alt" title="Editar"></i>&nbsp; Editar
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs" onclick="modalEliminarMatricula({{$dato->id_matriculas_detalle }})">
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
            "lengthChange": false,
            "searching": false,
            "ordering": false,
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
            "responsive": true, "lengthChange": false, "autoWidth": false,
        });
    });

</script>
<style>
#tres {
  overflow: hidden;

}


        #uno{
                font-size: 14px;
        }
        #dos{
                font-size: 14px;
        }


    </style>