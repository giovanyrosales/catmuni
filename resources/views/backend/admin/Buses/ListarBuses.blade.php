@extends('backend.menus.superior')

@section('content-admin-css')
    <!-- Para el select live search -->
    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">
    <!-- Finaliza el select live search -->
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="sweetalert2.min.css">
@stop
<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
</style>

<!-- Contenido Frame Principal -->
<div id="divcontenedor" style="display: none">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h5>Lista De Buses Registrados</h1>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Listado de buses</li>
                            </ol>
                        </div>
        </div>
        <br>
        <button type="button"onclick="location.href='{{ url('/admin/nuevo/buses/Crear') }}'" class="btn btn-success btn-sm" >
                <i class="fas fa-pencil-alt"></i>
                Nuevo rótulo
            </button>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <!-- CAJA -->
        <form class="form-horizontal" id="form1">
        <div class="card card-green">
          <div class="card-header">
            <h3 class="card-title">Buses Agregados</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
            <div class="col-md-6">
            </div>
            </div>
            <!-- /.col -->
            <div id="tablaDatatable"></div>
            </div>
            </div>
          <!-- /.row -->
          </div>
         <!-- /.card-body -->
        <div class="card-footer">          
        </div>
        <!-- /.card-footer -->
         </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
</div><!--Termina Contenido Frame Principal -->
 <!--Inicia Modal Especificar Bus-->
    
 <div class="modal fade" id="modalEspecificarBus">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <!--Contenido del modal-->
                <div class="modal-header">
                        <h4 class="modal-title"><i class="far fa-plus-square"></i>&nbsp;Especificar Buses</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <!--Form del modal-->
<!-----------------------------------Inicia Contenido ------------------------------------------->
           <div class="modal-body">

           <!-- LISTA DE MATRICULAS  -->
           <div class="tab-pane" id="tab_2">

            <form>
                    <div class="card-body">
            <input  id='id_buses_detalle' type='hidden'/>
            <table class="table" id="matrizBuses" style="border: 100px" data-toggle="table">
                    <thead>
                    <tr>                           
                        <th style="width: 15%; text-align: center">Placa</th>
                        <th style="width: 22%; text-align: center">Nombre</th>
                        <th style="width: 15%; text-align: center">Ruta</th>
                        <th style="width: 10%; text-align: center">Teléfono</th>
                        <th style="width: 15%; text-align: center">Eliminar</th>
                    </tr>
                    </thead>
                    <tbody id="myTbodyBuses">
                    </tbody>
                    </table>
                    <br>
                        <button type="button"  class="btn btn-block btn-success" id="btnAddbusEspecifico"><i class="far fa-plus-square"></i> &nbsp; Específicar nuevo bus</button>               
                    <br>
                    </div>
                    </form>
                    </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                            <button type="button" onclick="GuardarBusesEspecificos()" class="btn btn-success float-right">Guardar</button>
                        </div>
                    </div>
                    </div>
                </div>
  </div>
         
<!--Finaliza Modal Especificar Bus-->

<!--Inicia Modal Editar bus y bus específico-->
    
