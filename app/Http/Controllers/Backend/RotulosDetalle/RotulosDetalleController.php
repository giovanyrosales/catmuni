<?php

namespace App\Http\Controllers\Backend\RotulosDetalle;

use App\Http\Controllers\Backend\MatriculasDetalle\alert;
use App\Http\Controllers\Controller;
use App\Models\alertas_detalle_rotulos;
use App\Models\CalificacionRotulo;
use App\Models\CalificacionRotuloDetalle;
use App\Models\CobrosRotulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Environment\Console;
use App\Models\Contribuyentes;
use App\Models\RotulosDetalle;
use App\Models\Interes;
use App\Models\RotulosDetalleEspecifico;
use App\Models\Usuario;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Ramsey\Uuid\Guid\Guid;
use Illuminate\Validation\Rules\Unique;
use Symfony\Contracts\Service\Attribute\Required;

class RotulosDetalleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $contribuyentes = Contribuyentes::ALL();

        $rotulo = RotulosDetalle::orderBy('id')->get();

        $rotulos = RotulosDetalle::join('contribuyente', 'rotulos_detalle.id_contribuyente','contribuyente.id')
        ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo', 'estado_rotulo.id')

        ->select('rotulos_detalle.id','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
        'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
        'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
        'contribuyente.id', 'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido'.
        'estado_rotulo.id','estado_rotulo.estado')

        ->get();

       
      
        return view('backend.admin.RotulosDetalle.CrearRotulos', compact('contribuyentes','rotulo','rotulos'));

    }

    public function agregarRotulos(Request $request)
    {
     
        $regla = array(  
            'contribuyente' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);
    
        if ($validar->fails()){ return ['success' => 0];}

     
        $rotulo = new RotulosDetalle();       
        $rotulo->id_contribuyente = $request->contribuyente;            
        $rotulo->fecha_apertura = $request->fecha_apertura; 
        $rotulo->num_ficha = $request->num_ficha; 
        $rotulo->cantidad_rotulos = $request->cantidad_rotulos;   
        $rotulo->id_estado_rotulo = $request->estado_rotulo;
        $rotulo->nom_empresa = $request->nom_empresa;
        $rotulo->dire_empresa = $request->dire_empresa;
        $rotulo->nit_empresa = $request->nit_empresa;
        $rotulo->tel_empresa = $request->tel_empresa;
        $rotulo->email_empresa = $request->email_empresa;
        $rotulo->reg_comerciante = $request->reg_comerciante;
        
        
        if($rotulo->save())
        {
            return ['success' => 1];
        }
        
    
    }

    public function tablaRotulos(RotulosDetalle $rotulo)
    {
      
        $rotulo = RotulosDetalle::join('contribuyente', 'rotulos_detalle.id_contribuyente','contribuyente.id')
        ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo', 'estado_rotulo.id')

        ->select('rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
        'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
        'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
        
        'contribuyente.id','contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
        'estado_rotulo.id','estado_rotulo.estado')

        ->get();
           
        return view('backend.admin.RotulosDetalle.tabla.tablaListarRotulos', compact('rotulo'));
        
    }

    public function listarRotulos()
    {   
        $contribuyentes = Contribuyentes::ALL();
             
        return view('backend.admin.RotulosDetalle.ListarRotulos', compact('contribuyentes'));
    }

    public function especificarRotulos(Request $request)
    {

            log::info($request->all());

            $id_rotulos_detalle = $request->id_rotulos_detalle;
          
            $CantidadSeleccionada = RotulosDetalle::

            join('contribuyente', 'rotulos_detalle.id_contribuyente','=','contribuyente.id')
            ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo','=', 'estado_rotulo.id')

            ->select('rotulos_detalle.id','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
            'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
            'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
            
            'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido','contribuyente.id',
            'estado_rotulo.estado','estado_rotulo.id')

            ->where('rotulos_detalle.id', $id_rotulos_detalle)  
               
            ->first();

            $rotulosEspecificos = RotulosDetalleEspecifico::join('rotulos_detalle','rotulos_detalle_especifico.id_rotulos_detalle','rotulos_detalle.id')

            ->select('rotulos_detalle_especifico.id','rotulos_detalle_especifico.id_rotulos_detalle', 'rotulos_detalle_especifico.nombre','rotulos_detalle_especifico.medidas',
            'rotulos_detalle_especifico.total_medidas','rotulos_detalle_especifico.caras','rotulos_detalle_especifico.tarifa',
            'rotulos_detalle_especifico.total_tarifa','rotulos_detalle_especifico.coordenadas_geo','rotulos_detalle_especifico.foto_rotulo',
            
            'rotulos_detalle.id','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
            'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
            'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',)

            ->where('rotulos_detalle_especifico.id_rotulos_detalle', $id_rotulos_detalle)

            ->first();

            
            //return view('backend.admin.RotulosDetalle.EspecificarRotulos', compact('CantidadSeleccionada','rotulosEspecificos'));
          
            return  [

                        'success' => 1,
                        'cantidad_rotulos' =>$CantidadSeleccionada->cantidad_rotulos,
                        'id_rotulos_detalle' =>$request->id_rotulos_detalle,
                        'rotulosEspecificos' =>$rotulosEspecificos,
                    
                    ];
                    

    }

    public function agregar_rotulos_detalle_especifico(Request $request)
    {
        $especificada="especificada";
       
        for ($i = 0; $i < count ((array)$request->nombre) ; $i++){

            if (($request->foto_rotulo[$i] )) {
               
         
                $cadena = Str::random(15);
                $tiempo = microtime();
                $union = $cadena.$tiempo;
                $nombre = str_replace(' ', '_', $union);
              
                  
                $extension = '.'.$request->foto_rotulo[$i];
                $avatar = $request->file('foto_rotulo');
                $extension = '.'.$request->file('foto_rotulo')->getClientOriginalExtension();
                $file = $nombre.strtolower($extension);
                
                $estado = Storage::disk('images')->put($file, \File::get($avatar));
               
             

                    $Bd = new RotulosDetalleEspecifico();                 
                    $Bd->id_rotulos_detalle = $request->id_rotulos_detalle;               
                    $Bd->nombre = $request->nombre[$i];
                    $Bd->medidas = $request->medidas[$i];
                    $Bd->total_medidas=$request->total_medidas[$i];
                    $Bd->caras = $request->caras[$i];
                    $Bd->tarifa = $request->tarifa[$i];
                    $Bd->total_tarifa = $request->total_tarifa[$i];
                    $Bd->coordenadas_geo = $request->coordenadas_geo[$i];
                    $Bd->foto_rotulo = $request->$file[$i];

                    
                       
                    RotulosDetalle::where('id', $request->id_rotulos_detalle)
                    ->update([
                                'estado_especificacion' =>$especificada,               
                            ]);
                     
                    return ['success' => 1];
    
            }else{return ['success' => 2];}
       
        }
             
    } 

    public function showRotulos($id_rotulos_detalle)
    {

        $fechahoy = carbon::now()->format('Y-m-d');

        $rotulos = RotulosDetalle::join('contribuyente', 'rotulos_detalle.id_contribuyente','contribuyente.id')
        ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo', 'estado_rotulo.id')

        ->select('rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
        'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
        'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
        'contribuyente.id', 'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
        'estado_rotulo.id','estado_rotulo.estado')

        ->find($id_rotulos_detalle);


        $calificacionRotulos = CalificacionRotuloDetalle::
        select('calificacion_rotulo_detalle.id', 'calificacion_rotulo_detalle.fecha_calificacion','calificacion_rotulo_detalle.estado_calificacion','calificacion_rotulo_detalle.id_rotulos_detalle')
           
        ->where('id_rotulos_detalle', $id_rotulos_detalle)
        ->latest()
        ->first();

        $ListarCobros = CobrosRotulo::latest()
        ->get();

          //** Inicia - Para obtener la tasa de interes más reciente */
          $Tasainteres=Interes::latest()
          ->pluck('monto_interes')
              ->first();
          //** Finaliza - Para obtener la tasa de interes más reciente */
  

        if ($calificacionRotulos == null)
        {
           $detectorNull = 0;
        }
            else
            {
                $detectorNull = 1;
            }
        
    
            $ultimo_cobro_rotulo = CobrosRotulo::latest()
            ->where('id_contribuyente', $rotulos->id_contribuyente)
            ->first();
            
                if( $ultimo_cobro_rotulo==null)
                {
                    $ultimoCobroRotulos= $rotulos->fecha_apertura;
                }else{
                    $ultimoCobroRotulos = $ultimo_cobro_rotulo->periodo_cobro_fin;
                }


        //** Comprobación de pago al dia se hace para reinciar las alertas avisos y notificaciones */
            $ComprobandoPagoAlDiaRotulos = CobrosRotulo::latest()
            ->where('id_contribuyente', $rotulos->id_contribuyente)
            ->pluck('periodo_cobro_fin')
                ->first();

        if($ComprobandoPagoAlDiaRotulos == null){
            
           
                        if($ComprobandoPagoAlDiaRotulos == null){
                            $ComprobandoPagoAlDiaRotulos = $rotulos->fecha_apertura;
                       
                    
            }else{
                        $ComprobandoPagoAlDiaRotulos = $rotulos->fecha_apertura;
                        
                }

        }//** Comprobación de pago al dia se hace para reinciar las alertas avisos y notificaciones */

        log::info('comprobacion de pago:' .$ComprobandoPagoAlDiaRotulos);

        $alerta_notificacion_rotulo = alertas_detalle_rotulos::where('id_contribuyente', $rotulos->id_contribuyente)
        ->where('id_alerta','2')
        ->pluck('cantidad')
        ->first();
    
        $alerta_aviso_rotulo = alertas_detalle_rotulos::where('id_contribuyente', $rotulos->id_contribuyente)
        ->where('id_alerta','1')
        ->pluck('cantidad')
        ->first();
    
        if($alerta_aviso_rotulo == null)
        {           
                $alerta_aviso_rotulo = 0;

            }else{
    
                    if($ComprobandoPagoAlDiaRotulos >= $fechahoy)  
                    {
                       
                        $alerta_aviso_rotulo=0;

                        alertas_detalle_rotulos::where('id_contribuyente', $rotulos->id_contribuyente)
                        ->where('id_alerta','1')
                        ->update([
                                    'cantidad' => $alerta_aviso_rotulo,              
                                ]);   
    
                    }else{
                            $alerta_aviso_rotulo = $alerta_aviso_rotulo;
                        }
                }
    
        if($alerta_notificacion_rotulo == null)
        {
            $alerta_notificacion_rotulo = 0;

        }else{
                if($ComprobandoPagoAlDiaRotulos >= $fechahoy)  
                {
                    $alerta_notificacion_rotulo=0;
                    alertas_detalle_rotulos::where('id_contribuyente', $rotulos->id_contribuyente)
                    ->where('id_alerta','2')
                    ->update([
                                'cantidad' => $alerta_notificacion_rotulo,              
                            ]); 
    
                }else{
                         $alerta_notificacion_bus = $alerta_notificacion_rotulo;
                     }
            }
    
            
            if($ComprobandoPagoAlDiaRotulos > 0) 
            {
                //** Si NoNotificar vale 1 entonces NO SE DEBE imprimir una notificación ni avisos*/Esta al dia
                $NoNotificarRotulo = 1;
                log::info('NoNotificar:' .$NoNotificarRotulo);

            }else{  
                //** Si NoNotificar vale 0 entonces es permitido imprimir una notificación o avisos*/
                $NoNotificarRotulo = 0;
                log::info('NoNotificar:' .$NoNotificarRotulo);
            } 
         


           //******************* Determinando si una empresa esta en mora  *******************/

           log::info('|°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°|');
           $f3=carbon::now()->format('Y-m-d');
           $f1=Carbon::parse($ComprobandoPagoAlDiaRotulos);
           Log::info('f1 es el ultimo pago: '.$f1);
           Log::info('f3 fecha actual: '.$f3);
   
           //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
           $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
           Log::info('UltimoDiaMes: '.$UltimoDiaMes);
           $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(60)->format('Y-m-d');
   
   
           $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
           //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
           Log::info('inicio Moratorio aqui: '.$FechaDeInicioMoratorio);
   
           if($FechaDeInicioMoratorio->lt($f3)){
                   $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
                   Log::info('Cantidad de dias de insteres moratorio: '.$DiasinteresMoratorio);
                   Log::info('No entro al else');
           }else{
                   $DiasinteresMoratorio=0;
                   Log::info('Cantidad de dias de interes moratorio: '.$DiasinteresMoratorio);
                   Log::info('Entro al else');
               }
   
                        if($DiasinteresMoratorio > 0) 
                        {
                           $estado_de_solvencia = 1;//Si es 1 esta en Mora
                           
                        }else{
                            $estado_de_solvencia = 0;//Si es 0 esta Solvente
                        } 
                       Log::info('Era empresa y el estado de solvencia es: '.$estado_de_solvencia);
           
   
   //******************* FIN - Determinando si una empresa o matricula esta en mora  *******************/
   

        return view('backend.admin.RotulosDetalle.vistaRotulos', compact('rotulos','id_rotulos_detalle',
                                            'calificacionRotulos',
                                            'detectorNull','fechahoy',
                                          
                                            'alerta_aviso_rotulo',                                                        
                                            'alerta_notificacion_rotulo', 
                                            'NoNotificarRotulo',
                                            'Tasainteres',
                                            'ultimoCobroRotulos',
                                            'DiasinteresMoratorio',
                                            'estado_de_solvencia',
                                            ));
        
    }


    public function calificacionRotulo($id_rotulos_detalle)
    {

        $rotulo = RotulosDetalle::ALL();


        $rotulosEspecificos = RotulosDetalleEspecifico::join('rotulos_detalle','rotulos_detalle_especifico.id_rotulos_detalle','rotulos_detalle.id')

        ->select('rotulos_detalle_especifico.id','rotulos_detalle_especifico.id_rotulos_detalle', 'rotulos_detalle_especifico.nombre','rotulos_detalle_especifico.medidas',
        'rotulos_detalle_especifico.total_medidas','rotulos_detalle_especifico.caras','rotulos_detalle_especifico.tarifa',
        'rotulos_detalle_especifico.total_tarifa','rotulos_detalle_especifico.coordenadas_geo','rotulos_detalle_especifico.foto_rotulo',
        
        'rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
        'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
        'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',)

        ->where('id_rotulos_detalle', $id_rotulos_detalle) 
        ->get();

        //log::info($rotulosEspecificos);
     

        $rotulos = RotulosDetalle::join('contribuyente', 'rotulos_detalle.id_contribuyente','contribuyente.id')
        ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo', 'estado_rotulo.id')

        ->select('rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
        'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
        'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
        
        'contribuyente.id as id_contribuyente', 'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
        'estado_rotulo.id','estado_rotulo.estado')

        ->find($id_rotulos_detalle);

        //log::info($rotulos);
      
       foreach($rotulo as $dato)
       {
        
            $fondo_fiesta = 0.05;
            $cantidad_rotulos = '';
            $suma_tarifa = '';
            $tarifaSinF = '';
            $tarifa_total = '';
            $tarifaaño = '';
            $tarifa_total_año = '';
            $tarifat_sinF = '';
            $id_especifico = '';

                foreach($rotulosEspecificos as $especifico)
                {
                    $tarifaSinF = $especifico->tarifa;
                    $cantidad_rotulos = $especifico->cantidad_rotulos;
                   
                }
           
                $id_especifico = $especifico->id;
                $suma_tarifa = (round($tarifaSinF * $cantidad_rotulos,2));
                $tarifaaño = (round($suma_tarifa * 12,2));
                $tarifa_total = (round($suma_tarifa +( $suma_tarifa *$fondo_fiesta),2));
                $tarifat_sinF = (round($tarifa_total * 12,2));
                $tarifa_total_año = (round($tarifa_total * 12,2));

       }
        //log::info($rotulo);
        log::info($suma_tarifa);
    

        return view('backend.admin.RotulosDetalle.CalificacionRotulos', compact('rotulos','rotulosEspecificos','id_rotulos_detalle',
                                            'rotulo','suma_tarifa','tarifa_total','tarifaaño','tarifa_total_año',
                                            'tarifat_sinF','id_especifico',
                                        ));

    }

    public function tablaCalificacionRotulo ($id_rotulos_detalle)
    {
     
        log::info('id_rotulos_detalle ' . $id_rotulos_detalle);

        $rotulo = RotulosDetalle::ALL();
        
        $rotulos = RotulosDetalle::where('id', $id_rotulos_detalle)->first();

        //log::info('rotulos ' . $rotulos);
       
        $rotulosEspecificos = RotulosDetalleEspecifico::join('rotulos_detalle','rotulos_detalle_especifico.id_rotulos_detalle','rotulos_detalle.id')

        ->select('rotulos_detalle_especifico.id','rotulos_detalle_especifico.id_rotulos_detalle', 'rotulos_detalle_especifico.nombre','rotulos_detalle_especifico.medidas',
        'rotulos_detalle_especifico.total_medidas','rotulos_detalle_especifico.caras','rotulos_detalle_especifico.tarifa as tarifa',
        'rotulos_detalle_especifico.total_tarifa as mensual','rotulos_detalle_especifico.coordenadas_geo','rotulos_detalle_especifico.foto_rotulo',
        
        'rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha as ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos as cantidad',
        'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
        'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',)

        ->where('id_rotulos_detalle', $id_rotulos_detalle)
        ->get();

        log::info('especificos ' . $rotulosEspecificos);
      

        $rotulos = RotulosDetalle::join('contribuyente', 'rotulos_detalle.id_contribuyente','contribuyente.id')
        ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo', 'estado_rotulo.id')

        ->select('rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
        'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
        'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
        
        'contribuyente.id as id_contribuyente', 'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
        'estado_rotulo.id','estado_rotulo.estado')
      
        ->get();

        //log::info('rotulos '. $rotulos);
      
        return view('backend.admin.RotulosDetalle.tabla.tabla_calificacion_rotulo', compact('rotulos','rotulosEspecificos',
                                        'id_rotulos_detalle',
                                        'rotulo'));
    }

    public function GuardarCalificacionRotulo(Request $request)
    {
         
        $fecha_calificacion = $request->fechacalificar;
        $estado_calificacion = $request->estado_calificacion;
        $id_contribuyente = $request->id_contribuyente;
        $id_rotulos_detalle = $request->id_rotulos_detalle;
        $ficha = $request->ficha;
        $id_rotulos_detalle_especifico = $request->id_rotulos_detalle_especifico;

        log::info('fecha calificacion ' . $fecha_calificacion);
        log::info('estado calificacion ' . $estado_calificacion);
        log::info('id contribuyente ' . $id_contribuyente);
        log::info('id rotulos detalle ' . $id_rotulos_detalle);
        log::info('ficha ' . $ficha);
        log::info('id especificos ' . $id_rotulos_detalle_especifico);
     

        $rotulos=RotulosDetalle::select('cantidad_rotulos','estado_especificacion')
        ->where('id', $id_rotulos_detalle)
        ->latest()->first();

        $especifico = RotulosDetalleEspecifico::select('tarifa','total_tarifa')
        ->where('id_rotulos_detalle', $id_rotulos_detalle)      
        ->latest()->first();
        
       
        log::info('rotulos' . $rotulos);
        log::info('especifico' . $especifico);
    
    
        $dt = new CalificacionRotuloDetalle();
        $dt->id_rotulos_detalle = $request->id_rotulos_detalle;
        $dt->id_rotulos_detalle_especifico = $request->id_rotulos_detalle_especifico;
        $dt->id_contribuyente = $request->id_contribuyente;
        $dt->fecha_calificacion = $request->fechacalificar; 
        $dt->nFicha = $request->ficha;          
        $dt->cantidad_rotulos = $rotulos->cantidad_rotulos;   
        $dt->monto = $especifico->tarifa;
        $dt->pago_mensual = $especifico->total_tarifa;
        $dt->estado_calificacion = $request->estado_calificacion;
        $dt->save();
        
        if($dt->save())      
        {
            return ['success' => 1];    
        }
            return;
    }

    public function cobrosRotulos($id_rotulos_detalle)
    {
        log::info('id ' . $id_rotulos_detalle);
        
        $tasasDeInteres = Interes::select('monto_interes')
        ->orderby('id','desc')
        ->get();
        
        $date=Carbon::now()->toDateString(); 

        $rotulo = RotulosDetalle::where('id', $id_rotulos_detalle)->first();

      
        $rotulos = RotulosDetalle::join('contribuyente', 'rotulos_detalle.id_contribuyente','contribuyente.id')
        ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo', 'estado_rotulo.id')

        ->select('rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
        'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
        'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
        
        'contribuyente.id as id_contribuyente', 'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
        'estado_rotulo.id','estado_rotulo.estado')
      
        ->find($id_rotulos_detalle);

        
          

        $rotulosEspecificos = RotulosDetalleEspecifico::join('rotulos_detalle','rotulos_detalle_especifico.id_rotulos_detalle','rotulos_detalle.id')

        ->select('rotulos_detalle_especifico.id','rotulos_detalle_especifico.id_rotulos_detalle', 'rotulos_detalle_especifico.nombre','rotulos_detalle_especifico.medidas',
        'rotulos_detalle_especifico.total_medidas','rotulos_detalle_especifico.caras','rotulos_detalle_especifico.tarifa as tarifa',
        'rotulos_detalle_especifico.total_tarifa as mensual','rotulos_detalle_especifico.coordenadas_geo','rotulos_detalle_especifico.foto_rotulo',
        
        'rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha as ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos as cantidad',
        'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
        'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',)

        ->where('id_rotulos_detalle', $id_rotulos_detalle)
        ->get();

        $ultimo_cobro = CobrosRotulo::latest()
        ->where('id_rotulos_detalle', $id_rotulos_detalle)
        ->first();

        $ListarCobros = CobrosRotulo::latest()
        ->get();

        $calificacionRotulos = CalificacionRotuloDetalle::
            select('calificacion_rotulo_detalle.id', 'calificacion_rotulo_detalle.fecha_calificacion','calificacion_rotulo_detalle.estado_calificacion','calificacion_rotulo_detalle.id_rotulos_detalle')
               
            ->where('id_rotulos_detalle', $id_rotulos_detalle)
            ->latest()
            ->first();


        if ($calificacionRotulos == null)
        { 
            $detectorNull=0;
            if ($ultimo_cobro == null)
            {
                $detectorNull=0;
                $detectorCobro=0;
                return view('backend.admin.RotulosDetalle.Cobros.cobrosRotulos', compact('rotulos','rotulo','detectorNull','detectorCobro','calificacionRotulos','ListarCobros'));
            }
        }
        else
        {  
            $detectorNull=1;
            if ($ultimo_cobro == null)
            {
             $detectorNull=0;
             $detectorCobro=0;
            return view('backend.admin.RotulosDetalle.Cobros.cobrosRotulos', compact('rotulos','rotulo','calificacionRotulos','rotulosEspecificos','tasasDeInteres','date','detectorNull','detectorCobro','ListarCobros'));
            }
            else
            {
                $detectorNull=1;
                $detectorCobro=1;
                  
            return view('backend.admin.RotulosDetalle.Cobros.cobrosRotulos', compact('rotulos','rotulo','calificacionRotulos','rotulosEspecificos','tasasDeInteres','date','detectorNull','detectorCobro','ultimo_cobro','ListarCobros'));
            }
          
        }    
    
    }

    public function calcularCobrosRotulo(Request $request)
    {
  
        $id_contribuyente = $request->id_contribuyente;
        $id=$request->id;
        $id_rotulos_detalle = $request->id_rotulos_detalle;
    
            
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

        $rotulos = RotulosDetalle::join('contribuyente', 'rotulos_detalle.id_contribuyente','contribuyente.id')
        ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo', 'estado_rotulo.id')

        ->select('rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
        'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
        'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
        
        'contribuyente.id as id_contribuyente', 'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
        'estado_rotulo.id','estado_rotulo.estado')
      
        ->find($id_rotulos_detalle);

     
        $calificacionRotulos = CalificacionRotuloDetalle::
            select('calificacion_rotulo_detalle.id', 'calificacion_rotulo_detalle.fecha_calificacion','calificacion_rotulo_detalle.estado_calificacion','calificacion_rotulo_detalle.id_rotulos_detalle')
               
            ->where('id_rotulos_detalle', $id_rotulos_detalle)
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

           
                $tarifas = CalificacionRotuloDetalle::select('monto')
                ->where('id_rotulos_detalle',$id
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

                    $cobro = new CobrosRotulo();
                    $cobro->id_contribuyente = $request->id_contribuyente;
                    $cobro->id_rotulos_detalle = $request->id;
                    $cobro->id_usuario = '1';
                    $cobro->cantidad_meses_cobro = $Cantidad_MesesTotal;
                    $cobro->tasa_servicio_mora_32201 = $impuestos_mora;
                    $cobro->impuestos = $impuesto_año_actual;
                    $cobro->intereses_moratorios_15302 = $InteresTotal;
                    $cobro->fondo_fiestasP_12114 = $fondoFPValor;
                    $cobro->pago_total = $totalPagoValor;
                    $cobro->fecha_cobro = $request->fecha_interesMoratorio;
                    $cobro->tipo_cobro = 'tasas';
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
                        'tarifa'=>$tarifas,
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