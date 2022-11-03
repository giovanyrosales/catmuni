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
use App\Models\Interes;
use App\Models\Rotulos;
use App\Models\RotulosDetalle;
use App\Models\RotulosDetalleEspecifico;
use App\Models\CalificacionRotuloDetalle;
use App\Models\Traspasos;
use DateInterval;
use DatePeriod;
use Facade\Ignition\Http\Controllers\ScriptController;
use Illuminate\Support\MessageBag;
use Spatie\Permission\Models\Role;

class reportesRotulosDetalleController extends Controller
{
    public function generar_reporte_rotulos($id) {


            //Configuracion de Reporte en MPDF
            $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
            $mpdf->SetTitle('Alcaldía Metapán | Solvencia');
        
            // mostrar errores
            $mpdf->showImageErrors = false;
        
            $logoalcaldia = 'images/logo.png';
            $logoelsalvador = 'images/EscudoSV.png';
            $linea = 'images/linea4.png';
            $LeyT = 'images/LeyT.png';
        

            $rotulos = RotulosDetalle::join('contribuyente', 'rotulos_detalle.id_contribuyente','contribuyente.id')
            ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo', 'estado_rotulo.id')

            ->select('rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
            'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
            'rotulos_detalle.reg_comerciante','rotulos_detalle.reg_comerciante',
            'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.dui','contribuyente.nit as nit_contribuyente',
            'contribuyente.telefono as tel_contribuyente','contribuyente.email','contribuyente.direccion',
            'estado_rotulo.estado')

            ->find($id);
            $tabla = "<header> <div class='row'> <div class='content'>
                    <img id='logo' src='$logoalcaldia'>
                    <img id='EscudoSV' src='$logoelsalvador'>
                    <h4>DATOS GENERALES DE LA EMPRESA<br>
                    ALCALDIA MUNICIPAL DE METAPÁN, SANTA ANA, EL SALVADOR C.A<br>
                    UNIDAD DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL; TEL. 2402-7614            
                    </h4>
                    <img id='lineaimg' src='$linea'>
                    </div></div></header>";
        
            // $tabla .= "<footer><table><tr><td><p class='izq'><br></p></td><td></td></tr></table></footer>";
            $tabla .= "<div id='content'>
            <table border='0' align='center' style='width: 600px;margin-top: 10px'>
           
            <tr>
                <td align='left' colspan='2' height='50px'><strong><p style='font-size:11'>I. DATOS GENERALES DE LA EMPRESA</p></strong></td>
            </tr>

            <tr>
                <td id='uno'>NÚMERO DE FICHA</td>
                <td id='dos'>$rotulos->num_ficha</td>
            </tr>

            <tr>
                <td id='uno'>NOMBRE DE LA EMPRESA</td>
                <td id='dos'>$rotulos->nom_empresa</td>
            </tr>

            <tr>
                <td id='uno'>NIT</td>
                <td id='dos'>$rotulos->nit_empresa</td>
            </tr>

            <tr>
                <td id='uno'>TELÉFONO</td>
                <td id='dos'>$rotulos->tel_empresa</td>
            </tr>

            <tr>
                <td id='uno'>REGISTRO COMERCIANTE</td>
                <td id='dos'>$rotulos->reg_comerciante</td>
            </tr>

            <tr>
                <td id='uno'>ESTADO</td>
                <td id='dos'>$rotulos->estado </td>
            </tr>

            <tr>
                <td id='uno'>CANTIDAD DE ROTULOS</td>
                <td id='dos'>$rotulos->cantidad_rotulos</td>
            </tr>
         
            <tr>
                <td align='left' colspan='2' height='50px'><strong><p style='font-size:11'>II. CONTRIBUYENTE</p></strong></td>
            </tr> 

            <tr>
                <td id='uno'>NOMBRE</td>
                <td id='dos'>$rotulos->contribuyente $rotulos->apellido</td>
            </tr>

            <tr>
                <td id='uno'>DUI</td>
                <td id='dos'>$rotulos->dui</td>
            </tr>

            <tr>
                <td id='uno'>NIT</td>
                <td id='dos'>$rotulos->nit_contribuyente</td>
            </tr>

            <tr>
                <td id='uno'>TELÉFONO</td>
                <td id='dos'>$rotulos->tel_contribuyente</td>
            </tr>

            <tr>
                <td id='uno'>DIRECCIÓN</td>
                <td id='dos'>$rotulos->direccion</td>
            </tr>

            <tr>
                <td id='uno'>CORREO ELECTRÓNICO</td>
                <td id='dos'>$rotulos->email</td>
            </tr>

            <tr>
                <td align='left' colspan='2' height='50px'><strong><p style='font-size:11'>III. CALIFICACIÓN</p></strong></td>
            </tr> 

            <tr>
                <td id='uno'>FECHA DE CALIFICACIÓN</td>
                <td id='dos'></td>
            </tr>

            <tr>
                <td id='uno'>TARIFA ACTUAL</td>
                <td id='dos'>$</td>
            </tr>

            </table>
            </div>"; 
            
        
            // $stylesheet = file_get_contents('css/dataTables.bootstrap4.css');
            $stylesheet = file_get_contents('css/cssreportepdf.css');
                $mpdf->WriteHTML($stylesheet,1);
                $mpdf->SetMargins(0, 0, 10);
        
        
                //$mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        
                $mpdf->WriteHTML($tabla,2);
                $mpdf->Output();
        
    }