<div class="modal fade bd-example-modal-lg" id="modalEditarBus" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <!--Contenido del modal-->
               <div class="modal-header">
                    <h4 class="modal-title"><i class="far fa-plus-square"></i>&nbsp;Editar Bus</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--Form del modal-->
                <div class="modal-body">
                    <form id="formulario-EditarBus">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <!--Campos del modal-->
                                    <table class="table" id="BusesEditar" style="border: 80px" data-toggle="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 20%; text-align: center">Fecha Apertura</th>
                                        <th style="width: 35%; text-align: center">Empresa</th>
                                        <th style="width: 18%; text-align: center">Cantidad de buses</th>   
                                        <th style="width: 20%; text-align: center">Total Tarifa</th>               
                                        <th style="width: 16%; text-align: center">Pago Mensual</th>
                                        <th style="width: 13%; text-align: center">Opciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <td>
                                    <input  id='fecha_apertura-editar' disabled class='form-control' min='1' style='max-width: 250px' type='date' value=''/>
                                    </td>    

                                    <td> 
                                        <input  id='id-editar' class='form-control' min='1' style='max-width: 250px' type='hidden' value=''/>                                        
                                        <select class='form-control seleccion' disabled style='max-width: 300px' id='select_empresa-editar'  >
                                       
                                        </select>
                                        </td>
                                    </td>

                                    <td>
                                    <input  id='cantidad-editar' disabled class='form-control' min='1' style='max-width: 250px' type='number' value=''/>
                                    </td>

                                    <td>
                                    <input  id='monto-editar' class='form-control' disabled min='1' style='max-width: 250px' type='text' value=''/>
                                    </td>
                                    <td>
                                    <input  id='pago_mensual-editar' class='form-control' disabled min='1' style='max-width: 250px' type='text' value=''/>
                                    </td>

                                    <td>
                                    <button type='button' class='btn btn-block btn-primary'  id="btnActualizar" onclick='EditarBus()'>
                                        <i class="far fa-edit"></i>
                                        &nbsp;Actualizar
                                    </button>
                                    </td>

                                    </tr>
                                        </tbody>
                                        </table>
                                    <!--/.Campos del modal-->
<!-----------------------------------Inicia Contenido Editar Matrícuila específica------------------------------------------->
                                <input  id='id_buses_detalle-editar' type='hidden'/>
                                <table class="table" id="matrizBusesEditar" style="border: 100px" data-toggle="table">
                                <thead>
                                <tr>                           
                                    <th style="width: 15%; text-align: center">Placa</th>
                                    <th style="width: 22%; text-align: center">Nombre</th>
                                    <th style="width: 15%; text-align: center">Ruta</th>
                                    <th style="width: 10%; text-align: center">Teléfono</th>
                                    <th style="width: 15%; text-align: center">Eliminar</th>
                                </tr>
                                </thead>
                                <tbody>
                             
                                </tbody>
                                </table>
                                <br>
                                    <button type="button"  class="btn btn-block btn-success"   id="btnAddbusEditar"><i class="far fa-plus-square"></i> &nbsp; Especificar nuevo bus</button>               
                                <br>
                                </div>
                            </div>           
                        <!-----------------------------------Termina  Contenido Editar Matrícuila específica ------------------------------------------->
                    </form>
                </div>
                <!--/. Form del modal-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                </div>
               <!--Finaliza Contenido del modal-->
            </div>
        </div>
    </div>
<!--Finaliza Modal Editar bus y bus específico-->

<!--Inicia Modal ver Bus específico-->    
<div class="modal fade bd-example-modal-lg" id="modalVerBusEsp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <!--Contenido del modal-->
               <div class="modal-header">
                    <h4 class="modal-title"><i class="far fa-file-alt"></i>&nbsp;Vista detallada del bus</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--Form del modal-->
                <div class="modal-body">
                    <form id="formulario-VerBusEsp">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <!--Tabla 1-->
                                    <table class="table table-hover table-striped" id="VerBusesEsp_detallado" style="border: 100px" data-toggle="table">
                                    <form id="formulario-show">
                                    <tbody>
                                                                  
                                    </tbody>
                                </form>
                                </table>
                                <hr>
                                <!--Tabla 12-->
                                <table class="table" id="matrizVerBusesEsp" style="border: 100px" data-toggle="table">
                                <thead>
                                <tr>                           
                                    <th style="width: 25%; text-align: center">Placa</th>
                                    <th style="width: 15%; text-align: center">Nombre</th>
                                    <th style="width: 20%; text-align: center">Ruta</th>
                                    <th style="width: 40%; text-align: center">Teléfono</th>
                                    <th style="width: 30%; text-align: center">Empresa</th>
                                </tr>
                                </thead>
                                <tbody>
                             
                                </tbody>
                                </table>
                            </div>
                            </div>        
                        <!-----------------------------------Termina  Contenido Editar Matrícuila específica ------------------------------------------->
                    </form>
                </div>
                <!--/. Form del modal-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                </div>
               <!--Finaliza Contenido del modal-->
            </div>
        </div>
</div>
  </div>
  </div>
    
<!--Finaliza Modal ver Bus específico-->

