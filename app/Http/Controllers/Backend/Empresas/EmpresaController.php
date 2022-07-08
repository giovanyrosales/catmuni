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
use App\Models\Cobros;
use App\Models\calificacion;
use App\Models\CalificacionMatriculas;
use App\Models\CierresReaperturas;
use App\Models\CobrosLicenciaLicor;
use App\Models\CobrosMatriculas;
use App\Models\Interes;
use App\Models\LicenciaMatricula;
use App\Models\MatriculasDetalle;
use App\Models\TarifaFija;
use App\Models\TarifaVariable;
use App\Models\MatriculasDetalleEspecifico;
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
        ->join('actividad_especifica','empresa.id_actividad_especifica','=','actividad_especifica.id')
        
        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',
        'actividad_economica.rubro','actividad_economica.id as id_act_economica',
        'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica')
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
                        ->join('actividad_especifica','empresa.id_actividad_especifica','=','actividad_especifica.id')

        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',
        'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica')
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
            $actividad_especifica = ActividadEspecifica::orderBy('nom_actividad_especifica')->get();
            return ['success' => 1,
                'empresa' => $lista,
                'idgiro_co' => $lista->id_giro_comercial,
                'idact_eco' => $lista->id_actividad_economica,
                'giro_comercial' => $giro_comercial,
                'actividad_economica' => $actividad_economica,
                'actividad_especifica' => $actividad_especifica,
                'idact_esp' => $lista->id_actividad_especifica,
                ];
            
        }else{
            return ['success' => 2];
        }
    }

// ---------Calificación de empresa ------------------------------------------>

public function calificacion($id)
{
    $contribuyentes = Contribuyentes::All();
    $licencia = LicenciaMatricula::All()->where('tipo_permiso', "=", "Licencia");
    $matricula = LicenciaMatricula::All()->where('tipo_permiso', "=", "Matrícula");
    $estadoempresas = EstadoEmpresas::All();
    $giroscomerciales = GiroComercial::All();
    $actividadeseconomicas = ActividadEconomica::All();
    $calificaciones = calificacion::All();


    $tarifa_fijas= TarifaFija::join('actividad_economica','tarifa_fija.id_actividad_economica','=','actividad_economica.id')
    ->join('actividad_especifica','tarifa_fija.id_actividad_especifica','=','actividad_especifica.id')

    ->select('tarifa_fija.id','tarifa_fija.id_actividad_especifica','tarifa_fija.codigo','tarifa_fija.limite_inferior','tarifa_fija.limite_superior','tarifa_fija.impuesto_mensual',
    'actividad_economica.rubro as nombre_rubro',
    'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica' )
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
    ->join('actividad_especifica','empresa.id_actividad_especifica','=','actividad_especifica.id')
    
    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica',
    'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica')
    ->where('empresa.id',$id)
    ->first();
    log::info($empresa);

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

         $matriculas=MatriculasDetalle::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
         ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')
                         
         ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual',
                 'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                 'matriculas.nombre as tipo_matricula')
         ->where('id_empresa', "=", "$id")     
         ->get();
        
         $monto = 0;
         if($matriculas==null){
            $monto = 0;

        }
        else
        {
            foreach($matriculas as $dato){
                                          $monto = $monto + $dato->monto;
                                         } 
        }

        $monto = number_format((float)$monto, 2, '.', ',');
        $montoMatriculaValor='$'. $monto;
        
    return view('backend.admin.Empresas.Calificaciones.Calificacion', compact('id','empresa','giroscomerciales', 'monto', 'contribuyentes','estadoempresas','actividadeseconomicas','calificaciones','tarifa_fijas','licencia','matricula','detectorNull','matriculas','montoMatriculaValor','matriculasRegistradas'));
    
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

    $tarifa_fijas= TarifaFija::join('actividad_economica','tarifa_fija.id_actividad_economica','=','actividad_economica.id')
    ->join('actividad_especifica','tarifa_fija.id_actividad_especifica','=','actividad_especifica.id')

    ->select('tarifa_fija.id','tarifa_fija.id_actividad_especifica','tarifa_fija.codigo','tarifa_fija.limite_inferior','tarifa_fija.limite_superior','tarifa_fija.impuesto_mensual',
    'actividad_economica.rubro as nombre_rubro',
    'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica' )
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
    ->join('actividad_especifica','empresa.id_actividad_especifica','=','actividad_especifica.id')

    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica',
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

         $matriculas=MatriculasDetalle::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
         ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')
                         
         ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual',
                 'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                 'matriculas.nombre as tipo_matricula')
         ->where('id_empresa', "=", "$id")     
         ->get();
        
         $monto = 0;
         if($matriculas==null){
            $monto = 0;

        }
        else
        {
            foreach($matriculas as $dato){
                                          $monto = $monto + $dato->monto;
                                         } 
        }

        $monto = number_format((float)$monto, 2, '.', ',');
        $montoMatriculaValor='$'. $monto;

        $cali_lista=calificacion::latest()
        ->where('id_empresa',$id)
        ->first();
    
    return view('backend.admin.Empresas.Calificaciones.Recalificacion', compact('monto','montoMatriculaValor','detectorNull','empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','calificaciones','tarifa_fijas','licencia','matricula','matriculas','cali_lista'));
    
}

