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

    public function index()
    {
        $empresas = Empresas::ALL();

      
        return view('backend.admin.Buses.CrearBuses', compact('empresas'));
    }

    public function tablaBuses(BusesDetalle $buses){

        $buses=BusesDetalle        
        ::join('empresa','buses_detalle.id_empresa','=','empresa.id')
                              
        ->select('buses_detalle.id as id_buses', 'buses_detalle.cantidad','buses_detalle.monto_pagar','buses_detalle.tarifa',       
                'empresa.nombre as empresa','empresa.matricula_comercio','empresa.nit','empresa.referencia_catastral',
                'empresa.tipo_comerciante','empresa.inicio_operaciones','empresa.direccion','empresa.num_tarjeta',
                'empresa.telefono')
      
        ->get();

           
        return view('backend.admin.Buses.tabla.tabla_buses', compact('buses'));
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

    
}   