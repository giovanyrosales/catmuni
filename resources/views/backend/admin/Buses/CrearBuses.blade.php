@extends('backend.menus.superior')

@section('content-admin-css')


    <!-- Finaliza el select live search -->
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />

    



@stop
<script type="text/javascript">
function f1()
{
    
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
<!-----------------------------------Inicia Contenido ------------------------------------------->




<div class="content-wrapper" style="display: none" id="divcontenedor">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4><i class="far fa-plus-square"></i>&nbsp;Agregar Buses</h4>
                </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Buses</li>
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
                <div class="card-header text-info"><label>I. DATOS BUSES</label></div>
                <div class="card-body"><!-- Card-body -->
          <!-- /.card-header -->
               
            <!-- <h3 class="card-title"><i class="far fa-plus-square"></i> &nbsp;Formulario de datos de la empresa.</h3> -->

            <!-- LISTA DE MATRICULAS  -->
            <div class="tab-pane" id="tab_2">
            <div class="row">
               <!-- /.form-group -->
                <div class="col-md-4">
                   <div class="form-group">
                          <label>N° de Ficha:</label>
                          <input type="number"  id="nFicha" class="form-control"  placeholder="0000" >
                          <input type="number"  id="estado_buses" value="2" class="form-control" hidden placeholder="0000" >
                    </div>
                </div>
           
            <div class="col-md-4">
                    <div class="form-group">
                          <label>Fecha de Apertura:</label>
                          <input type="date"  id="fecha_apertura" class="form-control"  placeholder="" >
                    </div>  
            </div>

            <div class="col-md-4">
                <label>Contribuyente</label>
                    <select class='form-control seleccion' onchange='multiplicar(this)'  style='max-width: 300px' id='select_contribuyente'>
                            <option  value='0'> --Seleccione el contribuyente -- </option>
                            
                            @foreach($contribuyentes as $data)
                                <option value="{{ $data->id }}"   data-pagoM='17.14'> {{ $data->nombre }} {{$data->apellido}} </option>                                
                            @endforeach>
                                            
                    </select>
            </div>

            <div class="col-md-4">
                    <div class="form-group">
                          <label>Cantidad de Buses:</label>
                          <input  id='cantidad' onchange='multiplicar(this)' class='form-control' min='1' style='max-width: 400px' type='number' value=''/>                        

                    </div>  
            </div>
          
            <div class="col-md-4">
                    <div class="form-group">
                          <label>Total Tarifa:</label>
                          <input  id='monto_matricula' class='form-control' disabled min='1' style='max-width: 400px' type='text' value=''/>                        

                    </div>  
            </div>

            <div class="col-md-4">
                    <div class="form-group">
                          <label>Pago Mensual:</label>
                          <input  id='pago_mensual' class='form-control' disabled min='1' style='max-width: 300px' type='text' value=''/>                       

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
                                <input type="text" name="nit" id="dir_empresa" class="form-control"  placeholder="" >
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
                                <input type="text" name="r_comerciante" id="r_comerciante" class="form-control"  placeholder="" >
                            </div>
                        </div>
                    </div>
            </div>      
            </div>
            <div class="card-footer"> 
                  <button type="button" onclick="location.href='{{ url('/panel') }}'" class="btn btn-default">
                  <i class="fas fa-times-circle"></i>&nbsp;Cancelar</button>
                  <button type="button" class="btn btn-success float-right" onclick="agregarBus()">
                  <i class="fas fa-save"></i>&nbsp;Guardar</button>
          </div>
                        </form>
                           
         
  
        </section>
        
    




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
            var ruta = "{{ url('/admin/buses/tabla') }}/";
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";

            $('#empresaDIV').hide();
        });
    </script>

