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
                    <h3 class="card-title">Mora Tributaria</h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-info" style="margin: 0 auto;width: 100%;height:190px;">
                            <h5><i class="fas fa-info"></i> Generar reporte de Mora Tributaria</h5>
                                <form class="form-horizontal">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <div class="info-box shadow">
                                                    <span class="info-box-icon bg-transparent"><i class="fas fa-donate"></i></span>
                                                    <div class="info-box-content">
                                                        
                                                        <div class="input-group mb-6">
                                                            &nbsp;
                                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="generar_mora();" >
                                                                <i class="fas fa-file-signature"></i> Calcular Mora
                                                            </button>                   
                                                                &nbsp;
                                                            <button type="button" class="btn btn-primary btn-sm" onclick="generarPdfMoraTributaria();" id="btn_mora_pdf">
                                                                <i class="fas fa-file-pdf"></i> Generar PDF
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            
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
                                    <th style="width: 10%; text-align: center;font-weight: 700;">N° FICHA</th>
                                    <th style="width: 12%; text-align: center;font-weight: 700;">COD ACT ECO.</th>
                                    <th style="width: 20%; text-align: center;font-weight: 700;">EMPRESA O NEGOCIO</th>       
                                    <th style="width: 15%; text-align: center;font-weight: 700;">ULTIMO PAGO</th>
                                    <th style="width: 10%; text-align: center;font-weight: 700;">MESES</th>
                                    <th style="width: 20%; text-align: center;font-weight: 700;">ULTIMA TARIFA/AÑO</th>
                                    <th style="width: 12%; text-align: right;font-weight: 700;">MORA</th>
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

<!-- Inicia Contenido IMG-->
    <div class="card" style="margin: 5 auto;width: 97%;" id="contenido_img">
      <div class="progress" style="margin: 0 auto;width: 100%;height:5px;">
        <div class="progress-bar bg-secondary" role="progressbar" style="width:10%; height:100%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
        <div class="card-body">
        <!-- Inicia contenido--> 

        <div class="col-auto  p-5 text-center">
         <img src="{{ asset('/img/mora.png') }}" id="img_mora" style="display: block;margin: 0px auto;width: 25%; height:25%;" >
        </div>

        <!-- Finaliza contenido-->
        </div>
      </div>
    </div>
<!-- Finaliza Contenido IMG-->
   

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
            $('#btn_mora_pdf').hide();

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

            window.open("{{ URL::to('admin/pdf/reporte/mora_tributaria') }}/");
        }
        
        function generar_mora(){
 
                
            $("#matriz_ver_mora tbody tr").remove();
            var formData = new FormData();

  
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
                            $('#btn_mora_pdf').show();
                            $('#div_generar_reporte').show();
                            $('#contenido_img').hide();
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

                            <td align="right">
                            $${infodetalle[i].total_pago}
                            </td>

                           </tr>`;

                            $("#matriz_ver_mora tbody").append(markup);

                            }//*Cierre de for empresas
                           
                            var markup2 = `<tr>
                            
                            <td align="right" colspan="7">
                                <b>TOTAL: ${response.data.total_mora_final}</b>
                            </td>

                           </tr>`;

                            $("#matriz_ver_mora tbody").append(markup2);

                }
                else{
                      Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: 'Error al calcular la mora!',
                                 // footer: '<a href="">Why do I have this issue?</a>'
                                })
                                $('#div_generar_reporte').hide();
                                $('#contenido_img').show();

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
