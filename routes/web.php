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
    Route::get('/admin/nuevo/empresa/Listar', [EmpresaController::class,'listarEmpresas'])->name('admin.listarEmpresa.index');
    Route::get('/admin/empresas/tabla', [EmpresaController::class,'tablaEmpresas']);
    Route::post('/admin/empresas/informacion', [EmpresaController::class, 'informacionEmpresas']);
    Route::post('/admin/empresas/show-inf', [EmpresaController::class, 'informacionEmpresas']);
    Route::post('/admin/empresas/editar', [EmpresaController::class, 'editarEmpresas']);
    Route::get('/admin/empresas/show/{lista}', [EmpresaController::class, 'show']);
   
    

    // --- CONTRIBUYENTES ---
    Route::get('/admin/nuevo/contribuyentes/Listar', [ContribuyentesController::class,'listarContribuyentes'])->name('admin.listarContribuyentes.index');
    Route::get('/admin/nuevo/contribuyentes/Crear', [ContribuyentesController::class,'crearContribuyentes'])->name('admin.crear.contribuyentes.index');
    Route::get('/admin/contribuyentes/tabla', [ContribuyentesController::class,'tablaContribuyentes']);


    // --- SIN PERMISOS VISTA 403 ---
    Route::get('sin-permisos', [ControlController::class,'indexSinPermiso'])->name('no.permisos.index');
