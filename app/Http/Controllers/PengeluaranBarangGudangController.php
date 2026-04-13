<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranGudang;
use App\Models\BarangGudang;
use App\Models\BarangKeluar;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PengeluaranBarangGudangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function menu()
    {
        return view('pengeluaran_barang_gudang.menu');
    }

    public function index()
    {
        Gate::authorize('view-pengeluaran');
        $pengeluaran_gudang = PengeluaranGudang::with('customer', 'pic', 'items.masterBarang', 'barangkeluar')->latest()->paginate(10);
        $users = User::all();
        return view('pengeluaran_barang_gudang.index', compact('pengeluaran_gudang', 'users'));
    }

    public function create()
    {
        Gate::authorize('create-pengeluaran');
        $customers = Customer::all();
        $users = User::all();
        $barang_gudang = BarangGudang::where('stok_fisik_barang', '>', 0)->with('masterBarang')->get();
        return view('pengeluaran_barang_gudang.create', compact('customers', 'users', 'barang_gudang'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create-pengeluaran');
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'pic_id' => 'required|exists:users,id',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
            'bukti_keluar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'items' => 'required|array|min:1',
            'items.*.barang_gudang_id' => 'required|exists:barang_gudang,id',
            'items.*.jumlah_keluar' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $buktiKeluarPath = null;
            if ($request->hasFile('bukti_keluar')) {
                $file = $request->file('bukti_keluar');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $buktiKeluarPath = $file->storeAs('bukti_keluar', $fileName, 'public');
            }

            $pengeluaran_gudang = PengeluaranGudang::create([
                'customer_id' => $validatedData['customer_id'],
                'pic_id' => $validatedData['pic_id'],
                'tanggal_keluar' => $validatedData['tanggal_keluar'],
                'status' => 'dikeluarkan',
                'keterangan' => $validatedData['keterangan'] ?? null,
                'bukti_keluar' => $buktiKeluarPath,
            ]);

            $syncData = [];
            foreach ($validatedData['items'] as $bg) {
                $barang_gudang = BarangGudang::findOrFail($bg['barang_gudang_id']);

                if ($barang_gudang->stok_fisik_barang < $bg['jumlah_keluar']) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Stok fisik untuk {$barang_gudang->masterBarang->nama_barang} tidak mencukupi.");
                }

                $barang_gudang->decrement('stok_fisik_barang', $bg['jumlah_keluar']);
                $syncData[$barang_gudang->id] = ['jumlah_keluar' => $bg['jumlah_keluar']];
            }

            $pengeluaran_gudang->items()->attach($syncData);

            DB::commit();
            return redirect()->route('pengeluaran_barang_gudang.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing pengeluaran_gudang: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function kembalikan(Request $request, $id)
    {
        Gate::authorize('manage-pengeluaran');
        $pengeluaran_gudang = PengeluaranGudang::with('items.masterBarang')->findOrFail($id);

        if ($pengeluaran_gudang->status !== 'dikeluarkan') {
            return redirect()->route('pengeluaran_barang_gudang.index')->with('error', 'Status pengeluaran tidak valid untuk dikembalikan.');
        }

        $validatedData = $request->validate([
            'items_kembali' => 'required|array|min:1',
            'items_kembali.*.jumlah_kembali' => 'required|integer|min:0',
            'items_kembali.*.alasan' => 'nullable|string',
            'bukti_kembali' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);
        
        DB::beginTransaction();
        try {
            if ($request->hasFile('bukti_kembali')) {
                if ($pengeluaran_gudang->bukti_kembali && Storage::disk('public')->exists($pengeluaran_gudang->bukti_kembali)) {
                    Storage::disk('public')->delete($pengeluaran_gudang->bukti_kembali);
                }
                
                $file = $request->file('bukti_kembali');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $pengeluaran_gudang->bukti_kembali = $file->storeAs('bukti_kembali', $fileName, 'public');
            }
            
            foreach ($pengeluaran_gudang->items as $itemDetail) {
                $inputJumlahKembali = $validatedData['items_kembali'][$itemDetail->id]['jumlah_kembali'] ?? 0;
                $inputAlasan = $validatedData['items_kembali'][$itemDetail->id]['alasan'] ?? null;
                $barang_gudang = $itemDetail;
                $jumlah_dikeluarkan = $itemDetail->pivot->jumlah_keluar;

                if ($inputJumlahKembali > $jumlah_dikeluarkan) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Jumlah yang dikembalikan untuk {$barang_gudang->masterBarang->nama_barang} melebihi jumlah yang dikeluarkan.");
                }
                
                $jumlah_keluar_gudang = $jumlah_dikeluarkan - $inputJumlahKembali;
                $barang_gudang->increment('stok_fisik_barang', $inputJumlahKembali);

                if ($jumlah_keluar_gudang > 0) {
                    $barang_gudang->decrement('stok_sistem_barang', $jumlah_keluar_gudang);
                    BarangKeluar::create([
                        'pengeluaran_gudang_id' => $pengeluaran_gudang->id,
                        'barang_gudang_id' => $barang_gudang->id,
                        'jumlah' => $jumlah_keluar_gudang,
                        'alasan' => $inputAlasan,
                    ]);
                }
            }
            
            $pengeluaran_gudang->tanggal_kembali = now();
            $pengeluaran_gudang->status = 'sudahkeluar';
            $pengeluaran_gudang->save();

            DB::commit();
            return redirect()->route('pengeluaran_barang_gudang.index')->with('success', 'Barang berhasil dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error returning barang: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        Gate::authorize('edit-pengeluaran');

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->route('pengeluaran_barang_gudang.index')->with('error', 'Data pengeluaran tidak valid.');
        }

        $pengeluaran = PengeluaranGudang::with('items.masterBarang')
            ->findOrFail($id);

        if ($pengeluaran->status !== 'dikeluarkan') {
            return redirect()->route('pengeluaran_barang_gudang.index')->with('error', 'Pengeluaran yang sudah dikembalikan tidak dapat diedit.');
        }

        $customers = Customer::all();
        $users = User::all();
        $barang_gudang = BarangGudang::with('masterBarang')->get();

        return view(
            'pengeluaran_barang_gudang.edit',
            compact('pengeluaran', 'customers', 'users', 'barang_gudang')
        );
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('edit-pengeluaran');

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->route('pengeluaran_barang_gudang.index')->with('error', 'Data pengeluaran tidak valid.');
        }

        $pengeluaran = PengeluaranGudang::with('items')->findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($pengeluaran->items as $item) {
                $item->increment('stok_fisik_barang', $item->pivot->jumlah_keluar);
            }

            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'pic_id' => 'required|exists:users,id',
                'tanggal_keluar' => 'required|date',
                'keterangan' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.barang_gudang_id' => 'required|exists:barang_gudang,id',
                'items.*.jumlah_keluar' => 'required|integer|min:1',
            ]);

            $pengeluaran->update([
                'customer_id' => $validated['customer_id'],
                'pic_id' => $validated['pic_id'],
                'tanggal_keluar' => $validated['tanggal_keluar'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            $syncData = [];
            foreach ($validated['items'] as $item) {
                $barang = BarangGudang::findOrFail($item['barang_gudang_id']);

                if ($barang->stok_fisik_barang < $item['jumlah_keluar']) {
                    throw new \Exception(
                        "Stok {$barang->masterBarang->nama_barang} tidak mencukupi"
                    );
                }

                $barang->decrement('stok_fisik_barang', $item['jumlah_keluar']);
                $syncData[$barang->id] = [
                    'jumlah_keluar' => $item['jumlah_keluar']
                ];
            }

            $pengeluaran->items()->sync($syncData);

            DB::commit();
            return redirect()
                ->route('pengeluaran_barang_gudang.index')
                ->with('success', 'Pengeluaran berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        Gate::authorize('delete-pengeluaran');
        $pengeluaran_gudang = PengeluaranGudang::with('items')->findOrFail($id);
        
        if ($pengeluaran_gudang->status === 'dikeluarkan') {
            foreach ($pengeluaran_gudang->items as $bg) {
                $barang_gudang = $bg;
                if ($barang_gudang) {
                    $barang_gudang->increment('stok_fisik_barang', $bg->pivot->jumlah_keluar); 
                }
            }
        }

        if ($pengeluaran_gudang->bukti_keluar && Storage::disk('public')->exists($pengeluaran_gudang->bukti_keluar)) {
            Storage::disk('public')->delete($pengeluaran_gudang->bukti_keluar);
        }
        if ($pengeluaran_gudang->bukti_kembali && Storage::disk('public')->exists($pengeluaran_gudang->bukti_kembali)) {
            Storage::disk('public')->delete($pengeluaran_gudang->bukti_kembali);
        }
        
        $pengeluaran_gudang->delete();
        
        return redirect()->route('pengeluaran_barang_gudang.index')->with('success', 'Pengeluaran Gudang berhasil dihapus.');
    }

    public function suratIndex()
    {
        Gate::authorize('manage-barang');

        $pengeluaran = PengeluaranGudang::with(['customer', 'user', 'pic', 'items.masterBarang'])->orderByDesc('tanggal_keluar')->paginate(10);

        return view('surat.pengeluaran.gudang.index', compact('pengeluaran'));
    }

    public function download($id)
    {
        $pengeluaran_gudang = PengeluaranGudang::with(['customer', 'user', 'pic', 'items.masterBarang'])->findOrFail($id);

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ])->loadView('pengeluaran_barang_gudang.surat', compact('pengeluaran_gudang'));

        return $pdf->download('surat_pengeluaran_material_instalasi_' . $pengeluaran_gudang->id . '.pdf');
    }
}