<?php

namespace App\Http\Controllers;

use App\Mail\PengeluaranBarangEngineerMail;
use App\Models\Pengeluaran;
use App\Models\BarangEngineer;
use App\Models\BarangHangus;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;


class PengeluaranBarangEngineerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function menu()
    {
        return view('pengeluaran_barang_engineer.menu');
    }

    public function index()
    {
        Gate::authorize('view-pengeluaran');
        $pengeluaran = Pengeluaran::with('customer', 'user', 'pic', 'returner', 'items.masterBarang', 'barangHangus')->latest()->paginate(10);
        $users = User::all();
        return view('pengeluaran_barang_engineer.index', compact('pengeluaran', 'users'));
    }

    public function create()
    {
        Gate::authorize('create-pengeluaran');
        $customers = Customer::all();
        $users = User::all();
        $barang_engineer = BarangEngineer::where('stok_fisik_barang', '>', 0)->with('masterBarang')->get();
        return view('pengeluaran_barang_engineer.create', compact('customers', 'users', 'barang_engineer'));
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
            'items.*.barang_engineer_id' => 'required|exists:barang_engineer,id',
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

            $pengeluaran = Pengeluaran::create([
                'customer_id' => $validatedData['customer_id'],
                'user_id' => $validatedData['user_id'],
                'pic_id' => $validatedData['pic_id'],
                'tanggal_keluar' => $validatedData['tanggal_keluar'],
                'status' => 'dipinjam',
                'keterangan' => $validatedData['keterangan'] ?? null,
                'bukti_pinjam' => $buktiPinjamPath,
            ]);

            $syncData = [];
            foreach ($validatedData['items'] as $be) {
                $barang_engineer = BarangEngineer::findOrFail($be['barang_engineer_id']);
                
                if ($barang_engineer->stok_fisik_barang < $be['jumlah_keluar']) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Stok fisik untuk {$barang_engineer->masterBarang->nama_barang} tidak mencukupi.");
                }

                $barang_engineer->decrement('stok_fisik_barang', $be['jumlah_keluar']);
                $syncData[$barang_engineer->id] = ['jumlah_keluar' => $be['jumlah_keluar']];
            }

            $pengeluaran->items()->attach($syncData);

            DB::commit();
            try {
                $pengeluaran->load(['pic', 'user', 'customer', 'items.masterBarang']);

                if ($pengeluaran->pic && $pengeluaran->pic->email) {
                    Mail::to($pengeluaran->pic->email)->send(new PengeluaranBarangEngineerMail($pengeluaran));
                }
            } catch (\Exception $mailError) {
                Log::error('Gagal kirim email notifikasi pengeluaran: ' . $mailError->getMessage());
            }

            return redirect()->route('pengeluaran_barang_engineer.index')->with('success', 'Pengeluaran berhasil ditambahkan dan notifikasi email dikirim ke PIC.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing pengeluaran: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()); 
        }
    }

    public function kembalikan(Request $request, $id)
    {
        Gate::authorize('manage-pengeluaran');
        $pengeluaran = Pengeluaran::with('items.masterBarang')->findOrFail($id);

        if ($pengeluaran->status !== 'dipinjam') {
            return redirect()->route('pengeluaran_barang_engineer.index')->with('error', 'Status pengeluaran tidak valid untuk dikembalikan.');
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
                if ($pengeluaran->bukti_kembali && Storage::disk('public')->exists($pengeluaran->bukti_kembali)) {
                    Storage::disk('public')->delete($pengeluaran->bukti_kembali);
                }

                $file = $request->file('bukti_kembali');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $pengeluaran->bukti_kembali = $file->storeAs('bukti_kembali', $fileName, 'public');
            }

            $pengeluaran->returner_id = $validatedData['pengembali_id'];

            foreach ($pengeluaran->items as $itemDetail) {
                $inputJumlahKembali = $validatedData['items_kembali'][$itemDetail->id]['jumlah_kembali'] ?? 0;
                $inputAlasan = $validatedData['items_kembali'][$itemDetail->id]['alasan'] ?? null;
                $barang_engineer = $itemDetail;
                $jumlah_dipinjam = $itemDetail->pivot->jumlah_keluar;

                if ($inputJumlahKembali > $jumlah_dipinjam) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Jumlah yang dikembalikan untuk {$barang_engineer->masterBarang->nama_barang} melebihi jumlah yang dipinjam.");
                }
                
                $jumlah_hangus = $jumlah_dipinjam - $inputJumlahKembali;
                $barang_engineer->increment('stok_fisik_barang', $inputJumlahKembali);

                if ($jumlah_hangus > 0) {
                    $barang_engineer->decrement('stok_sistem_barang', $jumlah_hangus);
                    BarangHangus::create([
                        'pengeluaran_id' => $pengeluaran->id,
                        'barang_engineer_id' => $barang_engineer->id,
                        'jumlah' => $jumlah_hangus,
                        'alasan' => $inputAlasan,
                    ]);
                }
            }
            
            $pengeluaran->tanggal_kembali = now();
            $pengeluaran->status = 'dikembalikan';
            $pengeluaran->save();

            DB::commit();
            return redirect()->route('pengeluaran_barang_engineer.index')->with('success', 'Barang berhasil dikembalikan.');
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
                ->route('pengeluaran_barang_engineer.index')
                ->with('error', 'Data pengeluaran tidak valid.');
        }

        $pengeluaran = Pengeluaran::with('items.masterBarang')
            ->findOrFail($id);

        if ($pengeluaran->status !== 'dipinjam') {
            return redirect()
                ->route('pengeluaran_barang_engineer.index')
                ->with('error', 'Pengeluaran yang sudah dikembalikan tidak dapat diedit.');
        }

        $customers = Customer::all();
        $users = User::all();
        $barang_engineer = BarangEngineer::with('masterBarang')->get();

        return view(
            'pengeluaran_barang_engineer.edit',
            compact('pengeluaran', 'customers', 'users', 'barang_engineer')
        );
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('edit-pengeluaran');

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()
                ->route('pengeluaran_barang_engineer.index')
                ->with('error', 'Data pengeluaran tidak valid.');
        }

        $pengeluaran = Pengeluaran::with('items')->findOrFail($id);

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
                'items.*.barang_engineer_id' => 'required|exists:barang_engineer,id',
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
                $barang = BarangEngineer::findOrFail($item['barang_engineer_id']);

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
                ->route('pengeluaran_barang_engineer.index')
                ->with('success', 'Pengeluaran berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        Gate::authorize('delete-pengeluaran');
        $pengeluaran = Pengeluaran::with('items')->findOrFail($id);
        
        if ($pengeluaran->status === 'dipinjam') {
            foreach ($pengeluaran->items as $be) {
                $barang_engineer = $be;
                if ($barang_engineer) {
                    $barang_engineer->increment('stok_fisik_barang', $be->pivot->jumlah_keluar); 
                }
            }
        }

        if ($pengeluaran->bukti_pinjam && Storage::disk('public')->exists($pengeluaran->bukti_pinjam)) {
            Storage::disk('public')->delete($pengeluaran->bukti_pinjam);
        }
        if ($pengeluaran->bukti_kembali && Storage::disk('public')->exists($pengeluaran->bukti_kembali)) {
            Storage::disk('public')->delete($pengeluaran->bukti_kembali);
        }
        
        $pengeluaran->delete();
        
        return redirect()->route('pengeluaran_barang_engineer.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }

    public function suratIndex()
    {
        Gate::authorize('manage-barang');

        $pengeluaran = Pengeluaran::with(['customer', 'user', 'pic', 'items.masterBarang'])->orderByDesc('tanggal_keluar')->paginate(10);

        return view('surat.pengeluaran.engineer.index', compact('pengeluaran'));
    }

    public function download($id)
    {
        $pengeluaran = Pengeluaran::with(['customer', 'user', 'pic', 'items.masterBarang'])->findOrFail($id);

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ])->loadView('pengeluaran_barang_engineer.surat', compact('pengeluaran'));

        return $pdf->download('surat_pengeluaran_alat_engineer_' . $pengeluaran->id . '.pdf');
    }
}