// ---------Termina Recalificación de empresa ------------------------------------------>

//Vista detallada
public function show($id)
{
    $fechahoy=carbon::now()->format('Y-m-d');

    $contribuyentes = Contribuyentes::All();
    $estadoempresas = EstadoEmpresas::All();
    $giroscomerciales = GiroComercial::All();
    $actividadeseconomicas = ActividadEconomica::All();
    $matriculasEspecificas=MatriculasDetalle::select('id')
    ->where('id_empresa', "=", $id) 
    ->get();

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

    $alerta_aviso=alertas_detalle::where('id_empresa',$id)
    ->where('id_alerta','1')
    ->pluck('cantidad')
    ->first();

    if($alerta_aviso==null)
        {
            $alerta_aviso=0;
        }

    $alerta_notificacion=alertas_detalle::where('id_empresa',$id)
        ->where('id_alerta','2')
        ->pluck('cantidad')
        ->first();

    $ComprobandoPagoAlDia=cobros::latest()
        ->where('id_empresa',$id)
        ->pluck('periodo_cobro_fin')
            ->first();
    log::info('comprobacion de pago:' .$ComprobandoPagoAlDia);

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


    //** Comprobando si la empresa esta al dia con sus pagos de impuestos de empresa */
    if($ComprobandoPagoAlDia>=$fechahoy)
    {
        $NoNotificar=1;
    }else
            {
                $NoNotificar=0;
            }
    //* fin de comprobar */

    $Consul_traspasos=Traspasos::latest()
    ->where('id_empresa',$id)
    ->first();

    if($Consul_traspasos===null){
        $Consul_traspasos=0;
        }
    else
        {$Consul_traspasos=1;
        }   
   
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

    
    $ultimo_cobro = Cobros::latest()
    ->where('id_empresa',$id)
    ->first();

    if( $ultimo_cobro==null)
    {
        $ultimoCobroEmpresa=$empresa->inicio_operaciones;
    }else{
            $ultimoCobroEmpresa=$ultimo_cobro->periodo_cobro_fin;
        }

    //**¨Para detectar los cobros especiales */
    if($empresa->id_actividad_especifica==118)
    {
        $CE=1;
    } else if($empresa->id_actividad_especifica==120)
            {
                $CE=1; 
            }else if($empresa->id_actividad_especifica==121)
                    {
                        $CE=1;
                    }else if($empresa->id_actividad_especifica==122)
                            {
                                $CE=1;
                            }else if($empresa->id_actividad_especifica==127)
                                    {
                                        $CE=1;
                                    }else{
                                         $CE=0;
                                        }

    //**¨Fin detectar los cobros especiales */

    //**************************** SOLO PARA MATRÍCULAS ****************************/
    $fechahoy=carbon::now()->format('Y-m-d');
    $AnioActual=carbon::now()->format('Y');
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

    log::info('-------------------|');
    foreach($matriculas as $dato)
    {

        $id_detalle=$dato->id_matriculas_detalle;
//*ancla2
        $ComprobandoPagoAlDia=CobrosMatriculas::latest()
        ->where('id_matriculas_detalle',$id_detalle)
        ->pluck('periodo_cobro_fin')
            ->first();
            log::info($ComprobandoPagoAlDia);
        $estado_moratorioM=MatriculasDetalle::where('id',$id_detalle)
            ->pluck('id_estado_moratorio')
                ->first();

        $CantidadDias=ceil(carbon::parse($ComprobandoPagoAlDia)->diffInDays(carbon::parse($fechahoy)));
        log::info('Cantidad de dias transcurridos desde el último pago:'.$CantidadDias);
           
        //*Si es Aparatos Parlantes
            //* Estado matricula: 1= solvente.
        if($dato->id_matricula==2){
            if($ComprobandoPagoAlDia>$fechaLimite)  
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

   if ($calificaciones == null)
    { 
        $detectorNull=0;
        if ($ultimo_cobro == null)
        {  
            $detectorCobro=0;
            return view('backend.admin.Empresas.show', compact('empresa',
                                                                'giroscomerciales',
                                                                'contribuyentes',
                                                                'estadoempresas',
                                                                'actividadeseconomicas',
                                                                'ultimo_cobro',
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
                                                                'NoNotificar'
                                                            ));  

        }else{
                $detectorCobro=1;
                return view('backend.admin.Empresas.show', compact('empresa',
                'giroscomerciales',
                'contribuyentes',
                'estadoempresas',
                'actividadeseconomicas',
                'ultimo_cobro',
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
                'NoNotificar'
                ));   
             }           
    }
    else
    {

            $detectorNull=1;
            if ($ultimo_cobro == null)
            {
                $detectorCobro=0;
                return view('backend.admin.Empresas.show', compact('empresa',
                                                                    'giroscomerciales',
                                                                    'contribuyentes',
                                                                    'estadoempresas',
                                                                    'actividadeseconomicas',
                                                                    'ultimo_cobro',
                                                                    'calificaciones',
                                                                    'ultimo_cobro',
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
                                                                    'NoNotificar'
                                                                ));
            }else
            {
              
                $detectorCobro=1;
               
                return view('backend.admin.Empresas.show', compact('empresa',
                                                                    'giroscomerciales',
                                                                    'contribuyentes',
                                                                    'estadoempresas',
                                                                    'actividadeseconomicas',
                                                                    'ultimo_cobro',
                                                                    'calificaciones',
                                                                    'ultimo_cobro',
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
                                                                    'NoNotificar'
                                                                ));
                                                                }
                   
    }
      
}

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
        ->join('actividad_especifica','empresa.id_actividad_especifica','=','actividad_especifica.id')
        
        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',
        'actividad_economica.rubro',
        'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica')
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
                if($empresa->id_actividad_especifica==118)
                {
                    $tarifa=$request->tarifaMes;
                } else if($empresa->id_actividad_especifica==120)
                        {
                            $tarifa=$request->tarifaMes;
                        }else if($empresa->id_actividad_especifica==121)
                                {
                                    $tarifa=$request->tarifaMes;
                                }else if($empresa->id_actividad_especifica==122)
                                        {
                                            $tarifa=1;
                                        }else if($empresa->id_actividad_especifica==127)
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
                if($empresa->id_actividad_especifica==118)
                {
                    $tarifaMulta=$request->tarifaMes;
                } else if($empresa->id_actividad_especifica==120)
                        {
                            $tarifaMulta=$request->tarifaMes;
                        }else if($empresa->id_actividad_especifica==121)
                                {
                                    $tarifaMulta=$request->tarifaMes;
                                }else if($empresa->id_actividad_especifica==122)
                                        {
                                            $tarifaMulta=$request->tarifaMes;
                                        }else if($empresa->id_actividad_especifica==127)
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
            $cobro->impuesto_mora = $impuestos_mora;
            $cobro->impuesto = $impuesto_año_actual;
            $cobro->intereses_moratorios = $InteresTotal;
            $cobro->monto_multa_balance = $monto_pago_multaBalance;
            $cobro->monto_multaPE = $totalMultaPagoExtemporaneo;
            $cobro->fondo_fiestasP = $fondoFPValor;
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
    $fechahoy=carbon::now();
   
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
        ->join('actividad_especifica','empresa.id_actividad_especifica','=','actividad_especifica.id')
        
        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro',
        'actividad_economica.rubro',
        'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica')
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
            $cobro->monto_multa_licencia = $multaTotalLicorDecimal;
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


