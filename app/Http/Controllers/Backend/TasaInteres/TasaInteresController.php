<?php

namespace App\Http\Controllers\Backend\TasaInteres;

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
use App\Models\Interes;

class TasaInteresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('backend.admin.TasaInteres.ListarInteres');
    }

    public function tablaTasas()
    {
        $interes = Interes::orderBy('id', 'ASC')->get();  
       
       
        foreach($interes as $ll)
        {
            $ll->interes = number_format($ll->interes, 2, );
        }

        return view('backend.admin.TasaInteres.tabla.tablalistatasainteres', compact('interes'));
    
    }

    public function listarInteres()
    {
         $interes = Interes::All()->pluck('monto_interes', 'id');
         $infInteres = Interes::where('id', $interes)->first();
      
        return view('backend.admin.TasaInteres.tabla', compact('interes'));
    }

    public function agregarInteres(Request $request)
    {
        $regla = array(
            'monto_interes' => 'Required',
        );

        $validar = Validator::make($request->all(), $regla);
  
          
        if ($validar->fails()){ return ['success' => 0];}
        
            $dato = new Interes();
            $dato->monto_interes = $request->monto_interes;

            if($dato->save()){
                return ['success' => 1];
            }else{
                return ['success' => 2];
            }

    }

    
    public function informacionInteres(Request $request)
    {
     $regla = array(
         'id' => 'required',
     );

     $validar = Validator::make($request->all(), $regla);

     if ($validar->fails()){ return ['success' => 0];}

     if($lista = Interes::where('id', $request->id)->first()){
     
     return ['success' => 1,
         'interes' => $lista,
        ];
     }else{
         return ['success' => 2];
     }
     }

     public function editarInteres(Request $request)
     {
       
       $regla = array(
           'id' => 'required',
           'monto_interes' => 'required',
                    
        );

       $validar = Validator::make($request->all(), $regla);

       if ($validar->fails()){ return ['success' => 0];} 
       
       if(Interes::where('id', $request->id)->first())
       {

                Interes::where('id', $request->id)->update([
                   'monto_interes' => $request->monto_interes,
    
                ]);

               return ['success' => 1];
           }else {
               return['success' => 2];
           }              
       }

    public function eliminarInteres(Request $request)
      {

                // buscamos el contribuyente el cual queremos eliminar
        $tasa = Interes::find($request->id);
        $tasa->delete();
             
            return ['success' => 1];
      }
  
}