<?php

namespace App\Http\Controllers\Backend\Empresas;

use App\Http\Controllers\Backend\MatriculasDetalle\MatriculasDetalleController;
use App\Http\Controllers\Controller;
use App\Models\Contribuyentes;
use App\Models\Usuario;
use App\Models\Empresas;
use App\Models\EstadoEmpresas;
use App\Models\GiroComercial;
use App\Models\ActividadEconomica;
use App\Models\ActividadEspecifica;
use App\Models\alertas_detalle;
use App\Models\BusesDetalle;
use App\Models\Cobros;
use App\Models\Calificacion;
use App\Models\CalificacionMatriculas;
use App\Models\CierresReaperturas;
use App\Models\CobrosLicenciaLicor;
use App\Models\CobrosMatriculas;
use App\Models\GiroEmpresarial;
use App\Models\Interes;
use App\Models\LicenciaMatricula;
use App\Models\MatriculasDetalle;
use App\Models\TarifaFija;
use App\Models\TarifaVariable;
use App\Models\MatriculasDetalleEspecifico;
use App\Models\NotificacionesHistorico;
use App\Models\Rotulos;
use App\Models\RotulosDetalle;
use App\Models\Traspasos;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\MessageBag;
use Spatie\Permission\Models\Role;

class EmpresaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){

        $idusuario = Auth::id();
        $infouser = Usuario::where('id', $idusuario)->first();
        $estadoempresas = EstadoEmpresas::All();
        $contribuyentes = Contribuyentes::All();
        $giroscomerciales = GiroComercial::All();
        $actividadeseconomicas = ActividadEconomica::All();
        $ConsultaEmpresa = Empresas::All();
        $actividadespecifica = ActividadEspecifica::ALL();

        return view('backend.admin.Empresas.Crear_Empresa', compact('contribuyentes','estadoempresas','giroscomerciales','actividadeseconomicas','ConsultaEmpresa','actividadespecifica'));
    }

    public function vista_cobro_general(){

        $idusuario = Auth::id();
        $infouser = Usuario::where('id', $idusuario)->first();
        $estadoempresas = EstadoEmpresas::All();
        $contribuyentes = Contribuyentes::All();
        $giroscomerciales = GiroComercial::All();
        $actividadeseconomicas = ActividadEconomica::All();
        $ConsultaEmpresa = Empresas::All();
        $actividadespecifica = ActividadEspecifica::ALL();
        $tasasDeInteres = Interes::select('monto_interes')
        ->orderby('id','desc')
        ->get();

        return view('backend.admin.Empresas.cobros_buscador',  compact('contribuyentes',
                                                                        'estadoempresas',
                                                                        'giroscomerciales',
                                                                        'actividadeseconomicas',
                                                                        'ConsultaEmpresa',
                                                                        'actividadespecifica',
                                                                        'tasasDeInteres'
                                                                    ));
    }
    public function buscar_ob_tributarias_calificadas(Request $request){

        //** Inicio - Detectando si una empre esta abierta o cerrada */
        $empresa=Empresas::where('id',$request->id)->first();

        //** Fin - Detectando si una empre esta abierta o cerrada */


        //**Inicio - Detectando si ya hay una calificación */
        $consulta_calificacion=calificacion::latest()
        ->where('id_empresa',$request->id)
        ->first();

        if($empresa->id_estado_empresa=='2')
        {
            if($consulta_calificacion==null)
                {
                    $id_detalle_matricula=MatriculasDetalle::where('id_empresa', $request->id)->pluck('id')->first();
                    $consulta_calificacion=CalificacionMatriculas::latest()->where('id_matriculas_detalle',$id_detalle_matricula)->first();

                    if($consulta_calificacion==null)
                    {
                        $consulta_calificacion=0;

                        return  [
                                    'success' => 2,
                                ];
                    }else{
                            return  [
                                        'success' => 1,
                                    ];
                        }

                }else{
                        return  [
                                    'success' => 1,
                                ];
                    }
            //**Fin - Detectando si ya hay una calificación */

        }else{
                return  [
                            'success' => 3,
                        ];
             }

    }

    public function vista_cobros(){

        $idusuario = Auth::id();
        $infouser = Usuario::where('id', $idusuario)->first();
        $estadoempresas = EstadoEmpresas::All();
        $contribuyentes = Contribuyentes::All();
        $giroscomerciales = GiroComercial::All();
        $actividadeseconomicas = ActividadEconomica::All();
        $ConsultaEmpresa = Empresas::All();
        $actividadespecifica = ActividadEspecifica::ALL();
        $tasasDeInteres = Interes::select('monto_interes')
        ->orderby('id','desc')
        ->get();

        return view('backend.admin.Empresas.Vista_cobros',  compact(    'contribuyentes',
                                                                        'estadoempresas',
                                                                        'giroscomerciales',
                                                                        'actividadeseconomicas',
                                                                        'ConsultaEmpresa',
                                                                        'actividadespecifica',
                                                                        'tasasDeInteres'
                                                                    ));
    }

    public function buscar_obligaciones_tributarias(Request $request){



    $Mes_anterior = Carbon::now()->subMonth()->day(1);


    log::info('Mes anterior al que solicita la Solvencia: '.$Mes_anterior);
    $empresas_registradas= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')

    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit',
    'empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones',
    'empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.id as id_contribuyente','contribuyente.nombre as contribuyente',
    'contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email',
    'contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax',
    'contribuyente.direccion as direccionCont',
    'estado_empresa.estado','estado_empresa.id as id_estado_empresa',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica',
     )
    ->where('id_contribuyente',$request->id_contribuyente)
    ->get();

    $buses_registrados=BusesDetalle
    ::join('contribuyente','buses_detalle.id_contribuyente','=','contribuyente.id')
    ->join('estado_buses','buses_detalle.id_estado_buses','=','estado_buses.id')

    ->select('buses_detalle.id','buses_detalle.nFicha','buses_detalle.cantidad','buses_detalle.nom_empresa',
    'contribuyente.id as id_contribuyente','contribuyente.nombre as contribuyente',
    'contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email',
    'contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax',
    'contribuyente.direccion as direccionCont',
    'estado_buses.estado as estado_bus','estado_buses.id as id_estado_bus',
    )
    ->where('id_contribuyente',$request->id_contribuyente)
    ->get();

    $rotulos_registrados=RotulosDetalle
    ::join('contribuyente','rotulos_detalle.id_contribuyente','=','contribuyente.id')
    ->join('estado_rotulo','rotulos_detalle.id_estado_rotulo','=','estado_rotulo.id')

    ->select('rotulos_detalle.id','rotulos_detalle.num_ficha as nFicha','rotulos_detalle.cantidad_rotulos','rotulos_detalle.nom_empresa',
    'rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa','rotulos_detalle.email_empresa',
    'contribuyente.id as id_contribuyente','contribuyente.nombre as contribuyente',
    'contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email',
    'contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax',
    'contribuyente.direccion as direccionCont',
    'estado_rotulo.estado as estado_rotulo','estado_rotulo.id as id_estado_rotulo',
    )
    ->where('id_contribuyente',$request->id_contribuyente)
    ->get();

    foreach($buses_registrados as $dato){
            if($dato->nom_empresa==null){
                $dato->nom_empresa='';
            }
    }

    //** Buscando Empresas resgistradas del contribuyente */
    if(sizeof($empresas_registradas) == 0)
    {
            $empresas_reg=0;
            $Solvencia=0;
    }else{
            $empresas_reg=1;
            $Solvencia=1;

            log::info(':::::Nombres de las empresas:____________');
            foreach($empresas_registradas as $dato)
            {
                    $ultima_fecha_pago=Cobros::latest()
                    ->where('id_empresa',$dato->id)
                    ->pluck('periodo_cobro_fin')
                    ->first();


                    if($ultima_fecha_pago==null)
                    {
                        $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$dato->id)
                        ->pluck('id')
                        ->first();

                                if($id_matriculadetalle==null){
                                        $ultima_fecha_pago=$dato->inicio_operaciones;

                                }else{

                                            $ultima_fecha_pago=CobrosMatriculas::latest()
                                                ->where('id_matriculas_detalle',$id_matriculadetalle)
                                                ->pluck('periodo_cobro_fin')
                                                ->first();

                                            //Nos aseguramos que si la última fecha de pago es nula se obtenga el inicio de operaciones
                                            if($ultima_fecha_pago==null)
                                            {
                                                $ultima_fecha_pago=$dato->inicio_operaciones;

                                            }

                                       }
                    }
                    $ultima_fecha_pago=Carbon::parse($ultima_fecha_pago);

                if($ultima_fecha_pago->gt($Mes_anterior))
                {
                        $estado_moratorio='Solvente';

                }else{
                        //** Nos aseguramos que la empresa que va estar en mora, no sea una que ya esta cerrada **/
                        //** ---- Si una empresa esta cerrada auntomaticamente esta solvente ---- */

                        if($dato->id_estado_empresa===1)
                        {
                                $estado_moratorio='Solvente';
                        }else{
                                $estado_moratorio='Mora';
                             }

                     }

                     log::info('ID: '.$dato->id.' '.'Nombre de la empresa: '.$dato->nombre.' Fecha de último pago: '.$ultima_fecha_pago.' Estado: '.$estado_moratorio);

                //** ---- Si se encuentra al menos un estado moratorio igual a Mora, no se emite solvencia ---- */
                if($estado_moratorio=='Mora')
                {
                    $Solvencia=0;

                }

               $dato->estado_moratorio_empresas=$estado_moratorio;

            }// Fin foreach
            log::info('Solvencia: '.$Solvencia);
         }//Fin IF

    //** Buscando Buses resgistrados del contribuyente */
    if(sizeof($buses_registrados) == 0)
    {
            $buses_reg=0;
    }else{
            $buses_reg=1;
         }

    //** Buscando Rótulos resgistrados del contribuyente */
    if(sizeof($rotulos_registrados) == 0)
    {
            $rotulos_reg=0;
    }else{
            $rotulos_reg=1;
         }





    //** Verificando si todas las busquedas realizadas son vacias */
    if(sizeof($empresas_registradas) == 0 and sizeof($buses_registrados) == 0 and sizeof($rotulos_registrados) == 0){
        return [
                    'success' => 2,
               ];
    }

    return [
                'success' => 1,
                'empresas_registradas'=>$empresas_registradas,
                'buses_registrados'=>$buses_registrados,
                'rotulos_registrados'=>$rotulos_registrados,

                'empresas_reg'=>$empresas_reg,
                'buses_reg'=>$buses_reg,
                'rotulos_reg'=>$rotulos_reg,

                'Solvencia'=>$Solvencia,

           ];

    }

    public function cierres_traspasos($id){

        $idusuario = Auth::id();
        $infouser = Usuario::where('id', $idusuario)->first();
        $estadoempresas = EstadoEmpresas::All();
        $contribuyentes = Contribuyentes::All();
        $giroscomerciales = GiroComercial::All();
        $actividadeseconomicas = ActividadEconomica::All();
        $ConsultaEmpresa = Empresas::All();
        $actividadespecifica = ActividadEspecifica::ALL();

        $empresa= Empresas
        ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
        ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
        ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
        ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')

        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',
        'actividad_economica.rubro','actividad_economica.id as id_act_economica',
        )
        ->where('empresa.id',$id)
        ->first();

        $Consul_traspasos=Traspasos::latest()
        ->where('id_empresa',$id)
        ->first();

        $Consul_cierres=CierresReaperturas::latest()
        ->where('id_empresa',$id)
        ->first();

        if($Consul_traspasos===null){
            $Consul_traspasos=0;
            }
        else
            {$Consul_traspasos=1;
            }

        if($Consul_cierres===null){
                $Consul_cierres=0;
                }
        else
                {
                    $Consul_cierres=1;
                }

        return view('backend.admin.Empresas.CierresTraspasos.Cierres_traspasos',
                compact(
                        'empresa',
                        'contribuyentes',
                        'estadoempresas',
                        'giroscomerciales',
                        'actividadeseconomicas',
                        'ConsultaEmpresa',
                        'actividadespecifica',
                        'Consul_traspasos',
                        'Consul_cierres',

                       ));
    }

    public function tablaCierres($id){

        $historico_cierres=CierresReaperturas::orderBy('id', 'desc')
        ->where('id_empresa',$id)
        ->get();


        return view('backend.admin.Empresas.CierresTraspasos.tablas.tabla_cierres', compact('historico_cierres'));
    }
    public function tablaTraspasos($id){

        $historico_traspasos=Traspasos::orderBy('id', 'desc')
        ->where('id_empresa',$id)
        ->get();

        return view('backend.admin.Empresas.CierresTraspasos.tablas.tabla_traspasos', compact('historico_traspasos'));
    }

    public function listarEmpresas()
    {

        $contribuyentes = Contribuyentes::All();
        $estadoempresas = EstadoEmpresas::All();
        $giroscomerciales = GiroComercial::All();
        $actividadeseconomicas = ActividadEconomica::All();
        $actividadespecifica = ActividadEspecifica::ALL();


        return view('backend.admin.Empresas.ListarEmpresas', compact('contribuyentes','estadoempresas','giroscomerciales','actividadeseconomicas','actividadespecifica'));
    }
    public function tablaEmpresas(Empresas $lista){


        $lista=Empresas::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
                        ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
                        ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')


        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',)
        ->get();

        return view('backend.admin.Empresas.tabla.tablalistaempresas', compact('lista'));
    }


    // informacion empresas
    public function informacionEmpresas(Request $request){
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($lista = Empresas::where('id', $request->id)->first()){
            $giro_comercial = GiroComercial::orderBy('nombre_giro')->get();
            $actividad_economica = ActividadEconomica::orderBy('rubro')->get();

            return ['success' => 1,
                'empresa' => $lista,
                'idgiro_co' => $lista->id_giro_comercial,
                'idact_eco' => $lista->id_actividad_economica,
                'giro_comercial' => $giro_comercial,
                'actividad_economica' => $actividad_economica,
                ];

        }else{
            return ['success' => 2];
        }
    }

    //Llenar detalle matriculas
    public function llenar_detalle_matriculas(Request $request){


        $id=$request->id_giro_comercial;

        $slug=GiroComercial::where('id',$id)
         ->pluck('slug')
         ->first();

        $matricula=licenciaMatricula::where('slug', $slug)
         ->first();


        return [
                    'success' => 1,
                    'matricula_Seleccionada' => $matricula,
               ];


    }
    //Termina llenar detalle matricula

