<?php

namespace App\Imports;

use App\Models\BarangSafety;
use App\Models\MasterBarang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangSafetyImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['kode_barang']) || empty($row['jumlah'])) {
            return null;
        }

        $masterBarang = MasterBarang::where('kode_barang', trim($row['kode_barang']))
            ->whereHas('kategoriBarang', function ($q) {
                $q->where('nama_kategori', 'Safety');
            })
            ->first();

        if (!$masterBarang) {
            throw new \Exception(
                "Barang safety dengan kode '{$row['kode_barang']}' tidak ditemukan"
            );
        }

        $barangSafety = BarangSafety::firstOrNew([
            'master_barang_id' => $masterBarang->id
        ]);

        if (!$barangSafety->exists) {
            $barangSafety->stok_sistem_barang = 0;
            $barangSafety->stok_fisik_barang = 0;
        }

        $jumlah = (int) $row['jumlah'];

        $barangSafety->stok_sistem_barang += $jumlah;
        $barangSafety->stok_fisik_barang += $jumlah;
        $barangSafety->keterangan = $row['keterangan'] ?? $barangSafety->keterangan;

        $barangSafety->save();

        return null;
    }
}