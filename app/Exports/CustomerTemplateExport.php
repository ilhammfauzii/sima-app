<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'nama_customer',
            'no_telp',
            'nik',
            'npwp',
            'alamat_lengkap',
            'id_pln',
            'marketing',
            'referensi_reseller',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Template',
                '08123456789',
                '',
                '',
                'Rembang, Jawa Tengah',
                '1234567890',
                'Nama Marketing',
                'Referensi A'
            ]
        ];
    }
}