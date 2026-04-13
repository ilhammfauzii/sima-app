<?php

namespace App\Http\Controllers;

use App\Models\FileEnkripsi;
use App\Models\User;
use App\Services\LayananEnkripsiUniversal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ArsipController extends Controller
{
    protected $layananEnkripsi;

    public function __construct(LayananEnkripsiUniversal $layananEnkripsi)
    {
        $this->layananEnkripsi = $layananEnkripsi;
    }

    public function uploadMenu()
    {
        return view('arsip.upload-menu');
    }

    public function createRahasia()
    {
        $jenisDokumen = [
            'surat_pengeluaran' => 'Surat Pengeluaran Barang',
            'laporan' => 'Laporan Inventaris',
            'kwitansi' => 'Kwitansi',
            'kontrak' => 'Kontrak',
            'lainnya' => 'Dokumen Lainnya'
        ];

        $users = User::where('id', '!=', auth()->id())->get(['id', 'nama', 'email']);

        return view('arsip.create-rahasia', compact('jenisDokumen', 'users'));
    }

    public function createBiasa()
    {
        $jenisDokumen = [
            'surat_pengeluaran' => 'Surat Pengeluaran Barang',
            'laporan' => 'Laporan Inventaris',
            'kwitansi' => 'Kwitansi',
            'kontrak' => 'Kontrak',
            'lainnya' => 'Dokumen Lainnya'
        ];

        $users = User::where('id', '!=', auth()->id())->get(['id', 'nama', 'email']);

        return view('arsip.create-biasa', compact('jenisDokumen', 'users'));
    }

    public function store(Request $request)
    {
        Log::info('=== ARSIP DOKUMEN STORE ===');
        Log::info('Kategori: ' . $request->kategori);
        Log::info('Request Data:', $request->all());

        $kategori = $request->kategori;

        $rules = [
            'jenis_dokumen' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,mp4,avi,mkv|max:51200',
            'deskripsi' => 'nullable|string|max:500'
        ];

        if ($kategori == 'rahasia') {
            $rules['penerima'] = 'required|array|min:1';
            $rules['penerima.*.user_id'] = 'required|exists:users,id';
            $rules['kadaluarsa_pada'] = 'nullable|date|after:now';
        } else {
            $rules['penerima'] = 'nullable|array';
            $rules['penerima.*.user_id'] = 'nullable|exists:users,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        try {
            $file = $request->file('file');
            $validated = $validator->validated();

            if ($kategori == 'rahasia') {
                $hasilEnkripsi = $this->layananEnkripsi->enkripsiFile($file, $validated['jenis_dokumen']);
                $pathFile = $hasilEnkripsi['path_file_terenkripsi'];
                $kunciEnkripsi = $hasilEnkripsi['kunci_enkripsi'];
            } else {
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $pathFile = $file->storeAs('arsip_biasa', $namaFile);
                $kunciEnkripsi = null;
            }

            $penerimaFormatted = $this->formatPenerima($validated['penerima'] ?? []);

            $uploader = auth()->user();
            $penerimaFormatted[] = [
                'user_id' => $uploader->id,
                'email' => $uploader->email,
                'nama' => $uploader->nama,
                'peran' => 'uploader'
            ];

            $fileData = [
                'jenis_dokumen' => $validated['jenis_dokumen'],
                'kategori' => $kategori,
                'nama_file_asli' => $file->getClientOriginalName(),
                'path_file_terenkripsi' => $pathFile,
                'kunci_enkripsi' => $kunciEnkripsi,
                'ukuran_file' => $file->getSize(),
                'tipe_file' => $file->getMimeType(),
                'penerima' => $penerimaFormatted,
                'diupload_oleh' => $uploader->id,
                'deskripsi' => $validated['deskripsi'] ?? null,
                'kadaluarsa_pada' => $validated['kadaluarsa_pada'] ?? null,
                'status' => 'aktif'
            ];

            $arsip = FileEnkripsi::create($fileData);

            $this->kirimEmailNotifikasi($arsip, $kategori);

            $successMessage = $kategori == 'rahasia'
                ? 'Dokumen rahasia berhasil dienkripsi dan diarsipkan. ' . count($penerimaFormatted) . ' penerima akan menerima email.'
                : 'Dokumen biasa berhasil diarsipkan.';

            return redirect()->route('arsip.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Error in store method: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return back()->with('error', 'Gagal mengarsipkan file: ' . $e->getMessage())->withInput();
        }
    }

    public function index()
    {
        $kategoriFilter = request('kategori', 'semua');
        $tipeFilter = request('tipe', 'semua');
        
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->isSuperAdmin()) {

            $query = FileEnkripsi::with('diuploadOleh')->aktif();

        } else {

            $query = FileEnkripsi::with('diuploadOleh')
                ->aktif()
                ->where(function ($q) use ($user) {
                    $q->where('diupload_oleh', $user->id)
                    ->orWhereJsonContains('penerima', ['user_id' => $user->id]);
                });

        }

        if ($kategoriFilter == 'rahasia') {
            $query->where('kategori', 'rahasia');
        } elseif ($kategoriFilter == 'tidak_rahasia') {
            $query->where('kategori', 'tidak_rahasia');
        }

        if (!$user->isSuperAdmin()) {

            if ($tipeFilter == 'dikirim') {
                $query->where('diupload_oleh', $user->id);
            } elseif ($tipeFilter == 'diterima') {
                $query->whereJsonContains('penerima', ['user_id' => $user->id])
                    ->where('diupload_oleh', '!=', $user->id);
            }

        }

        $files = $query->latest()->paginate(10);

        return view('arsip.index', compact('files', 'kategoriFilter', 'tipeFilter'));
    }

    public function formDekripsi()
    {
        return view('arsip.dekripsi');
    }

    public function prosesDekripsi(Request $request)
    {
        $validated = $request->validate([
            'file_id' => 'required|exists:file_enkripsi,id',
            'kunci_enkripsi' => 'required|string'
        ]);

        $file = FileEnkripsi::findOrFail($validated['file_id']);
        
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $isUploader = $file->diupload_oleh == $user->id;
        $isReceiver = collect($file->penerima)->contains('user_id', $user->id);

        if (!$isUploader && !$isReceiver && !$user->isSuperAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        if ($file->kategori != 'rahasia') {
            return back()->with('error', 'Dokumen ini tidak terenkripsi. Tidak perlu didekripsi.');
        }

        if ($file->kadaluarsa_pada && now()->greaterThan($file->kadaluarsa_pada)) {
            return back()->with('error', 'File sudah kadaluarsa dan tidak bisa didekripsi lagi.');
        }

        try {
            $kontenTerdekripsi = $this->layananEnkripsi->dekripsiFile(
                $file->path_file_terenkripsi, 
                $validated['kunci_enkripsi']
            );

        Log::info('File didekripsi', [
            'file_id' => $file->id,
            'nama_file_asli' => $file->nama_file_asli,
            'user_id' => $user->id,
            'waktu' => now()
        ]);

            return response($kontenTerdekripsi)
                ->header('Content-Type', $file->tipe_file)
                ->header('Content-Disposition', 'attachment; filename="' . $file->nama_file_asli . '"');

        } catch (\Exception $e) {
            Log::error('Dekripsi error: ' . $e->getMessage());
            return back()->with('error', 'Kunci enkripsi salah atau file rusak.')->withInput();
        }
    }

    public function download($id)
    {
        $file = FileEnkripsi::findOrFail($id);

        $this->authorize('download', $file);

        if ($file->kategori != 'tidak_rahasia') {
            return back()->with('error', 'Dokumen rahasia harus didekripsi terlebih dahulu.');
        }

        if (!Storage::exists($file->path_file_terenkripsi)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::download($file->path_file_terenkripsi, $file->nama_file_asli);
    }

    public function destroy($id)
    {
        $file = FileEnkripsi::findOrFail($id);

        $this->authorize('delete', $file);

        try {
            $file->update(['status' => 'terhapus']);

            return redirect()
                ->route('arsip.index')
                ->with('success', 'Arsip berhasil dihapus.');

        } catch (\Exception $e) {

            Log::error('Delete error: ' . $e->getMessage());

            return back()->with('error', 'Gagal menghapus arsip.');
        }
    }

    private function formatPenerima(array $penerimaData): array
    {
        $penerimaFormatted = [];
        
        foreach ($penerimaData as $penerima) {
            if (!empty($penerima['user_id'])) {
                $user = User::find($penerima['user_id']);
                if ($user) {
                    $penerimaFormatted[] = [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'nama' => $user->nama,
                        'peran' => 'penerima'
                    ];
                }
            }
        }
        
        return $penerimaFormatted;
    }

    private function kirimEmailNotifikasi(FileEnkripsi $file, string $kategori): void
    {
        $errorCount = 0;

        foreach ($file->penerima as $penerima) {
            try {
                if ($kategori == 'rahasia') {
                    Mail::send('emails.dokumen-rahasia', [
                        'file' => $file,
                        'penerima' => $penerima,
                        'kunci_enkripsi' => $file->kunci_enkripsi
                    ], function ($message) use ($file, $penerima) {
                        $message->to($penerima['email'], $penerima['nama'])
                                ->subject('Dokumen Rahasia - ' . $file->nama_file_asli);
                    });
                } else {
                    Mail::send('emails.dokumen-biasa', [
                        'file' => $file,
                        'penerima' => $penerima
                    ], function ($message) use ($file, $penerima) {
                        $message->to($penerima['email'], $penerima['nama'])
                                ->subject('Dokumen Biasa - ' . $file->nama_file_asli);
                    });
                }

                Log::info("Email berhasil dikirim ke: {$penerima['email']}");
            } catch (\Exception $e) {
                $errorCount++;
                Log::error("Gagal mengirim email ke {$penerima['email']}: " . $e->getMessage());
            }
        }

        if ($errorCount > 0) {
            session()->flash('warning', "Gagal mengirim email ke {$errorCount} penerima.");
        }
    }

    public function editKadaluarsa($id)
    {
        $file = FileEnkripsi::findOrFail($id);
        return view('arsip.kadaluarsa', compact('file'));
    }

    public function updateKadaluarsa(Request $request, $id)
    {
        $file = FileEnkripsi::findOrFail($id);

        $request->validate([
            'kadaluarsa_pada' => 'nullable|date',
        ]);

        $file->kadaluarsa_pada = $request->kadaluarsa_pada ? $request->kadaluarsa_pada : null;
        $file->save();

        return redirect()->route('arsip.index')->with('success', 'Tanggal kadaluarsa berhasil diperbarui.');
    }

    public function downloadEncrypted($id)
    {
        $file = FileEnkripsi::findOrFail($id);

        $this->authorize('download', $file);

        if ($file->kategori != 'rahasia') {
            return back()->with('error', 'File ini tidak terenkripsi.');
        }

        if (!Storage::exists($file->path_file_terenkripsi)) {
            return back()->with('error', 'File terenkripsi tidak ditemukan.');
        }

        $encryptedName = 'encrypted_' . $file->nama_file_asli . '.enc';

        return Storage::download($file->path_file_terenkripsi, $encryptedName);
    }
}