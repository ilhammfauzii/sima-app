<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            [
                'nama_roles' => 'Super Admin',
                'deskripsi' => 'Kontrol penuh atas sistem, termasuk manajemen user dan roles',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_roles' => 'Admin',
                'deskripsi' => 'Mengelola sistem inventaris barang',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_roles' => 'User',
                'deskripsi' => 'Pengguna biasa yang dapat menambahkan pengeluaran dan melihat statusnya',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}