@extends('backend.menus.superior')

@section('content-admin-css')


    <!-- Finaliza el select live search -->
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />



@stop

<script>

window.onload = f4;

    function f4()
    {
        $('#imp_traspaso').hide();
        $('#imp_cierre').hide();
        $('#btn_ocultar_historial_traspasos').hide();
        $('#btn_ocultar_historial_cierres').hide();
        $('#Div_historico').hide();
        $('#Div_historico_cierres').hide();
        
        informacionTraspaso({{$rotulo->id}});
        
    }
  

    function f6()
    {
        $('#imp_traspaso').show();
        $('#historico_traspaso_emp').show();
    }

    function f7()
    {
        $('#imp_cierre').show();
        $('#historico_cierres_emp').show();
    }

    function verhistorialTraspasos()
    {
        recargarTraspasos();
        $('#Div_historico').show();
        $('#btn_ocultar_historial_traspasos').show();
        $('#btn_ver_historial_traspasos').hide();
    }
    function OcultarhistorialTraspasos()
    {
        $('#Div_historico').hide();
        $('#btn_ocultar_historial_traspasos').hide();
        $('#btn_ver_historial_traspasos').show();
    }

    function verhistorialCierres()
    {
        recargarCierres()
        $('#Div_historico_cierres').show();
        $('#btn_ocultar_historial_cierres').show();
        $('#btn_ver_historial_cierres').hide();
    }

    function OcultarhistorialCierres()
    {
        $('#Div_historico_cierres').hide();
        $('#btn_ocultar_historial_cierres').hide();
        $('#btn_ver_historial_cierres').show();
    }
