<?php

namespace App\Http\Controllers\Backend\Rotulos;

use App\Models\Contribuyentes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Empresas;
use App\Models\Usuario;
use App\Models\Rotulos;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;
use Symfony\Contracts\Service\Attribute\Required;
use Whoops\Run;

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
        $dato->num_tarjeta = $request->num_tarjeta;
        $dato->estado = $request->estado;
       
           
        if($dato->save()){
            return ['success' => 1];
        
        }
    }
    //Termina registrar rotulo

    //Función Tabla Rótulos
    public function tablaRotulos(Rotulos $lista){

                
        $lista=Rotulos::join('contribuyente','rotulos.id_contribuyente','=','contribuyente.id')
                      ->join('empresa','rotulos.id_empresa','=','empresa.id')
                      
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.direccion','rotulos.fecha_apertura','rotulos.permiso_instalacion','rotulos.medidas','rotulos.num_tarjeta','rotulos.estado',
        'contribuyente.nombre as contribuyente','contribuyente.apellido',
        'empresa.nombre as empresas')
        ->get();
                
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

        $rotulo= Rotulos
                ::join('contribuyente','rotulos.id_contribuyente','=','contribuyente.id')
                ->join('empresa','rotulos.id_empresa','=','empresa.id')
        
        ->select('rotulos.id','rotulos.nom_rotulo','rotulos.actividad_economica','rotulos.direccion','rotulos.fecha_apertura','rotulos.permiso_instalacion','rotulos.medidas','rotulos.num_tarjeta','rotulos.estado',
        'contribuyente.nombre as contribuyente','contribuyente.apellido',
        'empresa.nombre as empresa')
   
        ->find($id);

        return view('backend.admin.Rotulos.vistaRotulos', compact('id','rotulo','contribuyentes','empresas'));
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
}