//** Vista Cobros */
public function cobros($id)
{
    

    $contribuyentes = Contribuyentes::All();
    $estadoempresas = EstadoEmpresas::All();
    $giroscomerciales = GiroComercial::All();
    $actividadeseconomicas = ActividadEconomica::All();
    $tasasDeInteres = Interes::select('monto_interes')
    ->orderby('id','desc')
    ->get();
    
    $matriculasRegistradas=MatriculasDetalle::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
    ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')
                    
    ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto',
            'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'matriculas.nombre as tipo_matricula','matriculas.slug')
    ->where('id_empresa', "=", "$id")     
    ->get($id);
    
    if ($matriculasRegistradas == null)
         { 
            $MatriculasNull=1;
         }else 
         {
            $MatriculasNull=0;
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
    'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica','actividad_economica.mora',
    'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica')
    ->find($id);   
  

    //**¨Para detectar los cobros especiales */
    if($empresa->id_actividad_especifica==118)
    {
        $CE=1;
    } else if($empresa->id_actividad_especifica==120)
            {
                $CE=1; 
            }else if($empresa->id_actividad_especifica==121)
                    {
                        $CE=1;
                    }else if($empresa->id_actividad_especifica==122)
                            {
                                $CE=1;
                            }else if($empresa->id_actividad_especifica==127)
                                    {
                                        $CE=1;
                                    }else{
                                         $CE=0;
                                        }

    //**¨Fin detectar los cobros especiales */

    $date=Carbon::now()->toDateString();

    if ($calificaciones == null)
    { 
        $detectorNull=0;
        if ($ultimo_cobro == null)
        {
            $detectorCobro=0;
            return view('backend.admin.Empresas.Cobros.Cobros', compact('empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','calificaciones','detectorNull','date','detectorCobro','tasasDeInteres','matriculasRegistradas','MatriculasNull','licencia','ultimo_cobro_licor','CE','ListaCobros','ListaCobroslicor'));
        }else{
                $detectorCobro=1;
                return view('backend.admin.Empresas.Cobros.Cobros', compact('empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','calificaciones','detectorNull','date','detectorCobro','tasasDeInteres','matriculasRegistradas','MatriculasNull','licencia','ultimo_cobro_licor','CE','ListaCobros','ListaCobroslicor'));
             }
            
           
    }
    else
    {  
        $detectorNull=1;
        if ($ultimo_cobro == null)
        {
         $detectorNull=0;
         $detectorCobro=0;
        return view('backend.admin.Empresas.Cobros.Cobros', compact('empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','calificaciones','detectorNull','date','detectorCobro','tasasDeInteres','matriculasRegistradas','MatriculasNull','licencia','ultimo_cobro_licor','CE','ListaCobros','ListaCobroslicor'));
        }
        else
        {
            $detectorNull=1;
            $detectorCobro=1;
            return view('backend.admin.Empresas.Cobros.Cobros', compact('empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','calificaciones','detectorNull','date','detectorCobro','tasasDeInteres','matriculasRegistradas','MatriculasNull','licencia','ultimo_cobro_licor','CE','ListaCobros','ListaCobroslicor'));
        }
    }
      
}



 //Registrar empresa
