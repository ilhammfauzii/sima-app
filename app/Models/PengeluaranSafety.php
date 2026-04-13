<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranSafety extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans_safety';
    protected $fillable = [
        'customer_id',
        'user_id',
        'pic_id',
        'returner_id',
        'tanggal_keluar',
        'status',
        'tanggal_kembali',
        'keterangan',
        'bukti_pinjam',
        'bukti_kembali',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function returner()
    {
        return $this->belongsTo(User::class, 'returner_id');
    }

    public function items()
    {
        return $this->belongsToMany(BarangSafety::class, 'pengeluaran_barang_safety', 'pengeluaran_safety_id', 'barang_safety_id')->withPivot('jumlah_keluar'); 
    }
    
    public function barangLenyap()
    {
        return $this->hasMany(BarangLenyap::class, 'pengeluaran_safety_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}