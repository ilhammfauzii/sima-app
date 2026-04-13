<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            MasterSeeder::class,
            MasterDataSeeder::class,
            KategoriBarangSeeder::class,
            UserSeeder::class,
        ]);
    }
}
