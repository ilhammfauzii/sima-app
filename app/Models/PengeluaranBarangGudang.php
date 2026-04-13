<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PengeluaranBarangGudang extends Pivot
{
    use HasFactory;

    protected $table = 'pengeluaran_barang_gudang';
    protected $fillable = ['pengeluaran_gudang_id', 'barang_gudang_id', 'jumlah_keluar'];

    public function pengeluaranGudang()
    {
        return $this->belongsTo(PengeluaranGudang::class);
    }

    public function barangGudang()
    {
        return $this->belongsTo(BarangGudang::class, 'barang_gudang_id');
    }
}