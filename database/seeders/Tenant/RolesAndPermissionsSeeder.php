<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        //registramos los roles
        $arrayOfPermissionsNames = [
            'ver compra', 'add compra','anular compra',
            'ver venta','add venta', 'anular venta',
            'ver depositos','add depositos','editar depositos','eliminar depositos',
            'ver productos','add productos','editar productos','eliminar productos',
            'ver clientes','add clientes','editar clientes','eliminar clientes',
            'ver proveedores','add proveedores','editar proveedores','eliminar proveedores',
            'ver usuarios','add usuarios','editar usuarios','eliminar usuarios',
            'ver tps entrada de dinero','add tps entrada de dinero','editar tps entrada de dinero','eliminar tps entrada de dinero',
            'ver tps salida de dinero','add tps salida de dinero','editar tps salida de dinero','eliminar tps entrada de dinero',
            'ver tps ingreso de productos','add tps ingreso de productos','editar tps ingreso de productos','eliminar tps ingreso de productos',
            'ver tps salida de productos','add tps salida de productos','editar tps salida de productos','eliminar tps salida de productos',
            'ver unidades','add unidades','editar unidades','eliminar unidades',
            'ver categorias','add categorias','editar categorias','eliminar categorias',
            'ver sub-categorias','add sub-categorias','editar sub-categorias','eliminar sub-categorias',
        ];
        $permissions = collect($arrayOfPermissionsNames)->map(function ($permission) {
            return [
                'name' => $permission, 
                'guard_name' => 'tenant',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ];
        });
        if (Permission::count() == 0) {
             Permission::insert($permissions->toArray());
        }
        if (Role::count() == 0) {
            $role = Role::create(['name' => 'admin','guard_name' => 'tenant',]);
        $role->givePermissionTo(Permission::all());

        // or may be done by chaining
        $role = Role::create(['name' => 'ventas','guard_name' => 'tenant',])
                ->givePermissionTo([
                    'ver venta','add venta', 'anular venta',
                    'ver clientes','add clientes','editar clientes','eliminar clientes'
                ]);

        $role = Role::create(['name' => 'compras','guard_name' => 'tenant',])
                ->givePermissionTo([
                    'ver compra', 'add compra','anular compra',
                    'ver proveedores','add proveedores','editar proveedores','eliminar proveedores'
                ]);

        $role = Role::create(['name' => 'supervisor','guard_name' => 'tenant',]);
        $role->givePermissionTo([
                'ver depositos','add depositos','editar depositos','eliminar depositos',
                'ver productos','add productos','editar productos','eliminar productos',
                'ver unidades','add unidades','editar unidades','eliminar unidades',
                'ver categorias','add categorias','editar categorias','eliminar categorias',
                'ver sub-categorias','add sub-categorias','editar sub-categorias','eliminar sub-categorias',
        ]);
        }
    }
}
