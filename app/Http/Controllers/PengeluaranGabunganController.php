<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PengeluaranGabunganController extends Controller
{
    public function index()
    {
        return view('pengeluaran.index');
    }

    protected function generateSuratFormat(array $engineerIds, array $gudangIds, array $safetyIds, $tanggal)
    {
        $bulanTahun = Carbon::parse($tanggal)->format('m/Y');

        $parts = [];
        if (!empty($engineerIds)) $parts[] = 'E-' . implode(',', $engineerIds);
        if (!empty($gudangIds))   $parts[] = 'M-' . implode(',', $gudangIds);
        if (!empty($safetyIds))   $parts[] = 'S-' . implode(',', $safetyIds);

        return 'SPB-' . implode('/', $parts) . '/BE/' . $bulanTahun;
    }

    public function cetakGabungan(Request $request)
    {
        $tanggal    = $request->tanggal;
        $keterangan = $request->keterangan;

        $engineer = DB::table('pengeluarans')
            ->leftJoin('customers', 'pengeluarans.customer_id', '=', 'customers.id')
            ->join('pengeluaran_barang_engineer', 'pengeluaran_barang_engineer.pengeluaran_id', '=', 'pengeluarans.id')
            ->join('barang_engineer', 'pengeluaran_barang_engineer.barang_engineer_id', '=', 'barang_engineer.id')
            ->join('master_barang', 'barang_engineer.master_barang_id', '=', 'master_barang.id')
            ->join('kategori_barang', 'master_barang.kategori_barang_id', '=', 'kategori_barang.id')
            ->leftJoin('users as pic', 'pengeluarans.pic_id', '=', 'pic.id')
            ->leftJoin('users as peminjam', 'pengeluarans.user_id', '=', 'peminjam.id')
            ->select(
                'pengeluarans.id as pengeluaran_id',
                'pic.nama as pic_nama',
                'peminjam.nama as peminjam_nama',
                DB::raw("customers.alamat_lengkap as lokasi"),
                'master_barang.kode_barang',
                'master_barang.nama_barang',
                'kategori_barang.nama_kategori as kategori',
                'pengeluaran_barang_engineer.jumlah_keluar'
            )
            ->whereDate('pengeluarans.tanggal_keluar', $tanggal)
            ->where('pengeluarans.keterangan', $keterangan)
            ->get();

        $gudang = DB::table('pengeluarans_gudang')
            ->leftJoin('customers', 'pengeluarans_gudang.customer_id', '=', 'customers.id')
            ->join('pengeluaran_barang_gudang', 'pengeluaran_barang_gudang.pengeluaran_gudang_id', '=', 'pengeluarans_gudang.id')
            ->join('barang_gudang', 'pengeluaran_barang_gudang.barang_gudang_id', '=', 'barang_gudang.id')
            ->join('master_barang', 'barang_gudang.master_barang_id', '=', 'master_barang.id')
            ->join('kategori_barang', 'master_barang.kategori_barang_id', '=', 'kategori_barang.id')
            ->leftJoin('users as pic', 'pengeluarans_gudang.pic_id', '=', 'pic.id')
            ->select(
                'pengeluarans_gudang.id as pengeluaran_id',
                'pic.nama as pic_nama',
                DB::raw("'-' as peminjam_nama"),
                DB::raw("customers.alamat_lengkap as lokasi"),
                'master_barang.kode_barang',
                'master_barang.nama_barang',
                'kategori_barang.nama_kategori as kategori',
                'pengeluaran_barang_gudang.jumlah_keluar'
            )
            ->whereDate('pengeluarans_gudang.tanggal_keluar', $tanggal)
            ->where('pengeluarans_gudang.keterangan', $keterangan)
            ->get();

        $safety = DB::table('pengeluarans_safety')
            ->leftJoin('customers', 'pengeluarans_safety.customer_id', '=', 'customers.id')
            ->join('pengeluaran_barang_safety', 'pengeluaran_barang_safety.pengeluaran_safety_id', '=', 'pengeluarans_safety.id')
            ->join('barang_safety', 'pengeluaran_barang_safety.barang_safety_id', '=', 'barang_safety.id')
            ->join('master_barang', 'barang_safety.master_barang_id', '=', 'master_barang.id')
            ->join('kategori_barang', 'master_barang.kategori_barang_id', '=', 'kategori_barang.id')
            ->leftJoin('users as pic', 'pengeluarans_safety.pic_id', '=', 'pic.id')
            ->leftJoin('users as peminjam', 'pengeluarans_safety.user_id', '=', 'peminjam.id')
            ->select(
                'pengeluarans_safety.id as pengeluaran_id',
                'pic.nama as pic_nama',
                'peminjam.nama as peminjam_nama',
                DB::raw("customers.alamat_lengkap as lokasi"),
                'master_barang.kode_barang',
                'master_barang.nama_barang',
                'kategori_barang.nama_kategori as kategori',
                'pengeluaran_barang_safety.jumlah_keluar'
            )
            ->whereDate('pengeluarans_safety.tanggal_keluar', $tanggal)
            ->where('pengeluarans_safety.keterangan', $keterangan)
            ->get();

        $gabungan = $engineer->concat($gudang)->concat($safety);

        if ($gabungan->isEmpty()) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        $nomorSurat = $this->generateSuratFormat(
            $engineer->pluck('pengeluaran_id')->unique()->toArray(),
            $gudang->pluck('pengeluaran_id')->unique()->toArray(),
            $safety->pluck('pengeluaran_id')->unique()->toArray(),
            $tanggal
        );

        return Pdf::loadView('pengeluaran.cetak_gabungan', [
            'gabungan'      => $gabungan,
            'tanggal'       => $tanggal,
            'keterangan'    => $keterangan,
            'nomor_surat'   => $nomorSurat,
            'lokasi_batch'  => $gabungan->pluck('lokasi')->filter()->first() ?? '-',
            'peminjam'      => $gabungan->pluck('peminjam_nama')->filter()->first() ?? '-',
            'pic_signature' => $gabungan->pluck('pic_nama')->unique()->filter()->implode(' / ')
        ])
        ->setPaper('a4')
        ->setOptions(['isRemoteEnabled' => true])
        ->download('Surat_Pengeluaran_Gabungan.pdf');
    }
}