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
         $contribuyentes = Contribuyentes::ALL();
 
       return view('backend.admin.Buses.CrearBus', compact('empresas','contribuyentes'));
 
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
                $dato->id_contribuyente = $request->contribuyente;
                $dato->id_empresa = $request->empresa;
                $dato->nFicha = $request->nFicha;
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

    //Función para llenar el select Actividad Especifica
    public function buscarEmpresaBus(Request $request)
    {

        $empresa = Empresas::
        where('id_contribuyente',$request->id_select)
        ->orderBy('nombre', 'ASC')
        ->get();

        return ['success' => 1,
        'empresa' => $empresa,
         
        ];

    }
    //Terminar llenar select

    //Función Tabla Buses
    public function tablaBuses(Buses $lista)
    {
                                                                                                                                                                                              
        $lista = Buses::orderBy('nom_bus')->get();

        foreach($lista as $dato) 
        {
            $nom_apellido = ' ';
            $nom_empresa = ' ';

            if ($info = Contribuyentes::where ('id', $dato->id_contribuyente)->first())
            {
               $nom_apellido = $info->nombre . ' ' . $info->apellido;{}
            }

            if ($info = Empresas::where ('id',$dato->id_empresa)->first())
            {
                $nom_empresa = $info->nombre;
            }

            $dato->cont = $nom_apellido;
            $dato->empr = $nom_empresa;

        }
       
        return view('backend.admin.Buses.tabla.tablaListarBuses', compact('lista'));
    }
    //Termina función tabla Buses

    //Función Listar Buses
    public function listarBus()
    {
   
        $empresas = Empresas::All();
        $contribuyentes = Contribuyentes::ALL();
     
        return view('backend.admin.Buses.ListarBus', compact('empresas','contribuyentes'));
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
            $contribuyentes = Contribuyentes::orderby('nombre')->get();
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
       

        $lista = Buses::where('id', $id_bus)->first();
 
        
            $contri = ' ';
            $emp = ' ';

            if ($contribuyente = Contribuyentes::where ('id', $lista->id_contribuyente)->first())
            {
                $contri = $contribuyente->nombre . ' ' . $contribuyente->apellido;
            }
 
            if ($empresa = Empresas::where ('id',$lista->id_empresa)->first())
            {
                $emp= $empresa->nombre;
            }

            if ($estado = EstadoBuses::where ('id', $lista->id_estado_buses)->first())
            {
                $estado = $estado->estado;
            }

       
        //Termina consulta para mostrar los buses que pertenecen a una sola empresa
        $calificacion = CalificacionBus::
        select('calificacion_bus.id', 'calificacion_bus.fecha_calificacion','calificacion_bus.estado_calificacion','calificacion_bus.id_empresa')
       
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
        

        return view('backend.admin.Buses.showBus', compact('lista','estado','contribuyentes','empresas','calificacion','detectorNull','emp','contri'));


    }
//Termina vista detallada

//Función para tabla de calificacion de buses
    public function tablaCalificacionB($id_bus)
    {
        $bus = Buses::where('id', $id_bus)->get();

        $buses = Buses::where('id', $id_bus)->first();

        if ($contribuyente = Contribuyentes::where ('id', $buses->id_contribuyente)->first())
        {
            $contri = $contribuyente->nombre . ' ' . $contribuyente->apellido;
        }

   
            $tBus = TarifaBus::orderBy('id', 'ASC')->get();  

            foreach ($bus as $dato)
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

   

        return view('backend.admin.Buses.tabla.tabla_busC', compact('total','contri','buses','total1','bus','tBus','tarifa_mensual','dato','tarifa'));
         
    }
//Termica tabla de la calificación de buses

// GENERAR CALIFICACION DE BUSES
    public function calificacionBuses ($id)
    {
       
        $empresa = Empresas::ALL();
        $bus = Buses::where('id', $id)->get();
       
        $contribuyente = Contribuyentes::orderBy('id', 'ASC')->get();
      //  $empresa = Empresas::orderBy('id', 'ASC')->get();

        $lista = Buses::where('id', $id)->first();
    
       
            $contri = ' ';
            $emp = ' ';

            if ($contribuyente = Contribuyentes::where ('id', $lista->id_contribuyente)->first())
            {
               $contri = $contribuyente->nombre . ' ' . $contribuyente->apellido;
            }
            
            if ($empresa = Empresas::where ('id',$lista->id_empresa)->first())
            {
                $emp= $empresa->nombre;
            }

            if ($estado = EstadoBuses::where ('id', $lista->id_estado_buses)->first())
            {
                $estado = $estado->estado;
            }

        
        //Termina consulta para mostrar los buses que pertenecen a una sola empresa        
        $contador=0;
        $tBus = TarifaBus::orderBy('id', 'ASC')->get();  

            foreach ($bus as $dato)
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
      
        return view('backend.admin.Buses.CalificacionBus', compact('id','lista','emp','contri','contador','bus','totalTarifaImA','totalTarifaI','totalTarifaA','totalTarifa','contribuyente','empresa'));
    }
// TERMINA GENERAR CALIFICACION DE BUSES


