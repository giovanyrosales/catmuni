<?php

namespace App\Http\Controllers\Backend\Buses;

use App\Http\Controllers\Backend\MatriculasDetalle\alert;
use App\Http\Controllers\Controller;
use App\Models\Buses;
use App\Models\Calificacion;
use App\Models\CalificacionMatriculas;
use App\Models\CobrosMatriculas;
use App\Models\LicenciaMatricula;
use App\Models\MatriculasDetalle;
use App\Models\Empresas;
use App\Models\Contribuyentes;
use App\Models\CobrosBus;
use App\Models\Interes;
use App\Models\Usuario;
use App\Models\MatriculasDetalleEspecifico;
use App\Models\BusesDetalleEspecifico;
use App\Models\CalificacionBus;
use App\Models\CalificacionBuses;
use App\Models\CierresReaperturasBuses;
use App\Models\CobrosBuses;
use App\Models\EstadoBuses;
use App\Models\TarifaBus;
use App\Models\TraspasosBuses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Environment\Console;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Facades\Bus;
use Ramsey\Uuid\Guid\Guid;

class BusesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

     //Agregar nuevo bus
    public function crearBus()
    {
         $idusuario = Auth::id();
         $infouser = Usuario::where('id', $idusuario)->first();
         $empresas = Empresas::ALL();
 
       return view('backend.admin.Buses.CrearBus', compact('empresas'));
 
    }

     //Agregar Rótulo
    public function nuevoBus(Request $request)
    {

        $regla = array(
    
            'nom_bus' => 'required',            
           
        );
    
        $validar = Validator::make($request->all(), $regla, 
       
    
        );
           
        if ($validar->fails()){
    
        return [
    
         'success'=> 0,
    
        'message' => $validar->errors()->first()
    
        ];
        }
    
               
                $dato = new Buses();
                $dato->id_empresa = $request->empresa;
                $dato->nom_bus = $request->nom_bus;
                $dato->fecha_inicio = $request->fecha_inicio;
                $dato->placa = $request->placa;
                $dato->ruta = $request->ruta;
                $dato->telefono = $request->telefono;
                $dato->id_estado_buses = $request->estado_buses;
            
 
                if($dato->save()){

                    return ['success' => 1];
                }else{
                    return ['success' => 2];
                }
                    
    }

    //Función Tabla Buses
     public function tablaBuses(Buses $lista){

             
        $lista=Buses::join('empresa','buses.id_empresa','=','empresa.id')
                     
                      
        ->select('buses.id AS id_bus','buses.nom_bus','buses.fecha_inicio','buses.placa','buses.ruta','buses.telefono',
        'empresa.nombre as empresas')
        ->get();
          
        return view('backend.admin.Buses.tabla.tablaListarBuses', compact('lista'));
    }
    //Termina función tabla Buses

    //Función Listar Buses
    public function listarBus()
    {
   
        $empresas = Empresas::All();
     
        return view('backend.admin.Buses.ListarBus', compact('empresas'));
    }
    //Termina función Listar Buses

    //Ver informacón del bus para actualizar
    public function informacionBus(Request $request)
    {
       
        $regla = array(
            'id' => 'required',
        
    );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if ($bus = Buses::where('id', $request->id)->first())
           {
          
            $empresas = Empresas::orderby('nombre')->get();
                
                return['success' => 1,
       
                'buses' => $bus,             
                'id_emp' => $bus->id_empresa,
                'empresa' => $empresas,

                 ];
           }
            else
            {
                return ['success' => 2];
                
            }
          
        
    }
    //Termina funcion para ver informacion del Bus

    //Función para editar buses
    public function editarBus(Request $request)
    {
         log::info($request->all());
            
         $regla = array(  
             'nom_bus' => 'required',
            
             
         );
 
         $validar = Validator::make($request->all(), $regla);
 
         if ($validar->fails()){ return ['success' => 0];} 
 
         if(Buses::where('id', $request->id)->first()){

            Buses::where('id', $request->id)->update([
     
                 'id_empresa' => $request->empresa,
                 'nom_bus' => $request->nom_bus,
                 'fecha_inicio' => $request->fecha_inicio,
                 'placa' => $request->placa,
                 'ruta'=> $request->ruta,                
                 'telefono' => $request->telefono
             
             ]);
     
             return ['success' => 1];
         }else{
             return ['success' => 2];
         }      
 
      
 
    }
    //Termina función para editar rótulos
 

    //Función vista detallada
    public function showBuses($id_bus)
    {
        $contribuyentes = Contribuyentes::All();
        $empresas = Empresas::ALL();
         
      
        $bus=Buses::join('empresa','buses.id_empresa','=','empresa.id')
                ->join('contribuyente', 'empresa.id_contribuyente', '=', 'contribuyente.id')
            

        ->select('buses.id AS id_bus','buses.nom_bus','buses.fecha_inicio','buses.placa','buses.ruta','buses.telefono',
        'empresa.nombre as empresas', 
        'contribuyente.nombre as contri' , 'contribuyente.apellido as ape')
        ->find($id_bus);

        $calificacionB = Buses::join('empresa','buses.id_empresa','=','empresa.id')
                                
        //Consulta para mostrar los buses que pertenecen a una sola empresa
                              
                ->select('buses.id AS id_bus','buses.nom_bus','buses.fecha_inicio','buses.placa','buses.ruta','buses.telefono',
                'empresa.nombre as empresas', )
                
                ->where('id_empresa', $bus->id_empresa)
                ->get();
        
         //Termina consulta para mostrar los buses que pertenecen a una sola empresa
         $calificacion = CalificacionBus::select('calificacion_bus.id', 'calificacion_bus.fecha_calificacion','calificacion_bus.estado_calificacion','calificacion_bus.id_empresa')
       
         ->where('id_buses', $id_bus)
         ->latest()
         ->first();

         if ($calificacion == null)
         {
           $detectorNull = 0;
         }
           else
           {
           $detectorNull = 1;
           }
        
           

        return view('backend.admin.Buses.showBus', compact('id_bus','bus','contribuyentes','empresas','calificacionB','calificacion','detectorNull',));

    }
    //Termina vista detallada

