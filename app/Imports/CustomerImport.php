<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama_customer'])) {
            return null;
        }

        $customerName = trim($row['nama_customer']);

        $marketing = null;
        if (!empty($row['marketing'])) {
            $marketing = User::whereRaw(
                'LOWER(nama) LIKE ?',
                ['%' . strtolower(trim($row['marketing'])) . '%']
            )->first();
        }

        $customer = Customer::whereRaw(
            'LOWER(nama_customer) = ?',
            [strtolower($customerName)]
        )->first();

        $data = [
            'nama_customer' => $customerName,
            'no_telp' => $row['no_telp'] ?? null,
            'nik' => $row['nik'] ?? null,
            'npwp' => $row['npwp'] ?? null,
            'alamat_lengkap' => $row['alamat_lengkap'] ?? null,
            'id_pln' => $row['id_pln'] ?? null,
            'marketing_id' => $marketing?->id,
            'referensi_reseller' => $row['referensi_reseller'] ?? null,
        ];

        if ($customer) {
            $customer->update($data);
            return null;
        }

        return Customer::create($data);
    }
}