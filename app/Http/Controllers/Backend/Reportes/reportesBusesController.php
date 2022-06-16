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

class reportesBusesController extends Controller
{
    public function estado_cuenta_buses($f1, $f2, $ti, $ib, $id_empresa) 
    {   
        log::info([$f1, $f2, $ti, $ib, $id_empresa]);

        $fecha_interes_moratorio=carbon::now();
        $id=$ib;
    
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
                $f3=Carbon::parse($fecha_interes_moratorio);
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
                $DTF=Carbon::parse($f2)->addMonthsNoOverflow(1)->day(1);
                $PagoUltimoDiaMes=$DTF->subDays(1)->format('Y-m-d');
                //Log::info($PagoUltimoDiaMes);
                //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */
        
                //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
                $f_inicio=Carbon::parse($f1)->addMonthsNoOverflow(2)->day(1);
                $UltimoDiaMes=$f_inicio->subDays(1);
                //Log::info( $UltimoDiaMes);
                $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(30)->format('Y-m-d');
        
                $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
                Log::info('Inicio moratorio inicia aqui');
                Log::info($FechaDeInicioMoratorio);
                $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
                //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
        
            
              
                //** Inicia - Para obtener la tasa de interes más reciente */
                $Tasainteres=$ti;
                //** Finaliza - Para obtener la tasa de interes más reciente */
        
                $calificacion=BusesDetalle        
                ::join('empresa','buses_detalle.id_empresa','=','empresa.id')
               
                                      
                ->select('buses_detalle.id as id_buses_detalle', 'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.tarifa',
                'buses_detalle.fecha_apertura','buses_detalle.estado_especificacion' ,      
                       'empresa.id as id_empresa', 'empresa.nombre as empresa','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral',
                        'empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta',
                        'empresa.telefono')
        
                                    
                ->find($id);
        
             
                $calificaciones = CalificacionBuses::select('calificacion_buses.id','calificacion_buses.cantidad','calificacion_buses.monto','calificacion_buses.pago_mensual',
                'calificacion_buses.fecha_calificacion','calificacion_buses.estado_calificacion')
               
                ->where('id_buses_detalle', $id)
                ->latest()
                ->first(); 
                  
                 //Termina consulta para mostrar los rótulos que pertenecen a una sola empresa
             
                
        
                        $intervalo = DateInterval::createFromDateString('1 Year');
                        $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);
        
                        $Cantidad_MesesTotal=0;
                        $impuestoTotal=0;
                        $impuestos_mora=0;
                        $impuesto_año_actual=0;
                        $multaPagoExtemporaneo=0;         
                        $totalMultaPagoExtemporaneo=0;
        
                   
                        $tarifas=CalificacionBuses::select('monto')
                        ->where('id_buses_detalle',$id)
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
                            Log::info($tarifa);
                            Log::info($impuestosValor);
                            Log::info($impuestos_mora);
                            Log::info('año actual '. $impuesto_año_actual);                    
                            Log::info($AñoSumado);                    
                            Log::info($f2);
                            Log::info($divisiondefila);             
                            Log::info($linea);
        
                        }   //** Termina el foreach */
        
                        //** -------Inicia - Cálculo para intereses--------- */
        
                        $TasaInteresDiaria=($Tasainteres/365);
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
                        'actividad_economica.rubro',
                        'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica')
                        ->find($id_empresa); 
    
       //** Finaliza calculo de cobro licencia licor **/

       $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
       $fechaF = Carbon::parse(Carbon::now());
       $mes = $mesesEspañol[($fechaF->format('n')) - 1];
       $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');


       $view = View::make('backend.admin.Buses.Reportes.estado_cuenta_buses', compact([

                   'FechaDelDia',
                   'empresa',
                   'fondoFP',     
                   'totalPago',
                   'impuestos_mora_Dollar',
                   'impuesto_año_actual_Dollar',
                   'InteresTotalDollar',
                   'InicioPeriodo',
                   'PagoUltimoDiaMes',




       ]))->render();

       $pdf = App::make('dompdf.wrapper');
       $pdf->getDomPDF()->set_option("enable_php", true);
       $pdf->loadHTML($view)->setPaper('carta', 'portrait');

       return $pdf->stream();

    }


}//** Cierre final */
