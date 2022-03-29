<?php

namespace App\Http\Controllers\Backend\MatriculasDetalle;

use App\Http\Controllers\Backend\MatriculasDetalle\alert;
use App\Http\Controllers\Controller;
use App\Models\LicenciaMatricula;
use App\Models\MatriculasDetalle;
use App\Models\Empresas;
use App\Models\MatriculasDetalleEspecifico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Environment\Console;


class MatriculasDetalleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function index($id)
    { 
        $matriculas= LicenciaMatricula::
        where('tipo_permiso', "Matrícula")
        ->get();
                 
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
                        
        ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual',
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

        return view('backend.admin.Empresas.Matriculas.agregar.agregar_matriculas', compact('id','matriculas','empresa','detectorNull',));

    }


    public function tablaMatriculas($id){

        $matriculas=MatriculasDetalle
        ::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
        ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')
                        
        ->select('matriculas_detalle.id as id_matriculas_detalle', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual','matriculas_detalle.estado_especificacion',
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
        $tarifa=LicenciaMatricula::where('id',$request->tipo_matricula)->pluck('tarifa')->first();
    
        $monto_total=$monto*$request->cantidad;
        $pago_mensual_total=$tarifa*$request->cantidad;
     

        //*Fin Operación

        $md = new MatriculasDetalle();
        $md->id_empresa = $request->id_empresa;
        $md->id_matriculas = $request->tipo_matricula;
        $md->cantidad = $request->cantidad;
        $md->monto = $monto_total;
        $md->pago_mensual = $pago_mensual_total;
        $md->save();
    
        return ['success' => 1];
   
    
    }
    public function eliminarM(Request $request)
    { 
        $listado=db::table('matriculas_detalle_especifico AS m')

        ->join('matriculas_detalle AS me', 'me.id', '=', 'm.id_matriculas_detalle')
            
        ->select('m.id','m.id_matriculas_detalle', 'm.cod_municipal','m.codigo','m.num_serie','m.direccion',
                'me.cantidad','me.monto',
                )
        ->where('m.id_matriculas_detalle', $request->id)     
        ->first();

        if($listado==""){
            $tasa = MatriculasDetalle::find($request->id);
            $tasa->delete();

            return [
                'success' => 1,
               ];
        }else{
                return [
                    'success' => 2,
                ];
             }
    }
    public function informacionMatricula(Request $request)
    {
        log::info($request->all());

           $regla = array(
            'id' => 'required',
        );

          $validar = Validator::make($request->all(), $regla);

     if ($validar->fails()){ return ['success' => 0];}

     if($lista = MatriculasDetalle::where('id', $request->id)->first()){
        $tipo_matricula = LicenciaMatricula::orderBy('nombre')->get();
        $signoD='$';

    $listado=db::table('matriculas_detalle_especifico AS m')

    ->join('matriculas_detalle AS me', 'me.id', '=', 'm.id_matriculas_detalle')
          
    ->select('m.id','m.id_matriculas_detalle', 'm.cod_municipal','m.codigo','m.num_serie','m.direccion',
             'me.cantidad','me.monto',
            )
    ->where('m.id_matriculas_detalle', $request->id)     
    ->get();

     return ['success' => 1,
            'matriculas_detalle' => $lista,
            'id_matriculas' => $lista->id_matriculas,
            'cantidad' => $lista->cantidad,
            'montoDolar'=> $signoD.$lista->monto,
            'Pago_mensualDolar'=> $signoD.$lista->pago_mensual,
            'tipo_matricula'=>$tipo_matricula,
            'listado'=> $listado,
            ];
     }else{
         return ['success' => 2];
     }
     }
     
     //*** Inicia editar matrícula y específica.****//
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
       
    DB::beginTransaction();
    try {
        
        //Actualizar registro matrícula detalle
       if(MatriculasDetalle::where('id', $request->id_editar)->first())
                {
                    
                         if($request->hayregistro == 1){
                                 
                            //agregar id a pila
                            $pila = array();
                            for ($i = 0; $i < count($request->idarray); $i++) 
                                {
                                    // Los id que sean 0, seran nuevos registros
                                    if($request->idarray[$i] != 0) 
                                    {
                                        array_push($pila, $request->idarray[$i]);
                                    }

                                }
                   
                                // borrar todos los registros
                                // primero obtener solo la lista de requisicon obtenido de la fila
                                // y no quiero que borre los que si vamos a actualizar con los ID
                                MatriculasDetalleEspecifico::where('id_matriculas_detalle', $request->id_matricula_detalle_editar)
                                    ->whereNotIn('id', $pila)
                                    ->delete();

                                // actualizar registros
                                for ($i = 0; $i < count($request->idarray); $i++) {
                                    if($request->idarray[$i] != 0){
                                        MatriculasDetalleEspecifico::where('id', $request->idarray[$i])->update([
                                            'cod_municipal' => $request->cod_municipal_editar[$i],
                                            'codigo' => $request->codigo_editar[$i],
                                            'num_serie' => $request->num_serie_editar[$i],
                                            'direccion' => $request->direccion_editar[$i]
                                        ]);
                                    }
                                }
                
                                // hoy registrar los nuevos registros
                                for ($i = 0; $i < count($request->idarray); $i++) {
                                    if($request->idarray[$i] == 0){
                                        $rDetalle = new MatriculasDetalleEspecifico();
                                        $rDetalle->id_matriculas_detalle = $request->id_matricula_detalle_editar;
                                        $rDetalle->cod_municipal = $request->cod_municipal_editar[$i];
                                        $rDetalle->codigo = $request->codigo_editar[$i];
                                        $rDetalle->num_serie = $request->num_serie_editar[$i];
                                        $rDetalle->direccion = $request->direccion_editar[$i];
                                        $rDetalle->save();
                                    }
                                }
                                //* Operación
                                $monto=LicenciaMatricula::where('id',$request->tipo_matricula_editar)
                                ->pluck('monto')->first();  
                                $tarifa=LicenciaMatricula::where('id',$request->tipo_matricula_editar)
                                ->pluck('tarifa')->first();
                                $monto_total=$monto*$request->cantidad_editar;
                                $pago_mensual_total=$tarifa*$request->cantidad_editar;
                                //*Fin Operación
                                if($request->cantidad_editar==0){
                                            $tasa = MatriculasDetalle::find($request->id_editar);
                                            $tasa->delete();
                                            
                                    }
                                    else
                                        {
                                            MatriculasDetalle::where('id', $request->id_editar)
                                            ->update([
                                                        'cantidad' => $request->cantidad_editar,
                                                        'monto' => $monto_total,
                                                        'pago_mensual' =>$pago_mensual_total,               
                                                    ]);
                                        }
                                    // actualizar registros matrícula específica
                                DB::commit();

                            // /. actualizar registros matrícula específica
                            return ['success' => 1];

                        }else
                            {  // borrar registros detalle
                                // solo si viene vacio el array
                                if($request->idarray == null){
                                MatriculasDetalleEspecifico::where('id_matriculas_detalle', $request->id_matricula_detalle_editar)
                                ->delete();
                                if($request->cantidad_editar==0){
                                    $tasa = MatriculasDetalle::find($request->id_editar);
                                    $tasa->delete();
                                    
                            }
                                }
                                DB::commit();
                                return ['success' => 1];
                            }     
                        
                    }

    }catch(\Throwable $e){
        DB::rollback();
        return ['success' => 2];
    }
    
    }//Termina función editar matrícula y específica.
     


