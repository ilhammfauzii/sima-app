<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SLA;
use App\Models\Customer;
use App\Models\MasterData;

class MigrateSlaCustomer extends Command
{
    protected $signature = 'app:migrate-sla-customer';

    protected $description = 'Migrasi data SLA dari master_data ke customers';

    public function handle()
    {
        $slas = SLA::all();

        foreach ($slas as $sla) {
            if (!$sla->nama_customer_id) {
                $this->warn("SLA ID {$sla->id} tidak memiliki nama_customer_id");
                continue;
            }

            $masterCustomer = MasterData::find($sla->nama_customer_id);

            if (!$masterCustomer) {
                $this->warn("SLA ID {$sla->id}: master customer tidak ditemukan");
                continue;
            }

            $customer = Customer::firstOrCreate(
                ['nama_customer' => $masterCustomer->data_master]
            );

            $sla->update([
                'customer_id' => $customer->id
            ]);

            $this->info("SLA ID {$sla->id} → Customer {$customer->nama_customer}");
        }

        $this->info('Migrasi customer_id ke SLA selesai');
    }
}