<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\KategoriBarang;
use App\Imports\MasterBarangImport;
use App\Exports\MasterBarangTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class MasterBarangController extends Controller
{

    public function index()
    {
        $masterBarang = MasterBarang::with('kategoriBarang')->orderBy('id')->paginate(10);
        return view('master_barang.index', compact('masterBarang'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $masterBarang = MasterBarang::with('kategoriBarang')
            ->when($query, function ($q) use ($query) {
                $q->where('nama_barang', 'like', "%{$query}%")
                ->orWhere('kode_barang', 'like', "%{$query}%");
            })
            ->orderBy('id')
            ->paginate(10);

        return view('master_barang.index', compact('masterBarang'));
    }

    public function create()
    {
        $kategoris = KategoriBarang::all();
        return view('master_barang.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_barang_id' => 'required|exists:kategori_barang,id',
            'kode_barang' => 'required|string|max:255|unique:master_barang,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'tanggal_beli' => 'required|date', 
        ]);

        MasterBarang::create([
            'kategori_barang_id' => $request->kategori_barang_id,
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
            'tanggal_beli' => $request->tanggal_beli, 
        ]);

        return redirect()->route('master_barang.index')->with('success', 'Master Barang berhasil ditambahkan!');
    }

    public function edit($encryptedId)
    {
        Gate::authorize('manage-barang');

        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $masterBarang = MasterBarang::findOrFail($id);
        $kategoris = KategoriBarang::all();

        return view('master_barang.edit', compact('masterBarang', 'kategoris'));
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $masterBarang = MasterBarang::findOrFail($id);

        $request->validate([
            'kategori_barang_id' => 'required|exists:kategori_barang,id',
            'kode_barang' => 'required|string|max:255|unique:master_barang,kode_barang,' . $masterBarang->id,
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'tanggal_beli' => 'required|date',
        ]);

        $masterBarang->update([
            'kategori_barang_id' => $request->kategori_barang_id,
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
            'tanggal_beli' => $request->tanggal_beli,
        ]);

        return redirect()->route('master_barang.index')->with('success', 'Master Barang berhasil diperbarui!');
    }

    public function destroy(MasterBarang $masterBarang)
    {
        $masterBarang->delete();

        return redirect()->route('master_barang.index')->with('success', 'Master Barang berhasil dihapus!');
    }

    public function importForm()
    {
        return view('master_barang.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new MasterBarangImport, $request->file('file'));

        return redirect()->route('master_barang.index')
            ->with('success', 'Master barang berhasil diimport');
    }

    public function template()
    {
        return Excel::download(
            new MasterBarangTemplateExport,
            'template_master_barang.xlsx'
        );
    }

}