<script>
        //* Inicia función multiplicar
        function multiplicar()
        {
                var fondoF = 0.05;
                var monto_matricula=0;
                var pago_mensual=0;
               
                       
                var sel = document.getElementById("select_contribuyente");  
                var selected = sel.options[sel.selectedIndex];         
                var tarifa=selected.getAttribute('data-pagoM');              
                var cantidad = document.getElementById("cantidad").value; 

                //Operación                               
                var Total_pago_mensual=  tarifa*cantidad;           
                var monto_total = Math.ceil(tarifa*fondoF + (tarifa*cantidad));
           

                //Imprimiendo resultado
                document.getElementById('monto_matricula').value = Total_pago_mensual;
                document.getElementById('pago_mensual').value= monto_total;
               


        } //* Termina función multiplicar

        function agregarBus()
        {
            
            var contribuyente = document.getElementById("select_contribuyente").value;            
            var fecha_apertura = document.getElementById("fecha_apertura").value;
            var cantidad = document.getElementById("cantidad").value;
            var nFicha = document.getElementById("nFicha").value;
            var monto_pagar = document.getElementById("pago_mensual").value;
            var tarifa = document.getElementById("monto_matricula").value;
            var estado_buses = document.getElementById("estado_buses").value;
            var nom_empresa = document.getElementById("nom_empresa").value;
            var dir_empresa = document.getElementById("dir_empresa").value;
            var nit_empresa = document.getElementById("nit_empresa").value;
            var tel_empresa = document.getElementById("tel_empresa").value;
            var email_empresa = document.getElementById("email_empresa").value;
            var r_comerciante = document.getElementById("r_comerciante").value;
            

                if(fecha_apertura == "")
                {
                    modalMensaje('Aviso', 'Debe ingresar una fecha');
                    return;
                }

                if(nFicha == "")
                {
                    modalMensaje('Aviso', 'Debe ingresar un número de ficha');
                    return;
                }

                if(contribuyente == 0)
                {
                    modalMensaje('Aviso', 'Debe selecionar un contribuyente');
                    return;
                }
                                    
                if(cantidad == "")
                {
                    modalMensaje('Aviso', 'Debe ingresar una cantidad');
                    return;
                }

                if(cantidad == 0)
                {
                    modalMensaje('Aviso', 'Debe ingresar una cantidad mayor a 0');
                    return;
                }

            openLoading();
            var formData = new FormData();             
                formData.append('contribuyente', contribuyente);                
                formData.append('fecha_apertura', fecha_apertura);
                formData.append('cantidad', cantidad);
                formData.append('nFicha', nFicha);
                formData.append('tarifa', tarifa);
                formData.append('monto_pagar', monto_pagar);
                formData.append('estado_buses', estado_buses);
                formData.append('nom_empresa', nom_empresa);
                formData.append('dir_empresa', dir_empresa);
                formData.append('nit_empresa', nit_empresa);
                formData.append('tel_empresa', tel_empresa);
                formData.append('email_empresa', email_empresa);
                formData.append('r_comerciante', r_comerciante);
           

                    axios.post('/admin/buses/agregar', formData, {
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
                                        fallo('Error!', 'Error al agregar el bus');                                    
                                    });              

        }

        function recargar()
        {   
                  
            var ruta = "{{ url('/admin/buses/tabla') }}/";
                $('#tablaDatatable').load(ruta);
        }

  
    </script>

    <script>

        function resetbtn()
        {
            document.getElementById('monto_matricula').value=""; 
            document.getElementById('cantidad').value=""; 
            document.getElementById('pago_mensual').value=""; 
            document.getElementById('select_contribuyente').value=0; 
        } 
    
        
        function agregado()
        {
                Swal.fire({
                    title: 'Bus Agregado',
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
                            f3();
                            ListarBuses();
                        
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
                    title: '¿Guardar Buses?',
                    text: "",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Guardar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        agregarBus();
                    }
                });
        }

        function agregado_buses_especifico()
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

        function ListarBuses()
        {
            openLoading();
            window.location.href="{{ url('/admin/buses/Listar') }}/";
        }



    </script>

@endsection