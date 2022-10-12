<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Login\LoginController;
use App\Http\Controllers\Controles\ControlController;
use App\Http\Controllers\Backend\Empresas\EmpresaController;
use App\Http\Controllers\Backend\RolesPermisos\RolesController;
use App\Http\Controllers\Backend\RolesPermisos\PermisosController;
use App\Http\Controllers\Backend\Contribuyentes\ContribuyentesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Backend\Perfil\PerfilController;
use App\Http\Controllers\Backend\DetalleActividadE\DetalleActividadEController;
use App\Http\Controllers\Backend\TasaInteres\TasaInteresController;
use App\Http\Controllers\Backend\TarifaFija\TarifaFijaController;
use App\Http\Controllers\Backend\LicenciaMatricula\LicenciaMatriculaController;
use App\Http\Controllers\Backend\Multas\MultasController;
use App\Http\Controllers\Backend\TarifaVariable\TarifaVariableController;
use App\Http\Controllers\Backend\ActividadEspecifica\ActividadEspecificaController;
use App\Http\Controllers\Backend\MatriculasDetalle\MatriculasDetalleController;
use App\Http\Controllers\Backend\Rotulos\RotulosController;
use App\Http\Controllers\Backend\RotulosDetalle\RotulosDetalleController;
use App\Http\Controllers\Backend\BusesDetalle\BusesDetalleController;
use App\Http\Controllers\Backend\Buses\BusesController;
use App\Http\Controllers\Backend\Reportes\reportesBusesController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Backend\Reportes\reportesController;
use App\Http\Controllers\Backend\Reportes\reportesRotulosController;
use App\Http\Controllers\Backend\Reportes\reportesBusesDetalleController;
use App\Models\Empresas;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

    Route::get('/', [LoginController::class,'index'])->name('login');

    Route::post('admin/login', [LoginController::class, 'login']);
    Route::post('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

    // --- CONTROL WEB ---
    Route::get('/panel', [ControlController::class,'indexRedireccionamiento'])->name('admin.panel');
    Route::get('/admin/roles/index', [RolesController::class,'index'])->name('admin.roles.index');

     // --- ROLES ---
    Route::get('/admin/roles/tabla', [RolesController::class,'tablaRoles']);
    Route::get('/admin/roles/lista/permisos/{id}', [RolesController::class,'vistaPermisos']);
    Route::get('/admin/roles/permisos/tabla/{id}', [RolesController::class,'tablaRolesPermisos']);
    Route::post('/admin/roles/permiso/borrar', [RolesController::class, 'borrarPermiso']);
    Route::post('/admin/roles/permiso/agregar', [RolesController::class, 'agregarPermiso']);
    Route::get('/admin/roles/permisos/lista', [RolesController::class,'listaTodosPermisos']);
    Route::get('/admin/roles/permisos-todos/tabla', [RolesController::class,'tablaTodosPermisos']);
    Route::post('/admin/roles/borrar-global', [RolesController::class, 'borrarRolGlobal']);

    // --- PERMISOS ---
    Route::get('/admin/permisos/index', [PermisosController::class,'index'])->name('admin.permisos.index');
    Route::get('/admin/permisos/tabla', [PermisosController::class,'tablaUsuarios']);
    Route::post('/admin/permisos/nuevo-usuario', [PermisosController::class, 'nuevoUsuario']);
    Route::post('/admin/permisos/info-usuario', [PermisosController::class, 'infoUsuario']);
    Route::post('/admin/permisos/editar-usuario', [PermisosController::class, 'editarUsuario']);
    Route::post('/admin/permisos/nuevo-rol', [PermisosController::class, 'nuevoRol']);
    Route::post('/admin/permisos/extra-nuevo', [PermisosController::class, 'nuevoPermisoExtra']);
    Route::post('/admin/permisos/extra-borrar', [PermisosController::class, 'borrarPermisoGlobal']);

    // --- PERFIL ---
    Route::get('/admin/usuario/editarperfil/index', [PerfilController::class,'indexEditarPerfil'])
    ->name('admin.usuario.editarperfil');
    Route::post('/admin/usuario/editarperfil', [PerfilController::class, 'actualizarUsuario']);

    // --- NUEVA EMPRESA - ROL ENCARGADO EMPRESAS
    Route::get('/admin/nuevo/empresa/index', [EmpresaController::class,'index'])->name('admin.crear.empresa.index');
    Route::post('/admin/empresa/nueva', [EmpresaController::class,'nuevaEmpresa']);
    Route::get('/admin/nuevo/empresa/listar', [EmpresaController::class,'listarEmpresas'])->name('admin.listarEmpresa.index');
    Route::get('/admin/empresas/tabla', [EmpresaController::class,'tablaEmpresas']);
    Route::post('/admin/empresas/informacion', [EmpresaController::class, 'informacionEmpresas']);
    Route::post('/admin/empresas/editar', [EmpresaController::class, 'editarEmpresas']);
    Route::get('/admin/empresas/show/{lista}', [EmpresaController::class, 'show']);
    Route::get('/admin/empresas/calificacion/{empresa}', [EmpresaController::class, 'calificacion']);
    Route::get('/admin/empresas/cobros/{empresa}', [EmpresaController::class, 'cobros']);
    Route::post('/admin/empresas/calculo_calificacion', [EmpresaController::class, 'calculo_calificacion']);
    Route::post('/admin/empresas/calificacion/nueva', [EmpresaController::class,'nuevaCalificacion']);
    Route::post('/admin/empresas/calculo_cobros_empresa', [EmpresaController::class, 'calculo_cobros_empresa']);
    Route::post('/admin/empresas/calculo_cobros_licencia_licor', [EmpresaController::class, 'calculo_cobroLicor']);
    Route::get('/admin/empresas/recalificacion/{empresa}', [EmpresaController::class,'Recalificacion']);
    Route::get('/admin/empresas/calificaciones/tabla_matriculas/{empresa}', [EmpresaController::class,'tablaMatriculas']);
    Route::get('/admin/empresas/calificaciones/tablaCalificaciones/{empresa}', [EmpresaController::class,'tablaCalificaciones']);
    Route::post('/admin/empresas/calificaciones/eliminar', [EmpresaController::class, 'eliminar_calificacion']);
    Route::post('/admin/empresas/calificacion/asignar_anterior', [EmpresaController::class, 'asignar_anterior']);
    Route::post('/admin/empresas/llenar_detalle_matriculas', [EmpresaController::class, 'llenar_detalle_matriculas']);
    Route::post('/admin/empresa/buscar/act_especifica', [EmpresaController::class, 'buscarActividadEsp']);
    Route::get('/admin/empresa/historial/avisos_notificaciones', [EmpresaController::class,'historial_avisos_notificaciones'])->name('admin.historico.avisos.index');

    //OBLIGACIONES TRIBUTARIAS
    Route::get('/admin/nuevo/empresa/vista_cobro_general', [EmpresaController::class,'vista_cobro_general'])->name('admin.cobrar.empresa.index');
    Route::post('/admin/buscar/obligaciones_tributarias', [EmpresaController::class,'buscar_obligaciones_tributarias']);

    // --- TRASPASO Y CIERRE EMPRESA
    Route::post('/admin/empresas/show/traspaso', [EmpresaController::class,'nuevoTraspaso']);
    Route::post('/admin/empresas/show/cierre', [EmpresaController::class,'nuevoEstado']);
    Route::post('/admin/empresas/show/informacion', [EmpresaController::class,'infoTraspaso']);

    Route::get('/admin/empresas/cierres_traspasos/{empresa}', [EmpresaController::class,'cierres_traspasos']);
    Route::get('/admin/empresas/cierres/tabla/{empresa}', [EmpresaController::class,'tablaCierres']);
    Route::get('/admin/empresas/traspasos/tabla/{empresa}', [EmpresaController::class,'tablaTraspasos']);

    // --- CONTRIBUYENTES ---
    Route::get('/admin/nuevo/contribuyentes/Listar', [ContribuyentesController::class,'listarContribuyentes'])->name('admin.listarContribuyentes.index');
    Route::get('/admin/nuevo/contribuyentes/Crear', [ContribuyentesController::class,'crearContribuyentes'])->name('admin.crear.contribuyentes.index');
    Route::get('/admin/contribuyentes/tabla', [ContribuyentesController::class,'tablaContribuyentes']);
    Route::post('/admin/Contribuyentes/Crear_Contribuyentes', [ContribuyentesController::class,'nuevoContribuyente']);
    Route::post('/admin/Contribuyentes/informacion', [ContribuyentesController::class, 'informacionContribuyentes']);
    Route::post('/admin/Contribuyentes/editar', [ContribuyentesController::class, 'editarContribuyente']);
    Route::post('/admin/Contribuyentes/eliminar_contribuyentes', [ContribuyentesController::class, 'eliminarContribuyentes']);
    Route::get('/admin/contribuyentes/historico/solvencias', [ContribuyentesController::class,'historico_solvencias'])->name('admin.historico.solvencias.index');
    Route::get('/admin/contribuyentes/tabla/historicocs', [ContribuyentesController::class,'tablahistoricocs']);
    Route::get('/admin/contribuyentes/tabla/historicocg', [ContribuyentesController::class,'tablahistoricocg']);

    // --- SIN PERMISOS VISTA 403 ---
    Route::get('sin-permisos', [ControlController::class,'indexSinPermiso'])->name('no.permisos.index');

    // --- TARIFA VARIABLE
    Route::get('/admin/TarifaVariable/Crear', [TarifaVariableController::class,'index'])->name('admin.crear.tarifavariable.index');
    Route::post('/admin/TarifaVariable/Detalle-Act', [TarifaVariableController::class,'nuevaTarifaV']);
    Route::get('/admin/TarifaVariable/Listar', [TarifaVariableController::class,'listarTarifaV'])->name('admin.listarTarifaVariable.index');
    Route::get('/admin/TarifaVariable/tabla', [TarifaVariableController::class,'tablaTarifaVariable']);
    Route::post('/admin/TarifaVariable/informacion', [TarifaVariableController::class, 'informacionTarifaV']);
    Route::post('/admin/TarifaVariable/editar', [TarifaVariableController::class, 'editarTarifaV']);
    Route::post('/admin/TarifaVariable/eliminar_detalles', [TarifaVariableController::class, 'eliminarTarifaV']);

    // --- TASA INTERES
    Route::get('/admin/TasaInteres/ListarInteres', [TasaInteresController::class,'index'])->name('admin.TasaInteres.index');
    Route::get('/admin/TasaInteres/tabla', [TasaInteresController::class,'tablaTasas']);
    Route::get('/admin/nuevo/TasaInteres/Listar', [TasaInteresController::class,'listarInteres'])->name('admin.listarInteres.index');
    Route::post('/admin/nuevo/TasaInteres/nuevo', [TasaInteresController::class,'agregarInteres']);
    Route::post('/admin/TasaInteres/informacion', [TasaInteresController::class, 'informacionInteres']);
    Route::post('/admin/TasaInteres/editar', [TasaInteresController::class, 'editarInteres']);
    Route::post('/admin/TasaInteres/eliminar', [TasaInteresController::class, 'eliminarInteres']);

    // --- TARIFA FIJA
    Route::get('/admin/TarifaFija/ListarTarifaFija', [TarifaFijaController::class,'index'])->name('admin.TarifaFija.index');
    Route::get('/admin/TarifaFija/tabla', [TarifaFijaController::class,'tablaTarifa']);
    Route::get('/admin/nuevo/TarifaFija/Listar', [TarifaFijaController::class,'listarTarifaFija'])->name('admin.listarTarifaFija.index');
    Route::post('/admin/TarifaFija/NuevaT', [TarifaFijaController::class,'nuevaTarifa']);
    Route::post('/admin/TarifaFija/informacion', [TarifaFijaController::class, 'informacionTarifaF']);
    Route::post('/admin/TarifaFija/editar', [TarifaFijaController::class, 'editarTarifaF']);
    Route::post('/admin/TarifaFija/eliminar', [TarifaFijaController::class, 'eliminarTarifaF']);


    // --- LLENAR SELECT ACTIVIDAD ESPECIFICA EN EL FORM TARIFA FIJA
    Route::post('/admin/TarifaFija/buscar', [TarifaFijaController::class,'buscarActividadEsp'] );

    // --- LICENCIA Y MATRICULA
    Route::get('/admin/LicenciaMatricula/ListarLicenciaMatricula', [LicenciaMatriculaController::class, 'index'])->name('admin.LicenciaMatricula.index');
    Route::get('/admin/LicenciaMatricula/tabla', [LicenciaMatriculaController::class,'tablaLicenciaMatricula']);
    Route::get('/admin/nuevo/LicenciaMatricula/Listar', [LicenciaMatriculaController::class,'listarLicencia'])->name('admin.listarLicenciaMatricula.index');
    Route::post('/admin/LicenciaMatricula/Nuevas', [LicenciaMatriculaController::class,'agregarLM']);
    Route::post('/admin/LicenciaMatricula/informacion', [LicenciaMatriculaController::class, 'informacionLM']);
    Route::post('/admin/LicenciaMatricula/editar', [LicenciaMatriculaController::class, 'editarLM']);
    Route::post('/admin/LicenciaMatricula/eliminar', [LicenciaMatriculaController::class, 'eliminarLM']);

    // --- INGRESOS Y EGRESOS
    Route::get('/admin/Multas/ListarMultas', [MultasController::class, 'index'])->name('admin.Multas.index');
    Route::get('/admin/Multas/tabla', [MultasController::class,'tablaMultas']);
    Route::get('/admin/nuevo/Multas/Listar', [MultasController::class,'listarMultas'])->name('admin.listarMultas.index');
    Route::post('/admin/Multas/NuevaM', [MultasController::class,'agregarM']);
    Route::post('/admin/Multas/informacion', [MultasController::class, 'informacionMultas']);
    Route::post('/admin/Multas/editar', [MultasController::class, 'editarMultas']);
    Route::post('/admin/Multas/eliminar', [MultasController::class, 'eliminarMultas']);

    // --- ACTIVIDAD ESPECÍFICA
    Route::get('/admin/ActividadEspecifica/ListarAE', [ActividadEspecificaController::class, 'index'])->name('admin.ActividadEspecifica.index');
    Route::get('/admin/ActividadEspecifica/tabla', [ActividadEspecificaController::class,'tablaActividadEspecifica']);
    Route::get('/admin/nuevo/ActividadEspecifica/Listar', [ActividadEspecificaController::class,'listarActividadEspecifica'])->name('admin.listarActividadEspecifica.index');
    Route::post('/admin/ActividadEspecifica/NuevaM', [ActividadEspecificaController::class,'agregarActividadE']);
    Route::post('/admin/ActividadEspecifica/informacion', [ActividadEspecificaController::class, 'informacionActividadEspecifica']);
    Route::post('/admin/ActividadEspecifica/editar', [ActividadEspecificaController::class, 'editarActividadEspecifica']);
    Route::post('/admin/ActividadEspecifica/eliminar', [ActividadEspecificaController::class, 'eliminarActividadEspecifica']);

    // ---MATRICULAS DETALLES
    Route::get('/admin/matriculas_detalle/index/{empresa}', [MatriculasDetalleController::class,'index']);
    Route::post('/admin/matriculas_detalle/agregar', [MatriculasDetalleController::class,'agregar_matriculas']);
    Route::get('/admin/matriculas_detalle/tabla/{empresa}', [MatriculasDetalleController::class,'tablaMatriculas']);
    Route::post('/admin/matriculas_detalle/eliminar', [MatriculasDetalleController::class, 'eliminarM']);
    Route::post('/admin/matriculas_detalle/informacion', [MatriculasDetalleController::class, 'informacionMatricula']);
    Route::post('/admin/matriculas_detalle/editar', [MatriculasDetalleController::class, 'editarMatricula']);
    Route::post('/admin/matriculas_detalle/agregar', [MatriculasDetalleController::class,'agregar_matriculas']);
    Route::post('/admin/empresas/info_cobro_matriculas', [MatriculasDetalleController::class, 'info_cobroMatriculas']);
    Route::post('/admin/empresas/calculo_cobros_mesas', [MatriculasDetalleController::class, 'calculo_cobroMesas']);
    Route::post('/admin/empresas/calculo_cobros_maquinas', [MatriculasDetalleController::class, 'calculo_cobroMaquinas']);
    Route::post('/admin/empresas/calculo_cobros_sinfonolas', [MatriculasDetalleController::class, 'calculo_cobroSinfonolas']);
    Route::post('/admin/empresas/calculo_cobros_aparatos', [MatriculasDetalleController::class, 'calculo_cobroAparatos']);
    Route::get('/admin/matriculas_detalle/ver_historial_cobros_aparatos/{id}', [MatriculasDetalleController::class, 'VerHistorialCobros_Aparatos']);
    Route::get('/admin/matriculas_detalle/ver_historial_cobros_sinfonolas/{id}', [MatriculasDetalleController::class, 'VerHistorialCobros_sinfonolas']);
    Route::get('/admin/matriculas_detalle/ver_historial_cobros_maquinas/{id}', [MatriculasDetalleController::class, 'VerHistorialCobros_maquinas']);
    Route::get('/admin/matriculas_detalle/ver_historial_cobros_mesas/{id}', [MatriculasDetalleController::class, 'VerHistorialCobros_mesas']);
    Route::post('/admin/empresas/calculo_calificacion_matricula', [MatriculasDetalleController::class,'Calculo_calificacion_matriculas']);
    Route::post('/admin/empresas/calificacion/matricula/nueva', [MatriculasDetalleController::class,'Registrar_calificacion_matriculas']);

    // ---MATRICULAS DETALLE ESPECIFICO
    Route::post('/admin/matriculas_detalle/ver_matriculas_especificas', [MatriculasDetalleController::class, 'VerMatriculaEsp']);
    Route::post('/admin/matriculas_detalle_especifico/agregar', [MatriculasDetalleController::class,'agregar_matriculas_detalle_especifico']);
    Route::post('/admin/matriculas_detalle/especificar', [MatriculasDetalleController::class, 'especificarMatriculas']);


    // --- RÓTULOS
    Route::get('/admin/nuevo/rotulos/Crear', [RotulosController::class,'crearRotulos'])->name('admin.crear.rotulos.index');
    Route::post('/admin/Rotulos/CrearRotulos', [RotulosController::class,'nuevoRotulo']);
    Route::get('/admin/Rotulos/tabla', [RotulosController::class,'tablaRotulos']);
    Route::get('/admin/Rotulos/Listar', [RotulosController::class,'listarRotulos'])->name('admin.listarRotulos.index');
    Route::post('/admin/Rotulos/Ver', [RotulosController::class, 'informacionRotulo']);
    Route::post('/admin/Rotulos/Editar', [RotulosController::class, 'editarRotulos']);
    Route::post('/admin/Rotulos/Borrar', [RotulosController::class, 'eliminarRotulo']);
    Route::get('/admin/Rotulos/vista/{rotulo}', [RotulosController::class, 'showRotulos']);
    Route::post('/admin/Rotulos/vista/cierre', [RotulosController::class, 'nuevoEstadoR']);
    Route::post('/admin/Rotulos/vista/inf-cierre', [RotulosController::class, 'infoCierre']);
    Route::post('/admin/Rotulos/vista/traspaso', [RotulosController::class, 'traspasoR']);
    Route::get('/admin/Rotulos/inspeccion/{rotulo}', [RotulosController::class, 'inspeccionRotulo']);
    Route::post('/admin/Rotulos/guardar-inspeccion', [RotulosController::class, 'crear_inspeccion']);
    Route::get('/admin/Rotulos/calificacion/{rotulo}', [RotulosController::class, 'calificacionRotulo']);
    Route::get('/admin/rotulos/calificaciones/tablarotulo/{rotulo}', [RotulosController::class,'tablaCalificacionR']);
    Route::post('/admin/rotulos/calificacion/nuevaC' , [RotulosController::class, 'guardarCalificacion']);
    Route::get('/admin/rotulos/cobros/{rotulo}', [RotulosController::class, 'cobros']);
    Route::post('/admin/rotulos/calcular-Cobros', [RotulosController::class, 'calcularCobros']);

    Route::get('/admin/rotulos/ver_historial_cobros_rotulos/{id}', [RotulosController::class, 'VerHistorialCobros_Rotulos']);

    Route::post('/admin/rotulos/buscarE', [RotulosController::class,'buscarEmpresa'] );
    Route::post('/admin/rotulos/buscar-traspaso', [RotulosController::class,'buscarEmpresaTraspaso'] );

    //CIERRE Y TRASPASO DE RÓTULOS
    Route::post('/admin/rotulos/show/informacion', [RotulosController::class,'infoTraspasoR']);
    Route::get('/admin/rotulos/cierres_traspasos/{rotulo}', [RotulosController::class,'cierres_traspasosRotulo']);
    Route::post('/admin/rotulos/show/cierre', [RotulosController::class,'nuevoEstadoRotulo']);
    Route::post('/admin/rotulos/show/traspaso', [RotulosController::class,'nuevoTraspasoRotulo']);

    //BUSES
    Route::get('/admin/nuevo/buses/Crear', [BusesDetalleController::class,'index'])->name('admin.crear.buses.index');
    Route::post('/admin/buses/agregar', [BusesDetalleController::class,'nuevoBus']);
    Route::get('/admin/buses/tabla', [BusesDetalleController::class,'tablaBuses']);
    Route::get('/admin/buses/Listar', [BusesDetalleController::class,'listarBuses'])->name('admin.listarBuses.index');
    Route::post('/admin/buses_detalle/especifico', [BusesDetalleController::class, 'especificarBuses']);
    Route::post('/admin/buses_detalle_especifico/agregar', [BusesDetalleController::class,'agregar_buses_detalle_especifico']);
    Route::post('/admin/buses_detalle/informacion', [BusesDetalleController::class, 'informacionBus']);
    Route::post('/admin/buses_detalle/editar', [BusesDetalleController::class, 'editarBus']);
    Route::post('/admin/buses_detalle/ver_buses_especificos', [BusesDetalleController::class, 'VerBusEsp']);
    Route::post('/admin/buses_detalle/eliminar', [BusesDetalleController::class, 'eliminarB']);
    Route::get('/admin/buses/vista/{bus}', [BusesDetalleController::class, 'showBuses']);
    Route::get('/admin/buses/calificacion/{bus}', [BusesDetalleController::class, 'calificacionBus']);
    Route::get('/admin/buses/calificaciones/tablabus/{bus}', [BusesDetalleController::class,'tablaCalificacionB']);
    Route::post('/admin/buses/calificacion/nueva' , [BusesDetalleController::class, 'guardarCalificacionBus']);
    Route::get('/admin/buses/cobros/{buses}', [BusesDetalleController::class, 'cobrosBus']);
    Route::post('/admin/buses/calcular-CobrosB', [BusesDetalleController::class, 'calcularCobrosBus']);

    Route::post('/admin/bus/buscar-traspasoBus', [BusesDetalleController::class,'buscarEmpresaBuses'] );

    Route::post('/admin/buses_detalle/show/informacion', [BusesDetalleController::class,'infoTraspasoBuses']);
    Route::get('/admin/buses/cierres_traspasosB/{buses}', [BusesDetalleController::class,'cierres_traspasosBus']);
    Route::post('/admin/buses/show/traspaso-buses', [BusesDetalleController::class,'nTraspasoBus']);
    Route::get('/admin/buses/tabla-traspaso', [BusesDetalleController::class,'tablaTraspasos']);

    //NUEVO MÓDULO RÓTULOS
    Route::get('/admin/nuevo/rotulo-detalle/Crear', [RotulosDetalleController::class,'index'])->name('admin.crear.rotulos.index');
    Route::post('/admin/rotulo-detalle/agregar', [RotulosDetalleController::class,'agregarRotulos']);
    Route::get('/admin/rotulo-detalle/tabla', [RotulosDetalleController::class,'tablaRotulos']);
    Route::get('/admin/rotulo-detalle/Listar', [RotulosDetalleController::class,'listarRotulos'])->name('admin.listarRotulosDetalle.index');
    Route::post('/admin/rotulo_detalle/especifico', [RotulosDetalleController::class, 'especificarRotulos']);
    Route::post('/admin/rotulos_detalle_especifico/agregar', [RotulosDetalleController::class,'agregar_rotulos_detalle_especifico']);


    //TERMINA NUEVO MÓDULO RÓTULOS

    //REPORTES EMPRESAS
    Route::get('/admin/estado_cuenta/pdf/{f1}/{f2}/{t1}/{f3}/{tf}/{id}', [reportesController::class, 'estado_cuenta']);
    Route::get('/admin/generar_aviso/pdf/{id}', [reportesController::class, 'aviso']);
    Route::get('/admin/generar_notificacion/pdf/{f1}/{f2}/{t1}/{f3}/{id}', [reportesController::class, 'notificacion']);
    Route::get('/admin/generar_notificacion/maquinas/pdf/{f1}/{f2}/{t1}/{f3}/{id}', [reportesController::class, 'notificacion_maquinas']);
    Route::get('/admin/generar_notificacion/mesas/pdf/{f1}/{f2}/{t1}/{f3}/{id}', [reportesController::class, 'notificacion_mesas']);
    Route::get('/admin/generar_notificacion/aparatos/pdf/{f1}/{f2}/{id}', [reportesController::class, 'notificacion_aparatos']);
    Route::get('/admin/generar_notificacion/sinfonolas/pdf/{f1}/{f2}/{t1}/{f3}/{id}', [reportesController::class, 'notificacion_sinfonolas']);

    Route::get('/admin/estado_cuenta_licor/pdf/{f1}/{f2}/{id}', [reportesController::class, 'estado_cuenta_licor']);
    Route::get('/admin/estado_cuenta_aparatos/pdf/{f1}/{f2}/{ap}/{id}', [reportesController::class, 'estado_cuenta_aparatos']);
    Route::get('/admin/estado_cuenta_sinfonolas/pdf/{f1}/{f2}/{is}/{ti}/{id}', [reportesController::class, 'estado_cuenta_sinfonolas']);
    Route::get('/admin/estado_cuenta_maquinas/pdf/{f1}/{f2}/{im}/{ti}/{id}', [reportesController::class, 'estado_cuenta_maquinas']);
    Route::get('/admin/estado_cuenta_mesas/pdf/{f1}/{f2}/{ime}/{ti}/{id}', [reportesController::class, 'estado_cuenta_mesas']);
    Route::get('/admin/traspaso_empresas/pdf/{id}', [reportesController::class, 'traspaso_empresa']);
    Route::get('/admin/cierre_empresas/pdf/{id}', [reportesController::class, 'cierre_empresa']);
    Route::get('/admin/traspaso_empresas_historico/pdf/{id}', [reportesController::class, 'traspaso_empresa_historico']);
    Route::get('/admin/cierres_empresas_historico/pdf/{id}', [reportesController::class, 'cierre_empresa_historico']);
    Route::get('/admin/reporte/calificacion/pdf/{id}', [reportesController::class, 'reporte_calificacion']);
    Route::get('/admin/generar_reporte/datos_empresa/pdf/{id}', [reportesController::class, 'reporte_datos_empresa']);
    Route::get('/admin/reporte/resolucion_apertura/pdf/{id}', [reportesController::class, 'resolucion_apertura']);

    //REPORTES CONTRIBUYENTES
    Route::get('/admin/generar/solvencia/pdf/{id}', [reportesController::class, 'generar_solvencia']);
    Route::get('/admin/generar/constancia/simple/pdf/{id}', [reportesController::class, 'generar_constancia_simple']);

    //REPORTE EMPRESAS
    Route::get('/admin/generar/solvencia/empresa/pdf/{id}', [reportesController::class, 'generar_solvencia_empresa']);

    //AVISOS Y NOTIFICACIONES HISTORIAL
    Route::get('/admin/empresa/tabla/historico/avisos', [EmpresaController::class,'tablahistoricoavisos']);
    Route::get('/admin/empresa/tabla/historico/notificaciones', [EmpresaController::class,'tablahistoriconotificaciones']);


    //AVISOS Y NOTIFICACIONES DE MATRICULAS

    // REPORTES
    Route::get('/admin/reporte/actividad/economica/index', [reportesController::class, 'indexReporteActividadEconomica'])->name('admin.sidebar.reporte.actividad.economica');
    Route::get('/admin/pdf/reporte/actividad/economica/{id}', [reportesController::class, 'pdfReporteActividadEconomica']);

    Route::get('/admin/reporte/contribuyentes/index', [reportesController::class, 'indexReporteContribuyentes'])->name('admin.sidebar.reporte.contribuyentes');
    Route::get('/admin/pdf/reporte/contribuyentes/{id}', [reportesController::class, 'pdfReporteContribuyentes']);
    
    Route::get('/admin/reporte/mora_tributaria/index', [reportesController::class, 'indexReporteMoraTributaria'])->name('admin.sidebar.reporte.mora_tributaria');
    Route::get('/admin/pdf/reporte/mora_tributaria', [reportesController::class, 'pdfReporteMoraTributaria']);
    Route::post('/admin/calculo/mora', [reportesController::class, 'calculo_mora']);

    //REPORTES RÓTULOS
    Route::get('/admin/estado_cuenta/rotulos/pdf/{f1}/{f2}/{ti}/{ir}/{id_empresa}', [reportesRotulosController::class, 'estado_cuenta_rotulos']);
    Route::get('/admin/estado_cuenta/buses/pdf/{f1}/{f2}/{ti}/{ib}/{id_empresa}', [reportesBusesController::class, 'estado_cuenta_buses']);



    //REPORTES BUSES
    Route::get('/admin/estado_cuenta/buses_detalle/pdf/{f1}/{f2}/{ti}/{f3}/{id}', [reportesBusesDetalleController::class, 'estado_cuentas_buses_d']);
    Route::get('/admin/generar_aviso/buses/pdf/{id}', [reportesBusesDetalleController::class, 'aviso_buses']);
    Route::get('/admin/generar_notificacion_bus/pdf/{f1}/{f2}/{ti}/{f3}/{id}', [reportesBusesDetalleController::class, 'reporte_notificacion_bus']);


