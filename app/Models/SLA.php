<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SLA extends Model
{
    use HasFactory;

    protected $table = 'sla';

    protected $fillable = [
        'customer_id',
        'departemen_id',
        'service_type_id',
        'PIC_id',
        'lokasi',
        'keterangan',
        'deadline',
        'start',
        'finish',
        'status',
        'file',
        'problem',
    ];

    protected $casts = [
        'deadline' => 'date',
        'start'    => 'date',
        'finish'   => 'date',
    ];

    public function getDurasiAttribute()
    {
        if ($this->start && $this->finish) {
            return $this->start
                ->diffInDays($this->finish) + 1 . ' hari';
        }

        return '-';
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function departemen()
    {
        return $this->belongsTo(MasterData::class, 'departemen_id');
    }

    public function serviceType()
    {
        return $this->belongsTo(MasterData::class, 'service_type_id');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'PIC_id');
    }
}