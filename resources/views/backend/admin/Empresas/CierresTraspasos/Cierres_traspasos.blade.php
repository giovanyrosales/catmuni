@extends('backend.menus.superior')

@section('content-admin-css')


    <!-- Finaliza el select live search -->
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />



@stop

<script>

window.onload = f4;

function f4(){
  $('#imp_traspaso').hide();
  $('#imp_cierre').hide();
  informacionTraspaso({{$empresa->id}});
  }

function f6(){
  $('#imp_traspaso').show();
  $('#imp_cierre').show();
  }

</script>
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
                        <h5 class="modal-title"><i class="far fa-edit">&nbsp;</i>Cierres y traspasos de la empresa&nbsp;<span class="badge badge-warning">&nbsp; {{$empresa->nombre}}&nbsp;</span></h5>
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
                                <h3 class="card-title">I. TRASPASO DE EMPRESA</h3>
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
                                                    data-style="btn-success"
                                                    data-show-subtext="true" 
                                                    data-live-search="true"   
                                                    id="select-contribuyente-traspaso" 
                                                    title="-- Seleccione un registro --"
                                                    >
                                                    @foreach($contribuyentes as $contribuyente)
                                                    <option value="{{ $contribuyente->id }}"> {{ $contribuyente->nombre }}&nbsp;{{ $contribuyente->apellido }}</option>
                                                    @endforeach
                                                    </select>
                                            </div>
                                            <!-- finaliza select estado-->  
                                    </div><!-- /.col-md-3 -->
                                    </div><!-- /.form-group -->
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
                                    <button type="button"  onclick="ImprimirTraspaso({{$empresa->id}})" id="imp_traspaso" class="btn btn-default btn-sm" ><i class="fa fa-print"></i>
                                        &nbsp; Imprimir resolución &nbsp;</button>
                                    </button>
                                    <!-- /.Botón Imprimir Traspaso -->

                                </div>
                                </div><!-- /.col-md-6 -->
                                <div class="col-md-6">
                                <div class="form-group">
                                    <!-- Botón Guardar Traspaso -->
                                        <br>
                                        <button type="button"  onclick="guardarTraspaso({{$empresa->id}})" class="btn btn-success btn-sm float-right" ><i class="fas fa-save"></i>
                                        &nbsp; Guardar Traspaso &nbsp;</button>
                                    <!-- /.Botón Guardar Traspaso -->
                                </div>
                                </div><!-- /.col-md-6 -->
                                <!-- /.form-group -->
                                </div><!-- /.ROW3 -->
                            </div> 
                        </div>
                        </div>
                        </div>
                <!------------------ Requisiciones DEL PROYECTO INDIVIDUAL ---------------->
                        <div class="col-sm-6 float-right">
                        <div class="container-fluid">
                            <div class="card card-danger">
                            <div class="card-header">
                                <h3 class="card-title">II. CIERRE DE EMPRESA</h3>
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
                                    <label>ESTADO DE LA EMPRESA:</label>
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
                                            data-style="btn-success"
                                            data-show-subtext="true" 
                                            data-live-search="true"   
                                            id="select-estado_empresa" 
                                            title="-- Seleccione el estado  --"
                                            >
                                                @foreach($estadoempresas as $estado)
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
                                <button type="button"  onclick="ImpimirCierre({{$empresa->id}})" id="imp_cierre"  class="btn btn-default btn-sm" ><i class="fa fa-print"></i>
                                &nbsp; Imprimir resolución de Cierre&nbsp;</button>
                                </button>
                            <!-- /.Botón Imprimir Cierre -->

                            </div>
                            </div><!-- /.col-md-6 -->
                            <div class="col-md-6">
                            <div class="form-group">
                                <!-- Botón Guardar Traspaso -->
                                <br>
                                <button type="button"  onclick="guardarEstado()" class="btn btn-success btn-sm float-right" ><i class="fas fa-save"></i>
                                &nbsp; Guardar Cierre &nbsp;</button>
                                <!-- /.Botón Guardar Traspaso -->
                            </div>
                            </div><!-- /.col-md-6 -->
                            <!-- /.form-group -->
                            </div><!-- /.ROW3 -->

                                      
                          </div>
                        </div>
                       </div>
                     </div>
                </section>
    <!-----------------------------------Termina Contenido ------------------------------------------->          
                            <div class="card-footer">
                                <button type="button" class="btn btn-default" onclick="VerEmpresa({{$empresa->id}})"><i class="fas fa-chevron-circle-left"></i> &nbsp;Volver</button>
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

    $(document).ready(function(){            
            document.getElementById("divcontenedor").style.display = "block";
        });

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

        function guardarTraspaso(id){

        var contribuyente = document.getElementById('select-contribuyente-traspaso').value;
        var Apartirdeldia = document.getElementById('Apartirdeldia').value;

        if(Apartirdeldia===''){
        modalMensaje('Aviso', 'No ha seleccionado la fecha a partir del día');
        return;
        }

        if(contribuyente === ''){
        modalMensaje('Aviso', 'El dato contribuyente es requerido');
        return;
        }

        openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('contribuyente', contribuyente);
            formData.append('Apartirdeldia', Apartirdeldia);

            axios.post('/admin/empresas/show/traspaso', formData, {
            })
            .then((response) => {          
                closeLoading();

                if (response.data.success === 1) 
                    
                    {
                        toastr.success('¡Propietario actualizado!');
                        f6();
                
                    }
                    else if(response.data.success === 3){

                    modalMensaje('Aviso', 'El contribuyente seleccionado ya es el representante de la empresa, debe seleccionar otro.');
                    return;
                    }else
                        {
                            toastMensaje('Error al actualizar');

                        }
            
            })
            .catch((error) => {
                toastr.error('Error al actualizar empresa');
                closeLoading();
            });
        }

        function informacionTraspaso(id){
          
            axios.post('/admin/empresas/show/informacion',{
                'id': id
            })
            .then((response) => {
              console.log(response);
                    closeLoading();
                    if(response.data.success === 1){
                        

                        document.getElementById("select-contribuyente-traspaso").options.length = 0;
                        document.getElementById("select-estado_empresa").options.length = 0;

                        
                        $.each(response.data.contribuyente, function( key, val ){
                            if(response.data.idcont == val.id){
                                $('#select-contribuyente-traspaso').append('<option value="' +val.id +'" selected="selected">'+val.nombre+'&nbsp;'+val.apellido+'</option>');
                            }else{
                                $('#select-contribuyente-traspaso').append('<option value="' +val.id +'">'+val.nombre+'&nbsp;'+val.apellido+'</option>');
                            }
                        });

                        $.each(response.data.estado_empresa, function( key, val ){
                            if(response.data.idesta == val.id){
                                $('#select-estado_empresa').append('<option value="' +val.id +'" selected="selected">'+val.estado+'</option>');
                            }else{
                                $('#select-estado_empresa').append('<option value="' +val.id +'">'+val.estado+'</option>');
                            }
                        }); 

                      }else{
                        toastr.error('Información no encontrada');
                    }

                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });

    }
    </script>

@endsection
