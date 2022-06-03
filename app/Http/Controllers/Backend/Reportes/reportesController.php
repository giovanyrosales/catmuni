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
use App\Models\Cierres;
use App\Models\Traspasos;
use DateInterval;
use DatePeriod;
use Illuminate\Support\MessageBag;
use Spatie\Permission\Models\Role;

class reportesController extends Controller 
{
    public function estado_cuenta($f1,$f2,$ti,$f3,$tf,$id) 
    { 
        log::info([$f1,$f2,$ti,$f3,$id,$tf]);
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

                /**¨Para detectar los cobros especiales y darle su tarifa */
                if($empresa->id_actividad_especifica==118)
                {
                    $tarifa=$tf;
                } else if($empresa->id_actividad_especifica==120)
                        {
                            $tarifa=$tf; 
                        }else if($empresa->id_actividad_especifica==121)
                                {
                                    $tarifa=$tf;
                                }else if($empresa->id_actividad_especifica==122)
                                        {
                                            $tarifa=$tf;
                                        }else if($empresa->id_actividad_especifica==127)
                                                {
                                                    $tarifa=$tf;
                                                }else{
                                                    
                                                    $tarifa=calificacion::where('año_calificacion','=',$AñoPago)
                                                    ->where('id_empresa','=',$id) 
                                                    ->pluck('pago_mensual') 
                                                        ->first();
               
                                                    }
                //**¨Fin detectar los cobros especiales */
         
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
                        
                /**¨Para detectar los cobros especiales y darle su tarifa */
                if($empresa->id_actividad_especifica==118)
                {
                    $tarifaMulta=$tf;
                } else if($empresa->id_actividad_especifica==120)
                        {
                            $tarifaMulta=$tf;
                        }else if($empresa->id_actividad_especifica==121)
                                {
                                    $tarifaMulta=$tf;
                                }else if($empresa->id_actividad_especifica==122)
                                        {
                                            $tarifaMulta=$tf;
                                        }else if($empresa->id_actividad_especifica==127)
                                                {
                                                    $tarifaMulta=$tf;
                                                }else{
                                                    
                                                    $tarifaMulta=calificacion::where('año_calificacion','=',$TarifaAñoMulta)
                                                    ->where('id_empresa','=',$id) 
                                                       ->pluck('pago_mensual') 
                                                           ->first();
               
                                                    }

                //**¨Fin detectar los cobros especiales */
                    
                    $MesDeMulta->addMonthsNoOverflow(1)->format('Y-M');
  

                   //** INICIO- Determinar multa por pago extemporaneo. */
                   if($CantidaDiasMesMulta>0){                                                   
                        if($CantidaDiasMesMulta<=90)
                        {  
                                    $multaPagoExtemporaneo=round(($tarifaMulta*0.05),2);
                                    $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo+$multaPagoExtemporaneo;
                                    $stop="Avanza:Multa";

                        }elseif($CantidaDiasMesMulta>=90)
                                {
                                    $multaPagoExtemporaneo=round(($tarifaMulta*0.10),2);
                                    $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo+$multaPagoExtemporaneo;  
                                    $stop="Avanza:Multa";
                                }

                        //** INICIO-  Cálculando el interes. */
                        $Interes=round((($TasaInteresDiaria*$CantidaDiasMesMulta)/100*$tarifaMulta),2);
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
    $fondoFPValor="$".number_format($fondoFPValor, 2, '.', ',');   
    $totalPagoValor="$".number_format($totalPagoValor, 2, '.', ',');
    $impuestos_mora="$".number_format($impuestos_mora, 2, '.', ',');
    $impuesto_año_actual="$".number_format($impuesto_año_actual, 2, '.', ',');
    $monto_pago_multaBalance="$".number_format($monto_pago_multaBalance, 2, '.', ',');
    $totalMultaPagoExtemporaneo="$".number_format($totalMultaPagoExtemporaneo, 2, '.', ',');
    $InteresTotal="$".number_format($InteresTotal, 2, '.', ',');

//** Finaliza calculo de cobro impuesto empresas **/

    $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $fechaF = Carbon::parse(Carbon::now());
    $mes = $mesesEspañol[($fechaF->format('n')) - 1];
    $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

    
    $view = View::make('backend.admin.Empresas.EstadoCuenta.Estado_cuenta', compact(['FechaDelDia',
    'empresa','impuestos_mora','fondoFPValor','totalPagoValor','impuesto_año_actual',
    'monto_pago_multaBalance','InteresTotal','totalMultaPagoExtemporaneo','PagoUltimoDiaMes',
    'InicioPeriodo','Cantidad_multas',
    
    ]))->render();
    $pdf = App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->loadHTML($view)->setPaper('carta', 'portrait');

    return $pdf->stream();
}


public function aviso($id) 
    { 
        $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaF = Carbon::parse(Carbon::now());
        $mes = $mesesEspañol[($fechaF->format('n')) - 1];
        $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');
        
        $cantidad=0;
        $alerta_aviso=alertas_detalle::where('id_empresa',$id)
        ->where('id_alerta','1')
        ->pluck('cantidad')
        ->first();

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


        $view = View::make('backend.admin.Empresas.Reportes.Aviso', compact(['FechaDelDia',
        'empresa',
        

        ]))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($view)->setPaper('carta', 'portrait');
    
        if($alerta_aviso===null){

            $cantidad_avisos=$cantidad+1;

            $registro = new alertas_detalle();
            $registro->id_empresa = $id;
            $registro->id_alerta ='1';
            $registro->cantidad = $cantidad_avisos;
            $registro->save();
        }else if($alerta_aviso==0){

            $cantidad=$alerta_aviso+1;

            alertas_detalle::where('id_empresa',$id)
            ->where('id_alerta','1')
            ->update([
                        'cantidad' =>$cantidad,              
                    ]);
             }
                else{
                        $cantidad=$alerta_aviso+1;

                        alertas_detalle::where('id_empresa',$id)
                        ->where('id_alerta','1')
                        ->update([
                                    'cantidad' =>$cantidad,              
                                ]);
                    }

        return $pdf->stream();
        
    }

    public function notificacion($f1,$f2,$ti,$f3,$id) 
        { 
            log::info([$f1,$f2,$ti,$f3,$id]);
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
                
                                                        
                    //**¨Fin detectar los cobros especiales */
            
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
                            
                   
                                                        
                            $tarifaMulta=calificacion::where('año_calificacion','=',$TarifaAñoMulta)
                                ->where('id_empresa','=',$id) 
                                ->pluck('pago_mensual') 
                                ->first();
                
                                                        

                    //**¨Fin detectar los cobros especiales */
                        
                        $MesDeMulta->addMonthsNoOverflow(1)->format('Y-M');
    

                    //** INICIO- Determinar multa por pago extemporaneo. */
                    if($CantidaDiasMesMulta>0){                                                   
                            if($CantidaDiasMesMulta<=90)
                            {  
                                        $multaPagoExtemporaneo=round(($tarifaMulta*0.05),2);
                                        $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo+$multaPagoExtemporaneo;
                                        $stop="Avanza:Multa";

                            }elseif($CantidaDiasMesMulta>=90)
                                    {
                                        $multaPagoExtemporaneo=round(($tarifaMulta*0.10),2);
                                        $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo+$multaPagoExtemporaneo;  
                                        $stop="Avanza:Multa";
                                    }

                            //** INICIO-  Cálculando el interes. */
                            $Interes=round((($TasaInteresDiaria*$CantidaDiasMesMulta)/100*$tarifaMulta),2);
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
        $fondoFPValor="$".number_format($fondoFPValor, 2, '.', ',');   
        $totalPagoValor="$".number_format($totalPagoValor, 2, '.', ',');
        $impuestos_mora="$".number_format($impuestos_mora, 2, '.', ',');
        $impuesto_año_actual="$".number_format($impuesto_año_actual, 2, '.', ',');
        $monto_pago_multaBalance="$".number_format($monto_pago_multaBalance, 2, '.', ',');
        $totalMultaPagoExtemporaneo="$".number_format($totalMultaPagoExtemporaneo, 2, '.', ',');
        $InteresTotal="$".number_format($InteresTotal, 2, '.', ',');

    //** Finaliza calculo de cobro impuesto empresas **/

        $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaF = Carbon::parse(Carbon::now());
        $mes = $mesesEspañol[($fechaF->format('n')) - 1];
        $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

        
        $view = View::make('backend.admin.Empresas.Reportes.Notificacion', compact(['FechaDelDia',
        'empresa','impuestos_mora','fondoFPValor','totalPagoValor','impuesto_año_actual',
        'monto_pago_multaBalance','InteresTotal','totalMultaPagoExtemporaneo','PagoUltimoDiaMes',
        'InicioPeriodo','Cantidad_multas',
        
        ]))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($view)->setPaper('carta', 'portrait');

        $cantidad=0;
        $alerta_notificacion=alertas_detalle::where('id_empresa',$id)
        ->where('id_alerta','2')
        ->pluck('cantidad')
        ->first();

        if($alerta_notificacion===null){

            $cantidad_notificaciones=$cantidad+1;

            $registro = new alertas_detalle();
            $registro->id_empresa = $id;
            $registro->id_alerta ='2';
            $registro->cantidad = $cantidad_notificaciones;
            $registro->save();

        }else if($alerta_notificacion==0)
        {
            $cantidad=$alerta_notificacion+1;

            alertas_detalle::where('id_empresa',$id)
            ->where('id_alerta','2')
            ->update([
                        'cantidad' =>$cantidad,              
                    ]);
             }
             else
                    { $cantidad=$alerta_notificacion+1;

                        alertas_detalle::where('id_empresa',$id)
                        ->where('id_alerta','2')
                        ->update([
                                    'cantidad' =>$cantidad,              
                                ]);}


        return $pdf->stream();
    }

public function estado_cuenta_licor($f1,$f2,$id){ 


    $idusuario = Auth::id();
    $MesNumero=Carbon::createFromDate($f1)->format('d');
    //log::info($MesNumero);

    if($MesNumero<='15')
    {
        $f1=Carbon::parse($f1)->format('Y-m-01');
        $f1=Carbon::parse($f1);
        $InicioPeriodo=Carbon::createFromDate($f1);
        $InicioPeriodo= $InicioPeriodo->format('Y-01-01');
        //log::info('inicio de mes');
    }
    else
        {
         $f1=Carbon::parse($f1)->addMonthsNoOverflow(1)->day(1);
         $InicioPeriodo=Carbon::parse($f1)->format('Y-01-01');
        // log::info('fin de mes ');
         }

   
    $f2=Carbon::parse($f2);
    $FechaPagara=Carbon::parse($f2)->format('Y-12-31');
    $añoActual=Carbon::now()->format('Y');
    $fechahoy=carbon::now();
   
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


    $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
    Log::info('ultimo dia del mes---->' .$UltimoDiaMes);

        
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
        ->find($id); 
        $nombre_empresa= $empresa->nombre;

     
            $intervalo = DateInterval::createFromDateString('1 Year');
            $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);
            $multa=0;
            $multaTotalLicor=0;
           
                 /** Calculando las licencias*/
                 $Cantidad_licencias=0;
                 $monto_pago_licencia=0;
                 $fila='------------------';
                 $fila2='_______________________';
                 foreach ($periodo as $dt) {

                    
                    $AñoCancelar =$dt->format('Y');
                    $AñoSumadoLicencia=Carbon::createFromDate($AñoCancelar, 12, 31);
                    $monto_licencia=calificacion::where('año_calificacion','=', $AñoCancelar)
                    ->where('id_empresa','=',$id) 
                       ->pluck('licencia') 
                           ->first();
                           
                   $año_calificacion=calificacion::where('año_calificacion','=', $AñoCancelar)
                   ->where('id_empresa','=',$id) 
                      ->pluck('año_calificacion') 
                          ->first();
   
                   $id_estado_licencia=calificacion::where('año_calificacion','=', $AñoCancelar)
                   ->where('id_empresa','=',$id) 
                       ->pluck('id_estado_licencia_licor') 
                          ->first();
                    
                    log::info($año_calificacion);
                    log::info($id_estado_licencia);
                    log::info($monto_licencia);
                    log::info($fila);
                    $FechaLimiteVariable=Carbon::parse($dt)->month(1)->day(15);

                    if($id_estado_licencia=='2' and $año_calificacion<$añoActual)
                    {
                             $monto_pago_licencia= $monto_pago_licencia+$monto_licencia;
                             $Cantidad_licencias=$Cantidad_licencias+1;
                            
                             Log::info($monto_pago_licencia);
                             Log::info($Cantidad_licencias);

                             $Cantidadsemanas=ceil(($FechaLimiteVariable->floatDiffInRealWeeks( $AñoSumadoLicencia)));
                             $multa=$Cantidadsemanas*$monto_licencia;
                             $multaTotalLicor= $multaTotalLicor+$multa;
                             log::info($FechaLimiteVariable);  
                             log::info($AñoSumadoLicencia);  
                             Log::info('IF1- Con Multa');
                             log::info($multa);
                             log::info($Cantidadsemanas);
                             log::info($fila2);
                    }else if($id_estado_licencia=='2' and $año_calificacion==$añoActual)
                    {
                        if($fechahoy>$FechaLimiteVariable)
                        {
 
                         $monto_pago_licencia= $monto_pago_licencia+$monto_licencia;
                         $Cantidad_licencias=$Cantidad_licencias+1;
               
                         Log::info($monto_pago_licencia);
                         Log::info($Cantidad_licencias);
                 
                         $Cantidadsemanas=ceil(($FechaLimiteVariable->floatDiffInRealWeeks($fechahoy)));
                                
                        $multa=$Cantidadsemanas*$monto_licencia;
                        $multaTotalLicor= $multaTotalLicor+$multa;
                        log::info($FechaLimiteVariable);  
                        log::info($fechahoy);  
                         Log::info('IF2- Con Multa');
                         log::info($multa);
                         log::info($Cantidadsemanas);
                         log::info($fila2);
 
                        }else 
                             {
                                 $monto_pago_licencia= $monto_pago_licencia+$monto_licencia;
                                 $Cantidad_licencias=$Cantidad_licencias+1;
                         
                                 Log::info($monto_pago_licencia);
                                 Log::info($Cantidad_licencias);
                                 $multaTotalLicor=$multaTotalLicor;
                                 Log::info('IF3 - Sin Multa');
                                 log::info($multa);
                                 log::info($fila2);
                             }
 
                    }               
                 }//** Temrtmina foreach */


             //** Fin- Determinar si el permiso de una licencia ya fue pagada*/ 

     
            $totalPagoValor= round($multaTotalLicor+$monto_pago_licencia,2);
            
            //Dando formato a las cantidades finales
            $multa=number_format($multa, 2, '.', ',');
            $multaTotalLicorDecimal=round($multaTotalLicor,2);
            $totalPagoValorDecimal=round($totalPagoValor, 2);
            $multaTotalLicor=number_format($multaTotalLicor, 2, '.', ',');
            $totalPagoValor=number_format($totalPagoValor, 2, '.', ',');
            

        //** Finaliza calculo de cobro licencia licor **/

        $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaF = Carbon::parse(Carbon::now());
        $mes = $mesesEspañol[($fechaF->format('n')) - 1];
        $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

        
        $view = View::make('backend.admin.Empresas.Reportes.Estado_cuenta_licor', compact([

                    'FechaDelDia',
                    'empresa',
                    'FechaPagara',
                    'InicioPeriodo',
                    'multaTotalLicor',
                    'monto_pago_licencia',
                    'totalPagoValor',

        ]))->render();

        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($view)->setPaper('carta', 'portrait');

        return $pdf->stream();

    }


    public function estado_cuenta_aparatos($f1,$f2,$ap,$id){ 

        $fechaPagaraAparatos=carbon::parse($f2)->format('Y-12-31');
        $id_matriculadetalleAparatos=$ap;

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
             $InicioPeriodo=Carbon::parse($f1)->format('Y-m-d');
            // log::info('fin de mes ');
             }
    
        
        $f2=Carbon::parse($fechaPagaraAparatos);
        $f3=carbon::now();
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
    

    
                    $añoActual=carbon::now()->format('Y');
             
                    $fecha_limite=Carbon::createFromDate($añoActual,03, 31);
                    $fechahoy=carbon::now();
                    //$fechahoy='2022-02-17';
    
                     $Cantidad_matriculas=0;
                     $monto_pago_matricula=0;
                     $multa=0;
    
                     $intervalo = DateInterval::createFromDateString('1 Year');
                     $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);
          
