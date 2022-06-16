<?php

namespace App\Http\Controllers\Backend\Rotulos;

use App\Http\Controllers\Controller;
use App\Models\CalificacionRotulo;
use App\Models\CierresReaperturasRotulos;
use App\Models\CobrosRotulo;
use App\Models\Contribuyentes;
use App\Models\InspeccionRotulos;
use App\Models\Rotulos;
use App\Models\Empresas;
use App\Models\EstadoRotulos;
use App\Models\TarifaRotulo;
use App\Models\Interes;
use App\Models\MultasDetalle;
use App\Models\InteresDetalle;
use App\Models\TraspasosRotulos;
use App\Models\Usuario;
use CreateCalificacionTable;
use Illuminate\Http\Request;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;
use Symfony\Contracts\Service\Attribute\Required;
use Whoops\Run;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\isEmpty;
use DateInterval;
use DatePeriod;
use Carbon\Carbon;

class RotulosController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
 
    //Agregar nuevo rótulo
    public function crearRotulos()
    {
        $idusuario = Auth::id();
        $infouser = Usuario::where('id', $idusuario)->first();
        $empresas = Empresas::ALL();

      return view('backend.admin.Rotulos.CrearRotulos', compact('empresas'));

    }

    //Agregar Rótulo
    public function nuevoRotulo(Request $request){

        $regla = array(
    
            'nom_rotulo' => 'required',            
            'permiso_instalacion' => 'required',
            'fecha_apertura' => 'required',
            'actividad_economica' => 'required',          
            'estado' => 'required'
        );
    
        $validar = Validator::make($request->all(), $regla, 
       
    
        );
           
        if ($validar->fails()){
    
        return [
    
         'success'=> 0,
    
        'message' => $validar->errors()->first()
    
        ];
        }
    
        if($request->hasFile('imagen')){
            $cadena = Str::random(15);
            $tiempo = microtime();
            $union = $cadena.$tiempo;
            $nombre = str_replace(' ', '_', $union);

            $extension = '.'.$request->imagen->getClientOriginalExtension();
            $nomImagen = $nombre.strtolower($extension);
            $avatar = $request->file('imagen');
            $estado = Storage::disk('archivos')->put($nomImagen, \File::get($avatar));

            if($estado){
                
                $dato = new Rotulos();
                $dato->id_empresa = $request->empresa;
                $dato->nom_rotulo = $request->nom_rotulo;
                $dato->direccion = $request->direccion;
                $dato->fecha_apertura = $request->fecha_apertura;
                $dato->actividad_economica = $request->actividad_economica;
                $dato->permiso_instalacion = $request->permiso_instalacion;
                $dato->medidas = $request->medidas;
                $dato->total_medidas = $request->total_medidas;
                $dato->total_caras = $request->total_caras;
                $dato->coordenadas = $request->coordenadas;
                $dato->imagen = $nomImagen;
                $dato->nom_inspeccion = $request->nom_inspeccion;
                $dato->cargo_inspeccion = $request->cargo_inspeccion;

                if($dato->save()){

                    return ['success' => 1];
                }else{return ['success' => 2];}
            }else{
                return ['success' => 2];
            }
     
      
        }else 
        {
     
          
        $dato = new Rotulos();
        $dato->id_empresa = $request->empresa;
        $dato->fecha_apertura = $request->fecha_apertura;
        $dato->direccion = $request->direccion;
        $dato->nom_rotulo = $request->nom_rotulo;
        $dato->actividad_economica = $request->actividad_economica;
        $dato->permiso_instalacion = $request->permiso_instalacion;
        $dato->medidas = $request->medidas;
        $dato->total_medidas = $request->total_medidas;
        $dato->total_caras = $request->total_caras;
        $dato->coordenadas = $request->coordenadas;
        $dato->nom_inspeccion = $request->nom_inspeccion;
        $dato->cargo_inspeccion = $request->cargo_inspeccion;
      
      
        if($dato->save()){

            return ['success' => 1];
        }else{return ['success' => 2];}

         }
    }
    //Termina registrar rotulo

    //Función Tabla Rótulos
    public function tablaRotulos(Rotulos $lista){

             
        $lista=Rotulos::join('empresa','rotulos.id_empresa','=','empresa.id')
                     
                      
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura','rotulos.permiso_instalacion','rotulos.medidas',
        'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.direccion','rotulos.cargo_inspeccion','rotulos.coordenadas','rotulos.imagen',
        'empresa.nombre as empresas')
        ->get();
    
      
        return view('backend.admin.Rotulos.tabla.tablalistarotulos', compact('lista'));
    }
    //Termina función tabla Rótulos

    //Función Listar Rótulos
    public function listarRotulos()
    {
   
        $empresas = Empresas::All();
     
        return view('backend.admin.Rotulos.ListarRotulos', compact('empresas'));
    }
    //Termina función Listar Rótulos

    //Ver informacón del rótulo
    public function informacionRotulo(Request $request)
      {
       
        $regla = array(
            'id' => 'required',
        
    );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if ($lista = Rotulos::where('id', $request->id)->first())
           {
          
            $empresas = Empresas::orderby('nombre')->get();
                
                return['success' => 1,
       
                'rotulos' => $lista,             
                'id_empre' => $lista->id_empresa,
                'empresa' => $empresas,
                 ];
           }
            else
            {
                return ['success' => 2];
                
            }

           
        
    }
    //Termina funcion para ver informacion del rótulo

    //Función para editar rótulos
    public function editarRotulos(Request $request)
    {
        log::info($request->all());
           
        $regla = array(  
            'nom_rotulo' => 'required',
            'actividad_economica' => 'required',
            'direccion' => 'required',
            'fecha_apertura' => 'required',           
            'permiso_instalacion' => 'required',
            'medidas' => 'required',
            'total_medidas' => 'required',
            'total_caras' => 'required',
            'empresa' => 'required',
            'coordenadas' => 'required',
            'cargo_inspeccion' => 'required',
            

            
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];} 

        DB::beginTransaction();

        try {

        if($data = Rotulos::where('id', $request->id)->first()){

            if($request->hasFile('imagen')){
             
                $imagenOld = $data->imagen;

                $cadena = Str::random(15);
                $tiempo = microtime();
                $union = $cadena.$tiempo;
                $nombre = str_replace(' ', '_', $union);

                $extension = '.'.$request->imagen->getClientOriginalExtension();
                $nomImagen = $nombre.strtolower($extension);
                $avatar = $request->file('imagen');
                $estado = Storage::disk('archivos')->put($nomImagen, \File::get($avatar));

                     if($estado)
                        {

                        Rotulos::where('id', $request->id)->update([
                            
                            'id_empresa' => $request->empresa,
                            'nom_rotulo' => $request->nom_rotulo,
                            'actividad_economica' => $request->actividad_economica,
                            'direccion' => $request->direccion,
                            'fecha_apertura' => $request->fecha_apertura,
                            'permiso_instalacion'=> $request->permiso_instalacion,
                            'medidas'=> $request->medidas,   
                            'total_medidas' => $request->total_medidas,
                            'total_caras' => $request->total_caras,         
                            'coordenadas' => $request->coordenadas, 
                            'nom_inspeccion' => $request->nom_inspeccion,
                            'cargo_inspeccion' => $request->cargo_inspeccion,
                            'imagen' => $nomImagen,
                    
                           
                        ]);
                    

                            if(Storage::disk('archivos')->exists($imagenOld)){
                                Storage::disk('archivos')->delete($imagenOld);
                            }

                           //**Al actualizar datos del rótulo se debera calificar para una nueva tarifa */
                                CalificacionRotulo::where('id_empresa', $request->empresa)     
                                ->delete();
                           //**Termina borrar calificación del rótulo */

                        }
                       
                        DB::commit();
                            return ['success' => 1];
        }else{
            Rotulos::where('id', $request->id)->update([
                   
                'id_empresa' => $request->empresa,
                'nom_rotulo' => $request->nom_rotulo,
                'actividad_economica' => $request->actividad_economica,
                'direccion' => $request->direccion,
                'fecha_apertura' => $request->fecha_apertura,
                'permiso_instalacion'=> $request->permiso_instalacion,
                'medidas'=> $request->medidas,   
                'total_medidas' => $request->total_medidas,
                'total_caras' => $request->total_caras,         
                'coordenadas' => $request->coordenadas, 
                'nom_inspeccion' => $request->nom_inspeccion,
                'cargo_inspeccion' => $request->cargo_inspeccion,
               
            ]);

            //**Al actualizar datos del rótulo se debera calificar para una nueva tarifa */
            CalificacionRotulo::where('id_empresa', $request->empresa)     
            ->delete();
            //**Termina borrar calificación del rótulo */

            DB::commit();
            return ['success' => 1];
        }

     

    }   else 
        {
        return ['success' => 2];
 
        }
        }  
        catch(\Throwable $e){
            DB::rollback();    

            return ['success' => 3];
    }
}
    //Termina función para editar rótulos

    //Función para eliminar rótulos
    public function eliminarRotulo(Request $request)
    {

        $tasa = Rotulos::find($request->id);
        $tasa->delete();
             
            return ['success' => 1];

    }
    //Termina función para eliminar rótulos

    //Función vista detallada
    public function showRotulos($id)
    {
        $contribuyentes = Contribuyentes::All();
        $empresas = Empresas::ALL();
         
      
        $lista=Rotulos::join('empresa','rotulos.id_empresa','=','empresa.id')
                                            
                      
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura','rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
        'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion','rotulos.coordenadas','rotulos.imagen',
        'empresa.nombre as empresas')
        ->find($id);
    

        $calificacion = CalificacionRotulo::latest()
            ->join('rotulos','calificacion_rotulo.id_rotulos','=','rotulos.id')
    
        ->select('calificacion_rotulo.id','calificacion_rotulo.tarifa_mensual','calificacion_rotulo.total_impuesto','calificacion_rotulo.fecha_calificacion','calificacion_rotulo.estado_calificacion',
        'rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura','rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
        'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion','rotulos.coordenadas','rotulos.imagen',)
        ->where('id_rotulos', "=", "$id")
        ->first();


        /*
        $rotulo= Rotulos
                ::join('contribuyente','rotulos.id_contribuyente','=','contribuyente.id')
                ->join('empresa','rotulos.id_empresa','=','empresa.id')
        
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.direccion','rotulos.fecha_apertura','rotulos.permiso_instalacion','rotulos.medidas','rotulos.num_tarjeta','rotulos.estado',
        'contribuyente.nombre as contribuyente','contribuyente.apellido',
        'empresa.nombre as empresa')
        ->find($id);
        */
  

            if ($calificacion == null)
              {
                $detectorNull = 0;
              }

                else
                {
                $detectorNull = 1;
                }
              

        return view('backend.admin.Rotulos.vistaRotulos', compact('id','lista','contribuyentes','empresas','calificacion','detectorNull'));

    }
    //Termina vista detallada
    
    public function infoCierre(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($lista = Rotulos::where('id', $request->id)->first()){
           
            $empresa = Empresas::orderBy('nombre')->get();
            $estadorotulo = EstadoRotulos::orderBy('estado')->get();
            return ['success' => 1,
            'idcont' => $lista->id_contribuyente,
            'empresa' => $empresa,
            'rotulos' => $lista,
            'estadorotulos' => $estadorotulo,

            
        ];
    }else{
        return ['success' => 2];
    }
    }

    public function nuevoEstadoR(Request $request)
    {
  
        $regla = array(  
            'id' => 'required',
            'estado' => 'required', 
            'fecha_cierre' => 'required'
           
        );
      
        $validar = Validator::make($request->all(), $regla,
      
        );

        if ($validar->fails()){ 
            return ['success' => 0,
            'message' => $validar->errors()->first()
        ];
        }
        if(Rotulos::where('id', $request->id)->first()){

            Rotulos::where('id', $request->id)->update([
                
             
                 'estado' => $request->estado,
                 'fecha_cierre' => $request->fecha_cierre,

                 
         ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function traspasoR (Request $request)
    {
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
    if (Rotulos::where('id', $request->id)->first())
    {
        Rotulos::where('id', $request->id)->update([
            
            'id_contribuyente' => $request->contribuyente,
        ]);

        return ['success' => 1];
    }
        else{
            return ['success' => 2];
        }
    }


    public function calificacionRotulo ($id)
    {
        $contribuyente = Contribuyentes::ALL();
        $empresa = Empresas::ALL();
        $rotulo = Rotulos::ALL();
       
        $contribuyente = Contribuyentes::orderBy('id', 'ASC')->get();

        $rotulo = Rotulos::where ('id', $id)->first();

        $contri = ' ';
        $emp = '';
        $emp1 = '';
        $emp2 = '';
      
     

        if ($empresa = Empresas::where('id', $rotulo->id_empresa)->first())
        {
           

           
            $emp = $empresa->nombre;
            $emp1 = $empresa->direccion;
            $emp2  = $empresa->contribuyente;
            
        
        }
        $empresa = Rotulos::where('id', $id)->first();
     
        $calificacion=Rotulos::join('empresa','rotulos.id_empresa','=','empresa.id')
                                
        //Consulta para mostrar los rótulos que pertenecen a una sola empresa
                      
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura',
        'rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
        'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion',
        'rotulos.coordenadas','rotulos.imagen',
        'empresa.nombre as empresas')
        
        ->where('id_empresa', $empresa->id_empresa)
        ->get();

        $tRotulo = TarifaRotulo::orderBy('id', 'ASC')->get();          
    
        $total1 = 0;
        $totalanual = 0;
        $totalA = 0;
        $total = 0;
        $monto_tarifa = 0;
        $total_medidas = 0;
        $fondoF = 0.05;
        $total_impuesto = 0;
       
//Calculo de la calificación de rótulos
    foreach ($calificacion as $dato)
    {
        $tarifa_mensual = 0;
        
        foreach($tRotulo as $tarifa)
        {
           if ($dato->total_medidas >= $tarifa->limite_inferior && $dato->total_medidas <= $tarifa->limite_superior)
           {
                $tarifa_mensual = $tarifa->monto_tarifa; 
                    
                    if($dato->total_caras >1)
                    {
                        $tarifa_mensual = $tarifa_mensual * $dato->total_caras;
                    }
                 
            break;         

           }  
          
           else if($dato->total_medidas > 8)
            {
                $tarifa_mensual = $dato->total_medidas;

                    if($dato->total_caras >1)
                    {
                        $tarifa_mensual = $tarifa_mensual * $dato->total_caras;
                    }
            break;
          
            }

        }

        $total = $total + $tarifa_mensual;
        $dato->monto = $tarifa_mensual;
        $dato->total_impuesto = round($total + ($total * $fondoF),2);
        $totalA = round($dato->total_impuesto *12);
        $total1 = round(($total * 12),2);
        

    }
  
 
        return view('backend.admin.Rotulos.CalificacionRotulo', compact('id','rotulo','tarifa','totalA','totalanual','total','total1','emp','emp1','emp2','contribuyente', 'empresa','calificacion'));
    }

//Función para tabla de calificacion de rótulos

    public function tablaCalificacionR($id)
    {
        $empresa = Rotulos::where('id', $id)->first();
     
        $calificacion=Rotulos::join('empresa','rotulos.id_empresa','=','empresa.id')
                                
//Consulta para mostrar los rótulos que pertenecen a una sola empresa
                      
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura',
        'rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
        'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion',
        'rotulos.coordenadas','rotulos.imagen',
        'empresa.nombre as empresas')
        
        ->where('id_empresa', $empresa->id_empresa)
        ->get();

 //Termina consulta para mostrar los rótulos que pertenecen a una sola empresa

    $tRotulo = TarifaRotulo::orderBy('id', 'ASC')->get();          
      
        $monto_tarifa = 0;
        $total_medidas = 0;
        $fondoF = 0.05;
        $total = 0;
        $total_impuesto = 0;
       
//Calculo de la calificación de rótulos
    foreach ($calificacion as $dato)
    {
        $tarifa_mensual = 0;
        
        foreach($tRotulo as $tarifa)
        {
           if ($dato->total_medidas >= $tarifa->limite_inferior && $dato->total_medidas <= $tarifa->limite_superior)
           {
                $tarifa_mensual = $tarifa->monto_tarifa; 
               
                    if($dato->total_caras >1)
                    {
                        $tarifa_mensual = $tarifa_mensual * $dato->total_caras;
                    }
               break;

              
           }  
          
           else if($dato->total_medidas > 8)
           {
                $tarifa_mensual = $dato->total_medidas;
                    if($dato->total_caras >1)
                    {
                        $tarifa_mensual = $tarifa_mensual * $dato->total_caras;
                    }
               break;
           }
          
        }

        $dato->monto = $tarifa_mensual;
        $dato->total_impuesto	 = round($tarifa_mensual + ($tarifa_mensual * $fondoF),2);
        
    }
  
        return view('backend.admin.Rotulos.tabla.tablarotulo', compact('calificacion','total_impuesto','tarifa_mensual','dato'));
         
    }
    //Termica calculo de la calificación de rótulos
   


    public function guardarCalificacion(Request $request)
    {    
        
        $fecha_calificacion = $request->fechacalificar;
        $estado_calificaion =  $request->estado_calificacion;
        $id_rotulo = $request->id_rotulos;
        $id_empresa = $request->id_empresa;
       // $id_empresa = Rotulos::select('id_empresa')->where('id',$id_rotulo)->first();

 
        log::info($fecha_calificacion);
        log::info($estado_calificaion);
        log::info($id_rotulo);
        log::info($id_empresa);
      
        $tRotulo = TarifaRotulo::orderBy('id', 'ASC')->get();    

        log::info($id_empresa);
        log::info($tRotulo);
        
   // log::info($tRotulo);
        
            $fondoF = 0.05;
            $total_impuesto = 0;
            $tarifa_mensual = 0;
  
      
        $calificacion=Rotulos::join('empresa','rotulos.id_empresa','=','empresa.id')
                                         
                    ->select('rotulos.id','rotulos.nom_rotulo',            
                    'rotulos.total_medidas', 'rotulos.total_caras',                 
                    'empresa.nombre as empresas')
                    
                    ->where('id_empresa', $id_empresa)
                    ->get();
            
                    log::info($calificacion);
                  $contador=0;
//Calculo de la calificación de rótulos
foreach ($calificacion as $dato)
{
    
   
   foreach($tRotulo as $tarifa)
   {
      if ($dato->total_medidas >= $tarifa->limite_inferior && $dato->total_medidas <= $tarifa->limite_superior)
      {
           $tarifa_mensual = $tarifa->monto_tarifa; 
              
               if($dato->total_caras >1)
               {
                   $tarifa_mensual = $tarifa->monto_tarifa * $dato->total_caras;
               }           
         
      }  
     
      else if($dato->total_medidas > 8)
       {
           $tarifa_mensual = $dato->total_medidas;

               if($dato->total_caras >1)
               {
                   $tarifa_mensual = $tarifa_mensual * $dato->total_caras;
               }   
         
       }
    
   }

        $tarifa->tarifa_mensual = $tarifa_mensual;
        $tarifa->total_impuesto	 = round($tarifa_mensual + ($tarifa_mensual * $fondoF),2);

        
        log::info($tarifa_mensual);
        
        $contador=$contador+1;
        log::info('contador igual a:'.$contador);

        $dt = new CalificacionRotulo();
        $dt->id_rotulos = $dato->id;
        $dt->id_empresa = $request->id_empresa;
        $dt->fecha_calificacion = $request->fechacalificar;
        $dt->estado_calificacion = $request->estado_calificacion;
        $dt->tarifa_mensual = $tarifa->tarifa_mensual;
        $dt->total_impuesto = $tarifa->total_impuesto;
        $dt->save();

        }       
        
            return ['success' => 1];
 
    
    }

    public function cobros($id)
    {
        $tasasDeInteres = Interes::select('monto_interes')
        ->orderby('id','desc')
        ->get();
        
        $date=Carbon::now()->toDateString();

        $empresa = Rotulos::where('id', $id)->first();
     
        $calificacion=Rotulos::join('empresa','rotulos.id_empresa','=','empresa.id')
                                
//Consulta para mostrar los rótulos que pertenecen a una sola empresa
                      
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura',
        'rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
        'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion',
        'rotulos.coordenadas','rotulos.imagen',
        'empresa.nombre as empresas')
        
        ->where('id_empresa', $empresa->id_empresa)
        ->get();

 //Termina consulta para mostrar los rótulos que pertenecen a una sola empresa

        $rotulo = Rotulos::join('empresa','rotulos.id_empresa','=','empresa.id')
        ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
                           
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura',
        'rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
        'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion',
        'rotulos.coordenadas','rotulos.imagen',
        'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica','actividad_economica.mora',
        'empresa.nombre as empresas')
  
        ->find($id);

        $ultimo_cobro = CobrosRotulo::latest()
        ->where('id_rotulos', "=", "$id")
        ->first();


        $calificacion = CalificacionRotulo::latest()
        ->join('rotulos', 'calificacion_rotulo.id_rotulos', '=', 'rotulos.id')

        ->select('calificacion_rotulo.id', 'calificacion_rotulo.tarifa_mensual', 'calificacion_rotulo.total_impuesto', 'calificacion_rotulo.fecha_calificacion', 'calificacion_rotulo.estado_calificacion',
        'rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura',
        'rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
        'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion',
        'rotulos.coordenadas','rotulos.imagen')
        ->first();

        
        if ($calificacion == null)
        { 
            $detectorNull=0;
            if ($ultimo_cobro == null)
            {
                $detectorNull=0;
                $detectorCobro=0;
                return view('backend.admin.Rotulos.Cobros.CobroRotulo', compact('detectorNull','detectorCobro'));
            }
        }
        else
        {  
            $detectorNull=1;
            if ($ultimo_cobro == null)
            {
             $detectorNull=0;
             $detectorCobro=0;
            return view('backend.admin.Rotulos.Cobros.CobroRotulo', compact('rotulo','empresa','date','ultimo_cobro','calificacion','detectorNull','detectorCobro','tasasDeInteres',));
            }
            else
            {
                $detectorNull=1;
                $detectorCobro=1;
                return view('backend.admin.Rotulos.Cobros.CobroRotulo', compact('rotulo','empresa','date','ultimo_cobro','calificacion','detectorNull','date','detectorCobro','tasasDeInteres'));
            }
        }
    
       // return view('backend.admin.Rotulos.Cobros.CobroRotulo', compact('rotulo','calificacion','empresa','tasasDeInteres','date'));
    }

    public function calcularCobros(Request $request)
    {
  
        $id_empresa = $request->id_empresa;
        $id=$request->id; //* ID del rótulo.
        $id_rotulo = $request->id_rotulos;
    
            
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
            $InicioPeriodo=Carbon::parse($f1)->addMonthsNoOverflow(1)->day(1)->format('Y-m-d');
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
        $Tasainteres=Interes::latest()
        ->pluck('monto_interes')
            ->first();
        //** Finaliza - Para obtener la tasa de interes más reciente */

        $calificaciones = CalificacionRotulo::latest()
            ->join('rotulos','calificacion_rotulo.id_rotulos','=','rotulos.id')
    
        ->select('calificacion_rotulo.id','calificacion_rotulo.tarifa_mensual','calificacion_rotulo.total_impuesto','calificacion_rotulo.fecha_calificacion','calificacion_rotulo.estado_calificacion',
        'rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura','rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
        'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion','rotulos.coordenadas','rotulos.imagen',)
        ->where('id_rotulos', "=", "$id")
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

           
                $tarifas=CalificacionRotulo::select('tarifa_mensual')
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

                $cobro = new CobrosRotulo();
                $cobro->id_empresa = $request->id_empresa;
                $cobro->id_rotulos = $request->id_rotulos;
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

    public function cierres_traspasos_rotulo($id){
        
        $idusuario = Auth::id();
        $infouser = Usuario::where('id', $idusuario)->first();
        $estadorotulos = EstadoRotulos::All();
        $contribuyentes = Contribuyentes::All();
        $ConsultaEmpresa = Empresas::All();
        $rotulos = Rotulos::ALL();

        $rotulo= Rotulos
        ::join('empresa','rotulos.id_empresa','=','rotulos.id')
        ->join('estado_rotulo','rotulos.id_estado_rotulo','=','estado_rotulo.id')      
        
        ->select( 'rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura','rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
        'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion','rotulos.coordenadas','rotulos.imagen',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
        'estado_rotulo.estado',)
        ->where('rotulos.id',$id)
        ->first();

        $Consul_traspasos_r=TraspasosRotulos::latest()
        ->where('id_empresa',$id)
        ->first();

        $Consul_cierres=CierresReaperturasRotulos::latest()
        ->where('id_empresa',$id)
        ->first();

        if($Consul_traspasos_r===null){
            $Consul_traspasos_r=0;
            }
        else
            {$Consul_traspasos_r=1;
            }  
        
        if($Consul_cierres===null){
                $Consul_cierres=0;
                }
        else
                {
                    $Consul_cierres=1;
                }

        return view('backend.admin.Empresas.CierresTraspasos.Cierres_traspasos',
                compact(
                        'empresa',
                        'contribuyentes',
                        'estadorotulos',
                        'ConsultaEmpresa',
                        'rotulos',
                        'Consul_traspasos_r',
                        'Consul_cierres',
                        
                       ));
    }

   
} //Cierre final



