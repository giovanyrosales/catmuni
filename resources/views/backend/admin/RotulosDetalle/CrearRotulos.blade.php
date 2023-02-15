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


</script>
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
                <h4><i class="far fa-plus-square"></i>&nbsp;Agregar Rótulos</h4>
            </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Rótulos</li>
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
                <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
                <div class="card-header text-info"><label>I. DATOS RÓTULOS</label></div>
                <div class="card-body"><!-- Card-body -->
          <!-- /.card-header -->
               
            <!-- <h3 class="card-title"><i class="far fa-plus-square"></i> &nbsp;Formulario de datos de la empresa.</h3> -->

            <!-- LISTA DE MATRICULAS  -->
            <div class="tab-pane" id="tab_2">
            <div class="row">
               <!-- /.form-group -->
                <div class="col-md-3">
                   <div class="form-group">
                          <label>N° de Ficha:</label>
                          <input type="number"  id="num_ficha" class="form-control"  placeholder="0000" >
                          <input type="number"  id="estado_rotulo" value="2" class="form-control" hidden placeholder="0000" >
                    </div>
                </div>
           
            <div class="col-md-3">
                    <div class="form-group">
                          <label>Fecha de Apertura:</label>
                          <input type="date"  id="fecha_apertura" class="form-control"  placeholder="" >
                    </div>  
            </div>

            <div class="col-md-3">
                <label>Contribuyente</label>
                    <select class='form-control seleccion'  style='max-width: 300px' id='select_contribuyente'>
                            <option  value='0'> --Seleccione el contribuyente -- </option>
                            
                            @foreach($contribuyentes as $data)
                                <option value="{{ $data->id }}"   data-pagoM='17.14'> {{ $data->nombre }} {{$data->apellido}} </option>                                
                            @endforeach>
                                            
                    </select>
            </div>

            <div class="col-md-3">
                    <div class="form-group">
                          <label>Cantidad de Rótulos:</label>
                          <input  id='cantidad_rotulos' class='form-control' min='1' style='max-width: 400px' type='number' value=''/>                        

                    </div>  
            </div>
            </div>
            <form>
            </div>
                </div>                        
                </div>
             
                    <div class="card border-info mb-3"><!-- Panel Datos generales de la empresa -->
                    <div class="card-header text-info"><label>II. INFORMACIÓN DEL PROPIETARIO</label></div>
                    <div class="card-body"><!-- Card-body -->
                    
                    <div class="row"> <!-- Row -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombre empresa:</label>
                                <input type="text" name="nit" id="nom_empresa" class="form-control"  placeholder="" >
                            </div>
                        </div>
           

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Dirección:</label>
                                <input type="text" name="nit" id="dire_empresa" class="form-control"  placeholder="" >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NIT de la empresa:</label>
                                <input type="text" name="nit" id="nit_empresa" class="form-control"  placeholder="" >
                            </div>
                        </div>
                    </div> <!-- Cierre row -->

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Teléfono:</label>
                                <input type="text" name="nit" id="tel_empresa" class="form-control"  placeholder="" >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Correo electrónico:</label>
                                <input type="text" name="email" id="email_empresa" class="form-control"  placeholder="" >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Registro de comerciante:</label>
                                <input type="text" name="reg_comerciante" id="reg_comerciante" class="form-control"  placeholder="" >
                            </div>
                        </div>
                    </div>
            </div>      
            </div>
            <div class="card-footer"> 
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">
                  <i class="fas fa-times-circle"></i>&nbsp;Cancelar</button>
                  <button type="button" class="btn btn-success float-right" onclick="agregarRotulo()">
                  <i class="fas fa-save"></i>&nbsp;Guardar</button>
          </div>
                        </form>
                         
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

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function()
        {     
            var ruta = "{{ url('/admin/rotulo-detalle/tabla') }}/";
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";

        });

    </script>

    <script>

        function  agregarRotulo()
        {
            
            var contribuyente = document.getElementById("select_contribuyente").value;            
            var fecha_apertura = document.getElementById("fecha_apertura").value;
            var cantidad_rotulos = document.getElementById("cantidad_rotulos").value;
            var num_ficha = document.getElementById("num_ficha").value;          
            var estado_rotulo = document.getElementById("estado_rotulo").value;
            var nom_empresa = document.getElementById("nom_empresa").value;
            var dire_empresa = document.getElementById("dire_empresa").value;
            var nit_empresa = document.getElementById("nit_empresa").value;
            var tel_empresa = document.getElementById("tel_empresa").value;
            var email_empresa = document.getElementById("email_empresa").value;
            var reg_comerciante = document.getElementById("reg_comerciante").value;
            

                if(fecha_apertura == "")
                {
                    modalMensaje('Aviso', 'Debe ingresar una fecha');
                    return;
                }

                if(num_ficha == "")
                {
                    modalMensaje('Aviso', 'Debe ingresar un número de ficha');
                    return;
                }

                if(contribuyente == 0)
                {
                    modalMensaje('Aviso', 'Debe selecionar un contribuyente');
                    return;
                }
                                    
                if(cantidad_rotulos == "")
                {
                    modalMensaje('Aviso', 'Debe ingresar una cantidad');
                    return;
                }

                if(cantidad_rotulos == 0)
                {
                    modalMensaje('Aviso', 'Debe ingresar una cantidad mayor a 0');
                    return;
                }

            openLoading();
            var formData = new FormData();             
                formData.append('contribuyente', contribuyente);                
                formData.append('fecha_apertura', fecha_apertura);
                formData.append('cantidad_rotulos', cantidad_rotulos);
                formData.append('num_ficha', num_ficha);              
                formData.append('estado_rotulo', estado_rotulo);
                formData.append('nom_empresa', nom_empresa);
                formData.append('dire_empresa', dire_empresa);
                formData.append('nit_empresa', nit_empresa);
                formData.append('tel_empresa', tel_empresa);
                formData.append('email_empresa', email_empresa);
                formData.append('reg_comerciante', reg_comerciante);
           

                    axios.post(url+'/rotulo-detalle/agregar', formData, {
                    })
                    .then((response) => {
                            console.log(response)
                            closeLoading();
                                if(response.data.success === 0)
                                {
                                    toastr.error(response.data.message);
                                }
                                else if(response.data.success === 1)
                                {
                                    agregado();
                                    resetbtn();
                                    
                                }
                                    })
                                    .catch((error) => {
                                        fallo('Error!', 'Error al agregar el rótulo');                                    
                                    });              
   

        }

        function recargar()
        {   
                  
            var ruta = "{{ url('/admin/rotulo-detalle/tabla') }}/";
                $('#tablaDatatable').load(ruta);

        }


    </script>

    <script>

        function resetbtn()
        {
        
            document.getElementById('cantidad_rotulos').value="";          
            document.getElementById('select_contribuyente').value=0; 

        } 

        function agregado()
        {

                Swal.fire({
                    title: 'Rótulo Agregado',
                    text: "Puede modificarla en la opción [Editar]",
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#28a745',
                    closeOnClickOutside: false,
                    allowOutsideClick: false,
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        ListarRotulo()                    
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
                    title: '¿Guardar Rótulos?',
                    text: "",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Guardar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        agregarRotulo();
                    }
                });

        }


        function agregado_buses_especifico()
        {
                Swal.fire({
                    title: 'Rótulo  específico agregado',
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

        function ListarRotulo()
        {
            openLoading();
            window.location.href="{{ url('/admin/rotulo-detalle/Listar') }}/";
        }


    </script>

@endsection