<!-- Inicia Modal Eliminar Bus-->
<div class="modal fade" id="modalEliminarBus">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="far fa-minus-square"></i>&nbsp;Eliminar Buses</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-EliminarBus">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>¿Realmente desea eliminar el bus seleccionado?"</p>

                                    <div class="form-group">
                                        <input type="hidden" id="idborrar">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger" onclick="eliminarM()">Borrar</button>
                </div>
            </div>
        </div>
    </div>
<!--Finaliza Modal Eliminar Bus-->





@extends('backend.menus.footerjs')
@section('archivos-js')
  <!-- Para el select live search -->
    <script src="{{ asset('js/bootstrap-select.min.js') }}" type="text/javascript"></script>
  <!-- Finaliza el select live search -->

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}" type="text/javascript"></script>
 
    <script src="sweetalert2.all.min.js"></script>
    <script src="sweetalert2.min.js"></script>

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    
    
<script type="text/javascript">
        $(document).ready(function(){
            var ruta = "{{ url('/admin/buses/tabla') }}";
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";
        });
</script>

<script type="text/javascript">
      
        $(document).ready(function(){
            document.getElementById("divcontenedor").style.display = "block";
        });

</script>

<script type="text/javascript">

    function recargar()
    {
        var ruta = "{{ url('/admin/buses/tabla') }}";
            $('#tablaDatatable').load(ruta);
    }

 
</script>

