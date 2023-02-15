@extends('backend.menus.superior')

@section('content-admin-css')


    <!-- Finaliza el select live search -->
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />



@stop
<script type="text/javascript">
function f1(){
    
    $('#tmatriculas').hide();
    $('#DivMatriculas').hide();
    $('#aviso').show();
}
function f2(){
    $('#tmatriculas').show();
    $('#aviso').hide();              
}
function f3(){
    $('#tmatriculas').show();
    $('#DivMatriculas').show();          
}
function f4(){
    location.reload();        
}
function ocultarAdd(){
    $('#tmatriculas').hide();
}


</script>
<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
</style>
<!-----------------------------------Inicia Contenido ------------------------------------------->

<div class="content-wrapper" style="display: none" id="divcontenedor">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4><i class="far fa-plus-square"></i>&nbsp;Agregar Matrículas</h4>
                </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Matrículas</li>
                            </ol>
                        </div>
            </div>
        </div>
    </section>


 <!-- Main content -->
 <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div class="card">


            <!-- LISTA DE MATRICULAS  -->
             <div class="tab-pane" id="tab_2">

                 <form>
                         <div class="card" id="tmatriculas">

                <table class="table"  style="border: 80px" data-toggle="table">
                        <thead>
                           <tr>
                            <th style="width: 35%; text-align: center">Tipo de Matricula</th>
                            <th style="width: 13%; text-align: center">Cantidad</th>
                            <th style="width: 22%; text-align: center">Total Matrículas</th>
                            <th style="width: 18%; text-align: center">Pago Mensual</th>
                            <th style="width: 15%; text-align: center">&nbsp;</th>
                           </tr>
                        </thead>
                        <tbody>
                            <td>
                            <select class='form-control seleccion' onchange='multiplicar(this)' style='max-width: 300px' id='select_matriculas'  >
                               <!-- <option value='0'> --  Seleccione el tipo matrícula  -- </option> -->
                                @foreach($matriculas as $data)
                                  
                                        <option value='{{ $data->id }}' data-matricula='{{ $data->monto }}' data-pagoM='{{ $data->tarifa }}'> {{ $data->nombre }}</option>
                                    
                                @endforeach>
                   
                            </select>
                            </td>

                        <td>
                        <input  id='cantidad' onchange='multiplicar(this)' class='form-control' min='1' style='max-width: 250px' type='number' value=''/>
                        </td>

                        <td>
                        <input  id='monto_matricula' class='form-control' disabled min='1' style='max-width: 250px' type='text' value=''/>
                        </td>


                        <td>
                        <input  id='pago_mensual' class='form-control' disabled min='1' style='max-width: 250px' type='text' value=''/>
                        </td>

                        <td>
                        <button type='button' class='btn btn-block btn-success'  id="btnAdd" onclick='verificar()'>
                            <i class="far fa-plus-square"></i> 
                            &nbsp;Agregar
                        </button>
                        </td>

                        </tr>
                            </tbody>
                            </table>
                            </div>
                        </form>
                       </div>
              
                       <!-- Inclución de tabla -->
                       <div class="m-0 row justify-content-center" id="DivMatriculas">
                            <div class="card">
                                    <div class="card-header" style="background-color:#FFD219; color: #FFFFFF;">
                                        <h5> Matrículas registradas para <span class="badge badge-pill badge-light">{{$empresa->nombre}}</span></h5> 
                                    </div>
                                    <div class="col-auto  p-5 text-center" id="tablaDatatable"></div>
                            </div>
                        </div>
                        @if($detectorNull== '1')
                                    <script>
                                    window.onload = f1;
                                    </script>
                                <section class="content-header" id="aviso">
                                    <div class="container-fluid">
                                        <div>
                                            <br>
                                            <div class="callout callout-info">
                                                <h5><i class="fas fa-info"></i> Nota:</h5>
                                                <h5> No hay matrículas registradas para <span class="badge badge-warning">{{$empresa->nombre}}</span></h5> 
                                                <button type='button' class='btn btn-block btn-primary'  id="btnAdd" onclick='f2()'>
                                                    <i class="far fa-plus-square"></i> 
                                                    &nbsp;Agregar matrículas
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                        @endif
                       @if($detectorNull== '0')
                       <script>
                                    window.onload =ocultarAdd;
                       </script>
                       @endif
                       <!-- /.Inclución de tabla -->
                  
                            <div class="card-footer">
                                <button type="button" class="btn btn-default" onclick="VerEmpresa()"><i class="fas fa-chevron-circle-left"></i> &nbsp;Volver</button>
                            </div>
                         </div>
                        </div>
                      </div>
                </div>
         </section>
