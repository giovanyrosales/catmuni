@extends('backend.menus.superior')

@section('content-admin-css')


    <!-- Finaliza el select live search -->
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />



@stop
<script type="text/javascript">
function f1(){
 
    $('#prueba').hide();
    $('#DivMatriculas').hide();
    $('#aviso').show();
}
function f2(){
    $('#prueba').show();
    $('#aviso').hide();              
}
function f3(){
    $('#prueba').show();
    $('#DivMatriculas').show();          
}
function f4(){
    location.reload();        
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
                    <h3>Agregar Matrículas</h3>
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
                         <div class="card" id="prueba">

                <table class="table" id="matrizMatriculas" style="border: 80px" data-toggle="table">
                        <thead>
                           <tr>
                            <th style="width: 25%; text-align: center">Tipo de Matricula</th>
                            <th style="width: 25%; text-align: center">Cantidad</th>
                            <th style="width: 25%; text-align: center">Monto</th>
                            <th style="width: 15%; text-align: center">Opciones</th>
                           </tr>
                        </thead>
                        <tbody>
                            <td>
                            <select class='form-control seleccion' onchange='multiplicar(this)' style='max-width: 300px' id='select_matriculas'  >
                                <option value='0'> --  Seleccione el tipo matrícula  -- </option>
                                @foreach($matriculas as $data)
                                <option value='{{ $data->id }}' data-matricula='{{ $data->monto }}'> {{ $data->nombre }}</option>
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
                                    <div class="card-header text-success">
                                        <h5> Matrículas registradas para <span class="badge badge-secondary">{{$empresa->nombre}}</span></h5> 
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
                       
                       <!-- /.Inclución de tabla -->
                  
                            <div class="card-footer">
                                <button type="button" class="btn btn-default" onclick="VerEmpresa()">Volver</button>
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
                    <h4 class="modal-title">Eliminar Matrículas</h4>
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

    <!--Inicia Modal Editar Matrícula-->
    
    <div class="modal fade bd-example-modal-lg" id="modalEditarMatricula" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <!--Contenido del modal-->
               <div class="modal-header">
                    <h4 class="modal-title">Editar Matrícula</h4>
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
                                    <table class="table" id="matrizMatriculas" style="border: 80px" data-toggle="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 30%; text-align: center">Tipo de Matricula</th>
                                        <th style="width: 20%; text-align: center">Cantidad</th>
                                        <th style="width: 25%; text-align: center">Monto</th>
                                        <th style="width: 20%; text-align: center">Opciones</th>
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
                                    <input  id='cantidad-editar' onchange='Editarmultiplicar(this)' class='form-control' min='1' style='max-width: 250px' type='number' value=''/>
                                    </td>

                                    <td>
                                    <input  id='monto-editar' class='form-control' disabled min='1' style='max-width: 250px' type='text' value=''/>
                                    </td>

                                    <td>
                                    <button type='button' class='btn btn-block btn-primary'  id="btnAdd" onclick='EditarMatricula()'>
                                        <i class="far fa-edit"></i>
                                        &nbsp;Actualizar
                                    </button>
                                    </td>

                                    </tr>
                                        </tbody>
                                        </table>
                                    <!--/.Campos del modal-->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--/. Form del modal-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>



               <!--Finaliza Contenido del modal-->
            </div>
        </div>
    </div>

    <!--Finaliza Modal Editar Matrícula-->


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

//* Inicia función multiplicar
function multiplicar(){

        var sel = document.getElementById("select_matriculas");  
        var selected = sel.options[sel.selectedIndex];
        var monto_matricula=selected.getAttribute('data-matricula');

        var cantidad = document.getElementById("cantidad").value; 
        console.log(monto_matricula);
        //Operación
        var monto_total= '$'+ monto_matricula*cantidad;

        //Imprimiendo resultado
        document.getElementById('monto_matricula').value=monto_total; 


} //* Termina función multiplicar 

//* Inicia función Editarmultiplicar
function Editarmultiplicar(){

        var sel = document.getElementById("select_matriculas-editar");  
        var selected = sel.options[sel.selectedIndex];
        var monto_matricula=selected.getAttribute('data-matriculaEditar');

        var cantidad = document.getElementById("cantidad-editar").value; 

        //Operación
        var monto_total= '$'+ monto_matricula*cantidad;
        console.log(monto_matricula,monto_total, cantidad);
        //Imprimiendo resultado
        document.getElementById('monto-editar').value=monto_total; 


} //* Termina función Editarmultiplicar

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


  axios.post('/admin/matriculas_detalle/agregar', formData, {
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
            else if(response.data.success === 2){
                modalMensaje('Matrícula repetida!', 'Para agregar más o eliminarlas seleccione las opciones [Editar o Eliminar]');
                resetbtn();
                return;
            }
         
      })
      .catch((error) => {
            fallo();
          closeLoading();
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

    axios.post('/admin/matriculas_detalle/eliminar', formData, {
            })
              .then((response) => {
                closeLoading()
                    $('#modalEliminarMatricula').modal('hide');
                    
               if(response.data.success === 1){
                Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '¡Matrícula eliminada correctamente!',
                          showConfirmButton: false,
                          timer: 3000
                        })
                  recargar();
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

//*** Inicia Editar matrículas ***//

function InformacionMatricula(id)
    {
      openLoading();
            document.getElementById("formulario-EditarMatricula").reset();

            axios.post('/admin/matriculas_detalle/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    console.log(response);
                    if(response.data.success === 1){
                        $('#modalEditarMatricula').modal('show');
                        $('#id-editar').val(response.data.matriculas_detalle.id);
                        $('#cantidad-editar').val(response.data.matriculas_detalle.cantidad);
                        $('#monto-editar').val(response.data.montoDolar);
                        
                        document.getElementById("select_matriculas-editar").options.length = 0;
                        $.each(response.data.tipo_matricula, function( key, val ){
                            if(response.data.id_matriculas == val.id){
                                $('#select_matriculas-editar').append('<option value="' +val.id +'"data-matriculaEditar="'+val.monto+'" selected="selected">'+val.nombre+'</option>');
                            }else{
                                $('#select_matriculas-editar').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                            }
                        });
                     
                    }else{
                        toastr.error('Información solicitada no fue encontrada');
                    }
                    
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
       
    }

    function EditarMatricula()
    {
            var cantidad_editar = document.getElementById('cantidad-editar').value;
            var tipo_matricula_editar = document.getElementById('select_matriculas-editar').value;
            var id_editar = document.getElementById('id-editar').value;
            
           
            openLoading()

              var formData = new FormData();

              formData.append('id_editar', id_editar);
              formData.append('cantidad_editar', cantidad_editar);
              formData.append('tipo_matricula_editar', tipo_matricula_editar);
     
           
            axios.post('/admin/matriculas_detalle/editar', formData, {
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

//*** Funciones varias ***//
        function VerEmpresa(){
            var id={{$id}};
                     window.location.href="{{ url('/admin/empresas/show') }}/"+id;
        }

        function borrarFila(elemento){
            var tabla = elemento.parentNode.parentNode;
            tabla.parentNode.removeChild(tabla);
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
                   
                        recargar();
                        f3()
                }
            });
        }
        function fallo(){
            Swal.fire({
                title: 'Error',
                text: "Error al agregar la matrícula",
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                closeOnClickOutside: false,
                allowOutsideClick: false,
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
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
        document.getElementById('select_matriculas').value=0; 
    }    
     
</script>

@endsection
