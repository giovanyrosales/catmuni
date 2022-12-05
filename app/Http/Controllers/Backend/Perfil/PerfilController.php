<?php

namespace App\Http\Controllers\Backend\Perfil;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{ 
     public function __construct(){
        $this->middleware('auth');
    }

    public function indexEditarPerfil(){
       $usuario = auth()->user();
       return view('backend.admin.usuario.editarperfil', compact('usuario'));
    }

    public function actualizarUsuario(Request $request){

       if (!Auth::check()) {return ['success' => 2];}
       //Log::info($request->all());
        
        $usuario = auth()->user();
  
            Usuario::where('id', $usuario->id)
                ->update(['password' => bcrypt($request->password)]);

            return ['success' => 1];
        }
        
        
    
}