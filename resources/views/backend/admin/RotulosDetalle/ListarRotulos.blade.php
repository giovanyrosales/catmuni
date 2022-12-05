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

<style>
/*

#tabla {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-left: 20px;
            margin-right: 20px;
            margin-top: 35px;
            text-align: center;
        }

        #tabla th {
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: #1E1E1E;
            color: #1E1E1E;
            text-align: center;
            font-size: 16px;
        }

*/

  .fake-textarea {
        border: 1px solid black;
        width: 30rem;
        padding: .5rem;
        min-height: 3rem;
      }
      .fake-textarea:empty::before {
        position: absolute;
        content: "Escribe aquí...";
      }
      #description {
        display: none;
      }
</style>

<!-- Contenido Frame Principal -->
<div id="divcontenedor" style="display: none">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h5>Lista De Rótulos Registrados</h1>
          </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Listado de rótulos</li>
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
            <h3 class="card-title">Rótulos Agregados</h3>

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
 <div class="modal" id="modalEspecificarRotulo">
        <div class="modal-fullscreen-xxl-down">
            <div class="modal-content">
              <!--Contenido del modal-->
                <div class="modal-header">
                        <h4 class="modal-title"><i class="far fa-plus-square"></i>&nbsp;Especificar Rotulos</h4>
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
                        <input  id='id_rotulos_detalle' type='hidden'/>
                        <table class="table" id="matrizRotulos" style="border: 200px" data-toggle="table">
                        <thead>
                            <tr>                           
                                <th style="width: 15%; text-align: center">Nombre</th>                           
                                <th style="width: 18%; text-align: center">Medidas</th>
                                <th style="width: 12%; text-align: center">Total Medidas</th>
                                <th style="width: 10%; text-align: center">Caras</th>
                                <th style="width: 10%; text-align: center">Tarifa</th>
                                <th style="width: 10%; text-align: center">Pago Mensual</th>
                                <th style="width: 20%; text-align: center">Coordenadas</th>
                                <th style="width: 20%; text-align: center">Foto</th>                            
                            </tr>                        
                        </thead>
                            <tbody id="myTbodyRotulos"></tbody>
                        </table>
                            <br>
                                <button type="button"  class="btn btn-block btn-success" onclick="modalEspecificar()" id="btnAddrotuloEspecifico"><i class="far fa-plus-square"></i> &nbsp; Específicar Rótulos</button>         
                            <br>
                        </div>
                        </form>
                        </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                                <button type="button" onclick="GuardarRotulosEspecificos()" class="btn btn-success float-right">Guardar</button>
                            </div>
                </div>
           </div>
        </div>
</div>
         
<!--Finaliza Modal Especificar Bus-->

<!--Empieza modal para especificar rótulos-->
<!--NUEVO-->

<div class="modal" id="modalEspecificar">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <!--Contenido del modal-->
                <div class="modal-header">
                        <h4 class="modal-title"><i class="far fa-plus-square"></i>&nbsp;DATOS</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            <form id="formulario-Especificar">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nombre:</label>
                    <input class="form-control" id="rotulo_nombre" name="rotulo_nombre[]">
                   
                </div>
            </div>
        
            <div class="col-md-6">
                <div class="form-group">
                    <label>Medidas:</label>
                    <input class="form-control" id="medidas_rotulo" name="medidas_rotulo[]">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Total Medidas:</label>
                    <input class="form-control" id="total_medidas_rotulo" name="total_medidas_rotulo[]">
                </div>
            </div>
      
            <div class="col-md-6">
                <div class="form-group">
                    <label>Caras:</label>
                    <input class="form-control" id="caras_rotulo" name="caras_rotulo[]">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tarifa:</label>
                    <input class="form-control" id="tarifa_rotulo" name="tarifa_rotulo[]">
                </div>
            </div>
      
            <div class="col-md-6">
                <div class="form-group">
                    <label>Pago Mensual:</label>
                    <input class="form-control" id="pago_mensual_rotulo" name="pago_mensual_rotulo[]">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Coordenadas:</label>
                    <input class="form-control" id="coordenadas_rotulo" name="coordenadas_rotulo[]">
                </div>
            </div>
      
            <div class="col-md-6">
                <div class="form-group">
                    <label>Foto:</label>
                    <input type="file" class="form-control-file" id="foto_rotulo" name="foto_rotulo">
                </div>
            </div>
        </div>
    </div>
        </form> 

    <div class="card-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
        <button type="button"  id="btnCopiarEspecificos" onclick="" class="btn btn-success float-right">Guardar</button>
    </div>

