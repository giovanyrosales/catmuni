<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // --- CREAR ROLES ---

        // encargado administrador
        $role1 = Role::create(['name' => 'Encargado-Administrador']);

        // encargado del area de Empresas
        $role2 = Role::create(['name' => 'Encargado-Empresas']);

        // encargado del area de Inmuebles
        $role3 = Role::create(['name' => 'Encargado-Inmuebles']);


        // --- CREAR PERMISOS ---

        // visualizar roles y permisos
        Permission::create(['name' => 'seccion.roles.y.permisos', 'description' => 'Cuando hace login, se podra visualizar roles y permisos'])->syncRoles($role1);
       
        // redireccionamiento a url - encargado de Empresas
        Permission::create(['name' => 'url.empresa.crear.index', 'description' => 'Cuando hace login, se redirigirá la vista Empresas Crear'])->syncRoles($role2);

        // redireccionamiento a url - encargado de Inmuebles
        Permission::create(['name' => 'url.inmueble.crear.index', 'description' => 'Cuando hace login, se redirigirá la vista Inmuebles Crear'])->syncRoles($role3);


    }
}