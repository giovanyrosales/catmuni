<?php

namespace App\Http\Controllers\Backend\BusesDetalle;

use App\Http\Controllers\Backend\MatriculasDetalle\alert;
use App\Http\Controllers\Controller;
use App\Models\BusesDetalle;
use App\Models\Calificacion;
use App\Models\CalificacionMatriculas;
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
use Illuminate\Support\Facades\Bus;
use Ramsey\Uuid\Guid\Guid;

class BusesDetalleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $empresas= Empresas
        ::join('contribuyente','empresa.id_contribuyente','=','contribuyente.id')
        ->join('buses_detalle','empresa.id_buses_detalle','=','buses_detalle.id')
      
        ->select('empresa.id','empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',
        'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.telefono as tel','contribuyente.dui','contribuyente.email','contribuyente.nit as nitCont','contribuyente.registro_comerciante','contribuyente.fax', 'contribuyente.direccion as direccionCont',  
        'buses_detalle.id as id_buses_detalle', 'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.tarifa')
        ->find($id);   
        
        $busesRegistrados=BusesDetalle
        ::join('empresa','buses_detalle.id_empresa','=','empresa.id')
                               
        ->select('buses_detalle.id', 'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.tarifa',
                'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral','empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta','empresa.telefono',)
        ->where('id_empresa', "=", "$id")     
        ->first($id);

        
        if ($busesRegistrados == null)
             { 
                 $detectorNull=1;
             }else 
             {
                $detectorNull=0;
             }


        
        return view('backend.admin.Buses.CrearBuses', compact('id','empresas','detectorNull'));
    }

    public function nuevoBus(Request $request)
    {
        log::info($request->all());
     
        $regla = array(  
            'empresa' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);
    
        if ($validar->fails()){ return ['success' => 0];}

        $bus = new BusesDetalle();       
        $bus->id_empresa = $request->empresa;       
        $bus->cantidad = $request->cantidad;
        $bus->monto_pagar = $request->monto_pagar;
        $bus->tarifa = $request->tarifa;
        
        if($bus->save()){
            return ['success' => 1];
        }
    
    }

    public function tablaBuses($id){

        $buses=BusesDetalle        
        ::join('empresa','buses.id_empresa','=','empresa.id')
                              
        ->select('buses.id as id_buses', 'buses.cantidad','buses.monto_pagar','buses.tarifa',       
                'empresa.nombre','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral',
                'empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta',
                'empresa.telefono',)
        ->where('id_empresa', "=", "$id")     
        ->get();

           
        return view('backend.admin.Buses.tabla.tabla_buses', compact('buses'));
    }
}   