</script>

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
    .avatar {
        vertical-align: middle;
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
</style>
<!-----------------------------------Inicia Contenido ------------------------------------------->

<div class="content-wrapper" style="display: none" id="divcontenedor">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h5><i class="far fa-plus-square"></i>&nbsp;Cierres y traspasos</h5>-->
                </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Cierres y traspasos</li>
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
                    <div class="card-header">
                        <h5 class="modal-title"><i class="far fa-edit">&nbsp;</i>Cierres y traspasos de rótulo &nbsp;<span class="badge badge-warning">&nbsp;{{$rotulo->nom_rotulo}} &nbsp;</span></h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                        </div>
                    </div>
    <!------------------------------ contenido -------------------------------------------->
                    <section class="content">
                        <br>
                    <div class="col-sm-6 float-left">
                        <div class="container-fluid">
                            <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">I. TRASPASO DE RÓTULOS</h3>
                                <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <div class="row"><!-- /.ROW2 -->

                                <!-- /.form-group -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                            <label>TRASPASO A NOMBRE DE:</label>
                                    </div>
                                    </div><!-- /.col-md-6 -->
                                    <!-- /.form-group -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <!-- Select estado - live search -->
                                            <div class="input-group mb-9">
                                                    <select 
                                                    required
                                                    class="form-control"                                                   
                                                    data-show-subtext="true" 
                                                    data-live-search="true"   
                                                    id="select-contribuyente-traspaso" 
                                                    title="-- Seleccione un registro --"
                                                    onchange="llenarSelect()"
                                                    >
                                                    @foreach($contribuyentes as $contribuyente)
                                                    <option value="{{ $contribuyente->id }}"> {{ $contribuyente->nombre }} {{$contribuyente->apellido}}</option>
                                                    @endforeach
                                                    </select>
                                            </div>
                                            <!-- finaliza select estado-->  
                                    </div><!-- /.col-md-3 -->
                                    </div><!-- /.form-group -->
                 

                        <div class="col-md-3">
                            <div class="form-group">
                                <label id="empresaDiv">ASIGNAR EMPRESA:</label>
                            </div>
                        </div><!-- /.col-md-6 --> 

                        <div class="col-md-3">
                            <div class="form-group" id= "asignar-empresaDIV">                          
                                <!-- Select estado - live search -->
                                <div class="input-group mb-3" >                        
                                <select class="form-control"  id="select-empresa"                         
                                >
                                </select>

                                </div>
                            </div>
                        </div>
                                 
                                    <!-- /.form-group -->
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>A PARTIR DEL DÍA:</label>                          
                                    </div>
                                    </div>
                                <!-- /.form-group --> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="date" id="Apartirdeldia" required class="form-control" >
                                        <input type="text" hidden value="{{$rotulo->id}}" id="id_rotulo" required class="form-control" >
                                    </div>
                                    </div>
                                <!-- /.form-group --> 
                                </div><!--  /.ROW2 -->

                                <!-- /.form-group -->
                                <div class="row"><!-- /.ROW3 -->
                                <!-- /.form-group -->
                                <div class="col-md-6">
                                <div class="form-group">
                                
                                    <!-- Botón Imprimir Traspaso-->
                                    <br>
                                    <button type="button"  onclick="ImprimirTraspaso()" id="imp_traspaso" class="btn btn-default btn-sm" ><i class="fa fa-print"></i>
                                        &nbsp; Imprimir resolución &nbsp;</button>
                                    </button>
                                    <!-- /.Botón Imprimir Traspaso -->

                                </div>
                                </div><!-- /.col-md-6 -->
                                <div class="col-md-6">
                                <div class="form-group">
                                    <!-- Botón Guardar Traspaso -->
                                        <br>
                                        <button type="button"  onclick="guardarTraspasoRotulo({{$rotulo->id}})" 
                                        class="btn btn-warning btn-sm float-right" ><i class="fas fa-save"></i>
                                        &nbsp; Guardar Traspaso &nbsp;</button>
                                    <!-- /.Botón Guardar Traspaso -->
                                </div>
                                </div><!-- /.col-md-6 -->
                                <!-- /.form-group -->
                                </div><!-- /.ROW4 -->
                                <hr>
                                <div class="row" id="historico_traspaso_emp"><!-- /.ROW5 -->
                               
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <h5>HISTÓRICO</h5>
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <!-- Botón ver historial de traspasos -->
                                            <button type="button" id="btn_ver_historial_traspasos"   onclick="verhistorialTraspasos()" 
                                            class="btn btn-dark btn-sm float-right" >
                                            <i class="fas fa-history"></i>
                                            &nbsp; Ver historial de traspasos &nbsp;</button>
                                        <!-- /.Botón ver historial de traspasos -->
                                        <!-- Botón ver historial de traspasos -->
                                            <button type="button" id="btn_ocultar_historial_traspasos"  onclick="OcultarhistorialTraspasos()" 
                                            class="btn btn-secondary btn-sm float-right" >
                                            <i class="fas fa-eye-slash"></i>
                                            &nbsp; Ocultar historial de traspasos &nbsp;</button>
                                        <!-- /.Botón ver historial de traspasos -->
                                    </div>
                                    </div><!-- /.col-md-6 -->
                                    <div class="col-md-12">
                                    <div class="form-group" id="Div_historico">
                                        <div class="col-auto  p-12 text-center" id="tabla_traspasos"></div>
                                    </div>
                                  </div>
                        
                                </div><!-- /.ROW5 -->

                            </div> 
                        </div>
                        </div>
                        </div>
                <!------------------ Requisiciones DEL PROYECTO INDIVIDUAL ---------------->
                        <div class="col-sm-6 float-right">
                        <div class="container-fluid">
                            <div class="card card-danger">
                            <div class="card-header">
                                <h3 class="card-title">II. CIERRE Y REAPERTURA DE RÓTULO</h3>
                                <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                                </div>
                            </div>
                                
                            <div class="card-body">

                            <div class="row"><!-- /.ROW2 -->

                            <!-- /.form-group -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ESTADO DEL RÓTULO:</label>
                                </div>
                            </div><!-- /.col-md-6 -->
                            <!-- /.form-group -->

                                <div class="col-md-3">
                                    <div class="form-group">
                                    <!-- Select estado - live search -->
                                        <div class="input-group mb-9">
                                            <select 
                                            required
                                            class="form-control"                                           
                                            data-show-subtext="true" 
                                            data-live-search="true"   
                                            id="select-estado_rotulo" 
                                            title="-- Seleccione el estado  --"
                                            >
                                                @foreach($estado_rotulo as $estado)
                                                <option value="{{ $estado->id }}"> {{ $estado->estado }}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    <!-- finaliza select estado-->  
                                </div><!-- /.col-md-3 -->
                            </div><!-- /.form-group -->
                            <!-- /.form-group -->
                            <!-- /.form-group -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>A PARTIR DEL DÍA:</label>                          
                                </div>
                                </div>
                            <!-- /.form-group --> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="date" id="Cierre_Apartirdeldia" required class="form-control" >
                                   
                                </div>
                                </div>
                            <!-- /.form-group --> 

                            </div><!--  /.ROW2 -->

                            <!-- /.form-group -->
                            <div class="row"><!-- /.ROW3 -->
                            <!-- /.form-group -->
                            <div class="col-md-6">
                            <div class="form-group">
                                
                            <!-- Botón Imprimir Cierre -->
                                 <br>
                                <button type="button"  onclick="" id="imp_cierre"  class="btn btn-default btn-sm" ><i class="fa fa-print"></i>
                                &nbsp; Imprimir resolución de Cierre&nbsp;</button>
                                </button>
                            <!-- /.Botón Imprimir Cierre -->

                            </div>
                            </div><!-- /.col-md-6 -->
                            <div class="col-md-6">
                            <div class="form-group">
                                <!-- Botón Guardar Traspaso -->
                                <br>
                                <button type="button"  onclick="guardarEstadoRotulo({{$rotulo->id}})" class="btn btn-danger btn-sm float-right" ><i class="fas fa-save"></i>
                                &nbsp; Guardar Cierre &nbsp;</button>
                                <!-- /.Botón Guardar Traspaso -->
                            </div>
                            </div><!-- /.col-md-6 -->
                            <!-- /.form-group -->
                            </div><!-- /.ROW3 -->
                            <hr>
                            <div class="row" id="historico_cierres_emp"><!-- /.ROW4 -->
                               
                               <div class="col-md-6">
                               <div class="form-group">
                                   <h5>HISTÓRICO</h5>
                               </div>
                               </div>
                               <div class="col-md-6">
                               <div class="form-group">
                                   <!-- Botón ver historial de cierres y reaperturas -->
                                       <button type="button" id="btn_ver_historial_cierres"   onclick="verhistorialCierres()" 
                                       class="btn btn-dark btn-sm float-right" >
                                       <i class="fas fa-history"></i>
                                       &nbsp; Ver historial &nbsp;</button>
                                   <!-- /.Botón ver historial de cierres y reaperturas -->
                                   <!-- Botón ver historial de cierres y reaperturas -->
                                       <button type="button" id="btn_ocultar_historial_cierres"  onclick="OcultarhistorialCierres()" 
                                       class="btn btn-secondary btn-sm float-right" >
                                       <i class="fas fa-eye-slash"></i>
                                       &nbsp; Ocultar historial &nbsp;</button>
                                   <!-- /.Botón ver historial de cierres y reaperturas -->
                               </div>
                               </div><!-- /.col-md-6 -->
                               <div class="col-md-12">
                               <div class="form-group" id="Div_historico_cierres">
                                    <div class="col-auto  p-12 text-center" id="tabla_cierres"></div>
                               </div>
                             </div>
                   
                           </div><!-- /.ROW4 -->

                          </div>
                        </div>
                       </div>
                     </div>
                </section>
    <!-----------------------------------Termina Contenido ------------------------------------------->          
                            <div class="card-footer">
                                <button type="button" class="btn btn-default" onclick=""><i class="fas fa-chevron-circle-left"></i> &nbsp;Volver</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>      
    </section>
</div>


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

        $(document).ready(function()
        {   

            document.getElementById("divcontenedor").style.display = "block";          

        });

        function resolucion_traspaso_historico(id)
        {
            window.open("{{ URL::to('/admin/traspaso_empresas_historico/pdf')}}/"+ id)
        }

        function resolucion_cierre_historico(id)
        {
            window.open("{{ URL::to('/admin/cierres_empresas_historico/pdf')}}/"+ id)
        }
        
        function ImprimirTraspaso(id)
        {
                window.open("{{ URL::to('/admin/traspaso_empresas/pdf') }}/" + id );
        }
        function ImprimirCierre(id)
        {
                window.open("{{ URL::to('/admin/cierre_empresas/pdf/') }}/" + id );
        }

        function VerEmpresa(id)
        {
             window.location.href="{{ url('/admin/empresas/show') }}/"+id;
        }

        function modalMensaje(titulo, mensaje)
        {
            Swal.fire
            ({
                title: titulo,
                text: mensaje,
                icon: 'info',
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar'
            }).then((result) => 
            {
                if (result.isConfirmed) 
                {

                }
            });   
        }

        function guardarTraspasoRotulo(id)
        {

            var id = document.getElementById('id_rotulo').value;
            var empresa = document.getElementById('select-empresa-traspaso').value;
            var contribuyente = document.getElementById('select-contribuyente-traspaso').value;
            var Apartirdeldia = document.getElementById('Apartirdeldia').value;

                if(Apartirdeldia==='')
                {
                    modalMensaje('Aviso', 'No ha seleccionado la fecha a partir del día');
                    return;
                }

     
        openLoading();

            var formData = new FormData();           
                formData.append('id', id);          
                formData.append('empresa', empresa);
                formData.append('contribuyente', contribuyente)
                formData.append('Apartirdeldia', Apartirdeldia);

            axios.post(url+'/rotulos/show/traspaso', formData, {
            })
            .then((response) => {          
                closeLoading();

                if (response.data.success === 1) 
                {

                        toastr.success('¡Propietario actualizado!');
                        f6();
                        recargarTraspasos();
                        document.getElementById('Apartirdeldia').value='';
                
                }
                    else if(response.data.success === 3)
                    {

                        modalMensaje('Aviso', 'El contribuyente seleccionado ya es el representante de la empresa, debe seleccionar otro.');
                    
                            return;
                    }else
                        {
                            toastMensaje('Error al actualizar');
                        }
            
            })
            .catch((error) => {
                toastr.error('Error al actualizar bus');
                closeLoading();
            });
        }

        function informacionTraspaso(id)
        {
            openLoading();
            axios.post(url+'/rotulos/show/informacion',{
                'id': id
            })
            .then((response) => {
              console.log(response);
                    closeLoading();
                    if(response.data.success === 1){
                        
                        document.getElementById("select-empresa-traspaso").options.length = 0;
                        document.getElementById("select-contribuyente-traspaso").options.length = 0;
                        document.getElementById("select-estado_rotulo").options.length = 0;
                        

                        $.each(response.data.empresa, function( key, val ){
                            if(response.data.id_emp == val.id){
                                $('#select-empresa-traspaso').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'</option>');
                            }else{
                                $('#select-empresa-traspaso').append('<option value="' +val.id +'">'+val.nombre+'</option>');
                            }
                            
                        });

                        $.each(response.data.contribuyente, function( key, val ){
                        if(response.data.id_contri == val.id){
                                $('#select-contribuyente-traspaso').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'&nbsp;'+val.apellido+'</option>');
                            }else{
                                $('#select-contribuyente-traspaso').append('<option value="' +val.id +'">'+val.nombre+'&nbsp;'+val.apellido+'</option>');
                            }
                        });
                        
                        $.each(response.data.estado_rotulo, function( key, val ){
                            if(response.data.idesta == val.id){
                                $('#select-estado_rotulo').append('<option value="' +val.id +'" selected="selected">'+val.estado+'</option>');
                            }else{
                                $('#select-estado_rotulo').append('<option value="' +val.id +'">'+val.estado+'</option>');
                            }
                        }); 

                      }else{
                        toastr.error('Información no ha sido encontrada');
                    }

                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontradaaaaaa');
                });

        }

    function guardarEstadoRotulo()
    {
    //Llamar la variable id desde el controlador
    
      
        var estado_rotulo = document.getElementById('select-estado_rotulo').value;
        var cierre_apartirdeldia = document.getElementById('Cierre_Apartirdeldia').value;

        if(estado_rotulo === '')
        {
            modalMensaje('Aviso', 'El estado del rótulo es requerido.');
            return;
        }

        if(cierre_apartirdeldia === '')
        {
            modalMensaje('Aviso', 'No ha seleccionado la fecha a partir del día');
            return;
        }

        openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('estado_rotulo', estado_rotulo);
            formData.append('cierre_apartirdeldia', cierre_apartirdeldia);

            axios.post(url+'/rotulos/show/cierre', formData, {
            })
            .then((response) => {          
                closeLoading();

                if (response.data.success === 1) 
                {
                    toastr.success('¡Estado del rótulo actualizado!');
                    f7();
                    recargarCierres();

                    document.getElementById('Cierre_Apartirdeldia').value='';

                }
                    else if(response.data.success === 3)
                    {

                        modalMensaje('Aviso', 'No ha cambiado el estado del rótulo, debe seleccionar otro.');
                        return;

                    }
                      else 
                            {
                                toastMensaje('Error al actualizar');                                
                            }
             
            })
            .catch((error) => {
                toastr.error('Error al actualizar rótulo');
                closeLoading();
            });
    }

        function recargarCierres()
        { 
            var id={{$empresas->id}};
            var ruta = "{{ url('/admin/empresas/cierres/tabla') }}/"+id;
                $('#tabla_cierres').load(ruta);
        }

        function recargarTraspasos()
        {
            var id={{$empresas->id}};
            var ruta = "{{ url('/admin/empresas/traspasos/tabla') }}/"+id;
                $('#tabla_traspasos').load(ruta);
        }

        function llenarSelect()
        {
            var id_select = document.getElementById('select-contribuyente-traspaso').value;
          
           
            var formData = new FormData();
                formData.append('id_select', id_select);
                
                axios.post(url+'/rotulos/buscar-traspaso', formData, {
                })
                .then((response) => {
                    
                    document.getElementById("select-empresa-traspaso").options.length = 0;
                            
            
                $.each(response.data.empresa, function( key, val ){
                       $('#select-empresa-traspaso').append('<option value="' +val.id +'">'+val.nombre+'</option>').select2();
                       
                            
                    });

               })
            .catch((error) => {
               // toastr.error('Error al registrar empresa');
               
            });            
             
        }

</script>

@endsection
