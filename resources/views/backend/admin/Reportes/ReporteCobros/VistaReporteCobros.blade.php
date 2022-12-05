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
            <div class="card card">
                <div class="card-header" style="color:#FFFFFF; background:#94D913">
                    <h3 class="card-title">Reporte cobros global</h3>
                </div>
                    <div class="card-body">
                    <!--Inicia NAV--> 
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" onclick="reset_cobros_global();" href="#nav-cobros-global-tab" role="tab" aria-selected="true" style="color:#727370;"> <i class="fas fa-hand-holding-usd"></i> Cobros global</a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" onclick="reset_cobros_global();" href="#nav-cobros-empresas" role="tab" aria-selected="false" style="color:#727370;"><i class="fas fa-city"></i> Por empresas</a>
                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" onclick="reset_cobros_global();" href="#nav-cobros-tasas" role="tab"  aria-selected="false" style="color:#727370;"><i class="fas fa-coins"></i> Por tasas</a>
                        </div>
                    </nav>
                        <div class="tab-content" id="nav-tabContent"> 
                        <div class="tab-pane fade show active" id="nav-cobros-global-tab" role="tabpanel">
                        <!--Contenido 1 NAV -->
                            <br>
                            <div class="callout callout-info" style="margin: 0 auto;width: 100%;height:230px;">
                                <h6><i class="fas fa-info"></i> Generar reporte de cobros global.</h6>
                                    <form class="form-horizontal">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                    <div class="info-box shadow">
                                                        <span class="info-box-icon bg-transparent"><i class="fas fa-donate"></i></span>
                                                        <div class="info-box-content">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>FECHA INICIO:</label>
                                                                    <div class="input-group mb-3 shadow">
                                                                            <input type="date" id="fecha_inicio"   required class="form-control" >                                                                   
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>FECHA FINAL:</label>
                                                                    <div class="input-group mb-3 shadow">
                                                                            <input type="date" id="fecha_fin"  required class="form-control" >                                                                   
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>&nbsp;</label>
                                                                    <div class="input-group mb-3">
                                                                        &nbsp;
                                                                        <button type="button" class="btn btn-outline btn-sm" style="color:white; background:#94D913" onclick="generar_lista_cobros();" >
                                                                        <i class="fas fa-search-dollar"></i> Ver histórico de cobros
                                                                        </button>                   
                                                                            &nbsp;
                                                                        <button type="button" class="btn btn btn-sm" style="color:white; background:#94D913" onclick="generarPdfCobrosGlobal();" id="btn_cobros_pdf">
                                                                            <i class="fas fa-file-pdf"></i> Generar PDF
                                                                        </button>                                                                   
                                                                    </div>
                                                                </div>
                                                                                                                                    
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                
                                </div>
                            </div>
                        <!--FIN Contenido 1 NAV -->
                        <!--Contenido 2 NAV -->
                        <div class="tab-pane fade" id="nav-cobros-empresas" role="tabpanel">
                            
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                    <div class="info-box shadow">
                                                        <span class="info-box-icon bg-transparent"><i class="fas fa-donate"></i></span>
                                                        <div class="info-box-content">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>FECHA INICIO:</label>
                                                                    <div class="input-group mb-3 shadow">
                                                                            <input type="date" id="fecha_inicio_cobros_codigos"   required class="form-control" >                                                                   
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>FECHA FINAL:</label>
                                                                    <div class="input-group mb-3 shadow">
                                                                            <input type="date" id="fecha_fin_cobros_codigos"  required class="form-control" >                                                                   
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>&nbsp;</label>
                                                                    <div class="input-group mb-3">
                                                                        &nbsp;
                                                                        <button type="button" class="btn btn-outline btn-sm" style="color:white; background:#94D913" onclick="cobros_empresas();" >
                                                                        <i class="fas fa-search-dollar"></i> Ver histórico de cobros
                                                                        </button>                   
                                                                            &nbsp;
                                                                        <button type="button" class="btn btn btn-sm" style="color:white; background:#94D913" onclick="generarPdfCobrosEmpresas();" id="btn_cobros_empresas_pdf">
                                                                            <i class="fas fa-file-pdf"></i> Generar PDF
                                                                        </button>                                                                   
                                                                    </div>
                                                                </div>
                                                                                                                                    
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                        </div>
                        <!-- Finaliza NAV 2-->
                        <!--Contenido 3 NAV -->
                        <div class="tab-pane fade" id="nav-cobros-tasas" role="tabpanel" >
                            k
                        </div>
                        <!-- Finaliza NAV 3-->

            </div>
        </div>


    </section>

    <!-- Sección generar reporte cobros global -->
    <section class="content" id="div_generar_reporte">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="callout callout-info">
                        <table class="table" id="matriz_ver_cobros_global" style="border: 100px;" data-toggle="table">
                            <thead style="background-color:#94D913; color:white;">
                                <tr style="font-size:14px;">  
                                    <th style="width: 10%; text-align: center;font-weight: 700;">N° FICHA</th>
                                    <th style="width: 40%; text-align: center;font-weight: 700;">EMPRESA O NEGOCIO</th>
                                    <th style="width: 10%; text-align: center;font-weight: 700;">CÓDIGO</th>       
                                    <th style="width: 10%; text-align: center;font-weight: 700;">A PARTIR DE</th>
                                    <th style="width: 10%; text-align: center;font-weight: 700;">HASTA</th>
                                    <th style="width: 10%; text-align: center;font-weight: 700;">FECHA DE PAGO</th>
                                    <th style="width: 10%; text-align: right;font-weight: 700;">TOTAL PAGADO</th>
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
    <section class="content" id="div_generar_cobros_codigos">
    
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
                            <table class="table" id="matriz_ver_cobros_codigos" style="border: 100px;" data-toggle="table">
                                <thead style="background-color:#97999A; color: #FFFFFF;">
                                    <tr>  
                                        <th style="width: 60%; text-align: left;font-weight: 700;">DESCRIPCIÓN</th>
                                        <th style="width: 15%; text-align: center;font-weight: 700;">CÓDIGO</th>
                                        <th style="width: 25%; text-align: right;font-weight: 700;">CANTIDAD</th>       
                                    </tr>
                                </thead>
                                    <tbody style="font-size: 14px;padding: 8px;">

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


    <!-- Sección generar reporte cobros tasas -->
    <section class="content" id="div_generar_cobros_tasas">
    
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
                                <canvas id="grafico_cobros_tasas" width="400" height="300"></canvas>
                        </div>
                    </div>
                 </div>
                <!--IniciaFinaliza card para cargar gráfico de mora por códigos-->
    </div>
    <!--end of content list body-->
    </section>