// ---------Calificación de empresa ------------------------------------------>

public function calificacion($id)
{
    $contribuyentes = Contribuyentes::All();
    $licencia = LicenciaMatricula::All()->where('tipo_permiso', "=", "Licencia");
    $matricula = LicenciaMatricula::All()->where('tipo_permiso', "=", "Matrícula");
    $estadoempresas = EstadoEmpresas::All();
    $giroscomerciales = GiroComercial::All();
    $actividadeseconomicas = ActividadEconomica::All();
    $girosempresariales = GiroEmpresarial::All();
    $calificaciones = calificacion::All();


    $tarifa_fijas= TarifaFija::join('giro_empresarial','tarifa_fija.id_giro_empresarial','=','giro_empresarial.id')
    ->join('actividad_especifica','tarifa_fija.id_actividad_especifica','=','actividad_especifica.id')

    ->select('tarifa_fija.id','tarifa_fija.id_actividad_especifica','tarifa_fija.codigo','tarifa_fija.limite_inferior','tarifa_fija.limite_superior','tarifa_fija.impuesto_mensual',
    'tarifa_fija.codigo',
    'giro_empresarial.nombre_giro_empresarial',
    'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica',
     )
     ->get();

    foreach($tarifa_fijas as $ll)
    {
        $ll->limite_inferior = number_format($ll->limite_inferior, 2, '.', ',');
        $ll->limite_superior = number_format($ll->limite_superior, 2, '.', ',');
        $ll->impuesto_mensual = number_format($ll->impuesto_mensual, 2, '.', ',');

    }

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica',
    )
    ->where('empresa.id',$id)
    ->first();

    $matriculasRegistradas=MatriculasDetalle
    ::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
    ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')

    ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual',
            'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'matriculas.nombre as tipo_matricula','matriculas.codigo as codigo_matricula')
    ->where('id_empresa', "=", "$id")
    ->first($id);

    $matriculas=MatriculasDetalle
    ::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
    ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')
    ->join('estado_moratorio','matriculas_detalle.id_estado_moratorio','=','estado_moratorio.id')

    ->select('matriculas_detalle.id as id_matriculas_detalle', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual','matriculas_detalle.estado_especificacion','matriculas_detalle.id_estado_moratorio',
            'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'matriculas.id as id_matricula','matriculas.nombre as tipo_matricula',
            'estado_moratorio.id as id_estado_moratorio','estado_moratorio.estado as estado_moratorio')
    ->where('id_empresa', "=", "$id")
    ->get();

    if(sizeof($matriculas) == 0){
            $MatriculasReg=0;
            log::info('No Hay matriculas:'.$MatriculasReg);
    }else{
            $MatriculasReg=1;
            log::info('Si Hay matriculas:'.$MatriculasReg);
         }

         $monto = 0;

    if(sizeof($matriculas) == 0)
    {
            $monto = 0;

    }
    else
        {
            foreach($matriculas as $dato)
            {
               $monto = $monto + $dato->monto;
            }
        }

        $monto = number_format((float)$monto, 2, '.', ',');
        $montoMatriculaValor='$'. $monto;


    //** Detectando si ya hay una calificación */
    $consulta_calificacion=calificacion::latest()
    ->where('id_empresa',$id)
    ->first();

    if($consulta_calificacion==null){
            $id_detalle_matricula=MatriculasDetalle::where('id_empresa',$id)->pluck('id')->first();
            $consulta_calificacion=CalificacionMatriculas::latest()->where('id_matriculas_detalle',$id_detalle_matricula)->first();

            if($consulta_calificacion==null)
            {
                $consulta_calificacion=0;
            }
    }
    log::info('consulta_calificacion: '.$consulta_calificacion);

    //** Detectando que año se debe calificar y el orden */

    $primer_año_calificar=Carbon::parse($empresa->inicio_operaciones)->format('Y');
    log::info('primer_año_calificar: '.$primer_año_calificar);
    if($consulta_calificacion=='0'){
        $año_a_calificar=$primer_año_calificar;
        $ultimo_año_calificado=0;
    }else{
      $ultimo_año_calificado=$consulta_calificacion->año_calificacion;
      $año_a_calificar=$ultimo_año_calificado+1;
    }
    log::info('año_a_calificar: '.$año_a_calificar);



    return view('backend.admin.Empresas.Calificaciones.Calificacion',
    compact('id',
            'empresa',
            'giroscomerciales',
            'monto',
            'contribuyentes',
            'estadoempresas',
            'actividadeseconomicas',
            'calificaciones',
            'tarifa_fijas',
            'licencia',
            'matricula',
            'MatriculasReg',
            'matriculas',
            'montoMatriculaValor',
            'matriculasRegistradas',
            'girosempresariales',
            //** Variables para saber que año debe calificar el usuario */
            'primer_año_calificar',
            'ultimo_año_calificado',
            'consulta_calificacion',
            'año_a_calificar'
        ));

}

// ---------Termina Calificación de empresa ------------------------------------------>

// ---------Relificación de empresa ------------------------------------------>

public function Recalificacion($id)
{

    $contribuyentes = Contribuyentes::All();
    $licencia = LicenciaMatricula::All()->where('tipo_permiso', "=", "Licencia");
    $matricula = LicenciaMatricula::All()->where('tipo_permiso', "=", "Matrícula");
    $estadoempresas = EstadoEmpresas::All();
    $giroscomerciales = GiroComercial::All();
    $actividadeseconomicas = ActividadEconomica::All();
    $calificaciones = calificacion::All();
    $girosempresariales = GiroEmpresarial::All();


    $consulta_detalle_matricula=MatriculasDetalle::select('id')
    ->where('id_empresa', "=", $id)
    ->first();

    $calificacion_anterior=calificacion::latest()
    ->where('id_empresa', $id)
    ->first();

    $anio_actual=carbon::now()->format('Y');


    $tarifa_fijas= TarifaFija::join('giro_empresarial','tarifa_fija.id_giro_empresarial','=','giro_empresarial.id')
    ->join('actividad_especifica','tarifa_fija.id_actividad_especifica','=','actividad_especifica.id')

    ->select('tarifa_fija.id','tarifa_fija.id_actividad_especifica','tarifa_fija.codigo','tarifa_fija.limite_inferior','tarifa_fija.limite_superior','tarifa_fija.impuesto_mensual',
    'tarifa_fija.codigo',
    'giro_empresarial.nombre_giro_empresarial',
    'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica',
     )
    ->get();

    foreach($tarifa_fijas as $ll)
    {
        $ll->limite_inferior = number_format($ll->limite_inferior, 2, '.', ',');
        $ll->limite_superior = number_format($ll->limite_superior, 2, '.', ',');
        $ll->impuesto_mensual = number_format($ll->impuesto_mensual, 2, '.', ',');

    }

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica',
    )
    ->find($id);


    $matriculasRegistradas=MatriculasDetalle
    ::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
    ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')

    ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual',
            'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'matriculas.nombre as tipo_matricula','matriculas.codigo as codigo_matricula')
    ->where('id_empresa', "=", "$id")
    ->first($id);

    if ($matriculasRegistradas == null)
         {
             $detectorNull=1;
         }else
         {
            $detectorNull=0;
         }

         $matriculas=MatriculasDetalle
         ::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
         ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')
         ->join('estado_moratorio','matriculas_detalle.id_estado_moratorio','=','estado_moratorio.id')

         ->select('matriculas_detalle.id as id_matriculas_detalle', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual','matriculas_detalle.estado_especificacion','matriculas_detalle.id_estado_moratorio',
                 'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                 'matriculas.id as id_matricula','matriculas.nombre as tipo_matricula',
                 'estado_moratorio.id as id_estado_moratorio','estado_moratorio.estado as estado_moratorio')
         ->where('id_empresa', "=", "$id")
         ->get();

         if(sizeof($matriculas) == 0){
                 $MatriculasReg=0;
                 log::info('No Hay matriculas:'.$MatriculasReg);
         }else{
                 $MatriculasReg=1;
                 log::info('Si Hay matriculas:'.$MatriculasReg);
              }

              $monto = 0;

         if(sizeof($matriculas) == 0)
         {
                 $monto = 0;

         }
         else
             {
                 foreach($matriculas as $dato)
                 {
                    $monto = $monto + $dato->monto;
                 }
             }

        $monto = number_format((float)$monto, 2, '.', ',');
        $montoMatriculaValor='$'. $monto;

        $cali_lista=calificacion::latest()
        ->where('id_empresa',$id)
        ->first();

        if($cali_lista==null){
            $cali_lista=CalificacionMatriculas::latest()
            ->where('id_matriculas_detalle',$consulta_detalle_matricula->id)
            ->first();
        }

          //** Detectando si ya hay una calificación */
          $consulta_calificacion=calificacion::latest()
          ->where('id_empresa',$id)
          ->first();

          if($consulta_calificacion==null){


                  $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$id)->pluck('id')->first();
                  $consulta_calificacion=CalificacionMatriculas::latest()->where('id_matriculas_detalle',$id_matriculadetalle)->first();
                  if($consulta_calificacion==null)
                  {
                      $consulta_calificacion=0;
                  }
          }
          log::info('consulta_calificacion: '.$consulta_calificacion);

          //** Detectando que año se debe calificar y el orden */
          $primer_año_calificar=Carbon::parse($empresa->inicio_operaciones)->format('Y');
          log::info('primer_año_calificar: '.$primer_año_calificar);
          if($consulta_calificacion=='0'){
                $año_a_calificar=$primer_año_calificar;
                $ultimo_año_calificado=0;
          }else{
            $ultimo_año_calificado=$consulta_calificacion->año_calificacion;
            $año_a_calificar=$ultimo_año_calificado+1;
          }
          log::info('ultimo_año_calificado: '.$ultimo_año_calificado);
          log::info('año_a_calificar: '.$año_a_calificar);

    return view('backend.admin.Empresas.Calificaciones.Recalificacion',
    compact(
                'monto',
                'montoMatriculaValor',
                'detectorNull',
                'empresa',
                'giroscomerciales',
                'contribuyentes',
                'estadoempresas',
                'actividadeseconomicas',
                'calificaciones',
                'tarifa_fijas',
                'licencia',
                'matricula',
                'matriculas',
                'cali_lista',
                'anio_actual',
                'MatriculasReg',
                'matriculasRegistradas',
                'girosempresariales',
                //** Variables para saber que año debe calificar el usuario */
                'primer_año_calificar',
                'ultimo_año_calificado',
                'consulta_calificacion',
                'año_a_calificar'

            ));

}

// ---------Termina Recalificación de empresa ------------------------------------------>

