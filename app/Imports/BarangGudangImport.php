<?php

namespace App\Imports;

use App\Models\BarangGudang;
use App\Models\MasterBarang;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangGudangImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

            foreach ($rows as $index => $row) {

                if (
                    empty($row['kode_barang']) ||
                    empty($row['jumlah'])
                ) {
                    continue;
                }

                $kodeBarang = trim($row['kode_barang']);
                $jumlah     = (int) $row['jumlah'];

                if ($jumlah <= 0) {
                    throw new \Exception(
                        "Jumlah harus lebih dari 0 (baris ke-" . ($index + 2) . ")"
                    );
                }

                $masterBarang = MasterBarang::where('kode_barang', $kodeBarang)
                    ->whereHas('kategoriBarang', function ($q) {
                        $q->where('nama_kategori', 'Material Instalasi');
                    })
                    ->first();

                if (!$masterBarang) {
                    throw new \Exception(
                        "Master barang '{$kodeBarang}' tidak ditemukan atau bukan Material Instalasi (baris ke-" . ($index + 2) . ")"
                    );
                }

                $barangGudang = BarangGudang::firstOrCreate(
                    ['master_barang_id' => $masterBarang->id],
                    [
                        'stok_sistem_barang' => 0,
                        'stok_fisik_barang'  => 0,
                        'penempatan'         => !empty($row['penempatan']) ? trim($row['penempatan']) : '-',
                        'keterangan'         => !empty($row['keterangan']) ? trim($row['keterangan']) : null,
                    ]
                );

                $barangGudang->stok_sistem_barang += $jumlah;
                $barangGudang->stok_fisik_barang  += $jumlah;

                if (!empty($row['penempatan'])) {
                    $barangGudang->penempatan = trim($row['penempatan']);
                }

                if (!empty($row['keterangan'])) {
                    $barangGudang->keterangan = trim($row['keterangan']);
                }

                $barangGudang->save();
            }

        });
    }
}