<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $departemenId = DB::table('master')->where('nama_master', 'Departemen')->value('id');

        $departemens = ['Business Development', 'Drafter', 'Sales & marketing', 'HR & GA', 'Engineering', 'Finance', 'Procurement', 'General Manager', 'Director', 'Commisioner'];

        foreach ($departemens as $dep) {
            DB::table('master_data')->insert([
                'master_id'   => $departemenId,
                'data_master' => $dep,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $serviceTypeId = DB::table('master')->where('nama_master', 'Service Type')->value('id');

        $serviceTypes = ['Leads Info', 'Kontrak', 'LHS', 'DED', 'BOQ', 'Proposal', 'Instalasi', 'Comissioning', 'Perizinan', 'Invoice Penagihan', 'Maintenance'];

        foreach ($serviceTypes as $type) {
            DB::table('master_data')->insert([
                'master_id'   => $serviceTypeId,
                'data_master' => $type,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}