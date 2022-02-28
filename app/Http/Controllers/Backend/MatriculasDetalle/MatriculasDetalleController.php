<?php

namespace App\Http\Controllers\Backend\MatriculasDetalle;

use App\Http\Controllers\Controller;
use App\Models\LicenciaMatricula;
use App\Models\MatriculasDetalle;
use App\Models\Empresas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class MatriculasDetalleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function index($id)
    { 
        $matriculas= LicenciaMatricula::All()
        ->where('tipo_permiso', "=", "Matrícula");

                
        $empresa= Empresas
        ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
        ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
        ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
        ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
        ->join('actividad_especifica','empresa.id_actividad_especifica','=','actividad_especifica.id')
        
        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',
        'actividad_economica.rubro',
        'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica')
        ->find($id);   
        
        $matriculasRegistradas=MatriculasDetalle
        ::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
        ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')
                        
        ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto',
                'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                'matriculas.nombre as tipo_matricula')
        ->where('id_empresa', "=", "$id")     
        ->first($id);

        if ($matriculasRegistradas == null)
             { 
                 $detectorNull=1;
             }else 
             {
                $detectorNull=0;
             }

        return view('backend.admin.Empresas.Matriculas.agregar.agregar_matriculas', compact('id','matriculas','empresa','detectorNull'));

    }


    public function tablaMatriculas($id){

        $matriculas=MatriculasDetalle
        ::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
        ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')
                        
        ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto',
                'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                'matriculas.nombre as tipo_matricula')
        ->where('id_empresa', "=", "$id")     
        ->get();
                
        return view('backend.admin.Empresas.Matriculas.agregar.tabla.tabla_matriculas', compact('matriculas'));
    }

    public function agregar_matriculas(Request $request){
        log::info($request->all());

        $rules = array(
            'id_empresa' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){
            return ['success' => 0];
        }

        if (MatriculasDetalle::where('id_empresa', $request->id_empresa)->where('id_matriculas', $request->tipo_matricula)->first()) {
           return ['success' => 2];   
        }
        
        //* Operación
        
        $monto=LicenciaMatricula::where('id',$request->tipo_matricula)->pluck('monto')->first();
    
        $monto_total=$monto*$request->cantidad;
        log::info($monto);

        //*Fin Operación

        $md = new MatriculasDetalle();
        $md->id_empresa = $request->id_empresa;
        $md->id_matriculas = $request->tipo_matricula;
        $md->cantidad = $request->cantidad;
        $md->monto = $monto_total;
        $md->save();
    
        return ['success' => 1];
   
    
    }
    public function eliminarM(Request $request)
    { 
        
    // buscamos el interes el cual queremos eliminar
        $tasa = MatriculasDetalle::find($request->id);
        $tasa->delete();
             
            return ['success' => 1];
    }

    public function informacionMatricula(Request $request)
    {


           $regla = array(
            'id' => 'required',
        );

          $validar = Validator::make($request->all(), $regla);

     if ($validar->fails()){ return ['success' => 0];}

     if($lista = MatriculasDetalle::where('id', $request->id)->first()){
        $tipo_matricula = LicenciaMatricula::orderBy('nombre')->get();
        $signoD='$';

     return ['success' => 1,
         'matriculas_detalle' => $lista,
         'id_matriculas' => $lista->id_matriculas,
         'cantidad' => $lista->cantidad,
         'montoDolar'=> $signoD.$lista->monto,
         'tipo_matricula'=>$tipo_matricula,
        ];
     }else{
         return ['success' => 2];
     }
     }
 
     public function editarMatricula(Request $request)
    {
        log::info($request->all());
       $regla = array(

           'id_editar' => 'required',
           'cantidad_editar' => 'required',
           'tipo_matricula_editar' => 'required',
               
        );

       $validar = Validator::make($request->all(), $regla);

       if ($validar->fails()){ return ['success' => 0];} 
       
       if(MatriculasDetalle::where('id', $request->id_editar)->first())
       {
        
        //* Operación
        $monto=LicenciaMatricula::where('id',$request->tipo_matricula_editar)->pluck('monto')->first();  
        $monto_total=$monto*$request->cantidad_editar;
        //*Fin Operación

                MatriculasDetalle::where('id', $request->id_editar)->update([
                   'cantidad' => $request->cantidad_editar,
                   'monto' => $monto_total,
    
                ]);

               return ['success' => 1];
           }else {
               return['success' => 2];
           }              
    }
     






}//* Cierre final