<script>
    function buses_especificos(e)
    {


        var table = e.parentNode.parentNode; // fila de la tabla

        var placa = table.cells[0].children[0]; //
        var nombre = table.cells[1].children[0]; //
        var ruta = table.cells[2].children[0];
        var telefono = table.cells[3].children[0]; 


    }

      // filas de la tabla Agrega Buses Específicos
      $(document).ready(function () {
      $("#btnAddbusEspecifico").on("click", function () {

          //agrega las filas dinamicamente
          
          
          if(cantidadBus==0){
              modalMensaje('¡Limite de Buses!', 'La cantidad de buses detallados llegó a su limite');
              }//cierra if
          while(cantidadBus>0){
              
          var markup = "<tr>"+

              "<td>"+
              "<input name='placa[]'  onchange='buses_especificos(this)' class='form-control' min='1' style='max-width: 250px' type='Text' value=''/>"+
              "</td>"+

              "<td>"+
              "<input name='nombre[]'  class='form-control'  min='1' style='max-width: 100px' type='text' value=''/>"+
              "</td>"+

              
              "<td>"+
              "<input name='ruta[]'  class='form-control'  min='1' style='max-width: 120px' type='text' value=''/>"+
              "</td>"+

              "<td>"+
              "<input name='telefono[]' class='form-control'  min='1' style='max-width: 250px' type='number' value=''/>"+
              "</td>"+

              "<td>"+
              "<button type='button' class='btn btn-block btn-danger' onclick='borrarFila(this)'><i class='fas fa-trash'></i></button>"+
              "</td>"+

              "</tr>";

          // $("tbody").append(markup);
          $("#matrizBuses tbody").append(markup);
          cantidadBus=cantidadBus-1;
          
          console.log(cantidadBus);
          }//cierra while

          
      });
      });

    // filas de la tabla Agregar buses específicos en Editar
    $(document).ready(function () {
    $("#btnAddbusEditar").on("click", function () {

        //agrega las filas dinamicamente


    var markup = "<tr id='0'>"+

        "<td>"+
        "<input name='placaeditararray[]'  onchange='buses_especificos(this)' class='form-control' min='1' style='max-width: 250px' type='text' value=''/>"+                  
        "</td>"+

        "<td>"+
        "<input name='nombreeditararray[]'  class='form-control'  min='1' style='max-width: 100px' type='text' value=''/>"+
        "</td>"+
        
        "<td>"+
        "<input name='rutaeditararray[]'  class='form-control'  min='1' style='max-width: 120px' type='text' value=''/>"+
        "</td>"+

        "<td>"+
        "<input name='telefonoeditararray[]' class='form-control'  min='1' style='max-width: 800px' type='number' value=''/>"+
        "</td>"+

        "<td>"+
        "<button type='button' class='btn btn-block btn-danger' onclick='borrarFilaBusEspEditar(this)'><i class='fas fa-trash'></i></button>"+
        "</td>"+

        "</tr>";

   // $("tbody").append(markup);
    $("#matrizBusesEditar tbody").append(markup);
  
    var nRegistro = $('#matrizBusesEditar >tbody >tr').length;

    document.getElementById('cantidad-editar').value = nRegistro;

    var fondoF = 0.05;
   
    var tarifa = nRegistro * 17.14;
    var calculador =  Math.ceil( tarifa + (tarifa *fondoF));

    

    //Imprimiendo resultado
    document.getElementById('monto-editar').value=  tarifa;
    document.getElementById('pago_mensual-editar').value= calculador;

 

    });
    });

  function InformacionBus(id)
  {

  openLoading();
  document.getElementById("formulario-EditarBus").reset();
  $("#matrizBusesEditar tbody tr").remove();

  axios.post('/admin/buses_detalle/informacion',{
      'id': id
  })
      .then((response) => {
        closeLoading();
        console.log(response);
        if(response.data.success === 1){
            if(response.data.listado.length!=0){
            window.cantidadBusEditar=response.data.buses_detalle.cantidad;
            //**** Cargar información editar matrícula detalle  ****//
            $('#modalEditarBus').css('overflow-y', 'auto');
            $('#modalEditarBus').modal({backdrop: 'static', keyboard: false})
            $('#id-editar').val(response.data.buses_detalle.id);
            $('#cantidad-editar').val(cantidadBusEditar);
            $('#id_buses_detalle-editar').val(response.data.buses_detalle.id);
            $('#monto-editar').val(response.data.montoDolar);
            $('#pago_mensual-editar').val(response.data.Pago_mensualDolar);
            $('#fecha_apertura-editar').val(response.data.buses_detalle.fecha_apertura);
            
            console.log(cantidadBusEditar);
            
            document.getElementById("select_empresa-editar").options.length = 0;
            $.each(response.data.empresa, function( key, val ){
                if(response.data.id_empresa == val.id){
                    $('#select_empresa-editar').append('<option value="' +val.id +'"data-pagoMEditar="'+val.tarifa+'" selected="selected">'+val.nombre+'</option>');
                }else{
                    $('#select_empresa-editar').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                }
            });
            //****  /. Cargar información editar matrícula detalle  ****//

            //****  Cargar información editar matrícula detalle específico  ****//
            var infodetalle = response.data.listado;
            
            
            for (var i = 0; i < infodetalle.length; i++) {
      

                var markup = "<tr id='"+infodetalle[i].id+"'>"+

                    "<td>"+
                    "<input name='placaeditararray[]' value='"+infodetalle[i].placa+"' maxlength='10' class='form-control' type='text'>"+
                    "</td>"+

                    "<td>"+
                    "<input name='nombreeditararray[]' value='"+infodetalle[i].nombre+"' maxlength='400' class='form-control' type='text'>"+
                    "</td>"+

                    "<td>"+
                    "<input name='rutaeditararray[]' value='"+infodetalle[i].ruta+"' maxlength='400' class='form-control' type='number'>"+
                    "</td>"+
                    
                    "<td>"+
                    "<input name='telefonoeditararray[]' value='"+infodetalle[i].telefono+"' maxlength='400' class='form-control' type='number'>"+
                    "</td>"+

                    "<td>"+
                    "<button type='button' class='btn btn-block btn-danger' onclick='borrarFilaBusEspEditar(this)'><i class='fas fa-trash'></i></button>"+
                    "</td>"+

                    "</tr>";

                $("#matrizBusesEditar tbody").append(markup);
                
                }//*Cierre de for
                
                   

            }else{
                    toastr.warning('Debe específicar primero la matrícula en la sección [Específicar matrículas].');
                    return;
                 }//*Cierre de if

            //****  /. Cargar información editar matrícula detalle específico  ****//
         
        }else{
            toastr.error('Información solicitada no fue encontrada.');
            }
        
    })
    .catch((error) => {
        closeLoading();
        toastr.error('Información no encontrada.');
    });

}

