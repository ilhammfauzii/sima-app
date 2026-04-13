<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'nama'          => 'Super Admin',
                'nomor_pegawai' => 'P0000',
                'email'         => 'sisteminventarismanajemenaset@gmail.com',
                'password'      => Hash::make('superadmin'),
                'id_roles'      => 1,
                'jabatan'       => 'Administrator',
                'departemen_id' => 5,
            ],
            [
                'nama'          => 'Pegawai 1',
                'nomor_pegawai' => 'P0001',
                'email'         => 'simapegawai@gmail.com',
                'password'      => Hash::make('pegawai1'),
                'id_roles'      => 2,
                'jabatan'       => 'HEAD OF ENGINEER',
                'departemen_id' => 5,
            ],
            [
                'nama'          => 'Pegawai 2',
                'nomor_pegawai' => 'P0002',
                'email'         => 'simapegawai+2@gmail.com',
                'password'      => Hash::make('pegawai2'),
                'id_roles'      => 2,
                'jabatan'       => 'STAFF ENGINEER',
                'departemen_id' => 5,
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert(array_merge($user, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}