//Función para tabla de calificacion de buses
    public function tablaCalificacionB($id_bus)
    {
        $bus = Buses::where('id', $id_bus)->first();
     
        $calificacionB = Buses::join('empresa','buses.id_empresa','=','empresa.id')
                                
//Consulta para mostrar los buses que pertenecen a una sola empresa
                      
        ->select('buses.id AS id_bus','buses.id_empresa','buses.nom_bus','buses.fecha_inicio','buses.placa','buses.ruta','buses.telefono',
        'empresa.nombre as empresas', )
        
        ->where('id_empresa', $bus->id_empresa)
        ->get();

//Termina consulta para mostrar los buses que pertenecen a una sola empresa

            $tBus = TarifaBus::orderBy('id', 'ASC')->get();  

            foreach ($calificacionB as $dato)
            {
                $tarifa_mensual = 0;
                $fondoF = 0.05;
                $total1 = 0;

                foreach ($tBus as $tarifa)
                {
                    $tarifa_mensual = $tarifa->monto_tarifa;
                }

                $dato->tarifa = $tarifa_mensual;
                $total = round($tarifa_mensual + ($tarifa_mensual * $fondoF),2);
                $dato->total_pagar = $total;
                log::info($tarifa_mensual);
                $total1 = $total1 + $tarifa_mensual;
                log::info($total1);
            }

   

        return view('backend.admin.Buses.tabla.tabla_busC', compact('calificacionB','total','total1','bus','tBus','tarifa_mensual','dato','tarifa'));
         
    }
//Termica tabla de la calificación de buses

