<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\BarangEngineer;
use App\Models\BarangHangus;
use App\Exports\BarangEngineerExport;
use App\Exports\BarangHangusExport;
use App\Imports\BarangEngineerImport;
use App\Exports\BarangEngineerTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class BarangEngineerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        Gate::authorize('view-barang');

        $barang_engineer = BarangEngineer::with(['masterBarang.kategoriBarang'])->orderBy('id')->paginate(10);

        $baranghangus = BarangHangus::with('barangEngineer.masterBarang', 'pengeluaran')->latest()->paginate(10);

        return view('barang_engineer.index', compact('barang_engineer', 'baranghangus'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $barang_engineer = BarangEngineer::with(['masterBarang.kategoriBarang'])->when($query, function ($q) use ($query) {
            $q->whereHas('masterBarang', function ($sub) use ($query) {
                $sub->where('nama_barang', 'like', "%{$query}%")->orWhere('kode_barang', 'like', "%{$query}%");
            });
        })->orderBy('id')->paginate(10);

        $baranghangus = BarangHangus::with('barangEngineer.masterBarang', 'pengeluaran')->latest()->paginate(10);

        return view('barang_engineer.index', compact('barang_engineer', 'baranghangus'));
    }

    public function create()
    {
        Gate::authorize('manage-barang');

        $master_barang = MasterBarang::whereHas('kategoriBarang', function ($query) {
            $query->where('nama_kategori', 'Engineer');
        })->get();

        return view('barang_engineer.create', compact('master_barang'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-barang');

        $request->validate([
            'master_barang_id' => 'required|exists:master_barang,id',
            'jumlah_tambah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $barangEngineer = BarangEngineer::firstOrNew([
            'master_barang_id' => $request->master_barang_id,
        ]);

        if (!$barangEngineer->exists) {
            $barangEngineer->stok_sistem_barang = 0;
            $barangEngineer->stok_fisik_barang = 0;
        }

        $barangEngineer->stok_sistem_barang += $request->jumlah_tambah;
        $barangEngineer->stok_fisik_barang += $request->jumlah_tambah;
        $barangEngineer->keterangan = $request->keterangan ?? $barangEngineer->keterangan;
        $barangEngineer->save();

        return redirect()
            ->route('barang_engineer.index')
            ->with('success', 'Stok Alat Engineer berhasil ditambahkan!');
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
        $barang_engineer = BarangEngineer::with('masterBarang')->findOrFail($id);

        return view('barang_engineer.edit', compact('barang_engineer'));
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $barang_engineer = BarangEngineer::findOrFail($id);

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

        $barang_engineer->update($data);

        return redirect()
            ->route('barang_engineer.index')
            ->with('success', 'Barang berhasil diupdate.');
    }
    
    public function destroy($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }

        BarangEngineer::destroy($id);

        return redirect()
            ->route('barang_engineer.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    public function downloadExcel()
    {
        return Excel::download(new BarangEngineerExport, 'Data-Alat-Engineer.xlsx');
    }

    public function downloadExcelBarangHangus()
    {
        return Excel::download(new BarangHangusExport, 'Data-Alat-Engineer-Hilang-Rusak.xlsx');
    }

    public function importForm()
    {
        return view('barang_engineer.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        Excel::import(new BarangEngineerImport, $request->file('file'));

        return redirect()->route('barang_engineer.index')->with('success', 'Data alat engineer berhasil diimport');
    }

    public function template()
    {
        return Excel::download(new BarangEngineerTemplateExport, 'template_import_alat_engineer.xlsx');
    }
}