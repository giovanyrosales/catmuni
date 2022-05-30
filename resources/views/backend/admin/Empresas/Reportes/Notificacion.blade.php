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
            <h4>ALCALDIA MUNICIPAL DE METAPAN<br>
            UNIDAD DE ADMINISTRACION TRIBUTARIA MUNICIPAL<br>
            DEPARTAMENTO DE SANTA ANA, EL SALVADOR C.A</h4>
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
 
            </td>
        </tr>
    </table>
</footer>

<div id="content">
<table border="0" align="center" style="width: 600px;">
        <tr>
            <td colspan="2" align="center"><strong><u>N O T I F I C A C I O N</u></strong></td>
        </tr>
        <tr>
            <td align="right" colspan="2">
                <strong>Metapán, {{$FechaDelDia}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <p style="font-size:11">Señor (a):&nbsp;{{$empresa->contribuyente}}&nbsp;{{$empresa->apellido}}<br>
                Dirección:&nbsp;{{$empresa->direccionCont}}<br>
                Cuenta Corriente N°:&nbsp;{{$empresa->num_tarjeta}}<br>
                Empresa o Negocio:&nbsp;{{$empresa->nombre}}</p>

                Estimado(a) señor (a):
                <p style="text-indent:20; font-size:11">En nombre del Concejo Municipal, reciba un afectuoso saludo y deseos de éxito. El
                    motivo de la presente es para manifestarle que su estado de cuenta en esta
                    Municipalidad es el siguiente:</p>
            <p style="font-size:11">
                <strong>Impuestos Municipales</strong><br>
                Validez: <strong><u>{{$FechaDelDia}}</u></strong><br>
            </p>
            </td>
        <tr>
            <td><hr></td>
            <td><hr></td>
        </tr>
        <tr>
            <th scope="col">Periodo: &nbsp;&nbsp;desde&nbsp; {{$InicioPeriodo}}&nbsp;</th>
            <th scope="col">&nbsp;&nbsp;hasta&nbsp; {{$PagoUltimoDiaMes}}&nbsp;</th>    
        </tr>
        <tr>
            <td align="right">IMPUESTOS</td>
            <td align="center">{{$impuesto_año_actual}}</td>
        </tr>
        <tr>
            <td align="right">IMPUESTO MORA</td>
            <td align="center">{{$impuestos_mora}}</td>
        </tr>
        <tr>
            <td align="right">INTERESES MORATORIOS</td>
            <td align="center">{{$InteresTotal}}</td>
        </tr>
        <tr>
            <td align="right">MULTAS POR BALANCE ({{$Cantidad_multas}})</td>
            <td align="center">{{$monto_pago_multaBalance}}</td>
        </tr>
        <tr>
            <td align="right">MULTAS P. EXTEMPORANEOS</td>
            <td align="center">{{$totalMultaPagoExtemporaneo}}</td>
        </tr>
        <tr>
            <td align="right">FONDO F. PATRONALES 5%</td>
            <td align="center">{{$fondoFPValor}}</td>
        </tr>
        <tr>
            <th scope="row">Total Adeudado</th>
            <th align="center">{{$totalPagoValor}}</th>
        </tr>
        <tr>
            <td><hr></td>
            <td><hr></td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="color: black; font-family: Arial; font-size: 10; text-align: justify; text-indent:20">
                Por lo que solicito para que comparezca ante esta Administración Tributaria Municipal, a saldar lo adeudado, o a
                solicitar un plan de pago, concediéndose un plazo de treinta días contados a partir de la notificación para que efectúe el
                pago correspondiente bajo la prevención, que de no hacerlo, obligara a esta Municipalidad a certificar su deuda
                pendiente, a fin de que sin tramite alguno, se proceda a iniciar las diligencias judiciales correspondientes. 
                <br><br>
                Agradeciendo su comprension y atención a esta notificación me suscribo de usted, muy cordialmente.
                </p>
            </td>
        </tr>
        <tr align="center">
            <td colspan="2">
            <img src="{{ asset('images/imgf1.png') }}" style="float: left" alt="" height="206px" width="600px">
            </td>
        </tr>
    </table>
    
</div>
</body>
</html>
      