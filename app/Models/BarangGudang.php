<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangGudang extends Model
{
    use HasFactory;
    
    protected $table = 'barang_gudang';

    protected $fillable = [
        'master_barang_id',
        'penempatan',
        'stok_sistem_barang',
        'stok_fisik_barang',
        'keterangan',
    ];

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'master_barang_id');
    }

    public function itemDikeluarkan()
    {
        return $this->hasMany(PengeluaranBarangGudang::class, 'barang_gudang_id')->whereHas('pengeluaranGudang', function($query) {
            $query->where('status', 'dikeluarkan');
        });
    }

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'barang_gudang_id');
    }
}