//Vista detallada
public function show($id)
{
    $fechahoy=carbon::now()->format('Y-m-d');
    //$fechahoy='2022-02-17';
    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit',
    'empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones',
    'empresa.direccion','empresa.num_tarjeta','empresa.telefono','empresa.excepciones_especificas',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel',
    'contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont',
    'contribuyente.registro_comerciante',
    'contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado','estado_empresa.id as id_estado_empresa',
    'giro_comercial.nombre_giro','giro_comercial.id as id_giro',
    'actividad_economica.rubro',
    )
    ->find($id);

    $id_giro_comercial=$empresa->id_giro;
    log::info('ID del giro comercial de la emppresa '.$empresa->nombre.' ID: '.$id_giro_comercial);
    $contribuyentes = Contribuyentes::All();
    $estadoempresas = EstadoEmpresas::All();
    $giroscomerciales = GiroComercial::All();
    $actividadeseconomicas = ActividadEconomica::All();
    $consulta_detalle_matricula=MatriculasDetalle::select('id')
    ->where('id_empresa', "=", $id)
    ->first();

    if($consulta_detalle_matricula==null){
            $pase_cobro_mat=0;
            $pase_recalificacion_mat=0;
    }else{
            $cali_matricula=CalificacionMatriculas::where('id_matriculas_detalle',$consulta_detalle_matricula->id)
            ->first();

            if($cali_matricula==null)
            {
                    $pase_cobro_mat=0;
                    $pase_recalificacion_mat=0;
            }else{
                    $pase_cobro_mat=1;
                    $pase_recalificacion_mat=1;//**Si ya hay una matricula, permite el pase a RECALIFICAR */
                }

        }
    log::info('pase recalificacion :'.$pase_cobro_mat);
    log::info($pase_cobro_mat);
    //** Inicia - Para obtener la tasa de interes más reciente */
    $Tasainteres=Interes::latest()
    ->pluck('monto_interes')
        ->first();
    //** Finaliza - Para obtener la tasa de interes más reciente */


                //** Para determinar la cantidad de multas por balance por empresa */
                $multasBalance=calificacion::select('multa_balance')
                ->where('id_empresa',$id)
                ->where('id_estado_multa','2')
                    ->get();

                    $Cantidad_multas=0;
                    $monto_pago_multaBalance=0;

                    foreach($multasBalance as $dato){
                        $multaBalance=$dato->multa_balance;

                            if ($multaBalance>0)
                            {
                                $monto_pago_multaBalance= $monto_pago_multaBalance+$multaBalance;
                                $Cantidad_multas=$Cantidad_multas+1;
                            }

                }
                //** Fin- Determinar la cantidad de multas por empresa */



    $calificaciones = calificacion
    ::join('empresa','calificacion.id_empresa','=','empresa.id')

    ->select('calificacion.id','calificacion.fecha_calificacion','calificacion.tarifa','calificacion.tipo_tarifa','calificacion.estado_calificacion','calificacion.id_estado_licencia_licor',
    'empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono')
    ->where('id_empresa', "=", "$id")
    ->first();

    //** Comprobación de pago al dia se hace para reinciar las alertas avisos y notificaciones */
    $ComprobandoPagoAlDia=cobros::latest()
        ->where('id_empresa',$id)
        ->pluck('periodo_cobro_fin')
            ->first();
    if($ComprobandoPagoAlDia==null){

        if($consulta_detalle_matricula!=null){
                $ComprobandoPagoAlDia=CobrosMatriculas::latest()
                ->where('id_matriculas_detalle',$consulta_detalle_matricula->id)
                ->pluck('periodo_cobro_fin')
                    ->first();
                    if($ComprobandoPagoAlDia==null){
                        $ComprobandoPagoAlDia=$empresa->inicio_operaciones;
                    }

        }else{
                    $ComprobandoPagoAlDia=$empresa->inicio_operaciones;

             }

    }//** Comprobación de pago al dia se hace para reinciar las alertas avisos y notificaciones */

    log::info('comprobacion de pago:' .$ComprobandoPagoAlDia);

    $alerta_notificacion=alertas_detalle::where('id_empresa',$id)
        ->where('id_alerta','2')
        ->pluck('cantidad')
        ->first();

    $alerta_aviso=alertas_detalle::where('id_empresa',$id)
    ->where('id_alerta','1')
    ->pluck('cantidad')
    ->first();

    if($alerta_aviso==null)
        {
            $alerta_aviso=0;
        }else{

                if($ComprobandoPagoAlDia>=$fechahoy)
                {

                    $alerta_aviso=0;
                    alertas_detalle::where('id_empresa',$id)
                    ->where('id_alerta','1')
                    ->update([
                                'cantidad' =>$alerta_aviso,
                            ]);

                }else{
                        $alerta_aviso=$alerta_aviso;
                    }
            }

    if($alerta_notificacion==null){
        $alerta_notificacion=0;
    }else{
            if($ComprobandoPagoAlDia>=$fechahoy)
            {
                $alerta_notificacion=0;
                alertas_detalle::where('id_empresa',$id)
                ->where('id_alerta','2')
                ->update([
                            'cantidad' =>$alerta_notificacion,
                        ]);

            }else{
                     $alerta_notificacion=$alerta_notificacion;
                 }
         }




    $Consul_traspasos=Traspasos::latest()
    ->where('id_empresa',$id)
    ->first();

    if($Consul_traspasos===null){
        $Consul_traspasos=0;
        }
    else
        {$Consul_traspasos=1;
        }



    $giro=$empresa->id_giro;
    log::info('id del giro: '.$giro);
    if($giro===1)//**Si es 1=empresas */
    {
        $pase_matriculas=0;//**No hay pase a matriculas */
    }else{
             $pase_matriculas=1;
         }



    if($empresa->id_giro===1){
        //Es empresa

        $ultimo_cobro = Cobros::latest()
        ->where('id_empresa',$id)
        ->first();

            if( $ultimo_cobro==null)
            {
                $ultimoCobroEmpresa=$empresa->inicio_operaciones;
            }else{
                $ultimoCobroEmpresa=$ultimo_cobro->periodo_cobro_fin;
            }

    }else{
        if($consulta_detalle_matricula!=null){
                    $ultimo_cobro = CobrosMatriculas::latest()
                    ->where('id_matriculas_detalle',$consulta_detalle_matricula->id)
                    ->first();

                    if( $ultimo_cobro==null)
                    {
                        $ultimoCobroEmpresa=$empresa->inicio_operaciones;
                    }else{
                        $ultimoCobroEmpresa=$ultimo_cobro->periodo_cobro_fin;
                    }
            }else{
                $ultimoCobroEmpresa=$empresa->inicio_operaciones;
                $ultimo_cobro='';
            }
         }


    //**¨Para detectar los cobros especiales */
    if($empresa->excepciones_especificas==='SI')
    {
      $CE=1;

    }else{
          $CE=0;
         }

    //**¨Fin detectar los cobros especiales */




    //**************************** SOLO PARA MATRÍCULAS ****************************/

    $AnioActual=carbon::now()->format('Y');
    log::info('año actual: '.$AnioActual);
    $AnioAnterior=carbon::now()->subYear()->format('Y-12-31');
    log::info('año anterior: '.$AnioAnterior);
    log::info('Fecha hoy: '.$fechahoy);
    $month=03;
    $day=31;
    $fechaLimite=Carbon::createFromDate($AnioActual, $month, $day);


    $matriculas=MatriculasDetalle
    ::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
    ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')
    ->join('estado_moratorio','matriculas_detalle.id_estado_moratorio','=','estado_moratorio.id')

    ->select('matriculas_detalle.id as id_matriculas_detalle', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual','matriculas_detalle.estado_especificacion','matriculas_detalle.id_estado_moratorio',
            'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'matriculas.id as id_matricula','matriculas.nombre as tipo_matricula',
            'estado_moratorio.id as id_estado_moratorio','estado_moratorio.estado as estado_moratorio')
    ->where('id_empresa', "=", "$id")
    ->get();

    if(sizeof($matriculas) == 0){
            $MatriculasReg=0;
            log::info('No Hay matriculas:'.$MatriculasReg);
    }else{
            $MatriculasReg=1;
            log::info('Si Hay matriculas:'.$MatriculasReg);
         }

    log::info('-------------------|');


    foreach($matriculas as $dato)
    {

        $id_detalle=$dato->id_matriculas_detalle;

        $ComprobandoPagoAlDiaMatriculas=CobrosMatriculas::latest()
        ->where('id_matriculas_detalle',$id_detalle)
        ->pluck('periodo_cobro_fin')
        ->first();
        log::info($ComprobandoPagoAlDiaMatriculas);

        if($ComprobandoPagoAlDiaMatriculas===null){
            $ComprobandoPagoAlDiaMatriculas=Empresas::where('id',$id)
            ->pluck('inicio_operaciones')
            ->first();
        }

        $estado_moratorioM=MatriculasDetalle::where('id',$id_detalle)
        ->pluck('id_estado_moratorio')
        ->first();

        $CantidadDias=ceil(carbon::parse($ComprobandoPagoAlDiaMatriculas)->diffInDays(carbon::parse($fechahoy)));
        log::info('Cantidad de dias transcurridos desde el último pago:'.$CantidadDias);

        //*Si es Aparatos Parlantes
        //* Estado matricula: 1= solvente.
        if($dato->id_matricula==2){
            if($ComprobandoPagoAlDiaMatriculas<$AnioAnterior)
                //*Si es true esta en Mora
                {
                    if($estado_moratorioM!=2){
                        MatriculasDetalle::where('id',$id_detalle)
                        ->update([
                                    'id_estado_moratorio' =>'2',
                                ]);
                                log::info('else if 1: estado: en mora');
                        }else{log::info('else if 1: estado: Ya estaba en mora');}

                }else if($ComprobandoPagoAlDiaMatriculas>$fechaLimite){
                    //*Si es true esta esta solvente
                    if($estado_moratorioM!=1){
                        MatriculasDetalle::where('id',$id_detalle)
                        ->update([
                                    'id_estado_moratorio' =>'1',
                                ]);
                                log::info('else if 2: estado: solvente');
                            }else{log::info('else if 2: estado: Ya estaba en Solvente');}

                }else if($ComprobandoPagoAlDiaMatriculas==$AnioAnterior and $fechahoy>$fechaLimite){
                    //*Si es true esta en Mora
                    if($estado_moratorioM!=2){
                        MatriculasDetalle::where('id',$id_detalle)
                        ->update([
                                    'id_estado_moratorio' =>'2',
                                ]);
                                log::info('else if 3: estado: en mora');
                        }else{log::info('else if 3: estado: Ya estaba en mora');}
                }else if($ComprobandoPagoAlDiaMatriculas==$AnioAnterior and $fechahoy<$fechaLimite){
                    //*Si es true esta esta solvente
                    if($estado_moratorioM!=1){
                        MatriculasDetalle::where('id',$id_detalle)
                        ->update([
                                    'id_estado_moratorio' =>'1',
                                ]);
                                log::info('else if 4: estado: solvente');
                            }else{log::info('else if 4: estado: Ya estaba en Solvente');}
                }




            //*Si es Mesas de billar o  Sinfonolas
            }else if($dato->id_matricula==1 or $dato->id_matricula==4)
            {

                    if( $CantidadDias<90)
                        {
                            if($estado_moratorioM!=1){
                            MatriculasDetalle::where('id',$id_detalle)
                            ->update([
                                        'id_estado_moratorio' =>'1',
                                    ]);
                                    log::info('estado: solvente');
                                }else{log::info('estado: Ya estaba en Solvente');}
                        }else{
                                if($estado_moratorioM!=2){
                                        MatriculasDetalle::where('id',$id_detalle)
                                        ->update([
                                                    'id_estado_moratorio' =>'2',
                                                ]);
                                                log::info('estado: en mora');
                                        }else{log::info('estado: Ya estaba en mora');}
                            }

            //*Si es Maquinas eletrónicas
            }else if($dato->id_matricula==3)
            {
                    if( $CantidadDias<60)
                    {
                        if($estado_moratorioM!=1){
                        MatriculasDetalle::where('id',$id_detalle)
                        ->update([
                                    'id_estado_moratorio' =>'1',
                                ]);
                                log::info('estado: solvente');
                            }else{log::info('estado: Ya estaba en Solvente');}
                    }else{
                            if($estado_moratorioM!=2){
                                    MatriculasDetalle::where('id',$id_detalle)
                                    ->update([
                                                'id_estado_moratorio' =>'2',
                                            ]);
                                            log::info('estado: en mora');
                                    }else{log::info('estado: Ya estaba en mora');}
                        }

            }
        log::info($dato->id_matriculas_detalle);
        //*Fin si es Aparatos Parlantes
        //*Si es Mesas de billar

        //*Fin si es Mesas de billar
        log::info('-------------------|');
    }


    //**************************** FIN-SOLO PARA MATRÍCULAS ****************************/



//******************* Determinando si una empresa esta en mora  *******************/

        log::info('|°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°|');
        $f3=carbon::now()->format('Y-m-d');
        $f1=Carbon::parse($ComprobandoPagoAlDia);
        Log::info('f1 es el ultimo pago: '.$f1);
        Log::info('f3 fecha actual: '.$f3);

        //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
        $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
        Log::info('UltimoDiaMes: '.$UltimoDiaMes);
        $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(60)->format('Y-m-d');


        $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
        //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
        Log::info('inicio Moratorio aqui: '.$FechaDeInicioMoratorio);

        if($FechaDeInicioMoratorio->lt($f3)){
                $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
                Log::info('Cantidad de dias de insteres moratorio: '.$DiasinteresMoratorio);
                Log::info('No entro al else');
        }else{
                $DiasinteresMoratorio=0;
                Log::info('Cantidad de dias de interes moratorio: '.$DiasinteresMoratorio);
                Log::info('Entro al else');
            }

        //** Comprobando si la empresa SE LE DEBE NOTIFICAR(AVISOS Y NOTIFICACIONES) O NO */
        if($FechaDeInicioMoratorio>=$fechahoy)
        {
            //** Si NoNotificar vale 1 entonces NO SE DEBE imprimir una notificación ni avisos*/Esta al dia
            $NoNotificar=1;
            log::info('NoNotificar:' .$NoNotificar);
        }else
                {
                    //** Si NoNotificar vale 0 entonces es permitido imprimir una notificación o avisos*/
                    $NoNotificar=0;
                    log::info('NoNotificar:' .$NoNotificar);
                }
        //* fin de comprobar */

         if($MatriculasReg===1){
            Log::info('id empresa: '.$id);
            $estado_matricula=MatriculasDetalle::where('id_empresa',$id)
            ->pluck('id_estado_moratorio')
            ->first();

                if($estado_matricula===2){

                        $estado_de_solvencia=1;//Si es 1 esta en Mora

                }else{

                        $estado_de_solvencia=0;//Si es 0 esta Solvente
                    }

                    Log::info('Entro a matriculas y el estado de solvencia es: '.$estado_de_solvencia);
         }else{
                    if($DiasinteresMoratorio>0)
                    {
                        $estado_de_solvencia=1;//Si es 1 esta en Mora

                    }else{
                        $estado_de_solvencia=0;//Si es 0 esta Solvente
                    }
                    Log::info('Era empresa y el estado de solvencia es: '.$estado_de_solvencia);
              }

//******************* FIN - Determinando si una empresa o matricula esta en mora  *******************/

   if ($calificaciones == null)
    {
        $detectorNull=0;

    }else{
            $detectorNull=1;
         }

    if ($ultimo_cobro == null)
        {
            $detectorCobro=0;

        }else
            {
                $detectorCobro=1;
            }

                log::info('detectorNull :'.$detectorNull);
     return view('backend.admin.Empresas.show', compact(
                                                        'empresa',
                                                        'giroscomerciales',
                                                        'contribuyentes',
                                                        'estadoempresas',
                                                        'actividadeseconomicas',
                                                        'ultimo_cobro',
                                                        'calificaciones',
                                                        'detectorNull',
                                                        'detectorCobro',
                                                        'id',
                                                        'Cantidad_multas',
                                                        'CE',
                                                        'Tasainteres',
                                                        'ultimoCobroEmpresa',
                                                        'fechahoy',
                                                        'alerta_aviso',
                                                        'alerta_notificacion',
                                                        'Consul_traspasos',
                                                        'NoNotificar',
                                                        'MatriculasReg',
                                                        'pase_cobro_mat',
                                                        'pase_matriculas',
                                                        'pase_recalificacion_mat',
                                                        'estado_de_solvencia',
                                                        'id_giro_comercial',
                                                        'ultimoCobroEmpresa'


                                                ));

}// ---------Fin vista Show ------------------------------------------>



