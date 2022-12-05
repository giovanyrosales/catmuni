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
            margin: 10px 0;
            color: #1E1E1E;
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

 
        .content p{
            margin-left: 15px;
            display: block;
            margin: 2px 0 0 0;
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



    </style>
<body>
<header style="margin-top: 25px">
    <div class="row">

        <div class="content" style="float: left">
            <img src="{{ asset('images/logo.png') }}" style="float: left" alt="" height="78px" width="78px">
            <h3>ALCALDIA MUNICIPAL DE METAPAN</h3>
            <h3>Santa Ana, El Salvador, C.A.</h3>
            <h3>UNIDAD DE ADMINISTRACION TRIBUTARIA MUNICIPAL, TEL 2402-7614</h3>
            <img src="{{ asset('images/linea3.png') }}"   alt="" height="30px" width="720px">
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
                <img src="{{ asset('images/linea3.png') }}" alt="" height="28px" width="700px">
                <p class="page" >
                Avenida Benjamín Estrada Valiente y Calle Poniente, Barrio San Pedro, Metapán.<br>
                Tel.:2402-7615 - 2402-7601 - Fax: 2402-7616 <br>
               <strong>www.alcaldiademetapan.org</strong>
                </p>
                
            </td>
        </tr>
    </table>
</footer>

<div id="content">
<h4 align="center"><u>AVISO</u></h4>
<table border="0" align="center" style="width: 600px;">
        <tr>
            <td></td>
            <td align="right">
                <b>EXP.&nbsp;{{$empresa->num_tarjeta}}<br>
                <strong>Metapán, {{$FechaDelDia}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="font-size: 14px;"><b>Señor (a):&nbsp;{{$empresa->contribuyente}}&nbsp;{{$empresa->apellido}}<br>
                Presente.</b></p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="text-align: justify;  font-size: 14px;">Aprovecho la oportunidad para saludarle y a la vez informarle que la falta de pago de los tributos
                municipales en el plazo o fecha límite correspondiente, coloca al sujeto pasivo en situación de mora, sin necesidad de requerimiento 
                de parte de la administración tributaria municipal y sin tomar en consideración, las causas o motivos de esa falta de pago. Art. 45 
                (Ley General Tributaria).
                <br>
                <br>
                Nombre del Negocio o Empresa en Mora:&nbsp; <strong>{{$empresa->nombre}}</strong><br>
                Direccion: &nbsp;<strong>{{$empresa->direccion}}</strong></p>
            </td>
        <tr>
            <td colspan="2">
                <p style="text-align: justify;  font-size: 14px;">La mora del sujeto pasivo producirá, entre otros, los siguientes efectos: 1º Hace exigible la deuda
                    tributaria, 2º Da lugar al devengo de intereses moratorios, 3º Da lugar a la aplicación de multas, por
                    configurar dicha mora, una infracción tributaria. Los intereses moratorios se aplicarán desde el
                    vencimiento de plazo en que debió pagarse el tributo hasta el día de la extinción total de la obligación
                    tributaria. Art. 46 (Ley General Tributaria), Por tanto, es necesario que se acerque al Departamento
                    de Catastro Tributario de esta Municipalidad a la mayor brevedad posible, para cancelar la deuda o
                    solicitar de manera escrita un plan de pago.
                    <br>
                    Agradecemos de antemano la atención prestada a esta nota, y esperamos la disposición necesaria
                    para solventar su situación. 
                </p>
                    <br>
                        <img src="{{ asset('images/LeyT.png') }}"   alt="" height="115px" width="595px">
                <p>Atentamente.</p>           
            </td>
        </tr>
        <tr align="center">
            <td colspan="2">
                <p>Sr. José Roberto Solito<br>
                Delegado de Cobro.</p>
            </td>
        </tr>
    </table>
    
</div>
</body>
</html>
      