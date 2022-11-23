@extends('backend.menus.superior')



@section('content-admin-css')

  <!-- Para el select live search -->
    <link href="{{ asset('css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">
  <!--Finaliza el select live search -->

    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/main.css') }}" type="text/css" rel="stylesheet" />
  
<style>

        #tres {
            overflow: hidden;
              }
        #tabla {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-left: 20px;
            margin-right: 20px;
            margin-top: 35px;
            text-align: center;
        }

        #tabla td{
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 15px;
        }

        th{
            border: 0px solid #ddd;
            padding: 3px;
            text-align: left;
            background-color: #F8F9F9;
            color: #1E1E1E;
        }

        #tbempresa{
            border: 0px solid #ddd;
            padding: 3px;
            text-align: left;
            background-color: #F2F2F2;
            color: #1E1E1E;
        }

        #tabla th {
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: #1E1E1E;
            color: #ddd;
            text-align: center;
            font-size: 16px;
        }

        .texto{
            margin-left: 12px;
            display: block;
            margin: 2px 0 0 0;
            font-size: small;
        }
        #uno{
                font-size: 15px;
        }
        #dos{
                font-size: 13px;
        }
        #especial{
            background-color: #E9F1FF;
            color: #1E1E1E;
        }
        #especial2{
            background-color: #E2FFED;
            color: #1E1E1E;
        }

        .badge-inverse {
        background-color: #0FC2EE;
        color: #ffffff;

        }

        .badge-inverse:hover {
        background-color: #108F27;
        color: #ffffff;
        }

/** CSS para btn flotante */
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
#btn-mas{
    display: none;
}
#contenedor{
    position: fixed;
    bottom: 20px;
    right: 20px;
    float: left;
}
.redes a, .btn-mas label{
    display: block;
    text-decoration: none;
    background: #08BE4D;
    color: #fff;
    width: 55px;
    height: 55px;
    line-height: 55px;
    text-align: center;
    border-radius: 50%;
    box-shadow: 0px 1px 10px rgba(0,0,0,0.4);
    transition: all 500ms ease;
}
.redes a:hover{
    background: #fff;
    color: #C20E0E;
}
.redes a{
    margin-bottom: -15px;
    opacity: 0;
    visibility: hidden;
}
#btn-mas:checked~ .redes a{
    margin-bottom: 10px;
    opacity: 1;
    visibility: visible;
}
.btn-mas label{
    cursor: pointer;
    background: #118EE5; /** Color del botón */
    font-size: 23px;
}
#btn-mas:checked ~ .btn-mas label{
    transform: rotate(135deg);
    font-size: 25px;
}
       
</style>
@stop    

<!-- Inicia content-wrapper-->
<div class="content-wrapper" style="display: none" id="divcontenedor">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                      <h5 class="modal-title"><i class="fas fa-search-dollar"></i>&nbsp;Buscador de obligaciones tributarias.</span>
                      </h5>
                    </div><!-- Col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Obligaciones tributarias.</li>
                        </ol>
                    </div><!-- /.col -->
            </div>
        </div>
    </section>
<!-- finaliza content-wrapper-->

<!--Inicia card-projectcard-project-->
<div class="card card-projectcard-project" style="width: 98%; height:25%; margin: 0 auto; -webkit-border-radius: 5px 5px 5px 5px;border-radius: 5px 5px 5px 5px;">
      <div class="progress" style="margin: 0 auto;width: 100%;height:5px;">
        <div class="progress-bar bg-success" role="progressbar" style="width: 25%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
  <div class="card-body">

    <!-- Inicia Contenido-->
    <div class="col-md-6" style="width: 95%; height:50%; margin: 0 auto;">
        <div class="input-group mb-3">
            <select 
            required 
            onchange="buscar_obligaciones_tributarias()" 
            class="form-control selectpicker show-tick" 
            data-style="btn btn-outline-success"  
            data-show-subtext="false" 
            data-live-search="true" 
            id="select-contribuyente" 
            title="Seleccione un contribuyente."
            >
                @foreach($contribuyentes as $contribuyente)
                  <option value="{{ $contribuyente->id }}"> {{ $contribuyente->nombre }}&nbsp;{{ $contribuyente->apellido }}&nbsp;(&nbsp;DUI:&nbsp;{{ $contribuyente->dui }}&nbsp;)</option>
                @endforeach 
            </select> 
          <div class="input-group-append">
            <label class="input-group-text"  for="inputGroupSelect02"><i class="fas fa-search"></i>&nbsp;Buscar </label>
          </div>
        </div>
    </div><!-- /.col-md-6 -->

  </div>
