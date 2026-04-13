<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranGudang extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans_gudang';
    protected $fillable = [
        'customer_id',
        'pic_id',
        'returner_id',
        'tanggal_keluar',
        'status',
        'tanggal_kembali',
        'keterangan',
        'bukti_keluar',
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
        return $this->belongsToMany(BarangGudang::class, 'pengeluaran_barang_gudang', 'pengeluaran_gudang_id', 'barang_gudang_id')->withPivot('jumlah_keluar'); 
    }
    
    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'pengeluaran_gudang_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}