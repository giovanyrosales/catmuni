<?php

namespace App\Http\Controllers\Backend\Contribuyentes;

use App\Models\Contribuyentes;
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



class ContribuyentesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function listarContribuyentes()
    {
         $contribuyentes = Contribuyentes::All();
        $infContribuyente = Contribuyentes::where('id', $contribuyentes)->first();
    //     ->join('empresa','empresa.id_empresa','=','contribuyentes.id_empresa')
    //     ->select('contribuyente.nombre','empresa.nombre')
    //     ->get();
    //     dd($contribuyentes);

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

//Agregar nuevo contribuyente

    public function nuevoContribuyente (Request $request){
    
        $regla = array(
            'nombre' => 'Required',
            'apellido' => 'Required',
            'direccion' => 'Required',
            'dui' => 'Required|unique:contribuyente,dui',
            'nit' => 'Required|unique:contribuyente,nit',
            'registro_comerciante' => 'Required|unique:contribuyente,registro_comerciante',
            'telefono' => 'Required',
            'email' => 'Required',
            'fax' => 'nullable'
          
          );
  
          $validar = Validator::make($request->all(), $regla);
  
          
          if ($validar->fails()){ return ['success' => 0];}
          
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
                'registro_comerciante' => 'required',
                'telefono' => 'required',
                'email' => 'required',
                'fax' => 'nullable',
            
            );

            $validar = Validator::make($request->all(), $regla);

            if ($validar->fails()){ return ['success' => 0];} 
            
            if($contribuyente = Contribuyentes::where('id', $request->id)->first())
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

        }
           
    
      
    
    

    

