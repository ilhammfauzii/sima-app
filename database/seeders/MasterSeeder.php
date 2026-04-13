<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        $masters = [
            'Departemen',
            'PIC',
            'Service Type',
            'Customer',
        ];

        foreach ($masters as $master) {
            DB::table('master')->insert([
                'nama_master' => $master,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}