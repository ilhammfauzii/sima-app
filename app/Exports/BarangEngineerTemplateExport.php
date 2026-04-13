<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangEngineerTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['kode_barang', 'jumlah', 'keterangan'];
    }

    public function array(): array
    {
        return [
            ['ENG-001', 10, 'Pengadaan awal']
        ];
    }
}