// GUARDAR CALIFICACION DE BUSES
    public function guardarCalificacionB(Request $request)
    {    
        
        $fecha_calificacion = $request->fechacalificar;
        $estado_calificaion =  $request->estado_calificacion;
        $id_bus = $request->id;
        $id_empresa = $request->id_empresa;
        $id_contribuyente = $request->id_contribuyente;
      
        log::info('Fecha calificacion ' . $fecha_calificacion);
        log::info('Estado calificacion ' . $estado_calificaion);
        log::info('ID bus ' . $id_bus);
        log::info('ID empresa ' . $id_empresa);
        log::info('ID contribuyente ' . $id_contribuyente);

              
        $tBus = TarifaBus::orderBy('id', 'ASC')->get();
      
     //   log::info($tBus);
             
      
    $calificacionB = Buses::where('id', $id_bus)->get();
            
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
                    $dt->id_buses = $dato->id;
                    $dt->id_empresa = $request->id_empresa;
                    $dt->id_contribuyente = $request->id_contribuyente;
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
        }else
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
            
            if($lista = Buses::where('id', $request->id)->first())
            {

                $empresa = Empresas::orderBy('nombre')->get();
                $estado_buses = EstadoBuses::orderBy('estado')->get();
                $contribuyente = Contribuyentes::orderBy('nombre')->get();
             
                return ['success' => 1,

                'id_emp' => $lista->id_empresa,
                'idesta' => $lista->id_estado_buses,
                'id_contri' => $lista->id_contribuyente,
                'empresa' => $empresa,
                'estado_buses' => $estado_buses,                
                'contribuyente' => $contribuyente,
               
                
                    ];
            }
            else
            {
                return ['success' => 2];
            }
    }
//Realizar traspaso finaliza

    public function cierres_traspasosB($id)
    {
                   
        $idusuario = Auth::id();
        $infouser = Usuario::where('id', $idusuario)->first();
        $estado_buses = EstadoBuses::All();
        $ConsultaEmpresa = Empresas::All();
        $empresas = Empresas::ALL();
        $contribuyentes = Contribuyentes::ALL();

      
        $lista = Buses::where('id', $id)->first();

    
            $contri = ' ';
            $emp = ' ';
            $estado = ' ';

            if ($contribuyente = Contribuyentes::where ('id', $lista->id_contribuyente)->first())
            {
               $contri = $contribuyente->nombre . ' ' . $contribuyente->apellido;
               $id = $contribuyente->id;
            }
            
            if ($empresa = Empresas::where ('id',$lista->id_empresa)->first())
            {
                $emp= $empresa->nomb8re;
            }

            if ($estado = EstadoBuses::where ('id', $lista->id_estado_buses)->first())
            {
                $estado = $estado->estado;
            }

    
       
        return view('backend.admin.Buses.CierresTraspasos.Cierre_TraspasoB',
                compact(
                        'estado_buses',                    
                        'ConsultaEmpresa',
                        'contribuyentes',
                        'empresas',
                        'id',
                        'lista',
                        'contri',
                        'emp',
                        'estado'
                       
                    ));
    }

    public function nuevoEstadoBus(Request $request)
    {
        log::info($request->all());

            $id = $request->id;
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
            ->find($id);
              

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

        $id = $request->id;
        $id_empresa = $request->empresa;
        $id_contribuyente = $request->contribuyente;
        
        $lista = Buses::orderBy('nom_bus')->get();

            foreach($lista as $dato) 
            {
                $nom_apellido = ' ';
                $nom_empresa = ' ';

                if ($info = Contribuyentes::where ('id', $dato->id_contribuyente)->first())
                {
                $nom_apellido = $info->nombre . ' ' . $info->apellido;{}
                }

                if ($info = Empresas::where ('id',$dato->id_empresa)->first())
                {
                    $nom_empresa = $info->nombre;
                }

                $dato->cont = $nom_apellido;
                $dato->empr = $nom_empresa;

            }
       
            $datos_empresa = Empresas::select('nombre')
            ->where('id', $id_empresa)
            ->first();

            $datos_contribuyente = Contribuyentes::select('nombre','apellido')
            ->where('id', $id_contribuyente)
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

            if(Buses::where('id', $request->id)->first()){
                //** Guardar registro historio en tabla traspasos */
            
            if($id_contribuyente != $lista->id_contribuyente){
                $traspaso = new TraspasosBuses();              
                $traspaso->id_buses = $id;            
                $traspaso->contribuyente_anterior = $lista->cont;
                $traspaso->contribuyente_nuevo = $datos_contribuyente->nombre;
                $traspaso->empresa_anterior = $lista->empr;
                $traspaso->empresa_nueva =  $datos_empresa->nombre;
                $traspaso->fecha_a_partir_de = $request->Apartirdeldia;
                $traspaso->save();
                //** FIN- Guardar registro historio en tabla traspasos */
                Buses::where('id', $request->id)->update([
         
                    'id_contribuyente' => $request->contribuyente,
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

    public function VerHistorialCobros_Buses($id)
    {

        $ListaCobrosBuses = CobrosBus::where('id_buses', $id)
        ->get();

      return view('backend.admin.Buses.Cobros.tablas.tabla_historico_Cobrosbus', compact('ListaCobrosBuses'));
    }

     //Función para llenar el select Actividad Especifica
    public function buscarEmpresaBusTraspaso(Request $request)
    {
 
          $empresa = Empresas::
          where('id_contribuyente',$request->id_select)
          ->orderBy('nombre', 'ASC')
          ->get();
 
          return ['success' => 1,
          'empresa' => $empresa,
          
          ];
 
     }
     //Terminar llenar select
 





}