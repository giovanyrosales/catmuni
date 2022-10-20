<?php

namespace App\Http\Controllers\Backend\Reportes;


use App\Models\BusesDetalle;
use App\Models\Contribuyentes;
use App\Models\EstadoBuses;
use App\Models\EstadoRotulo;
use App\Models\RotulosDetalle;
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
use App\Models\MatriculasDetalleEspecifico;
use App\Models\alertas;
use App\Models\alertas_detalle;
use App\Models\CierresReaperturas;
use App\Models\ConstanciasHistorico;
use App\Models\GiroEmpresarial;
use App\Models\NotificacionesHistorico;
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

        $calificacion=calificacion::latest()
        ->where('id_empresa',$id)
        ->first();

        $f1_original=$f1;

        $empresa= Empresas
        ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
        ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
        ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
        ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral',
        'empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta',
        'empresa.telefono','empresa.excepciones_especificas',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',
        'actividad_economica.rubro','actividad_economica.id as id_act_economica',
        )
        ->find($id);

        if($calificacion->tipo_tarifa=='Fija'){

            $Tarifa_fija=TarifaFija::join('actividad_especifica','tarifa_fija.id_actividad_especifica','=','actividad_especifica.id')
            ->select('actividad_especifica.nom_actividad_especifica')
            ->where('codigo',$calificacion->codigo_tarifa)
            ->first();
            $act_especifica=$Tarifa_fija->nom_actividad_especifica;
            log::info($act_especifica);
        }else{
                 $act_especifica=$empresa->rubro;
                 log::info($act_especifica);
             }


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
            $InicioPeriodo=Carbon::parse($f1_original)->addMonthsNoOverflow(1)->day(1)->format('Y-m-d');
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
        Log::info('inicion Moratorio aqui');
        Log::info($FechaDeInicioMoratorio);


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
                if($empresa->excepciones_especificas==='SI')
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
                if($empresa->excepciones_especificas==='SI')
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
                $multasBalance=calificacion::select('multa_balance')
                ->where('id_empresa',$id)
                ->where('id_estado_multa','2')
                    ->get();

                    $Cantidad_multas=0;
                    $monto_pago_multaBalance=0;

                    foreach($multasBalance as $dato){
                        $multaBalance=$dato->multa_balance;

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
    'InicioPeriodo','Cantidad_multas','act_especifica'

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


        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit',
        'empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones',
        'empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel',
        'contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante',
        'contribuyente.fax', 'contribuyente.direccion as direccionCont',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',
        'actividad_economica.rubro','actividad_economica.id as id_act_economica',
        )
        ->find($id);

        //** Guardando en el historico de avisos */
        $dato = new NotificacionesHistorico();
        $dato->id_empresa = $id;
        $dato->id_alertas = '1';
        $created_at=new Carbon();
        $dato->created_at=$created_at->setTimezone('America/El_Salvador');
        $dato->save();

        if($dato->save())
        {
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
                }else if($alerta_aviso>=2){

                    $cantidad=0;

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
    }

    public function notificacion($f1,$f2,$ti,$f3,$id)
        {
            log::info([$f1,$f2,$ti,$f3,$id]);
            $f1_original=$f1;
            $empresa= Empresas
            ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
            ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
            ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
            ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


            ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
            'estado_empresa.estado',
            'giro_comercial.nombre_giro',
            'actividad_economica.rubro','actividad_economica.id as id_act_economica',
             )
            ->find($id);

            //** Guardando en el historico de avisos */
            $dato = new NotificacionesHistorico();
            $dato->id_empresa = $id;
            $dato->id_alertas = '2';
            $created_at=new Carbon();
            $dato->created_at=$created_at->setTimezone('America/El_Salvador');
            $dato->save();
            if($dato->save())
            {

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
                    $InicioPeriodo=Carbon::parse($f1_original)->addMonthsNoOverflow(1)->day(1)->format('Y-m-d');
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
                    $multasBalance=calificacion::select('multa_balance')
                    ->where('id_empresa',$id)
                    ->where('id_estado_multa','2')
                        ->get();

                        $Cantidad_multas=0;
                        $monto_pago_multaBalance=0;

                        foreach($multasBalance as $dato){
                            $multaBalance=$dato->multa_balance;

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

        }//Fin If Dato->save
    }

public function estado_cuenta_licor($f1,$f2,$id){

    $f1_original=$f1;
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
         $InicioPeriodo=Carbon::parse($f1_original)->format('Y-01-01');
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
        $f1_original=$f1;
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
             $InicioPeriodo=Carbon::parse($f1_original)->format('Y-m-d');
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

                ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
                'estado_empresa.estado',
                'giro_comercial.nombre_giro',
                'actividad_economica.rubro',
                )
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
        $f1_original=$f1;
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
             $InicioPeriodo=Carbon::parse($f1_original)->format('Y-m-d');
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


                ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
                'estado_empresa.estado',
                'giro_comercial.nombre_giro',
                'actividad_economica.rubro',
                 )
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
    $f1_original=$f1;
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
         $InicioPeriodo=Carbon::parse($f1_original)->format('Y-m-d');
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

            ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
            'estado_empresa.estado',
            'giro_comercial.nombre_giro',
            'actividad_economica.rubro',
             )
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
    $f1_original=$f1;
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
         $InicioPeriodo=Carbon::parse($f1_original)->format('Y-m-d');
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

            ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
            'estado_empresa.estado',
            'giro_comercial.nombre_giro',
            'actividad_economica.rubro',
             )
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

            $datos_traspaso=Traspasos::select('propietario_nuevo','propietario_anterior','fecha_a_partir_de','num_resolucion')
            ->where('id_empresa',$id)
            ->latest()->first();

            $cant_resolucion=$datos_traspaso->num_resolucion;

            $empresa= Empresas
            ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
            ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
            ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
            ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


            ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
            'estado_empresa.estado',
            'giro_comercial.nombre_giro',
            'actividad_economica.rubro',
                )
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

    $datos_cierres=CierresReaperturas::select('fecha_a_partir_de','tipo_operacion','created_at')
    ->where('id_empresa',$id)
    ->latest()->first();


    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro',
    )
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

    //** Detectar tipo de operación [Cierre][Reapertura] */
    if($datos_cierres->tipo_operacion==='Cierre'){
        $cant_resolucion=CierresReaperturas::where('num_resolucion')
        ->where('tipo_operacion','Cierre')
        ->latest()->first();

            $view = View::make('backend.admin.Empresas.Reportes.Cierres_empresas', compact([

                    'FechaDelDia',
                    'empresa',
                    'dia',
                    'datos_cierres',
                    'cant_resolucion',
                    'FechaDelDiaApartirDe',
                    'diaApartirDe'

            ]))->render();
    }else{
        $cant_resolucion=CierresReaperturas::where('num_resolucion')
        ->where('tipo_operacion','Reapertura')
        ->latest()->first();

            $view = View::make('backend.admin.Empresas.Reportes.Reaperturas_empresas', compact([

                    'FechaDelDia',
                    'empresa',
                    'dia',
                    'datos_cierres',
                    'cant_resolucion',
                    'FechaDelDiaApartirDe',
                    'diaApartirDe'

            ]))->render();

         }//** Fin de detectar tipo de operación */

    $pdf = App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->loadHTML($view)->setPaper('carta', 'portrait');

    return $pdf->stream();

}

public function traspaso_empresa_historico($id){

    $datos_traspaso=Traspasos::select('propietario_nuevo','propietario_anterior','fecha_a_partir_de','id_empresa','num_resolucion')
    ->where('id',$id)
    ->latest()->first();

    $cant_resolucion=$datos_traspaso->num_resolucion;

    $id_empresa=$datos_traspaso->id_empresa;


    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro',
     )
    ->find($id_empresa);

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

public function cierre_empresa_historico($id){

    $datos_cierres=CierresReaperturas::select('fecha_a_partir_de','tipo_operacion','created_at','id_empresa')
    ->where('id',$id)
    ->latest()->first();

    $id_empresa= $datos_cierres->id_empresa;

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro',
     )
    ->find($id_empresa);

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

    //** Detectar tipo de operación [Cierre][Reapertura] */
    if($datos_cierres->tipo_operacion==='Cierre'){

        $cant_resolucion=CierresReaperturas::where('tipo_operacion','Cierre')
        ->count();

        $view = View::make('backend.admin.Empresas.Reportes.Cierres_empresas', compact([

                'FechaDelDia',
                'empresa',
                'dia',
                'datos_cierres',
                'cant_resolucion',
                'FechaDelDiaApartirDe',
                'diaApartirDe'

        ]))->render();
    }else{

        $cant_resolucion=CierresReaperturas::where('tipo_operacion','Reapertura')
        ->count();

        $view = View::make('backend.admin.Empresas.Reportes.Reaperturas_empresas', compact([

                'FechaDelDia',
                'empresa',
                'dia',
                'datos_cierres',
                'cant_resolucion',
                'FechaDelDiaApartirDe',
                'diaApartirDe'

        ]))->render();

    }//** Fin de detectar tipo de operación */

    $pdf = App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->loadHTML($view)->setPaper('carta', 'portrait');

    return $pdf->stream();

}


public function reporte_calificacion($id){

    $matriculasRegistradas=MatriculasDetalle
    ::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
    ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')

    ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual',
            'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'matriculas.nombre as tipo_matricula')
    ->where('id_empresa', "=", "$id")
    ->first($id);

    if ($matriculasRegistradas == null)
            {
                $detectorNull=1;
            }else
            {
                $detectorNull=0;
            }

            $matriculas=MatriculasDetalle::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
            ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')

            ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual',
                    'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                    'matriculas.nombre as tipo_matricula')
            ->where('id_empresa', "=", "$id")
            ->get();

            $monto = 0;
            if($matriculas==null){
               $monto = 0;

           }
           else
           {
               foreach($matriculas as $dato){
                                             $monto = $monto + $dato->monto;
                                            }
           }

           $monto = number_format((float)$monto, 2, '.', ',');
           $montoMatriculaValor='$'. $monto;

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica',
    )
    ->find($id);

    $ultimaCalificacion=calificacion::latest()//buscamos la última calificación para empresas.
    ->where('id_empresa',$id)
    ->first();

    if($ultimaCalificacion==null){ //Si no se encuetra calificación de empresa

        $ultimaCalificacion=CalificacionMatriculas::latest() //buscamos la última calificación para matriculas.
        ->where('id_matriculas_detalle',$matriculasRegistradas->id)
        ->first();

        $giro_empresarial=GiroEmpresarial::where('id',$ultimaCalificacion->id_giro_empresarial)
        ->pluck('nombre_giro_empresarial')
        ->first();
    }else{

        $giro_empresarial=GiroEmpresarial::where('id',$ultimaCalificacion->id_giro_empresarial)
        ->pluck('nombre_giro_empresarial')
        ->first();
    }

    //log::info($ultimaCalificacion);

    $FechaDelDia = Carbon::now()->format('Y-m-d');

    $view = View::make('backend.admin.Empresas.Reportes.Calificaciones',
            compact([

                    'FechaDelDia',
                    'empresa',
                    'matriculasRegistradas',
                    'detectorNull',
                    'matriculas',
                    'monto',
                    'ultimaCalificacion',
                    'giro_empresarial'


                    ]))->render();

    $pdf = App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->loadHTML($view)->setPaper('carta', 'portrait');

    return $pdf->stream();

}

public function reporte_datos_empresa($id){

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica',
     )
    ->find($id);

    $ultimaCalificacion=calificacion::latest()
    ->where('id_empresa',$id)
    ->first();

    $FechaDelDia = Carbon::now()->format('Y-m-d');

    $view = View::make('backend.admin.Empresas.Reportes.Datos_empresas',
            compact([

                    'FechaDelDia',
                    'empresa',
                    'ultimaCalificacion',

                    ]))->render();

    $pdf = App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->loadHTML($view)->setPaper('carta', 'portrait');

    return $pdf->stream();



}

