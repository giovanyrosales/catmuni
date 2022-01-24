<?php

namespace App\Http\Controllers\Backend\DetalleActividadE;

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


class DetalleActividadEController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function crearActividad()
    {
        $actividadeconomica = ActividadEconomica::All();
       
        return view('backend.admin.DetalleActividadEconomica.DetalleActividad', compact('actividadeconomica'));
    }

    
//Función para agregar nueva actividad económica

    public function nuevaActividad(Request $request)
        {
           
        $regla = array(
            'actividad_economica'=>'required',
            'limite_inferior' => 'required|unique:detalle_actividad_economica,limite_inferior',
            'fijo' => 'required|unique:detalle_actividad_economica,fijo',
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

        $dato = new DetalleActividad();
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

    public function listarDetalleActividadE()
      {
       
    $actividadeconomica = ActividadEconomica::All();

    return view('backend.admin.DetalleActividadEconomica.ListarDetalleActividad', compact('actividadeconomica'));
      }

//Tabla detalless
    public function tablaDetalleActividadEconomica(DetalleActividad $lista)
         {

         
    $lista=DetalleActividad::join('actividad_economica','detalle_actividad_economica.id_actividad_economica','=','actividad_economica.id')
           
      ->select('detalle_actividad_economica.id','detalle_actividad_economica.limite_inferior','detalle_actividad_economica.fijo','detalle_actividad_economica.excedente','detalle_actividad_economica.categoria','detalle_actividad_economica.millar',
      'actividad_economica.rubro as actividad_economica' )
       ->get();
            
            foreach($lista as $ll)
            {
                $ll->limite_inferior = number_format($ll->limite_inferior, 2, '.', ',');
                $ll->excedente = number_format($ll->excedente, 2, '.', ',');
                $ll->fijo = number_format($ll->fijo, 2, '.', ',');
            }

          return view('backend.admin.DetalleActividadEconomica.tabla.tablalistadetalleactividad', compact('lista'));
        }

//Ver informacón del detalle
    public function informacionDetalle(Request $request)
      {
    
        $regla = array(
            'id' => 'required',
        
    );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if ($lista = DetalleActividad::where('id', $request->id)->first())
           {
          
            $actividad_economica = ActividadEconomica::orderby('rubro')->get();
                
                return['success' => 1,
                'idact_eco' => $lista->id_actividad_economica,
                'detalle_actividad_economica' => $lista,
                'actividad_economica' => $actividad_economica,
            ];
            }
            else
            {
                return ['success' => 2];
            }
        }

    public function editarDetalles(Request $request)
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
    
    if(DetalleActividad::where('id', $request->id)->first()){
  
       DetalleActividad::where('id', $request->id)->update([

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


    public function eliminarD(Request $request)
        {

        $detalle = DetalleActividad::find($request->id);
             $detalle->delete();
                return ['success' => 1];

        }

}

