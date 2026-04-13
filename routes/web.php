<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangEngineerController;
use App\Http\Controllers\PengeluaranBarangEngineerController;
use App\Http\Controllers\BarangGudangController;
use App\Http\Controllers\PengeluaranBarangGudangController;
use App\Http\Controllers\BarangSafetyController;
use App\Http\Controllers\PengeluaranBarangSafetyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\SLAController;
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\PengeluaranGabunganController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\CustomerController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', fn () => redirect()->route('dashboard'));

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::view('/user_manual', 'user_manual')->name('user_manual');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
    });

    // --- DAFTARKAN SEMUA RUTE SPESIFIK/CUSTOM DULU (PENTING UNTUK MENGHINDARI 404) ---

    // --- Rute Custom ---
    Route::get('/sla/menu', [SLAController::class, 'menu'])->name('sla.menu');
    Route::get('/sla/{sla}/finish', [SLAController::class, 'showFinishForm'])->name('sla.show_finish_form');
    Route::post('/sla/{sla}/finish', [SLAController::class, 'finish'])->name('sla.finish');

    // --- Performance (khusus admin) ---
    Route::middleware('can:manage-sla')->group(function () {
        Route::get('/sla/performance', [SLAController::class, 'performance'])->name('sla.performance');
    });

    // --- Resource utama ---
    Route::resource('sla', SLAController::class)->except(['edit', 'update', 'destroy', 'show']);
    Route::get('/sla/search', [SLAController::class, 'search'])->name('sla.search');

    // --- Route edit & update untuk PIC atau admin ---
    Route::get('/sla/{sla}/edit', [SLAController::class, 'edit'])->name('sla.edit');
    Route::put('/sla/{sla}', [SLAController::class, 'update'])->name('sla.update');

    // --- Hanya admin yang boleh hapus & export ---
    Route::middleware('can:manage-sla')->group(function () {
        Route::delete('/sla/{sla}', [SLAController::class, 'destroy'])->name('sla.destroy');
        Route::get('/sla/export', [SLAController::class, 'export'])->name('sla.export');
    });
    
    Route::middleware('can:view-user-management')->group(function () {

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('users/menu', [UserController::class, 'menu'])->name('users.menu');
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            
            Route::middleware('can:manage-users')->group(function() {
                Route::resource('users', UserController::class)->except(['index', 'show']);
                Route::resource('roles', RoleController::class);
            });
        });

        Route::prefix('master')->name('master.')->group(function () {
            Route::get('/', [MasterController::class, 'index'])->name('index');
            Route::get('/create', [MasterController::class, 'create'])->name('create');
            Route::post('/store', [MasterController::class, 'store'])->name('store');
        });

        Route::prefix('master_data')->name('master_data.')->group(function () {
            Route::get('/create', [MasterDataController::class, 'create'])->name('create');
            Route::post('/store', [MasterDataController::class, 'store'])->name('store');
        });
    });

    Route::middleware('can:view-barang')->group(function () {
        Route::get('/barang_engineer', [BarangEngineerController::class, 'index'])->name('barang_engineer.index');
        Route::get('/barang_gudang', [BarangGudangController::class, 'index'])->name('barang_gudang.index');
        Route::get('/barang_safety', [BarangSafetyController::class, 'index'])->name('barang_safety.index');

        Route::get('/barang_engineer/menu', fn () => view('barang_engineer.menu'))->name('barang_engineer.menu');
    });

    Route::prefix('barang-engineer')->group(function () {

        Route::resource('inventaris', BarangEngineerController::class)->names('barang_engineer');
        Route::get('/barang-engineer/search', [BarangEngineerController::class, 'search'])->name('barang_engineer.search');
        Route::get('pengadaan/menu', [BarangEngineerController::class, 'index'])->name('pengadaan_barang_engineer.menu'); 
        Route::get('pengadaan/add', [BarangEngineerController::class, 'create'])->name('pengadaan_barang_engineer.create');
        Route::post('pengadaan/store', [BarangEngineerController::class, 'store'])->name('pengadaan_barang_engineer.store');

        Route::get('/barang_engineer/download/excel', [BarangEngineerController::class, 'downloadExcel'])->name('barang_engineer.download.excel');
        Route::get('/barang_hangus/download/excel', [BarangEngineerController::class, 'downloadExcelBarangHangus'])->name('barang_hangus.download.excel');
        Route::get('/barang_engineer/import', [BarangEngineerController::class, 'importForm'])->name('barang_engineer.import.form');
        Route::post('/barang_engineer/import', [BarangEngineerController::class, 'import'])->name('barang_engineer.import');
        Route::get('/barang_engineer/template', [BarangEngineerController::class, 'template'])->name('barang_engineer.template');
    });

    Route::prefix('barang-gudang')->group(function () {

        Route::resource('inventaris', BarangGudangController::class)->names('barang_gudang');
        Route::get('/barang-gudang/search', [BarangGudangController::class, 'search'])->name('barang_gudang.search');
        Route::get('pengadaan/menu', [BarangGudangController::class, 'index'])->name('pengadaan_barang_gudang.menu'); 
        Route::get('pengadaan/add', [BarangGudangController::class, 'create'])->name('pengadaan_barang_gudang.create');
        Route::post('pengadaan/store', [BarangGudangController::class, 'store'])->name('pengadaan_barang_gudang.store');

        Route::get('/barang_gudang/download/excel', [BarangGudangController::class, 'downloadExcel'])->name('barang_gudang.download.excel');
        Route::get('/barang_keluar/download/excel', [BarangGudangController::class, 'downloadExcelBarangKeluar'])->name('barang_keluar.download.excel');
        Route::get('/barang_gudang/import', [BarangGudangController::class, 'importForm'])->name('barang_gudang.import.form');
        Route::post('/barang_gudang/import', [BarangGudangController::class, 'import'])->name('barang_gudang.import');
        Route::get('/barang_gudang/template', [BarangGudangController::class, 'template'])->name('barang_gudang.template');
    });

    Route::prefix('barang-safety')->group(function () {

        Route::resource('inventaris', BarangSafetyController::class)->names('barang_safety');
        Route::get('/barang-safety/search', [BarangSafetyController::class, 'search'])->name('barang_safety.search');
        Route::get('pengadaan/menu', [BarangSafetyController::class, 'index'])->name('pengadaan_barang_safety.menu'); 
        Route::get('pengadaan/add', [BarangSafetyController::class, 'create'])->name('pengadaan_barang_safety.create');
        Route::post('pengadaan/store', [BarangSafetyController::class, 'store'])->name('pengadaan_barang_safety.store');

        Route::get('/barang_safety/download/excel', [BarangSafetyController::class, 'downloadExcel'])->name('barang_safety.download.excel');
        Route::get('/barang_keluar/download/excel', [BarangSafetyController::class, 'downloadExcelBarangKeluar'])->name('barang_keluar.download.excel');
        Route::get('/barang_safety/import', [BarangSafetyController::class, 'importForm'])->name('barang_safety.import.form');
        Route::post('/barang_safety/import', [BarangSafetyController::class, 'import'])->name('barang_safety.import');
        Route::get('/barang_safety/template', [BarangSafetyController::class, 'template'])->name('barang_safety.template');
    });
    

    Route::middleware('can:view-pengeluaran')->group(function () {
        Route::get('pengeluaran_barang_engineer/menu', [PengeluaranBarangEngineerController::class, 'menu'])->name('pengeluaran_barang_engineer.menu');
        Route::get('/pengeluaran_barang_engineer', [PengeluaranBarangEngineerController::class, 'index'])->name('pengeluaran_barang_engineer.index');

        Route::get('pengeluaran_barang_gudang/menu', [PengeluaranBarangGudangController::class, 'menu'])->name('pengeluaran_barang_gudang.menu');
        Route::get('/pengeluaran_barang_gudang', [PengeluaranBarangGudangController::class, 'index'])->name('pengeluaran_barang_gudang.index');

        Route::get('pengeluaran_barang_safety/menu', [PengeluaranBarangSafetyController::class, 'menu'])->name('pengeluaran_barang_safety.menu');
        Route::get('/pengeluaran_barang_safety', [PengeluaranBarangSafetyController::class, 'index'])->name('pengeluaran_barang_safety.index');
    });

    Route::middleware('can:create-pengeluaran')->group(function () {
        Route::resource('pengeluaran_barang_engineer', PengeluaranBarangEngineerController::class)->only(['create', 'store']);
        Route::resource('pengeluaran_barang_gudang', PengeluaranBarangGudangController::class)->only(['create', 'store']);
        Route::resource('pengeluaran_barang_safety', PengeluaranBarangSafetyController::class)->only(['create', 'store']);
    });

    Route::middleware('can:manage-pengeluaran')->group(function () {
        Route::resource('pengeluaran_barang_engineer', PengeluaranBarangEngineerController::class)->except(['index', 'show', 'create', 'store']);
        Route::post('pengeluaran_barang_engineer/{id}/kembalikan', [PengeluaranBarangEngineerController::class, 'kembalikan'])->name('pengeluaran_barang_engineer.kembalikan');
        Route::post('pengeluaran_barang_engineer/{id}/hanguskan', [PengeluaranBarangEngineerController::class, 'hanguskan'])->name('pengeluaran_barang_engineer.hanguskan');
        Route::get('pengeluaran_barang_engineer/download/{id}', [PengeluaranBarangEngineerController::class, 'download'])->name('pengeluaran_barang_engineer.download');

        Route::resource('pengeluaran_barang_gudang', PengeluaranBarangGudangController::class)->except(['index', 'show', 'create', 'store']);
        Route::post('pengeluaran_barang_gudang/{id}/kembalikan', [PengeluaranBarangGudangController::class, 'kembalikan'])->name('pengeluaran_barang_gudang.kembalikan');
        Route::post('pengeluaran_barang_gudang/{id}/keluarkan', [PengeluaranBarangGudangController::class, 'keluarkan'])->name('pengeluaran_barang_gudang.keluarkan');
        Route::get('pengeluaran_barang_gudang/download/{id}', [PengeluaranBarangGudangController::class, 'download'])->name('pengeluaran_barang_gudang.download');

        Route::resource('pengeluaran_barang_safety', PengeluaranBarangSafetyController::class)->except(['index', 'show', 'create', 'store']);
        Route::post('pengeluaran_barang_safety/{id}/kembalikan', [PengeluaranBarangSafetyController::class, 'kembalikan'])->name('pengeluaran_barang_safety.kembalikan');
        Route::post('pengeluaran_barang_safety/{id}/lenyapkan', [PengeluaranBarangSafetyController::class, 'lenyapkan'])->name('pengeluaran_barang_safety.lenyapkan');
        Route::get('pengeluaran_barang_safety/download/{id}', [PengeluaranBarangSafetyController::class, 'download'])->name('pengeluaran_barang_safety.download');
    });

    Route::middleware('can:edit-pengeluaran')->group(function () {
        Route::get('pengeluaran_barang_engineer/{id}/edit',[PengeluaranBarangEngineerController::class, 'edit'])->name('pengeluaran_barang_engineer.edit');
        Route::put('pengeluaran_barang_engineer/{id}',[PengeluaranBarangEngineerController::class, 'update'])->name('pengeluaran_barang_engineer.update');

        Route::get('pengeluaran_barang_gudang/{id}/edit',[PengeluaranBarangGudangController::class, 'edit'])->name('pengeluaran_barang_gudang.edit');
        Route::put('pengeluaran_barang_gudang/{id}',[PengeluaranBarangGudangController::class, 'update'])->name('pengeluaran_barang_gudang.update');

        Route::get('pengeluaran_barang_safety/{id}/edit',[PengeluaranBarangSafetyController::class, 'edit'])->name('pengeluaran_barang_safety.edit');
        Route::put('pengeluaran_barang_safety/{id}',[PengeluaranBarangSafetyController::class, 'update'])->name('pengeluaran_barang_safety.update');
    });

    Route::middleware('can:manage-barang')->group(function () {

        Route::prefix('pengadaan_barang_engineer')->name('pengadaan_barang_engineer.')->group(function () {
            Route::get('/menu', fn () => view('barang_engineer.menu'))->name('menu');
        });
        Route::prefix('pengadaan_barang_gudang')->name('pengadaan_barang_gudang.')->group(function () {
            Route::get('/menu', fn () => view('barang_gudang.menu'))->name('menu');
        });
        Route::prefix('pengadaan_barang_safety')->name('pengadaan_barang_safety.')->group(function () {
            Route::get('/menu', fn () => view('barang_safety.menu'))->name('menu');
        });

        Route::resource('barang_engineer', BarangEngineerController::class)->except(['index', 'show', 'create', 'store']);
        Route::resource('barang_gudang', BarangGudangController::class)->except(['index', 'show', 'create', 'store']);
        Route::resource('barang_safety', BarangSafetyController::class)->except(['index', 'show', 'create', 'store']);

        Route::get('/barang_gudang/download/excel', [BarangGudangController::class, 'downloadExcel'])->name('barang_gudang.download.excel');
        Route::get('/barang_keluar/download/excel', [BarangGudangController::class, 'downloadExcelBarangKeluar'])->name('barang_keluar.download.excel');
        Route::get('/barang_safety/download/excel', [BarangSafetyController::class, 'downloadExcel'])->name('barang_safety.download.excel');
        Route::get('/barang_lenyap/download/excel', [BarangSafetyController::class, 'downloadExcelBarangLenyap'])->name('barang_lenyap.download.excel');

        Route::get('/master-barang/menu', fn () => view('master_barang.menu'))->name('master_barang.menu');
        Route::get('/master-barang/search', [MasterBarangController::class, 'search'])->name('master_barang.search');
        Route::resource('master_barang', MasterBarangController::class);
        Route::get('/master-barang/import', [MasterBarangController::class, 'importForm'])->name('master_barang.import.form');

        Route::post('/master-barang/import', [MasterBarangController::class, 'import'])->name('master_barang.import');

        Route::get('/master-barang/template', [MasterBarangController::class, 'template'])->name('master_barang.template');
    });

    Route::middleware(['auth', 'can:manage-barang'])->group(function () {
        Route::get('/surat/pengeluaran/engineer', [PengeluaranBarangEngineerController::class, 'suratIndex'])->name('surat.pengeluaran.engineer.index');
        Route::get('/surat/pengeluaran/engineer/{id}', [PengeluaranBarangEngineerController::class, 'download'])->name('surat.pengeluaran.engineer.cetak');
        Route::get('/surat/pengeluaran/gudang', [PengeluaranBarangGudangController::class, 'suratIndex'])->name('surat.pengeluaran.gudang.index');
        Route::get('/surat/pengeluaran/gudang/{id}', [PengeluaranBarangGudangController::class, 'download'])->name('surat.pengeluaran.gudang.cetak');
        Route::get('/surat/pengeluaran/safety', [PengeluaranBarangSafetyController::class, 'suratIndex'])->name('surat.pengeluaran.safety.index');
        Route::get('/surat/pengeluaran/safety/{id}', [PengeluaranBarangSafetyController::class, 'download'])->name('surat.pengeluaran.safety.cetak');
        Route::get('/pengeluaran/gabungan', [PengeluaranGabunganController::class, 'index'])->name('pengeluaran.gabungan.index');
        Route::get('/pengeluaran/gabungan/cetak', [PengeluaranGabunganController::class, 'cetakGabungan'])->name('pengeluaran.gabungan.cetak');
    });

    Route::prefix('arsip')->name('arsip.')->group(function () {

        Route::get('/upload-menu', [ArsipController::class, 'uploadMenu'])->name('upload-menu');
        Route::get('/create-rahasia', [ArsipController::class, 'createRahasia'])->name('create-rahasia');
        Route::get('/create-biasa', [ArsipController::class, 'createBiasa'])->name('create-biasa');
        Route::post('/store', [ArsipController::class, 'store'])->name('store');
        Route::get('/', [ArsipController::class, 'index'])->name('index');
        Route::get('/dekripsi-form', [ArsipController::class, 'formDekripsi'])->name('dekripsi-form');
        Route::post('/proses-dekripsi', [ArsipController::class, 'prosesDekripsi'])->name('proses-dekripsi');
        Route::get('/download/{id}', [ArsipController::class, 'download'])->name('download');
        Route::delete('/destroy/{id}', [ArsipController::class, 'destroy'])->name('destroy');
        Route::get('/kadaluarsa/{id}', [ArsipController::class, 'editKadaluarsa'])->name('kadaluarsa.edit');
        Route::post('/kadaluarsa/{id}', [ArsipController::class, 'updateKadaluarsa'])->name('kadaluarsa.update');
        Route::get('/download-encrypted/{id}', [ArsipController::class, 'downloadEncrypted'])->name('download-encrypted');
    });

    Route::prefix('customers')->name('customers.')->group(function () {

        Route::get('/menu', [CustomerController::class, 'menu'])->name('menu');
        Route::get('/search', [CustomerController::class, 'search'])->name('search');
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');

        Route::get('/export', [CustomerController::class, 'export'])->name('export');
        Route::get('/template', [CustomerController::class, 'template'])->name('template');
        Route::get('/import', [CustomerController::class, 'importForm'])->name('import.form');
        Route::post('/import', [CustomerController::class, 'import'])->name('import');

        Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
    });
});