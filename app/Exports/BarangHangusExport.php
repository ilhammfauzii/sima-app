<?php

namespace App\Exports;

use App\Models\BarangHangus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class BarangHangusExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BarangHangus::with('barangEngineer', 'pengeluaran')
            ->get()
            ->map(function ($item) {
                return [
                    'Nama Peminjam'     => $item->pengeluaran->user->nama,
                    'Kode Barang'       => $item->barangEngineer->masterBarang->kode_barang,
                    'Nama Barang'       => $item->barangEngineer->masterBarang->nama_barang,
                    'Jumlah'            => $item->jumlah,
                    'Satuan'            => $item->barangEngineer->masterBarang->satuan,
                    'Alasan'            => $item->alasan,
                    'Keterangan'        => $item->pengeluaran->keterangan,
                    'Tanggal'    => Carbon::parse($item->created_at)->format('d M Y'),
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