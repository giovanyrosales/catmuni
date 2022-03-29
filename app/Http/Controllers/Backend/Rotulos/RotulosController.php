<?php

namespace App\Http\Controllers\Backend\Rotulos;

use App\Http\Controllers\Controller;
use App\Models\CalificacionRotulo;
use App\Models\Contribuyentes;
use App\Models\InspeccionRotulos;
use App\Models\Rotulos;
use App\Models\Empresas;
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
                        }DB::commit();
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
    

        /*
        $rotulo= Rotulos
                ::join('contribuyente','rotulos.id_contribuyente','=','contribuyente.id')
                ->join('empresa','rotulos.id_empresa','=','empresa.id')
        
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.direccion','rotulos.fecha_apertura','rotulos.permiso_instalacion','rotulos.medidas','rotulos.num_tarjeta','rotulos.estado',
        'contribuyente.nombre as contribuyente','contribuyente.apellido',
        'empresa.nombre as empresa')
        ->find($id);
        */
  
        /*

            if ($inspecciones == null)
            {
                $detectorNull = 0;
            }
            else
            {
            $detectorNull = 1;
            }

        */          

        return view('backend.admin.Rotulos.vistaRotulos', compact('id','lista','contribuyentes','empresas'));
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


    public function calificacionRotulo ($id)
    {
        $contribuyente = Contribuyentes::ALL();
        $empresa = Empresas::ALL();
        $rotulo = Rotulos::ALL();
       
        $rotulo = Rotulos::where ('id', $id)->first();

        $contri = ' ';
        $emp = '';
        $emp1 = '';
        $emp2 = '';
      
        if ($contribuyente = Contribuyentes::where('id', $rotulo->id_contribuyente)->first())
        {
            $contri  = $contribuyente->nombre . ' ' . $contribuyente->apellido;
        }

        if ($empresa = Empresas::where('id', $rotulo->id_empresa)->first())
        {
            $emp = $empresa->nombre;
            $emp1 = $empresa->direccion;
            $emp2 = $empresa->contribuyente;
        }
     
        
        return view('backend.admin.Rotulos.CalificacionRotulo', compact('id','rotulo','contri','emp','emp1','emp2','contribuyente', 'empresa'));
    }

    //Función para mostrar los rótulos que pertenecen a una sola empresa
    public function tablaCalificacionR($id)
    {
        $empresa = Rotulos::where('id', $id)->first();
      

        $calificacion=Rotulos::join('empresa','rotulos.id_empresa','=','empresa.id')
     
                      
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.fecha_apertura',
        'rotulos.direccion','rotulos.permiso_instalacion','rotulos.medidas',
        'rotulos.total_medidas', 'rotulos.total_caras','rotulos.nom_inspeccion','rotulos.cargo_inspeccion',
        'rotulos.coordenadas','rotulos.imagen',
        'empresa.nombre as empresas')
        
        ->where('id_empresa', $empresa->id_empresa)
        ->get();

        $cantidad = 0;

    foreach ($calificacion as $dato)
    {
        $nombreRotulo = $dato->nom_rotulo;

            $cantidad = $cantidad + 1;

    }


      
            return view('backend.admin.Rotulos.tabla.tablarotulo', compact('calificacion','cantidad'));
         
    }
    //Termina función para mostrar los rótulos que pertenecen a una sola empresa


    public function calcularTarifaR ($id)
    {

    }
}



