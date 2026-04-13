<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PengeluaranBarangEngineer extends Pivot
{
    use HasFactory;

    protected $table = 'pengeluaran_barang_engineer';
    protected $fillable = ['pengeluaran_id', 'barang_engineer_id', 'jumlah_keluar'];

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class);
    }

    public function barangEngineer()
    {
        return $this->belongsTo(BarangEngineer::class, 'barang_engineer_id');
    }
}