<!-- Termina modal para especifica rótulos-->




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
 
 

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    
    
<script type="text/javascript">
        $(document).ready(function(){
            var ruta = "{{ url('/admin/rotulo-detalle/tabla') }}";
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";
        });
</script>

<script>


        function EspecificarRotulo(id_rotulos_detalle)
        {
            console.log(id_rotulos_detalle)

            var formData = new FormData();
                formData.append('id_rotulos_detalle', id_rotulos_detalle);

                
            axios.post('/admin/rotulo_detalle/especifico', formData, {
            })
           
                .then((response) => {
            
                    closeLoading()

                    if (response.data.success === 1) 
                    { 
                        console.log(response);

                        if(response.data.rotulosEspecificos!=null)
                        {
                            toastr.warning('El rótulo ya fue específicado');
                            return;
                        }else{

                                $('#modalEspecificarRotulo').css('overflow-y','auto');
                                $('#modalEspecificarRotulo').modal({backdrop:'static',keyboard:false});
                                $("#matrizRotulos tbody tr").remove();
                                document.getElementById('id_rotulos_detalle').value=response.data.id_rotulos_detalle;
                                window.cantidadRotulo = response.data.cantidad_rotulos;
                            }
                    }
                })
                .catch((error) => {
                    closeLoading()
                    toastMensaje('error', 'Error');
                });
            
        } 
        

        function rotulos_especificos(e)
        {

            var table = e.parentNode.parentNode; // fila de la tablas

            var nombre = table.cells[0].children[0]; //
            var medidas = table.cells[1].children[0]; //
            var total_medidas = table.cells[2].children[0]; //
            var caras = table.cells[3].children[0];
            var tarifa = table.cells[4].children[0]; 
            var pago_mensual = table.cells[5].children[0];
            var coordenadas = table.cells[6].children[0]; 
            var foto_rotulo = table.cells[7].children[0]; 
           

        }

     
        function GuardarRotulosEspecificos()
        {

            var id_rotulos_detalle=(document.getElementById('id_rotulos_detalle').value);    
            var nombre = $("textarea[name='nombre[]']").map(function(){return $(this).val();}).get();
            var medidas = $("textarea[name='medidas[]']").map(function(){return $(this).val();}).get();
            var total_medidas = $("textarea[name='total_medidas[]']").map(function(){return $(this).val();}).get();
            var caras = $("textarea[name='caras[]']").map(function(){return $(this).val();}).get();
            var tarifa = $("textarea[name='tarifa[]']").map(function(){return $(this).val();}).get();
            var total_tarifa = $("textarea[name='total_tarifa[]']").map(function(){return $(this).val();}).get();
            var coordenadas_geo = $("textarea[name='coordenadas_geo[]']").map(function(){return $(this).val();}).get();
            var foto_rotulo = $("textarea[name='coordenadas_geo[]']").get();
          
            //**** Validar */

            var nRegistro = $('#matrizRotulos >tbody >tr').length;

            if (nRegistro <= 0)
            {
                    modalMensaje('Registro Vacio', 'Debe especificar al menos una matrícula');
                    return;
            }          
            //**** Fin de validar */

            let formData = new FormData();                       
            // llenar array para enviar
            for(var j = 0; j < nombre.length; j++)
            {
               
                    formData.append('nombre[]', nombre[j]);  
                    formData.append('medidas[]', medidas[j]);
                    formData.append('total_medidas[]', total_medidas[j]);
                    formData.append('caras[]', caras[j]);
                    formData.append('tarifa[]', tarifa[j]);
                    formData.append('total_tarifa[]', total_tarifa[j]);
                    formData.append('coordenadas_geo[]', coordenadas_geo[j]);         
                               
            }

                formData.append('id_rotulos_detalle', id_rotulos_detalle);
         
                    axios.post('/admin/rotulos_detalle_especifico/agregar', formData, {
                    })
                    .then((response) => {
                       console.log(response)
                       
                if(response.data.success === 1)
                {                             
                    // Rótulo agregado
                    agregado_rotulos_especifico();
                }  

                else{
                    // error al crear
                        toastr.error('Error al agregar rótulo');
                    }

                })
                    .catch((error) => {
                    toastr.error('Error!');
                    closeLoading();

                });

        }//**** Fin de guardar bus */
 
  
        function agregado_rotulos_especifico()
        {
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
                        $('#modalEspecificarRotulo').modal('hide');
                            recargar();
                            
                    }
                });
        }


        function recargar()
        {
            var ruta = "{{ url('/admin/rotulo-detalle/tabla') }}";
                $('#tablaDatatable').load(ruta);
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

</script>

<script>
        function VistaRotulo(id_rotulos_detalle)
        {
            openLoading();
            window.location.href="{{ url('/admin/rotulos_detalle/show/') }}/"+id_rotulos_detalle;

        }
    </script>

    <script>
        function Especificar()
        {
            //console.log(id_rotulos_detalle);
            var formData = new FormData();
                formData.append('id_rotulos_detalle', id_rotulos_detalle);

            axios.post('/admin/rotulo/especifico',  formData, {
            })
           
                .then((response) => {
            
                    closeLoading()

                    if (response.data.success === 1) 
                    { 
                        console.log(response);

                        if(response.data.rotulosEspecificos!=null)
                        {
                            toastr.warning('El rótulo ya fue específicada');
                            return;
                        }
                        
                        else{
                      
                                window.location.href="{{ url('/admin/rotulo_detalle/especifico') }}/"+id_rotulos_detalle;
                              
                            }
                        }
                })
                .catch((error) => {
                    closeLoading()
                    toastMensaje('error', 'Error');
                });
            
            
        }
 

//CÓDIGO PARA NUEVA FORMA DE ESPECIFICAR RÓTULOS-

        function modalEspecificar()
        {
            $('#modalEspecificar').modal('show');
            
        }

            // filas de la tabla Agrega Buses Específicos 
            $(document).ready(function () {
            $("#btnCopiarEspecificos").on("click", function () 
            {

                
                    
                       
                for (var i = 0; i < cantidadRotulo.length; i++) {

                            while(cantidadRotulo>0){

                    var markup = "<tr>"+
                    
                                "<td>"+
                                "<input name='nombre_rotulos[]'  id= 'nombre_rotulos' class='form-control' style='max-width: 200px' type='text' autocomplete='off' disabled>"+                   
                                "</td>"+

                                "<td>"+
                                "<input name='rotulos_medidas[]' id = 'rotulos_medidas' class='form-control' style='max-width: 230px' type='text' autocomplete='off' disabled>"+
                                "</td>"+

                                "<td>"+
                                "<input name='total_medidas[]' id= 'total_medidas'  class='form-control'  style='max-width: 135px' type='text' autocomplete='off' disabled>"+
                                "</td>"+
                        
                                "<td>"+
                                "<input name='caras[]' id = 'caras' class='form-control' style='max-width: 135x' type='text' autocomplete='off' disabled>"+
                                "</td>"+

                                "<td>"+
                                "<input name='tarifa[]' id= 'tarifa' class='form-control' style='max-width: 120px' type='text' autocomplete='off' disabled>"+
                                "</td>"+

                                "<td>"+
                                "<input name='total_tarifa[]' id='total_tarifa' class='form-control'  style='max-width: 120px' type='text' autocomplete='off' disabled>"+
                                "</td>"+

                                "<td>"+
                                "<input name='coordenadas_geo[]' id= 'coordenadas_geo' class='form-control'  style='max-width: 250px' type='text' autocomplete='off' disabled>"+
                                "</td>"+

                                "<td>"+
                                "<input  name='foto_rotulo[]' id= 'coordenadas_geo' class='form-control-file'  style='max-width: 270px' type='file' disabled>"+
                                "</td>"+

                                "</tr>";        
                                


                            // $("tbody").append(markup);
                            $("#matrizRotulos tbody").append(markup);
                            cantidadRotulo = cantidadRotulo - 1;
                        
                           // console.log(cantidadRotulo);
                   
           
   
                        var rotulo_nombre = $("input[name='rotulo_nombre[]']").map(function(){return $(this).val();}).get();
                        var medidas_rotulo = $("input[name='medidas_rotulo[]']").map(function(){return $(this).val();}).get();
                        var total_medidas_rotulo = $("input[name='total_medidas_rotulo[]']").map(function(){return $(this).val();}).get();
                        var caras_rotulo = $("input[name='caras_rotulo[]']").map(function(){return $(this).val();}).get();
                        var tarifa_rotulo = $("input[name='tarifa_rotulo[]']").map(function(){return $(this).val();}).get();
                        var pago_mensual_rotulo = $("input[name='pago_mensual_rotulo[]']").map(function(){return $(this).val();}).get();
                        var coordenadas_rotulo = $("input[name='coordenadas_rotulo[]']").map(function(){return $(this).val();}).get();
                

                /* 
                       
                            var id_rotulos_detalle=(document.getElementById('id_rotulos_detalle').value);    
                            var rotulo_nombre = document.getElementById('rotulo_nombre').value;
                            var medidas_rotulo = document.getElementById('medidas_rotulo').value;
                            var total_medidas_rotulo = document.getElementById('total_medidas_rotulo').value;
                            var caras_rotulo = document.getElementById('caras_rotulo').value;
                            var tarifa_rotulo = document.getElementById('tarifa_rotulo').value;
                            var pago_mensual_rotulo = document.getElementById('pago_mensual_rotulo').value;
                            var coordenadas_rotulo = document.getElementById('coordenadas_rotulo').value;

                        //console.log(id_rotulos_detalle);
               */       
                        /*
                            if(rotulo_nombre === '')
                            {
                                toastr.error('El campo nombre es obligatorio');
                                return;
                            }

                            if (medidas_rotulo === '')
                            {
                                toastr.error('Las medidas del rótulo son obligatorias');
                                return;
                            }

                            if (total_medidas_rotulo === '')
                            {
                                toastr.error('El total de medidas es obligatorio');
                                return;
                            }

                            if (caras_rotulo === '')
                            {
                                toastr.error('El total de caras es obligatorio');
                                return;
                            }

                            if (tarifa_rotulo === '')
                            {
                                toastr.error('La tarifa es obligatoria')
                                return;
                            }

                            if (pago_mensual_rotulo === '')
                            {
                                toastr.error('El pago mensual es obligatorio')
                                return;
                            }
                
                        */    

                            $('#nombre_rotulos').val(rotulo_nombre[i]);
                            $('#rotulos_medidas').val(medidas_rotulo[i]);
                            $('#total_medidas').val(total_medidas_rotulo[i]);
                            $('#caras').val(caras_rotulo[i]);
                            $('#tarifa').val(tarifa_rotulo[i]);
                            $('#total_tarifa').val(pago_mensual_rotulo[i]);
                            $('#coordenadas_geo').val(coordenadas_rotulo[i]);
 }//cierra while 
                        /*  
                            $('#nombre_rotulos').val(rotulo_nombre);
                            $('#rotulos_medidas').val(medidas_rotulo);
                            $('#total_medidas').val(total_medidas_rotulo);
                            $('#caras').val(caras_rotulo);
                            $('#tarifa').val(tarifa_rotulo);
                            $('#total_tarifa').val(pago_mensual_rotulo);
                            $('#coordenadas_geo').val(coordenadas_rotulo);
                      */

                        cantidadRotulo = cantidadRotulo - 1;
                             
                    }

                        document.getElementById('rotulo_nombre').value = "";
                        document.getElementById('medidas_rotulo').value = "";
                        document.getElementById('total_medidas_rotulo').value = "";
                        document.getElementById('caras_rotulo').value = "";
                        document.getElementById('tarifa_rotulo').value = "";
                        document.getElementById('pago_mensual_rotulo').value = "";
                        document.getElementById('coordenadas_rotulo').value = "";
                     
                      
                                
                    });
        });
     

     
/*
        function limpiar()
        {
             // $('#modalEspecificar').reset();

              document.getElementById("formulario-Especificar").reset();
        }
*/
        
        function calculo()
        {

            var fondo_fiesta = 0.05;
            var tarifa_sin_fondo = '';
            var pago_mensual = '';

            var total_medidas = document.getElementById('total_medidas').value;
            var caras = document.getElementById('caras').value;

            var tarifa_sin_fondo
        

        }
//CÓDIGO PARA NUEVA FORMA DE ESPECIFICAR RÓTULOS--TERMINA

        </script>
@stop