function EspecificarB(id_buses_detalle)
{

var formData = new FormData();
formData.append('id_buses_detalle', id_buses_detalle);

axios.post('/admin/buses_detalle/especifico', formData, {
})

    .then((response) => {
 
        closeLoading()

       if (response.data.success === 1) 
        { console.log(response);

            if(response.data.busesEspecificos!=null)
            {
                toastr.warning('La matrícula ya fue específicada');
                return;
            }else{
                    $('#modalEspecificarBus').css('overflow-y','auto');
                    $('#modalEspecificarBus').modal({backdrop:'static',keyboard:false});
                    $("#matrizBuses tbody tr").remove();
                    document.getElementById('id_buses_detalle').value=response.data.id_buses_detalle;
                    window.cantidadBus=response.data.cantidad;
                 }
            
            
        }
        else 
            {
                toastMensaje('Error');
                $('#modalEspecificarBus').modal('hide');
                recargar();
            }
    })
    .catch((error) => {
        closeLoading()
        toastMensaje('error', 'Error');
    });

    


}

//*** Inicia Editar buses ***//
function EditarBus()
{
var cantidad_editar = document.getElementById('cantidad-editar').value;
var empresa_editar = document.getElementById('select_empresa-editar').value;
var id_editar = document.getElementById('id-editar').value;
var monto_editar = document.getElementById('monto-editar').value;
var pago_mensual_editar = document.getElementById('pago_mensual-editar').value;
var fecha_apertura_editar = document.getElementById('fecha_apertura-editar').value;

//Datos matrícula específica
var id_buses_detalle_editar = document.getElementById('id_buses_detalle-editar').value;
var placa_editar= $("input[name='placaeditararray[]']").map(function(){return $(this).val();}).get();
var nombre_editar = $("input[name='nombreeditararray[]']").map(function(){return $(this).val();}).get();           
var ruta_editar = $("input[name='rutaeditararray[]']").map(function(){return $(this).val();}).get();
var telefono_editar = $("input[name='telefonoeditararray[]']").map(function(){return $(this).val();}).get();


//**** Validar */
var hayregistro=0;
var nRegistro = $('#matrizBusesEditar >tbody >tr').length;

let formData = new FormData();

if (nRegistro > 0){  

hayregistro = 1;  

for(var a = 0; a < placa_editar.length; a++){

var DatoPlaca = placa_editar[a];


if(DatoPlaca == ""){
                modalMensaje('Placa', 'Debe digitar un código de placa');
                return;
            }

}

for(var b = 0; b < nombre_editar.length; b++){

var DatoNombre = nombre_editar[b];


if(DatoNombre == ""){
                    modalMensaje('Nombre', 'Debe digitar un nombre');
                    return;
                }

}

for(var c = 0; c < ruta_editar.length; c++){

var DatoRuta = ruta_editar[c];


if(DatoRuta == ""){
                    modalMensaje('Ruta', 'Debe digitar una ruta');
                    return;
                }

}
for(var e = 0; e < telefono_editar.length; e++){

    var DatoTelefono = telefono_editar[e];


    if(DatoTelefono == ""){
                        modalMensaje('Teléfono', 'Debe digitar un número de teléfono');
                        return;
                    }

    }
   
//**** Fin de validar */


}//**Cierre de if nRegistro */

//*** Cargando los datos de matriculas detalle */

formData.append('hayregistro', hayregistro);  
formData.append('id_editar', id_editar);
formData.append('cantidad_editar', cantidad_editar);
formData.append('monto_editar', monto_editar);
formData.append('pago_mensual_editar', pago_mensual_editar);
formData.append('fecha_apertura_editar', fecha_apertura_editar);

console.log(hayregistro);
openLoading() 
// llenar array para enviar
//*** Cargando los datos de matriculas específicas */

for(var f = 0; f < placa_editar.length; f++){
var id = $("#matrizBusesEditar tr:eq("+(f+1)+")").attr('id');
formData.append('idarray[]', id);
formData.append('placa_editar[]', placa_editar[f]);
formData.append('nombre_editar[]', nombre_editar[f]);
formData.append('ruta_editar[]', ruta_editar[f]);
formData.append('telefono_editar[]', telefono_editar[f]);
console.log(placa_editar[f],nombre_editar[f],ruta_editar[f],telefono_editar[f]);
}
formData.append('id_buses_detalle_editar', id_buses_detalle_editar);


axios.post('/admin/buses_detalle/editar', formData, {
})

    .then((response) => {
 
        closeLoading()

       if (response.data.success === 1) 
        {
          Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: '¡Bus actualizado correctamente!',
              showConfirmButton: false,
              timer: 3000
            })
            $('#modalEditarBus').modal('hide');
            recargar();
        }
        else 
        {
            toastMensaje('Error al actualizar');
            $('#modalEditarBus').modal('hide');
                   recargar();
        }
    })
    .catch((error) => {
        closeLoading()
        toastMensaje('error', 'Error');
    });

}