</div>

<!-- Inicia Modal Eliminar Matrícula-->

<div class="modal fade" id="modalEliminarMatricula">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="far fa-minus-square"></i>&nbsp;Eliminar Matrículas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-EliminarMatrícula">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <p>¿Realmente desea eliminar la matrícula seleccionada?"</p>

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

    <!--Finaliza Modal Eliminar Matrícula-->

    <!--Inicia Modal Editar Matrícula y matrícula específica-->
    
    <div class="modal fade bd-example-modal-lg" id="modalEditarMatricula" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <!--Contenido del modal-->
               <div class="modal-header">
                    <h4 class="modal-title"><i class="far fa-plus-square"></i>&nbsp;Editar Matrícula</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--Form del modal-->
                <div class="modal-body">
                    <form id="formulario-EditarMatricula">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <!--Campos del modal-->
                                    <table class="table" id="MatriculasEditar" style="border: 80px" data-toggle="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 32%; text-align: center">Tipo de Matricula</th>
                                        <th style="width: 15%; text-align: center">Cantidad</th>
                                        <th style="width: 25%; text-align: center">Total Matrículas</th>
                                        <th style="width: 20%; text-align: center">Pago Mensual</th>
                                        <th style="width: 25%; text-align: center">Opciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <td> 
                                        <input  id='id-editar' class='form-control' min='1' style='max-width: 250px' type='hidden' value=''/>                                        
                                        <select class='form-control seleccion' disabled style='max-width: 300px' id='select_matriculas-editar'  >  
                                        </select>
                                        </td>
                                    </td>

                                    <td>
                                    <input  id='cantidad-editar' disabled class='form-control' min='1' style='max-width: 250px' type='text' value=''/>
                                    </td>
                                     <td>
                                    <input  id='monto-editar' class='form-control' disabled min='1' style='max-width: 250px' type='text' value=''/>
                                    </td>
                                    <td>
                                    <input  id='pago_mensual-editar' class='form-control' disabled min='1' style='max-width: 250px' type='text' value=''/>
                                    </td>

                                    <td>
                                    <button type='button' class='btn btn-block btn-primary'  id="btnActualizar" onclick='EditarMatricula()'>
                                        <i class="far fa-edit"></i>
                                        &nbsp;Actualizar
                                    </button>
                                    </td>

                                    </tr>
                                        </tbody>
                                        </table>
                                    <!--/.Campos del modal-->
<!-----------------------------------Inicia Contenido Editar Matrícuila específica------------------------------------------->
                                <input  id='id_matriculas_detalle-editar' type='hidden'/>
                                <table class="table" id="matrizMatriculasEditar" style="border: 100px" data-toggle="table">
                                <thead>
                                <tr>                           
                                    <th style="width: 25%; text-align: center">Cód. Municipal</th>
                                    <th style="width: 15%; text-align: center">Código</th>
                                    <th style="width: 20%; text-align: center">N° serie</th>
                                    <th style="width: 35%; text-align: center">Dirección</th>
                                    <th style="width: 15%; text-align: center">Eliminar</th>
                                </tr>
                                </thead>
                                <tbody>
                             
                                </tbody>
                                </table>
                                <br>
                                     <button type="button"  class="btn btn-block btn-success"   id="btnAddmatriculaEditar"><i class="far fa-plus-square"></i> &nbsp; Especificar nueva matrícula</button>             
                                <br>
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

    <!--Finaliza Modal Editar Matrícula y matrícula específica-->

    <!--Inicia Modal Especificar Matrícula-->
    
    <div class="modal fade" id="modalEspecificarMatricula">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <!--Contenido del modal-->
                <div class="modal-header">
                        <h4 class="modal-title"><i class="far fa-plus-square"></i>&nbsp;Especificar Matrículas</h4>
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
            <input  id='id_matriculas_detalle' type='hidden'/>
            <table class="table" id="matrizMatriculas" style="border: 100px" data-toggle="table">
                    <thead>
                    <tr>                           
                        <th style="width: 20%; text-align: center">Cód. Municipal</th>
                        <th style="width: 20%; text-align: center">Código</th>
                        <th style="width: 20%; text-align: center">N° serie</th>
                        <th style="width: 35%; text-align: center">Dirección</th>
                        <th style="width: 15%; text-align: center">Eliminar</th>
                    </tr>
                    </thead>
                    <tbody id="myTbodyMatriculas">
                    </tbody>
                    </table>
                    <br>
                        <button type="button"  class="btn btn-block btn-success" id="btnAddmatriculaEspecifica"><i class="far fa-plus-square"></i> &nbsp; Específicar nueva matrícula</button>               
                    <br>
                    </div>
                    </form>
                    </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                            <button type="button" onclick="GuardarMatriculaEspecifica()" class="btn btn-success float-right">Guardar</button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            </section>
            </div>

           </div>
