<?php

namespace App\Http\Controllers;
 
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    protected $fpdf;
 
    public function __construct()
    {
        $this->fpdf = new Fpdf;
    }
    

    public function estado_cuenta() 
    {
   


        return view('backend.admin.Empresas.EstadoCuenta.Estado_cuenta');
    }
   
}
