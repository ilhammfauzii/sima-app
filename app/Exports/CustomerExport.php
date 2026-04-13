<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'Nama Customer',
            'No Telp',
            'NIK',
            'NPWP',
            'Alamat Lengkap',
            'ID PLN',
            'Marketing',
            'Referensi Reseller',
            'Created At',
        ];
    }

    public function collection()
    {
        return Customer::with('marketing')
            ->get()
            ->map(function ($customer) {
                return [
                    $customer->nama_customer,
                    $customer->no_telp,
                    $customer->nik,
                    $customer->npwp,
                    $customer->alamat_lengkap,
                    $customer->id_pln,
                    optional($customer->marketing)->nama,
                    $customer->referensi_reseller,
                    $customer->created_at,
                ];
            });
    }
}