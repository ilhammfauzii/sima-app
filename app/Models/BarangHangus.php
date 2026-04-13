<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangHangus extends Model
{
    use HasFactory;

    protected $table = 'barang_hangus'; 

    protected $fillable = [
        'pengeluaran_id',
        'barang_engineer_id',
        'jumlah',
        'alasan',
    ];

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'pengeluaran_id');
    }

    public function barangEngineer()
    {
        return $this->belongsTo(BarangEngineer::class, 'barang_engineer_id');
    }
}