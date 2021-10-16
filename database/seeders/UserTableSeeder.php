<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            "name" => "Augusto",
            "email" => "auguss24@gmail.com",
            "role" => "admin",
            "email_verified_at" => now(),
            "password" => bcrypt("password"),
            "remember_token" => Str::random(10),
        ]);

        \App\Models\User::create([
            "name" => "Mariana Elba",
            "email" => "mari@gmail.com",
            "role" => "tenancy",
            "email_verified_at" => now(),
            "password" => bcrypt("password"),
            "remember_token" => Str::random(10),
        ]);
    }
}