<!-----------------------------------Termina Contenido ------------------------------------------->
               <!--Finaliza Contenido del modal-->
            </div>
        </div>
    </div>

    <!--Finaliza Modal Especificar Matrícula-->


<!--Inicia Modal ver Matrícula y matrícula específica-->
    
<div class="modal fade bd-example-modal-lg" id="modalVerMatriculaEsp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <!--Contenido del modal-->
               <div class="modal-header">
                    <h5 class="modal-title"><i class="far fa-file-alt"></i>&nbsp;
                    Vista detallada de la matrícula:&nbsp; <label id="nombre_matricula"></label>
                    <input type="hidden" id="id_de_matricula">
                </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--Form del modal-->
                <div class="modal-body">
                    <form id="formulario-VerMatriculaEsp">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <!--Tabla 1-->
                                     <div class="card" >
                                        <div class="card-header" style="background-color:#14A3D9; color: #FFFFFF;">
                                            <b>Información General</b>
                                        </div>
                                        <div class="card-body">

                                    <table class="table table-hover table-striped" id="VerMatriculasEsp_detallada" style="border: 100px" data-toggle="table">
                                    <form id="formulario-show">
                                    <tbody>
                                    <tr>
                                        <th>Empresa:</th>
                                        <td>{{$empresa->nombre}}</td>
                                    </tr>
                                    <tr>
                                        <th>Contribuyente:</th>
                                        <td>{{$empresa->contribuyente}} &nbsp;{{$empresa->apellido}}</td>
                                    </tr>
                                    <tr>
                                        <th>Telefono:</th>
                                        <td>{{$empresa->telefono}}</td>
                                    </tr>
                                    
                                    </tbody>
                                </form>
                                </table>
                                <hr>
                                <!--Tabla 12-->
                                <table class="table" id="matrizVerMatriculasEsp" style="border: 100px" data-toggle="table">
                                <thead>
                                <tr>                           
                                    <th style="width: 25%; text-align: center">Cód. Municipal</th>
                                    <th style="width: 15%; text-align: center">Código</th>
                                    <th style="width: 20%; text-align: center">N° serie</th>
                                    <th style="width: 40%; text-align: center">Dirección</th>
                                    <th style="width: 30%; text-align: center">Características</th>
                                </tr>
                                </thead>
                                <tbody>
                             
                                </tbody>
                                </table>
                                </div>
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

    <!--Finaliza Modal ver Matrícula específica-->

