<?php

namespace App\Http\Controllers\Backend\MatriculasDetalle;

use App\Http\Controllers\Backend\MatriculasDetalle\alert;
use App\Http\Controllers\Controller;
use App\Models\Calificacion;
use App\Models\CalificacionMatriculas;
use App\Models\Cobros;
use App\Models\CobrosMatriculas;
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
use Carbon\Carbon;
use DateInterval;
use DatePeriod;

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
        ->join('estado_moratorio','matriculas_detalle.id_estado_moratorio','=','estado_moratorio.id')
                        
        ->select('matriculas_detalle.id as id_matriculas_detalle', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual','matriculas_detalle.estado_especificacion','matriculas_detalle.id_estado_moratorio','matriculas_detalle.inicio_operaciones',
                'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones as inicio_operacionesEmp','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                'matriculas.nombre as tipo_matricula',
                'estado_moratorio.id as id_estado_moratorio','estado_moratorio.estado as estado_moratorio')
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

        //** Para comprobar si una matricula ya existe en los registro y no se permita agregar una repetida */
        //** if (MatriculasDetalle::where('id_empresa', $request->id_empresa)->where('id_matriculas', $request->tipo_matricula)->first()) {
        //** return ['success' => 2];   
        //** }
        
        //* Operación
        
        $monto=LicenciaMatricula::where('id',$request->tipo_matricula)->pluck('monto')->first();
        $tarifa=LicenciaMatricula::where('id',$request->tipo_matricula)->pluck('tarifa')->first();
    
        $monto_total=$monto*$request->cantidad;
        $pago_mensual_total=$tarifa*$request->cantidad;
     

        //*Fin Operación

        $md = new MatriculasDetalle();
        $md->id_empresa = $request->id_empresa;
        $md->id_matriculas = $request->tipo_matricula;
        $md->id_estado_moratorio ='1';
        $md->inicio_operaciones = $request->inicio_operaciones;
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

        $Detectar_calificacion_martricula=CalificacionMatriculas::where('id_matriculas_detalle', $request->id)
        ->get();
        
        if($listado=="")
        {
                if($Detectar_calificacion_martricula=!null){

                    $delete = CalificacionMatriculas::where('id_matriculas_detalle', $request->id);
                    $delete->delete();

                    $tasa = MatriculasDetalle::find($request->id);
                    $tasa->delete();

                    return 
                        [
                            'success' => 1,
                        ];
                }else{
                        $tasa = MatriculasDetalle::find($request->id);
                        $tasa->delete();
            
                        return 
                            [
                                'success' => 1,
                            ];
                    }
        }
         else
             {//if pricipal
                    return 
                    [
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
             'me.cantidad','me.monto', 'me.inicio_operaciones',
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
        $Detectar_calificacion_martricula=CalificacionMatriculas::where('id_matriculas_detalle', $request->id_editar)
        ->get();

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

                                            if($Detectar_calificacion_martricula=!null){

                                                $delete = CalificacionMatriculas::where('id_matriculas_detalle', $request->id_editar);
                                                $delete->delete();
                            
                                                $tasa = MatriculasDetalle::find($request->id);
                                                $tasa->delete();
                            
                                            }else{
                                            
                                                    $tasa = MatriculasDetalle::find($request->id_editar);
                                                    $tasa->delete();
                                                }
                                    }
                                    else
                                        {
                                            MatriculasDetalle::where('id', $request->id_editar)
                                            ->update([
                                                        'cantidad' => $request->cantidad_editar,
                                                        'inicio_operaciones'=>$request->inicio_operaciones_editar,
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
                                    if($Detectar_calificacion_martricula=!null){
                                        $delete = CalificacionMatriculas::where('id_matriculas_detalle', $request->id_editar);
                                        $delete->delete();

                                    $tasa = MatriculasDetalle::find($request->id_editar);
                                    $tasa->delete();
                                    }else{
                                        $tasa = MatriculasDetalle::find($request->id_editar);
                                        $tasa->delete();
                                    }
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
public function VerHistorialCobros_Aparatos($id)
{

    $ListaCobrosMatriculas = CobrosMatriculas::where('id_matriculas_detalle', $id)
    ->get();

return view('backend.admin.Empresas.Cobros.tablas.tabla_historico_cobros_aparatos', compact('ListaCobrosMatriculas'));
}

public function VerHistorialCobros_sinfonolas($id)
{

    $ListaCobrosSinfonolas = CobrosMatriculas::where('id_matriculas_detalle', $id)
    ->get();

return view('backend.admin.Empresas.Cobros.tablas.tabla_historico_cobros_sinfonolas', compact('ListaCobrosSinfonolas'));
}

public function VerHistorialCobros_maquinas($id)
{

    $ListaCobrosMaquinas = CobrosMatriculas::where('id_matriculas_detalle', $id)
        ->get();

return view('backend.admin.Empresas.Cobros.tablas.tabla_historico_cobros_maquinas', compact('ListaCobrosMaquinas'));
}

public function VerHistorialCobros_mesas($id)
{

    $ListaCobrosMesas = CobrosMatriculas::where('id_matriculas_detalle', $id)
        ->get();

return view('backend.admin.Empresas.Cobros.tablas.tabla_historico_cobros_mesas', compact('ListaCobrosMesas'));
}

public function VerMatriculaEsp(Request $request)
    {
       

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
             'm.id_matriculas',
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

public function info_cobroMatriculas(Request $request){
    log::info($request->all());
    //Inicia Información mátriculas mesas
        $id_matriculadetalleMesas=$request->id_matriculadetalleMesas;
        $ultimo_cobroMesas = CobrosMatriculas::whereNotNull('periodo_cobro_fin')
        ->where('id_matriculas_detalle', $id_matriculadetalleMesas)->latest()->first();

    //Información mátricula máquinas eletrónicas
        $id_matriculadetalleMaquinas=$request->id_matriculadetalleMaquinas;
        $ultimo_cobroMaquinas = CobrosMatriculas::whereNotNull('periodo_cobro_fin')
        ->where('id_matriculas_detalle',$id_matriculadetalleMaquinas)->latest()->first();

    //Información mátricula Sinfonolas
        $id_matriculadetalleSinfonolas=$request->id_matriculadetalleSinfonolas;
        $ultimo_cobroSinfonolas = CobrosMatriculas::whereNotNull('periodo_cobro_fin')
        ->where('id_matriculas_detalle', $id_matriculadetalleSinfonolas)->latest()->first();

    //Información mátricula Aparatos
        $id_matriculadetalleaparatos=$request->id_matriculadetalleAparatos;
        $ultimo_cobroAparatos =  CobrosMatriculas::whereNotNull('periodo_cobro_fin')
        ->where('id_matriculas_detalle', $id_matriculadetalleaparatos)->latest()->first();



    return [
            'success' => 1,
            'ultimo_cobroMesas' => $ultimo_cobroMesas,
            'ultimo_cobroMaquinas' => $ultimo_cobroMaquinas,
            'ultimo_cobroSinfonolas' => $ultimo_cobroSinfonolas,
            'ultimo_cobroAparatos' => $ultimo_cobroAparatos,
           ];
}

//** ------------------ Cálculo para cobrar la matrícula de mesas de billar ----------------------------------- */
public function calculo_cobroMesas(Request $request){
    log::info($request->all());

    $idusuario = Auth::id();
    $id_empresa=$request->id;
    $id_matriculadetalleMesas=$request->id_matriculadetalleMesas;
    $tasa_interes=$request->tasa_interesMesas;
    
 
    $MesNumero=Carbon::createFromDate($request->ultimo_cobroMesas)->format('d');
    //log::info($MesNumero);

    if($MesNumero<='15')
    {
        $f1=Carbon::parse($request->ultimo_cobroMesas)->format('Y-m-01');
        $f1=Carbon::parse($f1);
        $InicioPeriodo=Carbon::createFromDate($f1);
        $InicioPeriodo= $InicioPeriodo->format('Y-m-d');
        //log::info('inicio de mes');
    }
    else
        {
         $f1=Carbon::parse($request->ultimo_cobroMesas)->addMonthsNoOverflow(1)->day(1);
         $InicioPeriodo=Carbon::parse($request->ultimo_cobroMesas)->addMonthsNoOverflow(1)->day(1)->format('Y-m-d');
        // log::info('fin de mes ');
         }

    
    $f2=Carbon::parse($request->fechaPagaraMesas);
    $f3=Carbon::parse($request->fecha_interesMoratorioMesas);
    $añoActual=Carbon::now()->format('Y');
   
    //** Inicia - Para determinar el intervalo de años a pagar */
    $monthInicio='01';
    $dayInicio='01';
    $monthFinal='12';
    $dayFinal='31';
    $AñoInicio=$f1->format('Y');
    $AñoFinal=$f2->format('Y');
    $FechaInicio=Carbon::createFromDate($AñoInicio, $monthInicio, $dayInicio);
    $FechaFinal=Carbon::createFromDate($AñoFinal, $monthFinal, $dayFinal);
    //** Finaliza - Para determinar el intervalo de años a pagar */

 
    ///** INICIO - Para obtener SIEMPRE el último día del mes que selecciono el usuario */
    $PagoUltimoDiaMes=Carbon::parse($request->fechaPagaraMesas)->endOfMonth()->format('Y-m-d');
    //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */
    Log::info('Pago ultimo dia del mes---->' .$PagoUltimoDiaMes);

    //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
    $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
    Log::info('ultimo dia del mes---->' .$UltimoDiaMes);
    $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(60)->format('Y-m-d');
    Log::info($FechaDeInicioMoratorio);
    
    $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
    $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
    //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
    
     Log::info($DiasinteresMoratorio);
   
     $calificacionesMesas = CalificacionMatriculas::latest()
        
     ->join('matriculas_detalle','calificacion_matriculas.id_matriculas_detalle','=','matriculas_detalle.id')
     
     ->select('calificacion_matriculas.id','calificacion_matriculas.nombre_matricula','calificacion_matriculas.cantidad','calificacion_matriculas.monto_matricula','calificacion_matriculas.pago_mensual','calificacion_matriculas.año_calificacion','calificacion_matriculas.estado_calificacion','calificacion_matriculas.id_estado_matricula',
     'matriculas_detalle.id as id_matriculadetalle','matriculas_detalle.id_empresa',)
 
     ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMesas)
     ->first();

   

        if($f1->lt($PagoUltimoDiaMes))
        {

            $intervalo = DateInterval::createFromDateString('1 Year');
            $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);

            $Cantidad_MesesTotal=0;
            $impuestoTotal=0;
            $impuestos_mora=0;
            $impuesto_año_actual=0;
            $multaPagoExtemporaneo=0;
         
            $totalMultaPagoExtemporaneo=0;
           
            //** Inicia Foreach para cálculo de impuesto por años de la matricula mesas de billar */
            foreach ($periodo as $dt) {

                $AñoPago =$dt->format('Y');
               
                $AñoSumado=Carbon::createFromDate($AñoPago, 12, 31);

                
                $tarifa=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMesas)
                    ->pluck('pago_mensual') 
                        ->first();
                
         
                        if($AñoPago==$AñoFinal)//Stop para cambiar el resultado de la cantidad de meses en la última vuelta del foreach...
                            {
                                $CantidadMeses=ceil(($f1->floatDiffInRealMonths($PagoUltimoDiaMes)));
                            }
                        else
                            {

                                $CantidadMeses=ceil(($f1->floatDiffInRealMonths($AñoSumado)));  
                                $f1=$f1->addYears(1)->month(1)->day(1);
   
                            }

                //*** calculo */
       
                $impuestosValor=(round($tarifa*$CantidadMeses,2));
                $impuestoTotal=$impuestoTotal+$impuestosValor;
                $Cantidad_MesesTotal=$Cantidad_MesesTotal+$CantidadMeses;

                if($AñoPago==$AñoFinal and $AñoPago<$añoActual)
                {
                        $impuestos_mora=$impuestos_mora+$impuestosValor;
                        $impuesto_año_actual=$impuesto_año_actual;
                }
                else if( $AñoPago==$AñoFinal and $AñoPago==$añoActual)
                {
                        $impuestos_mora=$impuestos_mora;
                        $impuesto_año_actual=$impuesto_año_actual+$impuestosValor;
                }else{
                        $impuestos_mora=$impuestos_mora+$impuestosValor;
                        $impuesto_año_actual=$impuesto_año_actual;
                }

                $linea="_____________________<<::>>";
                $divisiondefila=".....................";
   
   

                Log::info($AñoPago);
                Log::info($CantidadMeses);
                Log::info($tarifa);
                Log::info($impuestosValor);
                Log::info($impuestos_mora);
                Log::info($impuesto_año_actual);
                
                Log::info($AñoSumado);
                
                Log::info($f2);
                Log::info($divisiondefila);
                
                Log::info($linea);

            }   //** Termina el foreach */

            //** -------Inicia - Cálculo para multas por pago extemporaneo--------- */
            /* -------------------------------------------------------------------
               "Se determina una multa por día en mora, despues de haberse vencido 
               la fecha de pago y una vez haya transcurrido 60 días despues del 
               vencimiento de la fecha limite de pago".
               ------------------------------------------------------------------*/
               $TasaInteresDiaria=($tasa_interes/365);
               $InteresTotal=0;
               $MesDeMulta=Carbon::parse($FechaDeInicioMoratorio)->subDays(60);
               $contador=0;
               $fechaFinMeses=$f2->addMonthsNoOverflow(1);
               $intervalo2 = DateInterval::createFromDateString('1 Month');
               $periodo2 = new DatePeriod ($MesDeMulta, $intervalo2, $fechaFinMeses);
                    
               //** Inicia Foreach para cálculo por meses */
                    foreach ($periodo2 as $dt) 
                    {
                       $contador=$contador+1;
                       $divisiondefila=".....................";

  
                        $TarifaAñoMulta=Carbon::parse($MesDeMulta)->format('Y');
                            $Date1=Carbon::parse($MesDeMulta)->day(1);
                            $Date2=Carbon::parse($MesDeMulta)->endOfMonth();
                            
                            $MesDeMultaDiainicial=Carbon::parse($Date1)->format('Y-m-d'); 
                            $MesDeMultaDiaFinal=Carbon::parse($Date2)->format('Y-m-d'); 
                            
                
                        $Fecha60Sumada=Carbon::parse($MesDeMultaDiaFinal)->addDays(60); 
                        Log::info($Fecha60Sumada);
                        Log::info($f3);
                        if($f3>$Fecha60Sumada){
                        $CantidaDiasMesMulta=ceil($Fecha60Sumada->diffInDays($f3)); //**le tenia floatdiffInDays y funcinona bien  */
                        }else
                        {
                            $CantidaDiasMesMulta=ceil($Fecha60Sumada->diffInDays($f3));
                            $CantidaDiasMesMulta=-$CantidaDiasMesMulta;
                            
                        }
                        Log::info($CantidaDiasMesMulta);
                        
                        $tarifa=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                          ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMesas)
                              ->pluck('pago_mensual') 
                                  ->first();

                        $monto_matricula=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                          ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMesas)
                              ->pluck('monto_matricula') 
                                  ->first();
                    
                    $MesDeMulta->addMonthsNoOverflow(1)->format('Y-M');
  

                   //** INICIO- Determinar multa por pago extemporaneo. */
                   if($CantidaDiasMesMulta>0){                                                   
                        if($CantidaDiasMesMulta<=90)
                        {  
                                    $multaPagoExtemporaneo=round(($tarifa*0.05),2);
                                    $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo+$multaPagoExtemporaneo;
                                    $stop="Avanza:Multa";

                        }elseif($CantidaDiasMesMulta>=90)
                                {
                                    $multaPagoExtemporaneo=round(($tarifa*0.10),2);
                                    $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo+$multaPagoExtemporaneo;  
                                    $stop="Avanza:Multa";
                                }

                        //** INICIO-  Cálculando el interes. */
                        $Interes=round((($TasaInteresDiaria*$CantidaDiasMesMulta)/100*$tarifa),2);
                        $InteresTotal=$InteresTotal+$Interes;
                        //** FIN-  Cálculando el interes. */


                        
                    }
                    else
                        { 
                            $Interes=0;
                            $InteresTotal=$InteresTotal;
                            $multaPagoExtemporaneo=$multaPagoExtemporaneo;
                            $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo;
                            $stop="Alto:No multa";
                        }
                   //** FIN-  Determinar multa por pago extemporaneo. */

                   
                    Log::info($contador);
                    Log::info($stop);
                    Log::info($MesDeMultaDiainicial);                   
                    Log::info($MesDeMultaDiaFinal); 
                    Log::info($MesDeMulta);
                    Log::info($multaPagoExtemporaneo);
                    Log::info($totalMultaPagoExtemporaneo);
                    Log::info($Interes);
                    Log::info($InteresTotal);
                    Log::info($divisiondefila);
                    }//FIN - Foreach para meses multa
                
                 if($totalMultaPagoExtemporaneo>0 and $totalMultaPagoExtemporaneo<2.86)
                 {
                     $totalMultaPagoExtemporaneo=2.86;
                 }

             //** Para determinar si el permiso de una matricula ya fue pagada y Determinar multa por permiso matricula */ */

             $añoActual=carbon::now()->format('Y');
             $fecha_limiteMesas=Carbon::createFromDate($añoActual,03, 31);
             $fechahoy=carbon::now();

                /** Calculando las licencias*/
                $Cantidad_matriculas=0;
                $monto_pago_matricula=0;
                $multa=0;
                $fila='------------------';
                $fila2='_______________________';

                //** Inicia Foreach para calcular matriculas y sus multas */
                foreach ($periodo as $dt) {

                 $AñoCancelar=$dt->format('Y');
                
                 $año_calificacion=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleMesas)
                 ->where('año_calificacion',$AñoCancelar)
                 ->pluck('año_calificacion')
                 ->first();

                 $id_estado_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleMesas)
                 ->where('año_calificacion',$AñoCancelar)
                 ->pluck('id_estado_matricula')
                 ->first();

                 $monto_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleMesas)
                 ->where('año_calificacion',$AñoCancelar)
                 ->pluck('monto_matricula')
                 ->first();
                 
                 log::info($año_calificacion);
                 log::info($id_estado_matricula);
                 log::info($monto_matricula);
                 log::info($fila);

                if($id_estado_matricula=='2' and $año_calificacion<$añoActual)
                {
                         $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                         $Cantidad_matriculas=$Cantidad_matriculas+1;
                         $multa= $multa+$monto_matricula;
                         Log::info($monto_pago_matricula);
                         Log::info($Cantidad_matriculas);
                         Log::info($multa);
                         Log::info('IF1- Con Multa');
                         log::info($fila2);
                }else if($id_estado_matricula=='2' and $año_calificacion===$añoActual)
                {
                    if($fechahoy>$fecha_limiteMesas)
                    {

                     $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                     $Cantidad_matriculas=$Cantidad_matriculas+1;
                     $multa= $multa+$monto_matricula;
                     Log::info($monto_pago_matricula);
                     Log::info($Cantidad_matriculas);
                     Log::info($multa);
                     Log::info('IF2- Con Multa');
                     log::info($fila2);

                    }else 
                         {
                             $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                             $Cantidad_matriculas=$Cantidad_matriculas+1;
                             $multa=$multa;
                             Log::info($monto_pago_matricula);
                             Log::info($Cantidad_matriculas);
                             Log::info($multa);
                             Log::info('IF3 - Sin Multa');
                             log::info($fila2);
                         } 
                 }
             } //** Finaliza foreach para calcular multa matricula y calculo de las matriculas */                                   
          


             Log::info($monto_pago_matricula);
             Log::info($Cantidad_matriculas);
         
            
            if($request->estado=='On')
            {
                $impuestos_mora=0;
                $impuesto_año_actual=0;
                $totalMultaPagoExtemporaneo=0;
                $InteresTotal=0;
                $impuestoTotal=0;
                $Cantidad_MesesTotal=0;
            } 

            $fondoFPValor=round(($impuestoTotal*0.05)+($monto_pago_matricula*0.05),2);
            $totalPagoValor= round($fondoFPValor+$monto_pago_matricula+$impuestoTotal+$totalMultaPagoExtemporaneo+$InteresTotal+$multa,2);
            //Le agregamos su signo de dollar para la vista al usuario

            $fondoFP= "$". $fondoFPValor;     
            $impuestos_mora_Dollar="$".$impuestos_mora;
            $impuesto_año_actual_Dollar="$".$impuesto_año_actual;
            $multaPagoExtemporaneoDollar="$".$totalMultaPagoExtemporaneo;
            $InteresTotalDollar="$".$InteresTotal;
            $multaMatriculaDollar="$".$multa;
            $monto_pago_PmatriculaDollar="$".$monto_pago_matricula;
            $totalPagoMesasDollar="$".$totalPagoValor;

            //** Guardar cobro*/
            if ($request->cobrar=='1')
            {   
            $cobro = new CobrosMatriculas();
            $cobro->id_matriculas_detalle = $request->id_matriculadetalleMesas;
            $cobro->id_usuario = $idusuario;
            $cobro->cantidad_meses_cobro =$Cantidad_MesesTotal;
            $cobro->monto_multa_matricula = $multa;
            $cobro->fondo_fiestasP = $fondoFPValor;
            $cobro->impuesto_mora =$impuestos_mora;
            $cobro->impuesto =$impuesto_año_actual;
            $cobro->intereses_moratorios =$InteresTotal;
            $cobro->monto_multaPE =$totalMultaPagoExtemporaneo;
            $cobro->pago_total = $totalPagoValor;
            $cobro->fecha_cobro =  $fechahoy;
            if($request->estado=='On')
            {
               $cobro->periodo_cobro_inicioMatricula = $InicioPeriodo;
               $cobro->periodo_cobro_finMatricula =$PagoUltimoDiaMes;

            }else{
                   $cobro->periodo_cobro_inicio = $InicioPeriodo;
                   $cobro->periodo_cobro_fin =$PagoUltimoDiaMes;
               
                 }
            $cobro->tipo_cobro ='matricula';
            $cobro->save();
        
            if($multa>0)
            {
                foreach ($periodo as $dt) {
                    $AñoCancelar =$dt->format('Y');
                CalificacionMatriculas::where('id_matriculas_detalle',$request->id_matriculadetalleMesas)
                ->where('id_estado_matricula','2')
                ->where('año_calificacion',$AñoCancelar)
                ->update([
                            'id_estado_matricula' =>"1",              
                        ]);
                    }
            }

            return ['success' => 2];
            

            }else{

            return [
                'success' => 1,
                'impuestoTotalMesas'=>$impuestoTotal,
                'impuestos_mora_DollarMesas'=>$impuestos_mora_Dollar,
                'impuesto_año_actual_DollarMesas'=>$impuesto_año_actual_Dollar,
                'Cantidad_MesesTotalMesas'=>$Cantidad_MesesTotal,          
                'tarifaMesas'=>$tarifa,
                'fondoFPMesas'=>$fondoFP,
                'DiasinteresMoratorioMesas'=>$DiasinteresMoratorio,
                'InicioPeriodoMesas'=>$InicioPeriodo,
                'PagoUltimoDiaMesMesas'=>$PagoUltimoDiaMes,
                'FechaDeInicioMoratorioMesas'=> $FechaDeInicioMoratorio,
                'multaPagoExtemporaneoDollarMesas'=> $multaPagoExtemporaneoDollar,
                'totalMultaPagoExtemporaneoMesas'=>$multaPagoExtemporaneoDollar,
                'InteresTotalDollar'=>$InteresTotalDollar,
                'monto_pago_PmatriculaDollarMesas'=>$monto_pago_PmatriculaDollar,
                'multa_por_matricula'=>$multaMatriculaDollar,
                'totalPagoMesas'=>$totalPagoMesasDollar,
          
               ];
            } //** Termina if validador de la fecha de ultimo pago no puede ser mayor que la de pagara */
        
        } else //** if principal */
               {
                   return ['success' => 0];
               }
}//** ------------------ Termina cálculo para cobrar la matrícula de MESAS DE BILLAR ---------------------------- */