    public function generarCalificacionImprimir ($id)
    {

        //EMPIEZA CONFIGURACIÓN DE PDF
            $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
            $mpdf->SetTitle('Alcaldía Metapán | Solvencia');
        
            // mostrar errores
            $mpdf->showImageErrors = false;
        
            $logoalcaldia = 'images/logo.png';
            $logoelsalvador = 'images/EscudoSV.png';
            $linea = 'images/linea4.png';
            $LeyT = 'images/LeyT.png';



            $rotulosEspecificos = RotulosDetalleEspecifico::join('rotulos_detalle','rotulos_detalle_especifico.id_rotulos_detalle','rotulos_detalle.id')

            ->select('rotulos_detalle_especifico.id','rotulos_detalle_especifico.id_rotulos_detalle', 'rotulos_detalle_especifico.nombre','rotulos_detalle_especifico.medidas',
            'rotulos_detalle_especifico.total_medidas','rotulos_detalle_especifico.caras','rotulos_detalle_especifico.tarifa',
            'rotulos_detalle_especifico.total_tarifa','rotulos_detalle_especifico.coordenadas_geo','rotulos_detalle_especifico.foto_rotulo',
            
            'rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
            'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
            'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',)
    
            ->where('id_rotulos_detalle', $id) 
            ->get();
    
            //log::info($rotulosEspecificos);
         
    
            $rotulos = RotulosDetalle::join('contribuyente', 'rotulos_detalle.id_contribuyente','contribuyente.id')
            ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo', 'estado_rotulo.id')
    
            ->select('rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
            'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
            'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
            
            'contribuyente.id as id_contribuyente', 'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
            'estado_rotulo.id','estado_rotulo.estado')
    
            ->find($id);
    


        $calificacionRotulos = CalificacionRotuloDetalle::join('rotulos_detalle','calificacion_rotulo_detalle.id_rotulos_detalle','rotulos_detalle.id')
                                                        ->join('contribuyente','calificacion_rotulo_detalle.id_contribuyente','contribuyente.id')

        ->select('calificacion_rotulo_detalle.id', 'calificacion_rotulo_detalle.fecha_calificacion','calificacion_rotulo_detalle.estado_calificacion',
        'calificacion_rotulo_detalle.nFicha',

        'rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.fecha_apertura as apertura','rotulos_detalle.nom_empresa as empresa',
        'contribuyente.id as id_contribuyente', 'contribuyente.nombre as contribuyente','contribuyente.apellido')
               
        ->where('id_rotulos_detalle', $id)
      
        ->first();

        

        $tabla = "<header> <div class='row'> <div class='content'>
        <img id='logo' src='$logoalcaldia'>
        <img id='EscudoSV' src='$logoelsalvador'>
        <h4>CALIFICACION &nbsp; $rotulos->nom_empresa &nbsp; $calificacionRotulos->fecha_calificacion<br>
        ALCALDIA MUNICIPAL DE METAPÁN, SANTA ANA, EL SALVADOR C.A<br>
        UNIDAD DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL; TEL. 2402-7614            
        </h4>
        <img id='lineaimg' src='$linea'>
        </div></div></header>";


        $tabla .= "<div id='content'>
            <table border='0' align='center' style='width: 600px;margin-top: 10px'>
           
            <tr>
                <td align='left' colspan='2' height='50px'><strong><p style='font-size:11'>I. DATOS GENERALES</p></strong></td>
            </tr>

            <tr>
                <td id='uno'>NÚMERO DE FICHA:</td>
                <td id='dos' align='center'>$calificacionRotulos->nFicha</td>
            </tr>

            <tr>
                <td id='uno'>FECHA DE APERTURA:</td>
                <td id='dos' align='center'>$calificacionRotulos->apertura</td>
            </tr>

            <tr>
                <td id='uno'>NOMBRE DE LA EMPRESA:</td>
                <td id='dos' align='center'>$calificacionRotulos->empresa</td>
            </tr>

            <tr>
                <td id='uno'>REPRESENTANTE LEGAL:</td>
                <td id='dos' align='center'>$calificacionRotulos->contribuyente&nbsp;$calificacionRotulos->apellido</td>
            </tr>
         
            <tr>
                <td id='uno'>FECHA DE CALIFICACIÓN:</td>
                <td id='dos' align='center'>$calificacionRotulos->fecha_calificacion</td>
            </tr>
           
            <tr>
                <td align='left' colspan='2' height='50px'><strong><p style='font-size:11'>II. BUSES</p></strong></td>
            </tr>

            <div id='content'  >
            </tabla>";

        $tabla .= 
        
                  <tr>
                    <th >RÓTULOS</th>
                    <th  >TOTAL MEDIDAS</th>
                    <th  >CARAS</th>
                    <th  >TARIFA</th> 
                    <th >EJERCICIO</th>
                  </tr>

                  <tr>
            
              @foreach()
                  <td style='width: 150px;' align='center'></td>
                  <td style='width: 150px;' align='center'></td>
                  <td style='width: 150px;' align='center'></td>
                  <td style='width: 150px;' align='center'></td>
                  <td style='width: 150px;' align='center'>2022</td>

                  </tr>
              @endforeach 
                  
                  <tr>
                    <td> </td>
                    <td></td>
                    <td>IMPUESTO:</td>
                    <td align='center'>MENSUAL</td>
                    <td align='center'>ANUAL</td>
                  </tr>

                  <tr>
             
                    <td align='center'></td>
                    <td></td>
                    <td> </td>                         
                    <td align='center' ><label id= 'tarifa_mensual'></label> <input type='hidden' id='tarifa_mensual'></td>                         
                    <td align='center'></td>
                  </tr>                      
                    
                  <tr>
                    <td rowspan='2'></td>
                    <td colspan='2'>Fondo Fiestas Patronales 5%</td>
                    <td align='center'> </td>
                    <td align='center'></td>
                  </tr>

                  <tr>
                    <td colspan='2'>TOTAL IMPUESTO</td>
                    <td align='center' ><strong></strong><label id= 'total_impuesto'></label> <input type='hidden'  id='total_impuesto'></td>
                    <td align='center'><strong></strong></td>
                  </tr>
                  
                </table>
                </div> <!-- /.ROW1 -->
       
    
        </tabla>
        </div>"; 
        
    
        // $stylesheet = file_get_contents('css/dataTables.bootstrap4.css');
        $stylesheet = file_get_contents('css/cssreportepdf.css');
            $mpdf->WriteHTML($stylesheet,1);
            $mpdf->SetMargins(0, 0, 10);
    
    
            //$mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
    
            $mpdf->WriteHTML($tabla,2);
            $mpdf->Output();

    }
        
                   
}