<!-- Inicia Contenido IMG-->
    <div class="card" style="margin: 0 auto;width: 97%;" id="contenido_img">
      <div class="progress" style="margin: 0 auto;width: 100%;height:5px;">
        <div class="progress-bar bg-secondary" role="progressbar" style="width:10%; height:100%;-webkit-border-radius: 1px 0 0 0; border-radius: 5px 0 0 0;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
        <div class="card-body">
        <!-- Inicia contenido--> 

        <div class="col-auto  p-5 text-center">
         <img src="{{ asset('/img/empresa.png') }}" id="img_mora" style="display: block;margin: 0px auto;width: 25%; height:25%;" >
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
            $('#div_generar_cobros_codigos').hide();
            $('#div_generar_cobros_tasas').hide();
            $('#btn_cobros_pdf').hide();
            $('#btn_cobros_empresas_pdf').hide();
            window.imp_grafico_cobros_codigos=0;         
            window.imp_grafico_cobros_tasas=0;
           
        });

    </script>

    <script>

        function generarPdfCobrosGlobal(){

            //Validaciones
            var f1 = document.getElementById("fecha_inicio").value;
            var f2 = document.getElementById("fecha_fin").value; 
            var g='';
            //Validando SI se selecionado una fecha de ambos calendarios
            if(f1!="" || f2!=""){

                var g=1;

                if(f1 == ""){
                                $('#div_generar_reporte').hide();
                                $('#contenido_img').show();
                                modalMensaje('Fecha de inicio vacía', 'Debe selecionar una fecha de inicio.');
                                return;
                            }

                if(f2 == ""){
                                $('#div_generar_reporte').hide();
                                $('#contenido_img').show();
                                modalMensaje('Fecha de final vacía', 'Debe selecionar una fecha final.');
                                return;
                            }
            
            }else{
                    
                    var f1 = 0;
                    var f2 = 0; 
                    var g=0;
                 }
            //FIN - Validando SI se selecionado una fecha de ambos calendarios

            window.open("{{ URL::to('admin/pdf/reporte/cobros_global') }}/"+ f1 + "/" + f2 + "/" + g );
       
        }


        function generarPdfCobrosEmpresas(){

        //Validaciones
        var f1 = document.getElementById("fecha_inicio_cobros_codigos").value;
        var f2 = document.getElementById("fecha_fin_cobros_codigos").value; 
        var g='';
        //Validando SI se selecionado una fecha de ambos calendarios
        if(f1!="" || f2!=""){

            var g=1;

            if(f1 == ""){
                            $('#div_generar_cobros_codigos').hide();
                            $('#contenido_img').show();
                            modalMensaje('Fecha de inicio vacía', 'Debe selecionar una fecha de inicio.');
                            return;
                        }

            if(f2 == ""){
                            $('#div_generar_cobros_codigos').hide();
                            $('#contenido_img').show();
                            modalMensaje('Fecha de final vacía', 'Debe selecionar una fecha final.');
                            return;
                        }

        }else{
                
                var f1 = 0;
                var f2 = 0; 
                var g=0;
            }
        //FIN - Validando SI se selecionado una fecha de ambos calendarios

        window.open("{{ URL::to('admin/pdf/reporte/cobros_empresas') }}/"+ f1 + "/" + f2 + "/" + g );

        }

        function generar_lista_cobros(){

                openLoading();    
                $("#matriz_ver_cobros_global tbody tr").remove();

                //Validaciones
                var fecha_inicio = document.getElementById("fecha_inicio").value;
                var fecha_fin = document.getElementById("fecha_fin").value; 

                //Validando SI se selecionado una fecha de ambos calendarios
                if(fecha_inicio !="" || fecha_fin!=""){

                    var global=1;

                    if(fecha_inicio == ""){
                                            $('#div_generar_reporte').hide();
                                            $('#contenido_img').show();
                                            $('#btn_cobros_pdf').hide();
                                            modalMensaje('Fecha de inicio vacía', 'Debe selecionar una fecha de inicio.');
                                            return;
                                        }

                    if(fecha_fin == ""){
                                            $('#div_generar_reporte').hide();
                                            $('#contenido_img').show();
                                            $('#btn_cobros_pdf').hide();
                                            modalMensaje('Fecha de final vacía', 'Debe selecionar una fecha final.');
                                            return;
                                        }

                var formData = new FormData();
                formData.append('fecha_inicio', fecha_inicio);
                formData.append('fecha_fin', fecha_fin);
                formData.append('global', global);


                }else{
                        var global=0;

                        var formData = new FormData();
                        formData.append('global', global);
                    }
                //FIN - Validando SI se selecionado una fecha de ambos calendarios

                axios.post('/admin/cobros/globales/periodo', formData, {
                })
                .then((response) => {

                if(response.data.success === 1)
                    {

                        Swal.fire({
                            position:'top-end',
                            icon: 'success',
                            title: '¡Histórico de cobros generado!',
                            showConfirmButton: true,                     
                            })
                                $('#btn_cobros_pdf').show();
                                $('#div_generar_reporte').show();
                                $('#contenido_img').hide();
                                //**** Cargar información empresas registradas ****//
                                var infodetalle = response.data.lista_cobros;
                            
                                
                                for (var i = 0; i < infodetalle.length; i++) {

                                var markup = `<tr id="${infodetalle[i].id}">

                                <td align="center">
                                <span class="badge badge-pill badge-dark">${infodetalle[i].num_tarjeta}</span>
                                </td>
                                
                                <td align="center">
                                ${infodetalle[i].nombre}
                                </td>

                                <td align="center">
                                <span class="badge badge-secondary">${infodetalle[i].codigo}</span>
                                </td>

                                <td align="center">
                                ${infodetalle[i].apartir_de}
                                </td>

                                <td align="center">
                                ${infodetalle[i].hasta}
                                </td>

                                <td align="center">
                                ${infodetalle[i].fecha_pago}
                                </td>

                                <td align="right">
                                $${infodetalle[i].cobro_por_empresa}
                                </td>

                            </tr>`;

                                $("#matriz_ver_cobros_global tbody").append(markup);

                                }//*Cierre de for empresas

                                var infodetalle = response.data.lista_cobros_licencias;
                            
                                
                                for (var i = 0; i < infodetalle.length; i++) {

                                var markup2 = `<tr id="${infodetalle[i].id}">

                                <td align="center">
                                <span class="badge badge-pill badge-dark">${infodetalle[i].num_tarjeta}</span>
                                </td>
                                
                                <td align="center">
                                ${infodetalle[i].nombre}
                                </td>

                                <td align="center">
                                <span class="badge badge-success">${infodetalle[i].codigo}</span>
                                </td>

                                <td align="center">
                                ${infodetalle[i].apartir_de}
                                </td>

                                <td align="center">
                                ${infodetalle[i].hasta}
                                </td>

                                <td align="center">
                                ${infodetalle[i].fecha_pago}
                                </td>

                                <td align="right">
                                $${infodetalle[i].cobro_por_empresa}
                                </td>

                            </tr>`;

                                $("#matriz_ver_cobros_global tbody").append(markup2);

                                }//*Cierre de for licencia licor

                            var infodetalle = response.data.lista_cobros_matriculas;            
                                
                            for (var i = 0; i < infodetalle.length; i++) {

                            var markup3 = `<tr id="${infodetalle[i].id}">

                            <td align="center">
                            <span class="badge badge-pill badge-dark">${infodetalle[i].num_tarjeta}</span>
                            </td>
                            
                            <td align="center">
                            ${infodetalle[i].nombre}
                            </td>

                            <td align="center">
                            <span class="badge badge-primary">${infodetalle[i].codigo}</span>
                            </td>

                            <td align="center">
                            ${infodetalle[i].apartir_de}
                            </td>

                            <td align="center">
                            ${infodetalle[i].hasta}
                            </td>

                            <td align="center">
                            ${infodetalle[i].fecha_pago}
                            </td>

                            <td align="right">
                            $${infodetalle[i].cobro_por_empresa}
                            </td>

                        </tr>`;

                            $("#matriz_ver_cobros_global tbody").append(markup3);

                            }//*Cierre de for matriculas
                                                   
                            
                                var markup4 = `<tr>
                                
                                <td align="right" colspan="7">
                                    <b>TOTAL: $ ${response.data.total_cobros_global_formateado}</b>
                                </td>

                            </tr>`;

                                $("#matriz_ver_cobros_global tbody").append(markup4);

                    }
                    else if(response.data.success === 2){
                        Swal.fire({
                                    icon: 'info',
                                    title: 'Oops...',
                                    text: '¡No se encontró ningún cobro realizado en el período seleccionado!',   
                                    })
                                    $('#div_generar_reporte').hide();
                                    $('#contenido_img').show();

                        }else{
                        Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Error al generar el reporte de cobros global!',
                                    // footer: '<a href="">Why do I have this issue?</a>'
                                    })
                                    $('#div_generar_reporte').hide();
                                    $('#contenido_img').show();

                        }
                        
                })
                .catch((error) =>{
                                toastr.error('Error al generar el reporte de cobros global');
                            });        
                }






        function cobros_empresas(){

            if (window.imp_grafico_cobros_codigos=='1') {
                window.myChart.clear();
                window.myChart.destroy();            
            }
            
            if (window.imp_grafico_cobros_tasas=='1') {
                window.grafico_cobros_tasas.clear();
                window.grafico_cobros_tasas.destroy();
                window.imp_grafico_cobros_tasas=0;
            }                                                                 
                
            //Validaciones
            var fecha_inicio = document.getElementById("fecha_inicio_cobros_codigos").value;
            var fecha_fin = document.getElementById("fecha_fin_cobros_codigos").value; 

            //Validando SI se selecionado una fecha de ambos calendarios
            if(fecha_inicio !="" || fecha_fin!=""){

                var global=1;

                if(fecha_inicio == ""){

                                        modalMensaje('Fecha de inicio vacía', 'Debe selecionar una fecha de inicio.');
                                        $('#div_generar_reporte').hide();
                                        $('#div_generar_cobros_codigos').hide();
                                        $('#div_generar_cobros_tasas').hide();
                                        $('#contenido_img').show();
                                        $('#btn_cobros_empresas_pdf').hide();
                                        window.imp_grafico_cobros_codigos=0;
                                        return;
                                    }

                if(fecha_fin == ""){

                                        modalMensaje('Fecha de final vacía', 'Debe selecionar una fecha final.');
                                        $('#div_generar_reporte').hide();
                                        $('#div_generar_cobros_codigos').hide();
                                        $('#div_generar_cobros_tasas').hide();
                                        $('#contenido_img').show();
                                        $('#btn_cobros_empresas_pdf').hide();
                                        window.imp_grafico_cobros_codigos=0;
                                        return;
                                    }

                var formData = new FormData();
                formData.append('fecha_inicio', fecha_inicio);
                formData.append('fecha_fin', fecha_fin);
                formData.append('global', global);


                }else{
                        var global=0;

                        var formData = new FormData();
                        formData.append('global', global);
                    }
                //FIN - Validando SI se selecionado una fecha de ambos calendarios
                  
            $("#matriz_ver_cobros_global tbody tr").remove();
            $('#div_generar_reporte').hide();
            $('#div_generar_cobros_tasas').hide();

            $("#matriz_ver_cobros_codigos tbody tr").remove();

          axios.post('/admin/calculo/cobros_codigos_periodo', formData, {
           })
          .then((response) => {
            closeLoading();
            if(response.data.success === 1)
                {
                    window.imp_grafico_cobros_codigos=1;
                    $('#div_generar_cobros_codigos').show();
                    $('#contenido_img').hide();
                    $('#btn_cobros_empresas_pdf').show();
                    //**** Cargar información sobre cobros filtrada por códigos ****//
                    var cobro_11801 = response.data.cobro_11801;
                    var cobro_11802 = response.data.cobro_11802;
                    var cobro_11803 = response.data.cobro_11803;
                    var cobro_11804 = response.data.cobro_11804; 
                    var cobro_11806 = response.data.cobro_11806;
                    var cobro_11808 = response.data.cobro_11808;
                    var cobro_11809 = response.data.cobro_11809;
                    var cobro_11810 = response.data.cobro_11810;
                    var cobro_11813 = response.data.cobro_11813;
                    var cobro_11814 = response.data.cobro_11814;
                    var cobro_11815 = response.data.cobro_11815;
                    var cobro_11816 = response.data.cobro_11816;
                    var cobro_11899 = response.data.cobro_11899;
                    var cobro_15799 = response.data.cobro_15799;
                    
                    var cobro_12114 = response.data.cobro_12114;
                    var cobro_12207 = response.data.cobro_12207;
                    var cobro_32201 = response.data.cobro_32201;
                    var cobro_15302 = response.data.cobro_15302;
                    var cobro_15313 = response.data.cobro_15313; 
                    var cobro_12210 = response.data.cobro_12210;
                    var cobro_12299 = response.data.cobro_12299;

                    var cobro_11801_formateado = response.data.cobro_11801_formateado;
                    var cobro_11802_formateado = response.data.cobro_11802_formateado;
                    var cobro_11803_formateado = response.data.cobro_11803_formateado;
                    var cobro_11804_formateado = response.data.cobro_11804_formateado; 
                    var cobro_11806_formateado = response.data.cobro_11806_formateado;
                    var cobro_11808_formateado = response.data.cobro_11808_formateado;
                    var cobro_11809_formateado = response.data.cobro_11809_formateado;
                    var cobro_11810_formateado = response.data.cobro_11810_formateado;
                    var cobro_11813_formateado = response.data.cobro_11813_formateado;
                    var cobro_11814_formateado = response.data.cobro_11814_formateado;
                    var cobro_11815_formateado = response.data.cobro_11815_formateado;
                    var cobro_11816_formateado = response.data.cobro_11816_formateado;
                    var cobro_11899_formateado = response.data.cobro_11899_formateado;
                    var cobro_15799_formateado = response.data.cobro_15799_formateado;
                    
                    var cobro_12114_formateado = response.data.cobro_12114_formateado;
                    var cobro_12207_formateado = response.data.cobro_12207_formateado;
                    var cobro_32201_formateado = response.data.cobro_32201_formateado;
                    var cobro_15302_formateado = response.data.cobro_15302_formateado;
                    var cobro_15313_formateado = response.data.cobro_15313_formateado;
                    var cobro_12210_formateado = response.data.cobro_12210_formateado;
                    var cobro_12299_formateado = response.data.cobro_12299_formateado;

                    var markup = `<tr>
                    <td align="left">COMERCIO</td>
                    <td align="center">11801</td>
                    <td align="right">$${cobro_11801_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">INDUSTRIA</td>
                    <td align="center">11802</td>
                    <td align="right">$${cobro_11802_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">FINANCIERA</td>
                    <td align="center">11803</td>
                    <td align="right">$${cobro_11803_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">SERVICIOS</td>
                    <td align="center">11804</td>
                    <td align="right">$${cobro_11804_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">BAR Y RESTAURANTES</td>
                    <td align="center">11806</td>
                    <td align="right">$${cobro_11806_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">CENTROS DE ENSEÑANAZA</td>
                    <td align="center">11808</td>
                    <td align="right">$${cobro_11808_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">ESTUDIO DE FOTOS</td>
                    <td align="center">11809</td>
                    <td align="right">$${cobro_11809_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">HOTELES Y HOSPEDAJE</td>
                    <td align="center">11810</td>
                    <td align="right">$${cobro_11810_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">CONSULTORIOS MEDICOS</td>
                    <td align="center">11813</td>
                    <td align="right">$${cobro_11813_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">SERVICIOS PROFESIONALES</td>
                    <td align="center">11814</td>
                    <td align="right">$${cobro_11814_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">CENTROS RECREATIVOS</td>
                    <td align="center">11815</td>
                    <td align="right">$${cobro_11815_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">TRANSPORTE</td>
                    <td align="center">11816</td>
                    <td align="right">$${cobro_11816_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">IMPUESTOS MUNICIPALES DIVERSOS</td>
                    <td align="center">11899</td>
                    <td align="right">$${cobro_11899_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">LIBRERIAS</td>
                    <td align="center">15799</td>
                    <td align="right">$${cobro_15799_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">FONDO FIESTA</td>
                    <td align="center">12114</td>
                    <td align="right">$${cobro_12114_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">LICENCIAS</td>
                    <td align="center">12207</td>
                    <td align="right">$${cobro_12207_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">MATRICULAS</td>
                    <td align="center">12210</td>
                    <td align="right">$${cobro_12210_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">DERECHOS DIVERSOS</td>
                    <td align="center">12299</td>
                    <td align="right">$${cobro_12299_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">INTERESES</td>
                    <td align="center">15302</td>
                    <td align="right">$${cobro_15302_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">MULTAS</td>
                    <td align="center">15313</td>
                    <td align="right">$${cobro_15313_formateado}</td>
                    </tr>
                    <tr>
                    <td align="left">IMPUESTO MORA</td>
                    <td align="center">32201</td>
                    <td align="right">$${cobro_32201_formateado}</td>
                    </tr>`;      

                    $("#matriz_ver_cobros_codigos tbody").append(markup);
                    
                    var markup2 = `<tr>
                    
                    <td align="right" colspan="7">
                        <b>TOTAL: $ ${response.data.total_cobros_mixto_formateado}</b>
                    </td>

                    </tr>`;

                    $("#matriz_ver_cobros_codigos tbody").append(markup2);
                
                   
                    const ctx = document.getElementById('myChart');

                     window.myChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            
                            labels: ['11801', '11802', '11803', '11804', '11806', '11808','11809', 
                                     '11810', '11813', '11814', '11815', '11816', '11899','15799',
                                     '12114', '12207', '12210', '12299', '15302', '15313', '32201',
                                    ],
                            datasets: [{
                                label: 'Cantidad por Actividad Económica',
                                
                                data: [cobro_11801, cobro_11802, cobro_11803, cobro_11804, cobro_11806, cobro_11808,
                                       cobro_11809, cobro_11810, cobro_11813, cobro_11814, cobro_11815, cobro_11816,
                                       cobro_11899, cobro_15799, 
                                       cobro_12114, cobro_12207, cobro_12210, cobro_12299, cobro_15302, cobro_15313, cobro_32201
                                       ],
                                       
                                backgroundColor: [
                                    'rgba(255, 99, 132, 2)',
                                    'rgba(54, 162, 235, 2)',
                                    'rgba(255, 206, 86, 2)',
                                    'rgba(75, 192, 192, 2)',
                                    'rgba(153, 102, 255, 2)',
                                    'rgba(255, 159, 64, 2)',
                                    'rgba(146, 43, 33, 2)',
                                    'rgba(118, 68, 138, 2)',
                                    'rgba(88, 254, 10, 2)',
                                    'rgba(31, 97, 141, 2)',
                                    'rgba(23, 165, 137, 2)',
                                    'rgba(34, 153, 84, 2)',
                                    'rgba(183, 149, 11, 2)',
                                    'rgba(243, 156, 18, 2)',
                                    'rgba(211, 84, 0, 2)',
                                    'rgba(208, 211, 212, 2)',
                                    'rgba(98, 101, 103 ,2)',
                                    'rgba(52, 73, 94 ,2)',
                                    'rgba(247, 247, 247 ,2)',
                                    'rgba(255, 2, 2 ,2)',
                                    'rgba(247, 10, 107 ,2)'
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
                    
                
                }else if(response.data.success === 2){
                        Swal.fire({
                                    icon: 'info',
                                    title: 'Oops...',
                                    text: '¡No se encontró ningún cobro realizado en el período seleccionado!',   
                                    })
                            
                                    $('#div_generar_reporte').hide();
                                    $('#div_generar_cobros_codigos').hide();
                                    $('#div_generar_cobros_tasas').hide();
                                    $('#contenido_img').show();
                                    $('#btn_cobros_empresas_pdf').hide();
                                    $("#matriz_ver_cobros_codigos tbody tr").remove();
                                    window.imp_grafico_cobros_codigos=0;

                        }else{
                                Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: '¡Error al generar el reporte de cobros!',
                                            })
                                }
            })
         .catch((error) =>{
                            toastr.error('¡Error al generar el reporte de cobros!');
                           });        
   
        }






        function reset_cobros_global(){
            if (window.imp_grafico_cobros_codigos=='1') {
                window.myChart.clear();
                window.myChart.destroy();
                window.imp_grafico_cobros_codigos=0;
            }

            if (window.imp_grafico_cobros_tasas=='1') {
                window.grafico_cobros_tasas.clear();
                window.grafico_cobros_tasas.destroy();
                window.imp_grafico_cobros_tasas=0;
            }

            $("#matriz_ver_cobros_global tbody tr").remove();
            $("#matriz_ver_cobros_codigos tbody tr").remove();
            $('#btn_cobros_pdf').hide();
            $('#btn_cobros_empresas_pdf').hide();
            $('#div_generar_reporte').hide();
            $('#div_generar_cobros_codigos').hide();
            $('#div_generar_cobros_tasas').hide();
            $('#contenido_img').show();

            document.getElementById('fecha_inicio_cobros_codigos').value='';
            document.getElementById('fecha_fin_cobros_codigos').value='';
            document.getElementById('fecha_inicio').value='';
            document.getElementById('fecha_fin').value='';
            
        }

        

        function generar_mora_tasas_periodo(){

            //verificando si el grafico ya fue generado antes
            if (window.imp_grafico_cobros_codigos=='1') {
                window.myChart.clear();
                window.myChart.destroy();
                window.imp_grafico_cobros_codigos=0;
            }
             
            if (window.imp_grafico_cobros_tasas=='1') {
                window.grafico_cobros_tasas.clear();
                window.grafico_cobros_tasas.destroy();         
            }
            
            $('#div_generar_reporte').hide();
            $('#div_generar_mora_codigos').hide();
            
            $("#matriz_ver_mora_tasas tbody tr").remove();
            var formData = new FormData();

  
          axios.post('/admin/calculo/mora_tasas_periodo', formData, {
           })
          .then((response) => {
        
            if(response.data.success === 1)
                {
                    window.imp_grafico_cobros_tasas=1;
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
                    <td align="left">ROTULOS, TAXIS, MAQUINAS</td>
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

                    const ctx = document.getElementById('grafico_cobros_tasas');

                     window.grafico_cobros_tasas = new Chart(ctx, {
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


<script>

    $(function () {
        $("#matriz_ver_cobros_global").DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "pagingType": "full_numbers",
            "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "Todo"]],

            "language": {

                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "responsive": true, "lengthChange": false, "autoWidth": true,
        });
    });

</script>

@endsection
