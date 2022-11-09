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

        log::info('id ' . $id);
       
        $FechaDelDia = carbon::now()->format('d-m-Y');
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
                                                        ->join('rotulos_detalle_especifico','calificacion_rotulo_detalle.id_rotulos_detalle_especifico','rotulos_detalle_especifico.id')

        ->select('calificacion_rotulo_detalle.id','calificacion_rotulo_detalle.id_rotulos_detalle', 'calificacion_rotulo_detalle.fecha_calificacion','calificacion_rotulo_detalle.estado_calificacion',
        'calificacion_rotulo_detalle.nFicha',

        'rotulos_detalle.id','rotulos_detalle.fecha_apertura','rotulos_detalle.nom_empresa',
        'contribuyente.id as id_contribuyente', 'contribuyente.nombre as contribuyente','contribuyente.apellido',
        
        'rotulos_detalle_especifico.id', 'rotulos_detalle_especifico.nombre','rotulos_detalle_especifico.medidas',
        'rotulos_detalle_especifico.total_medidas','rotulos_detalle_especifico.caras','rotulos_detalle_especifico.tarifa',
        'rotulos_detalle_especifico.total_tarifa','rotulos_detalle_especifico.coordenadas_geo','rotulos_detalle_especifico.foto_rotulo',)

               
        ->where('calificacion_rotulo_detalle.id_rotulos_detalle', $id)      
        ->get();

        log::info($calificacionRotulos);
        //return;

        $calificacion = CalificacionRotuloDetalle::where('id_rotulos_detalle', $id)->first();


     
        $tabla =" <div class='content'>
                <img id='logo' src='$logoalcaldia'>
                <img id='EscudoSV' src='$logoelsalvador'>
                <h4>CALIFICACIÓN &nbsp;$calificacion->fecha_calificacion <br>
                ALCALDIA MUNICIPAL DE METAPÁN, SANTA ANA, EL SALVADOR C.A<br>
                UNIDAD DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL; TEL. 2402-7614            
                </h4>
                <hr>
            </div>";
    


        $tabla .= "<div id='contentRotulo'>
            <table border='0' align='center' style='width: 600px;'>
           
            <tr>
                <td align='left' colspan='2' height='50px'><strong><p style='font-size:11'>I. DATOS GENERALES</p></strong></td>
            </tr>

            <tr>
                <td id='uno'>NÚMERO DE FICHA:</td>
                <td id='dos'>$calificacion->nFicha</td>
            </tr>

            <tr>
                <td id='uno'>FECHA DE APERTURA:</td>
                <td id='dos' >$rotulos->fecha_apertura</td>
            </tr>

            <tr>
                <td id='uno'>NOMBRE DE LA EMPRESA:</td>
                <td id='dos'>$rotulos->nom_empresa</td>
            </tr>

            <tr>
                <td id='uno'>REPRESENTANTE LEGAL:</td>
                <td id='dos' >$rotulos->contribuyente</td>
            </tr>
         
            <tr>
                <td id='uno'>FECHA DE CALIFICACIÓN:</td>
                <td id='dos'>$calificacion->fecha_calificacion</td>
            </tr>
           
            <tr>
                <td align='left' colspan='2' height='50px'><strong><p style='font-size:11'>II. BUSES</p></strong></td>
            </tr>
            </div>
            </table>";
     
            $tabla .=  " <table id='tablaR' align='center'>
           
                <tr>
                   <th colspan='#' >RÓTULOS</th>
                   <th scope='col' >TOTAL MEDIDAS</th>
                   <th scope='col' >CARAS</th>
                   <th scope='col' >TARIFA</th> 
                   <th scope='col' >EJERCICIO</th>
                </tr>";

                        
            foreach($calificacionRotulos as $dato)
            {
                $tabla .=  "<tr  align='center'>           
                <tr>     
                <td style='font-size:11px; text-align: center' colspan='#'>" . $dato->nombre . "</td>
                  <td>" . $dato->total_medidas . "</td>
                  <td>" . $dato->caras . "</td>
                  <td>$" .$dato->tarifa . "</td>
                  <td>2022</td>
                </tr>
            }
                  
                <tr>
                    <td colspan='#'></td>
                    <td></td>
                    <td scope='row'>IMPUESTO:</td>
                    <td scope='col'>MENSUAL</td>
                    <td scope='col'>ANUAL</td>
                </tr>

                <tr>             
                    <td colspan='#'align='center'></td>
                    <td></td>
                    <td> </td>                         
                    <td scope='col' align='center' style='font-size:13px;' >$<label id= 'tarifa_mensual'></label> <input type='hidden' id='tarifa_mensual'>$calificacion->monto</td>                         
                    <td align='center'></td>
                </tr>                
                    
                <tr>
                    <td rowspan='2'></td>
                    <td colspan='2'>Fondo Fiestas Patronales 5%</td>
                    <td align='center' style='font-size:13px;'>$ $calificacion->pago_mensual</td>
                    <td align='center'></td>
                </tr>

                <tr>
                    <td colspan='2'>TOTAL IMPUESTO</td>
                    <td align='center' style='font-size:13px;'>$<strong><label id= 'total_impuesto'></label> <input type='hidden'  id='total_impuesto'>$calificacion->pago_mensual</strong></td>
                    <td align='center'><strong></strong></td>
                </tr>
                  
                        
                <tr>
                    <td>
                    <h4>Nombre de Calificador:<br>Lic. Rosa Lisseth Aldana</h4>

                    <td><p style='text-align: justify; font-size: 6;'><b>Base Legal para el recurso de apelación respecto a esta
                             NOTIFICACION DE CALIFICACION.</b> Ley General Tributaria Municipal, Art. 123. 
                             -De la calificación de contribuyentes, de la determinación de tributos, 
                             de la resolución del Alcalde en el procedimiento de repetición del pago 
                             de lo no debido, y de la aplicación de sanciones hecha por la
                              administración tributaria municipal, se admitirá recurso de apelación 
                              para ante el Concejo Municipal respectivo, el cual deberá interponerse
                              ante el funcionario que haya hecho la calificación o pronunciada la 
                              resolución correspondiente, en el plazo de tres días después de su 
                              notificación.</p>
                        </td>
                        Fecha:&nbsp;$FechaDelDia&nbsp;       
                        </tr>
                      <tr>
                          <td colspan='2' id='uno'><b>Fecha:</b>&nbsp;$FechaDelDia&nbsp; </td>
                      </tr>
                
                </td>                
                </table>";
             
        
    
            // $stylesheet = file_get_contents('css/dataTables.bootstrap4.css');
            $stylesheet = file_get_contents('css/cssconsolidado.css');
            $mpdf->WriteHTML($stylesheet,1);
            $mpdf->SetMargins(0, 0, 10);
    
    
            //$mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
    
            $mpdf->WriteHTML($tabla,2);
            $mpdf->Output();

    }
}
}