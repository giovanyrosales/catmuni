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

<div class="content-wrapper" style="display: none" id="divcontenedor">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4><i class="far fa-plus-square"></i>&nbsp;Especificar Rótulos</h4>
            </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Especificar Rótulos</li>
                        </ol>
                    </div>
        </div>
    </div>
</section>


            
                <table class="table" id="matrizRotulos" style="border: 100px" data-toggle="table">
                        <thead>
                        <tr>                           
                            <th style="width: 15%; text-align: center">Nombre</th>                           
                            <th style="width: 17%; text-align: center">Medidas</th>
                            <th style="width: 15%; text-align: center">Total Medidas</th>
                            <th style="width: 10%; text-align: center">Caras</th>
                            <th style="width: 11%; text-align: center">Tarifa</th>
                            <th style="width: 15%; text-align: center">Pago Mensual</th>
                            <th style="width: 19%; text-align: center">Coordenadas</th>
                          
                        </tr>                        
                        </thead>
                            <tbody id="myTbodyRotulos">
                            </tbody>
                        </table>
                            <br>
                                <button type="button"  class="btn btn-block btn-success" id=" "><i class="far fa-plus-square"></i> &nbsp; Específicar nuevo bus</button>               
                              
                            <br>
                        </div>
                        </form>
                        </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                                <button type="button" onclick="GuardarRotulosEspecificos()" class="btn btn-success float-right">Guardar</button>
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
 
  
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    
    <script type="text/javascript">
        $(document).ready(function(){
        //var id_rotulos_detalle = {{$id_rotulos_detalle}};
           var ruta = "{{ url('/admin/rotulo-detalle/tabla') }}" 
           $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

<script>

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
           

        } 
        
        // filas de la tabla Agrega Buses Específicos
        $(document).ready(function () {
        $("#btnAddrotuloEspecifico").on("click", function () {

            //agrega las filas dinamicamente            
                if(cantidadRotulo == 0)
                {
                    modalMensaje('¡Limite de Rótulos!', 'La cantidad de rótulos detallados llegó a su limite');
                }//cierra if
                
            while(cantidadRotulo > 0)
            {
                
                var markup = "<tr>"+
           
                    "<td>"+
                    "<textarea name='nombre[]' id= 'nombre' class='form-control' rows='2' min='1' style='max-width: 120px' type='text'></textarea>"+                   
                    "</td>"+

                    "<td>"+
                    "<textarea name='medidas[]' id = 'medidas' class='form-control' rows = '2' min='2' style='max-width: 170px' type='text'></textarea>"+
                    "</td>"+

                    "<td>"+
                    "<textarea name='total_medidas[]' id= 'total_medidas'  class='form-control'  min='2' style='max-width: 100px' type='number'>m²</textarea>"+
                    "</td>"+
             
                    "<td>"+
                    "<textarea name='caras[]' id = 'caras' class='form-control' rows= '2' min='2' style='max-width: 100px' type='text'></textarea>"+
                    "</td>"+

                    "<td>"+
                    "<textarea name='tarifa[]' id= 'tarifa' class='form-control' rows = '2'  min='2' style='max-width: 100px' type='text'>$</textarea>"+
                    "</td>"+

                    "<td>"+
                    "<textarea name='total_tarifa[]' id='total_tarifa' class='form-control' rows = '2' min='2' style='max-width: 100px' type='text'>$</textarea>"+
                    "</td>"+

                    "<td>"+
                    "<textarea name='coordenadas_geo[]' id='coordenadas_geo class='form-control' rows = '2'  min='2' style='max-width: 150px' type='text'></textarea>"+
                    "</td>"+

                    "</tr>";
             
                // $("tbody").append(markup);
                $("#matrizRotulos tbody").append(markup);
                cantidadRotulo = cantidadRotulo - 1;
                
                console.log(cantidadRotulo);
            }//cierra while

            
            });
        });
            
    </script>


@stop