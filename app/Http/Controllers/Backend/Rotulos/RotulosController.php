<?php

namespace App\Http\Controllers\Backend\Rotulos;

use App\Http\Controllers\Controller;
use App\Models\Contribuyentes;
use App\Models\InspeccionRotulos;
use App\Models\Rotulos;
use App\Models\Empresas;
use App\Models\Usuario;
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
        $contribuyentes = Contribuyentes::All();
        $empresas = Empresas::ALL();

      return view('backend.admin.Rotulos.CrearRotulos', compact('contribuyentes', 'empresas'));

    }

    //Agregar Rótulo
    public function nuevoRotulo(Request $request){

        $regla = array(
    
            'nom_rotulo' => 'required',
            'direccion' => 'required',
            'permiso_instalacion' => 'required',
            'fecha_apertura' => 'required',
            'actividad_economica' => 'required',
            'medidas' => 'required',
            'total_medidas' => 'required',
            'total_caras' => 'required',
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
    
        $dato = new Rotulos();
        $dato->id_contribuyente = $request->contribuyente;
        $dato->id_empresa = $request->empresa;
        $dato->nom_rotulo = $request->nom_rotulo;
        $dato->direccion = $request->direccion;
        $dato->permiso_instalacion = $request->permiso_instalacion;
        $dato->actividad_economica = $request->actividad_economica;
        $dato->fecha_apertura = $request->fecha_apertura;
        $dato->medidas = $request->medidas;
        $dato->total_medidas = $request->total_medidas;
        $dato->total_caras = $request->total_caras;
        $dato->num_tarjeta = $request->num_tarjeta;
        $dato->estado = $request->estado;
       
           
        if($dato->save()){
            return ['success' => 1];
        
        }
    }
    //Termina registrar rotulo

    //Función Tabla Rótulos
    public function tablaRotulos(Rotulos $lista){

    /*          
        $lista=Rotulos::join('contribuyente','rotulos.id_contribuyente','=','contribuyente.id')
                      ->join('empresa','rotulos.id_empresa','=','empresa.id')
                      
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.direccion','rotulos.fecha_apertura','rotulos.permiso_instalacion','rotulos.medidas','rotulos.num_tarjeta','rotulos.estado',
        'contribuyente.nombre as contribuyente','contribuyente.apellido',
        'empresa.nombre as empresas')
        ->get();
    */
        
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

      

        return view('backend.admin.Rotulos.tabla.tablalistarotulos', compact('lista'));
    }
    //Termina función tabla Rótulos

    //Función Listar Rótulos
    public function listarRotulos()
    {
   
        $contribuyentes = Contribuyentes::All();
        $empresas = Empresas::All();
     
        return view('backend.admin.Rotulos.ListarRotulos', compact('contribuyentes','empresas'));
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
            $contribuyente = Contribuyentes::orderby('nombre')->get();
            $empresas = Empresas::orderby('nombre')->get();
                
                return['success' => 1,
                'id_contri' => $lista->id_contribuyente,
                'rotulos' => $lista,
                'contribuyente' => $contribuyente,
                'id_empre' => $lista->id_empresa,
                'empresas' => $empresas
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
     
        $regla = array(  
            'nom_rotulo' => 'required',
            'actividad_economica' => 'required',
            'direccion' => 'required',
            'fecha_apertura' => 'required',
            'num_tarjeta' => 'required',
            'permiso_instalacion' => 'required',
            'medidas' => 'required',
            'total_medidas' => 'required',
            'total_caras' => 'required',
            'empresa' => 'required',
          
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];} 

        if(Rotulos::where('id', $request->id)->first()){

                Rotulos::where('id', $request->id)->update([
                   
                'id_empresa' => $request->empresa,
                'nom_rotulo' => $request->nom_rotulo,
                'actividad_economica' => $request->actividad_economica,
                'direccion' => $request->direccion,
                'fecha_apertura' => $request->fecha_apertura,
                'num_tarjeta'=> $request->num_tarjeta,
                'permiso_instalacion'=> $request->permiso_instalacion,
                'medidas'=> $request->medidas,   
                'total_medidas' => $request->total_medidas,
                'total_caras' => $request->total_caras,          
        
            ]);
          
            return ['success' => 1];
        }else{
            return ['success' => 2];
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
        

        /*
        $rotulo= Rotulos
                ::join('contribuyente','rotulos.id_contribuyente','=','contribuyente.id')
                ->join('empresa','rotulos.id_empresa','=','empresa.id')
        
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.direccion','rotulos.fecha_apertura','rotulos.permiso_instalacion','rotulos.medidas','rotulos.num_tarjeta','rotulos.estado',
        'contribuyente.nombre as contribuyente','contribuyente.apellido',
        'empresa.nombre as empresa')
        ->find($id);
        */

                
    $inspecciones = InspeccionRotulos
    ::join('rotulos','inspeccion_rotulos.id_rotulos','=','rotulos.id')
    
    ->select('inspeccion_rotulos.id','inspeccion_rotulos.hora_inspeccion','inspeccion_rotulos.fecha_inspeccion','inspeccion_rotulos.coordenadas','inspeccion_rotulos.imagen','inspeccion_rotulos.estado_inspeccion',
    'rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.direccion','rotulos.fecha_apertura','rotulos.num_tarjeta','rotulos.permiso_instalacion','rotulos.medidas','rotulos.total_medidas','rotulos.total_caras','rotulos.estado','rotulos.fecha_cierre')
    ->where('id_rotulos', "=", "$id")
    ->first();


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

            if ($inspecciones == null)
            {
                $detectorNull = 0;
            }
            else
            {
            $detectorNull =1;
              }

                      

        return view('backend.admin.Rotulos.vistaRotulos', compact('id','detectorNull','inspecciones','lista','contribuyentes','empresas', 'contri', 'emp'));
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
           
            $contribuyente = Contribuyentes::orderBy('nombre')->get();
            return ['success' => 1,
            'idcont' => $lista->id_contribuyente,
            'contribuyente' => $contribuyente,
            'rotulos' => $lista

            
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

    public function inspeccionRotulo($id)
    {
      
        $contribuyentes = Contribuyentes::All();
        $empresas = Empresas::All();
        $inspeccionR = InspeccionRotulos::ALL();
 

  
    
        $rotulo = Rotulos::where ('id', $id)->first();

            $contri = ' ';
            $emp = '';
          
            if ($contribuyente = Contribuyentes::where('id', $rotulo->id_contribuyente)->first())
            {
                $contri  = $contribuyente->nombre . ' ' . $contribuyente->apellido;
            }

            if ($empresa = Empresas::where('id', $rotulo->id_empresa)->first())
            {
                $emp = $empresa->nombre;
            }

        
        return view('backend.admin.Rotulos.InspeccionRotulos', compact('id','inspeccionR','rotulo','contri','emp','contribuyentes', 'empresas'));
    
    }

    public function crear_inspeccion(Request $request )
    {  
        log::info($request->all());
           
        $regla = array(
           
            'fecha_inspeccion' => 'required',
            'coordenadas' => 'required',
           
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
                
                $dato = new InspeccionRotulos();
                $dato->id_rotulos = $request->id_rotulos;
                $dato->fecha_inspeccion = $request->fecha_inspeccion;
                $dato->hora_inspeccion = $request->hora_inspeccion;
                $dato->coordenadas = $request->coordenadas;
                $dato->imagen = $nomImagen;
                $dato->estado_inspeccion = $request->estado_inspeccion;
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
     
          
        $dato = new InspeccionRotulos();
        $dato->id_rotulos = $request->id_rotulos;
        $dato->fecha_inspeccion = $request->fecha_inspeccion;
        $dato->hora_inspeccion = $request->hora_inspeccion;
        $dato->coordenadas = $request->coordenadas;
        $dato->estado_inspeccion = $request->estado_inspeccion;
        $dato->nom_inspeccion = $request->nom_inspeccion;
        $dato->cargo_inspeccion = $request->cargo_inspeccion;
      
      
        if($dato->save()){

            return ['success' => 1];
        }else{return ['success' => 2];}

    }
       
    }   
}