//*** Finaliza Editar buses ***//


function GuardarBusesEspecificos()
{

var id_buses_detalle=(document.getElementById('id_buses_detalle').value);    
var placa = $("input[name='placa[]']").map(function(){return $(this).val();}).get();
var nombre = $("input[name='nombre[]']").map(function(){return $(this).val();}).get();
var ruta = $("input[name='ruta[]']").map(function(){return $(this).val();}).get();
var telefono = $("input[name='telefono[]']").map(function(){return $(this).val();}).get();


//**** Validar */

var nRegistro = $('#matrizBuses >tbody >tr').length;
if (nRegistro <= 0){

            modalMensaje('Registro Vacio', 'Debe especificar al menos una matrícula');
            return;
    }

for(var a = 0; a < placa.length; a++){

var DatoPlaca = placa[a];


if(DatoPlaca == ""){
                modalMensaje('Código Municipal', 'Debe digitar un código municipal');
                return;
            }

}


for(var b = 0; b < nombre.length; b++){

var DatoNombre = nombre[b];


if(DatoNombre == "")
{
modalMensaje('Nombre', 'Debe digitar un nombre de la unidad');
return;
            }

}            


for(var c = 0; c < ruta.length; c++){

var DatoRuta = ruta[c];


if(DatoRuta == ""){
                    modalMensaje('Ruta', 'Debe digitar una ruta');
                    return;
                }

}
for(var e = 0; e < telefono.length; e++){

    var DatoTelefono = telefono[e];


    if(DatoTelefono == ""){
                        modalMensaje('Teléfono', 'Debe digitar un número de teléfono');
                        return;
                    }

    }
   
//**** Fin de validar */

let formData = new FormData();

// llenar array para enviar
for(var f = 0; f < placa.length; f++){

formData.append('placa[]', placa[f]);
formData.append('nombre[]', nombre[f]);
formData.append('ruta[]', ruta[f]);
formData.append('telefono[]', telefono[f]);
console.log(nombre[f],placa[f],ruta[f],telefono[f]);
}

formData.append('id_buses_detalle',id_buses_detalle);

axios.post('/admin/buses_detalle_especifico/agregar', formData, {
})
.then((response) => {

if(response.data.success === 1){
// Matrícula específica agregada
agregado_buses_especifico();
}
else{
// error al crear
toastr.error('Error al agregar matrícula específica');
}

})
.catch((error) => {
toastr.error('Error!');
closeLoading();
});




}//**** Fin de guardar bus */

