<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangEngineer extends Model
{
    use HasFactory;
    
    protected $table = 'barang_engineer';

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
        return $this->hasMany(PengeluaranBarangEngineer::class, 'barang_engineer_id')->whereHas('pengeluaran', function($query) {
            $query->where('status', 'dipinjam');
        });
    }

    public function barangHangus()
    {
        return $this->hasMany(BarangHangus::class, 'barang_engineer_id');
    }
}