<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'nama_customer',
        'no_telp',
        'nik',
        'npwp',
        'alamat_lengkap',
        'id_pln',
        'marketing_id',
        'referensi_reseller',
    ];

    public function slas()
    {
        return $this->hasMany(SLA::class);
    }

    public function marketing()
    {
        return $this->belongsTo(User::class, 'marketing_id');
    }

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function pengeluaransGudang()
    {
        return $this->hasMany(PengeluaranGudang::class);
    }

    public function pengeluaransSafety()
    {
        return $this->hasMany(PengeluaranSafety::class);
    }
}