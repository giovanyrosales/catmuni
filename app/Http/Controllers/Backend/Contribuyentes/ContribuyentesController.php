<?php

namespace App\Http\Controllers\Backend\Contribuyentes;

use App\Models\Contribuyentes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;



class ContribuyentesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function listarContribuyentes()
    {
        $contribuyentes = Contribuyentes::All();
        $infContribuyente = Contribuyentes::where('id', $contribuyentes)->first();
    //     ->join('empresa','empresa.id_empresa','=','contribuyentes.id_empresa')
    //     ->select('contribuyente.nombre','empresa.nombre')
    //     ->get();
    //     dd($contribuyentes);
        return view('backend.admin.Contribuyentes.ListarContribuyentes', compact('contribuyentes'));
    }
    public function crearContribuyentes()
    {

        return view('backend.admin.Contribuyentes.Crear_Contribuyentes');
    }
    public function tablaContribuyentes(){
        $contribuyentes = Contribuyentes::orderBy('id', 'ASC')->get();

        return view('backend.admin.Contribuyentes.tabla.tablalistacontribuyentes', compact('contribuyentes'));
    }
}