                     $fila='------------------';
                     $fila2='_______________________';
                     foreach ($periodo as $dt) {
                        $AñoPago =$dt->format('Y');
                        $año_calificacion=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleAparatos)
                        ->where('año_calificacion',$AñoPago)
                        ->pluck('año_calificacion')
                        ->first();
    
                        $id_estado_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleAparatos)
                        ->where('año_calificacion',$AñoPago)
                        ->pluck('id_estado_matricula')
                        ->first();
    
                        $monto_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleAparatos)
                        ->where('año_calificacion',$AñoPago)
                        ->pluck('monto_matricula')
                        ->first();
                        
                        log::info($año_calificacion);
                        log::info($id_estado_matricula);
                        log::info($monto_matricula);
                        log::info($fila);
    
                       if($id_estado_matricula=='2' and $año_calificacion<$añoActual)
                       {
                                $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                                $Cantidad_matriculas=$Cantidad_matriculas+1;
                                $multa= $multa+$monto_matricula;
                                Log::info($monto_pago_matricula);
                                Log::info($Cantidad_matriculas);
                                Log::info($multa);
                                Log::info('IF1- Con Multa');
                                log::info($fila2);
                       }else if($id_estado_matricula=='2' and $año_calificacion===$añoActual)
                       {
                           if($fechahoy>$fecha_limite)
                           {
    
                            $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                            $Cantidad_matriculas=$Cantidad_matriculas+1;
                            $multa= $multa+$monto_matricula;
                            Log::info($monto_pago_matricula);
                            Log::info($Cantidad_matriculas);
                            Log::info($multa);
                            Log::info('IF2- Con Multa');
                            log::info($fila2);
    
                           }else 
                                {
                                    $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                                    $Cantidad_matriculas=$Cantidad_matriculas+1;
                                    $multa=$multa;
                                    Log::info($monto_pago_matricula);
                                    Log::info($Cantidad_matriculas);
                                    Log::info($multa);
                                    Log::info('IF3 - Sin Multa');
                                    log::info($fila2);
                                }
    
                       }                                     
                }//** Finaliza foreach -periodo- */
                 //** Fin- Determinar si el permiso de una matricula ya fue pagada y Determinar multa  matricula */ 
    
                     
    
    
                 Log::info($monto_pago_matricula);
                 Log::info($Cantidad_matriculas);
    
                $fondoFPValor=round(($monto_pago_matricula*0.05),2);
                $totalPagoValor= round($fondoFPValor+$monto_pago_matricula+$multa,2);

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
                ->find($id); 
                
        //** Finaliza calculo de cobro licencia licor **/

        $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaF = Carbon::parse(Carbon::now());
        $mes = $mesesEspañol[($fechaF->format('n')) - 1];
        $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');


        $view = View::make('backend.admin.Empresas.Reportes.Estado_cuenta_aparatos', compact([

                    'FechaDelDia',
                    'empresa',
                    'fechaPagaraAparatos',
                    'InicioPeriodo',
                    'fondoFPValor',
                    'monto_pago_matricula',
                    'multa',
                    'totalPagoValor',

        ]))->render();

        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($view)->setPaper('carta', 'portrait');

        return $pdf->stream();
    }

    public function estado_cuenta_sinfonolas($f1,$f2,$is,$ti,$id){ 

        $MesNumero=Carbon::createFromDate($f1)->format('d');
      
        $fechaPagaraSinfonolas=$f2;
        $id_matriculadetalleSinfonolas=$is;
        $tasa_interes=$ti;
        $fecha_interesMoratorio=Carbon::now()->format('Y-m-d');
        $Message=0;
    
        if($MesNumero<='15')
        {
            $f1=Carbon::parse($f1)->format('Y-m-01');
            $f1=Carbon::parse($f1);
            $InicioPeriodo=Carbon::createFromDate($f1);
            $InicioPeriodo= $InicioPeriodo->format('Y-m-d');
            log::info('inicio de mes');
        }
        else
            {
             $f1=Carbon::parse($f1)->addMonthsNoOverflow(1)->day(1);
             $InicioPeriodo=Carbon::parse($f1)->format('Y-m-d');
            log::info('fin de mes ');
             }
        $f2=Carbon::parse($f2);
        $f3=Carbon::parse($fecha_interesMoratorio);
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
        $PagoUltimoDiaMes=Carbon::parse($fechaPagaraSinfonolas)->endOfMonth()->format('Y-m-d');
        //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */
    
         //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
         $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
         $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(60)->format('Y-m-d');
    
         Log::info($FechaDeInicioMoratorio);
         $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
         $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
         //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
         Log::info($DiasinteresMoratorio);
       
         $calificacionesSinfonolas = CalificacionMatriculas::latest()
            
         ->join('matriculas_detalle','calificacion_matriculas.id_matriculas_detalle','=','matriculas_detalle.id')
         
         ->select('calificacion_matriculas.id','calificacion_matriculas.nombre_matricula','calificacion_matriculas.cantidad','calificacion_matriculas.monto_matricula','calificacion_matriculas.pago_mensual','calificacion_matriculas.año_calificacion','calificacion_matriculas.estado_calificacion','calificacion_matriculas.id_estado_matricula',
         'matriculas_detalle.id as id_matriculadetalle','matriculas_detalle.id_empresa',)
     
         ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleSinfonolas)
         ->first();
    

    
                $intervalo = DateInterval::createFromDateString('1 Year');
                $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);
    
                $Cantidad_MesesTotal=0;
                $impuestoTotal=0;
                $impuestos_mora=0;
                $impuesto_año_actual=0;
                $multaPagoExtemporaneo=0;
             
                $totalMultaPagoExtemporaneo=0;
               
                //** Inicia Foreach para cálculo de impuesto por años de la matricula mesas de billar */
                foreach ($periodo as $dt) {
    
                    $AñoPago =$dt->format('Y');
                   
                    $AñoSumado=Carbon::createFromDate($AñoPago, 12, 31);
    
                    
                    $tarifa=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                    ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleSinfonolas)
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
                   $TasaInteresDiaria=($tasa_interes/365);
                   $InteresTotal=0;
                   $MesDeMulta=Carbon::parse($FechaDeInicioMoratorio)->subDays(60);
                   $contador=0;
                   $fechaFinMeses=$f2->addMonthsNoOverflow(1);
                   $intervalo2 = DateInterval::createFromDateString('1 Month');
                   $periodo2 = new DatePeriod ($MesDeMulta, $intervalo2, $fechaFinMeses);
                        
                   //** Inicia Foreach para cálculo de multas por por pago extemporaneos e interese moratorios */
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
                            $CantidaDiasMesMulta=ceil($Fecha60Sumada->diffInDays($f3)); //**le tenia floatdiffInDays y funcinona bien  */
                            }else
                            {
                                $CantidaDiasMesMulta=ceil($Fecha60Sumada->diffInDays($f3));
                                $CantidaDiasMesMulta=-$CantidaDiasMesMulta;
                                
                            }
                            Log::info($CantidaDiasMesMulta);
                            
                            $tarifa=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                              ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleSinfonolas)
                                  ->pluck('pago_mensual') 
                                      ->first();
    
                            $monto_Sinfonolas=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                              ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleSinfonolas)
                                  ->pluck('monto_matricula') 
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
    
                 //** Para determinar si el permiso de una matricula ya fue pagada y Determinar multa por permiso matricula */ */
    
                    $añoActual=carbon::now()->format('Y');
                    $fecha_limiteSinfonolas=Carbon::createFromDate($añoActual,03, 31);
                    $fechahoy=carbon::now();
    
                       /** Calculando las licencias*/
                       $Cantidad_matriculas=0;
                       $monto_pago_matricula=0;
                       $multa=0;
                       $fila='------------------';
                       $fila2='_______________________';
    
                       //** Inicia Foreach para calcular matriculas y sus multas */
                       foreach ($periodo as $dt) {
    
                        $AñoCancelar=$dt->format('Y');
                       
                        $año_calificacion=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleSinfonolas)
                        ->where('año_calificacion',$AñoCancelar)
                        ->pluck('año_calificacion')
                        ->first();
    
                        $id_estado_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleSinfonolas)
                        ->where('año_calificacion',$AñoCancelar)
                        ->pluck('id_estado_matricula')
                        ->first();
    
                        $monto_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleSinfonolas)
                        ->where('año_calificacion',$AñoCancelar)
                        ->pluck('monto_matricula')
                        ->first();
                        
                        log::info($año_calificacion);
                        log::info($id_estado_matricula);
                        log::info($monto_matricula);
                        log::info($fila);
    
                       if($id_estado_matricula=='2' and $año_calificacion<$añoActual)
                       {
                                $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                                $Cantidad_matriculas=$Cantidad_matriculas+1;
                                $multa= $multa+$monto_matricula;
                                Log::info($monto_pago_matricula);
                                Log::info($Cantidad_matriculas);
                                Log::info($multa);
                                Log::info('IF1- Con Multa');
                                log::info($fila2);
                       }else if($id_estado_matricula=='2' and $año_calificacion===$añoActual)
                       {
                           if($fechahoy>$fecha_limiteSinfonolas)
                           {
    
                            $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                            $Cantidad_matriculas=$Cantidad_matriculas+1;
                            $multa= $multa+$monto_matricula;
                            Log::info($monto_pago_matricula);
                            Log::info($Cantidad_matriculas);
                            Log::info($multa);
                            Log::info('IF2- Con Multa');
                            log::info($fila2);
    
                           }else 
                                {
                                    $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                                    $Cantidad_matriculas=$Cantidad_matriculas+1;
                                    $multa=$multa;
                                    Log::info($monto_pago_matricula);
                                    Log::info($Cantidad_matriculas);
                                    Log::info($multa);
                                    Log::info('IF3 - Sin Multa');
                                    log::info($fila2);
                                } 
                        }
                    } //** Finaliza foreach para calcular multa matricula y calculo de las matriculas */                                   

    
                $fondoFPValor=round(($impuestoTotal*0.05)+($monto_pago_matricula*0.05),2);
                $totalPagoValor= round($fondoFPValor+$monto_pago_matricula+$impuestoTotal+$totalMultaPagoExtemporaneo+$InteresTotal+$multa,2);
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
                ->find($id); 

                //** Agregando formato de número */
                $fondoFPValor=number_format($fondoFPValor, 2, '.', ',');
                $impuestos_mora=number_format($impuestos_mora, 2, '.', ',');
                $impuesto_año_actual=number_format($impuesto_año_actual, 2, '.', ',');
                $totalMultaPagoExtemporaneo=number_format($totalMultaPagoExtemporaneo, 2, '.', ',');
                $InteresTotal=number_format($InteresTotal, 2, '.', ',');
                $multa=number_format($multa, 2, '.', ',');
                $monto_pago_matricula=number_format($monto_pago_matricula, 2, '.', ',');
                $totalPagoValor=number_format($totalPagoValor, 2, '.', ',');

     //** Finaliza calculo de cobro licencia licor **/

     $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
     $fechaF = Carbon::parse(Carbon::now());
     $mes = $mesesEspañol[($fechaF->format('n')) - 1];
     $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');


     $view = View::make('backend.admin.Empresas.Reportes.Estado_cuenta_sinfonolas', compact([

                 'FechaDelDia',
                 'empresa',
                 'PagoUltimoDiaMes',
                 'InicioPeriodo',
                 'fondoFPValor',
                 'impuestos_mora',
                 'impuesto_año_actual',
                 'totalMultaPagoExtemporaneo',
                 'monto_pago_matricula',
                 'InteresTotal',
                 'multa',
                 'totalPagoValor',

     ]))->render();

     $pdf = App::make('dompdf.wrapper');
     $pdf->getDomPDF()->set_option("enable_php", true);
     $pdf->loadHTML($view)->setPaper('carta', 'portrait');

     return $pdf->stream();
 }

 public function estado_cuenta_maquinas($f1,$f2,$im,$ti,$id){ 

    $fechaPagaraMaquinas=$f2;
    $id_matriculadetalleMaquinas=$im;
    $tasa_interes=$ti;
    $Message=0;

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
         $InicioPeriodo=Carbon::parse($f1)->format('Y-m-d');
        // log::info('fin de mes ');
         }

    
    $f2=Carbon::parse($f2);
    $f3=Carbon::now()->format('Y-m-d');
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
   
     $calificacionesMaquinas = CalificacionMatriculas::latest()
        
     ->join('matriculas_detalle','calificacion_matriculas.id_matriculas_detalle','=','matriculas_detalle.id')
     
     ->select('calificacion_matriculas.id','calificacion_matriculas.nombre_matricula','calificacion_matriculas.cantidad','calificacion_matriculas.monto_matricula','calificacion_matriculas.pago_mensual','calificacion_matriculas.año_calificacion','calificacion_matriculas.estado_calificacion','calificacion_matriculas.id_estado_matricula',
     'matriculas_detalle.id as id_matriculadetalle','matriculas_detalle.id_empresa',)
 
     ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMaquinas)
     ->first();


            $intervalo = DateInterval::createFromDateString('1 Year');
            $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);

            $Cantidad_MesesTotal=0;
            $impuestoTotal=0;
            $impuestos_mora=0;
            $impuesto_año_actual=0;

           
            //** Inicia Foreach para cálculo de impuesto por años de la matricula mesas de billar */
            foreach ($periodo as $dt) {

                $AñoPago =$dt->format('Y');
               
                $AñoSumado=Carbon::createFromDate($AñoPago, 12, 31);

                
                $tarifa=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMaquinas)
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

            //** -------Inicia - Cálculo para determinar el interes moratorio--------- */

               $TasaInteresDiaria=($tasa_interes/365);
               $InteresTotal=0;
               $MesMora=Carbon::parse($FechaDeInicioMoratorio)->subDays(30);
               Log::info( $MesMora);
               $contador=0;
               $fechaFinMeses=$f2->addMonthsNoOverflow(1);
               $intervalo2 = DateInterval::createFromDateString('1 Month');
               $periodo2 = new DatePeriod ( $MesMora, $intervalo2, $fechaFinMeses);
                    
               //** Inicia Foreach para cálculo por meses */
                    foreach ($periodo2 as $dt) 
                    {
                       $contador=$contador+1;
                       $divisiondefila=".....................";

                            $Date1=Carbon::parse( $MesMora)->day(1);
                            $Date2=Carbon::parse( $MesMora)->endOfMonth();
                            
                            $MesDeMultaDiainicial=Carbon::parse($Date1)->format('Y-m-d'); 
                            $MesDeMultaDiaFinal=Carbon::parse($Date2)->format('Y-m-d'); 
   
                        $Fecha30Sumada=Carbon::parse($MesDeMultaDiaFinal)->addDays(30); 
                        Log::info($Fecha30Sumada);
                        Log::info($f3);
                        if($f3>$Fecha30Sumada){
                        $CantidaDiasMesMulta=ceil($Fecha30Sumada->diffInDays($f3)); //**le tenia floatdiffInDays y funcinona bien  */
                        }else
                        {
                            $CantidaDiasMesMulta=ceil($Fecha30Sumada->diffInDays($f3));
                            $CantidaDiasMesMulta=-$CantidaDiasMesMulta;
                            
                        }
                        Log::info($CantidaDiasMesMulta);
                        
                        $tarifa=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                          ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMaquinas)
                              ->pluck('pago_mensual') 
                                  ->first();

                    
                        $MesMora->addMonthsNoOverflow(1)->format('Y-M');
  

                   //** INICIO- Determinar interes total a pagar */
                   if($CantidaDiasMesMulta>0){  

                        //** INICIO-  Cálculando el interes. */
                        $Interes=round((($TasaInteresDiaria*$CantidaDiasMesMulta)/100*$tarifa),2);
                        $InteresTotal=$InteresTotal+$Interes;
                        //** FIN-  Cálculando el interes. */  

                    }
                    else
                        { 
                            $Interes=0;
                            $InteresTotal=$InteresTotal;
                        }
                   //** FIN- Determinar interes total a pagar. */

                   
                    Log::info($contador);
                    Log::info($MesDeMultaDiainicial);                   
                    Log::info($MesDeMultaDiaFinal); 
                    Log::info($MesMora);
                    Log::info($Interes);
                    Log::info($InteresTotal);
                    Log::info($divisiondefila);
                    }//FIN - Foreach para meses multa


             //** Para determinar si el permiso de una matricula ya fue pagada y Determinar multa por permiso matricula */ */
             
             $añoActual=carbon::now()->format('Y');
             $fecha_limiteMaquinas=Carbon::createFromDate($añoActual,03, 31);
             $fechahoy=carbon::now();

                /** Calculando las licencias*/
                $Cantidad_matriculas=0;
                $monto_pago_matricula=0;
                $multa=0;
                $fila='------------------';
                $fila2='_______________________';

                //** Inicia Foreach para calcular matriculas y sus multas */
                foreach ($periodo as $dt) {

                 $AñoCancelar=$dt->format('Y');
                
                 $año_calificacion=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleMaquinas)
                 ->where('año_calificacion',$AñoCancelar)
                 ->pluck('año_calificacion')
                 ->first();

                 $id_estado_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleMaquinas)
                 ->where('año_calificacion',$AñoCancelar)
                 ->pluck('id_estado_matricula')
                 ->first();

                 $monto_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleMaquinas)
                 ->where('año_calificacion',$AñoCancelar)
                 ->pluck('monto_matricula')
                 ->first();
                 
                 log::info($año_calificacion);
                 log::info($id_estado_matricula);
                 log::info($monto_matricula);
                 log::info($fila);

                if($id_estado_matricula=='2' and $año_calificacion<$añoActual)
                {
                         $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                         $Cantidad_matriculas=$Cantidad_matriculas+1;
                         $multa= $multa+$monto_matricula;
                         Log::info($monto_pago_matricula);
                         Log::info($Cantidad_matriculas);
                         Log::info($multa);
                         Log::info('IF1- Con Multa');
                         log::info($fila2);
                }else if($id_estado_matricula=='2' and $año_calificacion===$añoActual)
                {
                    if($fechahoy>$fecha_limiteMaquinas)
                    {

                     $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                     $Cantidad_matriculas=$Cantidad_matriculas+1;
                     $multa= $multa+$monto_matricula;
                     Log::info($monto_pago_matricula);
                     Log::info($Cantidad_matriculas);
                     Log::info($multa);
                     Log::info('IF2- Con Multa');
                     log::info($fila2);

                    }else 
                         {
                             $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                             $Cantidad_matriculas=$Cantidad_matriculas+1;
                             $multa=$multa;
                             Log::info($monto_pago_matricula);
                             Log::info($Cantidad_matriculas);
                             Log::info($multa);
                             Log::info('IF3 - Sin Multa');
                             log::info($fila2);
                         } 
                 }
             } //** Finaliza foreach para calcular multa matricula y calculo de las matriculas */                                   
 
             Log::info($monto_pago_matricula);
             Log::info($Cantidad_matriculas);
             Log::info($multa);

            $fondoFPValor=round(($impuestoTotal*0.05)+($monto_pago_matricula*0.05),2);
            $totalPagoValor= round($fondoFPValor+$monto_pago_matricula+$impuestoTotal+$InteresTotal+$multa,2);

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
            ->find($id); 

            //** Agregando formato de número */
            $fondoFPValor=number_format($fondoFPValor, 2, '.', ',');
            $impuestos_mora=number_format($impuestos_mora, 2, '.', ',');
            $impuesto_año_actual=number_format($impuesto_año_actual, 2, '.', ',');
            $InteresTotal=number_format($InteresTotal, 2, '.', ',');
            $multa=number_format($multa, 2, '.', ',');
            $monto_pago_matricula=number_format($monto_pago_matricula, 2, '.', ',');
            $totalPagoValor=number_format($totalPagoValor, 2, '.', ',');

            //** Finaliza calculo de cobro licencia licor **/

            $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $fechaF = Carbon::parse(Carbon::now());
            $mes = $mesesEspañol[($fechaF->format('n')) - 1];
            $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');


            $view = View::make('backend.admin.Empresas.Reportes.Estado_cuenta_maquinas', compact([

                        'FechaDelDia',
                        'empresa',
                        'PagoUltimoDiaMes',
                        'InicioPeriodo',
                        'fondoFPValor',
                        'impuestos_mora',
                        'impuesto_año_actual',
                        'monto_pago_matricula',
                        'InteresTotal',
                        'multa',
                        'totalPagoValor',

            ]))->render();

            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->loadHTML($view)->setPaper('carta', 'portrait');

            return $pdf->stream();
            }

