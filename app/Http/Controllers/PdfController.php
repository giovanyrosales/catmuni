<?php

namespace App\Http\Controllers;
 
use App\Http\Controllers\Fpdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{


    public function estado_cuenta() 
    {
    	
        return view('backend.admin.Empresas.EstadoCuenta.Estado_cuenta');
    }
   
}
