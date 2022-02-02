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
    Route::get('/admin/empresas/tarifas_fijas', [EmpresaController::class, 'tablaTarifaModal']);
    Route::post('/admin/empresas/calificacion/nueva', [EmpresaController::class,'nuevaCalificacion']);
    Route::post('/admin/empresas/calculo_cobros{empresa}', [EmpresaController::class, 'calculo_cobros']);
    Route::get('/admin/empresas/recalificacion/{empresa}', [EmpresaController::class,'Recalificacion']);


    // --- CONTRIBUYENTES ---
    Route::get('/admin/nuevo/contribuyentes/Listar', [ContribuyentesController::class,'listarContribuyentes'])->name('admin.listarContribuyentes.index');
    Route::get('/admin/nuevo/contribuyentes/Crear', [ContribuyentesController::class,'crearContribuyentes'])->name('admin.crear.contribuyentes.index');
    Route::get('/admin/contribuyentes/tabla', [ContribuyentesController::class,'tablaContribuyentes']);
    Route::post('/admin/Contribuyentes/Crear_Contribuyentes', [ContribuyentesController::class,'nuevoContribuyente']);
    Route::post('/admin/Contribuyentes/informacion', [ContribuyentesController::class, 'informacionContribuyentes']);
    Route::post('/admin/Contribuyentes/editar', [ContribuyentesController::class, 'editarContribuyente']);
    Route::post('/admin/Contribuyentes/eliminar_contribuyentes', [ContribuyentesController::class, 'eliminarContribuyentes']);


    // --- SIN PERMISOS VISTA 403 ---
    Route::get('sin-permisos', [ControlController::class,'indexSinPermiso'])->name('no.permisos.index');


    // --- DETALLE ACTIVIDAD ECONÓMICA
    Route::get('/admin/DetalleActividadEconomica/Crear', [DetalleActividadEController::class,'crearActividad'])->name('admin.crear.detalleactividad.index');
    Route::post('/admin/DetalleActividadEconomica/Detalle-Act', [DetalleActividadEController::class,'nuevaActividad']);
    Route::get('/admin/DetalleActividadEconomica/Listar', [DetalleActividadEController::class,'listarDetalleActividadE'])->name('admin.listarDetalleActividadEconomica.index');
    Route::get('/admin/DetalleActividadEconomica/tabla', [DetalleActividadEController::class,'tablaDetalleActividadEconomica']);
    Route::post('/admin/DetalleActividadEconomica/informacion', [DetalleActividadEController::class, 'informacionDetalle']);
    Route::post('/admin/DetalleActividadEconomica/editar', [DetalleActividadEController::class, 'editarDetalles']);
    Route::post('/admin/DetalleActividadEconomica/eliminar_detalles', [DetalleActividadEController::class, 'eliminarD']);

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
