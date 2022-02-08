<?php

namespace App\Http\Controllers\Backend\TarifaFija;

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
use App\Models\ActividadEspecifica;
use App\Models\TarifaFija;

class TarifaFijaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $actividadeconomica = ActividadEconomica::All();
        $actividadespecifica = ActividadEspecifica::ALL();

        return view('backend.admin.TarifaFija.ListarTarifaFija', compact('actividadeconomica','actividadespecifica'));
    }

    public function tablaTarifa(TarifaFija $tarifa_fija)
    {
      
        $tarifa_fija = 
        TarifaFija::join('actividad_economica','tarifa_fija.id_actividad_economica','=','actividad_economica.id')
        ->join('actividad_especifica','tarifa_fija.id_actividad_especifica','=','actividad_especifica.id')
                                
            
        ->select('tarifa_fija.id','tarifa_fija.codigo','tarifa_fija.limite_inferior','tarifa_fija.limite_superior','tarifa_fija.impuesto_mensual',
        'actividad_economica.rubro as nombre_rubro',
        'actividad_especifica.nom_actividad_especifica as nombre_actividad')
         ->get();
          //  orderBy('id', 'ASC')->get();  
     
     
        foreach($tarifa_fija as $ll)
        {
            $ll->limite_inferior = number_format($ll->limite_inferior, 2, '.', ',');
            $ll->limite_superior = number_format($ll->limite_superior, 2, '.', ',');
            $ll->impuesto_mensual = number_format($ll->impuesto_mensual, 2, '.', ',');
           
       
        }
         
        return view('backend.admin.TarifaFija.tabla.tablalistatarifafija', compact('tarifa_fija'));
    
    }


    public function listarTarifaFija()
    {
        $actividadeconomica = ActividadEconomica::All();
        $actividadespecifica = ActividadEspecifica::ALL();

      //  $tarifa_fija = TarifaFija::All();
        //$infTarifa = TarifaFija::where('id', $tarifa_fija)->first();

        return view('backend.admin.TarifaFija.ListarTarifaFija', compact('actividadeconomica','actividadespecifica'));
    }

    //función para agregar nueva tarifa fija
    public function nuevaTarifa(Request $request)
    {
        $regla = array(
            'codigo' => 'required',
            'actividad_especifica' => 'required',
            'impuesto_mensual' => 'required',
            'actividad_economica'=>'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){

            return [
  
             'success'=> 0,
   
           ];
      }

        $dato = new TarifaFija();
        $dato->codigo = $request->codigo;
        $dato->id_actividad_especifica = $request->actividad_especifica;
        $dato->limite_inferior = $request->limite_inferior;
        $dato->limite_superior = $request->limite_superior;
        $dato->impuesto_mensual = $request->impuesto_mensual;
        $dato->id_actividad_economica = $request->actividad_economica;

        if($dato->save())
         {
        return ['success' => 1];
         }
    
    }
    //termina funcion 
    
    public function informacionTarifaF(Request $request)
    {
           $regla = array(
            'id' => 'required',
        );

          $validar = Validator::make($request->all(), $regla);

     if ($validar->fails()){ return ['success' => 0];}

     if($lista = TarifaFija::where('id', $request->id)->first()){

        $actividad_economica = ActividadEconomica::orderby('rubro')->get();
        $actividad_especifica = ActividadEspecifica::orderby('nom_actividad_especifica')->get();
     
     return ['success' => 1,

         'tarifa_fija' => $lista,
         'idact_eco' => $lista->id_actividad_economica,
         'actividad_economica' => $actividad_economica,
         'idact_esp' =>$lista->id_actividad_especifica,
         'actividad_especifica' => $actividad_especifica,
        ];
     }else{
         return ['success' => 2];
     }
     }

     public function editarTarifaF(Request $request)
     {
       
       $regla = array(
           'id' => 'required',
           'codigo' => 'required',
           'impuesto_mensual' => 'required',
           'actividad_economica' => 'required',
           'actividad_especifica' => 'required',
                    
        );

       $validar = Validator::make($request->all(), $regla);

       if ($validar->fails()){ return ['success' => 0];} 
       
       if(TarifaFija::where('id', $request->id)->first())
       {

          TarifaFija::where('id', $request->id)->update([
          
          'codigo' => $request->codigo,
          'id_actividad_especifica' => $request->actividad_especifica,
          'limite_inferior' => $request->limite_inferior,
          'limite_superior' => $request->limite_superior,
          'impuesto_mensual' => $request->impuesto_mensual,
          'id_actividad_economica' => $request->actividad_economica,
    
            ]);

               return ['success' => 1];
           }else {
               return['success' => 2];
           }              
       }

    public function eliminarTarifaF(Request $request)
    {
 // buscamos el interes el cual queremos eliminar
        $tasa = TarifaFija::find($request->id);
        $tasa->delete();
             
            return ['success' => 1];
    }
}