<?php

namespace App\Exports;

use App\Models\BarangSafety;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class BarangSafetyExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        $barang_engineer = BarangSafety::with('masterBarang')->get();
        
        return $barang_engineer->map(function($be) {
            return [
                'Kode Barang' => $be->masterBarang->kode_barang,
                'Nama Barang' => $be->masterBarang->nama_barang,
                'Stok Sistem' => $be->stok_sistem_barang,
                'Stok Fisik' => $be->stok_fisik_barang,
                'Satuan' => $be->masterBarang->satuan,
                'Keterangan' => $be->keterangan,
                'Terakhir Update' => Carbon::parse($be->updated_at)->format('d M Y')
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Barang',
            'Nama Barang',
            'Stok Sistem',
            'Stok Fisik',
            'Satuan',
            'Keterangan',
            'Terakhir Update'
        ];
    }
}