public function nuevaEmpresa(Request $request){



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


    $dato = new Empresas();
    $dato->id_contribuyente = $request->contribuyente;
    $dato->id_estado_empresa = $request->estado_empresa;
    $dato->id_giro_comercial = $request->giro_comercial;
    $dato->id_actividad_economica = $request->actividad_economica;
    $dato->id_actividad_especifica = $request->actividad_especifica;
    $dato->nombre = $request->nombre;
    $dato->matricula_comercio = $request->matricula_comercio;
    $dato->nit = $request->nit;
    $dato->referencia_catastral = $request->referencia_catastral;
    $dato->tipo_comerciante = $request->tipo_comerciante;
    $dato->inicio_operaciones = $request->inicio_operaciones;
    $dato->direccion = $request->direccion;
    $dato->num_tarjeta = $request->num_tarjeta;
    $dato->telefono = $request->telefono;


    if($dato->save()){
        return ['success' => 1];
    
    }
}

 //Termina registrar empresa

//Función para llenar el select Actividad Especifica
    public function buscarActividadEsp(Request $request)
     {
 
     $actividad_especifica = ActividadEspecifica::
        where('id_actividad_economica',$request->id_select)
        ->orderBy('nom_actividad_especifica', 'ASC')
        ->get();

        return ['success' => 1,
        'actividad_especifica' => $actividad_especifica
        

        ];

    }
//Terminar llenar select

