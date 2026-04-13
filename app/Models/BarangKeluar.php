<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar'; 

    protected $fillable = [
        'pengeluaran_gudang_id',
        'barang_gudang_id',
        'jumlah',
        'alasan',
    ];

    public function pengeluaranGudang()
    {
        return $this->belongsTo(PengeluaranGudang::class, 'pengeluaran_gudang_id');
    }

    public function barangGudang()
    {
        return $this->belongsTo(BarangGudang::class, 'barang_gudang_id');
    }
}