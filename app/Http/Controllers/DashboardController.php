<?php

namespace App\Http\Controllers;

use App\Models\BarangEngineer;
use App\Models\BarangGudang;
use App\Models\BarangSafety;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $total_engineer_stock = BarangEngineer::sum('stok_fisik_barang');
        $total_gudang_stock   = BarangGudang::sum('stok_fisik_barang');
        $total_safety_stock   = BarangSafety::sum('stok_fisik_barang');

        $outgoing_engineer = DB::table('pengeluaran_barang_engineer')->join('pengeluarans', 'pengeluaran_barang_engineer.pengeluaran_id', '=', 'pengeluarans.id')->where('pengeluarans.status', 'dipinjam')->sum('pengeluaran_barang_engineer.jumlah_keluar');

        $outgoing_safety = DB::table('pengeluaran_barang_safety')->join('pengeluarans_safety', 'pengeluaran_barang_safety.pengeluaran_safety_id', '=', 'pengeluarans_safety.id')->where('pengeluarans_safety.status', 'dipinjam')->sum('pengeluaran_barang_safety.jumlah_keluar');

        $outgoing_gudang = DB::table('pengeluaran_barang_gudang')->join('pengeluarans_gudang', 'pengeluaran_barang_gudang.pengeluaran_gudang_id', '=', 'pengeluarans_gudang.id')->where('pengeluarans_gudang.status', 'dikeluarkan')->sum('pengeluaran_barang_gudang.jumlah_keluar');

        return view('dashboard', compact(
            'total_engineer_stock',
            'total_gudang_stock',
            'total_safety_stock',
            'outgoing_engineer',
            'outgoing_gudang',
            'outgoing_safety'
        ));
    }
}