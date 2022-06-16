<?php

namespace App\Http\Controllers\Backend\BusesDetalle;

use App\Http\Controllers\Backend\MatriculasDetalle\alert;
use App\Http\Controllers\Controller;
use App\Models\BusesDetalle;
use App\Models\Calificacion;
use App\Models\CalificacionMatriculas;
use App\Models\CobrosMatriculas;
use App\Models\LicenciaMatricula;
use App\Models\MatriculasDetalle;
use App\Models\Empresas;
use App\Models\Contribuyentes;
use App\Models\CobrosBuses;
use App\Models\Interes;
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
        $empresas = Empresas::ALL();

        $busesRegistrados=BusesDetalle
        ::join('empresa','buses_detalle.id_empresa','=','empresa.id')
                               
        ->select('buses_detalle.id', 'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.tarifa','buses_detalle.fecha_apertura','buses_detalle.estado_especificacion',
                'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante',
                'empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono')
        
        ->get();

        
        if ($busesRegistrados == null)
             { 
                 $detectorBus=1;
             }else 
             {
                $detectorBus=0;
             }
            
             
      
        return view('backend.admin.Buses.CrearBuses', compact('empresas','detectorBus'));
    }

    public function tablaBuses(BusesDetalle $buses)
    {

        $buses=BusesDetalle        
        ::join('empresa','buses_detalle.id_empresa','=','empresa.id')
                              
        ->select('buses_detalle.id as id_buses_detalle', 'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.tarifa','buses_detalle.fecha_apertura','buses_detalle.estado_especificacion' ,      
                'empresa.nombre as empresa','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral',
                'empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta',
                'empresa.telefono')
      
        ->get();

           
        return view('backend.admin.Buses.tabla.tabla_buses', compact('buses'));
    }

    public function listarBuses()
    {
   
        $empresas = Empresas::All();
     
        return view('backend.admin.Buses.ListarBuses', compact('empresas'));
    }

    public function nuevoBus(Request $request)
    {
        log::info($request->all());
     
        $regla = array(  
            'empresa' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);
    
        if ($validar->fails()){ return ['success' => 0];}

        if (BusesDetalle::where('id_empresa', $request->empresa)->first()) {
            return ['success' => 2];   
         }

        $bus = new BusesDetalle();       
        $bus->id_empresa = $request->empresa;     
        $bus->fecha_apertura = $request->fecha_apertura;  
        $bus->cantidad = $request->cantidad;
        $bus->tarifa = $request->tarifa;
        $bus->monto_pagar = $request->monto_pagar;
        
        
        if($bus->save()){
            return ['success' => 1];
        }
    
    }

    public function especificarBuses(Request $request)
    {

        log::info($request->all());
        $id_buses_detalle=$request->id_buses_detalle;
    
        $CantidadSeleccionada=db::table('buses_detalle')
    
        ->join('empresa', 'empresa.id', '=', 'buses_detalle.id_empresa')
                    
        ->select('buses_detalle.id', 'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar','buses_detalle.fecha_apertura','buses_detalle.estado_especificacion',
                'empresa.nombre AS empresa','empresa.id_contribuyente AS contribuyente','empresa.id AS empresa_id',)
               
        ->where('buses_detalle.id', "=", "$id_buses_detalle")     
        ->first();
    
        $busesEspecificos=db::table('buses_detalle_especifico')
    
        ->join('buses_detalle', 'buses_detalle.id', '=', 'buses_detalle_especifico.id_buses_detalle')
              
        ->select('buses_detalle_especifico.id','buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre','buses_detalle_especifico.ruta',
        'buses_detalle_especifico.telefono',
        'buses_detalle.cantidad','buses_detalle.monto_pagar')
        ->where('buses_detalle_especifico.id_buses_detalle', "=", "$id_buses_detalle")     
        ->first();
    
     
    
        return  [
                    'success' => 1,
                    'cantidad' =>$CantidadSeleccionada->cantidad,
                    'id_buses_detalle' =>$request->id_buses_detalle,
                
                    'busesEspecificos'=>$busesEspecificos,
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
          
            for ($i = 0; $i < count($request->placa); $i++) {
    
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
            $empresa = Empresas::orderBy('nombre')->get();
            
            $signoD='$';
        $listado=db::table('buses_detalle_especifico')

        ->join('buses_detalle', 'buses_detalle.id', '=', 'buses_detalle_especifico.id_buses_detalle')
            
        ->select('buses_detalle_especifico.id','buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre',
        'buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
                'buses_detalle.cantidad','buses_detalle.monto_pagar',
                )
        ->where('buses_detalle_especifico.id_buses_detalle', $request->id)     
        ->get();

        return ['success' => 1,
                'buses_detalle' => $lista,
                'empresa' =>$empresa,
                'id_empresa' => $lista->id_empresa,
                'cantidad' => $lista->cantidad,
                'montoDolar'=> $lista->tarifa,
                'Pago_mensualDolar'=> $lista->monto_pagar,
                //'empresa'=>$empresa,
                'listado'=> $listado,
                ];
        }else{
            return ['success' => 2];
        }
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

                                if($request->cantidad_editar==0){
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
                                if($request->idarray == null){
                                BusesDetalleEspecifico::where('id_buses_detalle', $request->id_buses_detalle_editar)
                                ->delete();
                                if($request->cantidad_editar==0){
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
            $empresa = Empresas::ALL();
         //   $busesEsp = BusesDetalleEspecifico::ALL();

           
        $buses=BusesDetalle        
        ::join('empresa','buses_detalle.id_empresa','=','empresa.id')
        ->join('contribuyente', 'empresa.id_contribuyente', '=', 'contribuyente.id')
                              
        ->select('buses_detalle.id as id_buses_detalle', 'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.tarifa','buses_detalle.fecha_apertura','buses_detalle.estado_especificacion' ,      
                'empresa.nombre as empresa','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral',
                'empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta',
                'empresa.telefono',
                'contribuyente.nombre as contribuyente','contribuyente.apellido as apellido')
      
        ->find($id);

        $listado=BusesDetalleEspecifico
        ::join('buses_detalle', 'buses_detalle.id', '=', 'buses_detalle_especifico.id_buses_detalle')
            
        ->select('buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre','buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
                'buses_detalle.cantidad','buses_detalle.monto_pagar',
                )
      
        ->find($id);

        $calificacion = CalificacionBuses::select('calificacion_buses.id', 'calificacion_buses.fecha_calificacion','calificacion_buses.estado_calificacion','calificacion_buses.id_empresa')
       
        ->where('id_buses_detalle', $id)
        ->latest()
        ->first();

      
        $ultimaEsp = BusesDetalle::latest()      
        ->where('id', $id)
        ->first();


        if ($calificacion == null  )
        {
            $detectorNull = 0;
            if($buses == null)
            
          $detectorNull = 0;
          $detectorEsp = 0;
         
            return view('backend.admin.Buses.vistaBuses', compact('detectorNull','detectorEsp','buses','calificacion'));
          
        }
        else
        {
        $detectorNull=1;
        if ($buses == null)
        {
            $detectorNull=0;
         $detectorEsp=0;

         return view('backend.admin.Buses.vistaBuses', compact('empresa','calificacion','buses','listado','detectorNull','detectorEsp',));        
        }
          else 
          {
          $detectorNull = 1;
          $detectorEsp = 1;

          }
          return view('backend.admin.Buses.vistaBuses', compact('empresa','calificacion','buses','listado','detectorNull','detectorEsp','ultimaEsp'));
        }
          
}


    public function calificacionBus ($id)
    {
          
            $empresa = Empresas::ALL();           
           
            $busesE = BusesDetalleEspecifico::where ('id', $id)->first();
            $buses = BusesDetalle::where ('id', $id)->first();

            $calificacionB=BusesDetalleEspecifico
            ::join('buses_detalle','buses_detalle_especifico.id_buses_detalle','=','buses_detalle.id')
                                
            //Consulta para mostrar los rótulos que pertenecen a una sola empresa
                                  
            ->select('buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre','buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
            'buses_detalle.tarifa','buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.fecha_apertura',)
            
            ->where('id_buses_detalle', $buses->id)
            
            ->get();
          
         

            
            $calificacion=BusesDetalle        
            ::join('empresa','buses_detalle.id_empresa','=','empresa.id')
           
                                  
            ->select('buses_detalle.id as id_buses_detalle', 'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.tarifa',
            'buses_detalle.fecha_apertura','buses_detalle.estado_especificacion' ,      
                    'empresa.nombre as empresa','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral',
                    'empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta',
                    'empresa.telefono')
                    
            ->get();

             
            $Cbus = ' ';
            $Tbus = '';
            $Mbus = '';
            $fecha = '';
            $totalT = '';
          //  $Ntarifa = 0;
          $fondoF = 0.05;
          
    
            if ($buses = BusesDetalle::where('id', $id)->first())
            {
                $fecha = $buses->fecha_apertura;
                $Cbus = $buses->cantidad;
                $Tbus = $buses->tarifa;
                $Mbus  = $buses->monto_pagar;
               
                
            }

            $Total = $Tbus / $Cbus;
            $Total = number_format((float)$Total, 2, '.', ',');
            $TotalA = $Tbus *12;
            $TotalAF = $Mbus * 12;
            $TotalT = $Total * $Cbus;
            $TotalF = $TotalT +($TotalT * $fondoF);
            $TotalF = number_format((float)$TotalF, 2, '.', ',');

            if ($empresa = Empresas::where('id', $buses->id_empresa)->first())
            {
               
                $emp  =   $empresa->nombre;
                $emp1 =  $empresa->direccion;
                $emp2  = $empresa->contribuyente;
                
            }

            if ($contribuyente = Contribuyentes::where('id', $empresa->id_contribuyente)->first())
            {
                $contri = $contribuyente->nombre;
                $ape = $contribuyente->apellido;
                
            }

          
            return view('backend.admin.Buses.CalificacionBuses', compact('id','emp','Total','TotalF','fondoF','TotalT','TotalA','TotalAF','totalT','fecha','ape','contri','empresa','emp','emp1','busesE','Cbus','Mbus','buses','calificacionB','calificacion'));
    }


    public function tablaCalificacionB($id)
    {
            $empresa = Empresas::where('id', $id)->first();
            $busesE = BusesDetalleEspecifico::where('id', $id)->first();
            $buses = BusesDetalle::where ('id', $id)->first();

            $calificacionB=BusesDetalleEspecifico
            ::join('buses_detalle','buses_detalle_especifico.id_buses_detalle','=','buses_detalle.id')
                                
            //Consulta para mostrar los rótulos que pertenecen a una sola empresa
                                  
            ->select('buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre','buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
            'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.fecha_apertura','buses_detalle.tarifa')

            ->where('id_buses_detalle', $buses->id)
            ->get();

          
            $calificacion=BusesDetalle        
            ::join('empresa','buses_detalle.id_empresa','=','empresa.id')
           
                                  
            ->select('buses_detalle.id as id_buses_detalle', 'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.tarifa',
            'buses_detalle.fecha_apertura','buses_detalle.estado_especificacion' ,      
                    'empresa.nombre as empresa','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral',
                    'empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta',
                    'empresa.telefono')
                       
            ->get();

           
          
            if ($buses = BusesDetalle::where('id', $id)->first())
            {
                $fecha = $buses->fecha_apertura;
                $Cbus = $buses->cantidad;
                $Tbus = $buses->tarifa;
                $Mbus = $buses->monto_pagar;

            }


            if ($empresa = Empresas::where('id', $buses->id_empresa)->first())
            {
               
                $emp = $empresa->nombre;
                $emp1 = $empresa->direccion;
                $emp2  = $empresa->contribuyente;
                
            }

            if ($contribuyente = Contribuyentes::where('id', $empresa->id_contribuyente)->first())
            {
                $contri = $contribuyente->nombre;
                $ape = $contribuyente->apellido;
                
            }
            
          
            return view('backend.admin.Buses.tabla.tablaBus', compact('calificacionB','calificacion','buses','fecha','Cbus','Tbus','Mbus','emp','emp1','emp2','contri','ape'));
             
    }
        //Termica calculo de la calificación de buses

    public function guardarCalificacionBus(Request $request)
    {   
          
    //   $id = $request->id;
        $fecha_calificacion = $request->fechacalificar;
        $estado_calificaion =  $request->estado_calificacion;     
        $id_buses_detalle = $request->id_buses_detalle;       
        $id_empresa = $request->id_empresa;

        log::info('fecha calificacion '.$fecha_calificacion);
        log::info('estado calificacion '.$estado_calificaion);
        log::info('id buses detalle ' .$id_buses_detalle);
        log::info('id empresa ' .$id_empresa);
        
      
        
        $calificacionB=BusesDetalle::select('cantidad','tarifa','monto_pagar','estado_especificacion')
        ->where('id', $id_buses_detalle)
        ->where('id_empresa', $id_empresa)
        ->latest()->first();
        
       
        log::info($calificacionB);
    
        $dt = new CalificacionBuses();
        $dt->id_buses_detalle = $request->id_buses_detalle;
        $dt->id_empresa = $request->id_empresa;
        $dt->fecha_calificacion = $request->fechacalificar;           
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

        $empresa = BusesDetalle::where('id', $id)->first();

        $buses = BusesDetalleEspecifico::where('id', $id)->first();

        $calificaciones=BusesDetalle        
        ::join('empresa','buses_detalle.id_empresa','=','empresa.id')
       
                              
        ->select('buses_detalle.id as id_buses_detalle', 'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.tarifa',
        'buses_detalle.fecha_apertura','buses_detalle.estado_especificacion' ,      
                'empresa.nombre as empresa','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral',
                'empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta',
                'empresa.telefono')

                            
        ->find($id);

        $calificacionB=BusesDetalleEspecifico
        ::join('buses_detalle','buses_detalle_especifico.id_buses_detalle','=','buses_detalle.id')
                            
        //Consulta para mostrar los rótulos que pertenecen a una sola empresa
                              
        ->select('buses_detalle_especifico.id_buses_detalle', 'buses_detalle_especifico.placa','buses_detalle_especifico.nombre','buses_detalle_especifico.ruta','buses_detalle_especifico.telefono',
        'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.fecha_apertura','buses_detalle.tarifa')
      
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
                return view('backend.admin.Buses.Cobros.cobroBus', compact('detectorNull','detectorCobro'));
            }
        }
        else
        {  
            $detectorNull=1;
            if ($ultimo_cobro == null)
            {
             $detectorNull=0;
             $detectorCobro=0;
            return view('backend.admin.Buses.Cobros.cobroBus', compact('buses','calificaciones','calificacion','calificacionB','empresa','tasasDeInteres','date','detectorNull','detectorCobro'));
            }
            else
            {
                $detectorNull=1;
                $detectorCobro=1;
                  
            return view('backend.admin.Buses.Cobros.cobrosBus', compact('buses','calificaciones','calificacion','calificacionB','empresa','tasasDeInteres','date','detectorNull','detectorCobro','ultimo_cobro'));
            }
          
        }    
    
    }

    public function calcularCobrosBus(Request $request)
    {
  
        $id_empresa = $request->id_empresa;
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
        $DTF=Carbon::parse($request->fechaPagara)->addMonthsNoOverflow(1)->day(1);
        $PagoUltimoDiaMes=$DTF->subDays(1)->format('Y-m-d');
        //Log::info($PagoUltimoDiaMes);
        //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */

        //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
        $f_inicio=Carbon::parse($request->ultimo_cobro)->addMonthsNoOverflow(2)->day(1);
        $UltimoDiaMes=$f_inicio->subDays(1);
        //Log::info( $UltimoDiaMes);
        $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(30)->format('Y-m-d');

        $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
        Log::info('Inicio moratorio inicia aqui');
        Log::info($FechaDeInicioMoratorio);
        $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
        //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */

    
      
        //** Inicia - Para obtener la tasa de interes más reciente */
        $Tasainteres=$request->tasa_interes;
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
                ->where('id_buses_detalle',$id_buses_detalle
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
                $cobro->id_empresa = $request->id_empresa;
                $cobro->id_buses_detalle = $request->id_buses_detalle;
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

   
    

}   