</div>
 <!-- Finaliza Contenido card-project-->

<!-- Inicia Contenido IMG-->
    <div class="card" style="margin: 5 auto;width: 98%;">
      <div class="progress" style="margin: 0 auto;width: 100%;height:5px;">
        <div class="progress-bar bg-secondary" role="progressbar" style="width:10%; height:100%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
        <div class="card-body"  >
        <!-- Inicia contenido-->  
          <img src="{{ asset('/img/063.svg') }}" id="img_contribuyente" style="display: block;margin: 0px auto;width: 30%; height:30%;" >
          <!--Tarjeta para empresas-->
          <div class="card border-primary mb-3" id="tarjeta_empresas_registradas">
                    <div class="card-header"  style="background-color:#14A3D9; color: #FFFFFF;">
                        <h5><span class="badge badge-pill badge-light"><i class="fas fa-building"></i></span>&nbsp;<b>Empresas registradas</b></h5>
                    </div>
                      <div class="card-body">
                          <!--Tabla 12-->
                          <table class="table" id="matriz_ver_empresas" style="border: 100px;" data-toggle="table">
                                    <thead>
                                    <tr id="tbempresa">      
                                      <td style="width: 15%; text-align: center;font-weight: 700;">Opciones</td>                     
                                      <td style="width: 25%; text-align: center;font-weight: 700;">Nombre</td>
                                      <td style="width: 15%; text-align: center;font-weight: 700;">Giro Comercial</td>
                                      <td style="width: 15%; text-align: center;font-weight: 700;">Estado</td>
                                      <td style="width: 15%; text-align: center;font-weight: 700;">Estado Moratorio</td>
                                      <td style="width: 15%; text-align: center;font-weight: 700;">N° Ficha</td>
                                      </tr>
                                    </thead>
                                    <tbody>
                                
                                    </tbody>
                          </table>
                      </div>
            </div>

            <!--Tarjeta para Buses-->
            <div class="card mb-3" id="tarjeta_buses_registradas">
                    <div class="card-header bg-success">
                        <h5><span class="badge badge-pill badge-light"><i class="fas fa-bus"></i>&nbsp;</span>&nbsp;<b>Buses registrados</b></h5>
                    </div>
                      <div class="card-body">
                          <!--Tabla 12-->
                          <table class="table" id="matriz_ver_buses" style="border: 100px;" data-toggle="table">
                                    <thead>
                                      <tr>  
                                        <th style="width: 15%; text-align: center;font-weight: 700;">Opciones</th>                         
                                        <th style="width: 25%; text-align: center;font-weight: 700;">Empresa</th>
                                        <th style="width: 15%; text-align: center;font-weight: 700;">Cantidad</th>
                                        <th style="width: 15%; text-align: center;font-weight: 700;">Estado</th>
                                        <th style="width: 15%; text-align: center;font-weight: 700;">N° Ficha</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                
                                    </tbody>
                          </table>
                      </div>
            </div>

                        <!--Tarjeta para Buses-->
                        <div class="card border-success mb-3" id="tarjeta_rotulos_registrados">
                    <div class="card-header" style="background-color:#DE4C35; color: #FFFFFF;">
                        <h5><span class="badge badge-pill badge-dark"><i class="fas fa-sign"></i>&nbsp;</span>&nbsp;<b>Rótulos registrados</b></h5>
                    </div>
                      <div class="card-body">
                          <!--Tabla 12-->
                          <table class="table" id="matriz_ver_rotulos" style="border: 100px;" data-toggle="table">
                                    <thead>
                                    <tr>  
                                      <th style="width: 15%; text-align: center;font-weight: 700;">Opciones</th>                         
                                      <th style="width: 25%; text-align: center;font-weight: 700;">Rótulo</th>
                                      <th style="width: 15%; text-align: center;font-weight: 700;">Cantidad</th>
                                      <th style="width: 15%; text-align: center;font-weight: 700;">Estado</th>
                                      <th style="width: 15%; text-align: center;font-weight: 700;">N° Ficha</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                
                                    </tbody>
                          </table>
                      </div>
            </div>

        <!-- Finaliza contenido-->
        </div>
      </div>
    </div>
