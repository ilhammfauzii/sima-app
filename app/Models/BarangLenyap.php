<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangLenyap extends Model
{
    use HasFactory;

    protected $table = 'barang_lenyap'; 

    protected $fillable = [
        'pengeluaran_safety_id',
        'barang_safety_id',
        'jumlah',
        'alasan',
    ];

    public function pengeluaranSafety()
    {
        return $this->belongsTo(PengeluaranSafety::class, 'pengeluaran_safety_id');
    }

    public function barangSafety()
    {
        return $this->belongsTo(BarangSafety::class, 'barang_safety_id');
    }
}