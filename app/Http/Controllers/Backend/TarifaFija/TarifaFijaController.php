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
use App\Models\TarifaFija;

class TarifaFijaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('backend.admin.TarifaFija.ListarTarifaFija');
    }

    public function tablaTarifa()
    {
        $tarifa_fija = TarifaFija::orderBy('id', 'ASC')->get();  
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
        $tarifa_fija = TarifaFija::All();
        $infTarifa = TarifaFija::where('id', $tarifa_fija)->first();

        return view('backend.admin.TarifaFija.ListarTarifaFija', compact('tarifa_fija'));
    }

    public function nuevaTarifa(Request $request)
    {
        $regla = array(
            'nombre_actividad' => 'required',
            'impuesto_mensual' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){

            return [
  
             'success'=> 0,
   
           ];
      }

        $dato = new TarifaFija();
        $dato->nombre_actividad = $request->nombre_actividad;
        $dato->limite_inferior = $request->limite_inferior;
        $dato->limite_superior = $request->limite_superior;
        $dato->impuesto_mensual = $request->impuesto_mensual;

        if($dato->save())
         {
        return ['success' => 1];
         }
    
    }

    public function informacionTarifaF(Request $request)
    {
           $regla = array(
            'id' => 'required',
        );

          $validar = Validator::make($request->all(), $regla);

     if ($validar->fails()){ return ['success' => 0];}

     if($lista = TarifaFija::where('id', $request->id)->first()){
     
     return ['success' => 1,
         'tarifa_fija' => $lista,
        ];
     }else{
         return ['success' => 2];
     }
     }

     public function editarTarifaF(Request $request)
     {
       
       $regla = array(
           'id' => 'required',
           'impuesto_mensual' => 'required',
                    
        );

       $validar = Validator::make($request->all(), $regla);

       if ($validar->fails()){ return ['success' => 0];} 
       
       if(TarifaFija::where('id', $request->id)->first())
       {

          TarifaFija::where('id', $request->id)->update([

          'nombre_actividad' => $request->nombre_actividad,
          'limite_inferior' => $request->limite_inferior,
          'limite_superior' => $request->limite_superior,
          'impuesto_mensual' => $request->impuesto_mensual,
    
            ]);

               return ['success' => 1];
           }else {
               return['success' => 2];
           }              
       }
}