public function resolucion_apertura($id){


    $consul_matricula=MatriculasDetalle::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
    ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')

    ->select('matriculas_detalle.id as id_detallematricula', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual',
            'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'matriculas.nombre as tipo_matricula')
    ->where('id_empresa', $id)
    ->first();

    if($consul_matricula==null){
            $hay_matricula=0;
    }else{
            $hay_matricula=1;
         }

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono','empresa.num_resolucion',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro',
                )
    ->find($id);

    if($hay_matricula==1)
    {
       $califiquese="(".$consul_matricula->cantidad.")"." ".$consul_matricula->tipo_matricula;
    }else{
        $califiquese=$empresa->nombre;
         }

    /** Obtener la fecha y días en español de inicio de operaciones*/
    $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $fechaF = Carbon::parse($empresa->inicio_operaciones);
    $mes = $mesesEspañol[($fechaF->format('n')) - 1];
    $inicio_operaciones = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

    $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
    $dia_inicio_op = $dias[(date('N', strtotime($fechaF))) - 1];
    /** FIN - Obtener la fecha y días en español de inicio de operaciones*/


    //Configuracion de Reporte en MPDF
    $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
    $mpdf->SetTitle('Alcaldía Metapán | Resolución de Apertura');

    // mostrar errores
    $mpdf->showImageErrors = false;

    $logoalcaldia = 'images/logo.png';
    $logoelsalvador = 'images/EscudoSV.png';
    $LeyT = 'images/LeyT.png';

    if($hay_matricula==1)
    {
        $calificacion_mat=CalificacionMatriculas::where('id_matriculas_detalle',$consul_matricula->id_detallematricula)
        ->first();

        /** Obtener la fecha y días en español y formato tradicional*/
        $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaF = Carbon::parse($calificacion_mat->created_at);
        $mes = $mesesEspañol[($fechaF->format('n')) - 1];
        $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

        $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $dia = $dias[(date('N', strtotime($fechaF))) - 1];
        /** FIN - Obtener la fecha y días en español y formato tradicional*/

        $total_anual_impuesto_matricula=number_format((float)($calificacion_mat->total_impuesto_mat*12), 2, '.', ',');

        $tabla = "<div class='content'>
                        <img id='logo' src='$logoalcaldia'>
                        <img id='EscudoSV' src='$logoelsalvador'>
                        <h4>ALCALDIA MUNICIPAL DE METAPÁN, SANTA ANA, EL SALVADOR C.A<br>
                            UNIDAD DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL<br>
                            RESOLUCIÓN
                        </h4>
                        <hr>
                </div>";

        $tabla .= "<table border='0' align='center' style='width: 650px;font-size:12px;'>
            <tr>
                <td  align='left'> </td>

                <td align='right'>
                    RESOLUCIÓN N°:&nbsp;<strong>$empresa->num_resolucion</strong><br><br>
                </td>
            </tr>
            <tr>
                <td id='uno'>FECHA DE RESOLUCIÓN:</td>
                <td id='dos'>$dia,&nbsp;$FechaDelDia</td>
            </tr>
            <tr>
                <td id='uno'>NÚMERO DE CUENTA CORRIENTE:</td>
                <td id='dos'>$empresa->num_tarjeta</td>
            </tr>
            <tr>
                <td id='uno'> CALIFIQUESE: </td>
                <td id='dos'>$califiquese</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
                <td id='uno'>DIRECCIÓN:</td>
                <td id='dos'>$empresa->direccion</td>
            </tr>
            <tr>
                <td id='uno'>PROPIEDAD DE:</td>
                <td id='dos'>$empresa->contribuyente&nbsp;$empresa->apellido</td>
            </tr>
            <tr>
                <td id='uno'>REPRESENTADO POR:</td>
                <td id='dos'></td>
            </tr>
            <tr>
                <td id='uno'>GIRO ECONÓMICO:</td>
                <td id='dos'>$calificacion_mat->giro_empresarial</td>
            </tr>
            <tr>
                <td id='uno'>FECHA DE INICIO DE OPERACIONES:</td>
                <td id='dos'>$dia_inicio_op&nbsp;$inicio_operaciones</td>
            </tr>
            <tr>
                <td colspan='2'  style='text-align: justify'>
                    <hr>

                            <table border='0' align='center' style='width: 650px;'>
                                <tr>
                                    <th scope='col' align='left'>DESCRIPCIÓN</th>
                                    <th scope='col'>&nbsp;</th>
                                    <th scope='col' align='left'>CARGO</th>
                                </tr>

                                <tr>
                                    <td> $calificacion_mat->cantidad&nbsp; $calificacion_mat->nombre_matricula</td>
                                    <td>&nbsp;</td>
                                    <td align='right'>$ $calificacion_mat->pago_mensual</td>
                                </tr>

                                <tr>
                                    <td>Fondo Fiestas Patronales 5%</td>
                                    <td>&nbsp;</td>
                                    <td align='right'>$$calificacion_mat->fondofp_impuesto_mat</td>
                                </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td align='right'><strong><hr>TOTAL MENSUAL</strong></td>
                                    <td align='right'><hr><b>$ $calificacion_mat->total_impuesto_mat</b></td>
                                </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td align='right'><hr>TOTAL ANUAL</td>
                                    <td align='right'><hr>$$total_anual_impuesto_matricula</td>
                                </tr>

                                <tr>
                                    <td>MATRICULA</td>
                                    <td>&nbsp;</td>
                                    <td align='right'>$$calificacion_mat->monto_matricula</td>
                                </tr>

                                <tr>
                                    <td>Fondo Fiestas Patronales 5%</td>
                                    <td> </td>
                                    <td align='right'>$$calificacion_mat->fondofp</td>
                                </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td align='right' style='font-size:7px';><b>MATRICULA ANUAL</b></td>
                                    <td align='right'>$$calificacion_mat->pago_anual</td>
                                </tr>

                            </table>


                    <p style='font-size:10px;'>
                        <br>
                        <br>
                        LICDA. ROSA LISSETH ALDANA MERLOS<br>
                        JEFE DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL

                    </p>
                    <hr>
                    <p style='font-size:6;text-align: justify'>
                        <b>Ley General Tributaria Municipal:</b><br>
                        <b>Art. 123.</b> -De la calificación de contribuyentes, de la determinación de tributos, de la resolución del Alcalde en el procedimiento de repetición del pago de lo no
                            debido, y de la aplicación de sanciones hecha por la administración tributaria municipal, se admitirá recurso de apelación para ante el Concejo Municipal
                            respectivo, el cual deberá interponerse ante el funcionario que haya hecho la calificación o pronunciada la resolución correspondiente, en el plazo de tres días después de su notificación.
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
                <td colspan='2'>
                <br>
                        <img id='LeyT' src='$LeyT'>
                </td>
            </tr>
        </table>";
    }
    else
        {
            $calificacion_emp=calificacion::where('id_empresa',$id)
            ->first();

            /** Obtener la fecha y días en español y formato tradicional*/
            $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $fechaF = Carbon::parse($calificacion_emp->created_at);
            $mes = $mesesEspañol[($fechaF->format('n')) - 1];
            $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

            $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
            $dia = $dias[(date('N', strtotime($fechaF))) - 1];
            /** FIN - Obtener la fecha y días en español y formato tradicional*/

            $tabla = "<div class='content'>
                            <img id='logo' src='$logoalcaldia'>
                            <img id='EscudoSV' src='$logoelsalvador'>
                            <h4>ALCALDIA MUNICIPAL DE METAPÁN, SANTA ANA, EL SALVADOR C.A<br>
                                UNIDAD DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL<br>
                                RESOLUCIÓN
                            </h4>
                            <hr>
                    </div>";

            $tabla .= "<table border='0' align='center' style='width: 650px;font-size:11px;'>
                <tr>
                    <td  align='left'> </td>

                    <td align='right'>
                        RESOLUCIÓN N°:&nbsp;<strong>$empresa->num_resolucion</strong><br><br>
                    </td>
                </tr>
                <tr>
                    <td id='uno'>FECHA DE RESOLUCIÓN:</td>
                    <td id='dos'>$dia,&nbsp;$FechaDelDia</td>
                </tr>
                <tr>
                    <td id='uno'>NÚMERO DE CUENTA CORRIENTE:</td>
                    <td id='dos'>$empresa->num_tarjeta</td>
                </tr>
                <tr>
                    <td id='uno'> CALIFIQUESE: </td>
                    <td id='dos'>$califiquese</td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                    <td id='uno'>DIRECCIÓN:</td>
                    <td id='dos'>$empresa->direccion</td>
                </tr>
                <tr>
                    <td id='uno'>PROPIEDAD DE:</td>
                    <td id='dos'>$empresa->contribuyente&nbsp;$empresa->apellido</td>
                </tr>
                <tr>
                    <td id='uno'>REPRESENTADO POR:</td>
                    <td id='dos'></td>
                </tr>
                <tr>
                    <td id='uno'>GIRO ECONÓMICO:</td>
                    <td id='dos'>$calificacion_emp->giro_empresarial</td>
                </tr>
                <tr>
                    <td id='uno'>FECHA DE INICIO DE OPERACIONES:</td>
                    <td id='dos'>$dia_inicio_op&nbsp;$inicio_operaciones</td>
                </tr>
                <tr>
                    <td colspan='2'  style='text-align: justify'>
                        <hr>

                                <table border='0' align='center' style='width: 650px;'>
                                    <tr>
                                        <th scope='col' align='left'>DESCRIPCIÓN</th>
                                        <th scope='col'>&nbsp;</th>
                                        <th scope='col' align='left'>CARGO</th>
                                    </tr>

                                    <tr>
                                        <td>$califiquese </td>
                                        <td>&nbsp;</td>
                                        <td align='right'>$$calificacion_emp->tarifa </td>
                                    </tr>

                                    <tr>
                                        <td>Fondo Fiestas Patronales 5%</td>
                                        <td>&nbsp;</td>
                                        <td align='right'>$ $calificacion_emp->fondofp_mensual</td>
                                    </tr>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align='right'><strong><hr>TOTAL MENSUAL</strong></td>
                                        <td align='right'><hr><b>$ $calificacion_emp->total_impuesto </b></td>
                                    </tr>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align='right'><hr>TOTAL ANUAL</td>
                                        <td align='right'><hr>$ $calificacion_emp->total_impuesto_anual</td>
                                    </tr>

                                </table>


                        <p style='font-size:10px;'>
                            <br>
                            <br>
                            LICDA. ROSA LISSETH ALDANA MERLOS<br>
                            JEFE DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL

                        </p>
                        <hr>
                        <p style='font-size:6;text-align: justify'>
                            <b>Ley General Tributaria Municipal:</b><br>
                            <b>Art. 123.</b> -De la calificación de contribuyentes, de la determinación de tributos, de la resolución del Alcalde en el procedimiento de repetición del pago de lo no
                                debido, y de la aplicación de sanciones hecha por la administración tributaria municipal, se admitirá recurso de apelación para ante el Concejo Municipal
                                respectivo, el cual deberá interponerse ante el funcionario que haya hecho la calificación o pronunciada la resolución correspondiente, en el plazo de tres días después de su notificación.
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
                    <td colspan='2'>
                    <br>
                            <img id='LeyT' src='$LeyT'>
                    </td>
                </tr>
            </table>";

        }

    $stylesheet = file_get_contents('css/cssconsolidado.css');
    $mpdf->WriteHTML($stylesheet,1);
    $mpdf->SetMargins(0, 0, 10);


    //$mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');

    $mpdf->WriteHTML($tabla,2);
    $mpdf->Output();



}

