<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PengaturanTokoController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // ── Master: Kategori ───────────────────────────────────────────
    Route::prefix('kategori')->controller(KategoriController::class)->group(function () {
        Route::get('/',        'index')->name('kategori.index');
        Route::post('/',       'store')->name('kategori.store');
        Route::put('/{id}',    'update')->name('kategori.update');
        Route::delete('/{id}', 'destroy')->name('kategori.destroy');
    });

    // ── Master: Supplier ───────────────────────────────────────────
    Route::prefix('supplier')->controller(SupplierController::class)->group(function () {
        Route::get('/',        'index')->name('supplier.index');
        Route::post('/',       'store')->name('supplier.store');
        Route::put('/{id}',    'update')->name('supplier.update');
        Route::delete('/{id}', 'destroy')->name('supplier.destroy');
    });

    Route::prefix('produk')->controller(ProdukController::class)->group(function () {
        Route::get('/',              'index')->name('produk.index');
        Route::post('/',             'store')->name('produk.store');
        Route::put('/{id}',          'update')->name('produk.update');
        Route::delete('/hapus-semua', 'destroyAll')->name('produk.destroy-all');
        Route::delete('/{id}',       'destroy')->name('produk.destroy');
    });

    Route::prefix('stock-in')->controller(StockInController::class)->group(function () {
        Route::get('/',                                    'index')->name('stock-in.index');
        Route::post('/',                                   'store')->name('stock-in.store');
        Route::delete('/{id}',                             'destroy')->name('stock-in.destroy');
        Route::post('/{id}/bayar-cicilan',                 'bayarCicilan')->name('stock-in.bayar-cicilan');
        Route::delete('/{barangMasukId}/cicilan/{cicilanId}', 'hapusCicilan')->name('stock-in.hapus-cicilan');
    });

    Route::prefix('pelanggan')->controller(PelangganController::class)->group(function () {
        Route::get('/',        'index')->name('pelanggan.index');
        Route::post('/',       'store')->name('pelanggan.store');
        Route::put('/{id}',    'update')->name('pelanggan.update');
        Route::delete('/{id}', 'destroy')->name('pelanggan.destroy');
    });

    Route::prefix('transactions')->controller(TransaksiController::class)->group(function () {
        Route::get('/', 'index')->name('transactions.index');
        Route::get('/pos', 'pos')->name('transactions.pos');
        Route::post('/', 'store')->name('transactions.store');
        Route::get('/{id}/struk', 'struk')->name('transactions.struk');
        Route::post('/{id}/batal', 'batal')->name('transactions.batal');
        Route::get('/{id}', 'show')->whereNumber('id')->name('transactions.show');
    });

    Route::prefix('piutang')->controller(PiutangController::class)->name('piutang.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/piutang/{id}/print', 'print')->name('print');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/{id}/bayar', 'bayar')->name('bayar');
        Route::get('/{id}/cetak', 'cetak')->name('cetak');
    });

    Route::prefix('modals')->controller(ModalController::class)->group(function () {
        Route::get('/', 'index')->name('modals.index');
        Route::post('/', 'store')->name('modals.store');
        Route::get('/{id}', 'show')->name('modals.show');
        Route::put('/{id}', 'update')->name('modals.update');
        Route::delete('/{id}', 'destroy')->name('modals.destroy');
        Route::post('/{id}/bayar-cicilan', 'bayarCicilan')->name('modals.bayar-cicilan');
        Route::post('/kalkulasi', 'kalkulasi')->name('modals.kalkulasi');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/',        [PengaturanTokoController::class, 'index'])->name('index');
        Route::put('/',        [PengaturanTokoController::class, 'update'])->name('update');
        Route::delete('/logo', [PengaturanTokoController::class, 'deleteLogo'])->name('delete-logo');
    });

    Route::get('/laporan',        [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak',  [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
});