// ---------COBROS ------------------------------------------>
public function calculo_cobros_empresa(Request $request)
{   log::info($request->all());

    $idusuario = Auth::id();
    $MesNumero=Carbon::createFromDate($request->ultimo_cobro)->format('d');
    //log::info($MesNumero);

    if($MesNumero<='15')
    {
        $f1=Carbon::parse($request->ultimo_cobro)->format('Y-m-01');
        $f1=Carbon::parse($f1);
        $InicioPeriodo=Carbon::createFromDate($f1);
        $InicioPeriodo= $InicioPeriodo->format('Y-m-d');
        //log::info('inicio de mes');
    }
    else
        {
         $f1=Carbon::parse($request->ultimo_cobro)->addMonthsNoOverflow(1)->day(1);
         $InicioPeriodo=Carbon::parse($request->ultimo_cobro)->addMonthsNoOverflow(1)->day(1)->format('Y-m-d');
        // log::info('fin de mes ');
         }

    //log::info('Ultimo pago: '.$f1);

    $id=$request->id;
    $Message=0;


    $f2=Carbon::parse($request->fechaPagara);
    $f3=Carbon::parse($request->fecha_interesMoratorio);
    $añoActual=Carbon::now()->format('Y');

    //** Inicia - Para determinar el intervalo de años a pagar */
    $monthInicio='01';
    $dayInicio='01';
    $monthFinal='12';
    $dayFinal='31';
    $AñoInicio=Carbon::parse($f1)->format('Y');

    $AñoFinal=$f2->format('Y');
    $FechaInicio=Carbon::createFromDate($AñoInicio, $monthInicio, $dayInicio);
    $FechaFinal=Carbon::createFromDate($AñoFinal, $monthFinal, $dayFinal);
    //** Finaliza - Para determinar el intervalo de años a pagar */


    //** INICIO - Para obtener SIEMPRE el último día del mes que selecciono el usuario */
    $PagoUltimoDiaMes=Carbon::parse($request->fechaPagara)->endOfMonth()->format('Y-m-d');
    //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */

     //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
     $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
     $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(60)->format('Y-m-d');

     $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
     $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
     //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
     Log::info('inicion Moratorio aqui');
     Log::info($FechaDeInicioMoratorio);

    //** Inicia - Para obtener la tasa de interes más reciente */
        $Tasainteres=$request->tasa_interes;
    //** Finaliza - Para obtener la tasa de interes más reciente */

        $calificaciones = calificacion::latest()

        ->join('empresa','calificacion.id_empresa','=','empresa.id')

        ->select('calificacion.id','calificacion.multa_balance','calificacion.fecha_calificacion','calificacion.tipo_tarifa','calificacion.tarifa','calificacion.estado_calificacion','calificacion.tipo_tarifa','calificacion.estado_calificacion','calificacion.id_estado_licencia_licor',
        'empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono')

        ->where('id_empresa', "=", "$id")
        ->first();


        $empresa= Empresas
        ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
        ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
        ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
        ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral',
        'empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'empresa.excepciones_especificas',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui',
        'contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax',
        'contribuyente.direccion as direccionCont',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',
        'actividad_economica.rubro','actividad_economica.codigo_atc_economica'
        )
        ->find($id);
        $nombre_empresa= $empresa->nombre;
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

            //** Inicia Foreach para cálculo de impuesto por años */
            foreach ($periodo as $dt) {

                $AñoPago =$dt->format('Y');

                $AñoSumado=Carbon::createFromDate($AñoPago, 12, 31);

                /**¨Para detectar los cobros especiales y darle su tarifa */
                if($empresa->excepciones_especificas==='SI')
                {
                    $tarifa=$request->tarifaMes;

                }else{

                        $tarifa=calificacion::where('año_calificacion','=',$AñoPago)
                            ->where('id_empresa','=',$id)
                            ->pluck('pago_mensual')
                            ->first();

                     }

                //**¨Fin detectar los cobros especiales */


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
               $TasaInteresDiaria=($Tasainteres/365);
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
                        $CantidaDiasMesMulta=ceil($Fecha60Sumada->diffInDays($f3));//**le tenia floatdiffInDays y funcinona bien  */
                        }else
                        {
                            $CantidaDiasMesMulta=ceil($Fecha60Sumada->diffInDays($f3));
                            $CantidaDiasMesMulta=-$CantidaDiasMesMulta;

                        }
                        Log::info($CantidaDiasMesMulta);


             /**¨Para detectar los cobros especiales y darle su tarifa */
                        if($empresa->excepciones_especificas==='SI')
                        {
                            $tarifaMulta=$request->tarifaMes;

                        }else{
                                $tarifaMulta=calificacion::where('año_calificacion','=',$TarifaAñoMulta)
                                ->where('id_empresa','=',$id)
                                    ->pluck('pago_mensual')
                                        ->first();
                                }

                //**¨Fin detectar los cobros especiales */

                    $MesDeMulta->addMonthsNoOverflow(1)->format('Y-M');


                   //** INICIO- Determinar multa por pago extemporaneo. */
                   if($CantidaDiasMesMulta>0){
                        if($CantidaDiasMesMulta<=90)
                        {
                                    $multaPagoExtemporaneo=round(($tarifaMulta*0.05),2);
                                    $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo+$multaPagoExtemporaneo;
                                    $stop="Avanza:Multa";

                        }elseif($CantidaDiasMesMulta>=90)
                                {
                                    $multaPagoExtemporaneo=round(($tarifaMulta*0.10),2);
                                    $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo+$multaPagoExtemporaneo;
                                    $stop="Avanza:Multa";
                                }

                        //** INICIO-  Cálculando el interes. */
                        $Interes=round((($TasaInteresDiaria*$CantidaDiasMesMulta)/100*$tarifaMulta),2);
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
                 //ancla1

                //** Para determinar la cantidad de multas por balance por empresa */
                $multasBalance=calificacion::select('multa_balance')
                ->where('id_empresa',$id)
                ->where('id_estado_multa','2')
                    ->get();

                    $Cantidad_multas=0;
                    $monto_pago_multaBalance=0;

                    foreach($multasBalance as $dato){
                        $multaBalance=$dato->multa_balance;

                            if ($multaBalance>0)
                            {
                                $monto_pago_multaBalance= $monto_pago_multaBalance+$multaBalance;
                                $Cantidad_multas=$Cantidad_multas+1;
                            }

                }
                //** Fin- Determinar la cantidad de multas por empresa */


            $fondoFPValor=round($impuestoTotal*0.05,2);
            $totalPagoValor= round($fondoFPValor+$monto_pago_multaBalance+$impuestoTotal+$totalMultaPagoExtemporaneo+$InteresTotal,2);

            //Le agregamos su signo de dollar para la vista al usuario
            $fondoFP="$".number_format($fondoFPValor, 2, '.', ',');
            $totalPago="$".number_format($totalPagoValor, 2, '.', ',');
            $impuestos_mora_Dollar="$".number_format($impuestos_mora, 2, '.', ',');
            $impuesto_año_actual_Dollar="$".number_format($impuesto_año_actual, 2, '.', ',');
            $monto_pago_multaDollar="$".number_format($monto_pago_multaBalance, 2, '.', ',');
            $multaPagoExtemporaneoDollar="$".number_format($totalMultaPagoExtemporaneo, 2, '.', ',');
            $InteresTotalDollar="$".number_format($InteresTotal, 2, '.', ',');

            //** Guardar cobro*/
            if ($request->cobrar=='1')
            {

                $cobro = new cobros();
                $cobro->id_empresa = $request->id;
                $cobro->id_usuario =$idusuario;
                $cobro->cantidad_meses_cobro = $Cantidad_MesesTotal;
                $cobro->impuesto_mora_32201 = $impuestos_mora;
                $cobro->impuestos = $impuesto_año_actual;
                $cobro->codigo = $empresa->codigo_atc_economica;
                $cobro->intereses_moratorios_15302 = $InteresTotal;
                $cobro->monto_multa_balance_15313 = $monto_pago_multaBalance;
                $cobro->monto_multaPE_15313 = $totalMultaPagoExtemporaneo;
                $cobro->fondo_fiestasP_12114 = $fondoFPValor;
                $cobro->pago_total = $totalPagoValor;
                $cobro->fecha_cobro = $request->fecha_interesMoratorio;
                $cobro->periodo_cobro_inicio = $InicioPeriodo;
                $cobro->periodo_cobro_fin =$PagoUltimoDiaMes;
                $cobro->tipo_cobro = 'impuesto';
                $cobro->save();

                        if($monto_pago_multaBalance>0)
                        {
                            foreach($multasBalance as $dato){
                                calificacion::where('id_empresa',$id)
                                ->where('id_estado_multa','2')
                                ->update([
                                            'id_estado_multa' =>"1",
                                        ]);

                            }

                        }

                return ['success' => 2];


                }else{
                        return ['success' => 1,
                            'InteresTotalDollar'=>$InteresTotalDollar,
                            'impuestoTotal'=>$impuestoTotal,
                            'impuestos_mora_Dollar'=>$impuestos_mora_Dollar,
                            'impuesto_año_actual_Dollar'=>$impuesto_año_actual_Dollar,
                            'Cantidad_MesesTotal'=>$Cantidad_MesesTotal,
                            'nombre_empresa'=>$nombre_empresa,
                            'tarifa'=>$tarifa,
                            'fondoFP'=>$fondoFP,
                            'totalPago'=>$totalPago,
                            'DiasinteresMoratorio'=>$DiasinteresMoratorio,
                            'multas_balance'=>$monto_pago_multaDollar,
                            'interes'=>$Tasainteres,
                            'InicioPeriodo'=>$InicioPeriodo,
                            'PagoUltimoDiaMes'=>$PagoUltimoDiaMes,
                            'FechaDeInicioMoratorio'=> $FechaDeInicioMoratorio,
                            'multaPagoExtemporaneoDollar'=> $multaPagoExtemporaneoDollar,
                            'totalMultaPagoExtemporaneo'=>$totalMultaPagoExtemporaneo,
                            ];
                    }
        } //if principal
          else
                {
                    return ['success' => 0];
                }

}


