<?php

namespace App\Http\Controllers\Backend\Rotulos;

use App\Http\Controllers\Controller;
use App\Models\CalificacionRotulo;
use App\Models\CierresReaperturasRotulo;
use App\Models\CobrosRotulo;
use App\Models\Contribuyentes;
use App\Models\InspeccionRotulos;
use App\Models\Rotulos;
use App\Models\Empresas;
use App\Models\EstadoRotulo;
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
        $contribuyentes = Contribuyentes::ALL();

      return view('backend.admin.Rotulos.CrearRotulos', compact('empresas','contribuyentes'));

    }

    //Agregar Rótulo
    public function nuevoRotulo(Request $request)
    {

        $regla = array(
    
            'nom_rotulo' => 'required',            
            'permiso_instalacion' => 'required',
            'fecha_apertura' => 'required',
            'actividad_economica' => 'required',          
         
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
                $dato->id_contribuyente = $request->contribuyente;
                $dato->id_empresa = $request->empresa;
                $dato->id_estado_rotulo = $request->estado_rotulo;
                $dato->nFicha = $request->nFicha;
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
        $dato->id_contribuyente = $request->contribuyente;
        $dato->id_empresa = $request->empresa;
        $dato->id_estado_rotulo = $request->estado_rotulo;
        $dato->fecha_apertura = $request->fecha_apertura;
        $dato->direccion = $request->direccion;
        $dato->nom_rotulo = $request->nom_rotulo;
        $dato->nFicha = $request->nFicha;
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
    public function tablaRotulos(Rotulos $lista)
    {

        $rotulo = Rotulos::ALL();

        $lista = Rotulos::orderBy('nom_rotulo')->get();

        foreach($lista as $dato) 
        {
            $nom_apellido = ' ';
            $nom_empresa = ' ';

            if ($info = Contribuyentes::where ('id', $dato->id_contribuyente)->first())
            {
               $nom_apellido = $info->nombre . ' ' . $info->apellido;
            }

            if ($info = Empresas::where ('id',$dato->id_empresa)->first())
            {
                $nom_empresa = $info->nombre;
            }

            $dato->cont = $nom_apellido;
            $dato->empr = $nom_empresa;

        }
      
        return view('backend.admin.Rotulos.tabla.tablalistarotulos', compact('lista','rotulo'));
    }
    //Termina función tabla Rótulos

    //Función Listar Rótulos
    public function listarRotulos()
    {
   
        $empresas = Empresas::All();
        $contribuyentes = Contribuyentes::ALL();
     
    
        return view('backend.admin.Rotulos.ListarRotulos', compact('empresas','contribuyentes'));
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
            
                $contribuyentes = Contribuyentes::orderby('nombre')->get();
                $empresa = Empresas::orderby('nombre')->get();               
            
                $empresas = Empresas::where ('id',$lista->id_empresa)->get();

                    return['success' => 1,
        
                    'rotulos' => $lista,             
                    'id_empre' => $lista->id_empresa,
                    'empresa' => $empresas,
                    'id_cont' => $lista->id_contribuyente,
                    'contribuyente' => $contribuyentes, 

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
          
            'coordenadas' => 'required',
            'cargo_inspeccion' => 'required',
            

            
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];} 

        DB::beginTransaction();

        try {

        if($data = Rotulos::where('id', $request->id)->first())
        {

            if($request->hasFile('imagen'))
            {
             
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
                            
                       
                            'nFicha' => $request->nFicha,
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
                    

                            if(Storage::disk('archivos')->exists($imagenOld))
                            {
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
                   
              
                
                'nFicha' => $request->nFicha,
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
   
         
      
        $lista = Rotulos::where ('id', $id)->first();

        $contri = ' ';
        $emp = '';

        if ($contribuyente = Contribuyentes::where('id', $lista->id_contribuyente)->first())
        {
            $contri  = $contribuyente->nombre . ' ' . $contribuyente->apellido;
        }

        if ($empresa = Empresas::where('id', $lista->id_empresa)->first())
        {
            $emp = $empresa->nombre;
        }

    

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
              

        return view('backend.admin.Rotulos.vistaRotulos', compact('id','lista','contribuyentes','empresas','calificacion','detectorNull','emp','contri'));

    }
    //Termina vista detallada
        
   

    public function tablaCierresR($id)
    {

        $historico_cierres=CierresReaperturasRotulo::orderBy('id', 'desc')
        ->where('id_empresa',$id)
        ->get();

           
        return view('backend.admin.Rotulos.CierresTraspasos.tablas.tabla_cierre_r', compact('historico_cierres'));
    }

    public function tablaTraspasosR($id)
    {

        $historico_traspasos=TraspasosRotulos::orderBy('id', 'desc')
        ->where('id_empresa',$id) 
        ->get();
           
        return view('backend.admin.Rotulos.CierresTraspasos.tablas.tabla_traspaso_r', compact('historico_traspasos'));
    }


    public function calificacionRotulo ($id)
    {

        $contribuyente = Contribuyentes::ALL();
        $empresa = Empresas::ALL();
  
        $rotulos = Rotulos
        ::join('contribuyente','rotulos.id_contribuyente','=','contribuyente.id')
        ->join('empresa','rotulos.id_empresa','=','empresa.id')

        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.direccion','rotulos.fecha_apertura','rotulos.permiso_instalacion','rotulos.medidas','rotulos.nFicha',
        'contribuyente.nombre as contribuyente','contribuyente.apellido',
        'empresa.nombre as empresa')
        ->find($id);
            
        $contribuyente = Contribuyentes::orderBy('id', 'ASC')->get();

        $calificacion = Rotulos::where ('id', $id)->get();

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

        if ($contribuyente = Contribuyentes::where('id', $rotulo->id_contribuyente)->first())
        {           

           
            $contri = $contribuyente->nombre;
            $apellido = $contribuyente->apellido;           
            
        
        }
        
        $empresa = Rotulos::where('id', $id)->first();
      

        $tRotulo = TarifaRotulo::orderBy('id', 'ASC')->get();
        $rotulos = Rotulos::orderBy('id', 'ASC')->get();
    
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
                    
                    if($dato->total_caras > 1)
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
        $total1 = round(($total * 12),2);
        $totalImpuesto = round ($tarifa_mensual + ($tarifa_mensual * $fondoF),2);
        $totalAnual = round($total1 + ($total1 * $fondoF),2);
        
        log::info('tarifa sin fondo fiesta ' . $total);
        log::info('total impuesto mensual con fondo fiesta ' . $totalImpuesto);
        log::info('Impuesto Anual sin fondo fiesta ' . $total1);
        log::info('Impuesto anual con fondo fiesta ' . $totalAnual);
     
    }
      
        
        return view('backend.admin.Rotulos.CalificacionRotulo', compact('id','calificacion','totalImpuesto','rotulo','tarifa_mensual','contri','apellido','tarifa','totalA','totalAnual','total','total1','emp','emp1','emp2','contribuyente','empresa','rotulos'));
        
    }

//Función para tabla de calificacion de rótulos

    public function tablaCalificacionR($id)
    {
        $rotulo = Rotulos::where('id', $id)->first();    
     
        $calificacion = Rotulos::where('id', $id)->get();
 

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
                    
                    if($dato->total_caras > 1)
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
  
        return view('backend.admin.Rotulos.tabla.tablarotulo', compact('calificacion','totalA','total1','total_impuesto','tarifa_mensual','dato'));
         
    }
    //Termica calculo de la calificación de rótulos
   


    public function guardarCalificacion(Request $request)
    {    
        
        $fecha_calificacion = $request->fechacalificar;
        $estado_calificaion =  $request->estado_calificacion;
        $id_rotulo = $request->id_rotulos;
        $id_empresa = $request->id_empresa;
        $id_contribuyente = $request->id_contribuyente;
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
  
      
            $calificacion = Rotulos::where('id', $id_rotulo)->get();
 

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

        
        $dato->monto = $tarifa_mensual;
        $total_impuesto = round($tarifa_mensual + ($tarifa_mensual * $fondoF),2);
      
        $tarifa->tarifa_mensual = $tarifa_mensual;
        $tarifa->total_impuesto	 = $total_impuesto;
        
        
        log::info($tarifa_mensual);
        
        $contador=$contador+1;
        log::info('contador igual a:'.$contador);

        $dt = new CalificacionRotulo();
        $dt->id_rotulos = $dato->id;
        $dt->id_empresa = $request->id_empresa;
        $dt->id_contribuyente = $request->id_contribuyente;
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
                foreach ($periodo as $dt) 
                {

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

    //Realizar traspaso
    public function infoTraspasoR(Request $request)
    {
        $empresa = Empresas::ALL();
  

            $regla = array(
                'id' => 'required',
            );

            $validar = Validator::make($request->all(), $regla);

            if ($validar->fails()){ return ['success' => 0];}

            if($lista = Rotulos::where('id', $request->id)->first())
            {
                
                $empresas = Empresas::orderBy('nombre')->get();
                $estado_rotulo = EstadoRotulo::orderBy('estado')->get();
                $contribuyentes = Contribuyentes::orderBy('nombre')->get();

                    return ['success' => 1,

                        'id_emp' => $lista->id_empresa,
                        'idesta' => $lista->id_estado_rotulo,
                        'empresa' => $empresas,
                        'id_contri' => $lista->id_contribuyente,
                        'contribuyente' => $contribuyentes,
                        'estado_rotulo' => $estado_rotulo,
                
                    ];
            }
                else
                {
                    return ['success' => 2];
                }

    }
//Realizar traspaso finaliza

        
//Función para llenar el select Actividad Especifica
        public function buscarEmpresaTraspaso(Request $request)
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

        public function cierres_traspasosRotulo($id)
        {
                    
            $idusuario = Auth::id();
            $infouser = Usuario::where('id', $idusuario)->first();
            $estado_rotulo = EstadoRotulo::All();
            $ConsultaEmpresa = Empresas::All();
            $empresas = Empresas::ALL();
            $contribuyentes = Contribuyentes::ALL();

        
            $rotulo = Rotulos::join('empresa','rotulos.id_empresa','=','empresa.id')
            ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
            ->join('estado_rotulo','rotulos.id_estado_rotulo','=', 'estado_rotulo.id')
                            
            ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura',
            'rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
            'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion',
            'rotulos.coordenadas','rotulos.imagen',
            'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica','actividad_economica.mora',
            'empresa.nombre as empresas',
            'estado_rotulo.estado')
            ->where('rotulos.id', $id)
            ->find($id);
            
        
            return view('backend.admin.Rotulos.CierresTraspasos.Cierres_TraspasosR',
                    compact(
                            'estado_rotulo',     
                            'rotulo',
                            'ConsultaEmpresa',
                            'contribuyentes',
                            'empresas',
                            
                                            
                        ));
        }

        public function nuevoEstadoRotulo(Request $request)
        {
            log::info($request->all());

                $id = $request->id;
                $estado_rotulo = $request->estado_rotulo;

                if($estado_rotulo == 1)
                {
                    $Tipo_operacion='Cierre';
                }else
                {
                    $Tipo_operacion='Reapertura';
                }

                $rotulo = Rotulos::join('empresa','rotulos.id_empresa','=','empresa.id')
                ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
                ->join('estado_rotulo','rotulos.id_estado_rotulo','=', 'estado_rotulo.id')
                                
                ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura',
                'rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
                'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion',
                'rotulos.coordenadas','rotulos.imagen',
                'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica','actividad_economica.mora',
                'empresa.nombre as empresas',
                'estado_rotulo.estado')
                ->find($id);
                

                $regla = array(  
                    'estado_rotulo' => 'required',
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

            
                if(Rotulos::where('id', $request->id)->first()){
                if($estado_rotulo != $rotulo->id_estado_rotulo){
                    //** Guardar registro historico en tabla traspasos */
                    $cierre = new CierresReaperturasRotulo();               
                    $cierre->id_rotulos = $request->id;
                    $cierre->fecha_a_partir_de = $request->cierre_apartirdeldia;
                    $cierre->tipo_operacion = $Tipo_operacion;
                    $cierre->save();
                    //** FIN- Guardar registro historico en tabla traspasos */

                    Rotulos::where('id', $request->id)->update([
            
                        'id_estado_rotulo' => $request->estado_rotulo,
                        
                    ]);
                    DB::commit();
                        return ['success' => 1];
                
                    }else{ 
                            return ['success' => 3];
                        }
                }
            }            

                    catch(\Throwable $e)
                    {
                        DB::rollback();   
                    return ['success' => 2];
                    }
            
        }

        public function nuevoTraspasoRotulo(Request $request)
        {

            log::info($request->all());

            $id = $request->id;
            $id_empresa = $request->empresa;
            $id_contribuyente = $request->contribuyente;

        
            $rotulo = Rotulos::join('empresa','rotulos.id_empresa','=','empresa.id')
            ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
            ->join('estado_rotulo','rotulos.id_estado_rotulo','=', 'estado_rotulo.id')
            ->join('contribuyente', 'empresa.id_contribuyente', '=', 'contribuyente.id')
                            
            ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura',
            'rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
            'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion',
            'rotulos.coordenadas','rotulos.imagen',
            'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica','actividad_economica.mora',
            'empresa.nombre as empresas',
            'estado_rotulo.estado',
            'contribuyente.nombre as contri', 'contribuyente.apellido as apellido')
            ->find($id);

                    
                $datos_empresa = Empresas::select('nombre')
                ->where('id', $id_empresa)
                ->first();

                $datos_contribuyente = Contribuyentes::select('nombre')
                ->where('id', $id_contribuyente)
                ->first();
        

                $regla = array(  
                    'id' => 'required',
                    
                );
            
                $validar = Validator::make($request->all(), $regla,
            
                );

                if ($validar->fails()){ 
                    return ['success' => 0,
                    'message' => $validar->errors()->first()
                ];
                }
                if(Rotulos::where('id', $request->id)->first()){
                    //** Guardar registro historio en tabla traspasos */
            
                if($id_contribuyente != $rotulo->id_contribuyente){
                    $traspaso = new TraspasosRotulos();
                    $traspaso->id_rotulos = $id;            
                    $traspaso->contribuyente_anterior = $rotulo->contri;
                    $traspaso->contribuyente_nuevo =  $datos_contribuyente->nombre;
                    $traspaso->empresa_anterior = $rotulo->empresas;
                    $traspaso->empresa_nueva = $datos_empresa->nombre;
                    $traspaso->fecha_a_partir_de = $request->Apartirdeldia;
                    $traspaso->save();
                    //** FIN- Guardar registro historio en tabla traspasos */
                    Rotulos::where('id', $request->id)->update([
            
                        'id_contribuyente' => $request->contribuyente,
                        'id_empresa' => $request->empresas,

                        ]);

                        return ['success' => 1];

                    }else{ 
                        return ['success' => 3];
                        }

                    }else{
                        return ['success' => 2];
                    }
        }

        public function VerHistorialCobros_Rotulos($id)
        {
    
                $ListaCobrosRotulo = CobrosRotulo::where('id_rotulos', $id)
                ->get();
    
            return view('backend.admin.Rotulos.Cobros.tablas.tabla_historico_Cobrosrotulo', compact('ListaCobrosRotulo'));
        }


    //Función para llenar el select Actividad Especifica
        public function buscarEmpresa(Request $request)
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

    



        
} //Cierre final



