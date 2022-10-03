<?php

namespace App\Http\Controllers\Backend\Contribuyentes;

use App\Models\Contribuyentes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConstanciasHistorico;
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



class ContribuyentesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function listarContribuyentes()
    {
         $contribuyentes = Contribuyentes::All();
        $infContribuyente = Contribuyentes::where('id', $contribuyentes)->first();
        $contribuyenteinf = Contribuyentes::where('id', $contribuyentes)->first();

        return view('backend.admin.Contribuyentes.ListarContribuyentes', compact('contribuyentes'));
    }
  
//Agregar nuevo contribuyente

    public function crearContribuyentes()
    {
        return view('backend.admin.Contribuyentes.Crear_Contribuyentes');
    }
    
    public function tablaContribuyentes(){
        $contribuyentes = Contribuyentes::orderBy('id', 'ASC')->get();

        return view('backend.admin.Contribuyentes.tabla.tablalistacontribuyentes', compact('contribuyentes'));
    
    }

    public function tablahistoricocs(){

        

        $constancias_cs= ConstanciasHistorico::join('contribuyente','constancias_historico.id_contribuyente','=','contribuyente.id')

        ->select('constancias_historico.id','constancias_historico.tipo_constancia',
        'constancias_historico.num_resolucion','constancias_historico.created_at',
        'contribuyente.id as id_contribuyente','contribuyente.nombre as contribuyente',
        'contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email',
        'contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 
        'contribuyente.direccion as direccionCont',
         )
        ->where('tipo_constancia','Simple')
        ->get();

        foreach($constancias_cs as $dato){
            $dato->año=carbon::parse($dato->created_at)->format('y');
            $dato->fecha_registro=date("d-m-Y h:m:s A", strtotime($dato->created_at));  
    
        }
 
        return view('backend.admin.Contribuyentes.tabla.tablahistoricocs', compact('constancias_cs'));
    
    }

    public function tablahistoricocg(){

        

        $constancias_cg= ConstanciasHistorico::join('contribuyente','constancias_historico.id_contribuyente','=','contribuyente.id')

        ->select('constancias_historico.id','constancias_historico.tipo_constancia',
        'constancias_historico.num_resolucion','constancias_historico.created_at',
        'contribuyente.id as id_contribuyente','contribuyente.nombre as contribuyente',
        'contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email',
        'contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 
        'contribuyente.direccion as direccionCont',
         )
        ->where('tipo_constancia','Global')
        ->get();

        foreach($constancias_cg as $dato){
            $dato->año=carbon::parse($dato->created_at)->format('y');
            $dato->fecha_registro=date("d-m-Y h:m:s A", strtotime($dato->created_at));
        }
 
        return view('backend.admin.Contribuyentes.tabla.tablahistoricocg', compact('constancias_cg'));
    
    }

//Agregar nuevo contribuyente

    public function nuevoContribuyente (Request $request){
    log::info($request->all());
        $regla = array(
            'nombre' => 'Required',
            'apellido' => 'Required',
            'direccion' => 'Required',
            'dui' => 'Required|unique:contribuyente,dui',
            'nit' => 'Required|unique:contribuyente,nit',
           // 'registro_comerciante' => 'unique:contribuyente,registro_comerciante',
            'telefono' => 'Required',
            'email' => 'Required',
            'fax' => 'nullable'
          
          );
          $message=[
    
  
            'dui.unique'=>'EL número de DUI ingresado ya esta registrado',
        ];

        $validar = Validator::make($request->all(), $regla, 
        $message
    
        );
  
          
          if ($validar->fails()){ return [
            'success' => 0,
            'message' => $validar->errors()->first()       
        ];}
          
              $dato = new Contribuyentes();
              $dato->nombre = $request->nombre;
              $dato->apellido = $request->apellido;
              $dato->direccion = $request->direccion;
              $dato->dui = $request->dui;
              $dato->nit = $request->nit;
              $dato->registro_comerciante = $request->registro_comerciante;
              $dato->telefono = $request->telefono;
              $dato->email = $request->email;
              $dato->fax = $request->fax;
  
              if($dato->save()){
                  return ['success' => 1];
              }else{
                  return ['success' => 2];
              }
          }  
//Función para llamar informacion

        public function informacionContribuyentes(Request $request)
           {
            $regla = array(
                'id' => 'required',
            );
    
            $validar = Validator::make($request->all(), $regla);
    
            if ($validar->fails()){ return ['success' => 0];}
    
            if($lista = Contribuyentes::where('id', $request->id)->first()){
            
            return ['success' => 1,
                'contribuyente' => $lista,
               ];
            }else{
                return ['success' => 2];
            }
            }
        
//Funcion editar contribuyentes

        public function editarContribuyente(Request $request)
          {
            
            $regla = array(
                'id' => 'required',
                'nombre' => 'required',
                'apellido' => 'required',
                'direccion' => 'required',
                'dui' => 'required',
                'nit' => 'required',
                
                'telefono' => 'required',
                'email' => 'required',
                
            
            );

            $validar = Validator::make($request->all(), $regla);

            if ($validar->fails()){ return ['success' => 0];} 
            
            if(Contribuyentes::where('id', $request->id)->first())
            {

                    Contribuyentes::where('id', $request->id)->update([
                        'nombre' => $request->nombre,
                        'apellido' => $request->apellido,
                        'direccion' => $request->direccion,
                        'dui' => $request->dui,
                        'nit' => $request->nit,
                        'registro_comerciante' => $request->registro_comerciante,
                        'telefono' => $request->telefono,
                        'email' => $request->email,
                        'fax' => $request->fax,
                       
                    ]);

                //  $contribuyente->save();

                    return ['success' => 1];
                }else {
                    return['success' => 2];
                }              
         
                //return view('backend.admin.Contribuyentes.ListarContribuyentes', compact('contribuyente'));
            }
         
//Eliminar Contribuyente    

        public function eliminarContribuyentes(Request $request)
            {

                // buscamos el contribuyente el cual queremos eliminar
                $contribuyente = Contribuyentes::find($request->id);
                $contribuyente->delete();
             
                return ['success' => 1];
            }

      //     public function borrarContribuyente(Request $request)
        //    {
          //      $contribuyente = Contribuyentes::findById($request->id);
            //    $contribuyente->delete();

              //  return ['success' => 1];
           // }
   

           public function verinfoContribuyentes(Request $request)
           {

            $regla = array(
                'id' => 'required',
            );
    
            $validar = Validator::make($request->all(), $regla);
    
            if ($validar->fails()){ return ['success' => 0];}
    
            if($lista = Contribuyentes::where('id', $request->id)->first()){
            
            return ['success' => 1,
                'contribuyente' => $lista,
               ];
            }else{
                return ['success' => 2];
            }
        }

        public function historico_solvencias()
        {
            $contribuyentes = Contribuyentes::All();
            
            return view('backend.admin.Contribuyentes.HistoricoSolvencias', compact('contribuyentes'));
        }


}
           
    
      
    
    

    

