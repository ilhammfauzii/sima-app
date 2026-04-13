<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PengeluaranBarangSafety extends Pivot
{
    use HasFactory;

    protected $table = 'pengeluaran_barang_safety';
    protected $fillable = ['pengeluaran_safety_id', 'barang_safety_id', 'jumlah_keluar'];

    public function pengeluaranSafety()
    {
        return $this->belongsTo(PengeluaranSafety::class);
    }

    public function barangSafety()
    {
        return $this->belongsTo(BarangSafety::class, 'barang_safety_id');
    }
}