public function calculo_cobroLicor(Request $request)
{
    log::info($request->all());
    $idusuario = Auth::id();
    $MesNumero=Carbon::createFromDate($request->ultimo_cobro)->format('d');
    //log::info($MesNumero);

    if($MesNumero<='15')
    {
        $f1=Carbon::parse($request->ultimo_cobro)->format('Y-m-01');
        $f1=Carbon::parse($f1);
        $InicioPeriodo=Carbon::createFromDate($f1);
        $InicioPeriodo= $InicioPeriodo->format('Y-01-01');
        //log::info('inicio de mes');
    }
    else
        {
         $f1=Carbon::parse($request->ultimo_cobro)->addMonthsNoOverflow(1)->day(1);
         $InicioPeriodo=Carbon::parse($f1)->format('Y-01-01');
        // log::info('fin de mes ');
         }

    $id=$request->id;

    $f2=Carbon::parse($request->fechaPagara);
    $FechaPagara=Carbon::parse($request->fechaPagara)->format('Y-12-31');
    $añoActual=Carbon::now()->format('Y');
    $fechahoy=carbon::now()->format('Y-m-d');

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
    $PagoUltimoDiaMes=Carbon::parse($request->fechaPagara)->endOfMonth()->format('Y-m-d');
    //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */
    Log::info('Pago ultimo dia del mes---->' .$PagoUltimoDiaMes);


    $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
    Log::info('ultimo dia del mes---->' .$UltimoDiaMes);


        $empresa= Empresas
        ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
        ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
        ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
        ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',
        'actividad_economica.rubro', 'actividad_economica.codigo_atc_economica',
         )
        ->find($id);
        $nombre_empresa= $empresa->nombre;

        if($f1->lt($PagoUltimoDiaMes))
        {

            $intervalo = DateInterval::createFromDateString('1 Year');
            $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);
            $multa=0;
            $multaTotalLicor=0;

                 /** Calculando las licencias*/
                 $Cantidad_licencias=0;
                 $monto_pago_licencia=0;
                 $fila='------------------';
                 $fila2='_______________________';
                 foreach ($periodo as $dt) {


                    $AñoCancelar =$dt->format('Y');
                    $AñoSumadoLicencia=Carbon::createFromDate($AñoCancelar, 12, 31);
                    $monto_licencia=calificacion::where('año_calificacion','=', $AñoCancelar)
                    ->where('id_empresa','=',$id)
                       ->pluck('licencia')
                           ->first();

                   $año_calificacion=calificacion::where('año_calificacion','=', $AñoCancelar)
                   ->where('id_empresa','=',$id)
                      ->pluck('año_calificacion')
                          ->first();

                   $id_estado_licencia=calificacion::where('año_calificacion','=', $AñoCancelar)
                   ->where('id_empresa','=',$id)
                       ->pluck('id_estado_licencia_licor')
                          ->first();

                    log::info($año_calificacion);
                    log::info($id_estado_licencia);
                    log::info($monto_licencia);
                    log::info($fila);
                    $FechaLimiteVariable=Carbon::parse($dt)->month(1)->day(15);

                    if($id_estado_licencia=='2' and $año_calificacion<$añoActual)
                    {
                             $monto_pago_licencia= $monto_pago_licencia+$monto_licencia;
                             $Cantidad_licencias=$Cantidad_licencias+1;

                             Log::info($monto_pago_licencia);
                             Log::info($Cantidad_licencias);

                             $Cantidadsemanas=ceil(($FechaLimiteVariable->floatDiffInRealWeeks( $AñoSumadoLicencia)));
                             $multa=$Cantidadsemanas*$monto_licencia;
                             $multaTotalLicor= $multaTotalLicor+$multa;
                             log::info($FechaLimiteVariable);
                             log::info($AñoSumadoLicencia);
                             Log::info('IF1- Con Multa');
                             log::info($multa);
                             log::info($Cantidadsemanas);
                             log::info($fila2);
                    }else if($id_estado_licencia=='2' and $año_calificacion==$añoActual)
                    {
                        if($fechahoy>$FechaLimiteVariable)
                        {

                         $monto_pago_licencia= $monto_pago_licencia+$monto_licencia;
                         $Cantidad_licencias=$Cantidad_licencias+1;

                         Log::info($monto_pago_licencia);
                         Log::info($Cantidad_licencias);

                         $Cantidadsemanas=ceil(($FechaLimiteVariable->floatDiffInRealWeeks($fechahoy)));

                        $multa=$Cantidadsemanas*$monto_licencia;
                        $multaTotalLicor= $multaTotalLicor+$multa;
                        log::info($FechaLimiteVariable);
                        log::info($fechahoy);
                         Log::info('IF2- Con Multa');
                         log::info($multa);
                         log::info($Cantidadsemanas);
                         log::info($fila2);

                        }else
                             {
                                 $monto_pago_licencia= $monto_pago_licencia+$monto_licencia;
                                 $Cantidad_licencias=$Cantidad_licencias+1;

                                 Log::info($monto_pago_licencia);
                                 Log::info($Cantidad_licencias);
                                 $multaTotalLicor=$multaTotalLicor;
                                 Log::info('IF3 - Sin Multa');
                                 log::info($multa);
                                 log::info($fila2);
                             }

                    }
                 }//** Temrtmina foreach */


             //** Fin- Determinar si el permiso de una licencia ya fue pagada*/


            $totalPagoValor= round($multaTotalLicor+$monto_pago_licencia,2);

            //Dando formato a las cantidades finales
            $multa=number_format($multa, 2, '.', ',');
            $multaTotalLicorDecimal=round($multaTotalLicor,2);
            $totalPagoValorDecimal=round($totalPagoValor, 2);
            $multaTotalLicor=number_format($multaTotalLicor, 2, '.', ',');
            $totalPagoValor=number_format($totalPagoValor, 2, '.', ',');

            //Le agregamos su signo de dollar para la vista al usuario

            $totalPago="$".$totalPagoValor;
            $monto_pago_licenciaDollar="$".$monto_pago_licencia;
            $monto_pago_multaDollar="$".$multaTotalLicor;



        //** Guardar cobro*/
        if ($request->cobrar=='1')
        {
            if($multaTotalLicorDecimal>0)
                {
                foreach ($periodo as $dt)
                    {
                        $AñoCancelar =$dt->format('Y');
                        calificacion::where('id_empresa',$id)
                        ->where('año_calificacion',$AñoCancelar)
                        ->where('id_estado_licencia_licor','2')
                                ->update([
                                            'id_estado_licencia_licor' =>'1',
                                        ]);

                     }

                }

            $cobro = new CobrosLicenciaLicor();
            $cobro->id_empresa = $request->id;
            $cobro->id_usuario = $idusuario;
            $cobro->monto_multa_licencia_15313 = $multaTotalLicorDecimal;
            $cobro->monto_licencia_12207 = $monto_pago_licencia;
            $cobro->codigo ='12207';
            $cobro->pago_total = $totalPagoValorDecimal;
            $cobro->fecha_cobro = $fechahoy;
            $cobro->periodo_cobro_inicio = $InicioPeriodo;
            $cobro->periodo_cobro_fin =$FechaPagara;
            $cobro->tipo_cobro = 'licencia';
            $cobro->save();

            return ['success' => 2];


        }else{
                    return ['success' => 1,
                            'nombre_empresaLicor'=>$nombre_empresa,
                            'totalPagoLicor'=>$totalPago,
                            'monto_pago_licencia'=>$monto_pago_licenciaDollar,
                            'monto_pago_multaDollar'=> $monto_pago_multaDollar,
                            'InicioPeriodoLicor'=>$InicioPeriodo,
                            'PagoUltimoDiaMesLicor'=>$FechaPagara,
                            ];
                }
        }else //** If principal */
        {
            return ['success' => 0];
        }
}


// ---------COBROS ------------------------------------------>


//** Vista_Cobros */
public function cobros($id)
{

    $contribuyentes = Contribuyentes::All();
    $estadoempresas = EstadoEmpresas::All();
    $giroscomerciales = GiroComercial::All();
    $actividadeseconomicas = ActividadEconomica::All();
    $tasasDeInteres = Interes::select('monto_interes')
    ->orderby('id','desc')
    ->get();


    $consulta_detalle_matricula=MatriculasDetalle::select('id')
    ->where('id_empresa', "=", $id)
    ->first();

    $cali_lista=calificacion::latest()
    ->where('id_empresa',$id)
    ->first();

    if($cali_lista==null){
        if($consulta_detalle_matricula!=null)
        {
            $cali_lista=CalificacionMatriculas::latest()
            ->where('id_matriculas_detalle',$consulta_detalle_matricula->id)
            ->first();
        }else{
                 $cali_lista=0;
            }
    }
    log::info('cali_lista que trae: '.$cali_lista);
    $matriculasRegistradas=MatriculasDetalle::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
    ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')

    ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto',
            'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'matriculas.nombre as tipo_matricula','matriculas.slug')
    ->where('id_empresa', "=", "$id")
    ->get();

    $matricula_reg=MatriculasDetalle::where('id_empresa',$id)
    ->first();

    if ($matricula_reg == null)
         {
            $MatriculasNull=1;
            $tipo_matricula=0;
            $id_detalleM=0;
         }else
         {
            $MatriculasNull=0;
            $tipo_matricula=$matricula_reg->id_matriculas;
            $id_detalleM=$matricula_reg->id;
         }

    $calificaciones = calificacion::latest()
    ->join('empresa','calificacion.id_empresa','=','empresa.id')

    ->select('calificacion.id','calificacion.fecha_calificacion','calificacion.tipo_tarifa','calificacion.tarifa','calificacion.estado_calificacion','calificacion.tipo_tarifa','calificacion.id_estado_licencia_licor','calificacion.licencia',
    'empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono')

    ->where('id_empresa', "=", "$id")
    ->first();

    if($calificaciones==null){
        $licencia=0;
    }else{
        $licencia=$calificaciones->licencia;
        }


    $ultimo_cobro = Cobros::latest()
    ->where('id_empresa', "=", "$id")
    ->first();


    $ultimo_cobro_licor = CobrosLicenciaLicor::latest()
    ->where('id_empresa', "=", "$id")
    ->first();

    $ListaCobros = Cobros::where('id_empresa', $id)
    ->get();

    $ListaCobroslicor = CobrosLicenciaLicor::where('id_empresa', $id)
    ->get();

    foreach($ListaCobroslicor as $dato){
        $dato->pago_total=number_format((float)$dato->pago_total, 2, '.', ',');
        $dato->monto_multa_licencia_15313=number_format((float)$dato->monto_multa_licencia_15313, 2, '.', ',');
        $dato->monto_licencia_12207=number_format((float)$dato->monto_licencia_12207, 2, '.', ',');

    }

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit',
    'empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones',
    'empresa.direccion','empresa.num_tarjeta','empresa.telefono','empresa.excepciones_especificas',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel',
    'contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante',
    'contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica','actividad_economica.mora',
     )
    ->find($id);

    if($ultimo_cobro!=null){
        $ultimo_cobro_empresa=Carbon::parse($ultimo_cobro->periodo_cobro_fin)->format('d-m-Y');
    }else{

            $ultimo_cobro_empresa=Carbon::parse($empresa->inicio_operaciones)->format('d-m-Y');
            $ultima_fecha_pago_original=$ultimo_cobro_empresa;

            //** Revisando que la ultima fecha sea el final o inicio de mes */
            $MesNumero=Carbon::createFromDate($ultimo_cobro_empresa)->format('d');

            if($MesNumero<='15')
            {
                $ultimo_cobro_empresa=Carbon::parse($ultima_fecha_pago_original)->subMonthNoOverflow(1)->lastOfMonth()->format('d-m-Y');
            }
            else
                {
                    $ultimo_cobro_empresa=Carbon::parse($ultima_fecha_pago_original)->lastOfMonth()->format('d-m-Y');
                }
            //** Fin - Revisando que la ultima fecha sea el final o inicio de mes */

         }

    //**¨Para detectar los cobros especiales */
    if($empresa->excepciones_especificas==='SI')
    {
      $CE=1;

    }else{
          $CE=0;
         }

    //**¨Fin detectar los cobros especiales */
log::info($tipo_matricula);
    $date=Carbon::now()->toDateString();

    if ($calificaciones == null)
    {
        $detectorNull=0;
        if ($ultimo_cobro == null)
        {
            $detectorCobro=0;
            return view('backend.admin.Empresas.Cobros.Cobros', compact('empresa','giroscomerciales',
            'contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','calificaciones',
            'detectorNull','date','detectorCobro','tasasDeInteres','matriculasRegistradas','MatriculasNull',
            'licencia','ultimo_cobro_licor','CE','ListaCobros','ListaCobroslicor','tipo_matricula',
            'id_detalleM','cali_lista','ultimo_cobro_empresa'));
        }else{
                $detectorCobro=1;
                return view('backend.admin.Empresas.Cobros.Cobros', compact('empresa','giroscomerciales',
                'contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','calificaciones',
                'detectorNull','date','detectorCobro','tasasDeInteres','matriculasRegistradas',
                'MatriculasNull','licencia','ultimo_cobro_licor','CE','ListaCobros','ListaCobroslicor',
                'tipo_matricula','id_detalleM','cali_lista','ultimo_cobro_empresa'));
             }


    }
    else
    {
        $detectorNull=1;
        if ($ultimo_cobro == null)
        {
         $detectorNull=0;
         $detectorCobro=0;
        return view('backend.admin.Empresas.Cobros.Cobros', compact('empresa','giroscomerciales',
        'contribuyentes', 'estadoempresas','actividadeseconomicas','ultimo_cobro','calificaciones',
        'detectorNull','date','detectorCobro','tasasDeInteres','matriculasRegistradas',
        'MatriculasNull','licencia','ultimo_cobro_licor','CE','ListaCobros','ListaCobroslicor',
        'tipo_matricula','id_detalleM','cali_lista','ultimo_cobro_empresa'));
        }
        else
        {
            $detectorNull=1;
            $detectorCobro=1;
            return view('backend.admin.Empresas.Cobros.Cobros', compact('empresa','giroscomerciales',
            'contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','calificaciones',
            'detectorNull','date','detectorCobro','tasasDeInteres','matriculasRegistradas',
            'MatriculasNull','licencia','ultimo_cobro_licor','CE','ListaCobros','ListaCobroslicor',
            'tipo_matricula','id_detalleM','cali_lista','ultimo_cobro_empresa'));
        }
    }

}



 //Registrar empresa
public function nuevaEmpresa(Request $request){

    log::info($request->all());


    $regla = array(

        'num_tarjeta' => 'unique:empresa',
        'empresa' => 'unique:empresa,nit'
    );

    $message=[


        'num_tarjeta.unique'=>'EL número de tarjeta ya esta registrado',
        'nit.unique'=>'EL NIT de tarjeta ya esta registrado'
    ];


    $validar = Validator::make($request->all(), $regla,
    $message

    );

    if ($validar->fails()){

    return [

     'success'=> 0,

    'message' => $validar->errors()->first()

    ];
    }

    $toggle=$request->toggle;

    if($toggle==0){
        $excepciones_especificas='NO';
    }else if($toggle==1){
        $excepciones_especificas='SI';
    }

    //Obtenemos la última resoluición y aumentamos su número.
    $ultima_resolucion=Empresas::orderBy('id','desc')->pluck('num_resolucion')->first();
    $nueva_resolucion=($ultima_resolucion + 1);


    DB::beginTransaction();
    try {

    $dato = new Empresas();
    $dato->id_contribuyente = $request->contribuyente;
    $dato->id_estado_empresa = '2';
    $dato->id_giro_comercial = $request->giro_comercial;
    $dato->id_actividad_economica = $request->actividad_economica;
    $dato->nombre = $request->nombre;
    $dato->matricula_comercio = $request->matricula_comercio;
    $dato->nit = str_replace("-", "", $request->nit);
    $dato->referencia_catastral = $request->referencia_catastral;
    $dato->tipo_comerciante = $request->tipo_comerciante;
    $dato->inicio_operaciones = $request->inicio_operaciones;
    $dato->direccion = $request->direccionE;
    $dato->num_tarjeta = $request->num_tarjeta;
    $dato->telefono = str_replace("-", "", $request->telefono);
    $dato->excepciones_especificas = $excepciones_especificas;
    $dato->num_resolucion = $nueva_resolucion;
    $dato->save();
    if($request->cantidad != null) {
        if($dato->save())
                {

                    $monto=LicenciaMatricula::where('id',$request->tipo_matricula)->pluck('monto')->first();
                    $tarifa=LicenciaMatricula::where('id',$request->tipo_matricula)->pluck('tarifa')->first();

                    $monto_total=$monto*$request->cantidad;
                    $pago_mensual_total=$tarifa*$request->cantidad;

                    $detalleM = new MatriculasDetalle();
                    $detalleM ->id_empresa= $dato->id;
                    $detalleM ->id_matriculas= $request->tipo_matricula;
                    $detalleM ->id_estado_moratorio= '1';
                    $detalleM ->cantidad= $request->cantidad;
                    $detalleM ->monto= $monto_total;
                    $detalleM ->pago_mensual= $pago_mensual_total;
                    $detalleM ->estado_especificacion= 'especificada';
                    $detalleM->save();

                    if($detalleM->save())
                    {
                    for ($i = 0; $i < count($request->cod_municipal); $i++)
                        {
                            $detalleE = new MatriculasDetalleEspecifico();
                            $detalleE ->id_matriculas_detalle= $detalleM->id;
                            $detalleE ->cod_municipal= $request->cod_municipal[$i];
                            $detalleE ->codigo= $request->codigo[$i];
                            $detalleE ->num_serie= $request->num_serie[$i];
                            $detalleE ->direccion= $request->direccionM[$i];
                            $detalleE->save();
                        }

                        if($detalleE->save())
                            {
                                DB::commit();
                                return ['success' => 1];
                            }

                    }

                  }//Fin de if si se guardo empresa.
                }else{
                        DB::commit();
                        return ['success' => 1];
                     }
            }catch(\Throwable $e){
                log::info('ee'.$e);
                DB::rollback();
                return ['success' => 2];
            }


}
 //Termina registrar empresa //



 //Editar empresa
 public function editarEmpresas(Request $request){
    log::info($request->all());

    $regla = array(
        'num_tarjeta' => 'required|unique:empresa,num_tarjeta,'.$request->id,
    );
    $message=[

        'num_tarjeta.unique'=>'EL número de tarjeta ya esta registrado'

    ];
    $validar = Validator::make($request->all(), $regla,
    $message
    );
    if ($validar->fails()){
        return ['success' => 0,
        'message' => $validar->errors()->first()
    ];
    }

    $toggle=$request->toggle;

    if($toggle==0){
        $excepciones_especificas='NO';
    }else if($toggle==1){
        $excepciones_especificas='SI';
    }


    if(Empresas::where('id', $request->id)->first()){

       Empresas::where('id', $request->id)->update([

            'id_giro_comercial' => $request->giro_comercial,
            'id_actividad_economica' => $request->actividad_economica,
            'nombre' => $request->nombre,
            'matricula_comercio' => $request->matricula_comercio,
            'nit' => str_replace("-", "", $request->nit),
            'referencia_catastral'=> $request->referencia_catastral,
            'tipo_comerciante' => $request->tipo_comerciante,
            'inicio_operaciones' => $request->inicio_operaciones,
            'direccion' => $request->direccion,
            'num_tarjeta' => $request->num_tarjeta,
            'telefono' => str_replace("-", "", $request->telefono),
            'excepciones_especificas' =>  $excepciones_especificas

        ]);

        return ['success' => 1];
    }else{
        return ['success' => 2];
    }
}

