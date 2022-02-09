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


class ActividadEspecificaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $actividadeconomica = ActividadEconomica::All();
       
        return view('backend.admin.ActividadEspecifica.ListarActividadEspecifica', compact('actividadeconomica'));
    }

    public function tablaActividadEspecifica(ActividadEspecifica $lista)
    {
        $lista=ActividadEspecifica::join('actividad_economica','actividad_especifica.id_actividad_economica','=','actividad_economica.id')
           
        ->select('actividad_especifica.id','actividad_especifica.nom_actividad_especifica',
        'actividad_economica.rubro as actividad_economica' )
         ->get();
              
         return view('backend.admin.ActividadEspecifica.tabla.tablaactividadespecifica', compact('lista'));
         
    }

    public function listarActividadEspecifica()
    {
        $actividadeconomica = ActividadEconomica::All();
        
        return view('backend.admin.ActividadEspecifica.tabla', compact('actividadeconomica'));
    }

    //Función para agregar nueva actividad específica

    public function agregarActividadE(Request $request)
        {
           
        $regla = array(
            'actividad_economica'=>'required',
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
        $dato->id_actividad_economica = $request->actividad_economica;

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

        $actividad_economica = ActividadEconomica::orderby('rubro')->get();
    
    return ['success' => 1,

        'actividad_especifica' => $lista,
        'idact_eco' => $lista->id_actividad_economica,
        'actividad_economica' => $actividad_economica,

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
        'actividad_economica' => 'required',
                    
        );

    $validar = Validator::make($request->all(), $regla);

    if ($validar->fails()){ return ['success' => 0];} 
    
    if(ActividadEspecifica::where('id', $request->id)->first())
    {

        ActividadEspecifica::where('id', $request->id)->update([
       
        'nom_actividad_especifica' => $request->nom_actividad_especifica,
        'id_actividad_economica' => $request->actividad_economica,

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


    