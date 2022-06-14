<?php

namespace App\Http\Controllers\Backend\LicenciaMatricula;

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
use App\Models\LicenciaMatricula;

class LicenciaMatriculaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('backend.admin.LicenciaMatricula.ListarLicenciaMatricula');
    }

    public function tablaLicenciaMatricula()
    {
        $licencia = LicenciaMatricula::orderBy('id', 'ASC')->get();  
       
        return view('backend.admin.LicenciaMatricula.tabla.tablalicenciamatricula', compact('licencia'));
    
    }
    
    public function listarLicencia()
    {
         $licencia = LicenciaMatricula::All();
        
        return view('backend.admin.LicenciaMatricula.tabla', compact('licencia'));
    }

    public function agregarLM(Request $request)
    {
        $regla = array(
            'nombre' => 'Required',
            'monto' => 'Required',
            'tarifa' => 'Required',
            'tipo_permiso' => 'Required',
        );

        $validar = Validator::make($request->all(), $regla);
  
          
        if ($validar->fails()){ return ['success' => 0];}
        
            $dato = new LicenciaMatricula();
            $dato->nombre = $request->nombre;
            $dato->monto = $request->monto;
            $dato->tarifa = $request->tarifa;
            $dato->tipo_permiso = $request->tipo_permiso;

            if($dato->save()){
                return ['success' => 1];
            }else{
                return ['success' => 2];
            }

    }

    public function informacionLM(Request $request)
    {
     $regla = array(
         'id' => 'required',
     );

     $validar = Validator::make($request->all(), $regla);

     if ($validar->fails()){ return ['success' => 0];}

     if($lista = LicenciaMatricula::where('id', $request->id)->first()){
     
     return ['success' => 1,
     //nombre de la tabla
         'licencia_matricula' => $lista,
            
        ];
     }else{
         return ['success' => 2];
     }
     }

    public function editarLM(Request $request)
    {
        log::info($request->all());
       $regla = array(
           'id' => 'required',
           'nombre' => 'required',
           'monto' => 'required',
           'tarifa' => 'required',
           'tipo_permiso' => 'required',
                    
        );

       $validar = Validator::make($request->all(), $regla);

       if ($validar->fails()){ return ['success' => 0];} 
       
       if(LicenciaMatricula::where('id', $request->id)->first())
       {

                LicenciaMatricula::where('id', $request->id)->update([
                   'nombre' => $request->nombre,
                   'monto' => $request->monto,
                   'tarifa' => $request->tarifa,
                   'tipo_permiso' => $request->tipo_permiso,
    
                ]);

               return ['success' => 1];
           }else {
               return['success' => 2];
           }              
    }

    public function eliminarLM(Request $request)
    {

        // buscamos el id de lo que queremos eliminar
      $lm = LicenciaMatricula::find($request->id);
      $lm->delete();
           
          return ['success' => 1];
    }

}
