<?php

namespace App\Exports;

use App\Models\BarangLenyap;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class BarangLenyapExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BarangLenyap::with('barangSafety', 'pengeluaranSafety')
            ->get()
            ->map(function ($item) {
                return [
                    'Nama Peminjam'     => $item->pengeluaranSafety->user->nama,
                    'Kode Barang'       => $item->barangSafety->masterBarang->kode_barang,
                    'Nama Barang'       => $item->barangSafety->masterBarang->nama_barang,
                    'Jumlah'            => $item->jumlah,
                    'Satuan'            => $item->barangSafety->masterBarang->satuan,
                    'Alasan'            => $item->alasan,
                    'Keterangan'        => $item->pengeluaranSafety->keterangan,
                    'Tanggal Lenyap'    => Carbon::parse($item->created_at)->format('d M Y'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Peminjam',
            'Kode Barang',
            'Nama Barang',
            'Jumlah',
            'Satuan',
            'Alasan',
            'Keterangan',
            'Tanggal',
        ];
    }
}