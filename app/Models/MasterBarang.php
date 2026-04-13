<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBarang extends Model
{
    use HasFactory;

    protected $table = 'master_barang';

    protected $fillable = [
        'kategori_barang_id',
        'kode_barang',
        'nama_barang',
        'tanggal_beli',
        'satuan',
    ];

    public function kategoriBarang()
    {
        return $this->belongsTo(KategoriBarang::class);
    }

    public function barangEngineer()
    {
        return $this->hasOne(BarangEngineer::class, 'master_barang_id');
    }

    public function barangGudang()
    {
        return $this->hasOne(BarangGudang::class, 'master_barang_id');
    }

    public function barangSafety()
    {
        return $this->hasOne(BarangSafety::class, 'master_barang_id');
    }
}