public function calculo_cobroMaquinas(Request $request){
    log::info($request->all());

    $idusuario = Auth::id();
    $id_empresa=$request->id;
    $fechaPagaraMaquinas=$request->fechaPagaraMaquinas;
    $id_matriculadetalleMaquinas=$request->id_matriculadetalleMaquinas;
    $tasa_interes=$request->tasa_interesMaquinas;
    $Message=0;

    $MesNumero=Carbon::createFromDate($request->ultimo_cobroMaquinas)->format('d');
    //log::info($MesNumero);

    if($MesNumero<='15')
    {
        $f1=Carbon::parse($request->ultimo_cobroMaquinas)->format('Y-m-01');
        $f1=Carbon::parse($f1);
        $InicioPeriodo=Carbon::createFromDate($f1);
        $InicioPeriodo= $InicioPeriodo->format('Y-m-d');
        //log::info('inicio de mes');
    }
    else
        {
         $f1=Carbon::parse($request->ultimo_cobroMaquinas)->addMonthsNoOverflow(1)->day(1);
         $InicioPeriodo=Carbon::parse($request->ultimo_cobroMaquinas)->addMonthsNoOverflow(1)->day(1)->format('Y-m-d');
        // log::info('fin de mes ');
         }

    
    $f2=Carbon::parse($request->fechaPagaraMaquinas);
    $f3=Carbon::parse($request->fecha_interesMoratorioMaquinas);
    $añoActual=Carbon::now()->format('Y');
   
    //** Inicia - Para determinar el intervalo de años a pagar */
    $monthInicio='01';
    $dayInicio='01';
    $monthFinal='12';
    $dayFinal='31';
    $AñoInicio=$f1->format('Y');
    $AñoFinal=$f2->format('Y');
    $FechaInicio=Carbon::createFromDate($AñoInicio, $monthInicio, $dayInicio);
    $FechaFinal=Carbon::createFromDate($AñoFinal, $monthFinal, $dayFinal);
    //** Finaliza - Para determinar el intervalo de años a pagar */

 
    //** INICIO - Para obtener SIEMPRE el último día del mes que selecciono el usuario */
    $PagoUltimoDiaMes=Carbon::parse($request->fechaPagaraMaquinas)->endOfMonth()->format('Y-m-d');
    //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */
    Log::info('Pago ultimo dia del mes---->' .$PagoUltimoDiaMes);

    //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
    $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
    Log::info('ultimo dia del mes---->' .$UltimoDiaMes);
    $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(30)->format('Y-m-d');
    Log::info($FechaDeInicioMoratorio);
    
    $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
    $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
    //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
     Log::info($DiasinteresMoratorio);
   
     $calificacionesMaquinas = CalificacionMatriculas::latest()
        
     ->join('matriculas_detalle','calificacion_matriculas.id_matriculas_detalle','=','matriculas_detalle.id')
     
     ->select('calificacion_matriculas.id','calificacion_matriculas.nombre_matricula','calificacion_matriculas.cantidad','calificacion_matriculas.monto_matricula','calificacion_matriculas.pago_mensual','calificacion_matriculas.año_calificacion','calificacion_matriculas.estado_calificacion','calificacion_matriculas.id_estado_matricula',
     'matriculas_detalle.id as id_matriculadetalle','matriculas_detalle.id_empresa',)
 
     ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMaquinas)
     ->first();

   

        if($f1->lt($PagoUltimoDiaMes))
        {

            $intervalo = DateInterval::createFromDateString('1 Year');
            $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);

            $Cantidad_MesesTotal=0;
            $impuestoTotal=0;
            $impuestos_mora=0;
            $impuesto_año_actual=0;

           
            //** Inicia Foreach para cálculo de impuesto por años de la matricula mesas de billar */
            foreach ($periodo as $dt) {

                $AñoPago =$dt->format('Y');
               
                $AñoSumado=Carbon::createFromDate($AñoPago, 12, 31);

                
                $tarifa=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMaquinas)
                    ->pluck('pago_mensual') 
                        ->first();
                
         
                        if($AñoPago==$AñoFinal)//Stop para cambiar el resultado de la cantidad de meses en la última vuelta del foreach...
                            {
                                $CantidadMeses=ceil(($f1->floatDiffInRealMonths($PagoUltimoDiaMes)));
                            }
                        else
                            {

                                $CantidadMeses=ceil(($f1->floatDiffInRealMonths($AñoSumado)));  
                                $f1=$f1->addYears(1)->month(1)->day(1);
   
                            }

                //*** calculo */
       
                $impuestosValor=(round($tarifa*$CantidadMeses,2));
                $impuestoTotal=$impuestoTotal+$impuestosValor;
                $Cantidad_MesesTotal=$Cantidad_MesesTotal+$CantidadMeses;

                if($AñoPago==$AñoFinal and $AñoPago<$añoActual)
                {
                        $impuestos_mora=$impuestos_mora+$impuestosValor;
                        $impuesto_año_actual=$impuesto_año_actual;
                }
                else if( $AñoPago==$AñoFinal and $AñoPago==$añoActual)
                {
                        $impuestos_mora=$impuestos_mora;
                        $impuesto_año_actual=$impuesto_año_actual+$impuestosValor;
                }else{
                        $impuestos_mora=$impuestos_mora+$impuestosValor;
                        $impuesto_año_actual=$impuesto_año_actual;
                }

                $linea="_____________________<<::>>";
                $divisiondefila=".....................";
   
   

                Log::info($AñoPago);
                Log::info($CantidadMeses);
                Log::info($tarifa);
                Log::info($impuestosValor);
                Log::info($impuestos_mora);
                Log::info($impuesto_año_actual);
                
                Log::info($AñoSumado);
                
                Log::info($f2);
                Log::info($divisiondefila);
                
                Log::info($linea);

            }   //** Termina el foreach */

            //** -------Inicia - Cálculo para determinar el interes moratorio--------- */

               $TasaInteresDiaria=($tasa_interes/365);
               $InteresTotal=0;
               $MesMora=Carbon::parse($FechaDeInicioMoratorio)->subDays(30);
               Log::info( $MesMora);
               $contador=0;
               $fechaFinMeses=$f2->addMonthsNoOverflow(1);
               $intervalo2 = DateInterval::createFromDateString('1 Month');
               $periodo2 = new DatePeriod ( $MesMora, $intervalo2, $fechaFinMeses);
                    
               //** Inicia Foreach para cálculo por meses */
                    foreach ($periodo2 as $dt) 
                    {
                       $contador=$contador+1;
                       $divisiondefila=".....................";

                            $Date1=Carbon::parse( $MesMora)->day(1);
                            $Date2=Carbon::parse( $MesMora)->endOfMonth();
                            
                            $MesDeMultaDiainicial=Carbon::parse($Date1)->format('Y-m-d'); 
                            $MesDeMultaDiaFinal=Carbon::parse($Date2)->format('Y-m-d'); 
   
                        $Fecha30Sumada=Carbon::parse($MesDeMultaDiaFinal)->addDays(30); 
                        Log::info($Fecha30Sumada);
                        Log::info($f3);
                        if($f3>$Fecha30Sumada){
                        $CantidaDiasMesMulta=ceil($Fecha30Sumada->diffInDays($f3)); //**le tenia floatdiffInDays y funcinona bien  */
                        }else
                        {
                            $CantidaDiasMesMulta=ceil($Fecha30Sumada->diffInDays($f3));
                            $CantidaDiasMesMulta=-$CantidaDiasMesMulta;
                            
                        }
                        Log::info($CantidaDiasMesMulta);
                        
                        $tarifa=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                          ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleMaquinas)
                              ->pluck('pago_mensual') 
                                  ->first();

                    
                        $MesMora->addMonthsNoOverflow(1)->format('Y-M');
  

                   //** INICIO- Determinar interes total a pagar */
                   if($CantidaDiasMesMulta>0){  

                        //** INICIO-  Cálculando el interes. */
                        $Interes=round((($TasaInteresDiaria*$CantidaDiasMesMulta)/100*$tarifa),2);
                        $InteresTotal=$InteresTotal+$Interes;
                        //** FIN-  Cálculando el interes. */  

                    }
                    else
                        { 
                            $Interes=0;
                            $InteresTotal=$InteresTotal;
                        }
                   //** FIN- Determinar interes total a pagar. */

                   
                    Log::info($contador);
                    Log::info($MesDeMultaDiainicial);                   
                    Log::info($MesDeMultaDiaFinal); 
                    Log::info($MesMora);
                    Log::info($Interes);
                    Log::info($InteresTotal);
                    Log::info($divisiondefila);
                    }//FIN - Foreach para meses multa


             //** Para determinar si el permiso de una matricula ya fue pagada y Determinar multa por permiso matricula */ */
             
             $añoActual=carbon::now()->format('Y');
             $fecha_limiteMaquinas=Carbon::createFromDate($añoActual,03, 31);
             $fechahoy=carbon::now();

                /** Calculando las licencias*/
                $Cantidad_matriculas=0;
                $monto_pago_matricula=0;
                $multa=0;
                $fila='------------------';
                $fila2='_______________________';

                //** Inicia Foreach para calcular matriculas y sus multas */
                foreach ($periodo as $dt) {

                 $AñoCancelar=$dt->format('Y');
                
                 $año_calificacion=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleMaquinas)
                 ->where('año_calificacion',$AñoCancelar)
                 ->pluck('año_calificacion')
                 ->first();

                 $id_estado_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleMaquinas)
                 ->where('año_calificacion',$AñoCancelar)
                 ->pluck('id_estado_matricula')
                 ->first();

                 $monto_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleMaquinas)
                 ->where('año_calificacion',$AñoCancelar)
                 ->pluck('monto_matricula')
                 ->first();
                 
                 log::info($año_calificacion);
                 log::info($id_estado_matricula);
                 log::info($monto_matricula);
                 log::info($fila);

                if($id_estado_matricula=='2' and $año_calificacion<$añoActual)
                {
                         $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                         $Cantidad_matriculas=$Cantidad_matriculas+1;
                         $multa= $multa+$monto_matricula;
                         Log::info($monto_pago_matricula);
                         Log::info($Cantidad_matriculas);
                         Log::info($multa);
                         Log::info('IF1- Con Multa');
                         log::info($fila2);
                }else if($id_estado_matricula=='2' and $año_calificacion===$añoActual)
                {
                    if($fechahoy>$fecha_limiteMaquinas)
                    {

                     $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                     $Cantidad_matriculas=$Cantidad_matriculas+1;
                     $multa= $multa+$monto_matricula;
                     Log::info($monto_pago_matricula);
                     Log::info($Cantidad_matriculas);
                     Log::info($multa);
                     Log::info('IF2- Con Multa');
                     log::info($fila2);

                    }else 
                         {
                             $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                             $Cantidad_matriculas=$Cantidad_matriculas+1;
                             $multa=$multa;
                             Log::info($monto_pago_matricula);
                             Log::info($Cantidad_matriculas);
                             Log::info($multa);
                             Log::info('IF3 - Sin Multa');
                             log::info($fila2);
                         } 
                 }
             } //** Finaliza foreach para calcular multa matricula y calculo de las matriculas */                                   
 
             Log::info($monto_pago_matricula);
             Log::info($Cantidad_matriculas);
             Log::info($multa);

             if($request->estado=='On')
             {
                 $impuestos_mora=0;
                 $impuesto_año_actual=0;
                 $InteresTotal=0;
                 $impuestoTotal=0;
                 $Cantidad_MesesTotal=0;
             } 

            $fondoFPValor=round(($impuestoTotal*0.05)+($monto_pago_matricula*0.05),2);
            $totalPagoValor= round($fondoFPValor+$monto_pago_matricula+$impuestoTotal+$InteresTotal+$multa,2);

            //Le agregamos su signo de dollar para la vista al usuario
            $fondoFP= "$". $fondoFPValor;     
            $totalPagoMatriculasDollar="$".$totalPagoValor;
            $impuestos_mora_Dollar="$".$impuestos_mora;
            $impuesto_año_actual_Dollar="$".$impuesto_año_actual;
            $InteresTotalDollar="$".$InteresTotal;
            $monto_pago_PmatriculaDollar="$".$monto_pago_matricula;
            $multaDolarMaquinas="$".$multa;


            //** Guardar cobro*/
            if ($request->cobrar=='1')
            {   

            $cobro = new CobrosMatriculas();
            $cobro->id_matriculas_detalle = $request->id_matriculadetalleMaquinas;
            $cobro->id_usuario = $idusuario;
            $cobro->cantidad_meses_cobro =$Cantidad_MesesTotal;
            $cobro->monto_multa_matricula = $multa;
            $cobro->fondo_fiestasP = $fondoFPValor;
            $cobro->impuesto_mora =$impuestos_mora;
            $cobro->impuesto =$impuesto_año_actual;
            $cobro->intereses_moratorios =$InteresTotal;
            $cobro->pago_total = $totalPagoValor;
            $cobro->fecha_cobro =  $fechahoy;
            if($request->estado=='On')
             {
                $cobro->periodo_cobro_inicioMatricula = $InicioPeriodo;
                $cobro->periodo_cobro_finMatricula =$PagoUltimoDiaMes;

             }else{
                    $cobro->periodo_cobro_inicio = $InicioPeriodo;
                    $cobro->periodo_cobro_fin =$PagoUltimoDiaMes;
                
                  }
            $cobro->tipo_cobro ='matricula';
            $cobro->save();

            if($multa>0)
            {
                foreach ($periodo as $dt) {
                $AñoCancelar =$dt->format('Y');
                CalificacionMatriculas::where('id_matriculas_detalle',$request->id_matriculadetalleMaquinas)
                 ->where('id_estado_matricula','2')
                 ->where('año_calificacion',$AñoCancelar)
                    ->update([
                            'id_estado_matricula' =>"1",              
                        ]);

                }

            }
        
            return ['success' => 2];
            

            }else{

                    return [
                        'success' => 1,
                        'impuestoTotalMaquinas'=>$impuestoTotal,
                        'impuestos_mora_DollarMaquinas'=>$impuestos_mora_Dollar,
                        'impuesto_año_actual_DollarMaquinas'=>$impuesto_año_actual_Dollar,
                        'Cantidad_MesesTotalMaquinas'=>$Cantidad_MesesTotal,          
                        'tarifaMaquinas'=>$tarifa,
                        'fondoFPMaquinas'=>$fondoFP,
                        'DiasinteresMoratorioMaquinas'=>$DiasinteresMoratorio,
                        'InicioPeriodoMaquinas'=>$InicioPeriodo,
                        'PagoUltimoDiaMesMaquinas'=>$PagoUltimoDiaMes,
                        'FechaDeInicioMoratorioMaquinas'=> $FechaDeInicioMoratorio,
                        'InteresTotalDollar'=>$InteresTotalDollar,
                        'monto_pago_PmatriculaDollarMaquinas'=>$monto_pago_PmatriculaDollar,
                        'multaDolarMaquinas'=>$multaDolarMaquinas,
                        'totalPagoMaquinas'=>$totalPagoMatriculasDollar,
                    ];
                } //** Termina if validador de la fecha de ultimo pago no puede ser mayor que la de pagara */
            } 
            else
                {
                    return ['success' => 0];
                }

}//** ------------------ Cálculo para cobrar la matrícula de MAQUINAS ELÉCTRONICAS ------------------------------- */


