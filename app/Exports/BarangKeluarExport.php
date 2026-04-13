<?php

namespace App\Exports;

use App\Models\BarangKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class BarangKeluarExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BarangKeluar::with('barangGudang', 'pengeluaranGudang')
            ->get()
            ->map(function ($item) {
                return [
                    'Nama PIC'          => $item->pengeluaranGudang->keterangan->user->nama,
                    'Kode Barang'       => $item->barangGudang->masterBarang->kode_barang,
                    'Nama Barang'       => $item->barangGudang->masterBarang->nama_barang,
                    'Jumlah'            => $item->jumlah,
                    'Satuan'            => $item->barangGudang->masterBarang->satuan,
                    'Alasan'            => $item->alasan,
                    'Keterangan'        => $item->pengeluaranGudang->keterangan,
                    'Tanggal Keluar'    => Carbon::parse($item->created_at)->format('d M Y'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama PIC',
            'Kode Barang',
            'Nama Barang',
            'Jumlah',
            'Satuan',
            'Alasan',
            'Keterangan',
            'Tanggal Keluar',
        ];
    }
}