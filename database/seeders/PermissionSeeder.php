<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [
            'ver-categoria',
            'crear-categoria',
            'editar-categoria',
            'eliminar-categoria',
            'ver-producto',
            'crear-producto',
            'editar-producto',
            'eliminar-producto',
            'ver-compra',
            'crear-compra',
            'mostrar-compra',
            'ver-venta',
            'crear-venta',
            'mostrar-venta',
            'ver-movimiento',
            'ver-persona',
            'crear-persona',
            'editar-persona',
            'eliminar-persona',
            'ver-user',
            'crear-user',
            'editar-user',
            'eliminar-user',
            'ver-role',
            'crear-role',
            'editar-role',
            'eliminar-role'
        ];

        //Crear permisos
        foreach($permisos as $permiso){
            Permission::create([
                'name' => $permiso
            ]);
        }

        // Crear Rol Admin
        $rol = Role::create([
            'name' => 'Administrador'
        ]);

        //Asignar permisos al rol admin
        $rol->givePermissionTo($permisos);
    }
}