//** ------------------ Cálculo para cobrar la matrícula de SINFONOLAS ----------------------------------- */
public function calculo_cobroSinfonolas(Request $request){
    log::info($request->all());

    $idusuario = Auth::id();
    $MesNumero=Carbon::createFromDate($request->ultimo_cobroSinfonolas)->format('d');
    $id_empresa=$request->id;
    $fechaPagaraSinfonolas=$request->fechaPagaraSinfonolas;
    $id_matriculadetalleSinfonolas=$request->id_matriculadetalleSinfonolas;
    $tasa_interes=$request->tasa_interesSinfonolas;
    $fecha_interesMoratorio=$request->fecha_interesMoratorioSinfonolas;
    $Message=0;

    if($MesNumero<='15')
    {
        $f1=Carbon::parse($request->ultimo_cobroSinfonolas)->format('Y-m-01');
        $f1=Carbon::parse($f1);
        $InicioPeriodo=Carbon::createFromDate($f1);
        $InicioPeriodo= $InicioPeriodo->format('Y-m-d');
        log::info('inicio de mes');
    }
    else
        {
         $f1=Carbon::parse($request->ultimo_cobroSinfonolas)->addMonthsNoOverflow(1)->day(1);
         $InicioPeriodo=Carbon::parse($request->ultimo_cobroSinfonolas)->addMonthsNoOverflow(1)->day(1)->format('Y-m-d');
        log::info('fin de mes ');
         }
    $f2=Carbon::parse($request->fechaPagaraSinfonolas);
    $f3=Carbon::parse($fecha_interesMoratorio);
    $añoActual=Carbon::now()->format('Y');
   
    //** Inicia - Para determinar el intervalo de años a pagar */
    $monthInicio='01';
    $dayInicio='01';
    $monthFinal='12';
    $dayFinal='31';
    $AñoInicio=$f1->format('Y');
    $AñoFinal=$f2->format('Y');
    $FechaInicio=Carbon::createFromDate($AñoInicio, $monthInicio, $dayInicio);
    $FechaFinal=Carbon::createFromDate($AñoFinal, $monthFinal, $dayFinal);
    //** Finaliza - Para determinar el intervalo de años a pagar */

 
    //** INICIO - Para obtener SIEMPRE el último día del mes que selecciono el usuario */
    $PagoUltimoDiaMes=Carbon::parse($fechaPagaraSinfonolas)->endOfMonth()->format('Y-m-d');
    //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */

     //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
     $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
     $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(60)->format('Y-m-d');

     Log::info($FechaDeInicioMoratorio);
     $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
     $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
     //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
     Log::info($DiasinteresMoratorio);
   
     $calificacionesSinfonolas = CalificacionMatriculas::latest()
        
     ->join('matriculas_detalle','calificacion_matriculas.id_matriculas_detalle','=','matriculas_detalle.id')
     
     ->select('calificacion_matriculas.id','calificacion_matriculas.nombre_matricula','calificacion_matriculas.cantidad','calificacion_matriculas.monto_matricula','calificacion_matriculas.pago_mensual','calificacion_matriculas.año_calificacion','calificacion_matriculas.estado_calificacion','calificacion_matriculas.id_estado_matricula',
     'matriculas_detalle.id as id_matriculadetalle','matriculas_detalle.id_empresa',)
 
     ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleSinfonolas)
     ->first();

   

        if($f1->lt($PagoUltimoDiaMes))
        {

            $intervalo = DateInterval::createFromDateString('1 Year');
            $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);

            $Cantidad_MesesTotal=0;
            $impuestoTotal=0;
            $impuestos_mora=0;
            $impuesto_año_actual=0;
            $multaPagoExtemporaneo=0;
         
            $totalMultaPagoExtemporaneo=0;
           
            //** Inicia Foreach para cálculo de impuesto por años de la matricula mesas de billar */
            foreach ($periodo as $dt) {

                $AñoPago =$dt->format('Y');
               
                $AñoSumado=Carbon::createFromDate($AñoPago, 12, 31);

                
                $tarifa=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleSinfonolas)
                    ->pluck('pago_mensual') 
                        ->first();
                
         
                        if($AñoPago==$AñoFinal)//Stop para cambiar el resultado de la cantidad de meses en la última vuelta del foreach...
                            {
                                $CantidadMeses=ceil(($f1->floatDiffInRealMonths($PagoUltimoDiaMes)));
                            }
                        else
                            {

                                $CantidadMeses=ceil(($f1->floatDiffInRealMonths($AñoSumado)));  
                                $f1=$f1->addYears(1)->month(1)->day(1);
   
                            }

                //*** calculo */
       
                $impuestosValor=(round($tarifa*$CantidadMeses,2));
                $impuestoTotal=$impuestoTotal+$impuestosValor;
                $Cantidad_MesesTotal=$Cantidad_MesesTotal+$CantidadMeses;

                if($AñoPago==$AñoFinal and $AñoPago<$añoActual)
                {
                        $impuestos_mora=$impuestos_mora+$impuestosValor;
                        $impuesto_año_actual=$impuesto_año_actual;
                }
                else if( $AñoPago==$AñoFinal and $AñoPago==$añoActual)
                {
                        $impuestos_mora=$impuestos_mora;
                        $impuesto_año_actual=$impuesto_año_actual+$impuestosValor;
                }else{
                        $impuestos_mora=$impuestos_mora+$impuestosValor;
                        $impuesto_año_actual=$impuesto_año_actual;
                }

                $linea="_____________________<<::>>";
                $divisiondefila=".....................";
   
   

                Log::info($AñoPago);
                Log::info($CantidadMeses);
                Log::info($tarifa);
                Log::info($impuestosValor);
                Log::info($impuestos_mora);
                Log::info($impuesto_año_actual);
                
                Log::info($AñoSumado);
                
                Log::info($f2);
                Log::info($divisiondefila);
                
                Log::info($linea);

            }   //** Termina el foreach */

            //** -------Inicia - Cálculo para multas por pago extemporaneo--------- */
            /* -------------------------------------------------------------------
               "Se determina una multa por día en mora, despues de haberse vencido 
               la fecha de pago y una vez haya transcurrido 60 días despues del 
               vencimiento de la fecha limite de pago".
               ------------------------------------------------------------------*/
               $TasaInteresDiaria=($tasa_interes/365);
               $InteresTotal=0;
               $MesDeMulta=Carbon::parse($FechaDeInicioMoratorio)->subDays(60);
               $contador=0;
               $fechaFinMeses=$f2->addMonthsNoOverflow(1);
               $intervalo2 = DateInterval::createFromDateString('1 Month');
               $periodo2 = new DatePeriod ($MesDeMulta, $intervalo2, $fechaFinMeses);
                    
               //** Inicia Foreach para cálculo de multas por por pago extemporaneos e interese moratorios */
                    foreach ($periodo2 as $dt) 
                    {
                       $contador=$contador+1;
                       $divisiondefila=".....................";

  
                        $TarifaAñoMulta=Carbon::parse($MesDeMulta)->format('Y');
                            $Date1=Carbon::parse($MesDeMulta)->day(1);
                            $Date2=Carbon::parse($MesDeMulta)->endOfMonth();
                            
                            $MesDeMultaDiainicial=Carbon::parse($Date1)->format('Y-m-d'); 
                            $MesDeMultaDiaFinal=Carbon::parse($Date2)->format('Y-m-d'); 
                            
                
                        $Fecha60Sumada=Carbon::parse($MesDeMultaDiaFinal)->addDays(60); 
                        Log::info($Fecha60Sumada);
                        Log::info($f3);
                        if($f3>$Fecha60Sumada){
                        $CantidaDiasMesMulta=ceil($Fecha60Sumada->diffInDays($f3)); //**le tenia floatdiffInDays y funcinona bien  */
                        }else
                        {
                            $CantidaDiasMesMulta=ceil($Fecha60Sumada->diffInDays($f3));
                            $CantidaDiasMesMulta=-$CantidaDiasMesMulta;
                            
                        }
                        Log::info($CantidaDiasMesMulta);
                        
                        $tarifa=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                          ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleSinfonolas)
                              ->pluck('pago_mensual') 
                                  ->first();

                        $monto_Sinfonolas=CalificacionMatriculas::where('año_calificacion','=',$AñoPago)
                          ->where('calificacion_matriculas.id_matriculas_detalle',$id_matriculadetalleSinfonolas)
                              ->pluck('monto_matricula') 
                                  ->first();
                    
                    $MesDeMulta->addMonthsNoOverflow(1)->format('Y-M');
  

                   //** INICIO- Determinar multa por pago extemporaneo. */
                   if($CantidaDiasMesMulta>0){                                                   
                        if($CantidaDiasMesMulta<=90)
                        {  
                                    $multaPagoExtemporaneo=round(($tarifa*0.05),2);
                                    $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo+$multaPagoExtemporaneo;
                                    $stop="Avanza:Multa";

                        }elseif($CantidaDiasMesMulta>=90)
                                {
                                    $multaPagoExtemporaneo=round(($tarifa*0.10),2);
                                    $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo+$multaPagoExtemporaneo;  
                                    $stop="Avanza:Multa";
                                }

                        //** INICIO-  Cálculando el interes. */
                        $Interes=round((($TasaInteresDiaria*$CantidaDiasMesMulta)/100*$tarifa),2);
                        $InteresTotal=$InteresTotal+$Interes;
                        //** FIN-  Cálculando el interes. */


                        
                    }
                    else
                        { 
                            $Interes=0;
                            $InteresTotal=$InteresTotal;
                            $multaPagoExtemporaneo=$multaPagoExtemporaneo;
                            $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo;
                            $stop="Alto:No multa";
                        }
                   //** FIN-  Determinar multa por pago extemporaneo. */

                   
                    Log::info($contador);
                    Log::info($stop);
                    Log::info($MesDeMultaDiainicial);                   
                    Log::info($MesDeMultaDiaFinal); 
                    Log::info($MesDeMulta);
                    Log::info($multaPagoExtemporaneo);
                    Log::info($totalMultaPagoExtemporaneo);
                    Log::info($Interes);
                    Log::info($InteresTotal);
                    Log::info($divisiondefila);
                    }//FIN - Foreach para meses multa
                
                 if($totalMultaPagoExtemporaneo>0 and $totalMultaPagoExtemporaneo<2.86)
                 {
                     $totalMultaPagoExtemporaneo=2.86;
                 }

             //** Para determinar si el permiso de una matricula ya fue pagada y Determinar multa por permiso matricula */ */

                $añoActual=carbon::now()->format('Y');
                $fecha_limiteSinfonolas=Carbon::createFromDate($añoActual,03, 31);
                $fechahoy=carbon::now();

                   /** Calculando las licencias*/
                   $Cantidad_matriculas=0;
                   $monto_pago_matricula=0;
                   $multa=0;
                   $fila='------------------';
                   $fila2='_______________________';

                   //** Inicia Foreach para calcular matriculas y sus multas */
                   foreach ($periodo as $dt) {

                    $AñoCancelar=$dt->format('Y');
                   
                    $año_calificacion=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleSinfonolas)
                    ->where('año_calificacion',$AñoCancelar)
                    ->pluck('año_calificacion')
                    ->first();

                    $id_estado_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleSinfonolas)
                    ->where('año_calificacion',$AñoCancelar)
                    ->pluck('id_estado_matricula')
                    ->first();

                    $monto_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleSinfonolas)
                    ->where('año_calificacion',$AñoCancelar)
                    ->pluck('monto_matricula')
                    ->first();
                    
                    log::info($año_calificacion);
                    log::info($id_estado_matricula);
                    log::info($monto_matricula);
                    log::info($fila);

                   if($id_estado_matricula=='2' and $año_calificacion<$añoActual)
                   {
                            $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                            $Cantidad_matriculas=$Cantidad_matriculas+1;
                            $multa= $multa+$monto_matricula;
                            Log::info($monto_pago_matricula);
                            Log::info($Cantidad_matriculas);
                            Log::info($multa);
                            Log::info('IF1- Con Multa');
                            log::info($fila2);
                   }else if($id_estado_matricula=='2' and $año_calificacion===$añoActual)
                   {
                       if($fechahoy>$fecha_limiteSinfonolas)
                       {

                        $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                        $Cantidad_matriculas=$Cantidad_matriculas+1;
                        $multa= $multa+$monto_matricula;
                        Log::info($monto_pago_matricula);
                        Log::info($Cantidad_matriculas);
                        Log::info($multa);
                        Log::info('IF2- Con Multa');
                        log::info($fila2);

                       }else 
                            {
                                $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                                $Cantidad_matriculas=$Cantidad_matriculas+1;
                                $multa=$multa;
                                Log::info($monto_pago_matricula);
                                Log::info($Cantidad_matriculas);
                                Log::info($multa);
                                Log::info('IF3 - Sin Multa');
                                log::info($fila2);
                            } 
                    }
                } //** Finaliza foreach para calcular multa matricula y calculo de las matriculas */                                   
             


             Log::info($monto_pago_matricula);
             Log::info($Cantidad_matriculas);

             if($request->estado=='On')
             {
                 $impuestos_mora=0;
                 $impuesto_año_actual=0;
                 $totalMultaPagoExtemporaneo=0;
                 $InteresTotal=0;
                 $impuestoTotal=0;
                 $Cantidad_MesesTotal=0;
             } 

            $fondoFPValor=round(($impuestoTotal*0.05)+($monto_pago_matricula*0.05),2);
            $totalPagoValor= round($fondoFPValor+$monto_pago_matricula+$impuestoTotal+$totalMultaPagoExtemporaneo+$InteresTotal+$multa,2);

            //Le agregamos su signo de dollar para la vista al usuario
            $fondoFP= "$". $fondoFPValor;     
            $totalPagoSinfonolasDollar="$".$totalPagoValor;
            $impuestos_mora_Dollar="$".$impuestos_mora;
            $impuesto_año_actual_Dollar="$".$impuesto_año_actual;
            $multaPagoExtemporaneoDollar="$".$totalMultaPagoExtemporaneo;
            $InteresTotalDollar="$".$InteresTotal;
            $multaMatriculaDollar="$".$multa;
            $monto_pago_PmatriculaDollar="$".$monto_pago_matricula;

            //** Guardar cobro*/
            if ($request->cobrar=='1')
            {   
            $cobro = new CobrosMatriculas();
            $cobro->id_matriculas_detalle = $request->id_matriculadetalleSinfonolas;
            $cobro->id_usuario = $idusuario;
            $cobro->cantidad_meses_cobro =$Cantidad_MesesTotal;
            $cobro->monto_multa_matricula = $multa;
            $cobro->fondo_fiestasP = $fondoFPValor;
            $cobro->impuesto_mora =$impuestos_mora;
            $cobro->impuesto =$impuesto_año_actual;
            $cobro->intereses_moratorios =$InteresTotal;
            $cobro->monto_multaPE =$totalMultaPagoExtemporaneo;
            $cobro->pago_total = $totalPagoValor;
            $cobro->fecha_cobro =  $fechahoy;
            if($request->estado=='On')
            {
               $cobro->periodo_cobro_inicioMatricula = $InicioPeriodo;
               $cobro->periodo_cobro_finMatricula =$PagoUltimoDiaMes;

            }else{
                   $cobro->periodo_cobro_inicio = $InicioPeriodo;
                   $cobro->periodo_cobro_fin =$PagoUltimoDiaMes;
               
                 }
            $cobro->tipo_cobro ='matricula';
            $cobro->save();

            if($multa>0)
            {
                foreach ($periodo as $dt) {
                    $AñoCancelar =$dt->format('Y');
                CalificacionMatriculas::where('id_matriculas_detalle',$request->id_matriculadetalleSinfonolas)
                ->where('id_estado_matricula','2')
                ->where('año_calificacion',$AñoCancelar)
                ->update([
                            'id_estado_matricula' =>"1",              
                        ]);
                    }
            }
            
            return ['success' => 2];
            

        }else{
                
            return [
                'success' => 1,
                'impuestoTotalSinfonolas'=>$impuestoTotal,
                'impuestos_mora_DollarSinfonolas'=>$impuestos_mora_Dollar,
                'impuesto_año_actual_DollarSinfonolas'=>$impuesto_año_actual_Dollar,
                'Cantidad_MesesTotalSinfonolas'=>$Cantidad_MesesTotal,          
                'tarifaSinfonolas'=>$tarifa,
                'fondoFPSinfonolas'=>$fondoFP,
                'DiasinteresMoratorioSinfonolas'=>$DiasinteresMoratorio,
                'InicioPeriodoSinfonolas'=>$InicioPeriodo,
                'PagoUltimoDiaMesSinfonolas'=>$PagoUltimoDiaMes,
                'FechaDeInicioMoratorioSinfonolas'=> $FechaDeInicioMoratorio,
                'multaPagoExtemporaneoDollarSinfonolas'=> $multaPagoExtemporaneoDollar,
                'totalMultaPagoExtemporaneoSinfonolas'=>$multaPagoExtemporaneoDollar,
                'InteresTotalDollar'=>$InteresTotalDollar,
                'monto_pago_PmatriculaDollarSinfonolas'=>$monto_pago_PmatriculaDollar,
                'multa_por_matricula'=>$multaMatriculaDollar,
                'totalPagoSinfonolas'=>$totalPagoSinfonolasDollar,
          
               ];
            
        } //** Termina if validador de la fecha de ultimo pago no puede ser mayor que la de pagara */
     } else
            {
                return ['success' => 0];
            }
        
}//** ------------------ Termina cálculo para cobrar la matrícula de SINFONOLAS ---------------------------- */