public function generar_solvencia($id){


    //Configuracion de Reporte en MPDF
    $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
    $mpdf->SetTitle('Alcaldía Metapán | Solvencia');

    // mostrar errores
    $mpdf->showImageErrors = false;

    $logoalcaldia = 'images/logo.png';
    $logoelsalvador = 'images/EscudoSV.png';
    $LeyT = 'images/LeyT.png';

    $fechahoy=carbon::now()->format('d-m-Y');

    /** Obtener la fecha y días en español y formato tradicional*/
    $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $fechaF = Carbon::parse($fechahoy);
    $mes = $mesesEspañol[($fechaF->format('n')) - 1];
    $FechaDelDia = $fechaF->format('d') . ' días del mes de ' . $mes . ' de ' . $fechaF->format('Y');

    $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
    $dia = $dias[(date('N', strtotime($fechaF))) - 1];
    /** FIN - Obtener la fecha y días en español y formato tradicional*/

    $año=carbon::now()->format('y');

    $contribuyente=Contribuyentes::where('id',$id)
    ->first();

    $num_resolucion=ConstanciasHistorico::latest()
    ->where('id_contribuyente',$id)
    ->where('tipo_constancia','Global')
    ->pluck('num_resolucion')
    ->first();
    log::info('consulta num resolucion: '.$num_resolucion);
    if($num_resolucion==null){
        $num_resolucion=0;
    }
    $num_resolucion_nueva=$num_resolucion+1;
    log::info('consulta constancias historico: '.$num_resolucion);
    log::info('consulta constancias historico: '.$num_resolucion_nueva);

    //** Guardando en el historico la resolución */
        $dato = new ConstanciasHistorico();
        $dato->id_contribuyente = $id;
        $dato->tipo_constancia = 'Global';
        $dato->num_resolucion =$num_resolucion_nueva;
        $dato->save();
    if($dato->save())
    {

            //** Terminando de guardar en el historico la resolución */

            $tabla = "<table border='0' align='center' style='width: 650px;font-size:12px;'>
                <tr>
                    <td  align='left'> </td>
                    <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                    <td align='right'>
                        <h4 style='border:1px solid black;border-radius:50px;'><strong>&nbsp;&nbsp; CS-$dato->num_resolucion-$año &nbsp;&nbsp;</strong></h4><br><br>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'  style='font-size:14;text-align: justify;line-height:40px;'>
                        <b>CONSTANCIA NO DEFINIDA AUN</b>
                            <br>
                            <br>
                        <p >

                        Por medio de la presente la Alcaldia Municipal de Metapán a través de la Unidad de Administración
                        Tributaria Municipal HACE CONSTAR QUE:&nbsp;<b>$contribuyente->nombre&nbsp;$contribuyente->apellido</b>,
                        con Documento Único de identidad: <b>$contribuyente->dui </b> no posee inmuebles o negocios
                        inscritos en nuestros registros de cuentas corrientes, por lo cual se encuentra solvente
                        en el pago de tasas e impuestos Municipales.
                        <br>
                        <br>
                        <br>
                        <br>
                        Se extiende la presente para usos del interesado, a los $FechaDelDia
                        </p>
                    </td>
                </tr>
                <tr>
                <td colspan='2' align='center' style='text-align: justify'>
                        <p style='font-size:14;'>
                            <br><br><br><br><br><br><br><br><br><br><br><br><br>

                            LICDA. Rosa Lisseth Aldana Merlos<br>
                            Jefatura Unidad de Administración Tributaria Municipal
                        </p>
                    </td>
                </tr>
            </table>";

        $stylesheet = file_get_contents('css/cssconsolidado.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->SetMargins(0, 0, 10);


        //$mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');

        $mpdf->WriteHTML($tabla,2);
        $mpdf->Output();

    }//Fin de if si se guardo...


}

public function generar_constancia_simple($id){



    //Configuracion de Reporte en MPDF
    $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
    $mpdf->SetTitle('Alcaldía Metapán | Constancia simple');

    // mostrar errores
    $mpdf->showImageErrors = false;

    $logoalcaldia = 'images/logo.png';
    $logoelsalvador = 'images/EscudoSV.png';

    $fechahoy=carbon::now()->format('d-m-Y');

    /** Obtener la fecha y días en español y formato tradicional*/
    $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $fechaF = Carbon::parse($fechahoy);
    $mes = $mesesEspañol[($fechaF->format('n')) - 1];
    $FechaDelDia = $fechaF->format('d') . ' días del mes de ' . $mes . ' de ' . $fechaF->format('Y');

    $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
    $dia = $dias[(date('N', strtotime($fechaF))) - 1];
    /** FIN - Obtener la fecha y días en español y formato tradicional*/
    $año=carbon::now()->format('y');

    $contribuyente=Contribuyentes::where('id',$id)
    ->first();

    $num_resolucion=ConstanciasHistorico::latest()
    ->where('id_contribuyente',$id)
    ->pluck('num_resolucion')
    ->first();

    if($num_resolucion==null){
        $num_resolucion=0;
    }
    $num_resolucion_nueva=$num_resolucion+1;
    log::info('consulta constancias historico: '.$num_resolucion);
    log::info('consulta constancias historico: '.$num_resolucion_nueva);

    //** Guardando en el historico la resolución */
        $dato = new ConstanciasHistorico();
        $dato->id_contribuyente = $id;
        $dato->tipo_constancia = 'Simple';
        $dato->num_resolucion =$num_resolucion_nueva;
        $dato->save();

        if($dato->save())
        {
            //** Terminando de guardar en el historico la resolución */

            $tabla = "<table border='0' align='center' style='width: 650px;font-size:12px;'>
                <tr>
                    <td  align='left'> </td>
                    <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                    <td align='right'>
                        <h4 style='border:1px solid black;border-radius:50px;'><strong>&nbsp;&nbsp; CS-$dato->num_resolucion-$año &nbsp;&nbsp;</strong></h4><br><br>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'  style='font-size:14;text-align: justify;line-height:40px;'>
                        <b> A QUIEN CORRESPONDA</b>
                            <br>
                            <br>
                        <p >
                        Por medio de la presente la Alcaldia Municipal de Metapán a través de la Unidad de Administración
                        Tributaria Municipal HACE CONSTAR QUE:&nbsp;<b>$contribuyente->nombre&nbsp;$contribuyente->apellido</b>,
                        con Documento Único de identidad: <b>$contribuyente->dui </b> no posee inmuebles o negocios
                        inscritos en nuestros registros de cuentas corrientes, por lo cual se encuentra solvente
                        en el pago de tasas e impuestos Municipales.
                        <br>
                        <br>
                        <br>
                        <br>
                        Se extiende la presente para usos del interesado, a los $FechaDelDia
                        </p>
                    </td>
                </tr>
                <tr>
                <td colspan='2' align='center' style='text-align: justify'>
                        <p style='font-size:14;'>
                            <br><br><br><br><br><br><br><br><br><br><br><br><br>

                            LICDA. Rosa Lisseth Aldana Merlos<br>
                            Jefatura Unidad de Administración Tributaria Municipal
                        </p>
                    </td>
                </tr>
            </table>";

        $stylesheet = file_get_contents('css/cssconsolidado.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->SetMargins(0, 0, 10);


        //$mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');

        $mpdf->WriteHTML($tabla,2);
        $mpdf->Output();

    }//Fin de if si se guardo...

}

public function generar_solvencia_empresa($id){


    //Configuracion de Reporte en MPDF
    $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
    $mpdf->SetTitle('Alcaldía Metapán | Solvencia');

    // mostrar errores
    $mpdf->showImageErrors = false;

    $logoalcaldia = 'images/logo.png';
    $logoelsalvador = 'images/EscudoSV.png';
    $LeyT = 'images/LeyT.png';

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

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono','empresa.num_resolucion',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'contribuyente.id as id_contribuyente',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro',
                )
    ->find($id);

    $contribuyente=Contribuyentes::where('id',$empresa->id_contribuyente)
    ->first();
    log::info('Contribuyente: '.$contribuyente);

    $num_resolucion=ConstanciasHistorico::latest()
    ->where('id_contribuyente',$empresa->id_contribuyente)
    ->where('tipo_constancia','Solvencia_empresa')
    ->pluck('num_resolucion')
    ->first();

    log::info('consulta num resolucion: '.$num_resolucion);
    if($num_resolucion==null){
        $num_resolucion=0;
    }
    $num_resolucion_nueva=$num_resolucion+1;
    log::info('consulta constancias historico: '.$num_resolucion);
    log::info('consulta constancias historico: '.$num_resolucion_nueva);



    //** Guardando en el historico la resolución */
        $dato = new ConstanciasHistorico();
        $dato->id_contribuyente = $empresa->id_contribuyente;
        $dato->tipo_constancia = 'Solvencia_empresa';
        $dato->num_resolucion =$num_resolucion_nueva;
        $dato->save();
    if($dato->save())
    {

            //** Terminando de guardar en el historico la resolución */

            $tabla = "<table border='0' align='center' style='width: 650px;font-size:12px;'>
                <tr>
                    <td  align='left'> </td>
                    <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                    <td align='right'>
                        <h4 style='border:1px solid black;border-radius:50px;'><strong>&nbsp;&nbsp; N° $dato->num_resolucion-S-$año &nbsp;&nbsp;</strong></h4><br><br>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'  style='font-size:14;text-align: justify;line-height:40px;'>
                        <br>
                        <br>
                        <p style='text-transform: uppercase;'>
                        $empresa->contribuyente&nbsp;$empresa->apellido, con obligación tributaria correspondiente a,
                        $empresa->nombre, y con dirección, $empresa->direccion, el cual se encuentra inscrito/a en nuestros
                        registros de cuenta corriente bajo el/los codigos/s $empresa->num_tarjeta,
                        <br>
                        <br>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='font-size:14;text-align: center;'>
                        <br>
                        <br>
                        <br>
                        <br>
                        <b>ESTA SOLVENTE DE PAGO DE IMPUESTOS CON ESTA MUNICIPALIDAD<b>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='font-size:12;text-align: right;'>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <p>
                            $FechaDelDia
                        </p>
                    </td>
                </tr>
            </table>";

        $stylesheet = file_get_contents('css/cssconsolidado.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->SetMargins(0, 0, 10);


        //$mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');

        $mpdf->WriteHTML($tabla,2);
        $mpdf->Output();

    }//Fin de if si se guardo...


}


//********************* Notificaciones de matriculas ***********************/


public function notificacion_maquinas($f1,$f2,$ti,$f3,$id){

    log::info('f1: '.$f1);
    log::info('f2: '.$f2);
    log::info('f3: '.$f3);

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

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono','empresa.num_resolucion',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'contribuyente.id as id_contribuyente',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro',
    )
    ->find($id);

    $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$id)
    ->pluck('id')
    ->first();

    $f1_original=$f1;
    $fechaPagaraMaquinas=$f2;
    $id_matriculadetalleMaquinas=$id_matriculadetalle;
    $tasa_interes=$ti;
    $Message=0;

    $MesNumero=Carbon::createFromDate($f1)->format('d');
    //log::info($MesNumero);

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

            //Le agregamos su signo de dollar para la vista al usuario
            $fondoFP= "$". number_format($fondoFPValor, 2, '.', ',');
            $totalPagoMatriculasDollar="$".number_format($totalPagoValor, 2, '.', ',');
            $impuestos_mora_Dollar="$".number_format($impuestos_mora, 2, '.', ',');
            $impuesto_año_actual_Dollar="$".number_format($impuesto_año_actual, 2, '.', ',');
            $InteresTotalDollar="$".number_format($InteresTotal, 2, '.', ',');
            $monto_pago_PmatriculaDollar="$".number_format($monto_pago_matricula, 2, '.', ',');
            $multaDolarMaquinas="$".number_format($multa, 2, '.', ',');


    //** Guardando en el historico de avisos */
    $dato = new NotificacionesHistorico();
    $dato->id_empresa = $id;
    $dato->id_alertas = '2'; 
    $created_at=new Carbon();
    $dato->created_at=$created_at->setTimezone('America/El_Salvador');
    $dato->save();
    if($dato->save())
    { 
        
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
            <p>Señor (a):&nbsp;$empresa->contribuyente&nbsp;$empresa->apellido<br>
                Dirección:&nbsp;$empresa->direccionCont<br>
                Cuenta Corriente N°:&nbsp;$empresa->num_tarjeta<br>
                Empresa o Negocio:&nbsp;$empresa->nombre
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
            <td align='right'>MATRÍCULA</td>
            <td align='center'>$monto_pago_PmatriculaDollar</td>
        </tr>
        <tr>
            <td align='right'>FONDO F. PATRONALES 5%</td>
            <td align='center'>$fondoFP</td>
        </tr>
        <tr>
            <td align='right'>MUL. MATRICULA</td>
            <td align='center'>$multaDolarMaquinas</td>
        </tr>
        <tr>
            <th scope='row'>TOTAL ADEUDADO</th>
            <th align='center'>$totalPagoMatriculasDollar</th>
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
    }//Fin If Dato->save

      //Registrando las alertas de notificaciones 
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
                              ]);
                  }
      //Fin - Registrando las alertas de notificaciones 
      
}

