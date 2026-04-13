<?php

namespace App\Imports;

use App\Models\MasterBarang;
use App\Models\BarangEngineer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangEngineerImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['kode_barang']) || empty($row['jumlah'])) {
            return null;
        }

        $masterBarang = MasterBarang::where('kode_barang', trim($row['kode_barang']))
            ->whereHas('kategoriBarang', fn ($q) => $q->where('nama_kategori', 'Engineer'))
            ->first();

        if (!$masterBarang) {
            throw new \Exception("Barang engineer '{$row['kode_barang']}' tidak ditemukan");
        }

        $barangEngineer = BarangEngineer::firstOrNew([
            'master_barang_id' => $masterBarang->id
        ]);

        if (!$barangEngineer->exists) {
            $barangEngineer->stok_sistem_barang = 0;
            $barangEngineer->stok_fisik_barang = 0;
        }

        $barangEngineer->stok_sistem_barang += (int) $row['jumlah'];
        $barangEngineer->stok_fisik_barang += (int) $row['jumlah'];
        $barangEngineer->keterangan = $row['keterangan'] ?? $barangEngineer->keterangan;

        $barangEngineer->save();

        return $barangEngineer;
    }
}