<?php

namespace App\Imports;

use App\Models\MasterBarang;
use App\Models\KategoriBarang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class MasterBarangImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (
            empty($row['kategori_barang']) ||
            empty($row['kode_barang']) ||
            empty($row['nama_barang'])
        ) {
            return null;
        }

        $kategori = KategoriBarang::whereRaw(
            'LOWER(nama_kategori) = ?',
            [strtolower(trim($row['kategori_barang']))]
        )->first();

        if (!$kategori) {
            throw new \Exception(
                "Kategori '{$row['kategori_barang']}' tidak ditemukan"
            );
        }

        $tanggalBeli = null;
        if (!empty($row['tanggal_beli'])) {
            if (is_numeric($row['tanggal_beli'])) {
                $tanggalBeli = Carbon::instance(
                    ExcelDate::excelToDateTimeObject($row['tanggal_beli'])
                )->format('Y-m-d');
            } else {
                try {
                    $tanggalBeli = Carbon::parse($row['tanggal_beli'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $tanggalBeli = null;
                }
            }
        }

        return MasterBarang::updateOrCreate(
            [
                'kode_barang' => trim($row['kode_barang']),
            ],
            [
                'kategori_barang_id' => $kategori->id,
                'nama_barang'        => trim($row['nama_barang']),
                'satuan'             => trim($row['satuan'] ?? ''),
                'tanggal_beli'       => $tanggalBeli,
            ]
        );
    }
}