<!-- Finaliza Contenido IMG-->


<!-- seccion botón flotante -->
<div id="contenedor">
    <input type="checkbox" id="btn-mas">
    <div class="redes">
        <a class="fas fa-file-signature" id="solvencia"  data-toggle="tooltip" data-placement="left" title="Generar solvencia" onclick="Generar_solvencia()"></a>
        <a class="fas fa-file-invoice" id="constancia_simple" data-toggle="tooltip" data-placement="left" title="Generar constancia simple" onclick="Generar_constancia_simple()"></a>
    </div>
    <div class="btn-mas">
        <label for="btn-mas" class="fa fa-plus"></label>
    </div>
</div>
<!--Fin seccion botón flotante -->


<!-- Cerrando el content-wrapper-->
      </div>
      </div>
       </div>
      <!-- /.card -->
      </form>
      <!-- /form -->
      </div>
    <!-- /.container-fluid -->
    </section>
<!-- Finaliza Formulario -->



@extends('backend.menus.footerjs')

@section('archivos-js')

<!-- Para el select live search -->
    <script src="{{ asset('js/bootstrap-select.min.js') }}" type="text/javascript"></script>
<!-- Finaliza el select live search -->

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/jquery.simpleaccordion.js') }}"></script>



<script type="text/javascript">

$(document).ready(function(){
  document.getElementById("divcontenedor").style.display = "block"; 
  
  $('#tarjeta_empresas_registradas').hide();
  $('#tarjeta_buses_registradas').hide();
  $('#tarjeta_rotulos_registrados').hide();
  $('#contenedor').hide();
  $('#constancia_simple').hide();

  //** Tooltips de botón flotante */
  $('[data-toggle="tooltip"]').tooltip();

 });

