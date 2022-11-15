<?php

namespace App\Http\Controllers\Backend\Reportes;

use App\Models\Contribuyentes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\alertas_detalle_rotulos;
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
use App\Models\CobrosRotulo;
use App\Models\NotificacionesHistoricoRotulos;
use App\Models\Traspasos;
use DateInterval;
use DatePeriod;
use Facade\Ignition\Http\Controllers\ScriptController;
use Illuminate\Support\MessageBag;
use Spatie\Permission\Models\Role;



class reportesRotulosDetalleController extends Controller
{
    public function generar_reporte_rotulos($id) {


        log::info('id_rotulos ' . $id);
      
        //Configuracion de Reporte en MPDF
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
        $mpdf->SetTitle('Alcaldía Metapán | Reporte de Buses');
    
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
        'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
        
        'contribuyente.id as id_contribuyente', 'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido','contribuyente.dui','contribuyente.nit',
        'contribuyente.email','contribuyente.telefono','contribuyente.direccion',
        'estado_rotulo.id','estado_rotulo.estado')

        ->find($id);

        $rotulos_especifico = RotulosDetalleEspecifico::latest()
        ->where('id_rotulos_detalle', $rotulos->id)
        ->first();

        $ultimaCalificacionRotulos = CalificacionRotuloDetalle::latest()
        ->where('id_contribuyente', $rotulos->id_contribuyente)
        ->first();
      
        $contribuyente=Contribuyentes::where('id',$rotulos->id_contribuyente)
        ->first();

        $tabla = "<header> <div class='row'> <div class='content'>
        <img id='logo' src='$logoalcaldia'>
        <img id='EscudoSV' src='$logoelsalvador'>
        <h4>DATOS GENERALES DE LA EMPRESA <br>
        ALCALDIA MUNICIPAL DE METAPÁN, SANTA ANA, EL SALVADOR C.A <br>
        UNIDAD DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL; TEL. 2402-7614
        </h4>
        <img id='lineaimg' src='$linea'>
        </div></div></header>";

        $tabla .= "<div id='content'>
        <table border='0' align='center' style='width: 600px;margin-top: 10px'>
       
        <tr>
            <td id='cero' align='left' colspan='2'><strong><p style='font-size:11.5'>I. DATOS GENERALES DE LA EMPRESA</p></strong></td></td>
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
            <td id='dos'>$rotulos->estado</td>
        </tr>

        <tr>
            <td id='uno'>CANTIDAD DE RÓTULOS</td>
            <td id='dos'>$rotulos->cantidad_rotulos</td>
        </tr>
     
        <tr>
            <td id='cero' align='left' colspan='2'><strong><p style='font-size:11.5'>II. CONTRIBUYENTE</p></strong></td></td>
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
            <td id='dos'>$rotulos->nit</td>
        </tr>

        <tr>
            <td id='uno'>TELÉFONO</td>
            <td id='dos'>$rotulos->telefono</td>
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
            <td id='cero' align='left' colspan='2'><strong><p style='font-size:11.5'>II. CALIFICACIÓN</p></strong></td></td>
        </tr> 

        <tr>
            <td id='uno'>FECHA DE CALIFICACIÓN</td>
            <td id='dos'>$ultimaCalificacionRotulos->fecha_calificacion</td>
        </tr>

        <tr>
            <td id='uno'>TARIFA ACTUAL</td>
            <td id='dos'>$ $ultimaCalificacionRotulos->pago_mensual</td>
        </tr>

        </table>
        </div>"; 
        
    
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
                <td align='left' colspan='2'><strong><p style='font-size:15'>I. DATOS GENERALES</p></strong></td>
            </tr>

            <tr>
                <td id='name'>NÚMERO DE FICHA:</td><br>
                <td id='name1'>$calificacion->nFicha</td><br>
            </tr>

            <tr>
                <td id='name'>FECHA DE APERTURA:</td><br>
                <td id='name1'>$rotulos->fecha_apertura</td><br>
            </tr>

            <tr>
                <td id='name'>NOMBRE DE LA EMPRESA:</td><br>
                <td id='name1'>$rotulos->nom_empresa</td><br>
            </tr>

            <tr>
                <td id='name'>REPRESENTANTE LEGAL:</td><br>
                <td id='name1' >$rotulos->contribuyente</td><br>
            </tr>
         
            <tr>
                <td id='name'>FECHA DE CALIFICACIÓN:</td><br>
                <td id='name1'>$calificacion->fecha_calificacion</td><br>
            </tr>
           
            <tr>
                <td align='left' colspan='2'><strong><p style='font-size:15'>II. BUSES</p></strong></td>
            </tr>
           
            </table>";
     
            $tabla .=  " <table id='tablaRotulo' align='center'>
           
                <tr>
                   <th scope='col' >RÓTULOS</th>
                   <th scope='col' >TOTAL MEDIDAS</th>
                   <th scope='col' >CARAS</th>
                   <th scope='col' >TARIFA</th> 
                   <th scope='col' >EJERCICIO</th>
                </tr>";

                        
            foreach($calificacionRotulos as $dato)
            {
                $tabla .=  "<tr>           
                <tr>     
                    <td>" . $dato->nombre . "</td>
                    <td>" . $dato->total_medidas . "</td>
                    <td>" . $dato->caras . "</td>
                    <td>$" .$dato->tarifa . "</td>
                  <td>2022</td>
                </tr>";
            }
                    
            $tabla .=  "<tr>   
                <tr>
                    <td></td>
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

                </table>";

                $tabla .=  " <table id='tablaRotulo' align='center'>
                        
                <tr>
                        <th colspan='2'><br>
                            <h4>Nombre de Calificador:<br>Lic. Rosa Lisseth Aldana</h4>
                        </th>
                    <td><p style='text-align: justify; font-size: 8;'><b>Base Legal para el recurso de apelación respecto a esta
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
 
                </tr>                
                </table>
                </div>";
             
        
    
            // $stylesheet = file_get_contents('css/dataTables.bootstrap4.css');
            $stylesheet = file_get_contents('css/cssconsolidado.css');
            $mpdf->WriteHTML($stylesheet,1);
            $mpdf->SetMargins(0, 0, 10);
    
    
            //$mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
    
