<?php

namespace App\Exports;

use App\Models\SLA;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SLAExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = SLA::with(['customer', 'departemen', 'pic', 'serviceType'])
            ->orderBy('id', 'ASC');

        if ($this->request->pic_filter) {
            $query->where('PIC_id', $this->request->pic_filter);
        }

        if ($this->request->customer_filter) {
            $query->where('customer_id', $this->request->customer_filter);
        }

        if ($this->request->filled('status') && $this->request->status !== 'ALL') {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('date_from')) {
            $query->whereDate('start', '>=', $this->request->date_from);
        }

        if ($this->request->filled('date_to')) {
            $query->whereDate('start', '<=', $this->request->date_to);
        }

        return $query->get()->map(function ($sla, $index) {
            $durasi = '-';

            if ($sla->start && $sla->finish) {
                $durasi = Carbon::parse($sla->start)
                    ->diffInDays(Carbon::parse($sla->finish)) + 1 . ' hari';
            }

            return [
                $index + 1,
                $sla->customer->nama_customer ?? '-',
                $sla->lokasi ?? '-',
                $sla->departemen->data_master ?? '-',
                $sla->pic->nama ?? '-',
                $sla->serviceType->data_master ?? '-',
                $sla->keterangan ?? '-',
                $sla->deadline
                    ? Carbon::parse($sla->deadline)->format('d-m-Y')
                    : '-',
                $sla->start
                    ? Carbon::parse($sla->start)->format('d-m-Y')
                    : '-',
                $sla->finish
                    ? Carbon::parse($sla->finish)->format('d-m-Y')
                    : '-',
                $durasi,
                $sla->status ?? '-',
                $sla->file ?? '-',
                $sla->problem ?? '-',
            ];

        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Customer',
            'Lokasi',
            'Departemen',
            'PIC',
            'Layanan',
            'Keterangan',
            'Deadline',
            'Mulai',
            'Selesai',
            'Durasi',
            'Status',
            'Link/File',
            'Masalah',
        ];
    }
}