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
                <table class="table" id="matrizRotulos" style="border: 100px" data-toggle="table">
                        <thead>
                        <tr>                           
                            <th style="width: 15%; text-align: center">Nombre</th>                           
                            <th style="width: 19%; text-align: center">Medidas</th>
                            <th style="width: 14%; text-align: center">Total Medidas</th>
                            <th style="width: 10%; text-align: center">Caras</th>
                            <th style="width: 10%; text-align: center">Tarifa</th>
                            <th style="width: 13%; text-align: center">Pago Mensual</th>
                            <th style="width: 20%; text-align: center">Coordenadas</th>
                            <th style="width: 24%; text-align: center">Foto</th>
                        </tr>                        
                        </thead>
                            <tbody id="myTbodyRotulos">
                            </tbody>
                        </table>
                            <br>
                                <button type="button"  class="btn btn-block btn-success" id="btnAddrotuloEspecifico"><i class="far fa-plus-square"></i> &nbsp; Específicar nuevo bus</button>               
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
            var ruta = "{{ url('/admin/rotulo-detalle/tabla') }}";
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";
        });
</script>

<script>

        function EspecificarRotulo(id_rotulos_detalle)
        {
           
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
                            toastr.warning('El rótulo ya fue específicada');
                            return;
                        }else{

                                $('#modalEspecificarRotulo').css('overflow-y','auto');
                                $('#modalEspecificarRotulo').modal({backdrop:'static',keyboard:false});
                                $("#matrizRotulos tbody tr").remove();
                                document.getElementById('id_rotulos_detalle').value=response.data.id_rotulos_detalle;
                                window.cantidadRotulo = response.data.cantidad_rotulos;
                            }
                        
                    }
                    else 
                        {
                            toastMensaje('Error');
                            $('#modalEspecificarRotulo').modal('hide');
                            recargar();
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
            var foto = table.cells[7].children[0]; 


        }

        // filas de la tabla Agrega Buses Específicos
        $(document).ready(function () {
        $("#btnAddrotuloEspecifico").on("click", function () {

            //agrega las filas dinamicamente
            
                if(cantidadRotulo == 0)
                {
                    modalMensaje('¡Limite de Buses!', 'La cantidad de buses detallados llegó a su limite');
                }//cierra if
                
            while(cantidadRotulo > 0)
            {
                
                var markup = "<tr>"+
           
                    "<td>"+
                    "<textarea name='nombre[]'  class='form-control' rows='2' min='1' style='max-width: 120px' type='text'></textarea>"+                   
                    "</td>"+

                    "<td>"+
                    "<textarea name='medidas[]'  class='form-control' rows = '2' min='2' style='max-width: 170px' type='text'></textarea>"+
                    "</td>"+

                    "<td>"+
                    "<textarea name='total_medidas[]'  class='form-control'  min='2' style='max-width: 100px' type='number'>m²</textarea>"+
                    "</td>"+
             
                    "<td>"+
                    "<textarea name='caras[]'  class='form-control' rows= '2' min='2' style='max-width: 100px' type='text'></textarea>"+
                    "</td>"+

                    "<td>"+
                    "<textarea name='tarifa[]'  class='form-control' rows = '2'  min='2' style='max-width: 100px' type='text'>$</textarea>"+
                    "</td>"+

                    "<td>"+
                    "<textarea name='total_tarifa[]'  class='form-control' rows = '2' min='2' style='max-width: 100px' type='text'>$</textarea>"+
                    "</td>"+

                    "<td>"+
                    "<textarea name='coordenadas_geo[]'  class='form-control' rows = '2'  min='2' style='max-width: 150px' type='text'></textarea>"+
                    "</td>"+

                    "<td>"+
                    "<input type='file' name = 'foto_rotulo[]' id = 'foto_rotulo[]' class='form-control' accept='image/jpeg, image/jpg, image/png '>"+
                    "</td>"+
                  
                    "</tr>";
             
                // $("tbody").append(markup);
                $("#matrizRotulos tbody").append(markup);
                cantidadRotulo = cantidadRotulo - 1;
                
                console.log(cantidadRotulo);
            }//cierra while

            
            });
        });


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
            var foto_rotulo = (document.getElementById('foto_rotulo'));
         
          
            //**** Validar */

            var nRegistro = $('#matrizRotulos >tbody >tr').length;

            if (nRegistro <= 0)
            {

                        modalMensaje('Registro Vacio', 'Debe especificar al menos una matrícula');
                        return;
            }

            for(var a = 0; a < nombre.length; a++)
            {

                var DatoNombre = nombre[a];


                if(DatoNombre == "")
                {
                    modalMensaje('Código Municipal', 'Debe digitar un código municipal');
                    return;
                }

            }

            for(var b = 0; b < medidas.length; b++)
            {

                var DatoMedidas = nombre[b];


                if(DatoMedidas == "")
                {
                    modalMensaje('Nombre', 'Debe digitar un nombre de la unidad');
                    return;
                }

            }            


            for(var c = 0; c < total_medidas.length; c++)
            {

                var DatoTotalMedidas = total_medidas[c];


                if(DatoTotalMedidas == "")
                {
                    modalMensaje('Ruta', 'Debe digitar una ruta');
                    return;
                }

            }

            
            for(var e = 0; e < caras.length; e++)
            {

                var DatoCaras = caras[e];


                if(DatoCaras == "")
                {
                    modalMensaje('Teléfono', 'Debe digitar un número de teléfono');
                    return;
                }

            }

            for(var f = 0; f < tarifa.length; f++)
            {

                var DatoTarifa = tarifa[f];


                if(DatoTarifa == "")
                {
                    modalMensaje('Teléfono', 'Debe digitar un número de teléfono');
                    return;
                }

            }

            for(var g = 0; g < total_tarifa.length; g++)
            {

                var DatoTotalTarifa = total_tarifa[g];


                if(DatoTotalMedidas == "")
                {
                    modalMensaje('Teléfono', 'Debe digitar un número de teléfono');
                    return;
                }

            }

            for(var h = 0; h < coordenadas_geo.length; h++)
            {

                var DatoCoordenadasGeo = coordenadas_geo[h];


                if(DatoCoordenadasGeo == "")
                {
                    modalMensaje('Teléfono', 'Debe digitar un número de teléfono');
                    return;
                }

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
                
               
                formData.append('foto_rotulo', foto_rotulo[j]);



                console.log(nombre[j],medidas[j],total_medidas[j],foto_rotulo[j]);
             

            }

                    formData.append('id_rotulos_detalle', id_rotulos_detalle);

                    axios.post('/admin/rotulos_detalle_especifico/agregar', formData, {
                    })
                    .then((response) => {

                if(response.data.success === 1)
                {
                // Matrícula específica agregada
                    agregado_rotulos_especifico();
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

</script>
@stop