// GENERAR CALIFICACION DE BUSES
    public function calificacionBuses ($id_bus)
    {
       
       $empresa = Empresas::ALL();
       $bus = Buses::where('id', $id_bus)->first();
       
        $contribuyente = Contribuyentes::orderBy('id', 'ASC')->get();
      //  $empresa = Empresas::orderBy('id', 'ASC')->get();

        $bus=Buses::join('empresa','buses.id_empresa','=','empresa.id')
        ->join('contribuyente', 'empresa.id_contribuyente', '=', 'contribuyente.id')
  

        ->select('buses.id AS id_bus','buses.id_empresa','buses.nom_bus','buses.fecha_inicio','buses.placa','buses.ruta','buses.telefono',
        'empresa.nombre as empresas', 
        'contribuyente.nombre as contri' , 'contribuyente.apellido as ape')
        ->find($id_bus);
              
        $calificacionB = Buses::join('empresa','buses.id_empresa','=','empresa.id')
                                
        //Consulta para mostrar los buses que pertenecen a una sola empresa
                              
            ->select('buses.id AS id_bus','buses.id_empresa','buses.nom_bus','buses.fecha_inicio','buses.placa','buses.ruta','buses.telefono',
            'empresa.nombre as empresas', )
            ->where('id_empresa', $bus->id_empresa)
            ->get();
        
        //Termina consulta para mostrar los buses que pertenecen a una sola empresa        
        $contador=0;
        $tBus = TarifaBus::orderBy('id', 'ASC')->get();  

            foreach ($calificacionB as $dato)
            {
                $tarifa_mensual = 0;
                $fondoF = 0.05;
                $total = 0;
                $total1 = 0;
                

                foreach ($tBus as $tarifa)
                {
                    $tarifa_mensual = $tarifa->monto_tarifa;
                }

                $contador=$contador+1;
               // log::info('contador igual a:'.$contador);
                $dato->tarifa = $tarifa_mensual;                
                $dato->total_pagar = $total;
                $totalTarifa = $contador * $tarifa_mensual;
                $totalTarifaA = round(($totalTarifa * 12),2);
                $totalTarifaI = round($totalTarifa + ($totalTarifa * $fondoF),2);
                $totalTarifaImA = $totalTarifaI * 12;


            }
      
        return view('backend.admin.Buses.CalificacionBus', compact('id_bus','contador','bus','totalTarifaImA','totalTarifaI','totalTarifaA','totalTarifa','calificacionB','contribuyente','empresa'));
    }
// TERMINA GENERAR CALIFICACION DE BUSES


// GUARDAR CALIFICACION DE BUSES
    public function guardarCalificacionB(Request $request)
    {    
        
        $fecha_calificacion = $request->fechacalificar;
        $estado_calificaion =  $request->estado_calificacion;
        $id_bus = $request->id_bus;
        $id_empresa = $request->id_empresa;
      
        log::info('Fecha calificacion ' . $fecha_calificacion);
        log::info('Estado calificacion ' . $estado_calificaion);
        log::info('ID bus ' . $id_bus);
        log::info('ID empresa ' . $id_empresa);

              
        $tBus = TarifaBus::orderBy('id', 'ASC')->get();
      
     //   log::info($tBus);
             
      
            $calificacionB = Buses::join('empresa','buses.id_empresa','=','empresa.id')
                                
            //Consulta para mostrar los buses que pertenecen a una sola empresa
                                  
                ->select('buses.id AS id_bus','buses.id_empresa','buses.nom_bus','buses.fecha_inicio','buses.placa','buses.ruta','buses.telefono',
                'empresa.nombre as empresas', )
                ->where('id_empresa', $id_empresa)
                ->get();
            
            
                    log::info($calificacionB);
                  $contador=0;

            //Calculo de la calificación de buses
            foreach ($calificacionB as $dato)
            {
                $tarifa_mensual = 0;
                $fondoF = 0.05;
                $total = 0;
                $total1 = 0;
                

                foreach ($tBus as $tarifa)
                {
                    $tarifa_mensual = $tarifa->monto_tarifa;
                }
               
          
                $dato->tarifa = $tarifa_mensual;   
                $dato->tarifaT = $tarifa_mensual + ($tarifa_mensual * $fondoF);
              

                log::info('tarifa mensual ' . $tarifa_mensual);
                log::info('tarifa total ' . $dato->tarifaT);
                           
            
                $contador=$contador+1;
                log::info('contador ' . $contador);

                    $dt = new CalificacionBus();
                    $dt->id_buses = $dato->id_bus;
                    $dt->id_empresa = $request->id_empresa;
                    $dt->fecha_calificacion = $request->fechacalificar;
                    $dt->estado_calificacion = $request->estado_calificacion;
                    $dt->tarifa_mensual = $dato->tarifa;
                    $dt->tarifa_total = $dato->tarifaT;
                    $dt->save();
                    
        }      

        return ['success' => 1];
                
    }
