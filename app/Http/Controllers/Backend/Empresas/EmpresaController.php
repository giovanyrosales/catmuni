<?php

namespace App\Http\Controllers\Backend\Empresas;

use App\Http\Controllers\Controller;
use App\Models\Contribuyentes;
use App\Models\Usuario;
use App\Models\Empresas;
use App\Models\EstadoEmpresas;
use App\Models\GiroComercial;
use App\Models\ActividadEconomica;
use App\Models\Cobros;
use App\Models\calificacion;
use App\Models\Interes;
use App\Models\LicenciaMatricula;
use App\Models\TarifaFija;
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

        return view('backend.admin.Empresas.Crear_Empresa', compact('contribuyentes','estadoempresas','giroscomerciales','actividadeseconomicas','ConsultaEmpresa'));
    }

    public function listarEmpresas()
    {
       
        $contribuyentes = Contribuyentes::All();
        $estadoempresas = EstadoEmpresas::All();
        $giroscomerciales = GiroComercial::All();
        $actividadeseconomicas = ActividadEconomica::All();



        return view('backend.admin.Empresas.ListarEmpresas', compact('contribuyentes','estadoempresas','giroscomerciales','actividadeseconomicas'));
    }
    public function tablaEmpresas(Empresas $lista){

                
        $lista=Empresas::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
                        ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
                        ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')

        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido',
        'estado_empresa.estado',
        'giro_comercial.nombre_giro')
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
            $contribuyente = Contribuyentes::orderBy('nombre')->get();
            $estado_empresa = EstadoEmpresas::orderBy('estado')->get();
            $giro_comercial = GiroComercial::orderBy('nombre_giro')->get();
            $actividad_economica = ActividadEconomica::orderBy('rubro')->get();
            return ['success' => 1,
                'empresa' => $lista,
                'idcont' => $lista->id_contribuyente,
                'idesta' => $lista->id_estado_empresa,
                'idgiro_co' => $lista->id_giro_comercial,
                'idact_eco' => $lista->id_actividad_economica,
                'contribuyente' => $contribuyente,
                'estado_empresa' => $estado_empresa,
                'giro_comercial' => $giro_comercial,
                'actividad_economica' => $actividad_economica
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
            
    ->select('tarifa_fija.id','tarifa_fija.nombre_actividad','tarifa_fija.limite_inferior','tarifa_fija.limite_superior','tarifa_fija.impuesto_mensual',
    'actividad_economica.rubro as nombre_rubro' )
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
    'actividad_economica.rubro','actividad_economica.id as id_act_economica')
    ->find($id);
    
    return view('backend.admin.Empresas.Calificaciones.Calificacion', compact('empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','calificaciones','tarifa_fijas','licencia','matricula'));
    
}

// ---------Termina Recalificación de empresa ------------------------------------------>

// ---------Calificación de empresa ------------------------------------------>

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
            
    ->select('tarifa_fija.id','tarifa_fija.nombre_actividad','tarifa_fija.limite_inferior','tarifa_fija.limite_superior','tarifa_fija.impuesto_mensual',
    'actividad_economica.rubro as nombre_rubro' )
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
    'actividad_economica.rubro','actividad_economica.id as id_act_economica')
    ->find($id);
    
    return view('backend.admin.Empresas.Calificaciones.Recalificacion', compact('empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','calificaciones','tarifa_fijas','licencia','matricula'));
    
}

// ---------Termina Recalificación de empresa ------------------------------------------>

//Vista detallada
public function show($id)
{
    $contribuyentes = Contribuyentes::All();
    $estadoempresas = EstadoEmpresas::All();
    $giroscomerciales = GiroComercial::All();
    $actividadeseconomicas = ActividadEconomica::All();

    $calificaciones = calificacion
    ::join('empresa','calificacion.id_empresa','=','empresa.id')
    
    ->select('calificacion.id','calificacion.fecha_calificacion','calificacion.tarifa','calificacion.tipo_tarifa','calificacion.estado_calificacion',
    'empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono')
    ->where('id_empresa', "=", "$id")
    ->first();


    $ultimo_cobro = Cobros::latest()
    ->where('id_empresa', "=", "$id")
    ->first();

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
    
    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro')
    ->find($id);    

   if ($calificaciones == null)
    { 
        $detectorNull=0;
        if ($ultimo_cobro == null)
        {
            $detectorNull=0;
            $detectorCobro=0;
            return view('backend.admin.Empresas.show', compact('empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','detectorNull','detectorCobro'));
        }
            
           
    }
    else
    {

            $detectorNull=1;
            if ($ultimo_cobro == null)
            {
                $detectorCobro=0;
                return view('backend.admin.Empresas.show', compact('empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','calificaciones','ultimo_cobro','detectorNull','detectorCobro'));
            }else
            {
                $detectorCobro=1;
                return view('backend.admin.Empresas.show', compact('empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','calificaciones','ultimo_cobro','detectorNull','detectorCobro'));
            }
                   
    }
      
}

