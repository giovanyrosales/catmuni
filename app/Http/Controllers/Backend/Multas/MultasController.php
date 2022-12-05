<?php

namespace App\Http\Controllers\Backend\Multas;

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
use App\Models\Multas;

class MultasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('backend.admin.Multas.ListarMultas');
    }

    public function tablaMultas()
    {
        $multa = Multas::orderBy('id', 'ASC')->get();  
       
        return view('backend.admin.Multas.tabla.tablalistamultas', compact('multa'));
    
    }
    
    public function listarMultas()
    {
         $multa = Multas::All();
        
        return view('backend.admin.Multas.tabla', compact('multa'));
    }

    public function agregarM(Request $request)
    {
        $regla = array(
            'codigo' => 'Required',
            'tipo_multa' => 'Required',
            'nombre' => 'Required',
        );

        $validar = Validator::make($request->all(), $regla);
  
          
        if ($validar->fails()){ return ['success' => 0];}
        
            $dato = new Multas();
            $dato->codigo = $request->codigo;
            $dato->tipo_multa = $request->tipo_multa;
            $dato->nombre = $request->nombre;

            if($dato->save()){
                return ['success' => 1];
            }else{
                return ['success' => 2];
            }

    }

    public function informacionMultas(Request $request)
    {
     $regla = array(
         'id' => 'required',
     );

     $validar = Validator::make($request->all(), $regla);

     if ($validar->fails()){ return ['success' => 0];}

     if($lista = Multas::where('id', $request->id)->first()){
     
     return ['success' => 1,
     //nombre de la tabla
         'multas' => $lista,
            
        ];
     }else{
         return ['success' => 2];
     }
     }

    public function editarMultas(Request $request)
    {
       
       $regla = array(
           'id' => 'required',
           'codigo' => 'required',
           'tipo_multa' => 'required',
           'nombre' => 'required',
                    
        );

       $validar = Validator::make($request->all(), $regla);

       if ($validar->fails()){ return ['success' => 0];} 
       
       if(Multas::where('id', $request->id)->first())
       {

                Multas::where('id', $request->id)->update([
                   'codigo' => $request->codigo,
                   'tipo_multa' => $request->tipo_multa,
                   'nombre' => $request->nombre,
    
                ]);

               return ['success' => 1];
           }else {
               return['success' => 2];
           }              
    }

    public function eliminarMultas(Request $request)
    {

        // buscamos el id de lo que queremos eliminar
      $multa = Multas::find($request->id);
      $multa->delete();
           
          return ['success' => 1];
    }

}



    
