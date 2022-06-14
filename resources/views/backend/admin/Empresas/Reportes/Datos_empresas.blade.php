
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
                font-size: 12px;
                padding-top: 5px;
                padding-bottom: 5px;
        }
        #dos{
                font-size: 13px;
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
            <h4>DATOS GENERALES DE LA EMPRESA<br>
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
            <td align="left" colspan="2"><strong><p style="font-size:9">I. DATOS GENERALES DE LA EMPRESA</strong></td></td>
        </tr>
        <tr>
            <td id="uno">NÚMERO DE FICHA</td>
            <td id="dos" >{{$empresa->num_tarjeta}}</td>
        </tr>
        <tr>
            <td id="uno">NOMBRE DE NEGOCIO</td>
            <td id="dos">{{$empresa->nombre}}</td>
        </tr>
        <tr>
            <td id="uno"> GIRO ECONOMICO</td>
            <td id="dos">{{$empresa->nombre_giro}}</td>
        </tr>
        <tr>
            <td id="uno"> ACTIVIDAD ECONÓMICA</td>
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
        <td align="left" colspan="2"><strong><p style="font-size:9">II. CONTRIBUYENTE</strong></td></td>
        </tr>   
        <tr>
            <td id="uno">NOMBRE</td>
            <td id="dos" >{{$empresa->contribuyente}}&nbsp;{{$empresa->apellido}}</td>
        </tr>
        <tr>
            <td id="uno">TELÉFONO</td>
            <td id="dos">{{$empresa->tel}}</td>
        </tr>
        <tr>
            <td id="uno"> DUI</td>
            <td id="dos">{{$empresa->dui}}</td>
        </tr>
        <tr>
            <td id="uno">NIT</td>
            <td id="dos">{{$empresa->nitCont}}</td>
        </tr>
        <tr>
            <td id="uno">DIRECCION</td>
            <td id="dos">{{$empresa->direccionCont}}</td>
        </tr>
        <tr>
            <td id="uno">CORREO ELECTÓNICO</td>
            <td id="dos">{{$empresa->email}}</td>
        </tr>                  
        <tr>
                <td align="left" colspan="2"><strong><p style="font-size:9">III. CALIFICACIÓN DE LA EMPRESA</strong></td></td>
        </tr> 
        @if($ultimaCalificacion===null)
        <tr>
            <td colspan="2" id="uno">(LA EMPRESA NO CUENTA CON UNA CALIFICACIÓN)</td>
        </tr>
        @else
        <tr>
            <td id="uno">ÚLTIMA CALIFICACIÓN</td>
            <td id="dos">{{$ultimaCalificacion->año_calificacion}}</td>
        </tr>
        <tr>
            <td id="uno">TARIFA ACTUAL:</td>
            <td id="dos">${{$ultimaCalificacion->tarifa}}</td>
        </tr> 
        <tr>
            <td id="uno">TIPO DE TARIFA ACTUAL:</td>
            <td id="dos">{{$ultimaCalificacion->tipo_tarifa}}</td>
        </tr> 
        @endif
    </table>
    </td>
</tr>

</table>
    
</div>
</body>
</html>
      