// ---------COBROS ------------------------------------------>
public function calculo_cobros(Request $request, $id)
{
        log::info($request->all());
        $f1=Carbon::parse($request->ultimo_cobro);
        $f2=Carbon::parse($request->fechaPagara);

        $calificaciones = calificacion::join('empresa','calificacion.id_empresa','=','empresa.id')
        
        ->select('calificacion.id','calificacion.fecha_calificacion','calificacion.tipo_tarifa','calificacion.tarifa','calificacion.estado_calificacion','calificacion.tipo_tarifa','calificacion.estado_calificacion',
        'empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono')
    
        ->where('id_empresa', "=", "$id")
        ->first();
        
        if($f1->lt($f2))
        {
            $CantidadMeses=$f1->diffInMonths($f2);  

            $tarifa=$calificaciones->tarifa;
            $fechaPagara= $request->fechaPagara;
          
 
            $signoDollar='$';
            

            $impuestosValor=round($tarifa*$CantidadMeses,2);
            $fondoFPValor=round($impuestosValor*0.05,2);
            $totalPagoValor= round($fondoFPValor+$impuestosValor,2);

            //Le agregamos su signo de dollar para la vista al usuario
            $fondoFP= $signoDollar. $fondoFPValor;
            $impuestos= $signoDollar. $impuestosValor;
            $totalPago=$signoDollar.$totalPagoValor;
           

            return ['success' => 1, 'cantidadMeses' => $CantidadMeses,'impuestos' => $impuestos,'tarifa'=>$tarifa,'fondoFP'=>$fondoFP,'totalPago'=>$totalPago];
        }else
        {
            return ['success' => 0];
        }

}

