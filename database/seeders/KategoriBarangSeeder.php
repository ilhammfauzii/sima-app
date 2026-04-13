<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriBarangSeeder extends Seeder
{
    public function run(): void
    {
        $kategori_barang = [
            'Engineer',
            'Material Instalasi',
            'Safety'
        ];

        foreach ($kategori_barang as $nama) {
            DB::table('kategori_barang')->insert([
                'nama_kategori' => $nama,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}