<?php

namespace App\Http\Controllers\Backend\InspeccionRotulos;

use App\Http\Controllers\Controller;
use App\Models\Contribuyentes;
use App\Models\InspeccionRotulos;
use App\Models\Rotulos;
use App\Models\Empresas;
use App\Models\Usuario;
use Illuminate\Http\Request;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;
use Symfony\Contracts\Service\Attribute\Required;
use Whoops\Run;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class InspeccionRotulosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
 


}