public function cobros($id)
{
    $contribuyentes = Contribuyentes::All();
    $estadoempresas = EstadoEmpresas::All();
    $giroscomerciales = GiroComercial::All();
    $actividadeseconomicas = ActividadEconomica::All();
    $tasasDeInteres = Interes::All();

    $calificaciones = calificacion
    ::join('empresa','calificacion.id_empresa','=','empresa.id')
    
    ->select('calificacion.id','calificacion.fecha_calificacion','calificacion.tipo_tarifa','calificacion.tarifa','calificacion.estado_calificacion','calificacion.tipo_tarifa','calificacion.estado_calificacion',
    'empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono')

    ->where('id_empresa', "=", "$id")
    ->first();


    $ultimo_cobro = Cobros::latest()
    ->where('id_empresa', "=", "$id")
    ->first();

    $empresa= Empresas
    ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
    ->join('estado_empresa','empresa.id_estado_empresa','=','estado_empresa.id')
    ->join('giro_comercial','empresa.id_giro_comercial','=','giro_comercial.id')
    ->join('actividad_economica','empresa.id_actividad_economica','=','actividad_economica.id')
    
    ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
    'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',
    'estado_empresa.estado',
    'giro_comercial.nombre_giro',
    'actividad_economica.rubro','actividad_economica.id as id_act_economica')
    ->find($id);   
    
    $date=Carbon::now()->toDateString();

   if ($calificaciones == null)
    { 
        $detectorNull=0;
        if ($ultimo_cobro == null)
        {
            $detectorNull=0;
            $detectorCobro=0;
            return view('backend.admin.Empresas.Cobros.Cobros', compact('empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','detectorNull','date','detectorCobro','tasasDeInteres'));
        }
            
           
    }
    else
    {  
        $detectorNull=1;
        if ($ultimo_cobro == null)
        {
         $detectorNull=0;
         $detectorCobro=0;
        return view('backend.admin.Empresas.Cobros.Cobros', compact('empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','calificaciones','ultimo_cobro','detectorNull','date','detectorCobro','tasasDeInteres'));
        }
        else
        {
            $detectorNull=1;
            $detectorCobro=1;
            return view('backend.admin.Empresas.Cobros.Cobros', compact('empresa','giroscomerciales','contribuyentes','estadoempresas','actividadeseconomicas','ultimo_cobro','calificaciones','ultimo_cobro','detectorNull','date','detectorCobro','tasasDeInteres'));
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

 //Editar empresa
 public function editarEmpresas(Request $request){

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

            'id_contribuyente' => $request->contribuyente,
            'id_estado_empresa' => $request->estado_empresa,
            'id_giro_comercial' => $request->giro_comercial,
            'id_actividad_economica' => $request->actividad_economica,
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
        $TarifaFijaMensualValor=$request->ValortarifaAplicada;
       

        $activo_imponible=$activo_total-$deducciones;

        $signo='$'; 

        $licenciaMatricula= $licencia+ $matricula;
        $licenciaMatriculaSigno=$signo . $licenciaMatricula;

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
                $licencia=0;
            }
            else{
                $licencia= $signo.$licencia;
            }
            if($matricula=='')
            {
                $matricula=0; 
            }else{
                $matricula=$signo.$matricula;
            }
            
            
            $PagoAnualLicenciasValor=round($licenciaMatricula+$fondoFLM,2);
            $PagoAnualLicenciasSigno=$signo.$PagoAnualLicenciasValor;
    
        $tarifaenColones=$TarifaFijaMensualValor*8.75;
        $FondoF= $TarifaFijaMensualValor*0.05;
        $Total_Impuesto=$FondoF+$TarifaFijaMensualValor;
        $valor= $signo . $activo_imponible;
    
        //Redondeando a dos decimales.
        $FondoF=round($FondoF,2);
        $Total_Impuesto=round($Total_Impuesto,2);
        $tarifaenColones=round($tarifaenColones,2);
    
        $signoC='¢';
        $tarifaenColonesSigno=$signoC . $tarifaenColones;
    
    
        if($activo_imponible>2858.14)
        {
            $tarifa='Variable';  
            return ['success' => 1, 'tarifa' =>$tarifa, 'valor'=>$valor];
        }
        else if($activo_imponible<2858.14)
        {
            $tarifa='Fija';  
            return ['success' => 1, 'tarifa' =>$tarifa, 'valor'=>$valor, 'FondoF'=>$FondoF,'Total_Impuesto'=>$Total_Impuesto,'tarifaenColonesSigno'=>$tarifaenColonesSigno,
            'PagoAnualLicenciasSigno'=>$PagoAnualLicenciasSigno, 'licencia'=> $licencia,'matricula'=>$matricula,'fondoFLMSigno'=>$fondoFLMSigno,'licenciaMatriculaSigno'=>$licenciaMatriculaSigno,'PagoAnualLicenciasValor'=>$PagoAnualLicenciasValor
        ];   
        
        }
        else
        {
        return ['success' => 2];
        }
   }   
}  
 

//Registrar Calificación
public function nuevaCalificacion(Request $request){
    
    $regla = array(

        'fecha_calificacion' => 'required',
        'tipo_tarifa' => 'required',
        'tarifa'=>'required',
        'año_calificacion'=>'required',
        'pago_mensual'=>'required',
        'total_impuesto'=>'required',  
    );

    $validar = Validator::make($request->all(), $regla);

    if ($validar->fails())
        {
            return 
            [
                'success'=> 0,
            ];
        }
                    
                $dato = new calificacion();
                $dato->id_empresa = $request->id_empresa;
                $dato->fecha_calificacion = $request->fecha_calificacion;
                $dato->tipo_tarifa = $request->tipo_tarifa;
                $dato->tarifa = $request->tarifa;
                $dato->estado_calificacion = $request->estado_calificacion;
                $dato->licencia = $request->licencia;
                $dato->matricula = $request->matricula;
                $dato->año_calificacion = $request->año_calificacion;
                $dato->pago_mensual = $request->pago_mensual;
                $dato->total_impuesto = $request->total_impuesto;
                $dato->pago_anual_permisos = $request->pago_anual_permisos;
                

                if($dato->save())
                {
                    return ['success' => 1];
                
                }
        }
        //Termina registrar Calificación


    }