public function estado_cuenta_mesas($f1,$f2,$ime,$ti,$id){ 

    $id_matriculadetalleMesas=$ime;
    $tasa_interes=$ti;

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
         $InicioPeriodo=Carbon::parse($f1)->format('Y-m-d');
        // log::info('fin de mes ');
         }

    
    $f2=Carbon::parse($f2);
    $f3=Carbon::now()->format('Y-m-d');
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

 
    ///** INICIO - Para obtener SIEMPRE el último día del mes que selecciono el usuario */
    $PagoUltimoDiaMes=Carbon::parse($f2)->endOfMonth()->format('Y-m-d');
    //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */
    Log::info('Pago ultimo dia del mes---->' .$PagoUltimoDiaMes);

    //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
    $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
    Log::info('ultimo dia del mes---->' .$UltimoDiaMes);
    $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(60)->format('Y-m-d');
    Log::info($FechaDeInicioMoratorio);
    
    $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
    $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
    //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
    
     Log::info($DiasinteresMoratorio);
   
     $calificacionesMesas = CalificacionMatriculas::latest()
        
     ->join('matriculas_detalle','calificacion_matriculas.id_matriculas_detalle','=','matriculas_detalle.id')
     
     ->select('calificacion_matriculas.id','calificacion_matriculas.nombre_matricula','calificacion_matriculas.cantidad','calificacion_matriculas.monto_matricula','calificacion_matriculas.pago_mensual','calificacion_matriculas.año_calificacion','calificacion_matriculas.estado_calificacion','calificacion_matriculas.id_estado_matricula',
     'matriculas_detalle.id as id_matriculadetalle','matriculas_detalle.id_empresa',)
 
     ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMesas)
     ->first();

            $intervalo = DateInterval::createFromDateString('1 Year');
            $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);

            $Cantidad_MesesTotal=0;
            $impuestoTotal=0;
            $impuestos_mora=0;
            $impuesto_año_actual=0;
            $multaPagoExtemporaneo=0;
         
            $totalMultaPagoExtemporaneo=0;
           
            //** Inicia Foreach para cálculo de impuesto por años de la matricula mesas de billar */
            foreach ($periodo as $dt) {

                $AñoPago =$dt->format('Y');
               
                $AñoSumado=Carbon::createFromDate($AñoPago, 12, 31);

                
                $tarifa=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMesas)
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
               $TasaInteresDiaria=($tasa_interes/365);
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
                        $CantidaDiasMesMulta=ceil($Fecha60Sumada->diffInDays($f3)); //**le tenia floatdiffInDays y funcinona bien  */
                        }else
                        {
                            $CantidaDiasMesMulta=ceil($Fecha60Sumada->diffInDays($f3));
                            $CantidaDiasMesMulta=-$CantidaDiasMesMulta;
                            
                        }
                        Log::info($CantidaDiasMesMulta);
                        
                        $tarifa=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                          ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMesas)
                              ->pluck('pago_mensual') 
                                  ->first();

                        $monto_matricula=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                          ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMesas)
                              ->pluck('monto_matricula') 
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

             //** Para determinar si el permiso de una matricula ya fue pagada y Determinar multa por permiso matricula */ */

             $añoActual=carbon::now()->format('Y');
             $fecha_limiteMesas=Carbon::createFromDate($añoActual,03, 31);
             $fechahoy=carbon::now();

                /** Calculando las licencias*/
                $Cantidad_matriculas=0;
                $monto_pago_matricula=0;
                $multa=0;
                $fila='------------------';
                $fila2='_______________________';

                //** Inicia Foreach para calcular matriculas y sus multas */
                foreach ($periodo as $dt) {

                 $AñoCancelar=$dt->format('Y');
                
                 $año_calificacion=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleMesas)
                 ->where('año_calificacion',$AñoCancelar)
                 ->pluck('año_calificacion')
                 ->first();

                 $id_estado_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleMesas)
                 ->where('año_calificacion',$AñoCancelar)
                 ->pluck('id_estado_matricula')
                 ->first();

                 $monto_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleMesas)
                 ->where('año_calificacion',$AñoCancelar)
                 ->pluck('monto_matricula')
                 ->first();
                 
                 log::info($año_calificacion);
                 log::info($id_estado_matricula);
                 log::info($monto_matricula);
                 log::info($fila);

                if($id_estado_matricula=='2' and $año_calificacion<$añoActual)
                {
                         $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                         $Cantidad_matriculas=$Cantidad_matriculas+1;
                         $multa= $multa+$monto_matricula;
                         Log::info($monto_pago_matricula);
                         Log::info($Cantidad_matriculas);
                         Log::info($multa);
                         Log::info('IF1- Con Multa');
                         log::info($fila2);
                }else if($id_estado_matricula=='2' and $año_calificacion===$añoActual)
                {
                    if($fechahoy>$fecha_limiteMesas)
                    {

                     $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                     $Cantidad_matriculas=$Cantidad_matriculas+1;
                     $multa= $multa+$monto_matricula;
                     Log::info($monto_pago_matricula);
                     Log::info($Cantidad_matriculas);
                     Log::info($multa);
                     Log::info('IF2- Con Multa');
                     log::info($fila2);

                    }else 
                         {
                             $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                             $Cantidad_matriculas=$Cantidad_matriculas+1;
                             $multa=$multa;
                             Log::info($monto_pago_matricula);
                             Log::info($Cantidad_matriculas);
                             Log::info($multa);
                             Log::info('IF3 - Sin Multa');
                             log::info($fila2);
                         } 
                 }
             } //** Finaliza foreach para calcular multa matricula y calculo de las matriculas */                                   
          


             Log::info($monto_pago_matricula);
             Log::info($Cantidad_matriculas);

            $fondoFPValor=round(($impuestoTotal*0.05)+($monto_pago_matricula*0.05),2);
            $totalPagoValor= round($fondoFPValor+$monto_pago_matricula+$impuestoTotal+$totalMultaPagoExtemporaneo+$InteresTotal+$multa,2);
            //Le agregamos su signo de dollar para la vista al usuario

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
            ->find($id); 

            //** Agregando formato de número */
            $fondoFPValor=number_format($fondoFPValor, 2, '.', ',');
            $impuestos_mora=number_format($impuestos_mora, 2, '.', ',');
            $impuesto_año_actual=number_format($impuesto_año_actual, 2, '.', ',');
            $totalMultaPagoExtemporaneo=number_format($totalMultaPagoExtemporaneo, 2, '.', ',');
            $InteresTotal=number_format($InteresTotal, 2, '.', ',');
            $multa=number_format($multa, 2, '.', ',');
            $monto_pago_matricula=number_format($monto_pago_matricula, 2, '.', ',');
            $totalPagoValor=number_format($totalPagoValor, 2, '.', ',');

            //** Finaliza calculo de cobro licencia licor **/

            $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $fechaF = Carbon::parse(Carbon::now());
            $mes = $mesesEspañol[($fechaF->format('n')) - 1];
            $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');


            $view = View::make('backend.admin.Empresas.Reportes.Estado_cuenta_mesas', compact([

                        'FechaDelDia',
                        'empresa',
                        'PagoUltimoDiaMes',
                        'InicioPeriodo',
                        'fondoFPValor',
                        'impuestos_mora',
                        'impuesto_año_actual',
                        'totalMultaPagoExtemporaneo',
                        'monto_pago_matricula',
                        'InteresTotal',
                        'multa',
                        'totalPagoValor',


            ]))->render();

            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->loadHTML($view)->setPaper('carta', 'portrait');

            return $pdf->stream();
            }
