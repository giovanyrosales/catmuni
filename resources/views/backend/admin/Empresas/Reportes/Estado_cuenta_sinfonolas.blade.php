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
            color: #A9A8A7;
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

        <div class="content">
            <img src="{{ asset('images/logoIMP.png') }}" style="float: left" alt="" height="78px" width="78px">
            <img src="{{ asset('images/EscudoSV.png') }}" style="float: right" alt="" height="78px" width="78px">
            <h3>ALCALDIA MUNICIPAL DE METAPAN</h3>
            <h3>Santa Ana, El Salvador, C.A.</h3>
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
<h4 align="center"><u>ESTADO DE CUENTA</u></h4>
<table border="0" align="center" style="width: 600px;">
        <tr>
            <td></td>
            <td align="right">
                <strong>Metapán, {{$FechaDelDia}}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p>Señor (a):&nbsp;{{$empresa->contribuyente}}&nbsp;{{$empresa->apellido}}<br>
                Dirección:&nbsp;{{$empresa->direccionCont}}<br>
                Cuenta Corriente N°:&nbsp;{{$empresa->num_tarjeta}}<br>
                Empresa o Negocio:&nbsp;{{$empresa->nombre}}/Matrícula Sinfonolas<br><br>
 
                Estimado(a) señor (a): </p>
                <p style="text-indent:20">En nombre del Concejo Municipal, reciba un afectuoso saludo y deseos de éxito. El
                    motivo de la presente es para manifestarle que su estado de cuenta en esta
                    Municipalidad es el siguiente:<br>
                <strong>Impuestos Municipales</strong></p>
                <p style="font-size:10">*Intereses cálculados con base a tabla proporcionados por el banco nacional de reserva.</p>
            </td>
        <tr>
            <th scope="col">Periodo: &nbsp;&nbsp;desde&nbsp; {{$InicioPeriodo}}&nbsp;</th>
            <th scope="col">&nbsp;&nbsp;hasta&nbsp; {{$PagoUltimoDiaMes}}&nbsp;</th>    
        </tr>
        <tr>
            <td align="right">IMPUESTO MORA</td>
            <td align="center">${{$impuestos_mora}}</td>
        </tr>
        <tr>
            <td align="right">IMPUESTOS</td>
            <td align="center">${{$impuesto_año_actual}}</td>
        </tr>
        <tr>
            <td align="right">INTERESES MORATORIOS</td>
            <td align="center">${{$InteresTotal}}</td>
        </tr>
        <tr>
            <td align="right">MULTAS</td>
            <td align="center">${{$totalMultaPagoExtemporaneo}}</td>
        </tr>
        <tr>
            <td align="right">MATRÍCULA</td>
            <td align="center">${{$monto_pago_matricula}}</td>
        </tr>

        <tr>
            <td align="right">FONDO F. PATRONALES 5%</td>
            <td align="center">${{$fondoFPValor}}</td>
        </tr>
        <tr>
            <td align="right">MUL. MATRÍCULA</td>
            <td align="center">${{$multa}}</td>
        </tr>
        <tr>
            <th scope="row">Total de Impuestos Adeudados</th>
            <th align="center">${{$totalPagoValor}}</th>
        </tr>
        <tr>
            <td><hr></td>
            <td><hr></td>
        </tr>
        <tr>
            <td colspan="2">
            Validez: <strong><u>{{$FechaDelDia}}</u></strong>
                <p  style="text-indent:20">Agradeciendo su comprension y atención a este estado de cuenta me suscribo de
                                            usted, muy cordialmente</p>
            </td>
        </tr>
        <tr align="center">
            <td colspan="2">
                <p>Lic. Rosa Lisseth Aldana <br>
                Unidad de Administración Tributaria Municipal</p>
            </td>
        </tr>
    </table>
    
</div>
</body>
</html>
      