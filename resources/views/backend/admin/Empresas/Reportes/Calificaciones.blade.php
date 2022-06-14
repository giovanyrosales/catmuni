
<html>
<head>
<link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <title>Alcaldía Metapán | Panel</title>
   <style>
        body{
            font-family: 'Calibri';
        }
        @page {
            margin: 145px 25px;
            /* margin-bottom: 10%;*/
        }
        header { position: fixed;
            left: 0px;
            top: -160px;
            right: 0px;
            height: 100px;
            text-align: center;
            font-size: 12px;
        }
        header h1{
            margin: 10px 0;
        }
        header h2{
            margin: 0 0 10px 0;
           
        }
        header h3{
            margin: 5px 0;
            color: #1E1E1E;
        }
        header p{
            margin-left: 15px;
            display: block;
            margin: 2px 0 0 0;
            font: size 5px;
        }
        footer {
            position: fixed;
            left: 0px;
            bottom: -10px;
            right: 0px;
            height: 10px;
            /* border-bottom: 2px solid #ddd;*/
        }

        footer table {
            width: 100%;
        }
        footer p {
            text-align: center;
            color: #A9A8A7;
        }
        footer .izq {
            margin-top: 20px; !important;
            margin-left: 20px;
            text-align: left;
        }
        footer img {
            text-align: center;
        }
        .content {
            padding: 20px;
            margin-left: auto;
            margin-right: auto;
        }

        .content img {
            margin-right: 15px;
            float: center;
        }

        .content h3{
            font-size: 20px;

        }

        .content hr{
             
             border: none;
             margin: 0;
             padding: 0;
         }

        #uno{
                font-size: 11px;
        }
        #dos{
                font-size: 12px;
                border: 1px solid #ddd;
                padding: 0px;
                color: #1E1E1E;
        }
        #tabla {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 90%;
            text-align: center;
        }

        #tabla td{
            border: 1px solid #ddd;
            padding: 0px;
            text-align: center;
            font-size: 11px;
        }

        #tabla th {
            border: 1px solid #ddd;
            padding: 0px;
            text-align: center;
        }

        #tabla th {
            padding-top: 2px;
            padding-bottom: 2px;
            background-color: #f2f2f2;
            color: #1E1E1E;
            text-align: center;
            font-size: 10px;
        }
        .fecha{
            font-size: 16px;
            margin-left: 17px;
            text-align: justify;
        }
        .texto{
            margin-left: 15px;
            display: block;
            margin: 2px 0 0 0;
            font-size: small;
        }



    </style>
<body>
<header style="margin-top: 25px">
    <div class="row">

        <div class="content">
            <img src="{{ asset('images/logo.png') }}" style="float: left" alt="" height="75px" width="75px">
            <img src="{{ asset('images/EscudoSV.png') }}" style="float: right" alt="" height="75px" width="75px">
            <h4>CALIFICACIÓN &nbsp;{{$ultimaCalificacion->año_calificacion}} <br>
            ALCALDIA MUNICIPAL DE METAPÁN, SANTA ANA, EL SALVADOR C.A<br>
            UNIDAD DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL; TEL. 2402-7614            
            </h4>
            <img src="{{ asset('images/linea4.png') }}"   alt="" height="5/3px" width="720px">
        </div>
        
    </div>
</header>

<footer>
    <table>
        <tr>
            <td>
                <p class="izq">
                    <br>

                </p>
            </td>
            <td>
 
            </td>
        </tr>
    </table>
</footer>

<div id="content">
<table border="0" align="center" style="width: 600px;">
        <tr>
            <td align="left" colspan="2"><strong><p style="font-size:9">I. UBICACIÓN DEL NEGOCIO</strong></td></td>
        </tr>
        <tr>
            <td id="uno">NÚMERO DE FICHA:</td>
            <td id="dos" align="center">{{$empresa->num_tarjeta}}</td>
        </tr>
        <tr>
            <td align="left" colspan="2"><strong><p style="font-size:9">II. DATOS GENERALES DE LA EMPRESA</strong></td></td>
        </tr>
        <tr>
            <td id="uno">NOMBRE DE NEGOCIO</td>
            <td id="dos">{{$empresa->nombre}}</td>
        </tr>
        <tr>
            <td id="uno"> GIRO ECONOMICO</td>
            <td id="dos">{{$empresa->rubro}}</td>
        </tr>
        <tr>
            <td id="uno">FECHA INICIO DE OPERACIONES</td>
            <td id="dos">{{$empresa->inicio_operaciones}}</td>
        </tr>
        <tr>
            <td id="uno">DIRECCION</td>
            <td id="dos">{{$empresa->direccion}}</td>
        </tr>
        <tr>
            <td id="uno">REPRESENTANTE LEGAL</td>
            <td id="dos">{{$empresa->contribuyente}}&nbsp;{{$empresa->apellido}}</td>
        </tr>
        <tr>
            <td id="uno">FECHA DE PRE. BALANCE O D. JURADA</td>
            <td id="dos">{{$ultimaCalificacion->fecha_calificacion}}</td>
        </tr> 
        <tr>
        <td align="left" colspan="2"><strong><p style="font-size:9">III. LICENCIAS Y PERMISOS</strong></td></td>
        </tr>                             
