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
            <select required onchange="buscar_obligaciones_tributarias()" class="form-control selectpicker show-tick" data-style="btn btn-outline-success"  data-show-subtext="true" data-live-search="true" id="select-contribuyente" title="Seleccione un contribuyente.">
                @foreach($contribuyentes as $contribuyente)
                  <option value="{{ $contribuyente->id }}"> {{ $contribuyente->nombre }}&nbsp;{{ $contribuyente->apellido }}</option>
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
                    <div class="card-header bg-transparent border-primary">
                        <h5><span class="badge badge-pill badge-primary"><i class="fas fa-building"></i></span>&nbsp;<span class="badge badge-dark">Empresas registradas</span></h5>
                    </div>
                      <div class="card-body">
                          <!--Tabla 12-->
                          <table class="table" id="matriz_ver_empresas" style="border: 100px;" data-toggle="table">
                                    <thead>
                                    <tr>      
                                      <th style="width: 15%; text-align: center;font-weight: 700;">Ver empresa</th>                     
                                      <th style="width: 25%; text-align: center;font-weight: 700;">Nombre</th>
                                      <th style="width: 15%; text-align: center;font-weight: 700;">N° Tarjeta</th>
                                      <th style="width: 15%; text-align: center;font-weight: 700;">Giro C.</th>
                                      <th style="width: 15%; text-align: center;font-weight: 700;">Estado</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                
                                    </tbody>
                          </table>
                      </div>
            </div>

            <!--Tarjeta para Buses-->
            <div class="card border-success mb-3" id="tarjeta_buses_registradas">
                    <div class="card-header bg-transparent border-success">
                        <h5><span class="badge badge-pill badge-success"><i class="fas fa-bus"></i>&nbsp;</span>&nbsp;<span class="badge badge-dark">Buses registrados</span></h5>
                    </div>
                      <div class="card-body">
                          <!--Tabla 12-->
                          <table class="table" id="matriz_ver_buses" style="border: 100px;" data-toggle="table">
                                    <thead>
                                    <tr>  
                                      <th style="width: 15%; text-align: center;font-weight: 700;">Ver</th>                         
                                      <th style="width: 25%; text-align: center;font-weight: 700;">Empresa</th>
                                      <th style="width: 15%; text-align: center;font-weight: 700;">N° Tarjeta</th>
                                      <th style="width: 15%; text-align: center;font-weight: 700;">Cantidad</th>
                                      <th style="width: 15%; text-align: center;font-weight: 700;">Estado</th>
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

 });

function buscar_obligaciones_tributarias(){
          openLoading();
         $("#matriz_ver_empresas tbody tr").remove();
         $("#matriz_ver_buses tbody tr").remove();

          var id_contribuyente = document.getElementById('select-contribuyente').value;
          
          var formData = new FormData();
          formData.append('id_contribuyente', id_contribuyente);
          
          axios.post('/admin/buscar/obligaciones_tributarias', formData, {
           })
         .then((response) => {

          if(response.data.success === 1)
                {
                    //document.getElementById('pago_mensual_hidden').value=response.data.matricula_Seleccionada.tarifa;
                    Swal.fire({
                          position:'top-end',
                          icon: 'success',
                          title: '¡Información encontrada!',
                          showConfirmButton: false,                     
                        })
                        $('#img_contribuyente').hide();
                        
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
                        

                            //****  Cargar información empresas registradas ****//
                            var infodetalle = response.data.empresas_registradas;
                            
                            for (var i = 0; i < infodetalle.length; i++) {

                            var markup = "<tr id='"+infodetalle[i].id+"'>"+

                                 "<td align='center'>"+                
                                      "<button type='button' class='btn btn-primary btn-xs'onclick=VerEmpresa('"+infodetalle[i].id+"')>&nbsp;&nbsp;<i class='fas fa-search'></i>&nbsp;&nbsp;</button>"+
                                "</td>"+

                                "<td id='especial' align='center'>"+
                                    infodetalle[i].nombre+
                                "</td>"+
                                
                                "<td align='center'>"+
                                    infodetalle[i].num_tarjeta+
                                "</td>"+

                                "<td align='center'>"+
                                    infodetalle[i].nombre_giro+
                                "</td>"+

                                "<td align='center'>"+
                                      infodetalle[i].estado+
                                "</td>"+

                                "</tr>";

                            $("#matriz_ver_empresas tbody").append(markup);
                            
                            }//*Cierre de for empresas

                            //****  Cargar información buses registradas ****//
                            var infodetalle_bus = response.data.buses_registrados;

                            for (var i = 0; i < infodetalle_bus.length; i++) {

                            var markup = "<tr id='"+infodetalle_bus[i].id+"'>"+

                            
                                "<td align='center'>"+                
                                      "<button type='button' class='btn btn-success btn-xs'onclick=VerBuses('"+infodetalle_bus[i].id+"')>&nbsp;&nbsp;<i class='fas fa-search'></i>&nbsp;&nbsp;</button>"+
                                "</td>"+

                                "<td id='especial2' align='center'>"+
                                  infodetalle_bus[i].nom_empresa+
                                "</td>"+
                                
                                "<td align='center'>"+
                                  infodetalle_bus[i].nFicha+
                                "</td>"+

                                "<td align='center'>"+
                                  infodetalle_bus[i].cantidad+
                                "</td>"+
                                
                                "<td align='center'>"+
                                  infodetalle_bus[i].estado_bus+
                                "</td>"+

                                "</tr>";

                            $("#matriz_ver_buses tbody").append(markup);
                            
                            }//*Cierre de for buses



                }
                else{
                      Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: 'No se ha encontrado ningún registro!',
                                  footer: '<a href="">Why do I have this issue?</a>'
                                })
                                $('#img_contribuyente').show();
                                $('#tarjeta_empresas_registradas').hide();
                                $('#tarjeta_buses_registradas').hide();

                    }
            })
         .catch((error) =>{
                            toastr.error('Error al buscar la obligación triburaria');
                           });                 
}

function VerEmpresa(id){
        openLoading();
        window.location.href="{{ url('/admin/empresas/show') }}/"+id;
        }

function VerBuses(id_bus)
    {
        openLoading();
        window.location.href="{{ url('/admin/buses/vista/') }}/"+id_bus;
    }id_bus

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