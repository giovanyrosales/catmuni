<?php

namespace App\Http\Controllers\Backend\Buses;

use App\Http\Controllers\Backend\MatriculasDetalle\alert;
use App\Http\Controllers\Controller;
use App\Models\Buses;
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

class BusesController extends Controller
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
}   