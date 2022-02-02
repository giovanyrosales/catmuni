<?php

namespace App\Http\Controllers\Backend\TarifaVariable;

use App\Models\DetalleActividad;
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


class TarifaVariableController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tarifavariable = TarifaVariable::All();
       
        return view('backend.admin.TarifaVariable.TarifaVariable', compact('tarifavariable'));
    }

    
//Función para agregar nueva actividad económica

    public function nuevaTarifaV(Request $request)
        {
           
        $regla = array(
            'actividad_economica'=>'required',
            'limite_inferior' => 'required|unique:tarifa_variable,limite_inferior',
            'fijo' => 'required|unique:tarifa_variable,fijo',
            'excedente' => 'required',
            'categoria' => 'required',
            'millar' => 'required', 
        );
    

    $validar = Validator::make($request->all(), $regla, 
       //   $message
    );
       
    if ($validar->fails()){

          return [

           'success'=> 0,
  //   'message' => $validar->errors()->first()

         ];
    }

        $dato = new TarifaVariable();
        $dato->limite_inferior = $request->limite_inferior;
        $dato->fijo = $request->fijo;
        $dato->excedente = $request->excedente;
        $dato->categoria = $request->categoria;
        $dato->millar = $request->millar;
        $dato->id_actividad_economica = $request->actividad_economica;

    if($dato->save())
    {
        return ['success' => 1];
    
    }
    }
// Termina función

//Función lista detalle de la actividad económica

    public function listarTarifaV()
      {
       
    $tarifavariable = TarifaVariable::All();

    return view('backend.admin.TarifaVariable.ListarTarifaVariable', compact('tarifavariable'));
      }

//Tabla detalless
    public function tablaTarifaVariable(TarifaVariable $lista)
         {

         
    $lista=TarifaVariable::join('actividad_economica','tarifa_variable.id_actividad_economica','=','actividad_economica.id')
           
      ->select('tarifa_variable.id','tarifa_variable.limite_inferior','tarifa_variable.fijo','tarifa_variable.excedente','tarifa_variable.categoria','tarifa_variable.millar',
      'actividad_economica.rubro as actividad_economica' )
       ->get();
            
            foreach($lista as $ll)
            {
                $ll->limite_inferior = number_format($ll->limite_inferior, 2, '.', ',');
                $ll->excedente = number_format($ll->excedente, 2, '.', ',');
                $ll->fijo = number_format($ll->fijo, 2, '.', ',');
            }

          return view('backend.admin.TarifaVariable.tabla.tablatarifavariable', compact('lista'));
        }

//Ver informacón del detalle
    public function informacionTarifaV(Request $request)
      {
    
        $regla = array(
            'id' => 'required',
        
    );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if ($lista = TarifaVariable::where('id', $request->id)->first())
           {
          
            $actividad_economica = ActividadEconomica::orderby('rubro')->get();
                
                return['success' => 1,
                'idact_eco' => $lista->id_actividad_economica,
                'tarifa_variable' => $lista,
                'actividad_economica' => $actividad_economica,
            ];
            }
            else
            {
                return ['success' => 2];
            }
        }

    public function editarTarifaV(Request $request)
        {

        $regla = array(  
            'limite_inferior' => 'required',
            'fijo' => 'required',
            'fijo' => 'required',
            'categoria' => 'required',
            'millar' => 'required',
            'actividad_economica' => 'required',
     );
   
        $validar = Validator::make($request->all(), $regla);

    if ($validar->fails()){ return ['success' => 0];} 
    
    if(TarifaVariable::where('id', $request->id)->first()){
  
       TarifaVariable::where('id', $request->id)->update([

            'id_actividad_economica' => $request->actividad_economica,
            'limite_inferior' => $request->limite_inferior,
            'fijo' => $request->fijo,
            'excedente' => $request->excedente,
            'categoria' => $request->categoria,
            'millar'=> $request->millar,
       
        ]);

        return ['success' => 1];
    }else{
        return ['success' => 2];
    }
    }


    public function eliminarTarifaV(Request $request)
        {

        $detalle = TarifaVariable::find($request->id);
             $detalle->delete();
                return ['success' => 1];

        }

}