// TERMINA GUARDAR CALIFICACION DE BUSES

    public function cobrosBus($id)
    {
        $tasasDeInteres = Interes::select('monto_interes')
        ->orderby('id','desc')
        ->get();
        
        $date=Carbon::now()->toDateString();

        $bus = Buses::where('id', $id)->first();
        $empresa = Empresas::where('id', $id)->first();

    //Consulta para mostrar los buses que pertenecen a una sola empresa    

        $calificacionB = Buses::join('empresa','buses.id_empresa','=','empresa.id')                    
                                             
            ->select('buses.id AS id_bus','buses.nom_bus','buses.fecha_inicio','buses.placa','buses.ruta','buses.telefono',
            'empresa.nombre as empresas', )
            ->where('id_empresa', $bus->id_empresa)
            ->get();

    //Termina consulta para mostrar los buses que pertenecen a una sola empresa

         $bus=Buses::join('empresa','buses.id_empresa','=','empresa.id')
        ->join('contribuyente', 'empresa.id_contribuyente', '=', 'contribuyente.id')
  

        ->select('buses.id AS id_bus','buses.id_empresa','buses.nom_bus','buses.fecha_inicio','buses.placa','buses.ruta','buses.telefono',
        'empresa.nombre as empresas', 
        'contribuyente.nombre as contri' , 'contribuyente.apellido as ape')
        ->find($id);
              

        $ultimo_cobro = CobrosBus::latest()
        ->where('id_buses', "=", "$id")
        ->first();


        $calificacion = CalificacionBus::select('calificacion_bus.id', 'calificacion_bus.fecha_calificacion','calificacion_bus.estado_calificacion','calificacion_bus.id_empresa')
       
        ->latest()
        ->first();

        if ($calificacion == null)
        { 
            $detectorNull=0;
            if ($ultimo_cobro == null)
            {
                $detectorNull=0;
                $detectorCobro=0;
                return view('backend.admin.Buses.Cobros.CobroBus', compact('detectorNull','detectorCobro'));
            }
        } else
        {  
            $detectorNull=1;
            if ($ultimo_cobro == null)
            {
             $detectorNull=0;
             $detectorCobro=0;
             return view('backend.admin.Buses.Cobros.CobroBus', compact('bus','calificacion','date','detectorNull','detectorCobro','ultimo_cobro','detectorCobro','tasasDeInteres',));
            } else
            {
                $detectorNull=1;
                $detectorCobro=1;
            return view('backend.admin.Buses.Cobros.CobroBus', compact('bus','date','calificacion','ultimo_cobro','calificacionB','tasasDeInteres','id','empresa','detectorNull','detectorCobro'));
            }
        }
            
     
    }

    public function calcularCobrosB(Request $request)
    {
  
        $id_empresa = $request->id_empresa;
        $id_buses = $request->id_buses; //* ID del bus.
                   
        log::info($request->all());
        $DetectorEnero=Carbon::parse($request->ultimo_cobro)->format('M');
        $AñoVariable=Carbon::parse($request->ultimo_cobro)->format('Y');
       
        $MesNumero=Carbon::createFromDate($request->ultimo_cobro)->format('d');
        //log::info($MesNumero);

        if($MesNumero<='15')
        {
            $f1=Carbon::parse($request->ultimo_cobro)->format('Y-m-01');
            $f1=Carbon::parse($f1);
            $InicioPeriodo=Carbon::createFromDate($f1);
            $InicioPeriodo= $InicioPeriodo->format('Y-m-d');
            //log::info('inicio de mes');
        }
        else
            {
            $f1=Carbon::parse($request->ultimo_cobro)->addMonthsNoOverflow(1)->day(1);
            $InicioPeriodo=Carbon::parse($request->ultimo_cobro)->addMonthsNoOverflow(1)->day(1)->format('Y-m-d');
            // log::info('fin de mes ');
            }
        
        $f2=Carbon::parse($request->fechaPagara);
        $f3=Carbon::parse($request->fecha_interesMoratorio);
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
        $PagoUltimoDiaMes=Carbon::parse($request->fechaPagara)->endOfMonth()->format('Y-m-d');
        //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */

        //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
        $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
        $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(30)->format('Y-m-d');

        $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
        Log::info('Inicio moratorio inicia aqui');
        Log::info($FechaDeInicioMoratorio);
        $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
        //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */

    
      
        //** Inicia - Para obtener la tasa de interes más reciente */
        $Tasainteres=Interes::latest()
        ->pluck('monto_interes')
            ->first();
        //** Finaliza - Para obtener la tasa de interes más reciente */

        $calificacion = CalificacionBus::select('calificacion_bus.id', 'calificacion_bus.fecha_calificacion','calificacion_bus.estado_calificacion','calificacion_bus.id_empresa')
       ->where('id_buses', "$id_buses")
        ->latest()
        ->first();

         //Termina consulta para mostrar los rótulos que pertenecen a una sola empresa
     
          

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

           
                $tarifas=CalificacionBus::select('tarifa_mensual')
                ->where('id_empresa',$id_empresa)
                 ->get();

                $tarifa_total=0;
                 foreach($tarifas as $dt)
                 {
                    $tarifa=$dt->tarifa_mensual;
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
                    Log::info($tarifas);
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
               

                if ($request->cobrar=='1')
                {  

                $cobro = new CobrosBus();
                $cobro->id_buses = $request->id_buses;
                $cobro->id_empresa = $request->id_empresa;              
                $cobro->id_usuario = '1';
                $cobro->cantidad_meses_cobro = $Cantidad_MesesTotal;
                $cobro->impuesto_mora = $impuestos_mora;
                $cobro->impuesto = $impuesto_año_actual;
                $cobro->intereses_moratorios = $InteresTotal;
                $cobro->fondo_fiestasP = $fondoFPValor;
                $cobro->pago_total = $totalPagoValor;
                $cobro->fecha_cobro = $request->fecha_interesMoratorio;
                $cobro->periodo_cobro_inicio = $InicioPeriodo;
                $cobro->periodo_cobro_fin =$PagoUltimoDiaMes;

                $cobro->save();
        
                return ['success' => 2];
                

                }else{
            
                return ['success' => 1,
                        'InteresTotalDollar'=>$InteresTotalDollar,
                        'impuestoTotal'=>$impuestoTotal,
                        'impuestos_mora_Dollar'=>$impuestos_mora_Dollar,
                        'impuesto_año_actual_Dollar'=>$impuesto_año_actual_Dollar,
                        'Cantidad_MesesTotal'=>$Cantidad_MesesTotal,           
                        'tarifas'=>$tarifas,
                        'fondoFP'=>$fondoFP,
                        'totalPago'=>$totalPago,
                        'DiasinteresMoratorio'=>$DiasinteresMoratorio,                
                        'interes'=>$Tasainteres,
                        'InicioPeriodo'=>$InicioPeriodo,
                        'PagoUltimoDiaMes'=>$PagoUltimoDiaMes,
                        'FechaDeInicioMoratorio'=> $FechaDeInicioMoratorio,
             
                        ];
                    }
            }else
            {
                return ['success' => 0];
            }

    }

