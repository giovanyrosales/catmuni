<html>
<head>
    <title>Alcaldía Metapán | Panel</title>
    <style>
        body{
            font-family: Arial;
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


        #tabla {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-left: 20px;
            margin-right: 20px;
            margin-top: 35px;
            text-align: center;
        }

        #tabla td{
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 15px;
        }

        th{
            border: 0px solid #ddd;
            padding: 3px;
            text-align: center;
            background-color: #ddd;
            color: #1E1E1E;
        }

        #tabla th {
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: #1E1E1E;
            color: #1E1E1E;
            text-align: center;
            font-size: 16px;
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
            <h4>ALCALDIA MUNICIPAL DE METAPÁN, SANTA ANA, EL SALVADOR C.A<br>
            UNIDAD DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL<br>
            RESOLUCIÓN DE TRASPASO
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
            <td  align="left"><strong><u>TRASPASO</u></strong></td>
        
            <td align="right">
                RESOLUCIÓN N°:&nbsp;<strong>{{$cant_resolucion}}</strong><br><br>
            </td>
        </tr>
        <tr>
            <td>FECHA DE RESOLUCIÓN:</td>
            <td>{{$dia}},&nbsp;{{$FechaDelDia}}</td>
        </tr>
        <tr>
            <td>NÚMERO DE CUENTA CORRIENTE:</td>
            <td>{{$empresa->num_tarjeta}}</td>
        </tr>
        <tr>
            <td> <b>TRASPÁSESE:</b></td>
            <td>{{$empresa->nombre}}</td>
        </tr>
        <tr>
            <td>DIRECCIÓN:</td>
            <td>{{$empresa->direccion}}</td>
        </tr>
        <tr>
            <td>PROPIEDAD DE:</td>
            <td>{{$datos_traspaso->propietario_anterior}}</td>
        </tr>
        <tr>
            <td>GIRO ECONÓMICO:</td>
            <td>{{$empresa->nombre_giro}}</td>
        </tr>
        <tr>
            <td>A PARTIR DEL DIA:</td>
            <td>{{$diaApartirDe}}, &nbsp;{{$FechaDelDiaApartirDe}}</td>
        </tr>           
        <tr>
            <td>A NOMBRE DE:</td>
            <td>{{$datos_traspaso->propietario_nuevo}}</td>
        </tr>                      
        <tr>
            <td colspan="2" align="left">
                <p style="font-size:9">
                    <br>
                    <br>
                    <br>
                    <br>
                    LICDA. ROSA LISSETH ALDANA MERLOS<br>
                    JEFE DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL
                    
                </p>
                <hr>
                <p style="font-size:6;  text-align: justify;">
                    <b>Ley General Tributaria Municipal:</b><br>
                    <b>Art. 123.</b> -De la calificación de contribuyentes, de la determinación de tributos, 
                        de la resolución del Alcalde en el procedimiento de repetición del pago de lo no 
                        debido, y de la aplicación de sanciones hecha por la administración tributaria
                        municipal, se admitirá recurso de apelación para ante el Concejo Municipal 
                        respectivo, el cual deberá interponerse ante el funcionario que haya hecho la 
                        calificación o pronunciada la resolución correspondiente, en el plazo de tres 
                        días después de su notificación.
                        <br>
                        <br>
                        
                    <b>Art. 90.</b>-Los contribuyentes, responsables y terceros, estarán obligados al cumplimiento de los deberes formales que se establezcan en esta Ley, en leyes u ordenanzas de creación de tributos municipales, sus reglamentos y otras disposiciones normativas que dicten las administraciones tributarias municipales, y particularmente están obligados a: 
                        <br>1º Inscribirse en los registros tributarios que establezcan dichas administraciones; proporcionarles los datos pertinentes y comunicarles oportunamente cualquier modificación al respecto; 
                        <br>2º Solicitar, por escrito, a la Municipalidad respectiva, las licencias o permisos previos que se requieran para instalar establecimientos y locales comerciales e informar a la autoridad tributaria la fecha de inicio de las actividades, dentro de los treinta días siguientes a dicha fecha; 
                        <br>3º Informar sobre los cambios de residencia y sobre cualquier otra circunstancia que modifique o pueda hacer desaparecer las obligaciones tributarias, dentro de los treinta días siguientes a la fecha de tales cambios; 
                        <br>4º Permitir y facilitar las inspecciones, exámenes, comprobaciones o investigaciones ordenadas por la administración tributaria municipal y que realizará por medio de sus funcionarios delegados a tal efecto; (4) 
                        <br>5º Presentar las declaraciones para la determinación de los tributos, con los anexos respectivos, cuando así se encuentre establecido, en los plazos y de acuerdo con las formalidades correspondientes; 
                        <br>6º Concurrir a las oficinas municipales cuando fuere citado por autoridad tributaria; 
                        <br>7º El contribuyente que ponga fin a su negocio o actividad, por cualquier causa, lo informará por escrito, a la autoridad tributaria municipal, dentro de los treinta días siguientes a la fecha de finalización de su negocio o actividad; presentará, al mismo tiempo, las declaraciones pertinentes, el balance o inventario final y efectuará el pago de los tributos adeudados sin perjuicio de que la autoridad tributaria pueda comprobar de oficio, en forma fehaciente, el cierre definitivo de cualquier establecimiento; 
                        <br>8º Las personas jurídicas no domiciliadas en el país y que desarrollen actividades económicas en determinadas comprensiones municipales, deberán acreditar un representante ante la administración tributaria, municipal correspondiente y comunicarlo oportunamente. Si no lo comunicaren, se tendrá como tal a los gerentes o administradores de los establecimientos propiedad de tales personas jurídicas; 
                        <br>9º A presentar o exhibir las declaraciones, balances, inventarios físicos, tanto los valuados como los registrados contablemente con los ajustes correspondientes si los hubiere, informes, documentos, activos, registros y demás informes relacionados con hechos generadores de los impuestos; (4) 
                        <br> 10º A permitir que se examine la contabilidad, registros y documentos, determinar la base imponible, liquidar el impuesto que le corresponda, cerciorarse de que no existe de acuerdo a la ley la obligación de pago del impuesto, o verificar el adecuado cumplimiento de las obligaciones establecidas en esta Ley General o en las leyes tributarias respectivas; (4) 
                        <br>11º En general, a dar las aclaraciones que le fueren solicitadas por aquélla, como también presentar o exhibir a requerimiento de la Administración Municipal dentro del plazo que para tal efecto le conceda, los libros o registros contables exigidos en esta Ley y a los demás que resulten obligados a llevar de conformidad a otras leyes especiales. (4)
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <img src="{{ asset('images/LeyT.png') }}"   alt="" height="115px" width="595px">
            </td>
        </tr>
    </table>
    
</div>
</body>
</html>
      