<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MasterBarangTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'kategori_barang',
            'kode_barang',
            'nama_barang',
            'satuan',
            'tanggal_beli',
        ];
    }    

    public function array(): array
    {
        return [
            [
                'Engineer',
                'BRG-001',
                'Inverter 5kW',
                'Unit',
                '2025-01-01'
            ]
        ];
    }
}