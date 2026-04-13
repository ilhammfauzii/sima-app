<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileEnkripsi extends Model
{
    use HasFactory;

    protected $table = 'file_enkripsi';
    
    protected $fillable = [
        'jenis_dokumen',
        'nama_file_asli',
        'path_file_terenkripsi',
        'kunci_enkripsi',
        'ukuran_file',
        'tipe_file',
        'penerima',
        'diupload_oleh',
        'deskripsi',
        'kadaluarsa_pada',
        'kategori',
        'status'
    ];

    protected $casts = [
        'penerima' => 'array',
        'kadaluarsa_pada' => 'datetime'
    ];

    public function diuploadOleh()
    {
        return $this->belongsTo(User::class, 'diupload_oleh');
    }

    public function getLabelKategoriAttribute()
    {
        return $this->kategori == 'rahasia' ? 'Rahasia' : 'Biasa';
    }

    public function getButuhEnkripsiAttribute()
    {
        return $this->kategori === 'rahasia';
    }

    public function getSudahKadaluarsaAttribute()
    {
        if (!$this->kadaluarsa_pada) {
            return false;
        }
        return now()->greaterThan($this->kadaluarsa_pada);
    }

    public function getUkuranFileFormattedAttribute()
    {
        $bytes = $this->ukuran_file;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function getLabelJenisDokumenAttribute()
    {
        $jenis = [
            'surat_peminjaman' => 'Surat Peminjaman Barang',
            'surat_pengeluaran' => 'Surat Pengeluaran Barang', 
            'laporan' => 'Laporan Inventaris',
            'sertifikat' => 'Sertifikat/Kontrak',
            'lainnya' => 'Dokumen Lainnya'
        ];
        
        return $jenis[$this->jenis_dokumen] ?? $this->jenis_dokumen;
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeRahasia($query)
    {
        return $query->where('kategori', 'rahasia');
    }

    public function scopeTidakRahasia($query)
    {
        return $query->where('kategori', 'tidak_rahasia');
    }

    public function scopeUntukUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('diupload_oleh', $userId)->orWhereJsonContains('penerima', ['user_id' => (int) $userId]);
        });
    }

    public function scopeDikirimOleh($query, $userId)
    {
        return $query->where('diupload_oleh', $userId);
    }

    public function scopeDiterimaOleh($query, $userId)
    {
        return $query->whereJsonContains('penerima', ['user_id' => (int) $userId])->where('diupload_oleh', '!=', $userId);
    }
}