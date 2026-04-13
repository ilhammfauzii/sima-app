<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans';
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
        return $this->belongsToMany(BarangEngineer::class, 'pengeluaran_barang_engineer', 'pengeluaran_id', 'barang_engineer_id')->withPivot('jumlah_keluar'); 
    }
    
    public function barangHangus()
    {
        return $this->hasMany(BarangHangus::class, 'pengeluaran_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}