<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangSafety extends Model
{
    use HasFactory;
    
    protected $table = 'barang_safety';

    protected $fillable = [
        'master_barang_id',
        'stok_sistem_barang',
        'stok_fisik_barang',
        'keterangan',
    ];

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'master_barang_id');
    }

    public function itemDipinjam()
    {
        return $this->hasMany(PengeluaranBarangSafety::class, 'barang_safety_id')->whereHas('pengeluaranSafety', function($query) {
            $query->where('status', 'dipinjam');
        });
    }

    public function barangLenyap()
    {
        return $this->hasMany(BarangLenyap::class, 'barang_safety_id');
    }
}