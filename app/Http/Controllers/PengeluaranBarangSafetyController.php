<?php

namespace App\Http\Controllers;

use App\Mail\PengeluaranBarangSafetyMail;
use App\Models\PengeluaranSafety;
use App\Models\BarangSafety;
use App\Models\BarangLenyap;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PengeluaranBarangSafetyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function menu()
    {
    
    return view('pengeluaran_barang_safety.menu');
    }

    public function index()
    {
        Gate::authorize('view-pengeluaran');
        $pengeluaran_safety = PengeluaranSafety::with('customer', 'user', 'pic', 'returner', 'items.masterBarang', 'baranglenyap')->latest()->paginate(10);
        $users = User::all();
        return view('pengeluaran_barang_safety.index', compact('pengeluaran_safety', 'users'));
    }

    public function create()
    {
        Gate::authorize('create-pengeluaran');
        $customers = Customer::all();
        $users = User::all();
        $barang_safety = BarangSafety::where('stok_fisik_barang', '>', 0)->with('masterBarang')->get();
        return view('pengeluaran_barang_safety.create', compact('customers', 'users', 'barang_safety'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create-pengeluaran');
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'pic_id' => 'required|exists:users,id',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
            'bukti_pinjam' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'items' => 'required|array|min:1',
            'items.*.barang_safety_id' => 'required|exists:barang_safety,id',
            'items.*.jumlah_keluar' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $buktiPinjamPath = null;
            if ($request->hasFile('bukti_pinjam')) {
                $file = $request->file('bukti_pinjam');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $buktiPinjamPath = $file->storeAs('bukti_pinjam', $fileName, 'public');
            }

            $pengeluaran_safety = PengeluaranSafety::create([
                'customer_id' => $validatedData['customer_id'],
                'user_id' => $validatedData['user_id'],
                'pic_id' => $validatedData['pic_id'],
                'tanggal_keluar' => $validatedData['tanggal_keluar'],
                'status' => 'dipinjam',
                'keterangan' => $validatedData['keterangan'] ?? null,
                'bukti_pinjam' => $buktiPinjamPath,
            ]);

            $syncData = [];
            foreach ($validatedData['items'] as $bs) {
                $barang_safety = BarangSafety::findOrFail($bs['barang_safety_id']);

                if ($barang_safety->stok_fisik_barang < $bs['jumlah_keluar']) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Stok fisik untuk {$barang_safety->masterBarang->nama_barang} tidak mencukupi.");
                }

                $barang_safety->decrement('stok_fisik_barang', $bs['jumlah_keluar']);
                $syncData[$barang_safety->id] = ['jumlah_keluar' => $bs['jumlah_keluar']];
            }

            $pengeluaran_safety->items()->attach($syncData);

            DB::commit();
            if ($pengeluaran_safety->pic && $pengeluaran_safety->pic->email) {
                Mail::to($pengeluaran_safety->pic->email)->send(new PengeluaranBarangSafetyMail($pengeluaran_safety));
            }

            return redirect()->route('pengeluaran_barang_safety.index')->with('success', 'Pengeluaran berhasil ditambahkan dan notifikasi email dikirim ke PIC.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing pengeluaran: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function kembalikan(Request $request, $id)
    {
        Gate::authorize('manage-pengeluaran');
        $pengeluaran_safety = PengeluaranSafety::with('items.masterBarang')->findOrFail($id);

        if ($pengeluaran_safety->status !== 'dipinjam') {
            return redirect()->route('pengeluaran_barang_safety.index')->with('error', 'Status pengeluaran tidak valid untuk dikembalikan.');
        }

        $validatedData = $request->validate([
            'pengembali_id' => 'required|exists:users,id',
            'items_kembali' => 'required|array|min:1',
            'items_kembali.*.jumlah_kembali' => 'required|integer|min:0',
            'items_kembali.*.alasan' => 'nullable|string',
            'bukti_kembali' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);
        
        DB::beginTransaction();
        try {
            if ($request->hasFile('bukti_kembali')) {
                if ($pengeluaran_safety->bukti_kembali && Storage::disk('public')->exists($pengeluaran_safety->bukti_kembali)) {
                    Storage::disk('public')->delete($pengeluaran_safety->bukti_kembali);
                }

                $file = $request->file('bukti_kembali');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $pengeluaran_safety->bukti_kembali = $file->storeAs('bukti_kembali', $fileName, 'public');
            }

            $pengeluaran_safety->returner_id = $validatedData['pengembali_id'];

            foreach ($pengeluaran_safety->items as $itemDetail) {
                $inputJumlahKembali = $validatedData['items_kembali'][$itemDetail->id]['jumlah_kembali'] ?? 0;
                $inputAlasan = $validatedData['items_kembali'][$itemDetail->id]['alasan'] ?? null;
                $barang_safety = $itemDetail;
                $jumlah_dipinjam = $itemDetail->pivot->jumlah_keluar;

                if ($inputJumlahKembali > $jumlah_dipinjam) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Jumlah yang dikembalikan untuk {$barang_safety->masterBarang->nama_barang} melebihi jumlah yang dipinjam.");
                }
                
                $jumlah_lenyap = $jumlah_dipinjam - $inputJumlahKembali;
                $barang_safety->increment('stok_fisik_barang', $inputJumlahKembali);

                if ($jumlah_lenyap > 0) {
                    $barang_safety->decrement('stok_sistem_barang', $jumlah_lenyap);
                    BarangLenyap::create([
                        'pengeluaran_safety_id' => $pengeluaran_safety->id,
                        'barang_safety_id' => $barang_safety->id,
                        'jumlah' => $jumlah_lenyap,
                        'alasan' => $inputAlasan,
                    ]);
                }
            }
            
            $pengeluaran_safety->tanggal_kembali = now();
            $pengeluaran_safety->status = 'dikembalikan';
            $pengeluaran_safety->save();

            DB::commit();
            return redirect()->route('pengeluaran_barang_safety.index')->with('success', 'Barang berhasil dikembalikan.');
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
            return redirect()
                ->route('pengeluaran_barang_safety.index')
                ->with('error', 'Data pengeluaran tidak valid.');
        }

        $pengeluaran = PengeluaranSafety::with('items.masterBarang')
            ->findOrFail($id);

        if ($pengeluaran->status !== 'dipinjam') {
            return redirect()
                ->route('pengeluaran_barang_safety.index')
                ->with('error', 'Pengeluaran yang sudah dikembalikan tidak dapat diedit.');
        }

        $customers = Customer::all();
        $users = User::all();
        $barang_safety = BarangSafety::with('masterBarang')->get();

        return view(
            'pengeluaran_barang_safety.edit',
            compact('pengeluaran', 'customers', 'users', 'barang_safety')
        );
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('edit-pengeluaran');

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()
                ->route('pengeluaran_barang_safety.index')
                ->with('error', 'Data pengeluaran tidak valid.');
        }

        $pengeluaran = PengeluaranSafety::with('items')->findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($pengeluaran->items as $item) {
                $item->increment('stok_fisik_barang', $item->pivot->jumlah_keluar);
            }

            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'user_id' => 'required|exists:users,id',
                'pic_id' => 'required|exists:users,id',
                'tanggal_keluar' => 'required|date',
                'keterangan' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.barang_safety_id' => 'required|exists:barang_safety,id',
                'items.*.jumlah_keluar' => 'required|integer|min:1',
            ]);

            $pengeluaran->update([
                'customer_id' => $validated['customer_id'],
                'user_id' => $validated['user_id'],
                'pic_id' => $validated['pic_id'],
                'tanggal_keluar' => $validated['tanggal_keluar'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            $syncData = [];
            foreach ($validated['items'] as $item) {
                $barang = BarangSafety::findOrFail($item['barang_safety_id']);

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
                ->route('pengeluaran_barang_safety.index')
                ->with('success', 'Pengeluaran berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $pengeluaran_safety = PengeluaranSafety::with('items')->findOrFail($id);
        
        if ($pengeluaran_safety->status === 'dipinjam') {
            foreach ($pengeluaran_safety->items as $bs) {
                $barang_safety = $bs;
                if ($barang_safety) {
                    $barang_safety->increment('stok_fisik_barang', $bs->pivot->jumlah_keluar); 
                }
            }
        }

        if ($pengeluaran_safety->bukti_pinjam && Storage::disk('public')->exists($pengeluaran_safety->bukti_pinjam)) {
            Storage::disk('public')->delete($pengeluaran_safety->bukti_pinjam);
        }
        if ($pengeluaran_safety->bukti_kembali && Storage::disk('public')->exists($pengeluaran_safety->bukti_kembali)) {
            Storage::disk('public')->delete($pengeluaran_safety->bukti_kembali);
        }

        $pengeluaran_safety->delete();
        
        return redirect()->route('pengeluaran_barang_safety.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }

    public function suratIndex()
    {
        Gate::authorize('manage-barang');

        $pengeluaran = PengeluaranSafety::with(['customer', 'user', 'pic', 'items.masterBarang'])->orderByDesc('tanggal_keluar')->paginate(10);

        return view('surat.pengeluaran.safety.index', compact('pengeluaran'));
    }

    public function download($id)
    {
        $pengeluaran_safety = PengeluaranSafety::with(['customer', 'user', 'pic', 'items.masterBarang'])->findOrFail($id);

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ])->loadView('pengeluaran_barang_safety.surat', compact('pengeluaran_safety'));

        return $pdf->download('surat_pengeluaran_alat_safety_' . $pengeluaran_safety->id . '.pdf');
    }
}