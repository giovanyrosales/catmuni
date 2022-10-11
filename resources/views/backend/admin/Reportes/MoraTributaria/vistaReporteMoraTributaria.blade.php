@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop


<div class="content-wrapper" id="divcc" style="display: none">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">

        </div>
    </section>
    
    <section class="content" id="divcontenedor">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Periodo de Mora</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                       
                            <div class="col-md-5">
                            <label>FECHA CORTE:</label>
                                <div class="input-group mb-4">
                                        <input type="date" id="fecha_corte" required class="form-control" >               
                                        &nbsp;
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                        onclick="generar_mora();" >
                                        <i class="fas fa-file-signature"></i> Calcular Mora
                                    </button>
                                </div>
                                
                            </div>  

                            <div id="tablaDatatable">
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content" id="div_generar_reporte">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div class="callout callout-info">
                    <table class="table" id="matriz_ver_mora" style="border: 100px;" data-toggle="table">
                                        <thead style="background-color:#14A3D9; color: #FFFFFF;">
                                        <tr>  
                                        <th style="width: 8%; text-align: center;font-weight: 700;">N° FICHA</th>
                                        <th style="width: 12%; text-align: center;font-weight: 700;">COD ACT ECO.</th>
                                        <th style="width: 20%; text-align: center;font-weight: 700;">EMPRESA O NEGOCIO</th>       
                                        <th style="width: 15%; text-align: center;font-weight: 700;">ULTIMO PAGO</th>
                                        <th style="width: 8%; text-align: center;font-weight: 700;">MESES</th>
                                        <th style="width: 20%; text-align: center;font-weight: 700;">U. TARIFA</th>
                                        <th style="width: 10%; text-align: center;font-weight: 700;">MORA</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                    
                                        </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


   

</div>


@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            document.getElementById("divcc").style.display = "block";
            $('#div_generar_reporte').hide();
            $('#select-contribuyente').select2({
                theme: "bootstrap-5",
                "language": {
                    "noResults": function(){
                        return "Búsqueda no encontrada";
                    }
                },
            });
        });

    </script>

    <script>

        function generarPdfMoraTributaria(){
            window.open("{{ URL::to('admin/pdf/reporte/mora_tributaria') }}/" );
        }
        
        function generar_mora(){

            $('#div_generar_reporte').show();
            var fecha_corte = document.getElementById("fecha_corte").value; 
            if(fecha_corte == ""){
                                    modalMensaje('Fecha de corte vacía', 'Debe selecionar una fecha de corte.');
                                    return;
                                }
                
            $("#matriz_ver_mora tbody tr").remove();
            var formData = new FormData();
            formData.append('fecha_corte', fecha_corte);
          
          axios.post('/admin/calculo/mora', formData, {
           })
        .then((response) => {
        
        if(response.data.success === 1)
                {
                    
                    Swal.fire({
                          position:'top-end',
                          icon: 'success',
                          title: '¡Cálculo realizado!',
                          showConfirmButton: true,                     
                        })
      
                            //**** Cargar información empresas registradas ****//
                            var infodetalle = response.data.mora_empresas;
                            
                            
                            for (var i = 0; i < infodetalle.length; i++) {

                            var markup = `<tr id="${infodetalle[i].id}">

                            <td align="center">
                            <span class="badge badge-pill badge-dark">${infodetalle[i].num_tarjeta}</span>
                            </td>
                            
                            <td align="center">
                            ${infodetalle[i].codigo_atc_economica}
                            </td>

                            <td align="center">
                            ${infodetalle[i].nombre}

                            <td align="center">
                            ${infodetalle[i].ultima_fecha_pago}
                            </td>

                            <td align="center">
                            ${infodetalle[i].meses}
                            </td>

                            <td align="center">
                            $${infodetalle[i].tarifaE}
                            </td>

                            <td align="center">
                            $${infodetalle[i].total_pago}
                            </td>

                           </tr>`;

                            $("#matriz_ver_mora tbody").append(markup);
                            
                            }//*Cierre de for empresas

                }
                else{
                      Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: 'Error al calcular la mora!',
                                 // footer: '<a href="">Why do I have this issue?</a>'
                                })
                                $('#div_generar_reporte').hide();

                    }
            })
         .catch((error) =>{
                            toastr.error('Error al calcular la mora');
                           });        
        }

        function modalMensaje(titulo, mensaje){
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
