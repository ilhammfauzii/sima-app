<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\BarangGudang;
use App\Models\BarangKeluar;
use App\Exports\BarangGudangExport;
use App\Exports\BarangKeluarExport;
use App\Imports\BarangGudangImport;
use App\Exports\BarangGudangTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class BarangGudangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        Gate::authorize('view-barang');

        $barang_gudang = BarangGudang::with(['masterBarang.kategoriBarang'])->orderBy('id')->paginate(10);
        
        $barangkeluar = BarangKeluar::with('barangGudang.masterBarang', 'pengeluaranGudang')->latest()->paginate(10);
        
        return view('barang_gudang.index', compact('barang_gudang', 'barangkeluar'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $barang_gudang = BarangGudang::with(['masterBarang.kategoriBarang'])->when($query, function ($q) use ($query) {
            $q->whereHas('masterBarang', function ($sub) use ($query) {
                $sub->where('nama_barang', 'like', "%{$query}%")->orWhere('kode_barang', 'like', "%{$query}%");
            });
        })->orderBy('id')->paginate(10);

        $barangkeluar = BarangKeluar::with('barangGudang.masterBarang', 'pengeluaranGudang')->latest()->paginate(10);

        return view('barang_gudang.index', compact('barang_gudang', 'barangkeluar'));
    }

    public function create()
    {
        Gate::authorize('manage-barang');

        $master_barang = MasterBarang::whereHas('kategoriBarang', function($query) {
            $query->where('nama_kategori', 'Material Instalasi');
        })->get();
        
        return view('barang_gudang.create', compact('master_barang'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-barang');
        $request->validate([
            'master_barang_id' => 'required|exists:master_barang,id',
            'jumlah_tambah' => 'required|integer|min:1',
            'penempatan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $barangGudang = BarangGudang::firstOrNew(['master_barang_id' => $request->master_barang_id]);

        if (!$barangGudang->exists) {
            $barangGudang->stok_sistem_barang = 0;
            $barangGudang->stok_fisik_barang = 0;
        }

        $barangGudang->stok_sistem_barang += $request->jumlah_tambah;
        $barangGudang->stok_fisik_barang += $request->jumlah_tambah;
        $barangGudang->penempatan = $request->penempatan;
        $barangGudang->keterangan = $request->keterangan ?? $barangGudang->keterangan;
        $barangGudang->save();

        return redirect()->route('barang_gudang.index')->with('success', 'Stok Material Instalasi berhasil ditambahkan!');
    }

    public function edit($encryptedId)
    {
        if (
            !Gate::allows('edit-barang-full') &&
            !Gate::allows('edit-barang-terbatas')
        ) {
            abort(403);
        }

        $id = Crypt::decrypt($encryptedId);
        $barang_gudang = BarangGudang::with('masterBarang')->findOrFail($id);

        return view('barang_gudang.edit', compact('barang_gudang'));
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $barang_gudang = BarangGudang::findOrFail($id);

        if (Gate::allows('edit-barang-full')) {
            $data = $request->validate([
                'stok_sistem_barang' => 'required|integer',
                'stok_fisik_barang'  => 'required|integer',
                'penempatan' => 'nullable|string|max:255',
                'keterangan'         => 'nullable|string|max:255',
            ]);
        }

        elseif (Gate::allows('edit-barang-terbatas')) {
            $data = $request->validate([
                'penempatan' => 'nullable|string|max:255',
                'keterangan' => 'nullable|string|max:255',
            ]);
        }

        else {
            abort(403, 'Anda tidak memiliki izin mengedit barang.');
        }

        $barang_gudang->update($data);

        return redirect()
            ->route('barang_gudang.index')
            ->with('success', 'Barang berhasil diupdate.');
    }

    public function destroy($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }

        BarangGudang::destroy($id);
        return redirect()->route('barang_gudang.index')->with('success', 'Barang berhasil dihapus');
    }

    public function downloadExcel()
    {
        return Excel::download(new BarangGudangExport, 'Data-Barang-Gudang.xlsx');
    }

    public function downloadExcelBarangKeluar()
    {
        return Excel::download(new BarangKeluarExport, 'Data-Material-Instalasi-Keluar-Gudang.xlsx');
    }

    public function importForm()
    {
        Gate::authorize('manage-barang');
        return view('barang_gudang.import');
    }

    public function import(Request $request)
    {
        Gate::authorize('manage-barang');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        Excel::import(new BarangGudangImport, $request->file('file'));

        return redirect()->route('barang_gudang.index')->with('success', 'Data Material Instalasi berhasil diimport');
    }

    public function template()
    {
        Gate::authorize('manage-barang');
        return Excel::download(new BarangGudangTemplateExport,'Template-Import-Material-Instalasi.xlsx');
    }
}