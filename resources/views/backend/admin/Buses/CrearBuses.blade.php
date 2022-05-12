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


            <!-- LISTA DE MATRICULAS  -->
             <div class="tab-pane" id="tab_2">

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
                            <select class='form-control seleccion'  style='max-width: 300px' id='select_empresa'  >
                                <option value='0'> --  Seleccione la empresa  -- </option>
                                @foreach($empresas as $data)
                                <option value="{{ $data->id }}" data-pagoM='{{17.14}}'> {{ $data->nombre }}</option>                                
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
                        <button type='button' class='btn btn-block btn-success'  id="btnAdd" onclick='verificar()'>
                            <i class="far fa-plus-square"></i> 
                            &nbsp;Agregar
                        </button>
                        </td>

                        </tr>
                            </tbody>
                            </table>
                            </div>
                        </form>
                       </div>
              
                       <!-- Inclución de tabla -->
                       <div class="m-0 row justify-content-center" id="DivMatriculas">
                            <div class="card">
                                    <div class="card-header text-success">
                                        <h5> Buses registrados recientemente: <span class="badge badge-secondary"></span></h5> 
                                    </div>
                                    <div class="col-auto  p-5 text-center" id="tablaDatatable"></div>
                            </div>
                        </div>

                        @if($detectorBus == '1')                
                                    <script>
                                    window.onload = f1;
                                    </script>
                                <section class="content-header" id="aviso">
                                    <div class="container-fluid">
                                        <div>
                                            <br>
                                            <div class="callout callout-info">
                                                <h5><i class="fas fa-info"></i> Nota:</h5>
                                                <h5> No hay buses registrados <span class="badge badge-warning"></span></h5> 
                                                <button type='button' class='btn btn-block btn-primary'  id="btnAdd" onclick='f2()'>
                                                    <i class="far fa-plus-square"></i> 
                                                    &nbsp;Agregar buses
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                @endif   
                       
                       <!-- /.Inclución de tabla -->
                  
                         </div>
                        </div>
                      </div>
                </div>
         </section>
</div>

<!-- Inicia Modal Eliminar Matrícula-->

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
                var Total_pago_mensual= '$'+ tarifa*cantidad;           
                var monto_total = '$'+ Math.ceil(tarifa*fondoF + (tarifa*cantidad));
           

                //Imprimiendo resultado
                document.getElementById('monto_matricula').value=monto_total; 
                document.getElementById('pago_mensual').value=Total_pago_mensual;


        } //* Termina función multiplicar

        function agregarBus(id)
        {
            
            var empresa = document.getElementById("select_empresa").value; 
            var cantidad = document.getElementById("cantidad").value;
            var monto_pagar = document.getElementById("monto_matricula").value;
            var tarifa = document.getElementById("pago_mensual").value;
            

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
            formData.append('cantidad', cantidad);
            formData.append('monto_pagar', monto_pagar);
            formData.append('tarifa', tarifa);


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



    </script>

@endsection