//Termina editar empresa



//Calcular fechas menores a los primeros 3 meses del año...
public function calculo_calificacion(Request $request)
{


    log::info($request->all());

   $deducciones= $request->deducciones;
   $activo_total=$request->activo_total;
   $licencia=$request->licencia;
   $matricula=$request->matricula;
   $estado_calificacion=$request->estado_calificacion;
   $fecha_pres_balance=$request->fecha_pres_balance;
   $año_calificacion=$request->año_calificacion;

   $signoC='¢';
   $signo='$';

   $licenciaMatricula= $licencia+ $matricula;
   $licenciaMatriculaSigno=$signo . $licenciaMatricula;


   //************* Declarando variables globales para operacion Multa por Balance *************//
   $Year=$año_calificacion;
   $month='03';
   $day='31';
   $anioActual=Carbon::now()->year;
   $CantMesesMulta=0;
   $f1=Carbon::parse($fecha_pres_balance);
   $f2=Carbon::createFromDate($Year, $month, $day);




   if($licenciaMatricula >0){
       $fondoFLM=$licenciaMatricula*0.05;
       $fondoFLMSigno=$signo.$fondoFLM;
       }
       else
       {
         $fondoFLM=0;
         $fondoFLMSigno=$signo.$fondoFLM;
       }

       if($licencia=='')
       {
           $licencia=0.00;
           $licencia=number_format((float)$licencia, 2, '.', ',');
           $licenciaSigno= $signo.$licencia;
       }
       else{
           $licencia=number_format((float)$licencia, 2, '.', ',');
           $licenciaSigno= $signo.$licencia;
       }
       if($matricula=='')
       {
           $matricula=0.00;
           $matriculaSigno=$signo.$matricula;
       }else{
           $matricula=$matricula;
           $matriculaSigno=$signo.$matricula;
       }


   if($activo_total==NULL)
   {
    $tarifa='No Calculada';
    return ['success' => 1,'tarifa' =>$tarifa];
   }
   else if($deducciones==NULL)
   {
    $tarifa='No Calculada';
    return ['success' => 1,'tarifa' =>$tarifa];
   }
   else
   {


       //Este dato sólo se ocupa para tarifa fija ya calculada...................]
       // $TarifaFijaMensualValor=$request->ValortarifaAplicada;
        $id_actividad_especifica=$request->id_actividad_especifica;


        //*** EL activo total y deducciones viene en dolares.....................]
        //*** y se obtiene un activo imponible en dolares........................]
        $activo_imponible=$activo_total-$deducciones;

        //*** Convirtiendo a Colones el activo imponible.........................]
        $activo_imponibleColones=$activo_imponible*8.75;
        // $activo_imponibleColones=$signoC.$activo_imponibleColones;

        //*** Recuperamos el id de la actividad economica de la empresa..........]
        $id_giro_empresarial=$request->id_giro_empresarial;


        $PagoAnualLicenciasValor=round($licenciaMatricula+$fondoFLM,2);
        $PagoAnualLicenciasSigno=$signo.$PagoAnualLicenciasValor;


        $valor= $signo . $activo_imponible;



 //....................................TARIFA VARIABLE..................................................//

        if($activo_imponibleColones>25000)
        {
            $tarifa='Variable';

    //************* Comienza calculo para calcular la tarifa variable *************//


    //*** Comparamos la actividad economica de la empresa para saber.........]
        //*** que consulta le toca y poder sacar los datos de tarifas variables..]
        if($id_giro_empresarial==1)
        {

                //Cargamos todos los registros de la tabla tarifa varibales.
                $ConsultaTarifasVariables = TarifaVariable::join('giro_empresarial','tarifa_variable.id_giro_empresarial','=','giro_empresarial.id')
                ->select('tarifa_variable.id','tarifa_variable.id_giro_empresarial','tarifa_variable.limite_inferior','tarifa_variable.limite_superior','tarifa_variable.fijo','tarifa_variable.excedente','tarifa_variable.categoria','tarifa_variable.millar',
                    'giro_empresarial.nombre_giro_empresarial' )
                ->where('id_giro_empresarial','=',"$id_giro_empresarial")
                ->where(function ($query)use ($activo_imponibleColones)
                {
                   $query->where('limite_superior','>',$activo_imponibleColones)
                      ->orwhere('limite_superior','=',"5000000.01");
                })
                ->first();
        }
        else if($id_giro_empresarial==2)
        {
                //Cargamos todos los registros de la tabla tarifa varibales.
                $ConsultaTarifasVariables = TarifaVariable::join('giro_empresarial','tarifa_variable.id_giro_empresarial','=','giro_empresarial.id')
                ->select('tarifa_variable.id','tarifa_variable.id_giro_empresarial','tarifa_variable.limite_inferior','tarifa_variable.limite_superior','tarifa_variable.fijo','tarifa_variable.excedente','tarifa_variable.categoria','tarifa_variable.millar',
                    'giro_empresarial.nombre_giro_empresarial' )
                ->where('id_giro_empresarial','=',"$id_giro_empresarial")
                ->where(function ($query)use ($activo_imponibleColones)
                {
                   $query->where('limite_superior','>',$activo_imponibleColones)
                      ->orwhere('limite_superior','=',"5000000.01");
                })
                ->first();
        }
        else if($id_giro_empresarial==4)
        {
                //Cargamos todos los registros de la tabla tarifa varibales.
                $ConsultaTarifasVariables = TarifaVariable::join('giro_empresarial','tarifa_variable.id_giro_empresarial','=','giro_empresarial.id')
                ->select('tarifa_variable.id','tarifa_variable.id_giro_empresarial','tarifa_variable.limite_inferior','tarifa_variable.limite_superior','tarifa_variable.fijo','tarifa_variable.excedente','tarifa_variable.categoria','tarifa_variable.millar',
                    'giro_empresarial.nombre_giro_empresarial' )
                ->where('id_giro_empresarial','=',"$id_giro_empresarial")
                ->where(function ($query)use ($activo_imponibleColones)
                {
                   $query->where('limite_superior','>',$activo_imponibleColones)
                   ->orwhere('limite_superior','=',"10000000.01");
                })
                ->first();
        }
        else if($id_giro_empresarial==3)
        {

                //Cargamos todos los registros de la tabla tarifa varibales.
                $ConsultaTarifasVariables = TarifaVariable::join('giro_empresarial','tarifa_variable.id_giro_empresarial','=','giro_empresarial.id')
                ->select('tarifa_variable.id','tarifa_variable.id_giro_empresarial','tarifa_variable.limite_inferior','tarifa_variable.limite_superior','tarifa_variable.fijo','tarifa_variable.excedente','tarifa_variable.categoria','tarifa_variable.millar',
                    'giro_empresarial.nombre_giro_empresarial' )
                ->where('id_giro_empresarial','=',"$id_giro_empresarial")
                ->where(function ($query)use ($activo_imponibleColones)
                {
                   $query->where('limite_superior','>',$activo_imponibleColones)
                      ->orwhere('limite_superior','=',"5000000.01");
                })
                ->first();
        }

        //***Sacando datos de la consulta........................................]
        $actividad_economica= $ConsultaTarifasVariables->actividad_economica;
        $categoria= $ConsultaTarifasVariables->categoria;
        $id_actividad_economica= $ConsultaTarifasVariables->id_actividad_economica;
        $limite_inferior= $ConsultaTarifasVariables->limite_inferior;
        $fijo= $ConsultaTarifasVariables->fijo;
        $millar= $ConsultaTarifasVariables->millar;
        $excedente= $ConsultaTarifasVariables->excedente;
        //***Termina sacando datos de la consulta................................]


        $fijo; //50.0
        $millar; //0.80
        $excedente; //25,000
        $MontoFijodelImpuesto=$fijo;
        $ValorExcedente_en_millares=ceil(($activo_imponibleColones-$excedente)/1000);
        $MontoVariable_del_Impuesto=round($millar*$ValorExcedente_en_millares,2);

        $ImpuestoMensualVariableColones=($MontoFijodelImpuesto+$MontoVariable_del_Impuesto);
        $ImpuestoAnualVariableColones= round($ImpuestoMensualVariableColones*12, 2);

        //*** Convirtiendo el impuesto en dolares *//
        $ImpuestoMensualVariableDolar=round($ImpuestoMensualVariableColones/8.75,2);
        $ImpuestoMensualVariableDolarSigno=$signo . $ImpuestoMensualVariableDolar;
        $ImpuestoAnualVariableDolar=round(($ImpuestoMensualVariableDolar*12),2);


        //*** Calculando los fondos para fiestas patronales *//
        $fondoFPVMensual=$ImpuestoMensualVariableColones*0.05;
        $fondoFPVAnual=$ImpuestoAnualVariableColones*0.05;

        //*** Convirtiendo a dolar los fondos para fiestas patronales *//
        $fondoFPVMensualDolar=round($fondoFPVMensual/8.75,2);
        $fondoFPVAnualDolar=round($fondoFPVAnual/8.75,2);

        //*** Calculando el impuesto total en colones *//
        $ImpuestoTotalMensualColones=round($fondoFPVMensual+$ImpuestoMensualVariableColones,2);
        $ImpuestoTotalAnualColones=round(($fondoFPVMensual+$ImpuestoMensualVariableColones)*12,2);

        //*** Convirtiendo a dolar el impuesto total *//
        $ImpuestoTotalMensualDolar=round($ImpuestoTotalMensualColones/8.75,2);
        $ImpuestoTotalAnualDolar=round(($ImpuestoAnualVariableDolar+$fondoFPVAnualDolar),2);

        //************* Calculando y determinando Multa por Balance *************//
         if( $estado_calificacion=='calificado')
         {
             $multabalance='0.00';
             $DeterminacionDeMulta='No Aplica multas por ser Calificación';

         }
         else if ( $estado_calificacion=='recalificado')
         {
        //*........ Calculando Multa por Balance .............................]

                if($f1->lt($f2))
                {
                    if($Year != $anioActual)
                    {
                        $DeterminacionDeMulta='Aplica multa';

                        $CantMesesMulta=ceil(($f1->floatDiffInRealMonths($f2)));
                        $multabalance=round((($CantMesesMulta*$ImpuestoMensualVariableDolar)*0.02),2);
                        if($multabalance<2.86)
                        {
                          $multabalance=2.86;
                        }

                    }
                    else
                    {
                        $DeterminacionDeMulta='No aplica multa';
                        $CantMesesMulta=0;
                        $multabalance='0.00';
                    }

                }else
                {
                        $DeterminacionDeMulta='Aplica multa';
                        $CantMesesMulta=ceil(($f1->floatDiffInRealMonths($f2)));
                        $multabalance=round((($CantMesesMulta*$ImpuestoMensualVariableDolar)*0.02),2);
                        if($multabalance<2.86)
                        {
                          $multabalance=2.86;
                        }
                }

         }
   //************* Fin de calculando Multa por Balance *************//

            return [

                        'success' => 1,
                        'ValorExcedente_en_millares'=>$ValorExcedente_en_millares,
                        'CantMesesMulta'=>$CantMesesMulta,
                        'f2'=>$f2,
                        'anioActual'=>$anioActual,
                        'DeterminacionDeMulta'=>$DeterminacionDeMulta,
                        'multabalance'=>$multabalance,
                        'tarifa' =>$tarifa,
                        'valor'=>$valor,
                        'PagoAnualLicenciasSigno'=>$PagoAnualLicenciasSigno,
                        'matriculaSigno'=>$matriculaSigno,
                        'licenciaSigno'=>$licenciaSigno,
                        'licencia'=> $licencia,
                        'matricula'=>$matricula,
                        'fondoFLM'=>$fondoFLM,
                        'fondoFLMSigno'=>$fondoFLMSigno,
                        'licenciaMatriculaSigno'=>$licenciaMatriculaSigno,
                        'licenciaMatricula'=>$licenciaMatricula,
                        'PagoAnualLicenciasValor'=>$PagoAnualLicenciasValor,
                        'activo_imponibleColones'=>$activo_imponibleColones,
                        'activo_imponible'=>$activo_imponible,
                        'categoria'=>$categoria,
                        'limite_inferior'=>$limite_inferior,
                        'fijo'=>$fijo,
                        'millar'=> $millar,
                        'excedente'=>$excedente,
                        'id_actividad_economica'=>$id_actividad_economica,
                       // '$id_giro_empresarial'=>$id_giro_empresarial,
                        'actividad_economica'=>$actividad_economica,
                        'ImpuestoAnualVariableColones'=>$ImpuestoAnualVariableColones,
                        'ImpuestoMensualVariableColones'=>$ImpuestoMensualVariableColones,
                        'ImpuestoMensualVariableDolar'=>$ImpuestoMensualVariableDolar,
                        'ImpuestoAnualVariableDolar'=>$ImpuestoAnualVariableDolar,
                        'ImpuestoMensualVariableDolarSigno'=>$ImpuestoMensualVariableDolarSigno,

                        'ImpuestoTotalMensualDolar'=>$ImpuestoTotalMensualDolar,
                        'ImpuestoTotalAnualDolar'=>$ImpuestoTotalAnualDolar,
                        'fondoFPVMensualDolar'=>$fondoFPVMensualDolar,
                        'fondoFPVAnualDolar'=>$fondoFPVAnualDolar,

                    ];
        }
   //.............................TARIFA FIJA.......................................

        else if($activo_imponibleColones<25000)
        {
            $tarifa='Fija';

                //Cargamos todos los registros de la tarifas fijas.
                $ConsultaTarifasFijas = TarifaFija:: where('id_giro_empresarial','=',$id_giro_empresarial)
                ->where('id_actividad_especifica','=',"$id_actividad_especifica")
                ->where(function ($query)use ($activo_imponibleColones)

                {
                    $query->where('limite_superior','>',"$activo_imponibleColones")
                   ->orwhere('limite_superior','=',null);


                })
                ->first();

        log::info($ConsultaTarifasFijas);


         //***Sacando datos de la consulta........................................]
         if($request->CasoEspecial=='1'){
            $impuesto_mensualFijo=ceil($request->tarifaAplicadaCasoEspecial*8.75);
         }
         else{
                $impuesto_mensualFijo=  $ConsultaTarifasFijas->impuesto_mensual;
                }
         $limite_superior=  $ConsultaTarifasFijas->limite_superior;
         $id_actividad_economica=  $ConsultaTarifasFijas->id_actividad_economica;
         $codigo=  $ConsultaTarifasFijas->codigo;
         //***Termina sacando datos de la consulta................................]

         //***Convirtiendo a dolar................................................]
         if($request->CasoEspecial=='1'){
            $tarifaFijaDolar=$request->tarifaAplicadaCasoEspecial;
         }
         else{
              $tarifaFijaDolar=$impuesto_mensualFijo/8.75;
             }
        log::info($impuesto_mensualFijo);

         //***Calculando impuesto total dolares...................................]
            $FondoF= $tarifaFijaDolar*0.05;
            $Total_ImpuestoFijoDolar=$FondoF+$tarifaFijaDolar;


        //***Redondeando a dos decimales..........................................]
            $Total_ImpuestoFijoDolarValor=round( $Total_ImpuestoFijoDolar,2);
            $tarifaFijaDolar=round($tarifaFijaDolar,2);
            $FondoF=round( $FondoF,2);

            $Total_ImpuestoFijoDolarSigno=$signo. $Total_ImpuestoFijoDolar;
            $tarifaFijaMensualDolarSigno= $signo .$tarifaFijaDolar;
            $tarifaenColonesSigno=$signoC . $impuesto_mensualFijo;

       //************* Calculando y determinando Multa por Balance *************//
       if( $estado_calificacion=='calificado')
       {
           $multabalance='0.00';
           $DeterminacionDeMulta='No aplica multas por ser Calificación';

       }
       else if ( $estado_calificacion=='recalificado')
       {
       //*........ Calculando Multa por Balance .............................]

       if($f1->lt($f2))
       {
           if($Year != $anioActual)
           {
            $DeterminacionDeMulta='Aplica multa';

               $CantMesesMulta=ceil(($f1->floatDiffInRealMonths($f2)));
               $multabalance=round((($CantMesesMulta*$tarifaFijaDolar)*0.02),2);
               if($multabalance<2.86)
               {
                 $multabalance=2.86;
               }

           }
           else
           {
               $DeterminacionDeMulta='No aplica multa';
               $CantMesesMulta=0;
               $multabalance='0.00';
           }

       }else
       {
               $DeterminacionDeMulta='Aplica multa';
               $CantMesesMulta=ceil(($f1->floatDiffInRealMonths($f2)));
               $multabalance=round((($CantMesesMulta*$tarifaFijaDolar)*0.02),2);
               if($multabalance<2.86)
               {
                 $multabalance=2.86;
               }
       }
}
//************* Fin de calculando Multa por Balance *************//


            return [
                    'success' => 1,
                    'CantMesesMulta'=>$CantMesesMulta,
                    'anioActual'=>$anioActual,
                    'f2'=>$f2,
                    'DeterminacionDeMulta'=>$DeterminacionDeMulta,
                    'multabalance'=>$multabalance,
                    'tarifa' =>$tarifa,
                    'valor'=>$valor,
                    'FondoF'=>$FondoF,
                    'codigo'=>$codigo,
                    'limite_superior'=>$limite_superior,
                    'id_actividad_economica'=>$id_actividad_economica,
                    'impuesto_mensualFijo'=>$impuesto_mensualFijo,
                    'tarifaFijaDolar'=>$tarifaFijaDolar,
                    'tarifaFijaMensualDolarSigno'=>$tarifaFijaMensualDolarSigno,
                    'Total_ImpuestoFijoDolarValor'=> $Total_ImpuestoFijoDolarValor,
                    'Total_ImpuestoFijoDolarSigno'=> $Total_ImpuestoFijoDolarSigno,
                    'id_actividad_especifica'=>$id_actividad_especifica,
                    'tarifaenColonesSigno'=>$tarifaenColonesSigno,
                    'PagoAnualLicenciasSigno'=>$PagoAnualLicenciasSigno,
                    'matriculaSigno'=>$matriculaSigno,
                    'licenciaSigno'=>$licenciaSigno,
                    'licencia'=> $licencia,
                    'matricula'=>$matricula,
                    'fondoFLMSigno'=>$fondoFLMSigno,
                    'fondoFLM'=>$fondoFLM,
                    'licenciaMatriculaSigno'=>$licenciaMatriculaSigno,
                    'licenciaMatricula'=>$licenciaMatricula,
                    'PagoAnualLicenciasValor'=>$PagoAnualLicenciasValor,
                    'activo_imponibleColones'=>$activo_imponibleColones,
                    'activo_imponible'=>$activo_imponible,

                  ];

        }
        else
        {
        return ['success' => 2];
        }
   }
}