<tr>
    <td colspan="2"> 
        <table id="tabla" align="center">
                                <tr>
                                    <th colspan="4"> TOTALES</th>
                                    <th scope="col">T. LICENCIAS</th>
                                    <th scope="col">T. MATRICULAS</th>
                                    <th scope="col">T. MAT. O PER.</th>
                                </tr>
                                @if($detectorNull==1)
                                <tr>
                                    <th scope="col">MARTRICULAS</th>
                                    <th scope="col">CANT.</th>
                                    <th scope="col">MONTO</th>
                                    <th scope="col">P. MENSUAL</th>
                                    <td>${{$ultimaCalificacion->licencia}} </td>
                                    <td>${{$ultimaCalificacion->matricula}}</td>
                                    <td>${{$ultimaCalificacion->total_mat_permisos}} </h6></td>
                                </tr>
                                <tr>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @else
                                <tr>
                                    <th scope="col">MARTRICULAS</th>
                                    <th scope="col">CANT.</th>
                                    <th scope="col">MONTO</th>
                                    <th scope="col">P. MENSUAL</th>
                                    <td>${{$ultimaCalificacion->licencia}} </td>
                                    <td>${{$ultimaCalificacion->matricula}}</td>
                                    <td>${{$ultimaCalificacion->total_mat_permisos}}</td>
                                </tr>
                                @foreach($matriculas as $dato)
                                <tr>
                                    <td>{{$dato->tipo_matricula}}</td>
                                    <td align="center" >{{$dato->cantidad}}</td>
                                    <td>${{$dato->monto}}</td>
                                    <td>${{$dato->pago_mensual}}</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    @endforeach
                                </tr>

                                @endif
                                <tr>
                                    <td rowspan="2" colspan="5"></td>
                                    <th><b>Fondo F. P. </b></th>
                                    <td>${{$ultimaCalificacion->fondofp_licencia_permisos}}</td>                              >
                                </tr>
                                <tr>
                                    <th><b>Pago Anual </b></th>
                                    <td>${{$ultimaCalificacion->pago_anual_permisos}}</td>
                                </tr>
        </table>
    </td>
</tr>
@if($ultimaCalificacion->tipo_tarifa==='Fija')
<tr>
        <td align="left" colspan="2"><strong><p style="font-size:9">IV. CALIFICACIÓN DE LA EMPRESA - TARIFA FIJA</strong></td></td>
</tr>  
<tr>
        <td colspan="2">
            <table id="tabla" align="center">
                      <tr>
                        <th scope="col">ACTIVIDAD ECONOMICA</th>
                        <th scope="col"> </th>
                        <th scope="col"> BASE IMPONIBLE </th>
                        <th scope="col">TARIFA (COLONES)</th> 
                        <th scope="col">TARIFA (DOLARES)</th>
                      </tr>

                      <tr>
                        <td align="center">{{$empresa->nombre}}</td>
                        <td> {{$ultimaCalificacion->codigo_tarifa}} </td>
                        <td align="center">1</td>
                        <td>¢{{$ultimaCalificacion->tarifa_colones}} </td>
                        <td>${{$ultimaCalificacion->tarifa}}</td>
                      </tr>

                      <tr>
                        <td></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td>$0.00</td>
                      </tr>

                      <tr>
                        <td></td>
                        <td colspan="2"> </td>
                        <td><strong>Fondo F. P. 5% </strong></td>
                        <td><strong>TOTAL IMPUESTO</strong></td>
                      </tr>

                      <tr>
                        <th scope="row">MENSUAL</th>
                        <td colspan="2">${{$ultimaCalificacion->pago_mensual}}</td>
                        <td>${{$ultimaCalificacion->fondofp_mensual}}</td>
                        <td>${{$ultimaCalificacion->total_impuesto}}</td>
                      </tr>
                    </table>
    </td>