public function especificarMatriculas(Request $request){

    log::info($request->all());
    $id_matriculas_detalle=$request->id_matriculas_detalle;

    $CantidadSeleccionada=db::table('matriculas_detalle AS m')

    ->join('empresa AS e', 'e.id', '=', 'm.id_empresa')
    ->join('matriculas AS ma', 'ma.id', '=', 'm.id_matriculas')
          
    ->select('m.id', 'm.cantidad','m.monto','m.pago_mensual',
            'e.nombre AS empresa','e.id_contribuyente AS contribuyente','e.id AS empresa_id',
            'ma.nombre as tipo_matricula')
    ->where('m.id', "=", "$id_matriculas_detalle")     
    ->first();

    $matriculasEspecificas=db::table('matriculas_detalle_especifico AS m')

    ->join('matriculas_detalle AS me', 'me.id', '=', 'm.id_matriculas_detalle')
          
    ->select('m.id','m.id_matriculas_detalle', 'm.cod_municipal','m.codigo','m.num_serie','m.direccion',
             'me.cantidad','me.monto',
            )
    ->where('m.id_matriculas_detalle', "=", "$id_matriculas_detalle")     
    ->first();




    return  [
                'success' => 1,
                'cantidad' =>$CantidadSeleccionada->cantidad,
                'id_matriculas_detalle' =>$request->id_matriculas_detalle,
            
                'matriculasEspecificas'=>$matriculasEspecificas,
            ];
}
public function agregar_matriculas_detalle_especifico(Request $request){
    log::info($request->all());
    $especificada="especificada";

    $rules = array(
        'id_matriculas_detalle' => 'required',
    );

    $validator = Validator::make($request->all(), $rules);

    if ( $validator->fails()){
        return ['success' => 0];
    }

    
    if($request->cod_municipal != null) {
      
        for ($i = 0; $i < count($request->cod_municipal); $i++) {

            $md = new MatriculasDetalleEspecifico();
            $md->id_matriculas_detalle =$request->id_matriculas_detalle;
            $md->cod_municipal =$request->cod_municipal[$i];
            $md->codigo =$request->codigo[$i];
            $md->num_serie=$request->num_serie[$i];
            $md->direccion = $request->direccion[$i];
            $md->save();
        }
        MatriculasDetalle::where('id', $request->id_matriculas_detalle)
        ->update([
                    'estado_especificacion' =>$especificada,               
                ]);
                
        return ['success' => 1];
    }
        
}

public function VerMatriculaEsp(Request $request)
    {
        log::info($request->all());

           $regla = array(
            'id' => 'required',
        );

          $validar = Validator::make($request->all(), $regla);

     if ($validar->fails()){ return ['success' => 0];}

     if($lista = MatriculasDetalle::where('id', $request->id)->first()){
        $tipo_matricula = LicenciaMatricula::orderBy('nombre')->get();
        $signoD='$';

    $listado=db::table('matriculas_detalle_especifico AS m')

    ->join('matriculas_detalle AS me', 'me.id', '=', 'm.id_matriculas_detalle')
          
    ->select('m.id_matriculas_detalle', 'm.cod_municipal','m.codigo','m.num_serie','m.direccion',
             'me.cantidad','me.monto',
            )
    ->where('m.id_matriculas_detalle', $request->id)     
    ->get();

    $mdetalle=db::table('matriculas_detalle AS m')

    ->join('matriculas AS me', 'me.id', '=', 'm.id_matriculas')
          
    ->select('m.id', 
             'me.nombre',
            )
    ->where('m.id', $request->id)     
    ->first();

     return ['success' => 1,
            'matriculas_detalle' => $lista,
            'id_matriculas' => $lista->id_matriculas,
            'cantidad' => $lista->cantidad,
            'montoDolar'=> $signoD.$lista->monto,
            'tipo_matricula'=>$tipo_matricula,
            'listado'=> $listado,
            'mdetalle'=>$mdetalle
            ];
     }else{
         return ['success' => 2];
     }
}
       


}//* Cierre final