//** ------------------ Cálculo para cobrar la matrícula de APARATOS PARLANTES ----------------------------------- */
public function calculo_cobroAparatos(Request $request){
    log::info($request->all());

    $idusuario = Auth::id();
    $id_empresa=$request->id;
    $fechaPagaraAparatos=carbon::parse($request->fecha_pagaraAparatos)->format('Y-12-31');
    $id_matriculadetalleAparatos=$request->id_matriculadetalleAparatos;
    $tasa_interes=$request->tasa_interesAparatos;

    $MesNumero=Carbon::createFromDate($request->ultimo_cobroAparatos)->format('d');
    //log::info($MesNumero);

    if($MesNumero<='15')
    {
        $f1=Carbon::parse($request->ultimo_cobroAparatos)->format('Y-m-01');
        $f1=Carbon::parse($f1);
        $InicioPeriodo=Carbon::createFromDate($f1);
        $InicioPeriodo= $InicioPeriodo->format('Y-m-d');
        //log::info('inicio de mes');
    }
    else
        {
         $f1=Carbon::parse($request->ultimo_cobroultimo_cobroAparatos)->addMonthsNoOverflow(1)->day(1);
         $InicioPeriodo=Carbon::parse($request->ultimo_cobroAparatos)->addMonthsNoOverflow(1)->day(1)->format('Y-m-d');
        // log::info('fin de mes ');
         }

    
    $f2=Carbon::parse($fechaPagaraAparatos);
    $f3=carbon::now();
    $añoActual=Carbon::now()->format('Y');
   
    //** Inicia - Para determinar el intervalo de años a pagar */
    $monthInicio='01';
    $dayInicio='01';
    $monthFinal='12';
    $dayFinal='31';
    $AñoInicio=$f1->format('Y');
    $AñoFinal=$f2->format('Y');
    $FechaInicio=Carbon::createFromDate($AñoInicio, $monthInicio, $dayInicio);
    $FechaFinal=Carbon::createFromDate($AñoFinal, $monthFinal, $dayFinal);
    //** Finaliza - Para determinar el intervalo de años a pagar */



    if($f1->lt($fechaPagaraAparatos))
    {

                $añoActual=carbon::now()->format('Y');
         
                $fecha_limite=Carbon::createFromDate($añoActual,03, 31);
                $fechahoy=carbon::now();
                //$fechahoy='2022-02-17';

                 $Cantidad_matriculas=0;
                 $monto_pago_matricula=0;
                 $multa=0;

                 $intervalo = DateInterval::createFromDateString('1 Year');
                 $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);
      
                 $fila='------------------';
                 $fila2='_______________________';
                 foreach ($periodo as $dt) {
                    $AñoPago =$dt->format('Y');
                    $año_calificacion=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleAparatos)
                    ->where('año_calificacion',$AñoPago)
                    ->pluck('año_calificacion')
                    ->first();

                    $id_estado_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleAparatos)
                    ->where('año_calificacion',$AñoPago)
                    ->pluck('id_estado_matricula')
                    ->first();

                    $monto_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$id_matriculadetalleAparatos)
                    ->where('año_calificacion',$AñoPago)
                    ->pluck('monto_matricula')
                    ->first();
                    
                    log::info($año_calificacion);
                    log::info($id_estado_matricula);
                    log::info($monto_matricula);
                    log::info($fila);

                   if($id_estado_matricula=='2' and $año_calificacion<$añoActual)
                   {
                            $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                            $Cantidad_matriculas=$Cantidad_matriculas+1;
                            $multa= $multa+$monto_matricula;
                            Log::info($monto_pago_matricula);
                            Log::info($Cantidad_matriculas);
                            Log::info($multa);
                            Log::info('IF1- Con Multa');
                            log::info($fila2);
                   }else if($id_estado_matricula=='2' and $año_calificacion===$añoActual)
                   {
                       if($fechahoy>$fecha_limite)
                       {

                        $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                        $Cantidad_matriculas=$Cantidad_matriculas+1;
                        $multa= $multa+$monto_matricula;
                        Log::info($monto_pago_matricula);
                        Log::info($Cantidad_matriculas);
                        Log::info($multa);
                        Log::info('IF2- Con Multa');
                        log::info($fila2);

                       }else 
                            {
                                $monto_pago_matricula= $monto_pago_matricula+$monto_matricula;
                                $Cantidad_matriculas=$Cantidad_matriculas+1;
                                $multa=$multa;
                                Log::info($monto_pago_matricula);
                                Log::info($Cantidad_matriculas);
                                Log::info($multa);
                                Log::info('IF3 - Sin Multa');
                                log::info($fila2);
                            }

                   }                                     
            }//** Finaliza foreach -periodo- */
             //** Fin- Determinar si el permiso de una matricula ya fue pagada y Determinar multa  matricula */ 

                 


             Log::info($monto_pago_matricula);
             Log::info($Cantidad_matriculas);

            $fondoFPValor=round(($monto_pago_matricula*0.05),2);
            $totalPagoValor= round($fondoFPValor+$monto_pago_matricula+$multa,2);

            //Le agregamos su signo de dollar para la vista al usuario
            $fondoFP= "$". $fondoFPValor;     
            $totalPagoAparatosDollar="$".$totalPagoValor;
            $multaMatriculaDollar="$".$multa;
            $monto_pago_PmatriculaDollar="$".$monto_pago_matricula;

            //** Guardar cobro*/
            if ($request->cobrar=='1')
            {   
                if($monto_pago_matricula>0)
                {
                    foreach ($periodo as $dt) {
                        $AñoCancelar =$dt->format('Y');
                    CalificacionMatriculas::where('id_matriculas_detalle',$request->id_matriculadetalleAparatos)
                    ->where('id_estado_matricula','2')
                    ->where('año_calificacion',$AñoCancelar)
                    ->update([
                                'id_estado_matricula' =>"1",              
                            ]);
                        }
            }

            $cobro = new CobrosMatriculas();
            $cobro->id_matriculas_detalle = $request->id_matriculadetalleAparatos;
            $cobro->id_usuario = $idusuario;
            $cobro->cantidad_meses_cobro = '12';
            $cobro->monto_multa_matricula = $multa;
            $cobro->fondo_fiestasP = $fondoFPValor;
            $cobro->pago_total = $totalPagoValor;
            $cobro->fecha_cobro =  $fechahoy;
            $cobro->periodo_cobro_inicio = $InicioPeriodo;
            $cobro->periodo_cobro_fin =$fechaPagaraAparatos;
            $cobro->tipo_cobro ='matricula';
            $cobro->save();
        
            return ['success' => 2];
            

        }else{

            return [
                'success' => 1,
       
                'fondoFPAparatos'=>$fondoFP,
                'InicioPeriodoAparatos'=>$InicioPeriodo,
                'PagoUltimoDiaMesAparatos'=>$fechaPagaraAparatos,
                'monto_pago_PmatriculaDollarAparatos'=>$monto_pago_PmatriculaDollar,
                'multa_por_matricula'=>$multaMatriculaDollar,
                'totalPagoAparatos'=>$totalPagoAparatosDollar,
          
               ];
            }

        } //** Termina if validador de la fecha de ultimo pago no puede ser mayor que la de pagara */
        else
            {
                return ['success' => 0];
            }


}//** ------------------ Termina cálculo para cobrar la matrícula de APARATOS PARLANTES ---------------------------- */

}//* Cierre final