public function notificacion_mesas($f1,$f2,$ti,$f3,$id){
    log::info('f1: '.$f1);
    log::info('f2: '.$f2);
    log::info('f3: '.$f3);

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

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
           
            
    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono','empresa.num_resolucion',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'contribuyente.id as id_contribuyente',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro',
    )
    ->find($id);

    $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$id)
    ->pluck('id')
    ->first();

    $f1_original=$f1;
    $fechaPagaraMaquinas=$f2;
    $id_matriculadetalleMesas=$id_matriculadetalle;
    $tasa_interes=$ti;
    $Message=0;

    $MesNumero=Carbon::createFromDate($f1)->format('d');
    //log::info($MesNumero);

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
         $InicioPeriodo=Carbon::parse($f1_original)->format('Y-m-d');
        // log::info('fin de mes ');
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
           
            ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
            'estado_empresa.estado',
            'giro_comercial.nombre_giro',
            'actividad_economica.rubro',
             )
            ->find($id); 

            //** Agregando formato de número */
            $fondoFPValor="$".number_format($fondoFPValor, 2, '.', ',');
            $impuestos_mora="$".number_format($impuestos_mora, 2, '.', ',');
            $impuesto_año_actual="$".number_format($impuesto_año_actual, 2, '.', ',');
            $totalMultaPagoExtemporaneo="$".number_format($totalMultaPagoExtemporaneo, 2, '.', ',');
            $InteresTotal="$".number_format($InteresTotal, 2, '.', ',');
            $multa="$".number_format($multa, 2, '.', ',');
            $monto_pago_matricula="$".number_format($monto_pago_matricula, 2, '.', ',');
            $totalPagoValor="$".number_format($totalPagoValor, 2, '.', ',');

   
    //** Guardando en el historico de avisos */
    $dato = new NotificacionesHistorico();
    $dato->id_empresa = $id;
    $dato->id_alertas = '2'; 
    $created_at=new Carbon();
    $dato->created_at=$created_at->setTimezone('America/El_Salvador');
    $dato->save();
    if($dato->save())
    { 

    
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
            <p>Señor (a):&nbsp;$empresa->contribuyente&nbsp;$empresa->apellido<br>
                Dirección:&nbsp;$empresa->direccionCont<br>
                Cuenta Corriente N°:&nbsp;$empresa->num_tarjeta<br>
                Empresa o Negocio:&nbsp;$empresa->nombre
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
                <td align='right'>IMPUESTO MORA</td>
                <td align='center'>$impuestos_mora</td>
            </tr>
            <tr>
                <td align='right'>IMPUESTOS</td>
                <td align='center'>$impuesto_año_actual</td>
            </tr>
            <tr>
                <td align='right'>INTERESES MORATORIOS</td>
                <td align='center'>$InteresTotal</td>
            </tr>
            <tr>
                <td align='right'>MULTAS</td>
                <td align='center'>$totalMultaPagoExtemporaneo</td>
            </tr>
            <tr>
                <td align='right'>MATRÍCULA</td>
                <td align='center'>$monto_pago_matricula</td>
            </tr>
            <tr>
                <td align='right'>FONDO F. PATRONALES 5%</td>
                <td align='center'>$fondoFPValor</td>
            </tr>
            <tr>
            <td align='right'>MUL. MATRICULA</td>
            <td align='center'>$multa</td>
            </tr>
            <tr>
                <th scope='row'>TOTAL ADEUDADO</th>
                <th align='center'>$totalPagoValor</th>
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
    }//Fin If Dato->save

      //Registrando las alertas de notificaciones 
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
                              ]);
                  }
      //Fin - Registrando las alertas de notificaciones 

}

public function notificacion_aparatos($f1,$f2,$id){
    log::info('f1: '.$f1);
    log::info('f2: '.$f2);

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

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
           
            
    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono','empresa.num_resolucion',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'contribuyente.id as id_contribuyente',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro',
    )
    ->find($id);

    $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$id)
    ->pluck('id')
    ->first();

    $f1_original=$f1;
    $id_matriculadetalleAparatos=$id_matriculadetalle;


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
             $InicioPeriodo=Carbon::parse($f1_original)->format('Y-m-d');
            // log::info('fin de mes ');
             }
    
        
        $f2=Carbon::parse($f2);
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
        $PInicio=Carbon::createFromDate($AñoInicio, $monthInicio, $dayInicio)->format('Y-m-d');
        $PFinal=Carbon::createFromDate($AñoFinal, $monthFinal, $dayFinal)->format('Y-m-d');
        log::info('fecha iniciooooo: '.$PInicio);
        log::info('fecha finaaaaal: '.$PFinal);
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

                
        //** Finaliza calculo de cobro licencia licor **/

    //** Guardando en el historico de avisos */
    $dato = new NotificacionesHistorico();
    $dato->id_empresa = $id;
    $dato->id_alertas = '2'; 
    $created_at=new Carbon();
    $dato->created_at=$created_at->setTimezone('America/El_Salvador');
    $dato->save();
    if($dato->save())
    { 
    
    
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
            <p>Señor (a):&nbsp;$empresa->contribuyente&nbsp;$empresa->apellido<br>
                Dirección:&nbsp;$empresa->direccionCont<br>
                Cuenta Corriente N°:&nbsp;$empresa->num_tarjeta<br>
                Empresa o Negocio:&nbsp;$empresa->nombre
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
                <th scope='col'>Periodo: &nbsp;&nbsp;desde&nbsp; $PInicio&nbsp;</th>
                <th scope='col'>&nbsp;&nbsp;hasta&nbsp;  $PFinal&nbsp;</th>    
            </tr>
            <tr>
                <td align='right'>LICENCIAS</td>
                <td align='center'>$$monto_pago_matricula</td>
            </tr>
            <tr>
                <td align='right'>FONDO F. PATRONALES 5%</td>
                <td align='center'>$$fondoFPValor</td>
            </tr>
            <tr>
            <td align='right'>MULTAS POR LICENCIA</td>
            <td align='center'>$$multa</td>
            </tr>
            <tr>
                <th scope='row'>TOTAL ADEUDADO</th>
                <th align='center'>$$totalPagoValor</th>
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
    }//Fin If Dato->save

      //Registrando las alertas de notificaciones 
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
                              ]);
                  }
      //Fin - Registrando las alertas de notificaciones 

}