function VerBus_especifico(id_buses_detalle){

openLoading();
document.getElementById("formulario-VerBusEsp").reset();
$("#matrizVerBusesEsp tbody tr").remove();

axios.post('/admin/buses_detalle/ver_buses_especificos',{
    'id': id_buses_detalle
})
.then((response) => {
        closeLoading();
        console.log(response);
        if(response.data.success === 1){
            if(response.data.listado.length!=0){//*** If para saber si la matrícula ya fue específicada */

            $('#modalVerBusEsp').css('overflow-y', 'auto');
            $('#modalVerBusEsp').modal({backdrop: 'static', keyboard: false})

            //****  Cargar información editar matrícula detalle específico  ****//
            var infodetalle = response.data.listado;
    
            var caracteristica = response.data.mdetalle.nombre;
 
            for (var i = 0; i < infodetalle.length; i++) {
     

                var markup = "<tr id='"+infodetalle[i].id+"'>"+

                    "<td align='center'>"+
                        infodetalle[i].placa+
                    "</td>"+

                    "<td align='center'>"+
                        infodetalle[i].nombre+
                    "</td>"+

                    "<td align='center'>"+
                        infodetalle[i].ruta+
                    "</td>"+
                    
                    "<td align='center'>"+
                        infodetalle[i].telefono+
                    "</td>"+

                    "<td align='center'>"+
                        caracteristica+
                    "</td>"+

                    "</tr>";

                $("#matrizVerBusesEsp tbody").append(markup);
                
                }//*Cierre de for
                
                   

            }else{
                    toastr.warning('Debe específicar primero la matrícula en la sección [Específicar matrículas].');
                    return;
                 }//*Cierre de if

            //****  /. Cargar información editar matrícula detalle específico  ****//
         
        }else{
            toastr.error('Información solicitada no fue encontrada.');
            }
        
    })
    .catch((error) => {
        closeLoading();
        toastr.error('Información no encontrada.');
    });

}//**cierre de función */

function modalEliminarBus(id)
{
$('#idborrar').val(id);
$('#modalEliminarBus').modal('show');
}

function eliminarM()
{
openLoading()

// se envia el ID de la matrícula
var id = document.getElementById('idborrar').value;

var formData = new FormData();
formData.append('id', id);

axios.post('/admin/buses_detalle/eliminar', formData, {
})
  .then((response) => {
    closeLoading()
        $('#modalEliminarBus').modal('hide');
        
        if(response.data.success === 1){
   
   Swal.fire({
             position: 'top-end',
             icon: 'success',
             title: '¡Matrícula eliminada correctamente!',
             showConfirmButton: false,
             timer: 3000
           })
     recargar();

                   
            
  }else if(response.data.success === 2)
  {
      toastr.warning('Debe eliminar primero las matrículas específicas en la sección [Editar].');
  }else{
           toastMensaje('error', 'Error al borrar');
       }
    })
    
.catch(function (error) {
  closeLoading()
  toastr.error("Error de Servidor!");
   }); 
}
//*** Termina eliminar buses ***//

//** Función para setear el input cantidad al momento de editar */
function setearFilaMatrizEditar(){
var table = document.getElementById('matrizBusesEditar');
var conteo = 0;
for (var r = 1, n = table.rows.length; r < n; r++) {
    conteo +=1;
    var element = table.rows[r].cells[0].children[0];
    document.getElementById('cantidad-editar').value = ""+conteo;
    
}
}
//** Termina función para setear el input cantidad al momento de editar */

//** Función eliminar filas */
function borrarFilaBusEspEditar(elemento)
{

var tabla = elemento.parentNode.parentNode;
tabla.parentNode.removeChild(tabla);
cantidadBusEditar=cantidadBusEditar-1;


$('#cantidad-editar').val(cantidadBusEditar);

 
var nRegistro = $('#matrizBusesEditar >tbody >tr').length;

document.getElementById('cantidad-editar').value = nRegistro;

var fondoF = 0.05;
   
   var tarifa = nRegistro * 17.14;
   var calculador =  Math.ceil( tarifa + (tarifa *fondoF));

   //Imprimiendo resultado
   document.getElementById('monto-editar').value=  tarifa;
   document.getElementById('pago_mensual-editar').value= calculador;

// setearFilaMatrizEditar();
  
}
//** Termina función eliminar filas */

</script>

<script>
//* Inicia función multiplicar
function multiplicar()
{
    var fondoF = 0.05;
    var monto_matricula=0;
    var pago_mensual=0;
   
           
    var sel = document.getElementById("select_empresa");  
    var selected = sel.options[sel.selectedIndex];         
    var tarifa=selected.getAttribute('data-pagoM');              
    var cantidad = document.getElementById("cantidad").value; 

    //Operación                               
    var Total_pago_mensual=  tarifa*cantidad;           
    var monto_total = Math.ceil(tarifa*fondoF + (tarifa*cantidad));


    //Imprimiendo resultado
    document.getElementById('monto_matricula').value = Total_pago_mensual;
    document.getElementById('pago_mensual').value= monto_total;
   


} //* Termina función multiplicar

