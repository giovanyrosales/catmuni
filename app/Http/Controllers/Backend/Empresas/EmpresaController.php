<?php

namespace App\Http\Controllers\Backend\Empresas;

use App\Http\Controllers\Controller;
use App\Models\Contribuyentes;
use App\Models\Usuario;
use App\Models\Empresas;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;

class EmpresaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        
        $idusuario = Auth::id();
        $infouser = Usuario::where('id', $idusuario)->first();

        return view('backend.admin.Empresas.Crear_Empresa');
    }
    public function listarEmpresas()
    {
        $empresas = Empresas::All();

        return view('backend.admin.Empresas.ListarEmpresas', compact('empresas'));
    }
    public function tablaEmpresas(){
        $empresas = Empresas::orderBy('id', 'ASC')->get();

        return view('backend.admin.Empresas.tabla.tablalistaempresas', compact('empresas'));
    }
    public function Contribuyentes()
    {
        $contribuyentes = Contribuyentes::All();

        return view('backend.admin.Empresas.Crear_Empresa', compact('contribuyentes'));
    }
    
}