</tr>
@endif
@if($ultimaCalificacion->tipo_tarifa==='Variable')
<tr>
        <td align="left" colspan="2"><strong><p style="font-size:9">IV. CALIFICACIÓN DE LA EMPRESA - TARIFA VARIABLE</strong></td></td>
</tr> 
<tr>
    <td colspan="2">
    <table id="tabla" align="center">
                      <tr>
                        <th scope="col">EMPRESA</th>
                        <th scope="col">ACTIVO TOTAL</th>
                        <th scope="col">DEDUCCIONES</th>
                        <th scope="col">ACTIVO IMPONIBLE</th> 
                        <th scope="col">EJERCICIO</th>
                      </tr>

                      <tr>
                        <td align="center">{{$empresa->nombre}}</td>
                        <td align="center">${{$ultimaCalificacion->activo_total}}</td>
                        <td align="center">${{$ultimaCalificacion->deducciones}}</td>
                        <td align="center">${{$ultimaCalificacion->activo_imponible}}</td>
                        <td align="center">{{$ultimaCalificacion->año_calificacion}}</td>
                      </tr>

                      <tr>
                        <th>ACTIVIDAD ECONOMICA / TARIFA </td>
                        <th>CODIGO</td>
                        <th>IMPUESTO:</td>
                        <th>MENSUAL</td>
                        <th>ANUAL</td>
                      </tr>

                      <tr>
                        <td align="center">{{$empresa->rubro}}</td>
                        <td align="center">{{$empresa->id_act_economica}}</td>
                        <td> </td>
                        <td>${{$ultimaCalificacion->pago_mensual}}</td>
                        <td>${{$ultimaCalificacion->pago_anual}}</td>
                      </tr>

                      <tr>
                        <td rowspan="2"></td>
                        <td colspan="2">Fondo Fiestas Patronales 5%</td>
                        <td>${{$ultimaCalificacion->fondofp_mensual}}</td>
                        <td>${{$ultimaCalificacion->fondofp_anual}}</td>
                      </tr>

                      <tr>
                        <th colspan="2"><b>TOTAL IMPUESTO</b></td>
                        <td>${{$ultimaCalificacion->total_impuesto}}</td>
                        <td>${{$ultimaCalificacion->total_impuesto_anual}}</td>
                      </tr>
                    </table>
    </td>
</tr>
@endif
<tr>
        <td align="left" colspan="2"><strong><p style="font-size:9">V. MULTAS</strong></td></td>
</tr> 
<tr>
    <td colspan="2">
        <table id="tabla" align="center">
                        <tr align="center">
                            <th scope="col"> &nbsp; GIRO ECONOMICO &nbsp;</th>
                            <th scope="col">&nbsp;MULTA A PAGAR&nbsp;</th>
                            <th scope="col">&nbsp;BASE LEGAL&nbsp;</th>
                        </tr>
                        <tr align="center">
                            <td>{{$empresa->rubro}}</td>
                            <td>${{$ultimaCalificacion->multa_balance}}</td>
                            <td>&nbsp;ART. 21, LEY DE IMPUESTOS MUNICIPALES&nbsp;</td>
                        </tr>
        </table>
        <br>
        <table id="tabla" align="center">
                      <tr>
                        <th scope="col">
                            <h4>Nombre de Calificador:<br>Lic. Rosa Lisseth Aldana</h4>
                        </th>
                        <td><p style="text-align: justify; font-size: 6;"><b>Base Legal para el recurso de apelación respecto a esta
                             NOTIFICACION DE CALIFICACION.</b> Ley General Tributaria Municipal, Art. 123. 
                             -De la calificación de contribuyentes, de la determinación de tributos, 
                             de la resolución del Alcalde en el procedimiento de repetición del pago 
                             de lo no debido, y de la aplicación de sanciones hecha por la
                              administración tributaria municipal, se admitirá recurso de apelación 
                              para ante el Concejo Municipal respectivo, el cual deberá interponerse
                              ante el funcionario que haya hecho la calificación o pronunciada la 
                              resolución correspondiente, en el plazo de tres días después de su 
                              notificación.</p>
                        </td>
                        Fecha:&nbsp;{{$FechaDelDia}}&nbsp;       
                      </tr>
                    <tr>
                        <td colspan="2" id="uno"><b>Fecha:</b>&nbsp;{{$FechaDelDia}}&nbsp; </td>
                    </tr>

    </table>
    </td>
</tr>

</table>
    
</div>
</body>
</html>
      