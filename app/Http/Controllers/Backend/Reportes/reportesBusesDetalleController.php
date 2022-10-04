<?php

namespace App\Http\Controllers\Backend\Reportes;

use App\Models\Contribuyentes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Faker\Core\Number;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;
use Symfony\Contracts\Service\Attribute\Required;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use PDF;
use App\Models\Empresas;
use App\Models\Usuario;
use App\Models\EstadoEmpresas;
use App\Models\GiroComercial;
use App\Models\ActividadEconomica;
use App\Models\ActividadEspecifica;
use App\Models\Cobros;
use App\Models\calificacion;
use App\Models\CalificacionMatriculas;
use App\Models\CobrosLicenciaLicor;
use App\Models\CobrosMatriculas;
use App\Models\Interes;
use App\Models\LicenciaMatricula;
use App\Models\MatriculasDetalle;
use App\Models\TarifaFija;
use App\Models\TarifaVariable;
use App\Models\MultasDetalle;
use App\Models\MatriculasDetalleEspecifico;
use App\Models\alertas;
use App\Models\alertas_detalle;
use App\Models\BusesDetalle;
use App\Models\CalificacionBuses;
use App\Models\CalificacionRotulo;
use App\Models\CierresReaperturas;
use App\Models\Rotulos;
use App\Models\Traspasos;
use DateInterval;
use DatePeriod;
use Illuminate\Support\MessageBag;
use Spatie\Permission\Models\Role;

