<?php

namespace App\Http\Controllers\Backend\BusesDetalle;

use App\Http\Controllers\Backend\MatriculasDetalle\alert;
use App\Http\Controllers\Controller;
use App\Models\alertas_detalle_buses;
use App\Models\BusesDetalle;
use App\Models\Calificacion;
use App\Models\CalificacionMatriculas;
use App\Models\CobrosMatriculas;
use App\Models\LicenciaMatricula;
use App\Models\MatriculasDetalle;
use App\Models\Empresas;
use App\Models\Contribuyentes;
use App\Models\TraspasoBuses;
use App\Models\CobrosBuses;
use App\Models\EstadoBuses;
use App\Models\Interes;
use App\Models\Usuario;
use App\Models\MatriculasDetalleEspecifico;
use App\Models\BusesDetalleEspecifico;
use App\Models\CalificacionBuses;
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

class BusesDetalleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        $contribuyentes = Contribuyentes::ALL();

        $bus = BusesDetalle::orderBy('id')->get();

        $buses = BusesDetalle::join('contribuyente','buses_detalle.id_contribuyente','contribuyente.id')
        ->join('estado_buses','buses_detalle.id_estado_buses','buses_detalle.id')

        ->select('buses_detalle.id', 'buses_detalle.fecha_apertura','buses_detalle.nFicha',
        'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar','buses_detalle.estado_especificacion',
        'buses_detalle.nom_empresa','buses_detalle.dir_empresa','buses_detalle.nit_empresa',
        'buses_detalle.tel_empresa','buses_detalle.email_empresa','buses_detalle.r_comerciante',
        'contribuyente.id','contribuyente.nombre','contribuyente.nombre',
        'estado_buses.estado')

        ->get();
                
             
      
        return view('backend.admin.Buses.CrearBuses', compact('contribuyentes','bus','buses'));
    }

    //Función para llenar el select empresa
    public function buscarEmpresaBuses(Request $request)
    {
  
        $empresa = Empresas::
        where('id_contribuyente', $request->id_select)
        ->orderBy('nombre', 'ASC')
        ->get();
  
        return ['success' => 1,
           'empresa' => $empresa,
           
        ];
  
    }
    //Terminar llenar select

    public function tablaBuses(BusesDetalle $buses)
    {
      
        $buses = BusesDetalle::join('contribuyente','buses_detalle.id_contribuyente','=','contribuyente.id')
        ->join('estado_buses','buses_detalle.id_estado_buses','=','estado_buses.id')

        ->select('buses_detalle.id', 'buses_detalle.fecha_apertura','buses_detalle.nFicha',
        'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar','buses_detalle.estado_especificacion',
        'buses_detalle.nom_empresa','buses_detalle.dir_empresa','buses_detalle.nit_empresa',
        'buses_detalle.tel_empresa','buses_detalle.email_empresa','buses_detalle.r_comerciante',

        'contribuyente.nombre as contribuyente','contribuyente.apellido',
        'estado_buses.estado')

        ->get();
         
           
        return view('backend.admin.Buses.tabla.tabla_buses', compact('buses'));
    }

    public function listarBuses()
    {
   
        $contribuyentes = Contribuyentes::ALL();
     
        return view('backend.admin.Buses.ListarBuses', compact('contribuyentes'));
    }

    public function nuevoBus(Request $request)
    {
     
        $regla = array(  
            'contribuyente' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);
    
        if ($validar->fails()){ return ['success' => 0];}

     
        $bus = new BusesDetalle();       
        $bus->id_contribuyente = $request->contribuyente;
            
        $bus->fecha_apertura = $request->fecha_apertura; 
        $bus->nFicha = $request->nFicha; 
        $bus->cantidad = $request->cantidad;
        $bus->tarifa = $request->tarifa;
        $bus->monto_pagar = $request->monto_pagar;
        $bus->id_estado_buses = $request->estado_buses;
        $bus->nom_empresa = $request->nom_empresa;
        $bus->dir_empresa = $request->dir_empresa;
        $bus->nit_empresa = $request->nit_empresa;
        $bus->tel_empresa = $request->tel_empresa;
        $bus->email_empresa = $request->email_empresa;
        $bus->r_comerciante = $request->r_comerciante;
        
        
        if($bus->save())
        {
            return ['success' => 1];
        }
        
    
    }

    public function especificarBuses(Request $request)
    {

        log::info($request->all());
        $id_buses_detalle=$request->id_buses_detalle;
    
        $CantidadSeleccionada = BusesDetalle::where('id',$id_buses_detalle)->first();

        $contri = ' ';
        $emp = ' ';

        if($contribuyente = Contribuyentes::where('id', $CantidadSeleccionada->id_contribuyente)->first())
        {
            $contri = $contribuyente->nombre . ' ' . $contribuyente->apellido;
        }
        
        if($empresa = Empresas::where('id', $CantidadSeleccionada->id_empresa)->first())
        {	
            $emp = $empresa->nombre;
        }
      
    
    
        return  [
                    'success' => 1,
                    'cantidad' =>$CantidadSeleccionada->cantidad,
                    'id_buses_detalle' =>$request->id_buses_detalle,
                
                ];
    }

    public function agregar_buses_detalle_especifico(Request $request)
    {
        log::info($request->all());
        $especificada="especificada";
    
        $rules = array(
            'id_buses_detalle' => 'required',
        );
    
        $validator = Validator::make($request->all(), $rules);
    
        if ( $validator->fails()){
            return ['success' => 0];
        }
    
        
        if($request->placa != null) {
          
            for ($i = 0; $i < count($request->placa); $i++)  
            {
    
                $Bd = new BusesDetalleEspecifico();
                $Bd->id_buses_detalle =$request->id_buses_detalle;               
                $Bd->placa =$request->placa[$i];
                $Bd->nombre =$request->nombre[$i];
                $Bd->ruta=$request->ruta[$i];
                $Bd->telefono = $request->telefono[$i];
                $Bd->save();
            }

            BusesDetalle::where('id', $request->id_buses_detalle)
            ->update([
                        'estado_especificacion' =>$especificada,               
                    ]);
                    
          
            return ['success' => 1];
        }
            
    }

    public function informacionBus(Request $request)
    {
        log::info($request->all());

           $regla = array(
            'id' => 'required',
        );

          $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}
        

        if($lista = BusesDetalle::where('id', $request->id)->first()){
            $contribuyentes = Contribuyentes::orderby('nombre')->get();
            $empresa = Empresas::orderby('nombre')->get();               
        
        
            $signoD='$';
        $listado=db::table('buses_detalle_especifico')

        ->join('buses_detalle', 'buses_detalle.id', '=', 'buses_detalle_especifico.id_buses_detalle')
            
        ->select('buses_detalle_especifico.id','buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre',
        'buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
                'buses_detalle.cantidad','buses_detalle.monto_pagar')

        ->where('buses_detalle_especifico.id_buses_detalle', $request->id)     
        ->get();
        
        return ['success' => 1,
                'buses_detalle' => $lista,
                'empresa' =>$empresa,
                'id_empresa' => $lista->id_empresa,
                'id_contri' => $lista->id_contribuyente,
                'contribuyente' => $contribuyentes,
                'cantidad' => $lista->cantidad,
                'montoDolar'=> $lista->tarifa,
                'Pago_mensualDolar'=> $lista->monto_pagar,
                //'empresa'=>$empresa,
                'listado'=> $listado,
                ];
            }
            return ['success' => 2];
    
    }

    //*** Inicia editar bus y específico.****//
    public function editarBus(Request $request)
    {
        

        log::info($request->all());
       
        $regla = array(

           'id_editar' => 'required',
       
               
        );

       $validar = Validator::make($request->all(), $regla);
       if ($validar->fails()){ return ['success' => 0];} 
       
    DB::beginTransaction();
    try {
        
        //Actualizar registro bus detalle
       if(BusesDetalle::where('id', $request->id_editar)->first())
                {
                    
                         if($request->hayregistro == 1){
                           
                            //agregar id a pila
                            $pila = array();
                            for ($i = 0; $i < count($request->idarray); $i++) 
                                { 
                                    // Los id que sean 0, seran nuevos registros
                                    if($request->idarray[$i] != 0) 
                                    {
                                        array_push($pila, $request->idarray[$i]);
                                    }

                                }
                   
                                // borrar todos los registros
                                // primero obtener solo la lista de requisicon obtenido de la fila
                                // y no quiero que borre los que si vamos a actualizar con los ID
                                BusesDetalleEspecifico::where('id_buses_detalle', $request->id_buses_detalle_editar)
                                    ->whereNotIn('id', $pila)
                                    ->delete();
                              
                                // actualizar registros
                                for ($i = 0; $i < count($request->idarray); $i++) {
                                    if($request->idarray[$i] != 0){
                                        BusesDetalleEspecifico::where('id', $request->idarray[$i])->update([
                                            'placa' => $request->placa_editar[$i],
                                            'nombre' => $request->nombre_editar[$i],
                                            'ruta' => $request->ruta_editar[$i],
                                            'telefono' => $request->telefono_editar[$i]
                                        ]);
                                 
                                    }
                                }
                
                                
                                // hoy registrar los nuevos registros
                                for ($i = 0; $i < count($request->idarray); $i++) {
                                    if($request->idarray[$i] == 0){
                                        $bDetalle = new BusesDetalleEspecifico();
                                        $bDetalle->id_buses_detalle = $request->id_buses_detalle_editar;
                                        $bDetalle->placa = $request->placa_editar[$i];
                                        $bDetalle->nombre = $request->nombre_editar[$i];
                                        $bDetalle->ruta = $request->ruta_editar[$i];
                                        $bDetalle->telefono = $request->telefono_editar[$i];
                                        $bDetalle->save();
                                    }
                                }
                            
                                $empresa=Empresas::where('id',$request->empresa_editar);

                                if($request->cantidad_editar==0)
                                {
                                        $tasa = BusesDetalle::find($request->id_editar);
                                        $tasa->delete();
                                            
                                }
                                    else
                                        {
                                            BusesDetalle::where('id', $request->id_editar)
                                            ->update([
                                                        'cantidad' => $request->cantidad_editar,
                                                        'tarifa' => $request->monto_editar,
                                                        'monto_pagar' => $request->pago_mensual_editar,           
                                                    ]);
                                        }
                                            //**Al actualizar datos de buses se debera calificar para una nueva tarifa */
                            CalificacionBuses::where('id_buses_detalle', $request->id_buses_detalle_editar)
                            ->delete();
                         //**Termina borrar calificación del rótulo */

                                    // actualizar registros matrícula específica
                                DB::commit();

                            // /. actualizar registros matrícula específica
                            return ['success' => 1];

                        }else
                            {  // borrar registros detalle
                                // solo si viene vacio el array
                                if($request->idarray == null)
                                {
                                    BusesDetalleEspecifico::where('id_buses_detalle', $request->id_buses_detalle_editar)
                                    ->delete();
                                    
                                    if($request->cantidad_editar==0)
                                    {
                                        $tasa = BusesDetalle::find($request->id_editar);
                                        $tasa->delete();                                    
                                    }

                           
                                }
                                DB::commit();
                                return ['success' => 1];
                            }     
                        
                    }

            }catch(\Throwable $e){
                DB::rollback();
                return ['success' => 2];
            }
    
    }
    
    //Termina función editar bus y específico.
    
    public function VerBusEsp(Request $request)
    {
        $regla = array(
          'id' => 'required',
        );

            $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($lista = BusesDetalle::where('id', $request->id)->first()){
            $empresa = Empresas::orderBy('nombre')->get();
            $signoD='$';

        $listado=db::table('buses_detalle_especifico')

        ->join('buses_detalle', 'buses_detalle.id', '=', 'buses_detalle_especifico.id_buses_detalle')
            
        ->select('buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre','buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
                'buses_detalle.cantidad','buses_detalle.monto_pagar',
                )
        ->where('buses_detalle_especifico.id_buses_detalle', $request->id)     
        ->get();

        $mdetalle=db::table('buses_detalle')

        ->join('empresa', 'empresa.id', '=', 'buses_detalle.id_empresa')
            
        ->select('buses_detalle.id', 
                'empresa.nombre',
                )
        ->where('buses_detalle.id', $request->id)     
        ->first();

        return ['success' => 1,
                'matriculas_detalle' => $lista,
                'id_empresa' => $lista->id_empresa,
                'cantidad' => $lista->cantidad,
                'montoDolar'=> $signoD.$lista->monto,
                'empresa'=>$empresa,
                'listado'=> $listado,
                'mdetalle'=>$mdetalle
                ];
        }else{
            return ['success' => 2];
        }
    }

    public function eliminarB(Request $request)
    { 
            $listado=db::table('buses_detalle_especifico')

            ->join('buses_detalle', 'buses_detalle.id', '=', 'buses_detalle_especifico.id_buses_detalle')
                
            ->select('buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre','buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
                    'buses_detalle.cantidad','buses_detalle.monto_pagar',
                    )
            ->where('buses_detalle_especifico.id_buses_detalle', $request->id)     
            ->first();

            $Detectar_calificacion_bus=CalificacionMatriculas::where('id_matriculas_detalle', $request->id)
            ->get();
            
            if($listado=="")
            {
                    if($Detectar_calificacion_bus=!null){

                        $delete = CalificacionMatriculas::where('id_matriculas_detalle', $request->id);
                        $delete->delete();

                        $tasa = BusesDetalle::find($request->id);
                        $tasa->delete();

                        return 
                            [
                                'success' => 1,
                            ];
                    }else{
                            $tasa = busesDetalle::find($request->id);
                            $tasa->delete();
                
                            return 
                                [
                                    'success' => 1,
                                ];
                        }
            }
            else
                {//if pricipal
                        return 
                        [
                            'success' => 2,
                        ];
                }
    }

    public function showBuses($id)
    {
        $fechahoy =carbon::now()->format('Y-m-d');
               
        $buses = BusesDetalle::join('contribuyente','buses_detalle.id_contribuyente','=','contribuyente.id')
        ->join('estado_buses','buses_detalle.id_estado_buses','=','estado_buses.id')

        ->select('buses_detalle.id', 'buses_detalle.fecha_apertura','buses_detalle.nFicha',
        'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar','buses_detalle.estado_especificacion',
        'buses_detalle.nom_empresa','buses_detalle.dir_empresa','buses_detalle.nit_empresa',
        'buses_detalle.tel_empresa','buses_detalle.email_empresa','buses_detalle.r_comerciante',
        
        'contribuyente.id as id_contribuyente','contribuyente.nombre as contribuyente', 'contribuyente.apellido',
        'estado_buses.estado')

        ->find($id);


        $listado=BusesDetalleEspecifico
        ::join('buses_detalle', 'buses_detalle.id', '=', 'buses_detalle_especifico.id_buses_detalle')
            
        ->select('buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre','buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
                'buses_detalle.cantidad','buses_detalle.monto_pagar')
      
        ->find($id);
        

        $calificacion = CalificacionBuses::select('calificacion_buses.id', 'calificacion_buses.fecha_calificacion','calificacion_buses.estado_calificacion','calificacion_buses.id_contribuyente')
       
        ->where('id_buses_detalle', $id) 
        ->latest()
        ->first();

      
        $ultimaEsp = BusesDetalle::latest()      
        ->where('id', $id)
        ->first();

        //** Inicia - Para obtener la tasa de interes más reciente */
        $Tasainteres=Interes::latest()
        ->pluck('monto_interes')
            ->first();
        //** Finaliza - Para obtener la tasa de interes más reciente */


        if ($calificacion == null  )
        {
            $detectorNull = 0;

            if($buses == null)
            
                $detectorNull = 0;
                $detectorEsp = 0;
         
          
        }

        else
        {

        $detectorNull=1;

        if ($buses == null)
        {
            $detectorNull=0;
            $detectorEsp=0;

        }
        else 
        {
          $detectorNull = 1;
          $detectorEsp = 1;

        }
        }

        //** Comprobación de pago al dia se hace para reinciar las alertas avisos y notificaciones */
            $ComprobandoPagoAlDiaBus = CobrosBuses::latest()
            ->where('id_contribuyente', $buses->id_contribuyente)
            ->pluck('periodo_cobro_fin')
                ->first();

        if($ComprobandoPagoAlDiaBus == null){
            
           
                        if($ComprobandoPagoAlDiaBus == null){
                            $ComprobandoPagoAlDiaBus = $buses->fecha_apertura;
                       
                    
            }else{
                        $ComprobandoPagoAlDiaBus = $buses->fecha_apertura;
                        
                }

        }//** Comprobación de pago al dia se hace para reinciar las alertas avisos y notificaciones */

        log::info('comprobacion de pago:' .$ComprobandoPagoAlDiaBus);

        $alerta_notificacion_bus = alertas_detalle_buses::where('id_contribuyente', $buses->id_contribuyente)
        ->where('id_alerta','2')
        ->pluck('cantidad')
        ->first();
    
        $alerta_aviso_bus = alertas_detalle_buses::where('id_contribuyente', $buses->id_contribuyente)
        ->where('id_alerta','1')
        ->pluck('cantidad')
        ->first();
    
        if($alerta_aviso_bus == null)
            {           
                $alerta_aviso_bus = 0;

            }else{
    
                    if($ComprobandoPagoAlDiaBus >= $fechahoy)  
                    {
                       
                        $alerta_aviso_bus=0;

                        alertas_detalle_buses::where('id_contribuyente', $buses->id_contribuyente)
                        ->where('id_alerta','1')
                        ->update([
                                    'cantidad' => $alerta_aviso_bus,              
                                ]);   
    
                    }else{
                            $alerta_aviso_bus = $alerta_aviso_bus;
                        }
                }
    
        if($alerta_notificacion_bus == null)
        {
            $alerta_notificacion_bus = 0;

        }else{
                if($ComprobandoPagoAlDiaBus >= $fechahoy)  
                {
                    $alerta_notificacion_bus=0;
                    alertas_detalle_buses::where('id_contribuyente', $buses->id_contribuyente)
                    ->where('id_alerta','2')
                    ->update([
                                'cantidad' => $alerta_notificacion_bus,              
                            ]); 
    
                }else{
                         $alerta_notificacion_bus = $alerta_notificacion_bus;
                     }
            }
    
    
        //** Comprobando si la empresa esta al dia con sus pagos de impuestos de empresa */
        if($ComprobandoPagoAlDiaBus >= $fechahoy)
        {   
          
            //** Si NoNotificar vale 1 entonces NO SE DEBE imprimir una notificación ni avisos*/Esta al dia
            $NoNotificarBus = 1;
            log::info('NoNotificar:' .$NoNotificarBus);

        }else
                {
                    //** Si NoNotificar vale 0 entonces es permitido imprimir una notificación o avisos*/
                    $NoNotificarBus = 0;
                    log::info('NoNotificar:' .$NoNotificarBus);

                }
            
        //* fin de comprobar */
        return view('backend.admin.Buses.vistaBuses', compact('id',                                                   
                                                    'calificacion',
                                                    'buses',
                                                    'listado',
                                                    'detectorNull',
                                                    'detectorEsp',
                                                    'ultimaEsp',
                                                    'fechahoy',                                                   
                                                    'calificacion',
                                                    'alerta_aviso_bus',                                                        
                                                    'alerta_notificacion_bus', 
                                                    'NoNotificarBus',
                                                    'Tasainteres'
                                                
                                                ));

    }


    public function calificacionBus($id)
    {
                   
            $busesE = BusesDetalleEspecifico::where ('id', $id)->first();
            $buses = BusesDetalle::where ('id', $id)->first();
          
            $bus = BusesDetalle::ALL();
         
            $calificacionB=BusesDetalleEspecifico
            ::join('buses_detalle','buses_detalle_especifico.id_buses_detalle','=','buses_detalle.id')
                                      
            ->select('buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre',
            'buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
            'buses_detalle.tarifa','buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.fecha_apertura','buses_detalle.nFicha')
            
            ->where('id_buses_detalle', $buses->id)					
            ->get();
        
        

            $calificacion = BusesDetalle::join('contribuyente','buses_detalle.id_contribuyente','=','contribuyente.id')
            ->join('estado_buses','buses_detalle.id_estado_buses','estado_buses.id')
    
            ->select('buses_detalle.id', 'buses_detalle.fecha_apertura','buses_detalle.nFicha',
            'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar','buses_detalle.estado_especificacion',
            'buses_detalle.nom_empresa','buses_detalle.dir_empresa','buses_detalle.nit_empresa',
            'buses_detalle.tel_empresa','buses_detalle.email_empresa','buses_detalle.r_comerciante',
            
            'contribuyente.nombre as contribuyente', 'contribuyente.apellido',
            'estado_buses.estado')

            ->get();
           

            $Cbus = ' ';
            $Tbus = '';
            $Mbus = '';
            $fecha = '';
            $TotalT = '';
            $TotalF = '';
            $Total = '';
            $TotalA = '';
            $TotalAF = '';
            $fondoF = 0.05;
            $nombre = '';
            $nom_empresa = '';
            $ficha = '';
            $cantidad = '';
            $tarifa = '';
            $total = '';
    
            if ($buses = BusesDetalle::where('id', $id)->first())
            {

                $fecha = $buses->fecha_apertura;
                $Cbus = $buses->cantidad;
                $Tbus = $buses->tarifa;
                $Mbus  = $buses->monto_pagar;
                $nom_empresa = $buses->nom_empresa;
                $ficha = $buses->nFicha;
                $cantidad = $buses->cantidad;
                $tarifa = $buses->tarifa;
                $total = $buses->monto_pagar;
              

                $Total = ($Tbus / $Cbus);
                $Total = number_format((float)$Total, 2, '.', ',');
                $TotalA = $Tbus *12;
                $TotalAF = $Mbus * 12;
                $TotalT = $Total * $Cbus;
                $TotalF = $TotalT +($TotalT * $fondoF);
                $TotalF = number_format((float)$TotalF, 2, '.', ',');
                
            }

            if ($contribuyentes = Contribuyentes::where('id', $buses->id_contribuyente)->first())
            {
                $contribuyente = $contribuyentes->nombre;
                $apellido = $contribuyentes->apellido;
                
            }
            return view('backend.admin.Buses.CalificacionBuses', compact('id','ficha','cantidad','tarifa','total','apellido','contribuyentes','contribuyente','nom_empresa','Total','nombre','Tbus','TotalF','fondoF','TotalT','TotalA','TotalAF','TotalT','fecha','busesE','Cbus','Mbus','buses','calificacionB','calificacion'));
    }


    public function tablaCalificacionB($id)
    {

            $busesE = BusesDetalleEspecifico::where('id', $id)->first();
            $buses = BusesDetalle::where ('id', $id)->first();
            $bus = BusesDetalle::ALL();

         
            $calificacionB=BusesDetalleEspecifico
            ::join('buses_detalle','buses_detalle_especifico.id_buses_detalle','=','buses_detalle.id')
                                      
            ->select('buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre',
            'buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
            'buses_detalle.id','buses_detalle.tarifa','buses_detalle.nFicha','buses_detalle.cantidad','buses_detalle.monto_pagar',
            'buses_detalle.fecha_apertura','buses_detalle.nFicha')

            ->where('id_buses_detalle', $buses->id)					
            ->get();
  


            $calificacion = BusesDetalle::join('contribuyente','buses_detalle.id_contribuyente','=','contribuyente.id')
            ->join('estado_buses','buses_detalle.id_estado_buses','estado_buses.id')
    
            ->select('buses_detalle.id', 'buses_detalle.fecha_apertura','buses_detalle.nFicha',
            'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar','buses_detalle.estado_especificacion',
            'buses_detalle.nom_empresa','buses_detalle.dir_empresa','buses_detalle.nit_empresa',
            'buses_detalle.tel_empresa','buses_detalle.email_empresa','buses_detalle.r_comerciante',
            
            'contribuyente.id', 'contribuyente.nombre AS contri', 'contribuyente.apellido AS apellido',
            'estado_buses.estado')

            
            ->get();


            $Cbus = ' ';
            $Tbus = '';
            $Mbus = '';
            $fecha = '';
            $TotalT = '';
            $TotalF = '';
            $Total = '';
            $TotalA = '';
            $TotalAF = '';
            $fondoF = 0.05;
            $nombre = '';
            $nom_empresa = '';
            $ficha = '';
            $cantidad = '';
            $tarifa = '';
            $total = '';

            if ($buses = BusesDetalle::where('id', $id)->first())
            {

                $fecha = $buses->fecha_apertura;
                $Cbus = $buses->cantidad;
                $Tbus = $buses->tarifa;
                $Mbus  = $buses->monto_pagar;
                $nom_empresa = $buses->nom_empresa;
                $ficha = $buses->nFicha;
                $cantidad = $buses->cantidad;
                $tarifa = $buses->tarifa;
                $total = $buses->monto_pagar;
              

                $Total = ($Tbus / $Cbus);
                $Total = number_format((float)$Total, 2, '.', ',');
                $TotalA = $Tbus *12;
                $TotalAF = $Mbus * 12;
                $TotalT = $Total * $Cbus;
                $TotalF = $TotalT +($TotalT * $fondoF);
                $TotalF = number_format((float)$TotalF, 2, '.', ',');
                
            }

            if ($contribuyentes = Contribuyentes::where('id', $buses->id_contribuyente)->first())
            {
                $contribuyente = $contribuyentes->nombre;
                $apellido = $contribuyentes->apellido;
                
            }
          
            return view('backend.admin.Buses.tabla.tablaBus', compact('calificacionB','calificacion','buses','ficha','cantidad'
        ,'tarifa','total'));
             
    }
        //Termica calculo de la calificación de buses

    public function guardarCalificacionBus(Request $request)
    {   
          
    //   $id = $request->id;
        $fecha_calificacion = $request->fechacalificar;
        $estado_calificaion =  $request->estado_calificacion;     
        $id_buses_detalle = $request->id_buses_detalle;       
        $id_contribuyente = $request->id_contribuyente;
        $ficha = $request->ficha;

        log::info('fecha calificacion '.$fecha_calificacion);
        log::info('estado calificacion '.$estado_calificaion);
        log::info('id buses detalle ' .$id_buses_detalle);
        log::info('id contribuyente ' .$id_contribuyente);
        log::info('ficha ' .$ficha);
        
      
        
        $calificacionB=BusesDetalle::select('cantidad','tarifa','monto_pagar','estado_especificacion')
        ->where('id', $id_buses_detalle)
      
        ->latest()->first();
        
       
        log::info($calificacionB);
    
        $dt = new CalificacionBuses();
        $dt->id_buses_detalle = $request->id_buses_detalle;
        $dt->id_contribuyente = $request->id_contribuyente;
        $dt->fecha_calificacion = $request->fechacalificar; 
        $dt->nFicha = $request->ficha;          
        $dt->cantidad = $calificacionB->cantidad;   
        $dt->monto = $calificacionB->tarifa;
        $dt->pago_mensual = $calificacionB->monto_pagar;
        $dt->estado_calificacion = $request->estado_calificacion;
        $dt->save();
        
        if($dt->save())
      
        {
        return ['success' => 1];
    
        }

    }  
    
    
    public function cobrosBus($id)
    {
        $tasasDeInteres = Interes::select('monto_interes')
        ->orderby('id','desc')
        ->get();
        
        $date=Carbon::now()->toDateString(); 

        $buses = BusesDetalle::where('id', $id)->first();

      
        $calificaciones = BusesDetalle::join('contribuyente','buses_detalle.id_contribuyente','=','contribuyente.id')
            ->join('estado_buses','buses_detalle.id_estado_buses','=','estado_buses.id')
    
            ->select('buses_detalle.id', 'buses_detalle.fecha_apertura','buses_detalle.nFicha',
            'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar','buses_detalle.estado_especificacion',
            'buses_detalle.nom_empresa','buses_detalle.dir_empresa','buses_detalle.nit_empresa',
            'buses_detalle.tel_empresa','buses_detalle.email_empresa','buses_detalle.r_comerciante',
            
            'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
            'estado_buses.estado')
                            
        ->find($id);

        
          

        $calificacionB=BusesDetalleEspecifico
            ::join('buses_detalle','buses_detalle_especifico.id_buses_detalle','=','buses_detalle.id')
                                      
            ->select('buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre',
            'buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
            'buses_detalle.id','buses_detalle.tarifa','buses_detalle.nFicha','buses_detalle.cantidad','buses_detalle.monto_pagar',
            'buses_detalle.fecha_apertura','buses_detalle.nFicha')

          			
            ->get();

        $ultimo_cobro = CobrosBuses::latest()
        ->where('id_buses_detalle', $id)
        ->first();

        $calificacion = CalificacionBuses::select('calificacion_buses.id', 'calificacion_buses.fecha_calificacion')
       
        ->where('id_buses_detalle', $id)
        ->latest()
        ->first(); 


        if ($calificacion == null)
        { 
            $detectorNull=0;
            if ($ultimo_cobro == null)
            {
                $detectorNull=0;
                $detectorCobro=0;
                return view('backend.admin.Buses.Cobros.cobrosBus', compact('buses','detectorNull','detectorCobro','calificaciones'));
            }
        }
        else
        {  
            $detectorNull=1;
            if ($ultimo_cobro == null)
            {
             $detectorNull=0;
             $detectorCobro=0;
            return view('backend.admin.Buses.Cobros.cobrosBus', compact('buses','calificaciones','calificacion','calificacionB','tasasDeInteres','date','detectorNull','detectorCobro'));
            }
            else
            {
                $detectorNull=1;
                $detectorCobro=1;
                  
            return view('backend.admin.Buses.Cobros.cobrosBus', compact('buses','calificaciones','calificacion','calificacionB','tasasDeInteres','date','detectorNull','detectorCobro','ultimo_cobro'));
            }
          
        }    
    
    }

    public function calcularCobrosBus(Request $request)
    {
  
        $id_contribuyente = $request->id_contribuyente;
        $id=$request->id;
        $id_buses_detalle = $request->id_buses_detalle;
    
            
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
        //Log::info($PagoUltimoDiaMes);
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

        $calificacion = BusesDetalle::join('contribuyente','buses_detalle.id_contribuyente','=','contribuyente.id')
            ->join('estado_buses','buses_detalle.id_estado_buses','=','estado_buses.id')
    
            ->select('buses_detalle.id', 'buses_detalle.fecha_apertura','buses_detalle.nFicha',
            'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar','buses_detalle.estado_especificacion',
            'buses_detalle.nom_empresa','buses_detalle.dir_empresa','buses_detalle.nit_empresa',
            'buses_detalle.tel_empresa','buses_detalle.email_empresa','buses_detalle.r_comerciante',
            
            'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
            'estado_buses.estado')
                            
        ->find($id);

     
        $calificaciones = CalificacionBuses::select('calificacion_buses.id','calificacion_buses.cantidad','calificacion_buses.monto','calificacion_buses.pago_mensual',
        'calificacion_buses.fecha_calificacion','calificacion_buses.estado_calificacion')
       
        ->where('id_buses_detalle', $id)
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
               

                if ($request->cobrar=='1')
                {  

                $cobro = new CobrosBuses();
                $cobro->id_contribuyente = $request->id_contribuyente;
                $cobro->id_buses_detalle = $request->id;
                $cobro->nFicha = $request->nFicha;
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
                        'tarifa'=>$tarifa,
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
    public function infoTraspasoBuses(Request $request)
    {
        $regla = array(
             'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($lista = BusesDetalle::where('id', $request->id)->first()){
            
            $contribuyente = Contribuyentes::orderBy('nombre')->get();
            $estado_buses = EstadoBuses::orderBy('estado')->get();
          
            return ['success' => 1,

            'id_contri' => $lista->id_contribuyente,
            'idesta' => $lista->id_estado_empresa,
            'contribuyente' => $contribuyente,
            'estado_buses' => $estado_buses,
            
            ];
        }else{
            return ['success' => 2];
        }
    }


    /// CIERRE Y TRASPASO DE BUSES DETALLE
    public function cierres_traspasosBus($id){
            
        $idusuario = Auth::id();
        $infouser = Usuario::where('id', $idusuario)->first();
        $estado_buses = EstadoBuses::All();     
        $contribuyentes = Contribuyentes::ALL();


        $bus = BusesDetalle::join('contribuyente','buses_detalle.id_contribuyente','=','contribuyente.id')
            ->join('estado_buses','buses_detalle.id_estado_buses','=','estado_buses.id')
    
            ->select('buses_detalle.id', 'buses_detalle.fecha_apertura','buses_detalle.nFicha',
            'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar','buses_detalle.estado_especificacion',
            'buses_detalle.nom_empresa','buses_detalle.dir_empresa','buses_detalle.nit_empresa',
            'buses_detalle.tel_empresa','buses_detalle.email_empresa','buses_detalle.r_comerciante',
            
            'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
            'estado_buses.estado')
                            
        ->where('buses_detalle.id', $id)                
        ->first();

        
        $calificacionB=BusesDetalleEspecifico
        ::join('buses_detalle','buses_detalle_especifico.id_buses_detalle','=','buses_detalle.id')
                            
        //Consulta para mostrar los rótulos que pertenecen a una sola empresa
                              
        ->select('buses_detalle_especifico.id as id_buses_detalle_esp','buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre','buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
        'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.fecha_apertura','buses_detalle.tarifa')
      
        ->where('id_buses_detalle', $id)                
        ->get();


       
        return view('backend.admin.Buses.CierreTraspaso.Cierre_TraspasoBus',
                compact(
                        'estado_buses',     
                        'bus',
                        'id',                     
                        'contribuyentes',                        
                        'calificacionB',                      
                    ));
    }

    public function nTraspasoBus (Request $request)
    {

        $id = $request->id;
        $id_contribuyente = $request->contribuyente;
        $buses_array = $request->buses_array;
        $id_buses_detalle_esp = $request->id_buses_detalle_esp;
        
    log::info("hola");

    
        for($i=0; $i<count($request->buses_array); $i++)
        {
            log::info($request->buses_array[$i]);
        }

    
        return;

        log::info($id);
        log::info($id_contribuyente);
        log::info($buses_array);
        log::info($id_buses_detalle_esp);
  

        $bus = BusesDetalle::join('contribuyente','buses_detalle.id_contribuyente','=','contribuyente.id')
        ->join('estado_buses','buses_detalle.id_estado_buses','=','estado_buses.id')
    
        ->select('buses_detalle.id', 'buses_detalle.fecha_apertura','buses_detalle.nFicha',
        'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar','buses_detalle.estado_especificacion',
        'buses_detalle.nom_empresa','buses_detalle.dir_empresa','buses_detalle.nit_empresa',
        'buses_detalle.tel_empresa','buses_detalle.email_empresa','buses_detalle.r_comerciante',
            
        'contribuyente.id as id_contribuyente','contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
        'estado_buses.estado')
      
                
        ->find($id);

        $datos_contribuyente=Contribuyentes::select('nombre','apellido')
        ->where('id', $id_contribuyente)
        ->first();

        $regla = array(  
            'id' => 'required',
            'contribuyente' => 'required',
        );
      
        $validar = Validator::make($request->all(), $regla,
      
        );

        if ($validar->fails())
        { 
                return ['success' => 0,
                'message' => $validar->errors()->first()
            ];
        }

        if(BusesDetalleEspecifico::where('id', $request->id_buses_detalle_esp)->first())
        {
            //** Guardar registro historio en tabla traspasos */

        
            if($id_contribuyente != $bus->id_contribuyente)
            {
                $traspaso = new TraspasoBuses();
                $traspaso->id_buses_detalle = $id;
                $traspaso->nombre_bus = $request->buses_array;        
                $traspaso->propietario_anterior = $bus->contribuyente.' '.$bus->apellido;
                $traspaso->propietario_nuevo =  $datos_contribuyente->nombre.' '.$datos_contribuyente->apellido;
                $traspaso->fecha_a_partir_de = $request->Apartirdeldia;
                $traspaso->save();
                    //** FIN- Guardar registro historio en tabla traspasos */
                    
                    BusesDetalleEspecifico::where('id', $request->id_buses_detalle_esp)->update([
            
                            'id_buses_detalle' => $bus->id,
                    
                        ]);

                        return ['success' => 1];
                        
            }else{ 

                return ['success' => 3];

            }
        }else{

                return ['success' => 2];

                }
 

    }

    public function tablaTraspasos(BusesDetalleEspecifico $listado, $id)
    {
        $buses = BusesDetalle::ALL();
        
        $listado=BusesDetalleEspecifico
        ::join('buses_detalle','buses_detalle_especifico.id_buses_detalle','=','buses_detalle.id')
                            
                                    
        ->select('buses_detalle_especifico.id as id_buses_detalle_esp','buses_detalle_especifico.id_buses_detalle as id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre','buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
        'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.fecha_apertura','buses_detalle.tarifa')
      
        ->where('id_buses_detalle', $buses->id)                
        ->get();

            return view('backend.admin.Buses.CierreTraspaso.tabla.tablaTraspasos',
            compact('listado',
                    'buses',
                    'id'));
        
    }

}   