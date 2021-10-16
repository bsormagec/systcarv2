<?php

namespace Database\Seeders;
use Database\Seeders\Tenant\UnitsTableSeeder;
use Database\Seeders\Tenant\CategoryTableSeeder;
use Database\Seeders\Tenant\WarehouseTableSeeder;
use Database\Seeders\Tenant\TypePaymentSeeder;
use Database\Seeders\Tenant\RolesAndPermissionsSeeder;
use Database\Seeders\Tenant\TypeDocumentSeeder;
use Database\Seeders\Tenant\SettingTableSeeder;
use Database\Seeders\Tenant\CurrencySeeder;
use Database\Seeders\Tenant\EspeciesTableSeeder;
use Database\Seeders\Tenant\RazasTableSeeder;
use Database\Seeders\Tenant\SpecialityTableSeeder;
use Database\Seeders\Tenant\SchedulesTableSeeder;
use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(WarehouseTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UnitsTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(TypePaymentSeeder::class);
        $this->call(TypeDocumentSeeder::class);
        $this->call(SettingTableSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(EspeciesTableSeeder::class);
        $this->call(RazasTableSeeder::class);
        $this->call(SpecialityTableSeeder::class);
        $this->call(SchedulesTableSeeder::class);
    }
}
