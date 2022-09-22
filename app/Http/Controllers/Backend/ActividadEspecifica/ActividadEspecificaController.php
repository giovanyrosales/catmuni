<?php

namespace App\Http\Controllers\Backend\ActividadEspecifica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;
use Symfony\Contracts\Service\Attribute\Required;
use function PHPUnit\Framework\isEmpty;
use App\Models\ActividadEconomica;
use App\Models\TarifaVariable;
use App\Models\ActividadEspecifica;
use App\Models\GiroEmpresarial;

class ActividadEspecificaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $giros_empresariales = GiroEmpresarial::All();
       
        return view('backend.admin.ActividadEspecifica.ListarActividadEspecifica', compact('giros_empresariales'));
    }

    public function tablaActividadEspecifica(ActividadEspecifica $lista)
    {
        $lista=ActividadEspecifica::join('giro_empresarial','actividad_especifica.id_giro_empresarial','=','giro_empresarial.id')
           
        ->select('actividad_especifica.id','actividad_especifica.nom_actividad_especifica',
        'giro_empresarial.nombre_giro_empresarial' )
         ->get();
              
         return view('backend.admin.ActividadEspecifica.tabla.tablaactividadespecifica', compact('lista'));
         
    }

    public function listarActividadEspecifica()
    {
        $giro_empresarial_lista = GiroEmpresarial::All();
        
        return view('backend.admin.ActividadEspecifica.tabla', compact('giro_empresarial_lista'));
    }

    //Función para agregar nueva actividad específica

    public function agregarActividadE(Request $request)
        {
            
       
        $regla = array(
            'giro_empresarial'=>'required',
            'nom_actividad_especifica' => 'required',
          
        );
    
    $validar = Validator::make($request->all(), $regla, 
     
    );
       
    if ($validar->fails()){

          return [

           'success'=> 0,
 
         ];
    }

        $dato = new ActividadEspecifica();
        $dato->nom_actividad_especifica = $request->nom_actividad_especifica;
        $dato->id_giro_empresarial = $request->giro_empresarial;

    if($dato->save())
    {
        return ['success' => 1];
    
    }
    }
// Termina función

   
    public function informacionActividadEspecifica(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

    if ($validar->fails()){ return ['success' => 0];}

    if($lista = ActividadEspecifica::where('id', $request->id)->first()){

        $giro_empresarial = GiroEmpresarial::orderby('nombre_giro_empresarial')->get();
    
    return ['success' => 1,

        'actividad_especifica' => $lista,
        'idact_giec' => $lista->id_giro_empresarial,
        'giro_empresarial' => $giro_empresarial,

        ];
    }else{
        return ['success' => 2];
    }
    }

    public function editarActividadEspecifica(Request $request)
    {
    
    $regla = array(
        'id' => 'required',
        'nom_actividad_especifica' => 'required',
        'giro_empresarial' => 'required',
                    
        );

    $validar = Validator::make($request->all(), $regla);

    if ($validar->fails()){ return ['success' => 0];} 
    
    if(ActividadEspecifica::where('id', $request->id)->first())
    {

        ActividadEspecifica::where('id', $request->id)->update([
       
        'nom_actividad_especifica' => $request->nom_actividad_especifica,
        'id_giro_empresarial' => $request->giro_empresarial,

            ]);

            return ['success' => 1];
        }else {
            return['success' => 2];
        }              
    }

    public function eliminarActividadEspecifica(Request $request)
    {
    // buscamos actividad especifica a eliminar
        $actividadEsp = ActividadEspecifica::find($request->id);
        $actividadEsp->delete();
            
            return ['success' => 1];
    }
 }


    