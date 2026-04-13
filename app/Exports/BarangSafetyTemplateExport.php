<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangSafetyTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['kode_barang', 'jumlah', 'keterangan'];
    }

    public function array(): array
    {
        return [
            ['SAFE-001', 5, 'Pengadaan awal']
        ];
    }
}