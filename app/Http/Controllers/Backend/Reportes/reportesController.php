<?php

namespace App\Http\Controllers\Backend\Reportes;


use App\Models\Contribuyentes;
use App\Models\Empresas;
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

use DateInterval;
use DatePeriod;
use Illuminate\Support\MessageBag;
use Spatie\Permission\Models\Role;

class reportesController extends Controller 
{
    public function estado_cuenta($f1,$f2,$ti,$f3, $id) 
    { 

        $empresa= Empresas
        ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
        ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
        ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
        ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
        ->join('actividad_especifica','empresa.id_actividad_especifica','=','actividad_especifica.id')
        
        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',
        'actividad_economica.rubro','actividad_economica.id as id_act_economica',
        'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica')
        ->find($id);

        //** Inicia calculo de cobro impuesto empresas **/

        $MesNumero=Carbon::createFromDate($f1)->format('d');
        //log::info($MesNumero);

        if($MesNumero<='15')
        {
            $f1=Carbon::parse($f1)->format('Y-m-01');
            $f1=Carbon::parse($f1);
            $InicioPeriodo=Carbon::createFromDate($f1);
            $InicioPeriodo= $InicioPeriodo->format('Y-m-d');
            //log::info('inicio de mes');
        }
        else
            {
            $f1=Carbon::parse($f1)->addMonthsNoOverflow(1)->day(1);
            $InicioPeriodo=Carbon::parse($f1)->addMonthsNoOverflow(1)->day(1)->format('Y-m-d');
            // log::info('fin de mes ');
            }

        $f2=Carbon::parse($f2);
        $f3=Carbon::parse($f3);
        $añoActual=Carbon::now()->format('Y');
        Log::info($f1);
        Log::info($f2);
        //** Inicia - Para determinar el intervalo de años a pagar */
        $monthInicio='01';
        $dayInicio='01';
        $monthFinal='12';
        $dayFinal='31';
        $AñoInicio=Carbon::parse($f1)->format('Y');
        
        $AñoFinal=$f2->format('Y');
        $FechaInicio=Carbon::createFromDate($AñoInicio, $monthInicio, $dayInicio);
        $FechaFinal=Carbon::createFromDate($AñoFinal, $monthFinal, $dayFinal);
        //** Finaliza - Para determinar el intervalo de años a pagar */

       
        //** INICIO - Para obtener SIEMPRE el último día del mes que selecciono el usuario */
        $PagoUltimoDiaMes=Carbon::parse($f2)->endOfMonth()->format('Y-m-d');
        //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */

        //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
        $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
        $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(60)->format('Y-m-d');

        
        $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
        $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
        //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
        


        $calificaciones = calificacion::latest()
        
        ->join('empresa','calificacion.id_empresa','=','empresa.id')
        
        ->select('calificacion.id','calificacion.multa_balance','calificacion.fecha_calificacion','calificacion.tipo_tarifa','calificacion.tarifa','calificacion.estado_calificacion','calificacion.tipo_tarifa','calificacion.estado_calificacion','calificacion.id_estado_licencia_licor',
        'empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono')
    
        ->where('id_empresa', "=", "$id")
        ->first();
        
      


            $intervalo = DateInterval::createFromDateString('1 Year');
          
