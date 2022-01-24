<?php

namespace App\Http\Controllers\Backend\TarifaFija;

use App\Models\DetalleActividad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;
use Symfony\Contracts\Service\Attribute\Required;
use function PHPUnit\Framework\isEmpty;
use App\Models\ActividadEconomica;
use App\Models\TarifaFija;

class TarifaFijaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('backend.admin.TarifaFija.ListarTarifaFija');
    }

    public function tablaTarifa()
    {
        $tarifa_fija = TarifaFija::orderBy('id', 'ASC')->get();  
             
        return view('backend.admin.TarifaFija.tabla.tablatarifafija', compact('tarifa_fija'));
    
    }

    public function listarTarifaFija()
    {
        $tarifa_fija = TarifaFija::All();
        $infTarifa = TarifaFija::where('id', $tarifa_fija)->first();

        return view('backend.admin.TarifaFija.ListarTarifaFija', compact('tarifa_fija'));
    }

}