class reportesBusesDetalleController extends Controller
{
    public function estado_cuentas_buses_d ($f1,$f2,$ti,$f3,$id)
    {
        log::info([$f1,$f2,$ti,$f3,$id,]);

       
        $calificacionBus = CalificacionBuses::latest()
        ->where('id_contribuyente', $id)
        ->first();

        $f1_original=$f1;

        $buses = BusesDetalle::join('contribuyente','buses_detalle.id_contribuyente','=','contribuyente.id')
        ->join('estado_buses','buses_detalle.id_estado_buses','=','estado_buses.id')

        ->select('buses_detalle.id','buses_detalle.id_contribuyente', 'buses_detalle.fecha_apertura','buses_detalle.nFicha',
        'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar','buses_detalle.estado_especificacion',
        'buses_detalle.nom_empresa','buses_detalle.dir_empresa','buses_detalle.nit_empresa',
        'buses_detalle.tel_empresa','buses_detalle.email_empresa','buses_detalle.r_comerciante',
        
        'contribuyente.nombre as contribuyente', 'contribuyente.apellido',
        'contribuyente.id as id_contribuyente',
        'estado_buses.estado')
                        
    ->find($id);


    $fechahoy=carbon::now()->format('d-m-Y');

    /** Obtener la fecha y días en español y formato tradicional*/
    $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $fechaF = Carbon::parse($fechahoy);
    $mes = $mesesEspañol[($fechaF->format('n')) - 1];
    $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

    $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
    $dia = $dias[(date('N', strtotime($fechaF))) - 1];
    /** FIN - Obtener la fecha y días en español y formato tradicional*/

    $año=carbon::now()->format('y');
  
    $f1_original=$f1;
    $fechaPagaraBuses = $f2;
    $id_buses_detalle = $id;
    $tasa_interes=$ti;
    $Message=0;

    $MesNumero=Carbon::createFromDate($f1)->format('d');

    if($MesNumero<='15')
    {
        $f1=Carbon::parse($f1)->format('Y-m-01');
        $f1=Carbon::parse($f1);
        $InicioPeriodo=Carbon::createFromDate($f1_original);
        $InicioPeriodo= $InicioPeriodo->format('Y-m-d');
        //log::info('inicio de mes');
    }
    else
        {
            $f1=Carbon::parse($f1)->addMonthsNoOverflow(1)->day(1);
            $InicioPeriodo=Carbon::parse($f1_original)->format('Y-m-d');
            log::info('f1_original: '.$f1_original);
            log::info('InicioPeriodo: '.$InicioPeriodo);
            log::info('fin de mes ');
        }


        $f2=Carbon::parse($f2);
        $f3=Carbon::parse($f3);
        $añoActual=Carbon::now()->format('Y');
       
        //** Inicia - Para determinar el intervalo de años a pagar */
        $monthInicio='01';
        $dayInicio='01';
        $monthFinal='12';
        $dayFinal='31';
        $AñoInicio=$f1->format('Y');
        $AñoFinal=$f2->format('Y');
        $FechaInicio=Carbon::createFromDate($AñoInicio, $monthInicio, $dayInicio);
        $FechaFinal=Carbon::createFromDate($AñoFinal, $monthFinal, $dayFinal);
        //** Finaliza - Para determinar el intervalo de años a pagar */
    
     
        //** INICIO - Para obtener SIEMPRE el último día del mes que selecciono el usuario */
        $PagoUltimoDiaMes=Carbon::parse($f2)->endOfMonth()->format('Y-m-d');
        //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */
        Log::info('Pago ultimo dia del mes---->' .$PagoUltimoDiaMes);
    
        //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
        $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
        Log::info('ultimo dia del mes---->' .$UltimoDiaMes);
        $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(30)->format('Y-m-d');
        Log::info($FechaDeInicioMoratorio);
        
        $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
        $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
        //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
        Log::info($DiasinteresMoratorio);
       

    $calificaciones = CalificacionBuses::select('calificacion_buses.id','calificacion_buses.cantidad','calificacion_buses.monto','calificacion_buses.pago_mensual',
    'calificacion_buses.fecha_calificacion','calificacion_buses.estado_calificacion')
   
    ->where('id_buses_detalle', $id)
    ->latest()
    ->first(); 
      

    if($f1->lt($PagoUltimoDiaMes))
            {

                $intervalo = DateInterval::createFromDateString('1 Year');
                $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);

                $Cantidad_MesesTotal=0;
                $impuestoTotal=0;
                $impuestos_mora=0;
                $impuesto_año_actual=0;
                $multaPagoExtemporaneo=0;         
                $totalMultaPagoExtemporaneo=0;

           
                $tarifas=CalificacionBuses::select('monto')
                ->where('id_buses_detalle',$id
                )
                 ->get();

                $tarifa_total=0;
                 foreach($tarifas as $dt)
                 {
                    $tarifa=$dt->monto;
                    $tarifa_total=$tarifa_total+$tarifa;

                 }
                //** Inicia Foreach para cálculo de impuesto por años */
                foreach ($periodo as $dt) {

                    $AñoPago =$dt->format('Y');
                
                    $AñoSumado=Carbon::createFromDate($AñoPago, 12, 31);

                    log::info($tarifa_total);
            
                            if($AñoPago==$AñoFinal)//Stop para cambiar el resultado de la cantidad de meses en la última vuelta del foreach...
                                {
                                    $CantidadMeses=ceil(($f1->floatDiffInRealMonths($PagoUltimoDiaMes)));
                                }
                            else
                                {

                                    $CantidadMeses=ceil(($f1->floatDiffInRealMonths($AñoSumado)));  
                                    $f1=$f1->addYears(1)->month(1)->day(1);
    
                                }

                    //*** calculo */
        
                    $impuestosValor=(round($tarifa_total*$CantidadMeses,2));
                    $impuestoTotal=$impuestoTotal+$impuestosValor;
                    $Cantidad_MesesTotal=$Cantidad_MesesTotal+$CantidadMeses;

                    if($AñoPago==$AñoFinal and $AñoPago<$añoActual)
                    {
                            $impuestos_mora=$impuestos_mora+$impuestosValor;
                            $impuesto_año_actual=$impuesto_año_actual;
                    }
                    else if( $AñoPago==$AñoFinal and $AñoPago==$añoActual)
                    {
                            $impuestos_mora=$impuestos_mora;
                            $impuesto_año_actual=$impuesto_año_actual+$impuestosValor;
                    }else{
                            $impuestos_mora=$impuestos_mora+$impuestosValor;
                            $impuesto_año_actual=$impuesto_año_actual;
                    }

                    $linea="_____________________<<::>>";
                    $divisiondefila=".....................";
    
    

                    Log::info($AñoPago);
                    Log::info($CantidadMeses);
                  
                    Log::info($impuestosValor);
                    Log::info($impuestos_mora);
                    Log::info('año actual '. $impuesto_año_actual);                    
                    Log::info($AñoSumado);                    
                    Log::info($f2);
                    Log::info($divisiondefila);             
                    Log::info($linea);

                }   //** Termina el foreach */

                //** -------Inicia - Cálculo para intereses--------- */

                $TasaInteresDiaria=($tasa_interes/365);
                $InteresTotal=0;
                $MesDeInteres=Carbon::parse($FechaDeInicioMoratorio)->subDays(30);
                $contador=0;
                $fechaFinMeses=$f2->addMonthsNoOverflow(1);
                $intervalo2 = DateInterval::createFromDateString('1 Month');
                $periodo2 = new DatePeriod ($MesDeInteres, $intervalo2, $fechaFinMeses);
                        
                //** Inicia Foreach para cálculo por meses */
                foreach ($periodo2 as $dt) 
                {
                   $contador=$contador+1;
                   $divisiondefila=".....................";

                        $Date1=Carbon::parse($MesDeInteres)->day(1);
                        $Date2=Carbon::parse($MesDeInteres)->endOfMonth();
                        
                        $MesDeInteresDiainicial=Carbon::parse($Date1)->format('Y-m-d'); 
                        $MesDeInteresDiaFinal=Carbon::parse($Date2)->format('Y-m-d'); 
                        
            
                    $Fecha30Sumada=Carbon::parse($MesDeInteresDiaFinal)->addDays(30); 
                    Log::info($Fecha30Sumada);
                    Log::info($f3);
                    if($f3>$Fecha30Sumada){
                    $CantidaDiasMesInteres=ceil($Fecha30Sumada->diffInDays($f3));//**le tenia floatdiffInDays y funcinona bien  */
                    }else
                    {
                        $CantidaDiasMesInteres=ceil($Fecha30Sumada->diffInDays($f3));
                        $CantidaDiasMesInteres=-$CantidaDiasMesInteres;
                        
                    }
                    Log::info($CantidaDiasMesInteres);

                
                $MesDeInteres->addMonthsNoOverflow(1)->format('Y-M');


               //** INICIO- Determinar Interes. */
               if($CantidaDiasMesInteres>0){                                                   
                 
                    $stop="Avanza:interes";    

                    //** INICIO-  Cálculando el interes. */
                    $Interes=round((($TasaInteresDiaria*$CantidaDiasMesInteres)/100*$tarifa_total),2);
                    $InteresTotal=$InteresTotal+$Interes;
                    //** FIN-  Cálculando el interes. */

                }
                else
                    { 
                        $Interes=0;
                        $InteresTotal=$InteresTotal;
                        $multaPagoExtemporaneo=$multaPagoExtemporaneo;
                        $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo;
                        $stop="Alto: Sin interes";
                    }
               //** FIN-  Determinar multa por pago extemporaneo. */

               
               
                    Log::info($contador);
                    Log::info('Mes multa '.$MesDeInteres);
                    Log::info($stop);
                    Log::info($MesDeInteresDiainicial);                   
                    Log::info($MesDeInteresDiaFinal);                 
                    Log::info($multaPagoExtemporaneo);
                    Log::info($totalMultaPagoExtemporaneo);
                    Log::info($Interes);
                    Log::info($InteresTotal);
                    Log::info($divisiondefila);
                }                 
                
                
                $fondoFPValor=round($impuestoTotal*0.05,2);
                $totalPagoValor= round($fondoFPValor+$impuestoTotal+$InteresTotal,2);

                //Le agregamos su signo de dollar para la vista al usuario
                $fondoFP= "$". $fondoFPValor;     
                $totalPago="$".$totalPagoValor;
                $impuestos_mora_Dollar="$".$impuestos_mora;
                $impuesto_año_actual_Dollar="$".$impuesto_año_actual; 
                $InteresTotalDollar="$".$InteresTotal;
               


            
    
    //Configuracion de Reporte en MPDF
    $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
    $mpdf->SetTitle('Alcaldía Metapán | Resolución de Apertura');

            
    // mostrar errores
    $mpdf->showImageErrors = false;

    $logoalcaldia = 'images/logo.png';
    $logoelsalvador = 'images/EscudoSV.png';
    $imgf1 = 'images/imgf1.png';
    
    
    $tabla = "<div class='content'>
                    <img id='logo' src='$logoalcaldia'>
                    <img id='EscudoSV' src='$logoelsalvador'>
                    <h4>ALCALDIA MUNICIPAL DE METAPAN<br>
                    UNIDAD DE ADMINISTRACION TRIBUTARIA MUNICIPAL<br>
                    DEPARTAMENTO DE SANTA ANA, EL SALVADOR C.A</h4>
                    <hr>
            </div>";

    $tabla .= "<table border='0' align='center' style='width: 650px;'>
    <tr>
    <td colspan='2' align='center'><strong><u>N O T I F I C A C I O N</u></strong></td>
    </tr>
    <tr>
    <td align='right' colspan='2'>
        <strong>Metapán, $FechaDelDia</strong>
    </td>
    </tr>
    <tr>
    <td colspan='2' style='font-size: 13;'>
        <p>Señor (a):&nbsp;$buses->contribuyente &nbsp; $buses->apellido<br>
            Dirección:&nbsp;$buses->dir_empresa<br>
            Cuenta Corriente N°:&nbsp;$buses->nFicha<br>
            Empresa o Negocio:&nbsp;$buses->nombre
        </p>
        <br>
        Estimado(a) señor (a):
        <p style='text-indent: 20px;'>En nombre del Concejo Municipal, reciba un afectuoso saludo y deseos de éxito. El
            motivo de la presente es para manifestarle que su estado de cuenta en esta
            Municipalidad es el siguiente:</p>
        <p>
        <br>
            <strong>Impuestos Municipales</strong><br>
            Validez: <strong><u>$FechaDelDia</u></strong><br>
            </p>
            </td>
        <tr>
            <td><hr></td>
            <td><hr></td>
        </tr>
        <tr>
            <th scope='col'>Periodo: &nbsp;&nbsp;desde&nbsp; $InicioPeriodo&nbsp;</th>
            <th scope='col'>&nbsp;&nbsp;hasta&nbsp; $PagoUltimoDiaMes&nbsp;</th>    
        </tr>
        <tr>
            <td align='right'>TASAS POR SERVICIO</td>
            <td align='center'>$impuesto_año_actual_Dollar</td>
        </tr>
        <tr>
            <td align='right'>TASAS POR SERVICIO MORA</td>
            <td align='center'>$impuestos_mora_Dollar</td>
        </tr>
        <tr>
            <td align='right'>INTERESES MORATORIOS</td>
            <td align='center'>$InteresTotalDollar</td>
        </tr>        
        <tr>
            <td align='right'>FONDO F. PATRONALES 5%</td>
            <td align='center'>$fondoFP</td>
        </tr>       
       
        <tr>
            <td><hr></td>
            <td><hr></td>
        </tr>
        <tr>
            <td colspan='2' style='text-indent: 20px;font-family: Arial; text-align: justify;font-size: 13;'>
                <p>
                Por lo que solicito para que comparezca ante esta Administración Tributaria Municipal, a saldar lo adeudado, o a
                solicitar un plan de pago, concediéndose un plazo de treinta días contados a partir de la notificación para que efectúe el
                pago correspondiente bajo la prevención, que de no hacerlo, obligara a esta Municipalidad a certificar su deuda
                pendiente, a fin de que sin tramite alguno, se proceda a iniciar las diligencias judiciales correspondientes. 
                <br><br>
                Agradeciendo su comprension y atención a esta notificación me suscribo de usted, muy cordialmente.
                </p>
            </td>
        </tr>
        <tr align='center'>
            <td colspan='2' align='center'>
                    
                    <img id='imgf1' src='$imgf1'>
            </td>
        </tr>
        </table>";
   
    $stylesheet = file_get_contents('css/cssconsolidado.css');
    $mpdf->WriteHTML($stylesheet,1);
    $mpdf->SetMargins(0, 0, 5);


    //$mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');

    $mpdf->WriteHTML($tabla,2);
    $mpdf->Output();

            }
        }
}