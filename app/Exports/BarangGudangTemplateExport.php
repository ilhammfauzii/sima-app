<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangGudangTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'kode_barang',
            'jumlah',
            'penempatan',
            'keterangan'
        ];
    }

    public function array(): array
    {
        return [
            ['MI-001', 10, 'Gudang Rak A', 'Pengadaan awal']
        ];
    }
}