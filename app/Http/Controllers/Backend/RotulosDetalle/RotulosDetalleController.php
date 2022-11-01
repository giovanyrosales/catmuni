<?php

namespace App\Http\Controllers\Backend\RotulosDetalle;

use App\Http\Controllers\Backend\MatriculasDetalle\alert;
use App\Http\Controllers\Controller;
use App\Models\CalificacionRotuloDetalle;
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

        if ($calificacionRotulos == null)
        {
           $detectorNull = 0;
        }
            else
            {
                $detectorNull = 1;
            }
        
    

        return view('backend.admin.RotulosDetalle.vistaRotulos', compact('rotulos','id_rotulos_detalle',
                                            'calificacionRotulos',
                                            'detectorNull'));
        
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

                foreach($rotulosEspecificos as $especifico)
                {
                    $tarifaSinF = $especifico->tarifa;
                    $cantidad_rotulos = $especifico->cantidad_rotulos;
                }
           
        
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
                                            'tarifat_sinF'
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

        log::info('fecha calificacion ' . $fecha_calificacion);
        log::info('estado calificacion ' . $estado_calificacion);
        log::info('id contribuyente ' . $id_contribuyente);
        log::info('id rotulos detalle ' . $id_rotulos_detalle);
        log::info('ficha ' . $ficha);

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


}