function agregarBus()
{

var empresa = document.getElementById("select_empresa").value; 
var fecha_apertura = document.getElementById("fecha_apertura").value;
var cantidad = document.getElementById("cantidad").value;
var monto_pagar = document.getElementById("pago_mensual").value;
var tarifa = document.getElementById("monto_matricula").value;


    if(fecha_apertura=="")
    {
        modalMensaje('Aviso', 'Debe ingresar una fecha');
        return;
    }

    if(empresa==0)
    {
        modalMensaje('Aviso', 'Debe selecionar una empresa');
        return;
    }
                        
    if(cantidad=="")
    {
        modalMensaje('Aviso', 'Debe ingresar una cantidad');
        return;
    }

    if(cantidad==0)
    {
        modalMensaje('Aviso', 'Debe ingresar una cantidad mayor a 0');
        return;
    }

openLoading();
var formData = new FormData();    

formData.append('empresa', empresa);
formData.append('fecha_apertura',fecha_apertura);
formData.append('cantidad', cantidad);
formData.append('tarifa', tarifa);
formData.append('monto_pagar', monto_pagar);



        axios.post('/admin/buses/agregar', formData, {
        })
            .then((response) => {
                console.log(response)
                closeLoading();
                if(response.data.success === 0){
                    toastr.error(response.data.message);
                }
                else if(response.data.success === 1){
                    agregado();
                    resetbtn();
                    }
                    else if(response.data.success === 2){
                    modalMensaje('Empresa repetida!', 'Para agregar más o eliminarlas seleccione las opciones [Editar o Eliminar]');
                    resetbtn();
                    return;
                }
                 
            })
            .catch((error) => {
                fallo('Error!', 'Error al agregar el bus');
            
            });              

}

function recargar()
{   
      
var ruta = "{{ url('/admin/buses/tabla') }}/";
    $('#tablaDatatable').load(ruta);
}


</script>

<script>

function resetbtn()
{
document.getElementById('monto_matricula').value=""; 
document.getElementById('cantidad').value=""; 
document.getElementById('pago_mensual').value=""; 
document.getElementById('select_empresa').value=0; 
} 

function agregado()
{
Swal.fire({
    title: 'Bus Agregado',
    text: "Puede modificarla en la opción [Editar]",
    icon: 'success',
    showCancelButton: false,
    confirmButtonColor: '#28a745',
    closeOnClickOutside: false,
    allowOutsideClick: false,
    confirmButtonText: 'Aceptar'
}).then((result) => {
    if (result.isConfirmed) {
       
            recargar();
            f3();
           
    }
});
}

function fallo(titulo, mensaje)
{
Swal.fire({
    title: titulo,
    text: mensaje,
    icon: 'error',
    showCancelButton: false,
    confirmButtonColor: '#28a745',
    confirmButtonText: 'Aceptar'
}).then((result) => {
    if (result.isConfirmed) {
    location.reload;
    }
});


}

function verificar()
{
Swal.fire({
    title: '¿Guardar Buses?',
    text: "",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonColor: '#d33',
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Guardar'
}).then((result) => {
    if (result.isConfirmed) {
        agregarBus();
    }
});
}

function agregado_buses_especifico(){
Swal.fire({
    title: 'Bus específico agregado',
    text: "Puede modificarla en la opción [Editar]",
    icon: 'success',
    showCancelButton: false,
    confirmButtonColor: '#28a745',
    closeOnClickOutside: false,
    allowOutsideClick: false,
    confirmButtonText: 'Aceptar'
}).then((result) => {
    if (result.isConfirmed) {
        $('#modalEspecificarBus').modal('hide');
            recargar();
            f3()
    }
});
}

function modalMensaje(titulo, mensaje)
{
Swal.fire({
    title: titulo,
    text: mensaje,
    icon: 'info',
    showCancelButton: false,
    confirmButtonColor: '#28a745',
    confirmButtonText: 'Aceptar'
}).then((result) => {
    if (result.isConfirmed) {

    }
});

}

function VistaBus(id)
{
openLoading();
window.location.href="{{ url('/admin/buses/vista/') }}/"+id;

}



  </script>

@stop