public function notificacion_sinfonolas($f1,$f2,$ti,$f3,$id){

    $fechahoy=carbon::now()->format('d-m-Y');

    /** Obtener la fecha y días en español y formato tradicional*/
    $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $fechaF = Carbon::parse($fechahoy);
    $mes = $mesesEspañol[($fechaF->format('n')) - 1];
    $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

    $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
    $dia = $dias[(date('N', strtotime($fechaF))) - 1];
    /** FIN - Obtener la fecha y días en español y formato tradicional*/


    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
           
            
    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono','empresa.num_resolucion',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'contribuyente.id as id_contribuyente',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro',
    )
    ->find($id);

    $f1_original=$f1;
    $fechaPagaraSinfonolas=$f2;
    $tasa_interes=$ti;
    $fecha_interesMoratorio=Carbon::now()->format('Y-m-d');

    $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$id)
    ->pluck('id')
    ->first();

    $MesNumero=Carbon::createFromDate($f1)->format('d');

    $id_matriculadetalleSinfonolas=$id_matriculadetalle;
   
    
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
             $InicioPeriodo=Carbon::parse($f1_original)->format('Y-m-d');
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
                
                
                ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
                'estado_empresa.estado',
                'giro_comercial.nombre_giro',
                'actividad_economica.rubro',
                 )
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

 
    //** Guardando en el historico de avisos */
    $dato = new NotificacionesHistorico();
    $dato->id_empresa = $id;
    $dato->id_alertas = '2'; 
    $created_at=new Carbon();
    $dato->created_at=$created_at->setTimezone('America/El_Salvador');
    $dato->save();
    if($dato->save())
    { 
    
    
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
            <p>Señor (a):&nbsp;$empresa->contribuyente&nbsp;$empresa->apellido<br>
                Dirección:&nbsp;$empresa->direccionCont<br>
                Cuenta Corriente N°:&nbsp;$empresa->num_tarjeta<br>
                Empresa o Negocio:&nbsp;$empresa->nombre
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
                <td align='right'>IMPUESTO MORA</td>
                <td align='center'>$$impuestos_mora</td>
            </tr>
            <tr>
                <td align='right'>IMPUESTOS</td>
                <td align='center'>$$impuesto_año_actual</td>
            </tr>
            <tr>
                <td align='right'>INTERESES MORATORIOS</td>
                <td align='center'>$$InteresTotal</td>
            </tr>
            <tr>
                <td align='right'>MULTAS</td>
                <td align='center'>$$totalMultaPagoExtemporaneo</td>
            </tr>
            <tr>
                <td align='right'>MATRÍCULA</td>
                <td align='center'>$$monto_pago_matricula</td>
            </tr>
            <tr>
                <td align='right'>FONDO F. PATRONALES 5%</td>
                <td align='center'>$$fondoFPValor</td>
            </tr>
            <tr>
            <td align='right'>MUL. MATRICULA</td>
            <td align='center'>$$multa</td>
            </tr>
            <tr>
                <th scope='row'>TOTAL ADEUDADO</th>
                <th align='center'>$$totalPagoValor</th>
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

    }//Fin If Dato->save

    //Registrando las alertas de notificaciones 
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
                            ]);
                }
    //Fin - Registrando las alertas de notificaciones 

}

 public function pdfReporteActividadEconomica($id){
        // viene ID de giro_empresarial

        $infoGiro = GiroComercial::where('id', $id)->first();

        $infoEmpresa = Empresas::where('id_giro_comercial', $id)
            ->orderBy('nombre', 'ASC')
            ->get();

        foreach ($infoEmpresa as $dd){

            $nombreEstado = '';
            $inicioOpe = '';

            if($dd->inicio_operaciones != null){
                $inicioOpe = date("d-m-Y", strtotime($dd->inicio_operaciones));
            }

            $dd->iniciooperaciones = $inicioOpe;

            if($infoEstado = EstadoEmpresas::where('id', $dd->id_estado_empresa)->first()){
                $nombreEstado = $infoEstado->estado;
            }

            $dd->nombreestado = $nombreEstado;

            $nombreContribuyente = '';
            if($infoContri = Contribuyentes::where('id', $dd->id_contribuyente)->first()){
                $nombreContribuyente = $infoContri->nombre;
            }

            $dd->nombrecontribuyente = $nombreContribuyente;
        }


        //Configuracion de Reporte en MPDF
        //$mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf->SetTitle('Alcaldía Metapán | Actividad Económica');

        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logo.png';
        $logoelsalvador = 'images/EscudoSV.png';

        $tabla = "<div class='content'>
                    <img id='logo' src='$logoalcaldia'>
                    <img id='EscudoSV' src='$logoelsalvador'>
                    <h4>ALCALDIA MUNICIPAL DE METAPAN<br>
                    UNIDAD DE ADMINISTRACION TRIBUTARIA MUNICIPAL<br>
                    DEPARTAMENTO DE SANTA ANA, EL SALVADOR C.A</h4>
                    <hr>
            </div>";

         $tabla .= "<p>Giro Comercial: <strong>$infoGiro->nombre_giro</strong></p>";
         $tabla .= "<p>Matrícula: <strong>$infoGiro->matricula</strong></p>";

        $tabla .= "
        <table id='tablaFor' style='width: 100%; border-collapse:collapse; border: none;'>
        <tbody>
        <tr>
            <th style='text-align: center; font-size:13px; width: 12%'>NOMBRE EMPRESA</th>
            <th style='text-align: center; font-size:13px; width: 20%'>CONTRIBUYENTE</th>
            <th style='text-align: center; font-size:13px; width: 9%'>ESTADO</th>
            <th style='text-align: center; font-size:13px; width: 10%'>NUM. TARJETA</th>
            <th style='text-align: center; font-size:13px; width: 9%'>INICIO OPERACIONES</th>
        </tr>";

        foreach ($infoEmpresa as $dd) {

            $tabla .= "<tr>
                <td style='font-size:11px; text-align: center'>" . $dd->nombre . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->nombrecontribuyente . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->nombreestado . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->num_tarjeta . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->iniciooperaciones . "</td>
            </tr>";
        }

        $tabla .= "</tbody></table>";

        $stylesheet = file_get_contents('css/cssconsolidado.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->SetMargins(0, 0, 5);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');

        $mpdf->WriteHTML($tabla,2);
        $mpdf->Output();
    }


    public function indexReporteContribuyentes(){

        $contribuyentes = Contribuyentes::orderBy('nombre')->get();
        return view('backend.admin.Reportes.Contribuyentes.vistaReporteContribuyentes', compact('contribuyentes'));
    }

    public function pdfReporteContribuyentes($id){

        $infoContribuyente = Contribuyentes::where('id', $id)->first();
        $persona = $infoContribuyente->nombre;

        $arrayEmpresas = Empresas::where('id_contribuyente', $id)->get();
        $arrayBuses = BusesDetalle::where('id_contribuyente', $id)->get();
        $arrayRotulos = RotulosDetalle::where('id_contribuyente', $id)->get();


        foreach ($arrayEmpresas as $dd){

            $categoria = '';
            $nombreEstado = '';
            if($dd->inicio_operaciones != null){
                $dd->inicio_operaciones = date("d-m-Y", strtotime($dd->inicio_operaciones));
            }

            if($infoEstado = EstadoEmpresas::where('id', $dd->id_estado_empresa)->first()){
                $nombreEstado = $infoEstado->estado;
            }

            $dd->nombreestado = $nombreEstado;

            if($infoActi = ActividadEconomica::where('id', $dd->id_actividad_economica)->first()){
                $categoria = $infoActi->categoria;
            }
            $dd->categoria = $categoria;
        }

        foreach ($arrayBuses as $dd){

            $estado = '';
            if($infoEstado = EstadoBuses::where('id', $dd->id_estado_buses)->first()){
                $estado = $infoEstado->estado;
            }

            $dd->estado = $estado;
        }

        foreach ($arrayRotulos as $dd){

            $estado = '';
            if($infoEstado = EstadoRotulo::where('id', $dd->id_estado_rotulo)->first()){
                $estado = $infoEstado->estado;
            }

            if($dd->fecha_apertura != null){
                $dd->fecha_apertura = date("d-m-Y", strtotime($dd->fecha_apertura));
            }

            $dd->estado = $estado;
        }

        //$mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf->SetTitle('Alcaldía Metapán | Contribuyentes');

        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logo.png';
        $logoelsalvador = 'images/EscudoSV.png';

        $tabla = "<div class='content'>
                    <img id='logo' src='$logoalcaldia'>
                    <img id='EscudoSV' src='$logoelsalvador'>
                    <h4>ALCALDIA MUNICIPAL DE METAPAN<br>
                    UNIDAD DE ADMINISTRACION TRIBUTARIA MUNICIPAL<br>
                    DEPARTAMENTO DE SANTA ANA, EL SALVADOR C.A</h4>
                    <hr>
            </div>";

        $tabla .= "<p align='center'>Contribuyente: <strong>$persona</strong></td>";

        if(sizeof($arrayEmpresas) > 0){
            $tabla .= "<p><strong>Empresas</strong></p>";

            $tabla .= "
        <table id='tablaFor' style='width: 100%; border-collapse:collapse; border: none;'>
        <tbody>
        <tr>
            <th style='text-align: center; font-size:13px; width: 12%'>NOMBRE EMPRESA</th>
            <th style='text-align: center; font-size:13px; width: 20%'>CATEGORÍA</th>
            <th style='text-align: center; font-size:13px; width: 9%'>ESTADO</th>
            <th style='text-align: center; font-size:13px; width: 10%'>NUM. TARJETA</th>
            <th style='text-align: center; font-size:13px; width: 9%'>INICIO OPERACIONES</th>
        </tr>";

            foreach ($arrayEmpresas as $dd) {

                $tabla .= "<tr>
                <td style='font-size:11px; text-align: center'>" . $dd->nombre . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->categoria . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->nombreestado . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->num_tarjeta . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->inicio_operaciones . "</td>
            </tr>";
            }

            $tabla .= "</tbody></table>";
        }

        if(sizeof($arrayBuses) > 0){
            $tabla .= "<br>";

            $tabla .= "<p><strong>Buses</strong></p>";

            $tabla .= "
        <table id='tablaFor' style='width: 100%; border-collapse:collapse; border: none;'>
        <tbody>
        <tr>
            <th style='text-align: center; font-size:13px; width: 12%'>CANTIDAD</th>
            <th style='text-align: center; font-size:13px; width: 20%'>ESTADO</th>
            <th style='text-align: center; font-size:13px; width: 9%'># FICHA</th>
            <th style='text-align: center; font-size:13px; width: 10%'>TARIFA</th>
            <th style='text-align: center; font-size:13px; width: 9%'>MONTO PAGAR</th>
        </tr>";

            foreach ($arrayBuses as $dd) {

                $tabla .= "<tr>
                <td style='font-size:11px; text-align: center'>" . $dd->cantidad . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->estado . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->nFicha . "</td>
                <td style='font-size:11px; text-align: center'>$" . $dd->tarifa . "</td>
                <td style='font-size:11px; text-align: center'>$" . $dd->monto_pagar . "</td>
            </tr>";
            }

            $tabla .= "</tbody></table>";
        }

        if(sizeof($arrayRotulos) > 0){
            $tabla .= "<br>";

            $tabla .= "<p><strong>Rotulos</strong></p>";

            $tabla .= "
        <table id='tablaFor' style='width: 100%; border-collapse:collapse; border: none;'>
        <tbody>
        <tr>
            <th style='text-align: center; font-size:13px; width: 20%'>EMPRESA</th>
            <th style='text-align: center; font-size:13px; width: 15%'># FICHA</th>
            <th style='text-align: center; font-size:13px; width: 10%'>ESTADO</th>
            <th style='text-align: center; font-size:13px; width: 10%'>FECHA APERTURA</th>
        </tr>";

            foreach ($arrayRotulos as $dd) {

                $tabla .= "<tr>
                <td style='font-size:11px; text-align: center'>" . $dd->nom_empresa . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->num_ficha . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->estado . "</td>
                <td style='font-size:11px; text-align: center'>" . $dd->fecha_apertura . "</td>
            </tr>";
            }

            $tabla .= "</tbody></table>";
        }


        $stylesheet = file_get_contents('css/cssconsolidado.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->SetMargins(0, 0, 5);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');

        $mpdf->WriteHTML($tabla,2);
        $mpdf->Output();
    }




    public function pdfReporteMoraTributaria(){

 
    $mora_empresas=Empresas::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
   
    ->select('empresa.id as id_empresa','empresa.nombre','empresa.matricula_comercio','empresa.nit',
    'empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones',
    'empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.id as id_contribuyente','contribuyente.nombre as contribuyente',
    'contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email',
    'contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 
    'contribuyente.direccion as direccionCont',
    'estado_empresa.estado','estado_empresa.id as id_estado_empresa',
    'giro_comercial.nombre_giro','giro_comercial.id as id_giro_comercial',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica',
     )
    ->get();

    if(sizeof($mora_empresas)>0)
    {
        $calculo_total_mora=0;
        foreach($mora_empresas as $dato)
        {
                $ultima_fecha_pago=Cobros::latest()
                ->where('id_empresa',$dato->id_empresa)
                ->pluck('periodo_cobro_fin')
                ->first();
                
                //** Sacando la ultima fecha de pago */
                if($ultima_fecha_pago==null)
                {
                    $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$dato->id_empresa)
                    ->pluck('id')
                    ->first();

                            if($id_matriculadetalle==null){
                                    $ultima_fecha_pago=$dato->inicio_operaciones; 
                                                                    
                            }else{
                                    
                                        $ultima_fecha_pago=CobrosMatriculas::latest()
                                            ->where('id_matriculas_detalle',$id_matriculadetalle)
                                            ->pluck('periodo_cobro_fin')
                                            ->first();

                                        //Nos aseguramos que si la última fecha de pago es nula se obtenga el inicio de operaciones
                                        if($ultima_fecha_pago==null)
                                        {
                                            $ultima_fecha_pago=$dato->inicio_operaciones;
                                            
                                        }
                                            
                                    }
                }
                
                //** Revisando que la ultima fecha sea el final de mes */
                $MesNumero=Carbon::createFromDate($ultima_fecha_pago)->format('d');

                $ultima_fecha_pago_original=$ultima_fecha_pago;
                if($MesNumero<='15')
                {
                    $ultima_fecha_pago=Carbon::parse($ultima_fecha_pago_original)->subMonthNoOverflow(1)->lastOfMonth();
                }
                else
                    {
                        $ultima_fecha_pago=Carbon::parse($ultima_fecha_pago_original)->lastOfMonth();
                    }
                //** Fin - Revisando que la ultima fecha sea el final de mes */

                //** Sacando la ultima tarifa */
                if($dato->id_giro_comercial!=1){
                        
                    $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$dato->id_empresa)
                    ->pluck('id')
                    ->first();

                    $dato_tarifa=CalificacionMatriculas::latest()
                    ->where('id_matriculas_detalle',$id_matriculadetalle)
                    ->first();
                    
                    if($dato_tarifa===null){
                        $tarifa=0.00;
                        $año='Sin calificación';
                        $año_real=Carbon::now()->format('Y');
                        
                    }else{
                            $tarifa=$dato_tarifa->pago_mensual;
                            $año=$dato_tarifa->año_calificacion;
                            $año_real=$dato_tarifa->año_calificacion;
                            }
                    

                }else{

                    $dato_tarifa=calificacion::latest()
                    ->where('id_empresa',$dato->id_empresa)
                    ->first();
                    
                    if($dato_tarifa===null){
                        $tarifa=0.00;
                        $año='Sin calificación';
                        $año_real=Carbon::now()->format('Y');
                        
                    }else{
                            $tarifa=$dato_tarifa->pago_mensual;
                            $año=$dato_tarifa->año_calificacion;
                            $año_real=$dato_tarifa->año_calificacion;
                            }

                }
               


               //** Creamos una fecha de corte personalizada para cada empresa segun su año de ultima calificación */
               $FechaCortePorEmpresa=Carbon::createFromDate($año_real, 12, 31);
               $ultima_fecha_pago_parseada=Carbon::parse($ultima_fecha_pago);
               $Inicio_moratorio=$ultima_fecha_pago_parseada->addDays(60);
               $año_ultimo_pago=$ultima_fecha_pago->format('Y');
                log::info('Inicio_moratorio: '.$Inicio_moratorio);
               $año_actual=Carbon::now()->format('Y');
               $fechahoy=Carbon::now();
                log::info('ultima_fecha_pago: '.$ultima_fecha_pago);
                log::info('Empresa: '.$dato->nombre.' año mora: '.$año_ultimo_pago);
                log::info('año_actual: '.$año_actual);

               //** Calculos */
                if($fechahoy>$FechaCortePorEmpresa){
                    $cantidad=ceil(carbon::parse($FechaCortePorEmpresa)->diffInDays(carbon::parse($ultima_fecha_pago)));
                    log::info('Cant Dias: '.$cantidad);
                    log::info('-----------------------------------------');
                }else{
                        if($Inicio_moratorio>$FechaCortePorEmpresa){
                            $cantidad=ceil(carbon::parse($FechaCortePorEmpresa)->diffInDays(carbon::parse($Inicio_moratorio)));
                            $cantidad=0;
                        }else{
                            $cantidad=ceil(carbon::parse($FechaCortePorEmpresa)->diffInDays(carbon::parse($Inicio_moratorio)));     
                        }                     
                        log::info('Cantidad Dias: '.$cantidad);
                        log::info('-----------------------------------------');
                }

               $meses=(($cantidad/365)*12);
               if($dato_tarifa===null){$meses_redondeado=0;}else{$meses_redondeado=round($meses,0);}
               $calculo_total_pago=$meses_redondeado*$tarifa;
               $calculo_total_mora=($calculo_total_mora+$calculo_total_pago);

               /** Formatenado variables numericas */
               $calculo_total_pago_formateado=number_format(( $calculo_total_pago), 2, '.', ',');
               $calculo_total_mora_formateado=number_format(( $calculo_total_mora), 2, '.', ',');
               $tarifa_formateado=number_format(($tarifa), 2, '.', ',');

               //** Modificando y creando nuevas variables */
               $dato->ultima_fecha_pago=Carbon::parse($ultima_fecha_pago)->format('d-m-Y');
               $dato->dato_contribuyente=$dato->contribuyente.$dato->apellido;
               $dato->meses=$meses_redondeado;
               $dato->tarifaE=$tarifa_formateado.' '.'/ '.$año;
               $dato->total_pago=$calculo_total_pago_formateado;
               $dato->total_moraE=$calculo_total_mora_formateado;

               $total_mora_final=$dato->total_moraE;
            }//** FIn Foreach mora_empresas */
            
            log::info('Total Mora: $'.$calculo_total_mora_formateado);
        }//** FIn if sizeof mora_empresas */

        
        //$mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
        $mpdf = new \Mpdf\Mpdf(['format' => 'LETTER']);
        $mpdf->SetTitle('Alcaldía Metapán | Mora Tributaria');

        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logo.png';
        $logoelsalvador = 'images/EscudoSV.png';

        $tabla = "<div class='content'>
                    <img id='logo' src='$logoalcaldia'>
                    <img id='EscudoSV' src='$logoelsalvador'>
                    <h4>ALCALDIA MUNICIPAL DE METAPAN<br>
                    UNIDAD DE ADMINISTRACION TRIBUTARIA MUNICIPAL<br>
                    DEPARTAMENTO DE SANTA ANA, EL SALVADOR C.A</h4>
                    <hr>
            </div>";

            if(sizeof($mora_empresas) > 0){
                $tabla .= "<p><strong>REPORTE DE MORA TRIBUTARIA</strong></p>";
    
                $tabla .= "
            <table id='tablaMora' style='width: 100%; border-collapse:collapse; border: none;'>
            <tbody> 
            <tr>
                <th style='width: 9%; text-align: center;'>N° FICHA</th>
                <th style='width: 15%; text-align: center;'>ACT. ECONOMICA</th>
                <th style='width: 20%; text-align: center;'>EMPRESA O NEGOCIO</th>       
                <th style='width: 15%; text-align: center;'>ULTIMO PAGO</th>
                <th style='width: 10%; text-align: center;'>MESES</th>
                <th style='width: 20%; text-align: center;'>ULTIMA TARIFA/AÑO</th>
                <th style='width: 12%; text-align: center;'>MORA</th>
            </tr>";

            foreach ($mora_empresas as $dd) {
            //Fila1
            $tabla .= "<tr>
                            <td align='center'>
                            <span class='badge badge-pill badge-dark'>". $dd->num_tarjeta . "</span> </td>
                            
                            <td align='center'>". $dd->codigo_atc_economica ."</td>

                            <td align='center'>". $dd->nombre ."</td>

                            <td align='center'>". $dd->ultima_fecha_pago ."</td>

                            <td align='center'>". $dd->meses ."</td>

                            <td align='center'>". '$'. $dd->tarifaE ."</td>

                            <td align='center'>". '$'. $dd->total_pago ."</td>

                           </tr>";

            }//** Fin foreach */

            $tabla .= "<tr>
                            
            <td align='right' colspan='7'>
                <b>TOTAL: ". $total_mora_final . "</b>
            </td>

           </tr>";

            $tabla .= "</tbody></table>";

            }

        $stylesheet = file_get_contents('css/cssconsolidado.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->SetMargins(0, 0, 5);

        $mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');

        $mpdf->WriteHTML($tabla,2);
        $mpdf->Output();
    }

    
    public function indexReporteMoraTributaria(){

       
        $contribuyentes = Contribuyentes::orderBy('nombre')->get();
        foreach($contribuyentes as $dato){
            $dato->nombre_contribuyente= $dato->nombre.' '.$dato->apellido;
        }

        return view('backend.admin.Reportes.MoraTributaria.vistaReporteMoraTributaria', compact('contribuyentes'));
    }

    public function indexReporteMoraTributariaPeriodica(){

       
        $contribuyentes = Contribuyentes::orderBy('nombre')->get();
        foreach($contribuyentes as $dato){
            $dato->nombre_contribuyente= $dato->nombre.' '.$dato->apellido;
        }

        return view('backend.admin.Reportes.MoraTributariaPeriodica.vistaReporteMoraTributariaPeriodica', compact('contribuyentes'));
    }

    public function calculo_mora(){

    $mora_empresas=Empresas::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
   
    ->select('empresa.id as id_empresa','empresa.nombre','empresa.matricula_comercio','empresa.nit',
    'empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones',
    'empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.id as id_contribuyente','contribuyente.nombre as contribuyente',
    'contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email',
    'contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 
    'contribuyente.direccion as direccionCont',
    'estado_empresa.estado','estado_empresa.id as id_estado_empresa',
    'giro_comercial.nombre_giro','giro_comercial.id as id_giro_comercial',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica',
     )
    ->get();

    if(sizeof($mora_empresas)>0)
    {
        $calculo_total_mora=0;
        foreach($mora_empresas as $dato)
        {
                $ultima_fecha_pago=Cobros::latest()
                ->where('id_empresa',$dato->id_empresa)
                ->pluck('periodo_cobro_fin')
                ->first();
                
                //** Sacando la ultima fecha de pago */
                if($ultima_fecha_pago==null)
                {
                    $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$dato->id_empresa)
                    ->pluck('id')
                    ->first();

                            if($id_matriculadetalle==null){
                                    $ultima_fecha_pago=$dato->inicio_operaciones; 
                                                                    
                            }else{
                                    
                                        $ultima_fecha_pago=CobrosMatriculas::latest()
                                            ->where('id_matriculas_detalle',$id_matriculadetalle)
                                            ->pluck('periodo_cobro_fin')
                                            ->first();

                                        //Nos aseguramos que si la última fecha de pago es nula se obtenga el inicio de operaciones
                                        if($ultima_fecha_pago==null)
                                        {
                                            $ultima_fecha_pago=$dato->inicio_operaciones;
                                            
                                        }
                                            
                                    }
                }

                //** Revisando que la ultima fecha sea el final de mes */
                $MesNumero=Carbon::createFromDate($ultima_fecha_pago)->format('d');

                $ultima_fecha_pago_original=$ultima_fecha_pago;
                if($MesNumero<='15')
                {
                    $ultima_fecha_pago=Carbon::parse($ultima_fecha_pago_original)->subMonthNoOverflow(1)->lastOfMonth();
                }
                else
                    {
                        $ultima_fecha_pago=Carbon::parse($ultima_fecha_pago_original)->lastOfMonth();
                    }
                //** Fin - Revisando que la ultima fecha sea el final de mes */     

                //** Sacando la ultima tarifa */
                if($dato->id_giro_comercial!=1){
                    
                    $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$dato->id_empresa)
                    ->pluck('id')
                    ->first();

                    $dato_tarifa=CalificacionMatriculas::latest()
                    ->where('id_matriculas_detalle',$id_matriculadetalle)
                    ->first();
                    
                    if($dato_tarifa===null){
                        $tarifa=0.00;
                        $año='Sin calificación';
                        $año_real=Carbon::now()->format('Y');
                     
                    }else{
                            $tarifa=$dato_tarifa->pago_mensual;
                            $año=$dato_tarifa->año_calificacion;
                            $año_real=$dato_tarifa->año_calificacion;
                         }
                   

                }else{

                    $dato_tarifa=calificacion::latest()
                    ->where('id_empresa',$dato->id_empresa)
                    ->first();
                    
                    if($dato_tarifa===null){
                        $tarifa=0.00;
                        $año='Sin calificación';
                        $año_real=Carbon::now()->format('Y');
                        
                    }else{
                            $tarifa=$dato_tarifa->pago_mensual;
                            $año=$dato_tarifa->año_calificacion;
                            $año_real=$dato_tarifa->año_calificacion;
                         }

                }

                $ultima_fecha_pago_parseada=Carbon::parse($ultima_fecha_pago);        
                $Inicio_moratorio=$ultima_fecha_pago_parseada->addDays(60);
                $año_ultimo_pago=$ultima_fecha_pago->format('Y');
                log::info('Inicio_moratorio: '.$Inicio_moratorio);
                $año_actual=Carbon::now()->format('Y');
                $fechahoy=Carbon::now();
                log::info('ultima_fecha_pago: '.$ultima_fecha_pago);
                log::info('Empresa: '.$dato->nombre.' año mora: '.$año_ultimo_pago);
                log::info('año_actual: '.$año_actual);

                //** Creamos una fecha de corte personalizada para cada empresa segun su año de ultima calificación */
                $FechaCortePorEmpresa=Carbon::createFromDate($año_real, 12, 31);

                //** Calculos */

                if($fechahoy>$FechaCortePorEmpresa){
                    $cantidad=ceil(carbon::parse($FechaCortePorEmpresa)->diffInDays(carbon::parse($ultima_fecha_pago)));
                    log::info('Cant Dias: '.$cantidad);
                    log::info('-----------------------------------------');
                }else{
                        if($Inicio_moratorio>$FechaCortePorEmpresa){
                            $cantidad=ceil(carbon::parse($FechaCortePorEmpresa)->diffInDays(carbon::parse($Inicio_moratorio)));
                            $cantidad=0;
                        }else{
                            $cantidad=ceil(carbon::parse($FechaCortePorEmpresa)->diffInDays(carbon::parse($Inicio_moratorio)));     
                        }                     
                        log::info('Cantidad Dias: '.$cantidad);
                        log::info('-----------------------------------------');
                }


                $meses=(($cantidad/365)*12);
                if($dato_tarifa===null){$meses_redondeado=0;}else{$meses_redondeado=round($meses,0);}
                $calculo_total_pago=$meses_redondeado*$tarifa;
                $calculo_total_mora=($calculo_total_mora+$calculo_total_pago);

                /** Formatenado variables numericas */
                $calculo_total_pago_formateado=number_format(( $calculo_total_pago), 2, '.', ',');
                $calculo_total_mora_formateado=number_format(( $calculo_total_mora), 2, '.', ',');
                $tarifa_formateado=number_format(($tarifa), 2, '.', ',');

                //** Modificando y creando nuevas variables */
                $dato->ultima_fecha_pago=Carbon::parse($ultima_fecha_pago)->format('d-m-Y');
                $dato->dato_contribuyente=$dato->contribuyente.$dato->apellido;
                $dato->meses=$meses_redondeado;
                $dato->tarifaE=$tarifa_formateado.' '.'/ '.$año;
                $dato->total_pago=$calculo_total_pago_formateado;
                $dato->total_moraE=$calculo_total_mora_formateado;

                $total_mora_final=$dato->total_moraE;
        }//** FIn Foreach mora_empresas */
            
            log::info('Total Mora: $'.$calculo_total_mora_formateado);
     }

    return [
        'success' => 1,
        'mora_empresas'=>$mora_empresas,
        'total_mora_final'=>$total_mora_final,
        ];

    }

    public function calculo_mora_codigos(){

    //Variables por codigo para guardar la mora
    $mora_11801=0;
    $mora_11802=0;
    $mora_11803=0;
    $mora_11804=0;
    $mora_11806=0;
    $mora_11808=0;
    $mora_11809=0;
    $mora_11810=0;
    $mora_11813=0;
    $mora_11814=0;
    $mora_11815=0;
    $mora_11816=0;
    $mora_11899=0;
    $mora_15799=0;

    $mora_empresas=Empresas::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
   
    ->select('empresa.id as id_empresa','empresa.nombre','empresa.matricula_comercio','empresa.nit',
    'empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones',
    'empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.id as id_contribuyente','contribuyente.nombre as contribuyente',
    'contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email',
    'contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 
    'contribuyente.direccion as direccionCont',
    'estado_empresa.estado','estado_empresa.id as id_estado_empresa',
    'giro_comercial.nombre_giro','giro_comercial.id as id_giro_comercial',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica',
     )
    ->get();

    if(sizeof($mora_empresas)>0)
    {
        $calculo_total_mora=0;
        foreach($mora_empresas as $dato)
        {
                $ultima_fecha_pago=Cobros::latest()
                ->where('id_empresa',$dato->id_empresa)
                ->pluck('periodo_cobro_fin')
                ->first();
                
                //** Sacando la ultima fecha de pago */
                if($ultima_fecha_pago==null)
                {
                    $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$dato->id_empresa)
                    ->pluck('id')
                    ->first();

                            if($id_matriculadetalle==null){
                                    $ultima_fecha_pago=$dato->inicio_operaciones; 
                                                                    
                            }else{
                                    
                                        $ultima_fecha_pago=CobrosMatriculas::latest()
                                            ->where('id_matriculas_detalle',$id_matriculadetalle)
                                            ->pluck('periodo_cobro_fin')
                                            ->first();

                                        //Nos aseguramos que si la última fecha de pago es nula se obtenga el inicio de operaciones
                                        if($ultima_fecha_pago==null)
                                        {
                                            $ultima_fecha_pago=$dato->inicio_operaciones;
                                            
                                        }
                                            
                                    }
                }

                //** Revisando que la ultima fecha sea el final de mes */
                $MesNumero=Carbon::createFromDate($ultima_fecha_pago)->format('d');

                $ultima_fecha_pago_original=$ultima_fecha_pago;
                if($MesNumero<='15')
                {
                    $ultima_fecha_pago=Carbon::parse($ultima_fecha_pago_original)->subMonthNoOverflow(1)->lastOfMonth();
                }
                else
                    {
                        $ultima_fecha_pago=Carbon::parse($ultima_fecha_pago_original)->lastOfMonth();
                    }
                //** Fin - Revisando que la ultima fecha sea el final de mes */
                
                //** Sacando la ultima tarifa */
                if($dato->id_giro_comercial!=1){
                    
                    $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$dato->id_empresa)
                    ->pluck('id')
                    ->first();

                    $dato_tarifa=CalificacionMatriculas::latest()
                    ->where('id_matriculas_detalle',$id_matriculadetalle)
                    ->first();
                    
                    if($dato_tarifa===null){
                        $tarifa=0.00;
                        $año='Sin calificación';
                        $año_real=Carbon::now()->format('Y');
                     
                    }else{
                            $tarifa=$dato_tarifa->pago_mensual;
                            $año=$dato_tarifa->año_calificacion;
                            $año_real=$dato_tarifa->año_calificacion;
                         }
                   

                }else{

                    $dato_tarifa=calificacion::latest()
                    ->where('id_empresa',$dato->id_empresa)
                    ->first();
                    
                    if($dato_tarifa===null){
                        $tarifa=0.00;
                        $año='Sin calificación';
                        $año_real=Carbon::now()->format('Y');
                        
                    }else{
                            $tarifa=$dato_tarifa->pago_mensual;
                            $año=$dato_tarifa->año_calificacion;
                            $año_real=$dato_tarifa->año_calificacion;
                         }

                }
               
                //** Creamos una fecha de corte personalizada para cada empresa segun su año de ultima calificación */
                $FechaCortePorEmpresa=Carbon::createFromDate($año_real, 12, 31);
                $ultima_fecha_pago_parseada=Carbon::parse($ultima_fecha_pago);
                $Inicio_moratorio=$ultima_fecha_pago_parseada->addDays(60);
                $año_ultimo_pago=$ultima_fecha_pago->format('Y');
                    log::info('Inicio_moratorio: '.$Inicio_moratorio);
                $año_actual=Carbon::now()->format('Y');
                $fechahoy=Carbon::now();
                    log::info('ultima_fecha_pago: '.$ultima_fecha_pago);
                    log::info('Empresa: '.$dato->nombre.' año mora: '.$año_ultimo_pago);
                    log::info('año_actual: '.$año_actual);

               //** Calculos */
                if($fechahoy>$FechaCortePorEmpresa){
                    $cantidad=ceil(carbon::parse($FechaCortePorEmpresa)->diffInDays(carbon::parse($ultima_fecha_pago)));
                    log::info('Cant Dias: '.$cantidad);
                    log::info('-----------------------------------------');
                }else{
                        if($Inicio_moratorio>$FechaCortePorEmpresa){
                            $cantidad=ceil(carbon::parse($FechaCortePorEmpresa)->diffInDays(carbon::parse($Inicio_moratorio)));
                            $cantidad=0;
                        }else{
                            $cantidad=ceil(carbon::parse($FechaCortePorEmpresa)->diffInDays(carbon::parse($Inicio_moratorio)));     
                        }                     
                        log::info('Cantidad Dias: '.$cantidad);
                        log::info('-----------------------------------------');
                }

                $meses=(($cantidad/365)*12);
                if($dato_tarifa===null){$meses_redondeado=0;}else{$meses_redondeado=round($meses,0);}
                $calculo_total_pago=$meses_redondeado*$tarifa;
                $calculo_total_mora=($calculo_total_mora+$calculo_total_pago);

                if ($dato->codigo_atc_economica==11801){$mora_11801 = ($mora_11801+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==11802) {$mora_11802 = ($mora_11802+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==11803) {$mora_11803 = ($mora_11803+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==11804) {$mora_11804 = ($mora_11804+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==11806) {$mora_11806 = ($mora_11806+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==11808) {$mora_11808 = ($mora_11808+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==11809) {$mora_11809 = ($mora_11809+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==11810) {$mora_11810 = ($mora_11810+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==11813) {$mora_11813 = ($mora_11813+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==11814) {$mora_11814 = ($mora_11814+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==11815) {$mora_11815 = ($mora_11815+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==11816) {$mora_11816 = ($mora_11816+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==11899) {$mora_11899 = ($mora_11899+$calculo_total_pago);}
                else if ($dato->codigo_atc_economica==15799) {$mora_15799 = ($mora_15799+$calculo_total_pago);}

                /** Formatenado variables numericas */
                $calculo_total_pago_formateado=number_format(( $calculo_total_pago), 2, '.', ',');
                $calculo_total_mora_formateado=number_format(( $calculo_total_mora), 2, '.', ',');
                $tarifa_formateado=number_format(($tarifa), 2, '.', ',');

                
                //** Modificando y creando nuevas variables */
                $dato->ultima_fecha_pago=Carbon::parse($ultima_fecha_pago)->format('d-m-Y');
                $dato->dato_contribuyente=$dato->contribuyente.$dato->apellido;
                $dato->meses=$meses_redondeado;
                $dato->tarifaE=$tarifa_formateado.' '.'/ '.$año;
                $dato->total_pago=$calculo_total_pago_formateado;
                $dato->total_moraE=$calculo_total_mora_formateado;

 

                $total_mora_final=$dato->total_moraE;
        }//** FIn Foreach mora_empresas */

                $mora_11801_formateado=number_format(($mora_11801), 2, '.', ',');
                $mora_11802_formateado=number_format(($mora_11802), 2, '.', ',');
                $mora_11803_formateado=number_format(($mora_11803), 2, '.', ',');
                $mora_11804_formateado=number_format(($mora_11804), 2, '.', ',');  
                $mora_11806_formateado=number_format(($mora_11806), 2, '.', ',');
                $mora_11808_formateado=number_format(($mora_11808), 2, '.', ',');
                $mora_11809_formateado=number_format(($mora_11809), 2, '.', ',');
                $mora_11810_formateado=number_format(($mora_11810), 2, '.', ',');
                $mora_11813_formateado=number_format(($mora_11813), 2, '.', ',');
                $mora_11814_formateado=number_format(($mora_11814), 2, '.', ',');
                $mora_11815_formateado=number_format(($mora_11815), 2, '.', ',');
                $mora_11816_formateado=number_format(($mora_11816), 2, '.', ',');
                $mora_11899_formateado=number_format(($mora_11899), 2, '.', ',');
                $mora_15799_formateado=number_format(($mora_15799), 2, '.', ',');

           // log::info('Total Mora: $'.$calculo_total_mora_formateado);
          
        }

    return [
        'success' => 1,
        'mora_11801_formateado'=>$mora_11801_formateado,
        'mora_11802_formateado'=>$mora_11802_formateado,
        'mora_11803_formateado'=>$mora_11803_formateado,
        'mora_11804_formateado'=>$mora_11804_formateado,
        'mora_11806_formateado'=>$mora_11806_formateado,
        'mora_11808_formateado'=>$mora_11808_formateado,
        'mora_11809_formateado'=>$mora_11809_formateado,
        'mora_11810_formateado'=>$mora_11810_formateado,
        'mora_11813_formateado'=>$mora_11813_formateado,
        'mora_11814_formateado'=>$mora_11814_formateado,
        'mora_11815_formateado'=>$mora_11815_formateado,
        'mora_11816_formateado'=>$mora_11816_formateado,
        'mora_11899_formateado'=>$mora_11899_formateado,
        'mora_15799_formateado'=>$mora_15799_formateado,

        'mora_11801'=>$mora_11801,
        'mora_11802'=>$mora_11802,
        'mora_11803'=>$mora_11803,
        'mora_11804'=>$mora_11804,
        'mora_11806'=>$mora_11806,
        'mora_11808'=>$mora_11808,
        'mora_11809'=>$mora_11809,
        'mora_11810'=>$mora_11810,
        'mora_11813'=>$mora_11813,
        'mora_11814'=>$mora_11814,
        'mora_11815'=>$mora_11815,
        'mora_11816'=>$mora_11816,
        'mora_11899'=>$mora_11899,
        'mora_15799'=>$mora_15799,
        
        'total_mora_final'=>$total_mora_final,
        ];

    }

    public function calculo_mora_tasas(){

        //Variables por codigo para guardar la mora
        $mora_11801=0;
        $mora_11802=0;
        $mora_11803=0;
        $mora_11804=0;
        $mora_11806=0;
        $mora_11808=0;
        $mora_11809=0;
        $mora_11810=0;
        $mora_11813=0;
        $mora_11814=0;
        $mora_11815=0;
        $mora_11816=0;
        $mora_11899=0;
        $mora_15799=0;
    
        $mora_empresas=Empresas::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
        ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
        ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
        ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
       
        ->select('empresa.id as id_empresa','empresa.nombre','empresa.matricula_comercio','empresa.nit',
        'empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones',
        'empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.id as id_contribuyente','contribuyente.nombre as contribuyente',
        'contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email',
        'contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 
        'contribuyente.direccion as direccionCont',
        'estado_empresa.estado','estado_empresa.id as id_estado_empresa',
        'giro_comercial.nombre_giro','giro_comercial.id as id_giro_comercial',
        'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica',
         )
        ->get();
    
        if(sizeof($mora_empresas)>0)
        {
            $calculo_total_mora=0;
            foreach($mora_empresas as $dato)
            {
                    $ultima_fecha_pago=Cobros::latest()
                    ->where('id_empresa',$dato->id_empresa)
                    ->pluck('periodo_cobro_fin')
                    ->first();
                    
                    //** Sacando la ultima fecha de pago */
                    if($ultima_fecha_pago==null)
                    {
                        $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$dato->id_empresa)
                        ->pluck('id')
                        ->first();
    
                                if($id_matriculadetalle==null){
                                        $ultima_fecha_pago=$dato->inicio_operaciones; 
                                                                        
                                }else{
                                        
                                            $ultima_fecha_pago=CobrosMatriculas::latest()
                                                ->where('id_matriculas_detalle',$id_matriculadetalle)
                                                ->pluck('periodo_cobro_fin')
                                                ->first();
    
                                            //Nos aseguramos que si la última fecha de pago es nula se obtenga el inicio de operaciones
                                            if($ultima_fecha_pago==null)
                                            {
                                                $ultima_fecha_pago=$dato->inicio_operaciones;
                                                
                                            }
                                                
                                        }
                    }
    
                    //** Revisando que la ultima fecha sea el final de mes */
                    $MesNumero=Carbon::createFromDate($ultima_fecha_pago)->format('d');
    
                    $ultima_fecha_pago_original=$ultima_fecha_pago;
                    if($MesNumero<='15')
                    {
                        $ultima_fecha_pago=Carbon::parse($ultima_fecha_pago_original)->subMonthNoOverflow(1)->lastOfMonth();
                    }
                    else
                        {
                            $ultima_fecha_pago=Carbon::parse($ultima_fecha_pago_original)->lastOfMonth();
                        }
                    //** Fin - Revisando que la ultima fecha sea el final de mes */
                    
                    //** Sacando la ultima tarifa */
                    if($dato->id_giro_comercial!=1){
                        
                        $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$dato->id_empresa)
                        ->pluck('id')
                        ->first();
    
                        $dato_tarifa=CalificacionMatriculas::latest()
                        ->where('id_matriculas_detalle',$id_matriculadetalle)
                        ->first();
                        
                        if($dato_tarifa===null){
                            $tarifa=0.00;
                            $año='Sin calificación';
                            $año_real=Carbon::now()->format('Y');
                         
                        }else{
                                $tarifa=$dato_tarifa->pago_mensual;
                                $año=$dato_tarifa->año_calificacion;
                                $año_real=$dato_tarifa->año_calificacion;
                             }
                       
    
                    }else{
    
                        $dato_tarifa=calificacion::latest()
                        ->where('id_empresa',$dato->id_empresa)
                        ->first();
                        
                        if($dato_tarifa===null){
                            $tarifa=0.00;
                            $año='Sin calificación';
                            $año_real=Carbon::now()->format('Y');
                            
                        }else{
                                $tarifa=$dato_tarifa->pago_mensual;
                                $año=$dato_tarifa->año_calificacion;
                                $año_real=$dato_tarifa->año_calificacion;
                             }
    
                    }
                   
                    //** Creamos una fecha de corte personalizada para cada empresa segun su año de ultima calificación */
                    $FechaCortePorEmpresa=Carbon::createFromDate($año_real, 12, 31);
                    $ultima_fecha_pago_parseada=Carbon::parse($ultima_fecha_pago);
                    $Inicio_moratorio=$ultima_fecha_pago_parseada->addDays(60);
                    $año_ultimo_pago=$ultima_fecha_pago->format('Y');
                        log::info('Inicio_moratorio: '.$Inicio_moratorio);
                    $año_actual=Carbon::now()->format('Y');
                    $fechahoy=Carbon::now();
                        log::info('ultima_fecha_pago: '.$ultima_fecha_pago);
                        log::info('Empresa: '.$dato->nombre.' año mora: '.$año_ultimo_pago);
                        log::info('año_actual: '.$año_actual);

                    //** Calculos */
                        if($fechahoy>$FechaCortePorEmpresa){
                            $cantidad=ceil(carbon::parse($FechaCortePorEmpresa)->diffInDays(carbon::parse($ultima_fecha_pago)));
                            log::info('Cant Dias: '.$cantidad);
                            log::info('-----------------------------------------');
                        }else{
                                if($Inicio_moratorio>$FechaCortePorEmpresa){
                                    $cantidad=ceil(carbon::parse($FechaCortePorEmpresa)->diffInDays(carbon::parse($Inicio_moratorio)));
                                    $cantidad=0;
                                }else{
                                    $cantidad=ceil(carbon::parse($FechaCortePorEmpresa)->diffInDays(carbon::parse($Inicio_moratorio)));     
                                }                     
                                log::info('Cantidad Dias: '.$cantidad);
                                log::info('-----------------------------------------');
                        }

                    $meses=(($cantidad/365)*12);
                    if($dato_tarifa===null){$meses_redondeado=0;}else{$meses_redondeado=round($meses,0);}
                    $calculo_total_pago=$meses_redondeado*$tarifa;
                    $calculo_total_mora=($calculo_total_mora+$calculo_total_pago);
    
                    if ($dato->codigo_atc_economica==11801){$mora_11801 = ($mora_11801+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==11802) {$mora_11802 = ($mora_11802+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==11803) {$mora_11803 = ($mora_11803+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==11804) {$mora_11804 = ($mora_11804+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==11806) {$mora_11806 = ($mora_11806+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==11808) {$mora_11808 = ($mora_11808+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==11809) {$mora_11809 = ($mora_11809+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==11810) {$mora_11810 = ($mora_11810+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==11813) {$mora_11813 = ($mora_11813+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==11814) {$mora_11814 = ($mora_11814+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==11815) {$mora_11815 = ($mora_11815+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==11816) {$mora_11816 = ($mora_11816+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==11899) {$mora_11899 = ($mora_11899+$calculo_total_pago);}
                    else if ($dato->codigo_atc_economica==15799) {$mora_15799 = ($mora_15799+$calculo_total_pago);}
    
                    /** Formatenado variables numericas */
                    $calculo_total_pago_formateado=number_format(( $calculo_total_pago), 2, '.', ',');
                    $calculo_total_mora_formateado=number_format(( $calculo_total_mora), 2, '.', ',');
                    $tarifa_formateado=number_format(($tarifa), 2, '.', ',');
    
                    
                    //** Modificando y creando nuevas variables */
                    $dato->ultima_fecha_pago=Carbon::parse($ultima_fecha_pago)->format('d-m-Y');
                    $dato->dato_contribuyente=$dato->contribuyente.$dato->apellido;
                    $dato->meses=$meses_redondeado;
                    $dato->tarifaE=$tarifa_formateado.' '.'/ '.$año;
                    $dato->total_pago=$calculo_total_pago_formateado;
                    $dato->total_moraE=$calculo_total_mora_formateado;
    
     
    
                    $total_mora_final=$dato->total_moraE;
            }//** FIn Foreach mora_empresas */
    
                    $mora_11801_formateado=number_format(($mora_11801), 2, '.', ',');
                    $mora_11802_formateado=number_format(($mora_11802), 2, '.', ',');
                    $mora_11803_formateado=number_format(($mora_11803), 2, '.', ',');
                    $mora_11804_formateado=number_format(($mora_11804), 2, '.', ',');  
                    $mora_11806_formateado=number_format(($mora_11806), 2, '.', ',');
                    $mora_11808_formateado=number_format(($mora_11808), 2, '.', ',');
                    $mora_11809_formateado=number_format(($mora_11809), 2, '.', ',');
                    $mora_11810_formateado=number_format(($mora_11810), 2, '.', ',');
                    $mora_11813_formateado=number_format(($mora_11813), 2, '.', ',');
                    $mora_11814_formateado=number_format(($mora_11814), 2, '.', ',');
                    $mora_11815_formateado=number_format(($mora_11815), 2, '.', ',');
                    $mora_11816_formateado=number_format(($mora_11816), 2, '.', ',');
                    $mora_11899_formateado=number_format(($mora_11899), 2, '.', ',');
                    $mora_15799_formateado=number_format(($mora_15799), 2, '.', ',');
    
                log::info('Total Mora: $'.$calculo_total_mora_formateado);
              
            }
    
        return [
            'success' => 1,
            'mora_11801_formateado'=>$mora_11801_formateado,
            'mora_11802_formateado'=>$mora_11802_formateado,
            'mora_11803_formateado'=>$mora_11803_formateado,
            'mora_11804_formateado'=>$mora_11804_formateado,
            'mora_11806_formateado'=>$mora_11806_formateado,
            'mora_11808_formateado'=>$mora_11808_formateado,
            'mora_11809_formateado'=>$mora_11809_formateado,
            'mora_11810_formateado'=>$mora_11810_formateado,
            'mora_11813_formateado'=>$mora_11813_formateado,
            'mora_11814_formateado'=>$mora_11814_formateado,
            'mora_11815_formateado'=>$mora_11815_formateado,
            'mora_11816_formateado'=>$mora_11816_formateado,
            'mora_11899_formateado'=>$mora_11899_formateado,
            'mora_15799_formateado'=>$mora_15799_formateado,

            
            'mora_11801'=>$mora_11801,
            'mora_11802'=>$mora_11802,
            'mora_11803'=>$mora_11803,
            'mora_11804'=>$mora_11804,
            'mora_11806'=>$mora_11806,
            'mora_11808'=>$mora_11808,
            'mora_11809'=>$mora_11809,
            'mora_11810'=>$mora_11810,
            'mora_11813'=>$mora_11813,
            'mora_11814'=>$mora_11814,
            'mora_11815'=>$mora_11815,
            'mora_11816'=>$mora_11816,
            'mora_11899'=>$mora_11899,
            'mora_15799'=>$mora_15799,
            
            'total_mora_final'=>$total_mora_final,
            ];
    
        }

//************** Reportes mora global por periodo ****************/

public function calculo_mora_periodo(Request $request){

    $fecha_inicio_mora=Carbon::parse($request->fecha_inicio_mora);
    $fecha_final_mora=Carbon::parse($request->fecha_fin_mora);
    log::info('fecha_inicio_mora: '.$fecha_inicio_mora);
    log::info('fecha_final_mora: '.$fecha_final_mora);
    $mora_empresas=Empresas::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
   
    ->select('empresa.id as id_empresa','empresa.nombre','empresa.matricula_comercio','empresa.nit',
    'empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones',
    'empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.id as id_contribuyente','contribuyente.nombre as contribuyente',
    'contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email',
    'contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 
    'contribuyente.direccion as direccionCont',
    'estado_empresa.estado','estado_empresa.id as id_estado_empresa',
    'giro_comercial.nombre_giro','giro_comercial.id as id_giro_comercial',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica',
     )
    ->get();

    if(sizeof($mora_empresas)>0)
    {
        $calculo_total_mora=0;
        foreach($mora_empresas as $dato)
        {
                $ultima_fecha_pago=Cobros::latest()
                ->where('id_empresa',$dato->id_empresa)
                ->pluck('periodo_cobro_fin')
                ->first();
                
                //** Sacando la última fecha de pago */
                if($ultima_fecha_pago==null)
                {
                    $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$dato->id_empresa)
                    ->pluck('id')
                    ->first();

                            if($id_matriculadetalle==null){
                                    $ultima_fecha_pago=$dato->inicio_operaciones; 
                                                                    
                            }else{
                                    
                                        $ultima_fecha_pago=CobrosMatriculas::latest()
                                            ->where('id_matriculas_detalle',$id_matriculadetalle)
                                            ->pluck('periodo_cobro_fin')
                                            ->first();

                                        //Nos aseguramos que si la última fecha de pago es nula se obtenga el inicio de operaciones
                                        if($ultima_fecha_pago==null)
                                        {
                                            $ultima_fecha_pago=$dato->inicio_operaciones;
                                            
                                        }
                                            
                                    }
                }

                //** Revisando que la ultima fecha sea el final de mes */
                $MesNumero=Carbon::createFromDate($ultima_fecha_pago)->format('d');

                $ultima_fecha_pago_original=$ultima_fecha_pago;
                if($MesNumero<='15')
                {
                    $ultima_fecha_pago=Carbon::parse($ultima_fecha_pago_original)->subMonthNoOverflow(1)->lastOfMonth();
                }
                else
                    {
                        $ultima_fecha_pago=Carbon::parse($ultima_fecha_pago_original)->lastOfMonth();
                    }
                //** Fin - Revisando que la ultima fecha sea el final de mes */
                
                //** Sacando la ultima tarifa */
                if($dato->id_giro_comercial!=1){
                    
                    $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$dato->id_empresa)
                    ->pluck('id')
                    ->first();

                    $dato_tarifa=CalificacionMatriculas::latest()
                    ->where('id_matriculas_detalle',$id_matriculadetalle)
                    ->first();
                    
                    if($dato_tarifa===null){
                        $tarifa=0.00;
                        $año='Sin calificación';
                        $año_real=Carbon::now()->format('Y');
                     
                    }else{
                            $tarifa=$dato_tarifa->pago_mensual;
                            $año=$dato_tarifa->año_calificacion;
                            $año_real=$dato_tarifa->año_calificacion;
                         }
                   

                }else{

                    $dato_tarifa=calificacion::latest()
                    ->where('id_empresa',$dato->id_empresa)
                    ->first();
                    
                    if($dato_tarifa===null){
                        $tarifa=0.00;
                        $año='Sin calificación';
                        $año_real=Carbon::now()->format('Y');
                        
                    }else{
                            $tarifa=$dato_tarifa->pago_mensual;
                            $año=$dato_tarifa->año_calificacion;
                            $año_real=$dato_tarifa->año_calificacion;
                         }

                }
               
                //** Creamos una fecha de corte personalizada para cada empresa segun su año de ultima calificación */
                
                $ultima_fecha_pago_parseada=Carbon::parse($ultima_fecha_pago);
                $Inicio_moratorio=$ultima_fecha_pago_parseada->addDays(60);
                $año_ultimo_pago=$ultima_fecha_pago->format('Y');
                $año_actual=Carbon::now()->format('Y');
                $fechahoy=Carbon::now();

               //** Calculos */


                log::info('Empresa: '.$dato->nombre.' ultima_fecha_pago: '.$ultima_fecha_pago);
               
                ///Intentooooooo
                if($ultima_fecha_pago>=$fecha_inicio_mora and $ultima_fecha_pago<=$fecha_final_mora){
                    if($fechahoy>$fecha_final_mora){
                        $cantidad=ceil(carbon::parse($fecha_final_mora)->diffInDays(carbon::parse($ultima_fecha_pago)));
                        log::info('Cant Dias: '.$cantidad);
                        log::info('-----------------------------------------');
                    }else{
                            if($Inicio_moratorio>$fecha_final_mora){
                                $cantidad=ceil(carbon::parse($fecha_final_mora)->diffInDays(carbon::parse($Inicio_moratorio)));
                                $cantidad=0;
                            }else{
                                $cantidad=ceil(carbon::parse($fecha_final_mora)->diffInDays(carbon::parse($Inicio_moratorio)));     
                            }                     
                            log::info('Cantidad Dias: '.$cantidad);
                            log::info('-----------------------------------------');
                    }
                    $meses=(($cantidad/365)*12);
                    log::info('Empresa: '.$dato->nombre.' ***Entro en el periodo seleccionado***'.' Meses: '.$meses.' En Mora');
                    log::info('******************************************');
                }else{
                        if($ultima_fecha_pago<$fecha_inicio_mora)
                        {
                            $cantidad=ceil(carbon::parse($fecha_inicio_mora)->diffInDays(carbon::parse($fecha_final_mora)));
                            $meses=(($cantidad/365)*12);
                            log::info('Empresa: '.$dato->nombre.' ***SI es menor que la fecha de incio mora***'.' Meses: '.$cantidad.' En Mora');
                            log::info('******************************************');
                        }else{
                                $cantidad=0;
                                $meses=(($cantidad/365)*12);
                                log::info('Empresa: '.$dato->nombre.' ***Su último pago es mayor que el periodo seleccionado***'.' Meses: '.$cantidad.' Solvente');
                                log::info('******************************************');
                             }

                    }
                //** Calculos */
                //$cantidad=ceil(carbon::parse($fecha_final_mora)->diffInDays(carbon::parse($fecha_inicio_mora)));
                //$meses=(($cantidad/365)*12);
                //if($dato_tarifa===null){$meses_redondeado=0;}else{$meses_redondeado=round($meses,0);}
                if($dato_tarifa===null)
                {
                    $meses_redondeado=0;
                }
                    else
                        {   //$meses_redondeado=$meses;
                            $meses_redondeado=round($meses,0);
                        }
                $calculo_total_pago=$meses_redondeado*$tarifa;
                $calculo_total_mora=($calculo_total_mora+$calculo_total_pago);

                /** Formatenado variables numericas */
                $calculo_total_pago_formateado=number_format(( $calculo_total_pago), 2, '.', ',');
                $calculo_total_mora_formateado=number_format(( $calculo_total_mora), 2, '.', ',');
                $tarifa_formateado=number_format(($tarifa), 2, '.', ',');

                //** Modificando y creando nuevas variables */
                $dato->ultima_fecha_pago=Carbon::parse($ultima_fecha_pago)->format('d-m-Y');
                $dato->dato_contribuyente=$dato->contribuyente.$dato->apellido;
                $dato->meses=$meses_redondeado;
                $dato->tarifaE=$tarifa_formateado.' '.'/ '.$año;
                $dato->total_pago=$calculo_total_pago_formateado;
                $dato->total_moraE=$calculo_total_mora_formateado;

                $total_mora_final=$dato->total_moraE;
        }//** FIn Foreach mora_empresas */
            
            log::info('Total Mora: $'.$calculo_total_mora_formateado);
     }

    return [
        'success' => 1,
        'mora_empresas'=>$mora_empresas,
        'total_mora_final'=>$total_mora_final,
        ];

    }


//** Fin de reportes controller */
}