//Registrar Calificación y recalificación
public function asignar_anterior(Request $request){

//*** Para recuperar la fecha actual */
$fechahoy=carbon::now()->format('Y-m-d');

$calificacion_anterior=calificacion::latest()
->where('id_empresa', $request->id_empresa)
->first();

$consulta_detalle_matricula=MatriculasDetalle::select('id')
        ->where('id_empresa', "=", $request->id_empresa)
        ->first();

if($calificacion_anterior==null and $consulta_detalle_matricula!=null)
{

    $calificacionesM=CalificacionMatriculas::latest()
        ->where('id_matriculas_detalle',$consulta_detalle_matricula->id)
        ->first();


    /*** Para aumentar el año de calificación */
    $Anio_a_calificar=$calificacionesM->año_calificacion+1;

    log::info('Ultimo año calificado: '.$calificacionesM->año_calificacion);
    log::info('Año a calificar: '.$Anio_a_calificar);

    log::info('la calificacion es de matricula');
    $dato = new CalificacionMatriculas();
    $dato->id_matriculas_detalle = $calificacionesM->id_matriculas_detalle ;
    $dato->id_estado_matricula ='2';
    $dato->nombre_matricula =$calificacionesM->nombre_matricula;
    $dato->cantidad = $calificacionesM->cantidad;
    $dato->fecha_calificacion = $fechahoy;
    $dato->monto_matricula = $calificacionesM->monto_matricula;
    $dato->pago_mensual = $calificacionesM->pago_mensual;
    $dato->fondofp = $calificacionesM->fondofp;
    $dato->pago_anual = $calificacionesM->pago_anual;
    $dato->tarifa_colones = $calificacionesM->tarifa_colones;
    $dato->total_impuesto_mat =$calificacionesM->total_impuesto_mat;
    $dato->fondofp_impuesto_mat = $calificacionesM->fondofp_impuesto_mat;
    $dato->año_calificacion = $Anio_a_calificar;
    $dato->estado_calificacion = 'Recalificado';
    $dato->tipo_tarifa = $calificacionesM->tipo_tarifa;
    $dato->codigo_tarifa = $calificacionesM->codigo_tarifa;
    $dato->id_giro_empresarial = $calificacionesM->id_giro_empresarial;
    $dato->save();

    return ['success' => 1];


}else{
    log::info('la calificacion es de empresa');

            /*** Para aumentar el año de calificación */
            $Anio_a_calificar=$calificacion_anterior->año_calificacion+1;


            //************* Declarando variables globales para operacion Multa por Balance *************//
            $Year=$Anio_a_calificar;
            $month='03';
            $day='31';
            $anioActual=Carbon::now()->year;
            $CantMesesMulta=0;
            $f1=Carbon::parse($fechahoy);
            $f2=Carbon::createFromDate($Year, $month, $day);

            //************* Calculando y determinando Multa por Balance *************//
            $EstadoCalificacion='recalificado';


            //*........ Calculando Multa por Balance .............................]

                if($f1->lt($f2))
                {
                    if($Year != $anioActual)
                    {
                        $DeterminacionDeMulta='Aplica multa';

                        $CantMesesMulta=ceil(($f1->floatDiffInRealMonths($f2)));
                        $multabalance=round((($CantMesesMulta*$calificacion_anterior->pago_mensual)*0.02),2);
                        if($multabalance<2.86)
                        {
                            $multabalance=2.86;
                        }

                    }
                    else
                    {
                        $DeterminacionDeMulta='No aplica multa';
                        $CantMesesMulta=0;
                        $multabalance='0.00';
                    }

                }else
                {
                        $DeterminacionDeMulta='Aplica multa';
                        $CantMesesMulta=ceil(($f1->floatDiffInRealMonths($f2)));
                        $multabalance=round((($CantMesesMulta*$calificacion_anterior->pago_mensual)*0.02),2);
                        if($multabalance<2.86)
                        {
                            $multabalance=2.86;
                        }
                }

            log::info($DeterminacionDeMulta);
            log::info($CantMesesMulta);
            log::info($multabalance);
            log::info($f1);
            log::info($f2);
            log::info($calificacion_anterior->id_giro_empresarial);


            $dato = new calificacion();
            $dato->id_empresa = $request->id_empresa;
            $dato->id_estado_licencia_licor ='2';
            $dato->id_multa =$calificacion_anterior->id_multa;
            $dato->id_estado_multa ='2';
            $dato->id_giro_empresarial = $calificacion_anterior->id_giro_empresarial;
            $dato->fecha_calificacion = $fechahoy;
            $dato->tipo_tarifa = $calificacion_anterior->tipo_tarifa;
            $dato->estado_calificacion = $EstadoCalificacion;
            $dato->licencia =$calificacion_anterior->licencia;
            $dato->matricula = $calificacion_anterior->matricula;
            $dato->total_mat_permisos = $calificacion_anterior->total_mat_permisos;
            $dato->fondofp_licencia_permisos = $calificacion_anterior->fondofp_licencia_permisos;
            $dato->pago_anual_permisos = $calificacion_anterior->pago_anual_permisos;
            $dato->activo_total = $calificacion_anterior->activo_total;
            $dato->deducciones = $calificacion_anterior->deducciones;
            $dato->activo_imponible = $calificacion_anterior->activo_imponible;
            $dato->año_calificacion =$Anio_a_calificar;
            $dato->tarifa = $calificacion_anterior->tarifa;
            $dato->tarifa_colones = $calificacion_anterior->tarifa_colones;
            $dato->pago_mensual = $calificacion_anterior->pago_mensual;
            $dato->pago_anual = $calificacion_anterior->pago_anual;
            $dato->fondofp_mensual = $calificacion_anterior->fondofp_mensual;
            $dato->fondofp_anual = $calificacion_anterior->fondofp_anual ;
            $dato->total_impuesto = $calificacion_anterior->total_impuesto;
            $dato->total_impuesto_anual = $calificacion_anterior->total_impuesto_anual;
            $dato->multa_balance = $multabalance;
            $dato->codigo_tarifa = $calificacion_anterior->codigo_tarifa;
            $dato->save();

            return ['success' => 1];

            }//Fin else...


}


