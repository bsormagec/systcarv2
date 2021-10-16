<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\tenant\Category;
use App\Models\tenant\SubCategory;
use Illuminate\Database\Eloquent\Model;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        if (Category::count() == 0) {
            $this->create();
            $category = Category::get()->last();
            SubCategory::create([
                'nombre' => 'Por defecto',
                'descripcion' => 'NN',
                'slug' => 'por-defecto',
                'category_id'=> $category->id
            ]);
        }
        Model::reguard();
    }
    private function create()
    {
        $rows = [
            [
                'nombre' => 'ADEL. DE HONORARIOS',
                'descripcion' => 'ADELANTO DE HONORARIOS',
                'type' => 'expense',
                'slug' => 'adelanto-de-honorarios'
            ],
            [
                'nombre' => 'CAMB. DE TURNOS',
                'descripcion' => 'CAMBIO DE TURNOS',
                'type' => 'expense',
                'slug' => 'cambio-de-turnos'
            ],
            [
                'nombre' => 'GASTOS OPERATIVOS',
                'descripcion' => 'GASTOS OPERATIVOS',
                'type' => 'expense',
                'slug' => 'gastos-operativos'
            ],
            [
                'nombre' => 'PAG. A PROVEEDORES',
                'descripcion' => 'PAGO A PROVEEDORES',
                'type' => 'expense',
                'slug' => 'pago-a-proveedores'
            ],
            [
                'nombre' => 'PAG. DE SERVICIOS',
                'descripcion' => 'PAGO DE SERVICIOS',
                'type' => 'expense',
                'slug' => 'pago-de-servicios'
            ],
            [
                'nombre' => 'PAG VARIOS',
                'descripcion' => 'PAGOS VARIOS',
                'type' => 'expense',
                'slug' => 'pagos-varios'
            ],
            [
                'nombre' => 'PASAJ DE PERSONAL',
                'descripcion' => 'PASAJE DE PERSONAL',
                'type' => 'expense',
                'slug' => 'pasaje-de-personal'
            ],
            [
                'nombre' => 'RET. DINERO DE CAJA',
                'descripcion' => 'RETIRO DINERO DE CAJA',
                'type' => 'expense',
                'slug' => 'retiro-dinero-de-caja'
            ],
            [
                'nombre' => 'ANULACION DE FACTURA',
                'descripcion' => 'ANULACION DE FACTURA',
                'type' => 'expense',
                'slug' => 'anulacion-de-factura'
            ],
            [
                'nombre' => 'ANULACION DE COMPRA',
                'descripcion' => 'ANULACION DE COMPRA',
                'type' => 'expense',
                'slug' => 'anulacion-de-compra'
            ],
            [
                'nombre' => 'VENTA',
                'descripcion' => 'VENTA',
                'type' => 'income',
                'slug' => 'venta'
            ],
            [
                'nombre' => 'SERVICIOS',
                'descripcion' => 'SERVICIOS',
                'type' => 'income',
                'slug' => 'servicios'
            ],
            [
                'nombre' => 'AGREGAR DINERO A CAJA',
                'descripcion' => 'AGREGAR DINERO A CAJA',
                'type' => 'income',
                'slug' => 'agregar-dinero-a-caja'
            ],
            [
                'nombre' => 'OTROS',
                'descripcion' => 'OTROS',
                'type' => 'income',
                'slug' => 'otros'
            ],
            [
                'nombre' => 'CANJE',
                'descripcion' => 'CANJE',
                'type' => 'expenseproducts',
                'slug' => 'canje'
            ],
            [
                'nombre' => 'CONFUSION DE MEDICAMENTOS',
                'descripcion' => 'CONFUSION DE MEDICAMENTOS',
                'type' => 'expenseproducts',
                'slug' => 'confusion-de-medicamentsos'
            ],
            [
                'nombre' => 'DETERIORADO U ROTO',
                'descripcion' => 'DETERIORADO U ROTO',
                'type' => 'expenseproducts',
                'slug' => 'deteriorado-u-roto'
            ],
            [
                'nombre' => 'DEVOLUCION A PROVEEDOR',
                'descripcion' => 'DEVOLUCION A PROVEEDOR',
                'type' => 'expenseproducts',
                'slug' => 'devolucion-a-proveedor'
            ],
            [
                'nombre' => 'DONACIONES',
                'descripcion' => 'DONACIONES',
                'type' => 'expenseproducts',
                'slug' => 'donaciones'
            ],
            [
                'nombre' => 'TRASLADO ALMACEN',
                'descripcion' => 'TRASLADO ALMACEN',
                'type' => 'expenseproducts',
                'slug' => 'traslado-de-almacen'
            ],
            [
                'nombre' => 'ERROR DE INGRESO',
                'descripcion' => 'ERROR DE INGRESO',
                'type' => 'expenseproducts',
                'slug' => 'error-de-ingreso'
            ],
            [
                'nombre' => 'PERDIDA',
                'descripcion' => 'PERDIDA',
                'type' => 'expenseproducts',
                'slug' => 'perdida'
            ],
            [
                'nombre' => 'USO PERSONAL',
                'descripcion' => 'USO PERSONAL',
                'type' => 'expenseproducts',
                'slug' => 'uso-personal'
            ],
            [
                'nombre' => 'VENCIMIENTO',
                'descripcion' => 'VENCIMIENTO',
                'type' => 'expenseproducts',
                'slug' => 'vencimiento'
            ],
            [
                'nombre' => 'VENTA',
                'descripcion' => 'VENTA',
                'type' => 'expenseproducts',
                'slug' => 'ventas'
            ],
            [
                'nombre' => 'COMPRA',
                'descripcion' => 'COMPRA',
                'type' => 'incomeproducts',
                'slug' => 'compra'
            ],
            [
                'nombre' => 'ANULACION DE VENTA',
                'descripcion' => 'ANULACION DE VENTA',
                'type' => 'incomeproducts',
                'slug' => 'anulacion-de-venta'
            ],
            [
                'nombre' => 'DONACION',
                'descripcion' => 'DONACION',
                'type' => 'incomeproducts',
                'slug' => 'donacion'
            ],
            [
                'nombre' => 'OFERTA U BONIFICACION',
                'descripcion' => 'OFERTA U BONIFICACION',
                'type' => 'incomeproducts',
                'slug' => 'oferta-u-bonificacion'
            ],
            [
                'nombre' => 'TRASLADO DE ALMACENES',
                'descripcion' => 'TRASLADO DE ALMACENES',
                'type' => 'incomeproducts',
                'slug' => 'traslado-de-almacenes'
            ],
            [
                'nombre' => 'INVENTARIO DE PRODUCTO',
                'descripcion' => 'INVENTARIO DE PRODUCTO',
                'type' => 'incomeproducts',
                'slug' => 'inventario-de-producto'
            ],
            [
                'nombre' => 'Por defecto',
                'descripcion' => 'NN',
                'type' => 'item',
                'slug' => 'por-defecto'
            ]
        ];
        
        foreach ($rows as $row) {
            Category::create($row);
        }
    }
}