            $mpdf->WriteHTML($tabla,2);
            $mpdf->Output();

    }

    public function resolucionAperturaRotulos($id)
    {

           //Configuracion de Reporte en MPDF
           $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
           $mpdf->SetTitle('Alcaldía Metapán | Resolución de Apertura');
   
           // mostrar errores
           $mpdf->showImageErrors = false;
   
           $logoalcaldia = 'images/logo.png';
           $logoelsalvador = 'images/EscudoSV.png';
           $LeyT = 'images/LeyT.png';


            $rotulos = RotulosDetalle::join('contribuyente', 'rotulos_detalle.id_contribuyente','contribuyente.id')
            ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo', 'estado_rotulo.id')

            ->select('rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
            'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
            'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
            
            'contribuyente.id as id_contribuyente', 'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
            'estado_rotulo.id','estado_rotulo.estado')

            ->find($id);

            $ultimaCalificacionRotulos = CalificacionRotuloDetalle::latest()
            ->where('id_contribuyente', $rotulos->id_contribuyente)
            ->first();

            $califiquese = $rotulos->nom_empresa;
      
               /** Obtener la fecha y días en español y formato tradicional*/
        $mesesEspañol = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $fechaF = Carbon::parse($ultimaCalificacionRotulos->created_at);
        $mes = $mesesEspañol[($fechaF->format('n')) - 1];
        $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

        $dias = array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
        $dia = $dias[(date('N', strtotime($fechaF))) - 1];
        /** FIN - Obtener la fecha y días en español y formato tradicional*/

        
        /** Obtener la fecha y días en español de inicio de operaciones*/
        $mesesEspañol = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $fechaF = Carbon::parse($rotulos->fecha_apertura);
        $mes = $mesesEspañol[($fechaF->format('n')) - 1];
        $inicio_apertura = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

        $dias = array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
        $dia_inicio_op = $dias[(date('N', strtotime($fechaF))) - 1];
        /** FIN - Obtener la fecha y días en español de inicio de operaciones*/


        $tabla = "<div class='content'>
                                <img id='logo' src='$logoalcaldia'>
                                <img id='EscudoSV' src='$logoelsalvador'>
                                <h4>ALCALDIA MUNICIPAL DE METAPÁN, SANTA ANA, EL SALVADOR C.A<br>
                                    UNIDAD DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL<br>
                                    RESOLUCIÓN
                                </h4>
                                <hr>
                        </div>";

        $tabla .= "<table border='0' align='center' style='width: 650px;font-size:11px;'>
                    <tr>
                        <td  align='left'> </td>

                        <td align='right'>
                            RESOLUCIÓN N°:&nbsp;<strong>$rotulos->num_resolucion</strong><br><br>
                        </td>
                    </tr>
                    <tr>
                        <td id='uno'>FECHA DE RESOLUCIÓN:</td>
                        <td id='dos'>$dia,&nbsp;$FechaDelDia</td>
                    </tr>
                    <tr>
                        <td id='uno'>NÚMERO DE CUENTA CORRIENTE:</td>
                        <td id='dos'>$rotulos->num_ficha</td>
                    </tr>
                    <tr>
                        <td id='uno'> CALIFIQUESE: </td>
                        <td id='dos'>$califiquese</td>
                    </tr>
                    <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td id='uno'>DIRECCIÓN:</td>
                        <td id='dos'>$rotulos->dir_empresa</td>
                    </tr>
                    <tr>
                        <td id='uno'>PROPIEDAD DE:</td>
                        <td id='dos'>$rotulos->contribuyente&nbsp;$rotulos->apellido</td>
                    </tr>
                    <tr>
                        <td id='uno'>REPRESENTADO POR:</td>
                        <td id='dos'></td>
                    </tr>
                    <tr>
                        <td id='uno'>GIRO ECONÓMICO:</td>
                        <td id='dos'>$ultimaCalificacionRotulos->giro_empresarial</td>
                    </tr>
                    <tr>
                        <td id='uno'>FECHA DE INICIO DE OPERACIONES:</td>
                        <td id='dos'>$dia_inicio_op&nbsp;$inicio_apertura</td>
                    </tr>
                    <tr>
                        <td colspan='2'  style='text-align: justify'>
                            <hr>
                                    <table border='0' align='center' style='width: 650px;'>
                                        <tr>
                                            <th scope='col' align='left'>DESCRIPCIÓN</th>
                                            <th scope='col'>&nbsp;</th>
                                            <th scope='col' align='left'>CARGO</th>
                                        </tr>

                                        <tr>
                                            <td>$califiquese </td>
                                            <td>&nbsp;</td>
                                            <td align='right'>$$ultimaCalificacionRotulos->monto </td>
                                        </tr>

                                        <tr>
                                            <td>Fondo Fiestas Patronales 5%</td>
                                            <td>&nbsp;</td>
                                            <td align='right'>$ $ultimaCalificacionRotulos->fondofp_mensual</td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td align='right'><strong><hr>TOTAL MENSUAL</strong></td>
                                            <td align='right'><hr><b>$ $ultimaCalificacionRotulos->pago_mensual </b></td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td align='right'><hr>TOTAL ANUAL</td>
                                            <td align='right'><hr>$ $ultimaCalificacionRotulos->total_impuesto_anual</td>
                                        </tr>

                                    </table>

                            <p style='font-size:10px;'>
                                <br>
                                <br>
                                LICDA. ROSA LISSETH ALDANA MERLOS<br>
                                JEFE DE ADMINISTRACIÓN TRIBUTARIA MUNICIPAL

                            </p>
                            <hr>
                            <p style='font-size:6;text-align: justify'>
                                <b>Ley General Tributaria Municipal:</b><br>
                                <b>Art. 123.</b> -De la calificación de contribuyentes, de la determinación de tributos, de la resolución del Alcalde en el procedimiento de repetición del pago de lo no
                                    debido, y de la aplicación de sanciones hecha por la administración tributaria municipal, se admitirá recurso de apelación para ante el Concejo Municipal
                                    respectivo, el cual deberá interponerse ante el funcionario que haya hecho la calificación o pronunciada la resolución correspondiente, en el plazo de tres días después de su notificación.
                                    <br>
                                    <br>

                                <b>Art. 90.</b>-Los contribuyentes, responsables y terceros, estarán obligados al cumplimiento de los deberes formales que se establezcan en esta Ley, en leyes u ordenanzas de creación de tributos municipales, sus reglamentos y otras disposiciones normativas que dicten las administraciones tributarias municipales, y particularmente están obligados a:
                                    <br>1º Inscribirse en los registros tributarios que establezcan dichas administraciones; proporcionarles los datos pertinentes y comunicarles oportunamente cualquier modificación al respecto;
                                    <br>2º Solicitar, por escrito, a la Municipalidad respectiva, las licencias o permisos previos que se requieran para instalar establecimientos y locales comerciales e informar a la autoridad tributaria la fecha de inicio de las actividades, dentro de los treinta días siguientes a dicha fecha;
                                    <br>3º Informar sobre los cambios de residencia y sobre cualquier otra circunstancia que modifique o pueda hacer desaparecer las obligaciones tributarias, dentro de los treinta días siguientes a la fecha de tales cambios;
                                    <br>4º Permitir y facilitar las inspecciones, exámenes, comprobaciones o investigaciones ordenadas por la administración tributaria municipal y que realizará por medio de sus funcionarios delegados a tal efecto; (4)
                                    <br>5º Presentar las declaraciones para la determinación de los tributos, con los anexos respectivos, cuando así se encuentre establecido, en los plazos y de acuerdo con las formalidades correspondientes;
                                    <br>6º Concurrir a las oficinas municipales cuando fuere citado por autoridad tributaria;
                                    <br>7º El contribuyente que ponga fin a su negocio o actividad, por cualquier causa, lo informará por escrito, a la autoridad tributaria municipal, dentro de los treinta días siguientes a la fecha de finalización de su negocio o actividad; presentará, al mismo tiempo, las declaraciones pertinentes, el balance o inventario final y efectuará el pago de los tributos adeudados sin perjuicio de que la autoridad tributaria pueda comprobar de oficio, en forma fehaciente, el cierre definitivo de cualquier establecimiento;
                                    <br>8º Las personas jurídicas no domiciliadas en el país y que desarrollen actividades económicas en determinadas comprensiones municipales, deberán acreditar un representante ante la administración tributaria, municipal correspondiente y comunicarlo oportunamente. Si no lo comunicaren, se tendrá como tal a los gerentes o administradores de los establecimientos propiedad de tales personas jurídicas;
                                    <br>9º A presentar o exhibir las declaraciones, balances, inventarios físicos, tanto los valuados como los registrados contablemente con los ajustes correspondientes si los hubiere, informes, documentos, activos, registros y demás informes relacionados con hechos generadores de los impuestos; (4)
                                    <br> 10º A permitir que se examine la contabilidad, registros y documentos, determinar la base imponible, liquidar el impuesto que le corresponda, cerciorarse de que no existe de acuerdo a la ley la obligación de pago del impuesto, o verificar el adecuado cumplimiento de las obligaciones establecidas en esta Ley General o en las leyes tributarias respectivas; (4)
                                    <br>11º En general, a dar las aclaraciones que le fueren solicitadas por aquélla, como también presentar o exhibir a requerimiento de la Administración Municipal dentro del plazo que para tal efecto le conceda, los libros o registros contables exigidos en esta Ley y a los demás que resulten obligados a llevar de conformidad a otras leyes especiales. (4)
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2'>
                        <br>
                                <img id='LeyT' src='$LeyT'>
                        </td>
                    </tr>
                </table>";

        $stylesheet = file_get_contents('css/cssconsolidado.css');
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetMargins(0, 0, 10);

        $mpdf->WriteHTML($tabla, 2);
        $mpdf->Output();
        
       
    }

    public function estado_cuentas_rotulos ($f1,$f2,$ti,$f3,$id_rotulos_detalle)
    {
        
                log::info([$f1,$f2,$ti,$f3,$id_rotulos_detalle,]);

                
                $f1_original=$f1;

                $rotulos = RotulosDetalle::join('contribuyente', 'rotulos_detalle.id_contribuyente','contribuyente.id')
                ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo', 'estado_rotulo.id')

                ->select('rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
                'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
                'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
                
                'contribuyente.id as id_contribuyente', 'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
                'estado_rotulo.id','estado_rotulo.estado')
            
                ->find($id_rotulos_detalle);
                
                $calificacionRotulos = CalificacionRotuloDetalle::
                    select('calificacion_rotulo_detalle.id', 'calificacion_rotulo_detalle.fecha_calificacion','calificacion_rotulo_detalle.estado_calificacion','calificacion_rotulo_detalle.id_rotulos_detalle')
                       
                    ->where('id_rotulos_detalle', $id_rotulos_detalle)
                    ->latest()
                    ->first();

                log::info($rotulos);
              
            $fechahoy=carbon::now()->format('d-m-Y');

            /** Obtener la fecha y días en español y formato tradicional*/
            $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $fechaF = Carbon::parse($fechahoy);
            $mes = $mesesEspañol[($fechaF->format('n')) - 1];
            $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

            $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
            $dia = $dias[(date('N', strtotime($fechaF))) - 1];
            /** FIN - Obtener la fecha y días en español y formato tradicional*/

            $año=carbon::now()->format('y');
        
            $f1_original=$f1;
            $fechaPagaraBuses = $f2;
            $id_rotulos_detalle = $id_rotulos_detalle;
            $tasa_interes=$ti;
            $Message=0;

            $MesNumero=Carbon::createFromDate($f1)->format('d');

            if($MesNumero<='15')
            {
                $f1=Carbon::parse($f1)->format('Y-m-01');
                $f1=Carbon::parse($f1);
                $InicioPeriodo=Carbon::createFromDate($f1_original);
                $InicioPeriodo= $InicioPeriodo->format('Y-m-d');
                //log::info('inicio de mes');
            }
            else
                {
                    $f1=Carbon::parse($f1)->addMonthsNoOverflow(1)->day(1);
                    $InicioPeriodo=Carbon::parse($f1_original)->format('Y-m-d');
                    log::info('f1_original: '.$f1_original);
                    log::info('InicioPeriodo: '.$InicioPeriodo);
                    log::info('fin de mes ');
                }


                $f2=Carbon::parse($f2);
                $f3=Carbon::parse($f3);
                $añoActual=Carbon::now()->format('Y');
            
                //** Inicia - Para determinar el intervalo de años a pagar */
                $monthInicio='01';
                $dayInicio='01';
                $monthFinal='12';
                $dayFinal='31';
                $AñoInicio=$f1->format('Y');
                $AñoFinal=$f2->format('Y');
                $FechaInicio=Carbon::createFromDate($AñoInicio, $monthInicio, $dayInicio);
                $FechaFinal=Carbon::createFromDate($AñoFinal, $monthFinal, $dayFinal);
                //** Finaliza - Para determinar el intervalo de años a pagar */
            
            
                //** INICIO - Para obtener SIEMPRE el último día del mes que selecciono el usuario */
                $PagoUltimoDiaMes=Carbon::parse($f2)->endOfMonth()->format('Y-m-d');
                //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */
                Log::info('Pago ultimo dia del mes---->' .$PagoUltimoDiaMes);
            
                //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
                $UltimoDiaMes=Carbon::parse($f1)->endOfMonth();
                Log::info('ultimo dia del mes---->' .$UltimoDiaMes);
                $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(30)->format('Y-m-d');
                Log::info($FechaDeInicioMoratorio);
                
                $FechaDeInicioMoratorio=Carbon::parse($FechaDeInicioMoratorio);
                $DiasinteresMoratorio=$FechaDeInicioMoratorio->diffInDays($f3);
                //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
                Log::info($DiasinteresMoratorio);
            

                $calificacionRotulos = CalificacionRotuloDetalle::
                    select('calificacion_rotulo_detalle.id', 'calificacion_rotulo_detalle.fecha_calificacion','calificacion_rotulo_detalle.estado_calificacion','calificacion_rotulo_detalle.id_rotulos_detalle')
                       
                    ->where('id_rotulos_detalle', $id_rotulos_detalle)
                    ->latest()
                    ->first();

            if($f1->lt($PagoUltimoDiaMes))
                    {

                        $intervalo = DateInterval::createFromDateString('1 Year');
                        $periodo = new DatePeriod ($FechaInicio, $intervalo, $FechaFinal);

                        $Cantidad_MesesTotal=0;
                        $impuestoTotal=0;
                        $impuestos_mora=0;
                        $impuesto_año_actual=0;
                        $multaPagoExtemporaneo=0;         
                        $totalMultaPagoExtemporaneo=0;

                
                        $tarifas = CalificacionRotuloDetalle::select('monto')
                        ->where('id_rotulos_detalle',$id_rotulos_detalle
                        )
                        ->get();

                        $tarifa_total=0;
                        foreach($tarifas as $dt)
                        {
                            $tarifa=$dt->monto;
                            $tarifa_total=$tarifa_total+$tarifa;

                        }
                        //** Inicia Foreach para cálculo de impuesto por años */
                        foreach ($periodo as $dt) {

                            $AñoPago =$dt->format('Y');
                        
                            $AñoSumado=Carbon::createFromDate($AñoPago, 12, 31);

                            log::info($tarifa_total);
                    
                                    if($AñoPago==$AñoFinal)//Stop para cambiar el resultado de la cantidad de meses en la última vuelta del foreach...
                                        {
                                            $CantidadMeses=ceil(($f1->floatDiffInRealMonths($PagoUltimoDiaMes)));
                                        }
                                    else
                                        {

                                            $CantidadMeses=ceil(($f1->floatDiffInRealMonths($AñoSumado)));  
                                            $f1=$f1->addYears(1)->month(1)->day(1);
            
                                        }

                            //*** calculo */
                
                            $impuestosValor=(round($tarifa_total*$CantidadMeses,2));
                            $impuestoTotal=$impuestoTotal+$impuestosValor;
                            $Cantidad_MesesTotal=$Cantidad_MesesTotal+$CantidadMeses;

                            if($AñoPago==$AñoFinal and $AñoPago<$añoActual)
                            {
                                    $impuestos_mora=$impuestos_mora+$impuestosValor;
                                    $impuesto_año_actual=$impuesto_año_actual;
                            }
                            else if( $AñoPago==$AñoFinal and $AñoPago==$añoActual)
                            {
                                    $impuestos_mora=$impuestos_mora;
                                    $impuesto_año_actual=$impuesto_año_actual+$impuestosValor;
                            }else{
                                    $impuestos_mora=$impuestos_mora+$impuestosValor;
                                    $impuesto_año_actual=$impuesto_año_actual;
                            }

                            $linea="_____________________<<::>>";
                            $divisiondefila=".....................";
            
            

                            Log::info($AñoPago);
                            Log::info($CantidadMeses);
                        
                            Log::info($impuestosValor);
                            Log::info($impuestos_mora);
                            Log::info('año actual '. $impuesto_año_actual);                    
                            Log::info($AñoSumado);                    
                            Log::info($f2);
                            Log::info($divisiondefila);             
                            Log::info($linea);

                        }   //** Termina el foreach */

                        //** -------Inicia - Cálculo para intereses--------- */

                        $TasaInteresDiaria=($tasa_interes/365);
                        $InteresTotal=0;
                        $MesDeInteres=Carbon::parse($FechaDeInicioMoratorio)->subDays(30);
                        $contador=0;
                        $fechaFinMeses=$f2->addMonthsNoOverflow(1);
                        $intervalo2 = DateInterval::createFromDateString('1 Month');
                        $periodo2 = new DatePeriod ($MesDeInteres, $intervalo2, $fechaFinMeses);
                                
                        //** Inicia Foreach para cálculo por meses */
                        foreach ($periodo2 as $dt) 
                        {
                        $contador=$contador+1;
                        $divisiondefila=".....................";

                                $Date1=Carbon::parse($MesDeInteres)->day(1);
                                $Date2=Carbon::parse($MesDeInteres)->endOfMonth();
                                
                                $MesDeInteresDiainicial=Carbon::parse($Date1)->format('Y-m-d'); 
                                $MesDeInteresDiaFinal=Carbon::parse($Date2)->format('Y-m-d'); 
                                
                    
                            $Fecha30Sumada=Carbon::parse($MesDeInteresDiaFinal)->addDays(30); 
                            Log::info($Fecha30Sumada);
                            Log::info($f3);
                            if($f3>$Fecha30Sumada){
                            $CantidaDiasMesInteres=ceil($Fecha30Sumada->diffInDays($f3));//**le tenia floatdiffInDays y funcinona bien  */
                            }else
                            {
                                $CantidaDiasMesInteres=ceil($Fecha30Sumada->diffInDays($f3));
                                $CantidaDiasMesInteres=-$CantidaDiasMesInteres;
                                
                            }
                            Log::info($CantidaDiasMesInteres);

                        
                        $MesDeInteres->addMonthsNoOverflow(1)->format('Y-M');


                    //** INICIO- Determinar Interes. */
                    if($CantidaDiasMesInteres>0){                                                   
                        
                            $stop="Avanza:interes";    

                            //** INICIO-  Cálculando el interes. */
                            $Interes=round((($TasaInteresDiaria*$CantidaDiasMesInteres)/100*$tarifa_total),2);
                            $InteresTotal=$InteresTotal+$Interes;
                            //** FIN-  Cálculando el interes. */

                        }
                        else
                            { 
                                $Interes=0;
                                $InteresTotal=$InteresTotal;
                                $multaPagoExtemporaneo=$multaPagoExtemporaneo;
                                $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo;
                                $stop="Alto: Sin interes";
                            }
                    //** FIN-  Determinar multa por pago extemporaneo. */

                    
                    
                            Log::info($contador);
                            Log::info('Mes multa '.$MesDeInteres);
                            Log::info($stop);
                            Log::info($MesDeInteresDiainicial);                   
                            Log::info($MesDeInteresDiaFinal);                 
                            Log::info($multaPagoExtemporaneo);
                            Log::info($totalMultaPagoExtemporaneo);
                            Log::info($Interes);
                            Log::info($InteresTotal);
                            Log::info($divisiondefila);
                        }                 
                        
                        
                        $fondoFPValor=round($impuestoTotal*0.05,2);
                        $totalPagoValor= round($fondoFPValor+$impuestoTotal+$InteresTotal,2);

                        //Le agregamos su signo de dollar para la vista al usuario
                        $fondoFP= "$". $fondoFPValor;     
                        $totalPago="$".$totalPagoValor;
                        $impuestos_mora_Dollar="$".$impuestos_mora;
                        $impuesto_año_actual_Dollar="$".$impuesto_año_actual; 
                        $InteresTotalDollar="$".$InteresTotal;
                    

                    
            
            //Configuracion de Reporte en MPDF
            $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
            $mpdf->SetTitle('Alcaldía Metapán | Resolución de Apertura');

                    
            // mostrar errores
            $mpdf->showImageErrors = false;

            $logoalcaldia = 'images/logo.png';
            $logoelsalvador = 'images/EscudoSV.png';
            $imgf1 = 'images/imgf1.png';
            
            
            $tabla = "<div class='content' align='center'>
                            <img id='logo' src='$logoalcaldia'>
                            <img id='EscudoSV' src='$logoelsalvador'>
                            <h4 id='texto'>ALCALDIA MUNICIPAL DE METAPAN<br>
                                SANTA ANA, EL SALVADOR C.A</h4>
                            <hr>
                           
                    </div>";

            $tabla .= "<table border='0' align='center' style='width: 650px;'>
            <tr>
            <td colspan='2' align='center'><strong><u>ESTADO DE CUENTA</u></strong></td>
            </tr>

            <tr>           
            <td align='right' colspan='2'><br><br>
                <strong>Metapán, $FechaDelDia</strong><br><br>
            </td>
            </tr>
            <tr>
            <td colspan='2' style='font-size: 13;'>
                <p>Señor (a):&nbsp;$rotulos->contribuyente&nbsp;$rotulos->apellido<br>
                    Dirección:&nbsp;$rotulos->dire_empresa<br>
                    Cuenta Corriente N°:&nbsp;$rotulos->num_ficha<br>
                    Empresa o Negocio:&nbsp; $rotulos->nom_empresa<br>
                </p>
                <br><br>
                Estimado(a) señor (a):
                <p style='text-indent: 20px;'>En nombre del Concejo Municipal, reciba un afectuoso saludo y deseos de éxito. El
                    motivo de la presente es para manifestarle que su estado de cuenta en esta
                    Municipalidad es el siguiente:</p>
                <p>
                <br>
                    <strong>Tasas Municipales</strong><br><br><br>
                   
                </p>
                </td>
                <tr>
                    <td><hr></td>
                    <td><hr></td>
                </tr>
                <tr>
                    <th scope='col'>Periodo: &nbsp;&nbsp;desde&nbsp; $InicioPeriodo&nbsp;</th>
                    <th scope='col'>&nbsp;&nbsp;hasta&nbsp; $PagoUltimoDiaMes&nbsp;</th>    
                </tr>
                <tr>
                    <td align='right'>TASAS POR SERVICIO</td>
                    <td align='center'>$impuesto_año_actual_Dollar</td>
                </tr>
                <tr>
                    <td align='right'>TASAS POR SERVICIO MORA</td>
                    <td align='center'>$impuestos_mora_Dollar</td>
                </tr>
                <tr>
                    <td align='right'>INTERESES MORATORIOS</td>
                    <td align='center'>$InteresTotalDollar</td>
                </tr>        
                <tr>
                    <td align='right'>FONDO F. PATRONALES 5%</td>
                    <td align='center'>$fondoFP</td>
                </tr>       
                <tr>
                    <th scope='row'>TOTAL ADEUDADO</th>
                    <th align='center'>$totalPago</th>
                </tr>
            
                <tr>
                    <td><hr></td><br><br>
                    <td><hr></td><br><br>
                </tr>

                <tr>
                    <td>
                    Validez: <strong><u>$FechaDelDia</u></strong><br><br><br>
                    </td>
                </tr> 

                <tr>
                    <td colspan='2' style='text-indent: 20px;font-family: Arial; text-align: justify;font-size: 13;'>
                        <p>
                            Agradeciendo su comprension y atención a esta notificación me suscribo de usted, muy cordialmente.<br><br><br><br><br><br>
                        </p>
                    </td>
                </tr>

                <tr>
                    <td colspan='2' style='text-indent: 20px;font-family: Arial; text-align: center;font-size: 13;'>
                       <h4> Lic. Rosa Lisseth Aldana<br>
                       Unidad de Administración Tributaria Municipal. 
                       
                    </td>
                </tr>
                <br>
                <br>                
                <br>
                <br>
                <hr>
                </table>";
        
                $tabla .= "<div class='content' align='center'>
                
                <h4 id='texto'>Avenida Benjamín Estrada Valiente y Calle Poniente, Barrio San Pedro, Metapán. <br>
                        Tel.:2402-7615 - 2402-7601 - Fax: 2402-7616 <br>
                        www.alcaldiademetapan.org
          
               
                </div>";

                
            $stylesheet = file_get_contents('css/cssconsolidado.css');
            $mpdf->WriteHTML($stylesheet,1);
            $mpdf->SetMargins(0, 0, 5);


            //$mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
           
            $mpdf->WriteHTML($tabla,2);
            $mpdf->Output();

                    }

    }

    public function aviso_rotulos($id_rotulos_detalle)
    {
       
        //Configuracion de Reporte en MPDF
        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
        $mpdf->SetTitle('Alcaldía Metapán | Resolución de Apertura');

                 
        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/logo.png';
        $logoelsalvador = 'images/EscudoSV.png';
        $imgf1 = 'images/imgf1.png';
         

        $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaF = Carbon::parse(Carbon::now());
        $mes = $mesesEspañol[($fechaF->format('n')) - 1];
        $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');
       
      

        $rotulos = RotulosDetalle::join('contribuyente', 'rotulos_detalle.id_contribuyente','contribuyente.id')
        ->join('estado_rotulo', 'rotulos_detalle.id_estado_rotulo', 'estado_rotulo.id')

        ->select('rotulos_detalle.id as id_rotulos_detalle','rotulos_detalle.num_ficha','rotulos_detalle.fecha_apertura','rotulos_detalle.cantidad_rotulos',
        'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa','rotulos_detalle.tel_empresa',
        'rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante','rotulos_detalle.estado_especificacion',
        
        'contribuyente.id as id_contribuyente', 'contribuyente.nombre as contribuyente', 'contribuyente.apellido as apellido',
        'estado_rotulo.id','estado_rotulo.estado')
    
        ->find($id_rotulos_detalle);
        
      
        $cantidad = 0;

        $alerta_aviso_rotulos = alertas_detalle_rotulos::where('id_contribuyente', $rotulos->id_contribuyente)
        ->where('id_alerta','1')
        ->pluck('cantidad')
        ->first();

        
      
        //** Guardando en el historico de avisos */
        $dato = new NotificacionesHistoricoRotulos();
        $dato->id_contribuyente = $rotulos->id_contribuyente;
        $dato->id_alertas = '1';
        $created_at=new Carbon();
        $dato->created_at=$created_at->setTimezone('America/El_Salvador');
        $dato->save();


        if($alerta_aviso_rotulos === null)
        {

            $cantidad_avisos = $cantidad + 1;

            $registro = new alertas_detalle_rotulos();
            $registro->id_contribuyente = $rotulos->id_contribuyente;
            $registro->id_alerta ='1';
            $registro->cantidad = $cantidad_avisos;
            $registro->save();
           
        }else if($alerta_aviso_rotulos == 0)
        {

            $cantidad = $alerta_aviso_rotulos + 1;

            alertas_detalle_rotulos::where('id_contribuyente', $rotulos->id_contribuyente)
            ->where('id_alerta','1')
            ->update([
                        'cantidad' => $cantidad,
                    ]);

        }else if($alerta_aviso_rotulos >= 2){

            $cantidad=0;

            alertas_detalle_rotulos::where('id_contribuyente',$rotulos->id_contribuyente)
            ->where('id_alerta','1')
            ->update([
                        'cantidad' => $cantidad,
                    ]);
            }
                else{
                    $cantidad = $alerta_aviso_rotulos + 1;

                    alertas_detalle_rotulos::where('id_contribuyente',$rotulos->id_contribuyente)
                    ->where('id_alerta','1')
                    ->update([
                                'cantidad' => $cantidad,
                            ]);
                }
              

                //CREANDO PDF
              
                $tabla = "<div class='content'>
                               <img id='logo' src='$logoalcaldia'>
                               <img id='EscudoSV' src='$logoelsalvador'>
                               <h4>ALCALDIA MUNICIPAL DE METAPAN<br>
                               UNIDAD DE ADMINISTRACION TRIBUTARIA MUNICIPAL<br>
                               DEPARTAMENTO DE SANTA ANA, EL SALVADOR C.A</h4>
                               <hr>
                       </div>";

                $tabla .= "<table border='0' align='center' style='width: 650px;'>
                       <tr>
                       <td align='right' colspan='2'>
                            <strong><u> EXP.</u></strong> &nbsp; <strong><u> 1606 </u></strong><br>
                          <h5> <strong>Fecha,&nbsp; $FechaDelDia</strong></h3>
                       </td>
                       </tr>
                       <br>

                       <tr>
                       <td colspan='2' style='font-size: 13;'>
                           <h3><p><strong>Sr/a. &nbsp;&nbsp;$rotulos->contribuyente&nbsp;$rotulos->apellido</strong><br>
                            <strong>Presente</strong><br>
                            <strong>Cantidad de rótulos: &nbsp;$rotulos->cantidad_rotulos &nbsp;<strong>
                           </p>

                           &nbsp;
                           &nbsp;
                           &nbsp;

                           <tr>
                           <td colspan='2' align='center'><strong><u>A V I S O</u></strong></td>
                           </tr>
                    </td>

                    <br>
                    <br>
                    <br>

                    <tr>                    
                    <td colspan='2'  style='text-indent: 20px;font-family: Arial; text-align: justify;font-size: 13;'>
                        <p>
                        Aprovecho la oportunidad para saludarle y a la vez informarle que la falta de pago de los tributos
                        municipales en el plazo o fecha límite correspondiente, coloca al sujeto pasivo en situación de mora, 
                        sin necesidad de requerimiento de parte de la administración tributaria municipal y sin tomar en
                        consideración, las causas o motivos de esa falta de pago. Art. 45 (Ley General Tributaria).  
                        <br><br>

                        </p>
                        <br>
                        <br>
                        <br>
                    </td>
                    
                    
                    </tr>

               
                    <tr>
                    <td colspan='2' style='font-size: 13;'>
                           <p>Nombre del Negocio o Empresa en Mora:<strong> &nbsp;&nbsp;$rotulos->nom_empresa </strong><br>
                           <br>
                            Direccion: <strong> &nbsp;$rotulos->dire_empresa &nbsp; </strong>
                           </p>
                         
                    </td>
                    </tr>

                    <br>
                    <br>
                    <br>

                    <tr>                    
                    <td colspan='2'  style='text-indent: 20px;font-family: Arial; text-align: justify;font-size: 13;'>
                        <p>

                    La mora del sujeto pasivo producirá, entre otros, los siguientes efectos: 1º Hace exigible la deuda
                    tributaria, 2º Da lugar al devengo de intereses moratorios, 3º Da lugar a la aplicación de multas, por
                    configurar dicha mora, una infracción tributaria. Los intereses moratorios se aplicarán desde el
                    vencimiento de plazo en que debió pagarse el tributo hasta el día de la extinción total de la obligación
                    tributaria. Art. 46 (Ley General Tributaria), Por tanto, es necesario que se acerque al Departamento
                    de Catastro Tributario de esta Municipalidad a la mayor brevedad posible, para cancelar la deuda o
                    solicitar de manera escrita un plan de pago. 

                        <br><br><br>

                    Agradecemos de antemano la atención prestada a esta nota, y esperamos la disposición necesaria
                    para solventar su situación. 

                        <br><br>
                    
                    Atentamente.

                        </p>

                            <br>
                            <br>
                            <br>
                    </td>
                    <tr>

                    <td colspan='2'  style='text-indent: 20px;font-family: Arial; text-align: center;font-size: 16;'>
                    <p>

                    Sr. José Roberto Solito<br>
                    Delegado de cobro
                    </td>

                    </tr>
            
                    </table>";

                       $stylesheet = file_get_contents('css/cssconsolidado.css');
                       $mpdf->WriteHTML($stylesheet,1);
                       $mpdf->SetMargins(0, 0, 5);
           
           
                      //$mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
           
                       $mpdf->WriteHTML($tabla,2);
                       $mpdf->Output();
            //TERMINA CREANDO PDF


                                  
    }

    /* Reportes historial de cobros mpdf */
    function pdfReporteRotulosCobros($id_rotulos_detalle) {
        $ListarCobros = CobrosRotulo::where('id_rotulos_detalle', $id_rotulos_detalle)
        ->get();

        $rotulos_detalle = RotulosDetalle
        ::select('rotulos_detalle.nom_empresa')
        ->where('rotulos_detalle.id', $id_rotulos_detalle)
        ->get();

        $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
        $mpdf->SetTitle('Alcaldía Metapán | Historial de cobros');

        $logoalcaldia = 'images/logo.png';
        $logoelsalvador = 'images/EscudoSV.png';

        $tabla = "<div class='content'>
                    <img id='logo' src='$logoalcaldia'>
                    <img id='EscudoSV' src='$logoelsalvador'>
                    <h4>ALCALDIA MUNICIPAL DE METAPAN<br>
                    UNIDAD DE ADMINISTRACION TRIBUTARIA MUNICIPAL<br>
                    DEPARTAMENTO DE SANTA ANA, EL SALVADOR C.A</h4>
                    <hr>
                </div>";

        $tabla .= "<p>Reporte e historial de cobros:<strong> " . $rotulos_detalle[0]['nom_empresa'] . "</strong></p>";
        $tabla .= "<table id='tablaMora' style='width:100%;border-collapse: collapse;border: none;'>
                    <tbody>
                        <tr>
                            <th style='width: 20%;text-align: center'>Fecha Pago</th>
                            <th style='width: 10%;text-align: center'>Meses</th>
                            <th style='width: 20%;text-align: center'>Impuestos Mora</th>
                            <th style='width: 15%;text-align: center'>Impuestos</th>
                            <th style='width: 15%;text-align: center'>Interes</th>
                            <th style='width: 15%;text-align: center'>Total</th>
                        </tr>";

        if (count($ListarCobros) > 0) {
            foreach ($ListarCobros as $dato) {
                $tabla .= "<tr>
                                <td align='center'>" . $dato->fecha_cobro . "</td>
                                <td align='center'>" . $dato->cantidad_meses_cobro . "</td>
                                <td align='center'>" . $dato->tasa_servicio_mora_32201 . "</td>
                                <td align='center'>" . $dato->impuestos . "</td>
                                <td align='center'>" . $dato->intereses_moratorios_15302 . "</td>
                                <td align='center'>" . $dato->pago_total . "</td>
                            </tr>";
            }
        } else {
            $tabla .= "<tr>
                            <td align='center' colspan='6'>no se encontraron datos disponibles</td>
                        </tr>";
        }

        $tabla .= "</tbody></table>";

        $stylesheet = file_get_contents('css/cssconsolidado.css');
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetMargins(0, 0, 5);

        $mpdf->SetFooter("Pagina: " . '{PAGENO}' . "/" . '{nb}');

        $mpdf->WriteHTML($tabla, 2);
        $mpdf->Output();
    }
    
    /* Reprote notificaciopn rotulos mpdf */
    function reporte_notificacion_rotulos($f1, $f2, $ti, $id, $f3) {
        log::info([$f1,$f2,$ti,$f3,$id]);

        $f1_original = $f3;

        $rotulos = RotulosDetalle
        ::join('contribuyente','rotulos_detalle.id_contribuyente','=','contribuyente.id')
        ->join('estado_rotulo','rotulos_detalle.id_estado_rotulo','=','estado_rotulo.id')

        ->select('rotulos_detalle.id','rotulos_detalle.fecha_apertura','rotulos_detalle.num_ficha',
                'rotulos_detalle.cantidad_rotulos','rotulos_detalle.estado_especificacion',
                'rotulos_detalle.nom_empresa','rotulos_detalle.dire_empresa','rotulos_detalle.nit_empresa',
                'rotulos_detalle.tel_empresa','rotulos_detalle.email_empresa','rotulos_detalle.reg_comerciante',
                'contribuyente.nombre as contribuyente','contribuyente.apellido','contribuyente.id as id_contribuyente',
                'estado_rotulo.estado')
        
        ->find($id);

        $calificacionRotulos = CalificacionRotuloDetalle::latest()
        ->where('id_contribuyente', $rotulos->id_contribuyente)
        ->first();

        log::info($rotulos);

        $fechahoy = Carbon::now()->format('d-m-Y');

        /** Obtener la fecha y dias en español y formato tradicional */
        $mesesEspañol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaF = Carbon::parse($fechahoy);
        $mes = $mesesEspañol[($fechaF->format('n')) - 1];
        $FechaDelDia = $fechaF->format('d') . ' de ' . $mes . ' de ' . $fechaF->format('Y');

        $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $dia = $dias[(date('N', strtotime($fechaF))) - 1];
        /** FIN - Obtener la fecha y dias en español y formato tradicional */

        $año=carbon::now()->format('y');

        $f1_original = $f1;
        $fechaPagaraRotulos = $f2;
        $id_rotulos_detalle = $id;
        $tasa_interes = $ti;
        $Message = 0;

        $MesNumero = Carbon::createFromDate($f1)->format('d');

        if ($MesNumero <= '15') {
            $f1 = Carbon::parse($f1)->format('Y-m-01');
            $f1 = Carbon::parse($f1);
            $InicioPeriodo = Carbon::createFromDate($f1_original);
            $InicioPeriodo = $InicioPeriodo->format('Y-m-d');
        } else {
            $f1=Carbon::parse($f1)->addMonthsNoOverflow(1)->day(1);
            $InicioPeriodo=Carbon::parse($f1_original)->format('Y-m-d');
            log::info('f1_original: '.$f1_original);
            log::info('InicioPeriodo: '.$InicioPeriodo);
            log::info('fin de mes ');
        }

        $f2 = Carbon::parse($f2);
        $f3 = Carbon::parse($f3);
        $añoActual = Carbon::now()->format('Y');

        //** Inicia - Para determinar el intervalo de años a pagar */
        $monthInicio = '01';
        $dayInicio = '01';
        $monthFinal = '12';
        $dayFinal = '31';
        $AñoInicio = $f1->format('Y');
        $AñoFinal = $f2->format('Y');
        $FechaInicio = Carbon::createFromDate($AñoInicio, $monthInicio, $dayInicio);
        $FechaFinal = Carbon::createFromDate($AñoFinal, $monthFinal, $dayFinal);
        //** Finaliza - Para determinar el intervalo de años a pagar */

        //** INICIO - Para obtener SIEMPRE el último día del mes que selecciono el usuario */
        $PagoUltimoDiaMes = Carbon::parse($f2)->endOfMonth()->format('Y-m-d');
        //** FIN - Para obtener SIEMPRE el último día del mes que selecioino el usuario */
        Log::info('Pago ultimo dia del mes---->' .$PagoUltimoDiaMes);
    
        //** INICIO- Determinar la cantidad de dias despues del primer pago y dias en interes moratorio. */
        $UltimoDiaMes = Carbon::parse($f1)->endOfMonth();
        Log::info('ultimo dia del mes---->' .$UltimoDiaMes);
        $FechaDeInicioMoratorio=$UltimoDiaMes->addDays(30)->format('Y-m-d');
        Log::info($FechaDeInicioMoratorio);
        
        $FechaDeInicioMoratorio = Carbon::parse($FechaDeInicioMoratorio);
        $DiasinteresMoratorio = $FechaDeInicioMoratorio->diffInDays($f3);
        //** FIN-  Determinar la cantidad de dias despues del primer pago y dias en interes moratorio.. */
        Log::info($DiasinteresMoratorio);

        $calificaciones = CalificacionRotuloDetalle
        ::select('calificacion_rotulo_detalle.id','calificacion_rotulo_detalle.cantidad_rotulos','calificacion_rotulo_detalle.monto',
                'calificacion_rotulo_detalle.pago_mensual','calificacion_rotulo_detalle.fecha_calificacion','calificacion_rotulo_detalle.estado_calificacion')
        
        ->where('id_rotulos_detalle', $id)
        ->latest()
        ->first();
        

        if ($f1->lt($PagoUltimoDiaMes)) {
            $intervalo = DateInterval::createFromDateString('1 Year');
            $periodo = new DatePeriod($FechaInicio, $intervalo, $FechaFinal);

            $Cantidad_MesesTotal = 0;
            $impuestoTotal = 0;
            $impuestos_mora = 0;
            $impuesto_año_actual = 0;
            $multaPagoExtemporaneo = 0;
            $totalMultaPagoExtemporaneo = 0;

            $tarifas = CalificacionRotuloDetalle::select('monto')
            ->where('id_rotulos_detalle', $id)
            ->get();

            $tarifa_total = 0;
            foreach ($tarifas as $dt) {
                $tarifa = $dt->monto;
                $tarifa_total = $tarifa_total + $tarifa;
            }

            //** Inicia Foreach para cálculo de impuesto por años */
            foreach ($periodo as $dt) {
                $AñoPago = $dt->format('Y');
                $AñoSumado = Carbon::createFromDate($AñoPago, 12, 31);

                log::info($tarifa_total);

                //Stop para cambiar el resultado de la cantidad de meses en la última vuelta del foreach...
                if ($AñoPago == $AñoFinal) {
                    $CantidadMeses = ceil(($f1->floatDiffInRealMonths($PagoUltimoDiaMes)));
                } else {
                    $CantidadMeses = ceil(($f1->floatDiffInRealMonths($AñoSumado)));  
                    $f1 = $f1->addYears(1)->month(1)->day(1);
                }

                //*** calculo */
                $impuestosValor = (round($tarifa_total * $CantidadMeses, 2));
                $impuestoTotal = $impuestoTotal + $impuestosValor;
                $Cantidad_MesesTotal = $Cantidad_MesesTotal + $CantidadMeses;

                if ($AñoPago == $AñoFinal and $AñoPago < $añoActual) {
                    $impuestos_mora = $impuestos_mora + $impuestosValor;
                    $impuesto_año_actual = $impuesto_año_actual;
                } else if ($AñoPago == $AñoFinal and $AñoPago == $añoActual) {
                    $impuestos_mora = $impuestos_mora;
                    $impuesto_año_actual = $impuesto_año_actual + $impuestosValor;
                } else {
                    $impuestos_mora = $impuestos_mora + $impuestosValor;
                    $impuesto_año_actual = $impuesto_año_actual;
                }

                $linea = "_____________________<<::>>";
                $divisiondefila = ".....................";

                Log::info($AñoPago);
                Log::info($CantidadMeses);

                Log::info($impuestosValor);
                Log::info($impuestos_mora);
                Log::info('año actual '. $impuesto_año_actual);                    
                Log::info($AñoSumado);                    
                Log::info($f2);
                Log::info($divisiondefila);             
                Log::info($linea);
                
            } //** Termina el foreach */

            //** -------Inicia - Cálculo para intereses--------- */

            $TasaInteresDiaria = ($tasa_interes / 365);
            $InteresTotal = 0;
            $MesDeInteres = Carbon::parse($FechaDeInicioMoratorio)->subDays(30);
            $contador = 0;
            $fechaFinMeses = $f2->addMonthsNoOverflow(1);
            $intervalo2 = DateInterval::createFromDateString('1 Month');
            $periodo2 = new DatePeriod ($MesDeInteres, $intervalo2, $fechaFinMeses);

            //** -------Inicia - Cálculo para intereses--------- */
            foreach ($periodo2 as $dt) {
                $contador = $contador+1;
                $divisiondefila = ".....................";

                $Date1 = Carbon::parse($MesDeInteres)->day(1);
                $Date2 = Carbon::parse($MesDeInteres)->endOfMonth();
                
                $MesDeInteresDiainicial = Carbon::parse($Date1)->format('Y-m-d'); 
                $MesDeInteresDiaFinal = Carbon::parse($Date2)->format('Y-m-d'); 

                $Fecha30Sumada=Carbon::parse($MesDeInteresDiaFinal)->addDays(30); 
                Log::info($Fecha30Sumada);
                Log::info($f3);

                if ($f3 > $Fecha30Sumada) {
                    $CantidaDiasMesInteres=ceil($Fecha30Sumada->diffInDays($f3));
                } else {
                    $CantidaDiasMesInteres=ceil($Fecha30Sumada->diffInDays($f3));
                    $CantidaDiasMesInteres=-$CantidaDiasMesInteres;
                }

                Log::info($CantidaDiasMesInteres);

                $MesDeInteres->addMonthsNoOverflow(1)->format('Y-M');

                //** INICIO- Determinar Interes. */
                if ($CantidaDiasMesInteres > 0) {
                    $stop = "Avanza:interes";    

                    //** INICIO-  Cálculando el interes. */
                    $Interes = round((($TasaInteresDiaria * $CantidaDiasMesInteres) / 100 * $tarifa_total), 2);
                    $InteresTotal = $InteresTotal + $Interes;
                    //** FIN-  Cálculando el interes. */
                } else {
                    $Interes=0;
                    $InteresTotal=$InteresTotal;
                    $multaPagoExtemporaneo=$multaPagoExtemporaneo;
                    $totalMultaPagoExtemporaneo=$totalMultaPagoExtemporaneo;
                    $stop="Alto: Sin interes";
                }
                //** FIN-  Determinar multa por pago extemporaneo. */

                Log::info($contador);
                Log::info('Mes multa ' . $MesDeInteres);
                Log::info($stop);
                Log::info($MesDeInteresDiainicial);                   
                Log::info($MesDeInteresDiaFinal);                 
                Log::info($multaPagoExtemporaneo);
                Log::info($totalMultaPagoExtemporaneo);
                Log::info($Interes);
                Log::info($InteresTotal);
                Log::info($divisiondefila);
            }

            $fondoFPValor = round($impuestoTotal * 0.05,2);
            $totalPagoValor= round($fondoFPValor + $impuestoTotal + $InteresTotal, 2);

            //Le agregamos su signo de dollar para la vista al usuario
            $fondoFP = "$" . $fondoFPValor;     
            $totalPago = "$" . $totalPagoValor;
            $impuestos_mora_Dollar = "$" . $impuestos_mora;
            $impuesto_año_actual_Dollar = "$" . $impuesto_año_actual; 
            $InteresTotalDollar = "$" . $InteresTotal;

            $cantidad = 0;
            $alerta_aviso_rotulos = alertas_detalle_rotulos::where('id_contribuyente', $rotulos->id_contribuyente)
            ->where('id_alerta', '2')
            ->pluck('cantidad')
            ->first();

            //** Guardando en el historico de avisos */
            $dato = new NotificacionesHistoricoRotulos();
            $dato->id_contribuyente = $rotulos->id_contribuyente;
            $dato->id_alertas = '2';
            $created_at = new Carbon();
            $dato->created_at = $created_at->setTimezone('America/El_Salvador');
            $dato->save();

            if ($alerta_aviso_rotulos === null) {
                $cantidad_avisos = $cantidad + 1;

                $registro = new alertas_detalle_rotulos();
                $registro->id_contribuyente = $rotulos->id_contribuyente;
                $registro->id_alerta ='2';
                $registro->cantidad = $cantidad_avisos;
                $registro->save();
            } else if ($alerta_aviso_rotulos == 0) {
                $cantidad = $alerta_aviso_rotulos + 1;

                alertas_detalle_rotulos::where('id_contribuyente', $rotulos->id_contribuyente)
                ->where('id_alerta', '2')
                ->update(['cantidad' => $cantidad,]);
            } else if ($alerta_aviso_rotulos >= 2) {
                $cantidad=0;

                alertas_detalle_rotulos::where('id_contribuyente', $rotulos->id_contribuyente)
                ->where('id_alerta','2')
                ->update(['cantidad' => $cantidad,]);
            } else {
                $cantidad = $alerta_aviso_rotulos + 1;

                alertas_detalle_rotulos::where('id_contribuyente', $rotulos->id_contribuyente)
                ->where('id_alerta','2')
                ->update(['cantidad' => $cantidad,]);
            }

            //Configuracion de Reporte en MPDF
            $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir(), 'format' => 'LETTER']);
            $mpdf->SetTitle('Alcaldía Metapán | Notificación');

            // mostrar errores
            $mpdf->showImageErrors = false;

            $logoalcaldia = 'images/logo.png';
            $logoelsalvador = 'images/EscudoSV.png';
            $imgf1 = 'images/imgf1.png';

            $tabla = "<div class='content'>
                        <img id='logo' src='$logoalcaldia'>
                        <img id='EscudoSV' src='$logoelsalvador'>
                        <h4>ALCALDIA MUNICIPAL DE METAPAN<br>
                        UNIDAD DE ADMINISTRACION TRIBUTARIA MUNICIPAL<br>
                        DEPARTAMENTO DE SANTA ANA, EL SALVADOR C.A</h4>
                        <hr>
                    </div>";
           
            $tabla .= "<table border='0' align='center' style='width: 650px;'>
                        <tr>
                            <td colspan='2' align='center'><strong><u>N O T I F I C A C I O N</u></strong></td>
                        </tr>
                        <tr>
                            <td colspan='2' style='font-size:13;'>
                                <p>Señor (a):&nbsp;$rotulos->contribuyente&nbsp;$rotulos->apellido<br>
                                    Direccion:&nbsp;$rotulos->dire_empresa<br>
                                    Cuenta Corriente N°:&nbsp;$rotulos->num_ficha<br>
                                    Empresa o Negocio:&nbsp;$rotulos->nom_empresa<br>
                                </p>
                                <br>
                                Estimado(a) señor(a):
                                <p style='text-indent: 20px;'>En nombre del Concejo Municipal, reciba un afectuoso saludo y deseos de éxito. El
                                    motivo de la presente es para manifestarle que su estado de cuenta en esta
                                    Municipalidad es el siguiente:
                                </p>
                                <p>
                                <br>
                                    <strong>Tasas Municipales</strong><br>
                                    Validez: <strong><u>$FechaDelDia</u></strong><br>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td><hr></td>
                            <td><hr></td>
                        </tr>
                        <tr>
                            <th scope='col'>Periodo: &nbsp;&nbsp;desde&nbsp; $InicioPeriodo&nbsp;</th>
                            <th scope='col'>&nbsp;&nbsp;hasta&nbsp; $PagoUltimoDiaMes&nbsp;</th>
                        </tr>
                        <tr>
                            <td align='right'>TASAS POR SERVICIO</td>
                            <td align='center'>$impuesto_año_actual_Dollar</td>
                        </tr>
                        <tr>
                            <td align='right'>TASAS POR SERVICION MORA</td>
                            <td align='center'>$impuestos_mora_Dollar
                        </tr>
                        <tr>
                            <td align='right'>INTEREES MORATORIOS</td>
                            <td align='center'>$InteresTotalDollar</td>
                        </tr>
                        <tr>
                            <td align='right'>FONDO F. PATRONALES 5%</td>
                            <td align='center'>$fondoFP</td>
                        </tr>
                        <tr>
                            <th scope='row'>TOTAL ADECUADO</th>
                            <th align='center'>$totalPago</th>
                        </tr>
                        <tr>
                            <td><hr></td>
                            <td><hr></td>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-indent: 20px;font-family: Arial; text-align: justify;font-size: 13;'>
                                <p>
                                Por lo que solicito para que comparezca ante esta Administración Tributaria Municipal, a saldar lo adeudado, o a
                                solicitar un plan de pago, concediéndose un plazo de treinta días contados a partir de la notificación para que efectúe el
                                pago correspondiente bajo la prevención, que de no hacerlo, obligara a esta Municipalidad a certificar su deuda
                                pendiente, a fin de que sin tramite alguno, se proceda a iniciar las diligencias judiciales correspondientes. 
                                <br><br>
                                Agradeciendo su comprension y atención a esta notificación me suscribo de usted, muy cordialmente.
                                </p>
                            </td>
                        </tr>
                        <tr align='center'>
                            <td colspan='2' align'center'>
                                <img id='imgf1' src='$imgf1'>
                            </td>
                        </tr>
                    </table>";
            
            $stylesheet = file_get_contents('css/cssconsolidado.css');
            $mpdf->WriteHTML($stylesheet,1);
            $mpdf->SetMargins(0, 0, 5);


            //$mpdf->setFooter("Página: " . '{PAGENO}' . "/" . '{nb}');
        
            $mpdf->WriteHTML($tabla,2);
            $mpdf->Output();
        }
    }
}

