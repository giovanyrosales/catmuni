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
use App\Models\MatriculasDetalleEspecifico;
use App\Models\BusesDetalleEspecifico;
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
                               
        ->select('buses_detalle.id', 'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.tarifa',
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
                              
        ->select('buses_detalle.id as id_buses_detalle', 'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.tarifa','buses_detalle.estado_especificacion' ,      
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
        $bus->cantidad = $request->cantidad;
        $bus->tarifa = $request->tarifa;
        $bus->monto_pagar = $request->monto_pagar;
        
        
        if($bus->save()){
            return ['success' => 1];
        }
    
    }

    public function especificarBuses(Request $request){

        log::info($request->all());
        $id_buses_detalle=$request->id_buses_detalle;
    
        $CantidadSeleccionada=db::table('buses_detalle')
    
        ->join('empresa', 'empresa.id', '=', 'buses_detalle.id_empresa')
                    
        ->select('buses_detalle.id', 'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar',
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

    public function agregar_buses_detalle_especifico(Request $request){
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
                                                         'tarifa' => $request->Total_pago_mensual,
                                                         'monto_pagar' => $request->monto_total,           
                                                    ]);
                                        }
                                         
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
    
    }//Termina función editar bus y específico.
    
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

    
    
    

    
}   