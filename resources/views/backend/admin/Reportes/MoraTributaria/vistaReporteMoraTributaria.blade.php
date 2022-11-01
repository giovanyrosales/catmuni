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
                    <h3 class="card-title">Mora tributaria global</h3>
                </div>
                <div class="card-body">
                <!--Inicia NAV--> 
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-mora-total-tab" onclick="reset_mora_total();" data-toggle="pill" href="#pills-mora-total" role="tab" aria-controls="pills-mora-total" aria-selected="true"><i class="fas fa-hand-holding-usd"></i> Mora total</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-mora-codigo-tab" onclick="mora_codigos();" data-toggle="pill" href="#pills-mora-codigo" role="tab" aria-controls="pills-mora-codigo" aria-selected="false"><i class="fab fa-slack-hash"></i> Por códigos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-mora-tasas-tab" onclick="generar_mora_tasas();" data-toggle="pill" href="#pills-mora-tasas" role="tab" aria-controls="pills-mora-tasas" aria-selected="false"><i class="fas fa-coins"></i> Por tasas</a>
                </li>
                </ul>
                <!--Contenido NAV-->
                <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-mora-total" role="tabpanel" aria-labelledby="pills-home-tab">
                       
                        <!--Contenido 1 NAV -->
                        <div class="callout callout-info" style="margin: 0 auto;width: 100%;height:190px;">
                            <h6><i class="fas fa-info"></i> Generar reporte de mora tributaria total</h6>
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
                
                
                </div><!--FIN Contenido 1 NAV -->

                <div class="tab-pane fade" id="pills-mora-codigo" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <!--Contenido 2 NAV -->
                </div>
                <div class="tab-pane fade" id="pills-mora-tasas" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <!--Contenido 3 NAV -->
                </div>
                </div>
                <!-- Finaliza NAV-->
                
        
                </div>
            </div>
        </div>

    </section>

    <!-- Sección generar reporte mora total -->
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

    <!-- Sección generar reporte mora codigos -->
    <section class="content" id="div_generar_mora_codigos">
    
        <!-- Lista -->
        <div class="content-list" style="margin: 0 auto;width: 98%;">
            <!--end of content list head-->
                    <div class="content-list-body row filter-list-1665680682896"><div class="col-lg-6">
                        
                    <!--Inicia card para cargar mora por códigos-->
                        <div class="card card-project">
                          <div class="progress"  style="margin: 0 auto;width: 100%;height:10px;">
                            <div class="progress-bar bg-secondary" role="progressbar" style="width:20%; height:100%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          <div class="card-body">
                            
                          <div class="container-fluid">
                            <table class="table" id="matriz_ver_mora_codigos" style="border: 100px;" data-toggle="table">
                                <thead style="background-color:#97999A; color: #FFFFFF;">
                                    <tr>  
                                        <th style="width: 50%; text-align: left;font-weight: 700;">DESCRIPCION</th>
                                        <th style="width: 25%; text-align: center;font-weight: 700;">CODIGO</th>
                                        <th style="width: 25%; text-align: right;font-weight: 700;">MORA</th>       
                                    </tr>
                                </thead>
                                    <tbody>

                                    </tbody>
                            </table>
                        </div>
                            
                          </div>
                        </div>
                    </div>
                    <!--Finaliza card para cargar mora por códigos-->

                    <!--Inicia card para cargar gráfico de mora por códigos-->
                      <div class="col-lg-6">
                        <div class="card card-project">

                          <div class="progress" style="margin: 0 auto;width: 100%;height:10px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width:35%; height:100%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>

                          <div class="card-body">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Gráfico comparativo</h6>
                                </div>
                                <canvas id="myChart" width="400" height="300"></canvas>
                            </div>
                           
                          </div>
                        </div>
                      </div>
                    <!--IniciaFinaliza card para cargar gráfico de mora por códigos-->
        </div>
        <!--end of content list body-->
    </section>


    <!-- Sección generar reporte mora tasas -->
    <section class="content" id="div_generar_mora_tasas">
    
    <!-- Lista -->
    <div class="content-list" style="margin: 0 auto;width: 98%;">
        <!--end of content list head-->
                <div class="content-list-body row filter-list-1665680682896"><div class="col-lg-6">
                    
                <!--Inicia card para cargar mora por códigos-->
                    <div class="card card-project">
                      <div class="progress"  style="margin: 0 auto;width: 100%;height:10px;">
                        <div class="progress-bar" role="progressbar" style="background-color:#98E826;width:20%; height:100%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <div class="card-body">
                        
                      <div class="container-fluid">
                        <table class="table" id="matriz_ver_mora_tasas" style="border: 100px;" data-toggle="table">
                            <thead style="background-color:#98E826; color: #FFFFFF;">
                                <tr>  
                                    <th style="width: 50%; text-align: left;font-weight: 700;">DESCRIPCION</th>
                                    <th style="width: 25%; text-align: center;font-weight: 700;">CODIGO</th>
                                    <th style="width: 25%; text-align: right;font-weight: 700;">MORA</th>       
                                </tr>
                            </thead>
                                <tbody>

                                </tbody>
                        </table>
                    </div>
                        
                      </div>
                    </div>
                </div>
                <!--Finaliza card para cargar mora por códigos-->

                <!--Inicia card para cargar gráfico de mora por códigos-->
                  <div class="col-lg-6">
                    <div class="card card-project">

                      <div class="progress" style="margin: 0 auto;width: 100%;height:10px;">
                        <div class="progress-bar bg-dark" role="progressbar" style="width:35%; height:100%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                                
                        <!-- Card Body -->
                        <div class="card-body">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Gráfico comparativo</h6>
                                </div>
                                <canvas id="grafico_mora_tasas" width="400" height="300"></canvas>
                        </div>
                    </div>
                 </div>
                <!--IniciaFinaliza card para cargar gráfico de mora por códigos-->
    </div>
    <!--end of content list body-->
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>


    <script>
        $(document).ready(function() {
            document.getElementById("divcc").style.display = "block";
 
            $('#div_generar_reporte').hide();
            $('#div_generar_mora_codigos').hide();
            $('#div_generar_mora_tasas').hide();
            $('#btn_mora_pdf').hide();
            window.imp_grafico_mora_codigos=0;         
            window.imp_grafico_mora_tasas=0;
           
        });

    </script>

    <script>

        function generarPdfMoraTributaria(){

            window.open("{{ URL::to('admin/pdf/reporte/mora_tributaria') }}/");
       
        }

        function mora_codigos(){
            if (window.imp_grafico_mora_codigos=='1') {
                window.myChart.clear();
                window.myChart.destroy();            
            }
            
            if (window.imp_grafico_mora_tasas=='1') {
                window.grafico_mora_tasas.clear();
                window.grafico_mora_tasas.destroy();
                window.imp_grafico_mora_tasas=0;
            }

  
            $("#matriz_ver_mora tbody tr").remove();
            $('#div_generar_reporte').hide();
            $('#div_generar_mora_tasas').hide();
            $('#btn_mora_pdf').hide();
            $('#contenido_img').hide();

            $("#matriz_ver_mora_codigos tbody tr").remove();
            var formData = new FormData();

  
          axios.post('/admin/calculo/mora_codigos', formData, {
           })
          .then((response) => {
            closeLoading();
            if(response.data.success === 1)
                {
                    window.imp_grafico_mora_codigos=1;
                    $('#div_generar_mora_codigos').show();
                    $('#contenido_img').hide();
                    //**** Cargar información mora filtrada por códigos ****//
                    var mora_11801 = response.data.mora_11801;
                    var mora_11802 = response.data.mora_11802;
                    var mora_11803 = response.data.mora_11803;
                    var mora_11804 = response.data.mora_11804; 
                    var mora_11806 = response.data.mora_11806;
                    var mora_11808 = response.data.mora_11808;
                    var mora_11809 = response.data.mora_11809;
                    var mora_11810 = response.data.mora_11810;
                    var mora_11813 = response.data.mora_11813;
                    var mora_11814 = response.data.mora_11814;
                    var mora_11815 = response.data.mora_11815;
                    var mora_11816 = response.data.mora_11816;
                    var mora_11899 = response.data.mora_11899;
                    var mora_15799 = response.data.mora_15799; 
                    
                    var mora_11801_formateado = response.data.mora_11801_formateado;
                    var mora_11802_formateado = response.data.mora_11802_formateado;
                    var mora_11803_formateado = response.data.mora_11803_formateado;
                    var mora_11804_formateado = response.data.mora_11804_formateado; 
                    var mora_11806_formateado = response.data.mora_11806_formateado;
                    var mora_11808_formateado = response.data.mora_11808_formateado;
                    var mora_11809_formateado = response.data.mora_11809_formateado;
                    var mora_11810_formateado = response.data.mora_11810_formateado;
                    var mora_11813_formateado = response.data.mora_11813_formateado;
                    var mora_11814_formateado = response.data.mora_11814_formateado;
                    var mora_11815_formateado = response.data.mora_11815_formateado;
                    var mora_11816_formateado = response.data.mora_11816_formateado;
                    var mora_11899_formateado = response.data.mora_11899_formateado;
                    var mora_15799_formateado = response.data.mora_15799_formateado; 
     
                    var markup = `<tr>
                    <td align="left">COMERCIO</td>
                    <td align="center">11801</td>
                    <td align="right">$${mora_11801_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">INDUSTRIA</td>
                    <td align="center">11802</td>
                    <td align="right">$${mora_11802_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">FINANCIERA</td>
                    <td align="center">11803</td>
                    <td align="right">$${mora_11803_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">SERVICIOS</td>
                    <td align="center">11804</td>
                    <td align="right">$${mora_11804_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">BAR Y RESTAURANTES</td>
                    <td align="center">11806</td>
                    <td align="right">$${mora_11806_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">CENTROS DE ENSEÑANAZA</td>
                    <td align="center">11808</td>
                    <td align="right">$${mora_11808_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">ESTUDIO DE FOTOS</td>
                    <td align="center">11809</td>
                    <td align="right">$${mora_11809_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">HOTELES Y HOSPEDAJE</td>
                    <td align="center">11810</td>
                    <td align="right">$${mora_11810_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">CONSULTORIOS MEDICOS</td>
                    <td align="center">11813</td>
                    <td align="right">$${mora_11813_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">SERVICIOS PROFESIONALES</td>
                    <td align="center">11814</td>
                    <td align="right">$${mora_11814_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">CENTROS RECREATIVOS</td>
                    <td align="center">11815</td>
                    <td align="right">$${mora_11815_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">TRANSPORTE</td>
                    <td align="center">11816</td>
                    <td align="right">$${mora_11816_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">MESAS DE BILLAR</td>
                    <td align="center">11899</td>
                    <td align="right">$${mora_11899_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">LIBRERIAS</td>
                    <td align="center">15799</td>
                    <td align="right">$${mora_15799_formateado}</td>
                    </tr>`;      

                    $("#matriz_ver_mora_codigos tbody").append(markup);
                    
                    var markup2 = `<tr>
                    
                    <td align="right" colspan="7">
                        <b>TOTAL: ${response.data.total_mora_final}</b>
                    </td>

                    </tr>`;

                    $("#matriz_ver_mora_codigos tbody").append(markup2);
                
                   
                    const ctx = document.getElementById('myChart');

                     window.myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            
                            labels: ['11801', '11802', '11803', '11804', '11806', '11808','11809', 
                                     '11810', '11813', '11814', '11815', '11816', '11899','15799'
                                    ],
                            datasets: [{
                                label: 'Cantidad por Actividad Económica',
                                
                                data: [mora_11801, mora_11802, mora_11803, mora_11804, mora_11806, mora_11808,
                                       mora_11809, mora_11810, mora_11813, mora_11814, mora_11815, mora_11816,
                                       mora_11899, mora_15799
                                       ],
                                       
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    
                
                }
                else{
                      Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: 'Error al calcular la mora!',
                                })
                                // $('#div_generar_mora_codigos').hide();
                                // $('#contenido_img').show();

                    }
            })
         .catch((error) =>{
                            toastr.error('Error al calcular la mora');
                           });        
   
        }

        function reset_mora_total(){
            if (window.imp_grafico_mora_codigos=='1') {
                window.myChart.clear();
                window.myChart.destroy();
                window.imp_grafico_mora_codigos=0;
            }

            if (window.imp_grafico_mora_tasas=='1') {
                window.grafico_mora_tasas.clear();
                window.grafico_mora_tasas.destroy();
                window.imp_grafico_mora_tasas=0;
            }

            $("#matriz_ver_mora tbody tr").remove();
            $('#btn_mora_pdf').hide();
            $('#div_generar_reporte').hide();
            $('#div_generar_mora_codigos').hide();
            $('#div_generar_mora_tasas').hide();
            $('#contenido_img').show();
        }

        function generar_mora(){

            openLoading();    
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
                            </td>

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

        function generar_mora_tasas(){

            //verificando si el grafico ya fue generado antes
            if (window.imp_grafico_mora_codigos=='1') {
                window.myChart.clear();
                window.myChart.destroy();
                window.imp_grafico_mora_codigos=0;
            }
             
            if (window.imp_grafico_mora_tasas=='1') {
                window.grafico_mora_tasas.clear();
                window.grafico_mora_tasas.destroy();         
            }
            
            $('#div_generar_reporte').hide();
            $('#div_generar_mora_codigos').hide();
            
            $("#matriz_ver_mora_tasas tbody tr").remove();
            var formData = new FormData();

  
          axios.post('/admin/calculo/mora_tasas', formData, {
           })
          .then((response) => {
        
            if(response.data.success === 1)
                {
                    window.imp_grafico_mora_tasas=1;
                    $('#div_generar_mora_tasas').show();
                    $('#contenido_img').hide();
                    //**** Cargar información mora filtrada por códigos ****//
                    var mora_11801 = response.data.mora_11801;
                    var mora_11802 = response.data.mora_11802;
                    var mora_11803 = response.data.mora_11803;
                    var mora_11804 = response.data.mora_11804; 
                    var mora_11806 = response.data.mora_11806;
                    var mora_11808 = response.data.mora_11808;
                    var mora_11809 = response.data.mora_11809;
                    var mora_11810 = response.data.mora_11810;
                    var mora_11813 = response.data.mora_11813;
                    var mora_11814 = response.data.mora_11814;
                    var mora_11815 = response.data.mora_11815;
                    var mora_11816 = response.data.mora_11816;
                    var mora_11899 = response.data.mora_11899;
                    var mora_15799 = response.data.mora_15799; 
                    
                    var mora_11801_formateado = response.data.mora_11801_formateado;
                    var mora_11802_formateado = response.data.mora_11802_formateado;
                    var mora_11803_formateado = response.data.mora_11803_formateado;
                    var mora_11804_formateado = response.data.mora_11804_formateado; 
                    var mora_11806_formateado = response.data.mora_11806_formateado;
                    var mora_11808_formateado = response.data.mora_11808_formateado;
                    var mora_11809_formateado = response.data.mora_11809_formateado;
                    var mora_11810_formateado = response.data.mora_11810_formateado;
                    var mora_11813_formateado = response.data.mora_11813_formateado;
                    var mora_11814_formateado = response.data.mora_11814_formateado;
                    var mora_11815_formateado = response.data.mora_11815_formateado;
                    var mora_11816_formateado = response.data.mora_11816_formateado;
                    var mora_11899_formateado = response.data.mora_11899_formateado;
                    var mora_15799_formateado = response.data.mora_15799_formateado; 
                   
            
                    var markup = `<tr>
                    <td align="left">ALUMBRADO</td>
                    <td align="center">12108</td>
                    <td align="right">$${mora_11801_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">ASEO</td>
                    <td align="center">12109</td>
                    <td align="right">$${mora_11802_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">PAVIMENTO</td>
                    <td align="center">12117</td>
                    <td align="right">$${mora_11803_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">BUSES</td>
                    <td align="center">12122</td>
                    <td align="right">$${mora_11804_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">APARATOS PARLANTES</td>
                    <td align="center">12210</td>
                    <td align="right">$${mora_11806_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">ROTULOS, MAQUINAS</td>
                    <td align="center">12299</td>
                    <td align="right">$${mora_11808_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">TORRES ELECTRICAS</td>
                    <td align="center">TSC006</td>
                    <td align="right">$${mora_11809_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">POSTES (ELECT.)</td>
                    <td align="center">TSP03E</td>
                    <td align="right">$${mora_11810_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">POSTES (CABLE)</td>
                    <td align="center">TSP03T</td>
                    <td align="right">$${mora_11813_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">TORRES TELEFONICAS</td>
                    <td align="center">TST004</td>
                    <td align="right">$${mora_11814_formateado}</td>                  
                    </tr>`;      

                    $("#matriz_ver_mora_tasas tbody").append(markup);
                    
                    var markup2 = `<tr>
                    
                    <td align="right" colspan="7">
                        <b>TOTAL: ${response.data.total_mora_final}</b>
                    </td>

                    </tr>`;

                    $("#matriz_ver_mora_tasas tbody").append(markup2);

                    const ctx = document.getElementById('grafico_mora_tasas');

                     window.grafico_mora_tasas = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            
                            labels: ['11801', '11802', '11803', '11804', '11806', '11808','11809', 
                                     '11810', '11813', '11814', '11815', '11816', '11899','15799'
                                    ],
                            datasets: [{
                                label: 'Cantidad por Actividad Económica',
                                
                                data: [mora_11801, mora_11802, mora_11803, mora_11804, mora_11806, mora_11808,
                                       mora_11809, mora_11810, mora_11813, mora_11814, mora_11815, mora_11816,
                                       mora_11899, mora_15799
                                       ],
                                       
                                backgroundColor: [
                                    'rgba(152, 232, 38, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(131, 202, 30, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                }
                else{
                      Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: 'Error al calcular la mora!',
                                })
                                // $('#div_generar_mora_codigos').hide();
                                // $('#contenido_img').show();

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
