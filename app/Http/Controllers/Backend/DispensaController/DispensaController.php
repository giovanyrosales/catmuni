<?php

namespace App\Http\Controllers\Backend\DispensaController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Dispensa;
use Carbon\Carbon;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;
use Symfony\Contracts\Service\Attribute\Required;
use function PHPUnit\Framework\isEmpty;

class DispensaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function index()
    {
        $dispensas = Dispensa::All();
       
        return view('backend.admin.Dispensa.dispensa', compact('dispensas'));
    }

    public function tabla_dispensas()
    {
        $dispensas = Dispensa::orderby('id','desc')->get();


        foreach($dispensas as $dato){

            $dato->fecha_inicio_periodo=date("d-m-Y h:m:s A", strtotime($dato->fecha_inicio_periodo));  
            $dato->fecha_fin_periodo=date("d-m-Y h:m:s A", strtotime($dato->fecha_fin_periodo)); 
        }
       
        return view('backend.admin.Dispensa.tabla.tabladispensas', compact('dispensas'));
    }

    public function nuevo_periodo_dispensa(Request $request){

        $dispensas = Dispensa::All();
        foreach($dispensas as $dato){

            if($dato->estado=='Activo'){
                $permiso_nuevo_periodo='0'; //Denegado

                return ['success' => 3];

            }else{
                $permiso_nuevo_periodo='1'; //Aprobado
            }
        }
  
        $regla = array(
            'inicio_periodo' => 'Required',
            'fin_periodo' => 'Required',      
          );
          $message=[
    
  
            'inicio_periodo.required'=>'El inicio del período es requerido',
            'fin_periodo.required'=>'La finalización del período es requerido',
        ];

        $validar = Validator::make($request->all(), $regla, 
        $message
    
        );
  
          
          if ($validar->fails()){ return [
            'success' => 0,
            'message' => $validar->errors()->first()       
        ];}
          
              $dato = new Dispensa();
              $dato->fecha_inicio_periodo = $request->inicio_periodo;
              $dato->fecha_fin_periodo = $request->fin_periodo;
              $dato->estado = 'Activo';

             
  
              if($dato->save()){
                  return ['success' => 1];
              }else{
                  return ['success' => 2];
              }
    }

    public function borrar_periodo_dispensa(Request $request){

        $dispensa = Dispensa::find($request->id_dispensa);
        $dispensa->delete(); 

        if($dispensa->delete())
        {
            return ['success' => 1];

        }else{
                return ['success' => 2];
             }
    }

    public function info_periodo_dispensa(Request $request){

        if($info = Dispensa::where('id', $request->id)->first()){
            if($info->estado=='Activo')
            {
                $info->activo=1;
            }else{
                    $info->activo=0;
                 }

            return [
                    'success' => 1,
                    'info' => $info,
                   ];

        }else{
            return ['success' => 2];
        }
    }






}
