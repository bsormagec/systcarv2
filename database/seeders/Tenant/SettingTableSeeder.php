<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\tenant\Setting;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Setting::count() == 0) {
            $configuraciones = [
                [
                   'sucursal_id' => 1,
                   'key' => 'default.type_payment_tocreditsales',
                   'value' => 4,
                ],
                [
                    'sucursal_id' => 1,
                    'key' => 'default.type_payment_to_chash',
                    'value' => 2,
                ],  
                [
                    'sucursal_id' => 1,
                    'key' => 'invoices.invoice_the_sale',
                    'value' => 0,
                ],
                [
                    'sucursal_id' => 1,
                    'key' => 'company.name',
                    'value' => 'Company 1',
                ],
                [
                    'sucursal_id' => 1,
                    'key' => 'company.address',
                    'value' => 'Address..',
                ],
                [
                    'sucursal_id' => 1,
                    'key' => 'company.nit',
                    'value' => null,
                ],
            ];
    
            foreach ($configuraciones as $config) {
                Setting::create($config);
             }
        }
    }
}