//Realizar traspaso
    public function infoTraspasoB(Request $request)
    {
        $empresa = Empresas::ALL();

            $regla = array(
                'id' => 'required',
            );

            $validar = Validator::make($request->all(), $regla);

            if ($validar->fails()){ return ['success' => 0];}

            if($lista = Buses::where('id', $request->id)->first()){
                
                $empresas = Empresas::orderBy('nombre')->get();
                $estado_buses = EstadoBuses::orderBy('estado')->get();
                return ['success' => 1,

                'id_emp' => $lista->id_empresa,
                'idesta' => $lista->id_estado_buses,
                'empresa' => $empresas,
                'estado_buses' => $estado_buses,
                
            ];
    }
        else
        {
            return ['success' => 2];
        }
    }
//Realizar traspaso finaliza

    public function cierres_traspasosB($id_bus){
            
        $idusuario = Auth::id();
        $infouser = Usuario::where('id', $idusuario)->first();
        $estado_buses = EstadoBuses::All();
        $ConsultaEmpresa = Empresas::All();
        $empresas = Empresas::ALL();
        $contribuyente = Contribuyentes::ALL();

      
        $bus=Buses::join('empresa','buses.id_empresa','=','empresa.id')
        ->join('estado_buses', 'buses.id_estado_buses', '=','estado_buses.id')
           

        ->select('buses.id AS id_bus','buses.nom_bus','buses.fecha_inicio','buses.placa','buses.ruta','buses.telefono',
        'empresa.id','empresa.nombre',   
        'estado_buses.estado')
        ->where('buses.id',$id_bus)
        ->first();

       
        return view('backend.admin.Buses.CierresTraspasos.Cierre_TraspasoB',
                compact(
                        'estado_buses',     
                        'bus',
                        'ConsultaEmpresa',
                        'contribuyente',
                        'empresas',
                        'id_bus'
                       
                    ));
    }

    public function nuevoEstadoBus(Request $request)
    {
        log::info($request->all());

            $id_bus = $request->id_bus;
            $estado_buses = $request->estado_buses;

            if($estado_buses == 1)
            {
                $Tipo_operacion='Cierre';
            }else
            {
                $Tipo_operacion='Reapertura';
            }

            
            $bus=Buses::join('empresa','buses.id_empresa','=','empresa.id')           
            ->join('estado_buses', 'buses.id_estado_buses', '=','estado_buses.id')
      
    
            ->select('buses.id','buses.nom_bus','buses.fecha_inicio','buses.placa','buses.ruta','buses.telefono',
            'empresa.nombre as empresas',            
            'estado_buses.estado')
            ->find($id_bus);
              

            $regla = array(  
                'estado_buses' => 'required',
                'cierre_apartirdeldia' => 'required',
            );
          
            $validar = Validator::make($request->all(), $regla,
          
            );
            DB::beginTransaction();

            try {

            if ($validar->fails()){ 
                return ['success' => 0,
                'message' => $validar->errors()->first()
            ];
            }

          
            if(Buses::where('id', $request->id_bus)->first()){
              if($estado_buses != $bus->id_estado_buses){
                //** Guardar registro historico en tabla traspasos */
                $cierre = new CierresReaperturasBuses();               
                $cierre->id_buses = $request->id_bus;
                $cierre->fecha_a_partir_de = $request->cierre_apartirdeldia;
                $cierre->tipo_operacion = $Tipo_operacion;
                $cierre->save();
                //** FIN- Guardar registro historico en tabla traspasos */

                Buses::where('id', $request->id_bus)->update([
         
                    'id_estado_buses' => $request->estado_buses,
                     
                ]);
                DB::commit();
                    return ['success' => 1];
              
                }else{ 
                        return ['success' => 3];
                     }
            }
        }            
    
                catch(\Throwable $e){
                    DB::rollback();   
                return ['success' => 2];
            }
        
    }

    public function nuevoTraspasoBus(Request $request)
    {

        log::info($request->all());

        $id_bus = $request->id_bus;
        $id_empresa = $request->empresa;
     
            $bus=Buses::join('empresa','buses.id_empresa','=','empresa.id')
            ->join('contribuyente', 'empresa.id_contribuyente', '=', 'contribuyente.id')
            ->join('estado_buses','buses.id_estado_buses', '=', 'estado_buses.id')
      
    
            ->select('buses.id AS id_bus','buses.id_empresa','buses.nom_bus','buses.fecha_inicio','buses.placa','buses.ruta','buses.telefono',
            'empresa.nombre as empresas', 
            'contribuyente.nombre as contri' , 'contribuyente.apellido as ape',
            'estado_buses.estado')
            ->find($id_bus);
                  


            $datos_empresa = Empresas::select('nombre')
            ->where('id', $id_empresa)
            ->first();
     

            $regla = array(  
                'id' => 'required',
                'empresa' => 'required',
            );
          
            $validar = Validator::make($request->all(), $regla,
          
            );

            if ($validar->fails()){ 
                return ['success' => 0,
                'message' => $validar->errors()->first()
            ];
            }
            if(Buses::where('id', $request->id_bus)->first()){
                //** Guardar registro historio en tabla traspasos */
            
            if($id_empresa != $bus->id_empresa){
                $traspaso = new TraspasosBuses();
                $traspaso->id_buses = $id_bus;            
                $traspaso->propietario_anterior = $bus->empresas;
                $traspaso->propietario_nuevo =  $datos_empresa->nombre;
                $traspaso->fecha_a_partir_de = $request->Apartirdeldia;
                $traspaso->save();
                //** FIN- Guardar registro historio en tabla traspasos */
                Buses::where('id', $request->id_bus)->update([
         
                     'id_empresa' => $request->empresa,
                    ]);

                    return ['success' => 1];

                 }else{ 
                    return ['success' => 3];
                      }

                }else{
                    return ['success' => 2];
                }
    }

    
    public function tablaCierresBus($id)
    {

        $historico_cierres=CierresReaperturasBuses::orderBy('id', 'desc')
        ->where('id_buses',$id)
        ->get();

           
        return view('backend.admin.Empresas.CierresTraspasos.tablas.tabla_cierres', compact('historico_cierres'));
    }




}