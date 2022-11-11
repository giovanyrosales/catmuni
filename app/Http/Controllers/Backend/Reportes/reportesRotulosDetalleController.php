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
    
}