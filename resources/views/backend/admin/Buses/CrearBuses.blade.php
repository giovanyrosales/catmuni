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

               
                <div class="card-header text-info"><h2>Buses</h2></div>
                <div class="card-body"><!-- Card-body -->

            <!-- LISTA DE MATRICULAS  -->
             <div class="tab-pane" id="tab_2">
             <div class="row">
               <!-- /.form-group -->
               <div class="col-md-2">
                <div class="form-group">
                    <label>Fecha de Apertura:</label>
                </div>
            </div><!-- /.col-md-6 -->
               <!-- Inicia Fecha de Inspección -->
            <div class="col-md-3">
                <div class="form-group">  
                    <input type="date"  value=" "  id="fecha_apertura" class="form-control" required >
                </div>
            </div>
              <!-- Finaliza Fecha de Inspección-->
               <!-- /.form-group -->
            </div>    
          </div>
            <form>
                     
                         <div class="card" id="tmatriculas">

                <table class="table"  style="border: 80px" data-toggle="table">
                        <thead>
                           <tr>
                         
                            <th style="width: 25%; text-align: center">Empresa</th>
                            <th style="width: 25%; text-align: center">Cantidad de buses</th>   
                            <th style="width: 25%; text-align: center">Total Tarifa</th>               
                            <th style="width: 14%; text-align: center">Pago Mensual</th>
                            <th style="width: 15%; text-align: center">Opciones</th>
                           </tr>
                        </thead>
                        <tbody>

                            <td>
                            <select class='form-control seleccion'  style='max-width: 250px' id='select_empresa'  >
                                <option value='0'> --  Seleccione la empresa  -- </option>
                                @foreach($empresas as $data)
                                <option value="{{ $data->id }}" data-pagoM='17.14'> {{ $data->nombre }}</option>                                
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
                                <input  id='pago_mensual' class='form-control' disabled min='1' style='max-width: 250px' type='text' value=''/>
                                </td>

                                <td>
                                <button type='button' class='btn btn-block btn-success'  id="btnAdd" onclick='verificar() '>
                                    <i class="far fa-plus-square"></i> 
                                    &nbsp;Agregar
                                </button>
                                </td>

                                </tr>
                                       </tbody>
                                       </table>
                                   </div>
                                </div>
                            </div>           
                        </form>
                     </div>
                 </div>
            </div>
  
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


    <script type="text/javascript">
        $(document).ready(function()
        {           
            var ruta = "{{ url('/admin/buses/tabla') }}/";
            $('#tablaDatatable').load(ruta);
            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

<script>
        //* Inicia función multiplicar
        function multiplicar()
        {
                var fondoF = 0.05;
                var monto_matricula=0;
                var pago_mensual=0;
               
                       
                var sel = document.getElementById("select_empresa");  
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
            
            var empresa = document.getElementById("select_empresa").value; 
            var fecha_apertura = document.getElementById("fecha_apertura").value;
            var cantidad = document.getElementById("cantidad").value;
            var monto_pagar = document.getElementById("pago_mensual").value;
            var tarifa = document.getElementById("monto_matricula").value;
            

                if(fecha_apertura=="")
                {
                    modalMensaje('Aviso', 'Debe ingresar una fecha');
                    return;
                }

                if(empresa==0)
                {
                    modalMensaje('Aviso', 'Debe selecionar una empresa');
                    return;
                }
                                    
                if(cantidad=="")
                {
                    modalMensaje('Aviso', 'Debe ingresar una cantidad');
                    return;
                }

                if(cantidad==0)
                {
                    modalMensaje('Aviso', 'Debe ingresar una cantidad mayor a 0');
                    return;
                }

            openLoading();
            var formData = new FormData();    
         
            formData.append('empresa', empresa);
            formData.append('fecha_apertura',fecha_apertura);
            formData.append('cantidad', cantidad);
            formData.append('tarifa', tarifa);
            formData.append('monto_pagar', monto_pagar);
           


                    axios.post('/admin/buses/agregar', formData, {
                    })
                        .then((response) => {
                            console.log(response)
                            closeLoading();
                            if(response.data.success === 0){
                                toastr.error(response.data.message);
                            }
                            else if(response.data.success === 1){
                                agregado();
                                resetbtn();
                                
                                }
                                else if(response.data.success === 2){
                                modalMensaje('Empresa repetida!', 'Para agregar más o eliminarlas seleccione las opciones [Editar o Eliminar]');
                                resetbtn();
                                return;
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
        document.getElementById('select_empresa').value=0; 
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

    function agregado_buses_especifico(){
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