function buscar_obligaciones_tributarias(){
          openLoading();
         $("#matriz_ver_empresas tbody tr").remove();
         $("#matriz_ver_buses tbody tr").remove();
         $("#matriz_ver_rotulos tbody tr").remove();

          var id_contribuyente = document.getElementById('select-contribuyente').value;
          
          var formData = new FormData();
          formData.append('id_contribuyente', id_contribuyente);
          
          axios.post('/admin/buscar/obligaciones_tributarias', formData, {
           })
         .then((response) => {
        
        if(response.data.success === 2)
        {
            $('#constancia_simple').show();
            $('#contenedor').show();
            $('#solvencia').hide();
        }

        if(response.data.success === 1)
                {
                    
                    Swal.fire({
                          position:'top-end',
                          icon: 'success',
                          title: '¡Información encontrada!',
                          showConfirmButton: false,                     
                        })
                        $('#img_contribuyente').hide();
                        $('#constancia_simple').hide();

                        if(response.data.buses_reg==0){
                          $('#tarjeta_buses_registradas').hide();
                        }else{
                                $('#tarjeta_buses_registradas').show();
                              }
                        if(response.data.empresas_reg==0){
                          $('#tarjeta_empresas_registradas').hide();
                        }else{
                                $('#tarjeta_empresas_registradas').show();
                            }
                        
                        if(response.data.rotulos_reg==0){
                          $('#tarjeta_rotulos_registrados').hide();
                        }else{
                                $('#tarjeta_rotulos_registrados').show();
                            }

                        if(response.data.Solvencia===1){
                            $('#contenedor').show();
                            $('#solvencia').show();
                        }else{
                            $('#contenedor').hide();
                             }
                        

                            //**** Cargar información empresas registradas ****//
                            var infodetalle = response.data.empresas_registradas;
                            
                            
                            for (var i = 0; i < infodetalle.length; i++) {

                            var markup = `<tr id="${infodetalle[i].id}">

                            <td align="center">               
                            <button type="button" class="btn btn-primary btn-xs" onclick="VerEmpresa(${infodetalle[i].id})">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;<b>VER</b>&nbsp;&nbsp;</button>
                            </td>

                            <td align="center">
                            <b>${infodetalle[i].nombre}</b>
                            </td>                     

                            <td align="center">
                            ${infodetalle[i].nombre_giro}
                            </td>

                            <td align="center">
                            ${infodetalle[i].id_estado_empresa!=1? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Cerrado</span>'}
                            </td>

                            <td align="center">
                            ${infodetalle[i].estado_moratorio_empresas!='Mora'? '<span class="badge badge-inverse"><i class="fas fa-check-circle"></i></span>' : '<span class="badge badge-warning"><i class="fas fa-times-circle"></i> Mora</span>'}
                            </td>

                            <td align="center">
                            <span class="badge badge-pill badge-dark">${infodetalle[i].num_tarjeta}</span>
                            </td>

                           </tr>`;

                            $("#matriz_ver_empresas tbody").append(markup);
                            
                            }//*Cierre de for empresas

                            //****  Cargar información buses registradas ****//
                            var infodetalle_bus = response.data.buses_registrados;

                            for (var i = 0; i < infodetalle_bus.length; i++) {

                             var markup = `<tr id="${infodetalle_bus[i].id}">
                            
                                <td align="center">               
                                      <button type="button" class="btn btn-success btn-xs" onclick="VerBuses(${infodetalle_bus[i].id})">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;<b>VER</b>&nbsp;&nbsp;</button>
                                </td>

                                <td align="center">
                                ${infodetalle_bus[i].nom_empresa}
                                </td>
                            
                                <td align="center">
                                ${infodetalle_bus[i].cantidad}
                                </td>
                                
                                <td align="center">
                        
                                ${infodetalle_bus[i].id_estado_bus!=1? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Cerrado</span>'}

                                </td>

                                <td align="center">
                                <span class="badge badge-pill badge-dark"> ${infodetalle_bus[i].nFicha} </span>
                                </td>

                                </tr>`;

                            $("#matriz_ver_buses tbody").append(markup);
                            
                            }//*Cierre de for buses

                            //****  Cargar información buses registradas ****//
                            var infodetalle_rotulo = response.data.rotulos_registrados;

                            for (var i = 0; i < infodetalle_rotulo.length; i++) {

                             var markup = `<tr id="${infodetalle_rotulo[i].id}">
                            
                                <td align="center">               
                                      <button type="button" class="btn btn-danger btn-xs" onclick="VerRotulos(${infodetalle_rotulo[i].id})">&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;<b>VER</b>&nbsp;&nbsp;</button>
                                </td>

                                <td align="center">
                                ${infodetalle_rotulo[i].nom_empresa}
                                </td>
                            
                                <td align="center">
                                ${infodetalle_rotulo[i].cantidad_rotulos}
                                </td>
                                
                                <td align="center">
                        
                                ${infodetalle_rotulo[i].id_estado_rotulo!=1? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Cerrado</span>'}

                                </td>

                                <td align="center">
                                <span class="badge badge-pill badge-dark"> ${infodetalle_rotulo[i].nFicha} </span>
                                </td>

                                </tr>`;

                            $("#matriz_ver_rotulos tbody").append(markup);
                            
                            }//*Cierre de for rótulos




                }
                else{
                      Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: 'No se ha encontrado ningún registro!',
                                 // footer: '<a href="">Why do I have this issue?</a>'
                                })
                                $('#img_contribuyente').show();
                                $('#tarjeta_empresas_registradas').hide();
                                $('#tarjeta_buses_registradas').hide();
                                $('#tarjeta_rotulos_registrados').hide();

                    }
            })
         .catch((error) =>{
                            toastr.error('Error al buscar la obligación triburaria');
                           });                 
}
function Generar_solvencia(){
    var id = document.getElementById('select-contribuyente').value;
    window.open("{{ URL::to('/admin/generar/solvencia/pdf') }}/" + id );
}

function Generar_constancia_simple(){
    var id = document.getElementById('select-contribuyente').value;
    window.open("{{ URL::to('/admin/generar/constancia/simple/pdf') }}/" + id );
}

function VerEmpresa(id){
        openLoading();
        window.location.href="{{ url('/admin/empresas/show') }}/"+id;
        }

function VerBuses(id_bus)
    {
        openLoading();
        window.location.href="{{ url('/admin/buses/vista/') }}/"+id_bus;
    }

function VerRotulos(id_rotulo)
{
    openLoading();
    window.location.href="{{ url('/admin/Rotulos/vista//') }}/"+id_rotulo;
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

@endsection 