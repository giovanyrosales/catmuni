<?php

namespace App\Http\Controllers\Backend\RotulosDetalle;

use App\Http\Controllers\Backend\MatriculasDetalle\alert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Environment\Console;
use App\Models\Contribuyentes;
use App\Models\RotulosDetalle;
use App\Models\TraspasoBuses;
use App\Models\CobrosBuses;
use App\Models\EstadoBuses;
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
       
        for ($i = 0; $i < isset ($request->nombre) ? count($request->nombre) : 0; $i++){     

            log::info($request->nombre);

            if ($request->file('foto_rotulo')[$i]) {
              Log::info('siii');
            }else{
                Log::info('nooo');
            }


        }
        log::info('pppp');
        return 99;









        $especificada="especificada";
    

        $regla = array(
    
            'id_rotulos_detalle' => 'required',            
            
        );
        $validar = Validator::make($request->all(), $regla, 
       
    
    );
       
    if ($validar->fails()){

    return [

     'success'=> 0,

    'message' => $validar->errors()->first()

    ];
    }

    if ($request->file('foto_rotulo'))
    {
        
            $cadena = Str::random(15);
            $tiempo = microtime();
            $union = $cadena.$tiempo;
            $nombre = str_replace(' ', '_', $union);

            $extension = '.'.$request->foto_rotulo->getClientOriginalExtension();
            $nomImagen = $nombre.strtolower($extension);
            $avatar = $request->file('foto_rotulo');
            $estado = Storage::disk('archivos')->put($nomImagen, \File::get($avatar));

            if($estado)
            {

    
                for ($i = 0; $i < count($request->nombre); $i++) 
                {      
                    
                    $Bd = new RotulosDetalleEspecifico();
                    $Bd->id_rotulos_detalle = $request->id_rotulos_detalle;               
                    $Bd->nombre = $request->nombre[$i];
                    $Bd->medidas = $request->medidas[$i];
                    $Bd->total_medidas=$request->total_medidas[$i];
                    $Bd->caras = $request->caras[$i];
                    $Bd->tarifa = $request->tarifa[$i];
                    $Bd->total_tarifa = $request->total_tarifa[$i];
                    $Bd->coordenadas_geo = $request->coordenadas_geo[$i];
                    $Bd->foto_rotulo = $request->$nomImagen[$i];
                    
                    $Bd->save();
                
                }   
           
            RotulosDetalle::where('id', $request->id_rotulos_detalle)
            ->update([
                        'estado_especificacion' =>$especificada,               
                    ]);
                    
                
                    return ['success' => 1];
            }
        }else
            {
                return ['success' => 2];
            }        
    } 
}