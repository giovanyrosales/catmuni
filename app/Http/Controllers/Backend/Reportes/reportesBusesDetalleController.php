<?php

namespace App\Http\Controllers\Backend\Reportes;

use App\Models\Contribuyentes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Faker\Core\Number;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;
use Symfony\Contracts\Service\Attribute\Required;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use PDF;
use App\Models\Empresas;
use App\Models\Usuario;
use App\Models\EstadoEmpresas;
use App\Models\GiroComercial;
use App\Models\ActividadEconomica;
use App\Models\ActividadEspecifica;
use App\Models\Cobros;
use App\Models\calificacion;
use App\Models\CalificacionMatriculas;
use App\Models\CobrosLicenciaLicor;
use App\Models\CobrosMatriculas;
use App\Models\Interes;
use App\Models\LicenciaMatricula;
use App\Models\MatriculasDetalle;
use App\Models\TarifaFija;
use App\Models\TarifaVariable;
use App\Models\MultasDetalle;
use App\Models\MatriculasDetalleEspecifico;
use App\Models\alertas;
use App\Models\alertas_detalle;
use App\Models\BusesDetalle;
use App\Models\CalificacionBuses;
use App\Models\CalificacionRotulo;
use App\Models\CierresReaperturas;
use App\Models\Rotulos;
use App\Models\Traspasos;
use DateInterval;
use DatePeriod;
use Illuminate\Support\MessageBag;
use Spatie\Permission\Models\Role;

class reportesBusesDetalleController extends Controller
{
    public function estado_cuentas_buses_d ($f1,$f2,$ti,$f3,$id)
    {
        log::info([$f1,$f2,$ti,$f3,$id,]);
       
        $calificacionBus = CalificacionBuses::latest()
        ->where('id_contribuyente', $id)
        ->first();

        $f1_original=$f1;

        $buses = BusesDetalle::join('contribuyente','buses_detalle.id_contribuyente','=','contribuyente.id')
        ->join('estado_buses','buses_detalle.id_estado_buses','=','estado_buses.id')

        ->select('buses_detalle.id', 'buses_detalle.fecha_apertura','buses_detalle.nFicha',
        'buses_detalle.cantidad','buses_detalle.tarifa','buses_detalle.monto_pagar','buses_detalle.estado_especificacion',
        'buses_detalle.nom_empresa','buses_detalle.dir_empresa','buses_detalle.nit_empresa',
        'buses_detalle.tel_empresa','buses_detalle.email_empresa','buses_detalle.r_comerciante',
        
        'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
        'estado_buses.estado')
                        
    ->find($id);

    
    }
}