public function nuevaCalificacion(Request $request){
  log::info($request->all());

  $pago_anual=round($request->pago_anualvariable,2);
  $fondo_mensualvariable=round($request->fondo_mensualvariable,2);
  $fondo_anual=round($request->fondo_anualvariable,2);
  $total_impuesto_anual=round($request->total_impuesto_anualvariable,1);

  $tarifa_colones=round($request->tarifa_colonesFijo,2);
  $total_mat_permisos=round($request->total_mat_permisos,2);
  $activoTotal=round($request->activo_total,2);
  $deducciones=round($request->deducciones,2);
  $activoImponible=round($request->activo_imponible,2);
  $fondofp_licenciaPermisos=round($request->fondofp_licencia_permisos,2);

  log::info($pago_anual);
  log::info($fondo_mensualvariable);
  log::info($fondo_anual);
  log::info($total_impuesto_anual);
  log::info($total_mat_permisos);
  log::info($activoTotal);
  log::info($deducciones);
  log::info($activoImponible);
  log::info($fondofp_licenciaPermisos);
  log::info($tarifa_colones);


    $id_multas=1;
    $id_estado_multa=2;

    $NohayRegistro=0;

    $regla = array(

        'fecha_calificacion' => 'required',
        'tipo_tarifa' => 'required',
        'tarifa'=>'required',
        'año_calificacion'=>'required',
        'pago_mensual'=>'required',
        'total_impuesto'=>'required',
        'multaBalance'=>'required'
    );

    $validar = Validator::make($request->all(), $regla);

    if ($validar->fails())
        {
            return
            [
                'success'=> 0,
            ];
        }

        //** /. Guardar calificaciones de matriculas detalle */

                $dato = new calificacion();
                $dato->id_empresa = $request->id_empresa;
                $dato->id_estado_licencia_licor ='2';
                $dato->id_multa ='1';
                $dato->id_estado_multa ='2';
                $dato->id_giro_empresarial = $request->id_giro_empresarial;
                $dato->fecha_calificacion = $request->fecha_calificacion;
                $dato->tipo_tarifa = $request->tipo_tarifa;
                $dato->estado_calificacion = $request->estado_calificacion;
                $dato->licencia = $request->licencia;
                $dato->matricula = $request->matricula;
                $dato->total_mat_permisos = $total_mat_permisos;
                $dato->fondofp_licencia_permisos = $fondofp_licenciaPermisos;
                $dato->pago_anual_permisos = $request->pago_anual_permisos;
                $dato->activo_total = $activoTotal;
                $dato->deducciones = $deducciones;
                $dato->activo_imponible = $activoImponible;
                $dato->año_calificacion = $request->año_calificacion;
                $dato->tarifa = $request->tarifa;
                $dato->tarifa_colones = $tarifa_colones;
                $dato->pago_mensual = $request->pago_mensual;
                $dato->pago_anual = $pago_anual;
                $dato->fondofp_mensual = $fondo_mensualvariable;
                $dato->fondofp_anual = $fondo_anual;
                $dato->total_impuesto = $request->total_impuesto;
                $dato->total_impuesto_anual = $total_impuesto_anual;
                $dato->multa_balance = $request->multaBalance;
                $dato->codigo_tarifa = $request->codigo_tarifa;
                $dato->save();
                if($dato->save())
                        {
                        return ['success' => 1];

                        }


        }

//Termina registrar Calificación.................................]



//Realizar traspaso
public function infoTraspaso(Request $request)
{
    $regla = array(
        'id' => 'required',
    );

    $validar = Validator::make($request->all(), $regla);

    if ($validar->fails()){ return ['success' => 0];}

    if($lista = Empresas::where('id', $request->id)->first()){

        $contribuyente = Contribuyentes::orderBy('nombre')->get();
        $estado_empresa = EstadoEmpresas::orderBy('estado')->get();
        return ['success' => 1,

        'idcont' => $lista->id_contribuyente,
        'idesta' => $lista->id_estado_empresa,
        'contribuyente' => $contribuyente,
        'estado_empresa' => $estado_empresa,

    ];
}else{
    return ['success' => 2];
}
}

        public function nuevoTraspaso(Request $request)
        {
            $id_empresa=$request->id;
            $id_contribuyente=$request->contribuyente;
            $empresa= Empresas
            ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
            ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
            ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
            ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


            ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'contribuyente.nombre as contribuyente','contribuyente.id as id_contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
            'estado_empresa.estado',
            'giro_comercial.nombre_giro',
            'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica','actividad_economica.mora',
             )
            ->find($id_empresa);

            $datos_contribuyente=Contribuyentes::select('nombre','apellido')
            ->where('id',$id_contribuyente)
            ->first();

            //Obtenemos la última resoluición y aumentamos su número.
            $ultima_resolucion=Traspasos::orderBy('id','desc')->pluck('num_resolucion')->first();
            $nueva_resolucion=($ultima_resolucion + 1);



            $regla = array(
                'id' => 'required',
                'contribuyente' => 'required',
            );

            $validar = Validator::make($request->all(), $regla,

            );

            if ($validar->fails()){
                return ['success' => 0,
                'message' => $validar->errors()->first()
            ];
            }
            if(Empresas::where('id', $request->id)->first()){
                //** Guardar registro historico en tabla traspasos */

            if($id_contribuyente!=$empresa->id_contribuyente){
                $traspaso = new Traspasos();
                $traspaso->id_empresa = $id_empresa;
                $traspaso->propietario_anterior = $empresa->contribuyente.' '.$empresa->apellido;
                $traspaso->propietario_nuevo =  $datos_contribuyente->nombre.' '.$datos_contribuyente->apellido;
                $traspaso->fecha_a_partir_de = $request->Apartirdeldia;
                $traspaso->num_resolucion = $nueva_resolucion;
                $traspaso->save();
                //** FIN- Guardar registro historico en tabla traspasos */
                Empresas::where('id', $request->id)->update([

                     'id_contribuyente' => $request->contribuyente,
                    ]);

                    return ['success' => 1];

                 }else{
                    return ['success' => 3];
                      }

                }else{
                    return ['success' => 2];
                }
        }

    public function nuevoEstado(Request $request)
    {
           $id_empresa=$request->id;
           $estado_empresa=$request->estado_empresa;

           if($estado_empresa==1)
           {
                $Tipo_operacion='Cierre';

                $datos_traspaso=CierresReaperturas::select('num_resolucion')
                ->where('tipo_operacion','Cierre')
                ->latest()
                ->first();

                $nueva_resolucion=$datos_traspaso->num_resolucion+1;

           }else{
                    $Tipo_operacion='Reapertura';

                    $datos_traspaso=CierresReaperturas::select('num_resolucion')
                    ->where('tipo_operacion','Reapertura')
                    ->latest()
                    ->first();

                    $nueva_resolucion=$datos_traspaso->num_resolucion+1;

                }



            $empresa= Empresas
                ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
                ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
                ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
                ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')


                ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                'contribuyente.nombre as contribuyente','contribuyente.id as id_contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
                'estado_empresa.estado','estado_empresa.id as id_estado_empresa',
                'giro_comercial.nombre_giro',
                'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica','actividad_economica.mora',
                )
                ->find($id_empresa);

            $regla = array(
                'id' => 'required',
                'estado_empresa' => 'required',
                'cierre_apartirdeldia' => 'required',
            );

            $validar = Validator::make($request->all(), $regla,

            );

            if ($validar->fails()){
                return ['success' => 0,
                'message' => $validar->errors()->first()
            ];
            }
            if(Empresas::where('id', $request->id)->first()){
              if($estado_empresa!=$empresa->id_estado_empresa){
                //** Guardar registro historico en tabla traspasos */
                $cierre = new CierresReaperturas();
                $cierre->id_empresa = $request->id;
                $cierre->fecha_a_partir_de = $request->cierre_apartirdeldia;
                $cierre->tipo_operacion =$Tipo_operacion;
                $cierre->num_resolucion =$nueva_resolucion;
                $cierre->save();
                //** FIN- Guardar registro historico en tabla traspasos */

                Empresas::where('id', $request->id)->update([

                     'id_estado_empresa' => $request->estado_empresa,


                ]);

                    return ['success' => 1];

                }else{
                        return ['success' => 3];
                     }
        }else
            {
                return ['success' => 2];
            }
    }


    public function tablaMatriculas($id){


        $matriculas=MatriculasDetalle::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
        ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')

        ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto',
                'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                'matriculas.nombre as tipo_matricula')
        ->where('id_empresa', "=", "$id")
        ->get();

            return view('backend.admin.Empresas.Calificaciones.tabla.tabla_matriculas', compact('matriculas'));
    }

    public function tablaCalificaciones($id){

        $empresa= Empresas
        ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
        ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
        ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
        ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')

        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',
        'actividad_economica.rubro','actividad_economica.id as id_act_economica',
        )
        ->find($id);

        $consulta_detalle_matricula=MatriculasDetalle::select('id')
        ->where('id_empresa', "=", $id)
        ->first();


        $calificaciones=calificacion::latest()
        ->where('id_empresa', $id)
        ->get();

        if(sizeof($calificaciones)==0){
            $calificacionesM=CalificacionMatriculas::latest()
            ->where('id_matriculas_detalle',$consulta_detalle_matricula->id)
            ->get();
        }else{
            $calificacionesM=0;
        }

        //** Detectando si ya hay una calificación */
        $consulta_calificacion=calificacion::latest()
        ->where('id_empresa',$id)
        ->first();

        if($consulta_calificacion==null){
                $id_matriculadetalle=MatriculasDetalle::where('id_empresa',$id)->pluck('id')->first();
                $consulta_calificacion=CalificacionMatriculas::latest()->where('id_matriculas_detalle',$id_matriculadetalle)->first();
                if($consulta_calificacion==null)
                {
                    $consulta_calificacion=0;
                }
        }

        //** Detectando que año se debe calificar y el orden */
        $anio_actual=carbon::now()->format('Y');
        $primer_año_calificar=Carbon::parse($empresa->inicio_operaciones)->format('Y');
        if($consulta_calificacion=='0'){
              $año_a_calificar=$primer_año_calificar;
              $ultimo_año_calificado=0;
        }else{
          $ultimo_año_calificado=$consulta_calificacion->año_calificacion;
          $año_a_calificar=$ultimo_año_calificado+1;
        }

        return view('backend.admin.Empresas.Calificaciones.tabla.tabla_calificaciones', compact('calificaciones','calificacionesM','año_a_calificar','anio_actual'));
    }

        //Función para eliminar calificaciones
        public function eliminar_calificacion(Request $request)
        {

            $matriculas=MatriculasDetalle::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
            ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')

            ->select('matriculas_detalle.id as id_matriculas_detalle', 'matriculas_detalle.cantidad','matriculas_detalle.monto',
                    'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                    'matriculas.nombre as tipo_matricula')
            ->where('id_empresa', $request->id_empresa)
            ->first();

            $cali = calificacion::find($request->id);
            if($cali==null){
                $cali = CalificacionMatriculas::find($request->id);
                $cali->delete();
            }else{
                  $cali->delete();
                }

                if($matriculas==null){

                    $buscando_calificaciones=calificacion::where('id_empresa',$request->id_empresa)
                    ->first();
                }else{
                        $buscando_calificaciones=CalificacionMatriculas::where('id_matriculas_detalle',$matriculas->id_matriculas_detalle)
                        ->first();
                     }
                     if($buscando_calificaciones==null){
                            $calificaciones_encontradas=0;
                     }else{
                            $calificaciones_encontradas=1;
                          }

                return [
                            'success' => 1,
                            'calificaciones_encontradas'=> $calificaciones_encontradas,
                       ];

        }
        //Termina función para eliminar calificaciones


        //Función para llenar el select Actividad Especifica
    public function buscarActividadEsp(Request $request)
    {

    $actividad_especifica = ActividadEspecifica::
       where('id_giro_empresarial',$request->id_select)
       ->orderBy('nom_actividad_especifica', 'ASC')
       ->get();

       return [
                'success' => 1,
                'actividad_especifica' => $actividad_especifica
             ];

   }
//Terminar llenar select


public function historial_avisos_notificaciones()
{
    $cantidad_avisos=NotificacionesHistorico::where('id_alertas',1)
    ->count();

    $cantidad_notificaciones=NotificacionesHistorico::where('id_alertas',2)
    ->count();

    return view('backend.admin.Empresas.Historial_avisos_notificaciones.Historial_avisos_notificaciones', compact('cantidad_avisos','cantidad_notificaciones'));
}

public function tablahistoricoavisos(){

    $historico_avisos= NotificacionesHistorico::join('empresa','notificaciones_historico.id_empresa','=','empresa.id')
    ->join('alertas','notificaciones_historico.id_alertas','=','alertas.id')

    ->select('notificaciones_historico.id as id_notificaciones_historico','notificaciones_historico.created_at',
    'empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'alertas.id as id_alertas','alertas.tipo_alerta',
     )
    ->where('id_alertas','1')
    ->orderby('created_at','desc')
    ->get();

    foreach($historico_avisos as $dato){
        $dato->fecha_registro=date("d-m-Y h:m:s A", strtotime($dato->created_at));
    }

    return view('backend.admin.Empresas.Historial_avisos_notificaciones.tabla.tablahistoricoavisos', compact('historico_avisos'));

}

public function tablahistoriconotificaciones(){

    $historico_notificaciones= NotificacionesHistorico::join('empresa','notificaciones_historico.id_empresa','=','empresa.id')
    ->join('alertas','notificaciones_historico.id_alertas','=','alertas.id')

    ->select('notificaciones_historico.id as id_notificaciones_historico','notificaciones_historico.created_at',
    'empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'alertas.id as id_alertas','alertas.tipo_alerta',
     )
    ->where('id_alertas','2')
    ->orderby('created_at','desc')
    ->get();

    foreach($historico_notificaciones as $dato){
        $dato->fecha_registro=date("d-m-Y h:m:s A", strtotime($dato->created_at));
    }

    return view('backend.admin.Empresas.Historial_avisos_notificaciones.tabla.tablahistoriconotificaciones', compact('historico_notificaciones'));

}

} //* Cierre final