            $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);
            
            $Cantidad_MesesTotal=0;
            $impuestoTotal=0;
            $impuestos_mora=0;
            $impuesto_año_actual=0;
            $multaPagoExtemporaneo=0;
            $totalMultaPagoExtemporaneo=0;
           
            //** Inicia Foreach para cálculo de impuesto por años */
            foreach ($periodo as $dt) {

                $AñoPago =$dt->format('Y');
               
                $AñoSumado=Carbon::createFromDate($AñoPago, 12, 31);

                $tarifa=calificacion::where('año_calificacion','=',$AñoPago)
                 ->where('id_empresa','=',$id) 
                    ->pluck('pago_mensual') 
                        ->first();
                
         
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
       
                $impuestosValor=(round($tarifa*$CantidadMeses,2));
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
                Log::info($tarifa);
                Log::info($impuestosValor);
                Log::info($impuestos_mora);
                Log::info($impuesto_año_actual);
                
                Log::info($AñoSumado);
                
                Log::info($f2);
                Log::info($divisiondefila);
                
                Log::info($linea);

            }   //** Termina el foreach */

            //** -------Inicia - Cálculo para multas por pago extemporaneo--------- */
            /* -------------------------------------------------------------------
               "Se determina una multa por día en mora, despues de haberse vencido 
               la fecha de pago y una vez haya transcurrido 60 días despues del 
               vencimiento de la fecha limite de pago".
               ------------------------------------------------------------------*/
               $TasaInteresDiaria=($ti/365);
               $InteresTotal=0;
               $MesDeMulta=Carbon::parse($FechaDeInicioMoratorio)->subDays(60);
               $contador=0;
               $fechaFinMeses=$f2->addMonthsNoOverflow(1);
               $intervalo2 = DateInterval::createFromDateString('1 Month');
               $periodo2 = new DatePeriod ($MesDeMulta, $intervalo2, $fechaFinMeses);
                    
               //** Inicia Foreach para cálculo por meses */
                    foreach ($periodo2 as $dt) 
                    {
                       $contador=$contador+1;
                       $divisiondefila=".....................";

  
                        $TarifaAñoMulta=Carbon::parse($MesDeMulta)->format('Y');
                            $Date1=Carbon::parse($MesDeMulta)->day(1);
                            $Date2=Carbon::parse($MesDeMulta)->endOfMonth();
                            
                            $MesDeMultaDiainicial=Carbon::parse($Date1)->format('Y-m-d'); 
                            $MesDeMultaDiaFinal=Carbon::parse($Date2)->format('Y-m-d'); 
                            
                
                        $Fecha60Sumada=Carbon::parse($MesDeMultaDiaFinal)->addDays(60); 
                        Log::info($Fecha60Sumada);
                        Log::info($f3);
                        if($f3>$Fecha60Sumada){
                        $CantidaDiasMesMulta=ceil($Fecha60Sumada->diffInDays($f3));//**le tenia floatdiffInDays y funcinona bien  */
                        }else
                        {
                            $CantidaDiasMesMulta=ceil($Fecha60Sumada->diffInDays($f3));
                            $CantidaDiasMesMulta=-$CantidaDiasMesMulta;
                            
                        }
                        Log::info($CantidaDiasMesMulta);
                        
                       $tarifa=calificacion::where('año_calificacion','=',$TarifaAñoMulta)
                       ->where('id_empresa','=',$id) 
                          ->pluck('pago_mensual') 
                              ->first();
                    
                    $MesDeMulta->addMonthsNoOverflow(1)->format('Y-M');
  

                   //** INICIO- Determinar multa por pago extemporaneo. */
                   if($CantidaDiasMesMulta>0){                                                   
                        if($CantidaDiasMesMulta<=90)
                        {  
                                    $multaPagoExtemporaneo=round(($tarifa*0.05),2);
                                    $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo+$multaPagoExtemporaneo;
                                    $stop="Avanza:Multa";

                        }elseif($CantidaDiasMesMulta>=90)
                                {
                                    $multaPagoExtemporaneo=round(($tarifa*0.10),2);
                                    $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo+$multaPagoExtemporaneo;  
                                    $stop="Avanza:Multa";
                                }

                        //** INICIO-  Cálculando el interes. */
                        $Interes=round((($TasaInteresDiaria*$CantidaDiasMesMulta)/100*$tarifa),2);
                        $InteresTotal=$InteresTotal+$Interes;
                        //** FIN-  Cálculando el interes. */


                        
                    }
                    else
                        { 
                            $Interes=0;
                            $InteresTotal=$InteresTotal;
                            $multaPagoExtemporaneo=$multaPagoExtemporaneo;
                            $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo;
                            $stop="Alto:No multa";
                        }
                   //** FIN-  Determinar multa por pago extemporaneo. */

                   
                   Log::info($contador);
                   Log::info($stop);
                   Log::info($MesDeMultaDiainicial);                   
                   Log::info($MesDeMultaDiaFinal); 
                   Log::info($MesDeMulta);
                       Log::info($multaPagoExtemporaneo);
                       Log::info($totalMultaPagoExtemporaneo);
                       Log::info($Interes);
                       Log::info($InteresTotal);
                       Log::info($divisiondefila);
                }//FIN - Foreach para meses multa
                
                 if($totalMultaPagoExtemporaneo>0 and $totalMultaPagoExtemporaneo<2.86)
                 {
                     $totalMultaPagoExtemporaneo=2.86;
                 }
                 
                //** Para determinar la cantidad de multas por balance por empresa */
                $multasBalance=MultasDetalle::select('monto_multa')
                ->where('id_empresa',$id)
                ->where('id_estado_multa','2')
                    ->get();

                    $Cantidad_multas=0;
                    $monto_pago_multaBalance=0;

                    foreach($multasBalance as $dato){
                        $multaBalance=$dato->monto_multa;

                            if ($multaBalance>0)
                            {
                                $monto_pago_multaBalance= $monto_pago_multaBalance+$multaBalance;
                                $Cantidad_multas=$Cantidad_multas+1;
                            }
                            
                } 
                //** Fin- Determinar la cantidad de multas por empresa */ 
               

           
    
    $fondoFPValor=round($impuestoTotal*0.05,2);
    $totalPagoValor= round($fondoFPValor+$monto_pago_multaBalance+$impuestoTotal+$totalMultaPagoExtemporaneo+$InteresTotal,2);

    //Le agregamos su signo de dollar para la vista al usuario
    $fondoFP= "$". $fondoFPValor;     
    $totalPago="$".$totalPagoValor;
    $impuestos_mora_Dollar="$".$impuestos_mora;
    $impuesto_año_actual_Dollar="$".$impuesto_año_actual;
    $monto_pago_multaDollar="$".$monto_pago_multaBalance;
    $multaPagoExtemporaneoDollar="$".$totalMultaPagoExtemporaneo;
    $InteresTotalDollar="$".$InteresTotal;

//** Finaliza calculo de cobro impuesto empresas **/

    $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $fechaF = Carbon::parse(Carbon::now());
    $mes = $mesesEspañol[($fechaF->format('n')) - 1];
    $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

    
    $view = View::make('backend.admin.Empresas.EstadoCuenta.Estado_cuenta', compact(['FechaDelDia',
    'empresa','impuestos_mora','fondoFPValor','totalPagoValor','impuesto_año_actual',
    'monto_pago_multaBalance','InteresTotal','totalMultaPagoExtemporaneo','PagoUltimoDiaMes','InicioPeriodo'
    
    ]))->render();
    $pdf = App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->loadHTML($view)->setPaper('carta', 'portrait');

    return $pdf->stream();
}
}