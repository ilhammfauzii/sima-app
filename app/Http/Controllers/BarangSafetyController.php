<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\BarangSafety;
use App\Models\BarangLenyap;
use App\Exports\BarangSafetyExport;
use App\Exports\BarangLenyapExport;
use App\Imports\BarangSafetyImport;
use App\Exports\BarangSafetyTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class BarangSafetyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        Gate::authorize('view-barang');
        
        $barang_safety = BarangSafety::with(['masterBarang.kategoriBarang'])->paginate(10);
        
        $baranglenyap = BarangLenyap::with('barangSafety.masterBarang', 'pengeluaranSafety')->latest()->paginate(10);
        
        return view('barang_safety.index', compact('barang_safety', 'baranglenyap'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $barang_safety = BarangSafety::with(['masterBarang.kategoriBarang'])->when($query, function ($q) use ($query) {
            $q->whereHas('masterBarang', function ($sub) use ($query) {
                $sub->where('nama_barang', 'like', "%{$query}%")->orWhere('kode_barang', 'like', "%{$query}%");
            });
        })->orderBy('id')->paginate(10);

        $baranglenyap = BarangLenyap::with('barangSafety.masterBarang', 'pengeluaranSafety')->latest()->paginate(10);

        return view('barang_safety.index', compact('barang_safety', 'baranglenyap'));
    }

    public function create()
    {
        Gate::authorize('manage-barang');

        $master_barang = MasterBarang::whereHas('kategoriBarang', function($query) {
            $query->where('nama_kategori', 'Safety');
        })->get();
        
        return view('barang_safety.create', compact('master_barang'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-barang');
        $request->validate([
            'master_barang_id' => 'required|exists:master_barang,id',
            'jumlah_tambah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $barangSafety = BarangSafety::firstOrNew(['master_barang_id' => $request->master_barang_id]);

        if (!$barangSafety->exists) {
            $barangSafety->stok_sistem_barang = 0;
            $barangSafety->stok_fisik_barang = 0;
        }

        $barangSafety->stok_sistem_barang += $request->jumlah_tambah;
        $barangSafety->stok_fisik_barang += $request->jumlah_tambah;
        $barangSafety->keterangan = $request->keterangan ?? $barangSafety->keterangan;
        $barangSafety->save();

        return redirect()->route('barang_safety.index')->with('success', 'Stok Alat Safety berhasil ditambahkan!');
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
        $barang_safety = BarangSafety::with('masterBarang')->findOrFail($id);

        return view('barang_safety.edit', compact('barang_safety'));
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $barang_safety = BarangSafety::findOrFail($id);

        if (Gate::allows('edit-barang-full')) {
            $data = $request->validate([
                'stok_sistem_barang' => 'required|integer',
                'stok_fisik_barang'  => 'required|integer',
                'keterangan'         => 'nullable|string|max:255',
            ]);
        }

        elseif (Gate::allows('edit-barang-terbatas')) {
            $data = $request->validate([
                'keterangan' => 'nullable|string|max:255',
            ]);
        }

        else {
            abort(403, 'Anda tidak memiliki izin mengedit barang.');
        }

        $barang_safety->update($data);

        return redirect()
            ->route('barang_safety.index')
            ->with('success', 'Barang berhasil diupdate.');
    }

    public function destroy($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }

        BarangSafety::destroy($id);
        return redirect()->route('barang_safety.index')->with('success', 'Barang berhasil dihapus');
    }

    public function downloadExcel()
    {
        return Excel::download(new BarangSafetyExport, 'Data-Alat-Safety.xlsx');
    }

    public function downloadExcelBarangLenyap()
    {
        return Excel::download(new BarangLenyapExport, 'Data-Alat-Safety-Hilang-Rusak.xlsx');
    }

    public function importForm()
    {
        Gate::authorize('manage-barang');
        return view('barang_safety.import');
    }

    public function import(Request $request)
    {
        Gate::authorize('manage-barang');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new BarangSafetyImport, $request->file('file'));

        return redirect()->route('barang_safety.index')->with('success', 'Data alat safety berhasil diimport');
    }

    public function template()
    {
        return Excel::download( new BarangSafetyTemplateExport, 'template_import_alat_safety.xlsx');
    }

}