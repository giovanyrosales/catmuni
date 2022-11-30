<style>
        body{
            font-family: 'Calibri';
        }

        #uno{
                font-size: 12px;
        }

        #Dos{
                font-size: 14px;
        }
        
</style>

<div>
    <button type='button' class='btn btn-block btn-primary' onclick='AgregarTarifaAnterior()' id="btn_asignar_tarifa_Anterior">
        <span class="badge badge-pill badge-light"><i class="fas fa-plus"></i></span>
        &nbsp;Agregar tarifa anterior
        <i class="fas fa-hand-holding-usd"></i>
    </button>
<br>
</div>
@if($calificacionesM=='0')
            <table id="tabla" class="table table-bordered table-striped"  data-toggle="table" width="100%">
            <thead>
                <tr id="uno">
                <th style="width: 10%;">Año</th>
                <th style="width: 10%;">Tipo tarifa</th>
                <th style="width: 10%;">Licencia</th>
                <th style="width: 12%;">Activo total</th>
                <th style="width: 10%;">Deducciones</th>
                <th style="width: 15%;">Activo Imponible</th>
                <th style="width: 10%;">Tarifa</th>
                <th style="width: 15%;">Multa balance</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            @foreach($calificaciones as $dato)
                <tr id="Dos">
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
@else
<table id="tabla" class="table table-bordered table-striped"  data-toggle="table" width="100%">
            <thead>
                <tr id="uno">
                <th style="width: 25%;">Año</th>
                <th style="width: 25%;">Tipo tarifa</th>
                <th style="width: 25%;">Tarifa</th>
                <th style="width: 25%;">Total matrículas</th>
                <th align="center"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($calificacionesM as $dato)
                <tr id="Dos">
                    <td>{{$dato->año_calificacion}}</td>
                    <td>{{$dato->tipo_tarifa}}</td>
                    <td>${{$dato->pago_mensual}}</td>
                    <td>${{$dato->monto_matricula}}</td>
                    <td align="center">
                        <button type="button" class="btn btn-danger btn-xs" onclick="modalEliminarCalidicación({{$dato->id}})">
                        <i class="fas fa-trash" title="Eliminar"></i> &nbsp;Borrar 
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>
@endif
<script type="text/javascript">

        $(document).ready(function(){
           
          var año_a_calificar ='{{$año_a_calificar}}';
          var anio_actual ='{{$anio_actual}}';

            //Validación anclaaa
            if(año_a_calificar>anio_actual){
                $("#btn_asignar_tarifa_Anterior").hide();        
             }


        });

    </script>

<script>
$(function () {
$("#tabla").DataTable({
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