<!-----------------------------------Termina Contenido ------------------------------------------->
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


    <script type="text/javascript">
        $(document).ready(function(){
            var id={{$id}};
            var ruta = "{{ url('/admin/matriculas_detalle/tabla') }}/"+id;
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

<!-------------  Agregar matrículas  ---------------------------------->
<script>

function matricula_especificas(e){


     var table = e.parentNode.parentNode; // fila de la tabla

            var cod_municipal = table.cells[0].children[0]; //
            var codigo = table.cells[1].children[0]; //
            var num_serie = table.cells[2].children[0];
            var direccion = table.cells[3].children[0]; 
          
         
}

// filas de la tabla Agrega Matrículas Específicas
        $(document).ready(function () {
            $("#btnAddmatriculaEspecifica").on("click", function () {


                //agrega las filas dinamicamente
               
                
                    if(cantidadMatricula==0)
                    {
                        modalMensaje('¡Limite de Matrículas!', 'La cantidad de matrícula detalladas llegó a su limite');
                    }   //cierra if

                while(cantidadMatricula>0){
                   
                var markup = "<tr>"+
  
                    "<td>"+
                    "<input name='cod_municipal[]'  onchange='matricula_especificas(this)' class='form-control' min='1' style='max-width: 250px' type='Text' value=''/>"+
                    "</td>"+

                    "<td>"+
                    "<input name='codigo[]' disabled class='form-control'  min='1' style='max-width: 100px' type='number' value='"+ '{{$empresa->codigo_atc_economica}}' + "'/>"+
                    "</td>"+

                    
                    "<td>"+
                    "<input name='num_serie[]'  class='form-control'  min='1' style='max-width: 120px' type='text' value=''/>"+
                    "</td>"+

                    "<td>"+
                    "<input name='direccion[]' class='form-control'  min='1' style='max-width: 250px' type='text' value=''/>"+
                    "</td>"+

                    "<td>"+
                    "<button type='button' class='btn btn-block btn-danger' onclick='borrarFila(this)'><i class='fas fa-trash'></i></button>"+
                    "</td>"+

                    "</tr>";

               // $("tbody").append(markup);
                $("#matrizMatriculas tbody").append(markup);
                cantidadMatricula=cantidadMatricula-1;
                
                console.log(cantidadMatricula);
              }//cierra while
            
              
            });
        });

// filas de la tabla Agregar matrículas específicas en Editar
$(document).ready(function () {
            $("#btnAddmatriculaEditar").on("click", function () {

                //agrega las filas dinamicamente


                var markup = "<tr id='0'>"+
  
                    "<td>"+
                    "<input name='cod_municipaleditararray[]'  onchange='matricula_especificas(this)' class='form-control' min='1' style='max-width: 250px' type='text' value=''/>"+                  
                    "</td>"+

                    "<td>"+
                    "<input name='codigoeditararray[]'  class='form-control'  min='1' style='max-width: 100px' type='number' value=''/>"+
                    "</td>"+

                    
                    "<td>"+
                    "<input name='num_serieeditararray[]'  class='form-control'  min='1' style='max-width: 120px' type='text' value=''/>"+
                    "</td>"+

                    "<td>"+
                    "<input name='direccioneditararray[]' class='form-control'  min='1' style='max-width: 250px' type='text' value=''/>"+
                    "</td>"+

                    "<td>"+
                    "<button type='button' class='btn btn-block btn-danger' onclick='borrarFilaMatrículaEspEditar(this)'><i class='fas fa-trash'></i></button>"+
                    "</td>"+

                    "</tr>";

               // $("tbody").append(markup);
                $("#matrizMatriculasEditar tbody").append(markup);
              
                cantidadMatriculaEditar=cantidadMatriculaEditar+1;
                $('#cantidad-editar').val(cantidadMatriculaEditar);

                
                var sel = document.getElementById("select_matriculas-editar");  
                var selected = sel.options[sel.selectedIndex];
                var monto_matricula=selected.getAttribute('data-matriculaEditar');
                var tarifa=selected.getAttribute('data-pagoMEditar');
                

                var cantidad = document.getElementById("cantidad-editar").value; 
                

                //Operación
                var monto_total= '$'+ monto_matricula*cantidadMatriculaEditar;
                var Total_pago_mensual= '$'+ tarifa*cantidadMatriculaEditar;
                console.log(monto_matricula,monto_total,cantidad,Total_pago_mensual);
                //Imprimiendo resultado
                document.getElementById('monto-editar').value=monto_total;
                document.getElementById('pago_mensual-editar').value=Total_pago_mensual;  
               
               
            });
        });
</script>



<script>


//*** Inicia Agregar matrículas ***//

//* Inicia función multiplicar
function multiplicar(){

var sel = document.getElementById("select_matriculas");  
var selected = sel.options[sel.selectedIndex];
var monto_matricula=selected.getAttribute('data-matricula');
var tarifa=selected.getAttribute('data-pagoM');
console.log(pago_mensual,monto_matricula);
var cantidad = document.getElementById("cantidad").value; 

//Operación
var Total_pago_mensual= '$'+ tarifa*cantidad;
var monto_total= '$'+ monto_matricula*cantidad;

//Imprimiendo resultado
document.getElementById('monto_matricula').value=monto_total; 
document.getElementById('pago_mensual').value=Total_pago_mensual;


} //* Termina función multiplicar 


//*** Inicia Agregar matrículas ***//
function AgregarMatricula(){
    var id={{$id}};
    var tipo_matricula = document.getElementById("select_matriculas").value; 
    var cantidad = document.getElementById("cantidad").value; 

    if(tipo_matricula==0){
                            modalMensaje('Aviso', 'Debe selecionar una matrícula');
                            return;
                        }
                        
    if(cantidad==""){
                            modalMensaje('Aviso', 'Debe ingresar una cantidad');
                            return;
                        }

    if(cantidad==0){
                            modalMensaje('Aviso', 'Debe ingresar una cantidad mayor a 0');
                            return;
                   }


  openLoading();
  var formData = new FormData();
  formData.append('id_empresa', id);
  formData.append('tipo_matricula', tipo_matricula);
  formData.append('cantidad', cantidad);

  axios.post(url+'/matriculas_detalle/agregar', formData, {
  })
      .then((response) => {
          closeLoading();
          if(response.data.success === 0){
              toastr.error(response.data.message);
          }
          else if(response.data.success === 1){
            agregado();
            resetbtn();
            }
           //**   else if(response.data.success === 2){
           //**   modalMensaje('Matrícula repetida!', 'Para agregar más o eliminarlas seleccione las opciones [Editar o Eliminar]');
           //**   resetbtn();
           //**   return;
           //**   }
         
      })
      .catch((error) => {
        fallo('Error!', 'Error al agregar la matrícula');
       
      });              

}//*** Termina agregar matrículas ***//

//*** Inicia eliminar matrículas ***//

function modalEliminarMatricula(id)
    {
        $('#idborrar').val(id);
        $('#modalEliminarMatricula').modal('show');
    }

function eliminarM(){
    openLoading()
        
    // se envia el ID de la matrícula
    var id = document.getElementById('idborrar').value;

    var formData = new FormData();
    formData.append('id', id);

    axios.post(url+'/matriculas_detalle/eliminar', formData, {
            })
              .then((response) => {
                closeLoading()
                    $('#modalEliminarMatricula').modal('hide');
                    
                    if(response.data.success === 1){
               
                        Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: '¡Matrícula eliminada correctamente!',
                                    showConfirmButton: true,
                                    timer: 3000
                                }).then((result) => {
                                if (result.isConfirmed) {
                                    openLoading();
                                    location.reload();    
                                }
                            });
                            
                            
                               
                        
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
//*** Termina eliminar matrículas ***//


function EspecificarM(id_matriculas_detalle)
    {
        var formData = new FormData();
        formData.append('id_matriculas_detalle', id_matriculas_detalle);
     
        axios.post(url+'/matriculas_detalle/especificar', formData, {
            })

                .then((response) => {
             
                    closeLoading()

                   if (response.data.success === 1) 
                    { console.log(response);

                        if(response.data.matriculasEspecificas!=null)
                        {
                            toastr.warning('La matrícula ya fue específicada');
                            return;
                        }else{
                                $('#modalEspecificarMatricula').css('overflow-y','auto');
                                $('#modalEspecificarMatricula').modal({backdrop:'static',keyboard:false});
                                $("#matrizMatriculas tbody tr").remove();
                                document.getElementById('id_matriculas_detalle').value=response.data.id_matriculas_detalle;
                                window.cantidadMatricula=response.data.cantidad;
                             }
                        
                        
                    }
                    else 
                        {
                            toastMensaje('Error');
                            $('#modalEspecificarMatricula').modal('hide');
                            recargar();
                        }
                })
                .catch((error) => {
                    closeLoading()
                    toastMensaje('error', 'Error');
                });

                
           

    }
function InformacionMatricula(id)
    {
        
         openLoading();
            document.getElementById("formulario-EditarMatricula").reset();
            $("#matrizMatriculasEditar tbody tr").remove();

            axios.post(url+'/matriculas_detalle/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    console.log(response);
                    if(response.data.success === 1){
                        if(response.data.listado.length!=0){
                        window.cantidadMatriculaEditar=response.data.matriculas_detalle.cantidad;
                        //**** Cargar información editar matrícula detalle  ****//
                        $('#modalEditarMatricula').css('overflow-y', 'auto');
                        $('#modalEditarMatricula').modal({backdrop: 'static', keyboard: false})
                        $('#id-editar').val(response.data.matriculas_detalle.id);
                        $('#inicio_operaciones-editar').val(response.data.matriculas_detalle.inicio_operaciones);
                        $('#cantidad-editar').val(cantidadMatriculaEditar);
                        $('#id_matriculas_detalle-editar').val(response.data.matriculas_detalle.id);
                        $('#monto-editar').val(response.data.montoDolar);
                        $('#pago_mensual-editar').val(response.data.Pago_mensualDolar);
                        
                        console.log(cantidadMatriculaEditar);
                        
                        document.getElementById("select_matriculas-editar").options.length = 0;
                        $.each(response.data.tipo_matricula, function( key, val ){
                            if(response.data.id_matriculas == val.id){
                                $('#select_matriculas-editar').append('<option value="' +val.id +'"data-matriculaEditar="'+val.monto+'"data-pagoMEditar="'+val.tarifa+'" selected="selected">'+val.nombre+'</option>');
                            }else{
                                $('#select_matriculas-editar').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                            }
                        });
                        //****  /. Cargar información editar matrícula detalle  ****//

                        //****  Cargar información editar matrícula detalle específico  ****//
                        var infodetalle = response.data.listado;
                        
                        
                        for (var i = 0; i < infodetalle.length; i++) {
                  

                            var markup = "<tr id='"+infodetalle[i].id+"'>"+

                                "<td>"+
                                "<input name='cod_municipaleditararray[]' value='"+infodetalle[i].cod_municipal+"' maxlength='10' class='form-control' type='text'>"+
                                "</td>"+

                                "<td>"+
                                "<input name='codigoeditararray[]' value='"+infodetalle[i].codigo+"' maxlength='400' class='form-control' type='text'>"+
                                "</td>"+

                                "<td>"+
                                "<input name='num_serieeditararray[]' value='"+infodetalle[i].num_serie+"' maxlength='400' class='form-control' type='text'>"+
                                "</td>"+
                                
                                "<td>"+
                                "<input name='direccioneditararray[]' value='"+infodetalle[i].direccion+"' maxlength='400' class='form-control' type='text'>"+
                                "</td>"+

                                "<td>"+
                                "<button type='button' class='btn btn-block btn-danger' onclick='borrarFilaMatrículaEspEditar(this)'><i class='fas fa-trash'></i></button>"+
                                "</td>"+

                                "</tr>";

                            $("#matrizMatriculasEditar tbody").append(markup);
                            
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
  //*** Inicia Editar matrículas ***//
    function EditarMatricula()
    {
            var cantidad_editar = document.getElementById('cantidad-editar').value;
            var tipo_matricula_editar = document.getElementById('select_matriculas-editar').value;
            var id_editar = document.getElementById('id-editar').value; 
           
            
            //Datos matrícula específica
            var id_matricula_detalle_editar = document.getElementById('id_matriculas_detalle-editar').value;
            var cod_municipal_editar = $("input[name='cod_municipaleditararray[]']").map(function(){return $(this).val();}).get();
            var codigo_editar= $("input[name='codigoeditararray[]']").map(function(){return $(this).val();}).get();
            var num_serie_editar = $("input[name='num_serieeditararray[]']").map(function(){return $(this).val();}).get();
            var direccion_editar = $("input[name='direccioneditararray[]']").map(function(){return $(this).val();}).get();


            //**** Validar */
            var hayregistro=0;
            var nRegistro = $('#matrizMatriculasEditar >tbody >tr').length;
            
            let formData = new FormData();

        if (nRegistro > 0){  

        hayregistro = 1;  

        for(var a = 0; a < cod_municipal_editar.length; a++){

            var DatoCod_municipal = cod_municipal_editar[a];
      

            if(DatoCod_municipal == ""){
                            modalMensaje('Código Municipal', 'Debe digitar un código municipal');
                            return;
                        }

        }
    
        for(var b = 0; b < codigo_editar.length; b++){

            var DatoCodigo = codigo_editar[b];


            if(DatoCodigo == ""){
                                modalMensaje('Código', 'Debe digitar un código');
                                return;
                            }

            }
    
        for(var c = 0; c < num_serie_editar.length; c++){

            var DatoNum_serie = num_serie_editar[c];


            if(DatoNum_serie == ""){
                                modalMensaje('Número de serie', 'Debe digitar un número de serie');
                                return;
                            }

            }
            for(var e = 0; e < direccion_editar.length; e++){

                var DatoDireccion = direccion_editar[e];


                if(DatoDireccion == ""){
                                    modalMensaje('Dirección', 'Debe digitar una dirección');
                                    return;
                                }

                }
               
    //**** Fin de validar */
 
    
    }//**Cierre de if nRegistro */

    //*** Cargando los datos de matriculas detalle */
   
    formData.append('hayregistro', hayregistro);  
    formData.append('id_editar', id_editar);
    formData.append('cantidad_editar', cantidad_editar);
    formData.append('tipo_matricula_editar', tipo_matricula_editar); 

    console.log(hayregistro);
    openLoading() 
    // llenar array para enviar
    //*** Cargando los datos de matriculas específicas */
        
        for(var f = 0; f < num_serie_editar.length; f++){
            var id = $("#matrizMatriculasEditar tr:eq("+(f+1)+")").attr('id');
            formData.append('idarray[]', id);
            formData.append('cod_municipal_editar[]', cod_municipal_editar[f]);
            formData.append('codigo_editar[]', codigo_editar[f]);
            formData.append('num_serie_editar[]', num_serie_editar[f]);
            formData.append('direccion_editar[]', direccion_editar[f]);
            console.log(cod_municipal_editar[f],codigo_editar[f],num_serie_editar[f],direccion_editar[f]);
            }
            formData.append('id_matricula_detalle_editar', id_matricula_detalle_editar);
  


           
            axios.post(url+'/matriculas_detalle/editar', formData, {
            })

                .then((response) => {
             
                    closeLoading()

                   if (response.data.success === 1) 
                    {
                      Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Matrícula actualizada correctamente!',
                          showConfirmButton: false,
                          timer: 3000
                        })
                        $('#modalEditarMatricula').modal('hide');
                        recargar();
                    }
                    else 
                    {
                        toastMensaje('Error al actualizar');
                        $('#modalEditarMatricula').modal('hide');
                               recargar();
                    }
                })
                .catch((error) => {
                    closeLoading()
                    toastMensaje('error', 'Error');
                });

}

//*** Finaliza Editar matrículas ***//
function GuardarMatriculaEspecifica(){
    var id_matriculas_detalle=(document.getElementById('id_matriculas_detalle').value);
    var cod_municipal = $("input[name='cod_municipal[]']").map(function(){return $(this).val();}).get();
    var codigo= $("input[name='codigo[]']").map(function(){return $(this).val();}).get();
    var num_serie = $("input[name='num_serie[]']").map(function(){return $(this).val();}).get();
    var direccion = $("input[name='direccion[]']").map(function(){return $(this).val();}).get();


//**** Validar */

var nRegistro = $('#matrizMatriculas >tbody >tr').length;
            if (nRegistro <= 0){

                        modalMensaje('Registro Vacio', 'Debe especificar al menos una matrícula');
                        return;
                }

                   
    for(var a = 0; a < cod_municipal.length; a++){

        var DatoCod_municipal = cod_municipal[a];
      

        if(DatoCod_municipal == ""){
                            modalMensaje('Código Municipal', 'Debe digitar un código municipal');
                            return;
                        }

     }
    
     for(var b = 0; b < codigo.length; b++){

        var DatoCodigo = codigo[b];


        if(DatoCodigo == ""){
                            modalMensaje('Código', 'Debe digitar un código');
                            return;
                        }

        }
    
        for(var c = 0; c < num_serie.length; c++){

            var DatoNum_serie = num_serie[c];


            if(DatoNum_serie == ""){
                                modalMensaje('Número de serie', 'Debe digitar un número de serie');
                                return;
                            }

            }
            for(var e = 0; e < direccion.length; e++){

                var DatoDireccion = direccion[e];


                if(DatoDireccion == ""){
                                    modalMensaje('Dirección', 'Debe digitar una dirección');
                                    return;
                                }

                }
               
    //**** Fin de validar */
 
    let formData = new FormData();

    // llenar array para enviar
        for(var f = 0; f < num_serie.length; f++){
            
            formData.append('cod_municipal[]', cod_municipal[f]);
            formData.append('codigo[]', codigo[f]);
            formData.append('num_serie[]', num_serie[f]);
            formData.append('direccion[]', direccion[f]);
            console.log(cod_municipal[f],codigo[f],num_serie[f],direccion[f]);
            }
      
            formData.append('id_matriculas_detalle',id_matriculas_detalle);
        
        axios.post(url+'/matriculas_detalle_especifico/agregar', formData, {
        })
        .then((response) => {

        if(response.data.success === 1){
         // Matrícula específica agregada
         agregado_matricula_especifica();
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



        
    }//**** Fin de guardar matricula */


//*** Funciones varias ***//
        function VerEmpresa(){
            var id={{$id}};
                     window.location.href="{{ url('/admin/empresas/show') }}/"+id;
        }

        
        function borrarFila(elemento){
            var tabla = elemento.parentNode.parentNode;
            tabla.parentNode.removeChild(tabla);
            cantidadMatricula=cantidadMatricula+1;
        }

        function borrarFilaMatrículaEspEditar(elemento){
            
            var tabla = elemento.parentNode.parentNode;
            tabla.parentNode.removeChild(tabla);
            cantidadMatriculaEditar=cantidadMatriculaEditar-1;
            $('#cantidad-editar').val(cantidadMatriculaEditar);

                var sel = document.getElementById("select_matriculas-editar");  
                var selected = sel.options[sel.selectedIndex];
                var monto_matricula=selected.getAttribute('data-matriculaEditar');
                var tarifa=selected.getAttribute('data-pagoMEditar');
                

                var cantidad = document.getElementById("cantidad-editar").value; 
                

                //Operación
                var monto_total= '$'+ monto_matricula*cantidadMatriculaEditar;
                var Total_pago_mensual= '$'+ tarifa*cantidadMatriculaEditar;
                console.log(monto_matricula,monto_total,cantidad,Total_pago_mensual);
                //Imprimiendo resultado
                document.getElementById('monto-editar').value=monto_total;
                document.getElementById('pago_mensual-editar').value=Total_pago_mensual;   
               
        }

        function verificar(){
            Swal.fire({
                title: '¿Guardar Matrículas?',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Guardar'
            }).then((result) => {
                if (result.isConfirmed) {
                    AgregarMatricula();
                }
            });
        }
            function modalMensaje(titulo, mensaje){
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

        function agregado(){
            Swal.fire({
                title: 'Matrícula agregada',
                text: "Puede modificarla en la opción [Editar]",
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                closeOnClickOutside: false,
                allowOutsideClick: false,
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                        openLoading();
                        location.reload();
                        recargar();
                        f3()
                          

                }
            });
        }
        function agregado_matricula_especifica(){
            Swal.fire({
                title: 'Matrícula específica agregada',
                text: "Puede modificarla en la opción [Editar]",
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                closeOnClickOutside: false,
                allowOutsideClick: false,
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#modalEspecificarMatricula').modal('hide');
                        recargar();
                        f3()
                        location.reload();    
                }
            });
        }
        function fallo(titulo, mensaje){
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

        function recargar()
    {
        var id={{$id}};
        var ruta = "{{ url('/admin/matriculas_detalle/tabla') }}/"+id;
            $('#tablaDatatable').load(ruta);
    }

    function resetbtn()
    {
        document.getElementById('monto_matricula').value=""; 
        document.getElementById('cantidad').value=""; 
        document.getElementById('pago_mensual').value=""; 
        document.getElementById('select_matriculas').value=0; 
    }   
    
    


    function VerMatricula_especifica(id_matriculas_detalle){
         
         openLoading();
         document.getElementById("formulario-VerMatriculaEsp").reset();
         $("#matrizVerMatriculasEsp tbody tr").remove();

         axios.post(url+'/matriculas_detalle/ver_matriculas_especificas',{
                'id': id_matriculas_detalle
            })
            .then((response) => {
                    closeLoading();
                    console.log(response);
                    if(response.data.success === 1){
                    
                        if(response.data.listado.length!=0){//*** If para saber si la matrícula ya fue específicada */

                        $('#modalVerMatriculaEsp').css('overflow-y', 'auto');
                        $('#modalVerMatriculaEsp').modal({backdrop: 'static', keyboard: false})

                        //****  Cargar información editar matrícula detalle específico  ****//
                        var infodetalle = response.data.listado;
                
                        var caracteristica = response.data.mdetalle.nombre;
                        var id_de_matricula = response.data.mdetalle.id_matriculas;
                        
                        if(id_de_matricula=='2'){
                            $('#seccionAlertas').hide();
                        }else{
                            $('#seccionAlertas').show();
                            }

                        document.getElementById('nombre_matricula').innerHTML=caracteristica; 
                        document.getElementById('id_de_matricula').value=id_de_matricula; 

                        for (var i = 0; i < infodetalle.length; i++) {
                 

                            var markup = "<tr id='"+infodetalle[i].id+"'>"+

                                "<td align='center'>"+
                                    infodetalle[i].cod_municipal+
                                "</td>"+

                                "<td align='center'>"+
                                    infodetalle[i].codigo+
                                "</td>"+

                                "<td align='center'>"+
                                    infodetalle[i].num_serie+
                                "</td>"+
                                
                                "<td align='center'>"+
                                    infodetalle[i].direccion+
                                "</td>"+

                                "<td align='center'>"+
                                    caracteristica+
                                "</td>"+

                                "</tr>";

                            $("#matrizVerMatriculasEsp tbody").append(markup);
                            
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
     
</script>

@endsection