//Función para llenar el select Actividad Especifica
public function buscarActividadEditar(Request $request)
{

$actividad_especifica = ActividadEspecifica::
   where('id_actividad_economica',$request->id_select)
   ->orderBy('nom_actividad_especifica', 'ASC')
   ->get();

   return ['success' => 1,
   'actividad_especifica' => $actividad_especifica,


   ];

}
//Terminar llenar select



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
  
    if(Empresas::where('id', $request->id)->first()){

       Empresas::where('id', $request->id)->update([

            'id_giro_comercial' => $request->giro_comercial,
            'id_actividad_economica' => $request->actividad_economica,
            'id_actividad_especifica' => $request->actividad_especifica,
            'nombre' => $request->nombre,
            'matricula_comercio' => $request->matricula_comercio,
            'nit' => $request->nit,
            'referencia_catastral'=> $request->referencia_catastral,
            'tipo_comerciante' => $request->tipo_comerciante,
            'inicio_operaciones' => $request->inicio_operaciones,
            'direccion' => $request->direccion,
            'num_tarjeta' => $request->num_tarjeta,
            'telefono' => $request->telefono
        
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
        $id_act_economica=$request->id_act_economica;


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
        if($id_act_economica==1)
        {

                //Cargamos todos los registros de la tabla tarifa varibales.
                $ConsultaTarifasVariables = TarifaVariable::join('actividad_economica','tarifa_variable.id_actividad_economica','=','actividad_economica.id')  
                ->select('tarifa_variable.id','tarifa_variable.id_actividad_economica','tarifa_variable.limite_inferior','tarifa_variable.limite_superior','tarifa_variable.fijo','tarifa_variable.excedente','tarifa_variable.categoria','tarifa_variable.millar',
                    'actividad_economica.rubro as actividad_economica' )
                ->where('id_actividad_economica','=',"$id_act_economica")
                ->where(function ($query)use ($activo_imponibleColones)
                {
                   $query->where('limite_superior','>',$activo_imponibleColones)
                      ->orwhere('limite_superior','=',"5000000.01");
                })
                ->first();
        }
        else if($id_act_economica==2)
        {
                //Cargamos todos los registros de la tabla tarifa varibales.
                $ConsultaTarifasVariables = TarifaVariable::join('actividad_economica','tarifa_variable.id_actividad_economica','=','actividad_economica.id')  
                ->select('tarifa_variable.id','tarifa_variable.id_actividad_economica','tarifa_variable.limite_inferior','tarifa_variable.limite_superior','tarifa_variable.fijo','tarifa_variable.excedente','tarifa_variable.categoria','tarifa_variable.millar',
                    'actividad_economica.rubro as actividad_economica' )
                ->where('id_actividad_economica','=',"$id_act_economica")
                ->where(function ($query)use ($activo_imponibleColones)
                {
                   $query->where('limite_superior','>',$activo_imponibleColones)
                      ->orwhere('limite_superior','=',"5000000.01");
                })
                ->first();
        }
        else if($id_act_economica==3)
        {
                //Cargamos todos los registros de la tabla tarifa varibales.
                $ConsultaTarifasVariables = TarifaVariable::join('actividad_economica','tarifa_variable.id_actividad_economica','=','actividad_economica.id')  
                ->select('tarifa_variable.id','tarifa_variable.id_actividad_economica','tarifa_variable.limite_inferior','tarifa_variable.limite_superior','tarifa_variable.fijo','tarifa_variable.excedente','tarifa_variable.categoria','tarifa_variable.millar',
                    'actividad_economica.rubro as actividad_economica' )
                ->where('id_actividad_economica','=',"$id_act_economica")
                ->where(function ($query)use ($activo_imponibleColones)
                {
                   $query->where('limite_superior','>',$activo_imponibleColones)
                   ->orwhere('limite_superior','=',"10000000.01");
                })
                ->first();
        }       
        else if($id_act_economica==4)
        {
            
                //Cargamos todos los registros de la tabla tarifa varibales.
                $ConsultaTarifasVariables = TarifaVariable::join('actividad_economica','tarifa_variable.id_actividad_economica','=','actividad_economica.id')  
                ->select('tarifa_variable.id','tarifa_variable.id_actividad_economica','tarifa_variable.limite_inferior','tarifa_variable.limite_superior','tarifa_variable.fijo','tarifa_variable.excedente','tarifa_variable.categoria','tarifa_variable.millar',
                    'actividad_economica.rubro as actividad_economica' )
                ->where('id_actividad_economica','=',$id_act_economica)
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
        $ValorExcedente_en_millares=round(($activo_imponibleColones-$excedente)/1000);
        $MontoVariable_del_Impuesto=($millar*$ValorExcedente_en_millares);

        $ImpuestoMensualVariableColones=($MontoFijodelImpuesto+$MontoVariable_del_Impuesto);
        $ImpuestoAnualVariableColones= round($ImpuestoMensualVariableColones*12);

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
                        'id_act_economica'=>$id_act_economica,
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
                $ConsultaTarifasFijas = TarifaFija:: where('id_actividad_economica','=',$id_act_economica)
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
public function nuevaCalificacion(Request $request){
  log::info($request->all());

  $pago_anual=round($request->pago_anualvariable,2);
  $fondo_mensualvariable=round($request->fondo_mensualvariable,2);
  $fondo_anual=round($request->fondo_anualvariable,2);
  $total_impuesto_anual=round($request->total_impuesto_anualvariable,2);
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

    $matriculas=MatriculasDetalle::join('empresa','matriculas_detalle.id_empresa','=','empresa.id')
         ->join('matriculas','matriculas_detalle.id_matriculas','=','matriculas.id')
                         
         ->select('matriculas_detalle.id', 'matriculas_detalle.cantidad','matriculas_detalle.monto','matriculas_detalle.pago_mensual',
                 'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                 'matriculas.nombre as tipo_matricula')
         ->where('id_empresa', $request->id_empresa)     
         ->get();
        
       $NohayRegistro=0;

        Log::info('matriculas: '.$matriculas);
    
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

                      //** Guardar calificaciones de matriculas detalle */
                      if(sizeof($matriculas) != 0){
                        log::info('entro a if 1');
                         foreach($matriculas as $dato) {
                              $rDetalle = new CalificacionMatriculas();
                              $rDetalle->id_matriculas_detalle = $dato->id;
                              $rDetalle->id_estado_matricula='2';
                              $rDetalle->nombre_matricula = $dato->tipo_matricula;
                              $rDetalle->cantidad = $dato->cantidad;
                              $rDetalle->monto_matricula = $dato->monto;
                              $rDetalle->pago_mensual = $dato->pago_mensual;
                              $rDetalle->año_calificacion = $request->año_calificacion;
                              $rDetalle->estado_calificacion = $request->estado_calificacion;
                              $rDetalle->save();
                              }
                              
                            if($dato->save() && $rDetalle->save())
                            {
                                return ['success' => 1];
                            
                            }
                            
                         } else
                                {
                                    log::info('entro al else');
                                        if($dato->save())
                                            {
                                            return ['success' => 1];
                                        
                                            }
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
            ->join('actividad_especifica','empresa.id_actividad_especifica','=','actividad_especifica.id')
        
            ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
            'contribuyente.nombre as contribuyente','contribuyente.id as id_contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
            'estado_empresa.estado',
            'giro_comercial.nombre_giro',
            'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica','actividad_economica.mora',
            'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica')
            ->find($id_empresa);   

            $datos_contribuyente=Contribuyentes::select('nombre','apellido')
            ->where('id',$id_contribuyente)
            ->first();
     

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
                //** Guardar registro historio en tabla traspasos */
            
            if($id_contribuyente!=$empresa->id_contribuyente){
                $traspaso = new Traspasos();
                $traspaso->id_empresa = $id_empresa;
                $traspaso->propietario_anterior = $empresa->contribuyente.' '.$empresa->apellido;
                $traspaso->propietario_nuevo =  $datos_contribuyente->nombre.' '.$datos_contribuyente->apellido;
                $traspaso->fecha_a_partir_de = $request->Apartirdeldia;
                $traspaso->save();
                //** FIN- Guardar registro historio en tabla traspasos */
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
           }else{
                    $Tipo_operacion='Reapertura';
                }
            
            $empresa= Empresas
                ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
                ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
                ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
                ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
                ->join('actividad_especifica','empresa.id_actividad_especifica','=','actividad_especifica.id')
            
                ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
                'contribuyente.nombre as contribuyente','contribuyente.id as id_contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
                'estado_empresa.estado','estado_empresa.id as id_estado_empresa',
                'giro_comercial.nombre_giro',
                'actividad_economica.rubro','actividad_economica.id as id_act_economica','actividad_economica.codigo_atc_economica','actividad_economica.mora',
                'actividad_especifica.id as id_actividad_especifica', 'actividad_especifica.nom_actividad_especifica','actividad_especifica.id_actividad_economica')
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

                
        $calificaciones=calificacion::latest()
        ->where('id_empresa', $id)     
        ->get();
                    
            return view('backend.admin.Empresas.Calificaciones.tabla.tabla_calificaciones', compact('calificaciones'));
    }

        //Función para eliminar calificaciones
        public function eliminar_calificacion(Request $request)
        {      
            $cali = calificacion::find($request->id);
            $cali->delete();
                 
                return ['success' => 1];
    
        }
        //Termina función para eliminar calificaciones

} //* Cierre final