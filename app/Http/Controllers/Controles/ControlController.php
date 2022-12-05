<?php

namespace App\Http\Controllers\Controles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ControlController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function indexRedireccionamiento(){

        $user = Auth::user();

        // $permiso = $user->getAllPermissions()->pluck('name');


        // Rol 1: Encargado-Empresas
        if($user->hasPermissionTo('url.empresa.crear.index')){
            $ruta = 'admin.cobrar.empresa.index';
        }

        // Rol 2: Encargado-Inmuebles
        else  if($user->hasPermissionTo('url.inmueble.crear.index')){
            $ruta = 'admin.crear.inmueble.index';
        }

        else{
            // no tiene ningun permiso de vista, redirigir a pantalla sin permisos
            $ruta = 'no.permisos.index';
        }

        return view('backend.index', compact( 'ruta', 'user'));
    }

    public function indexSinPermiso(){
        return view('errors.403');
    }

}