public function traspaso_empresa($id){

            $datos_traspaso=Traspasos::select('propietario_nuevo','propietario_anterior','fecha_a_partir_de')
            ->where('id_empresa',$id)
            ->latest()->first();

            $cant_resolucion=Traspasos::all()
            ->count();

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
            ->find($id);

            /** Obtener la fecha y días en español y formato tradicional*/
            $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $fechaF = Carbon::parse(Carbon::now());
            $mes = $mesesEspañol[($fechaF->format('n')) - 1];
            $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');
            
            $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
            $dia = $dias[(date('N', strtotime($fechaF))) - 1];
            /** FIN - Obtener la fecha y días en español y formato tradicional*/

            
            /** Obtener la fecha y días en español y formato tradicional para fecha A partir del dia del traspaso*/
            $fecha_ApartirDe= Carbon::parse($datos_traspaso->fecha_a_partir_de);
            
            $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $mes = $mesesEspañol[($fecha_ApartirDe->format('n')) - 1];
            $FechaDelDiaApartirDe = $fecha_ApartirDe->format('d') . ' de ' . $mes . ' de ' . $fecha_ApartirDe->format('Y');
            
            $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
            $diaApartirDe = $dias[(date('N', strtotime($fecha_ApartirDe))) - 1];
            /** FIN - Obtener la fecha y días en español y formato tradicional para fecha A partir del dia del traspaso*/

            $view = View::make('backend.admin.Empresas.Reportes.Traspaso', compact([

                        'FechaDelDia',
                        'empresa',
                        'dia',
                        'datos_traspaso',
                        'cant_resolucion',
                        'FechaDelDiaApartirDe',
                        'diaApartirDe'

            ]))->render();

            $pdf = App::make('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->loadHTML($view)->setPaper('carta', 'portrait');

            return $pdf->stream();

}
public function cierre_empresa($id){

    $datos_cierres=Cierres::select('fecha_a_partir_de','created_at')
    ->where('id_empresa',$id)
    ->latest()->first();

    $cant_resolucion=Cierres::all()
    ->count();

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
    ->find($id);

    /** Obtener la fecha y días en español y formato tradicional*/
    $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $fechaF = Carbon::parse(Carbon::now());
    $mes = $mesesEspañol[($fechaF->format('n')) - 1];
    $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');
    
    $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
    $dia = $dias[(date('N', strtotime($fechaF))) - 1];
    /** FIN - Obtener la fecha y días en español y formato tradicional*/

    
    /** Obtener la fecha y días en español y formato tradicional para fecha A partir del dia del traspaso*/
    $fecha_ApartirDe= Carbon::parse($datos_cierres->fecha_a_partir_de);
    
    $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $mes = $mesesEspañol[($fecha_ApartirDe->format('n')) - 1];
    $FechaDelDiaApartirDe = $fecha_ApartirDe->format('d') . ' de ' . $mes . ' de ' . $fecha_ApartirDe->format('Y');
    
    $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
    $diaApartirDe = $dias[(date('N', strtotime($fecha_ApartirDe))) - 1];
    /** FIN - Obtener la fecha y días en español y formato tradicional para fecha A partir del dia del traspaso*/

    $view = View::make('backend.admin.Empresas.Reportes.Cierres_empresas', compact([

                'FechaDelDia',
                'empresa',
                'dia',
                'datos_cierres',
                'cant_resolucion',
                'FechaDelDiaApartirDe',
                'diaApartirDe'

    ]))->render();

    $pdf = App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->loadHTML($view)->setPaper('carta', 'portrait');

    return $pdf->stream();

}
//** Fin de reportes controller */    
}