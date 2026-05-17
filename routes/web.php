<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();

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
        Route::get('/',        'index')->name('produk.index');
        Route::post('/',       'store')->name('produk.store');
        Route::put('/{id}',    'update')->name('produk.update');
        Route::delete('/{id}', 'destroy')->name('produk.destroy');
    });

    Route::prefix('stock-in')->controller(StockInController::class)->group(function () {
        Route::get('/',        'index')->name('stock-in.index');
        Route::post('/',       'store')->name('stock-in.store');
        Route::delete('/{id}', 'destroy')->name('stock-in.destroy');
    });

    Route::prefix('pelanggan')->controller(PelangganController::class)->group(function () {
        Route::get('/',        'index')->name('pelanggan.index');
        Route::post('/',       'store')->name('pelanggan.store');
        Route::put('/{id}',    'update')->name('pelanggan.update');
        Route::delete('/{id}', 'destroy')->name('pelanggan.destroy');
    });

    Route::prefix('transactions')->controller(TransaksiController::class)->group(function () {
        Route::get('/',        'index')->name('transactions.index');
        Route::get('/pos',     'pos')->name('transactions.pos');
        Route::post('/',       'store')->name('transactions.store');
        Route::get('/{id}',       'show')->name('transactions.show');
        Route::get('/{id}/struk', 'struk')->name('transactions.struk');
    });

    Route::prefix('piutang')->name('piutang.')->group(function () {
        Route::get('/', [PiutangController::class, 'index'])->name('index');
        Route::get('/{id}', [PiutangController::class, 'show'])->name('show');

        Route::post('/{id}/bayar', [PiutangController::class, 'bayar'])
            ->name('bayar');

        Route::get('/{id}/cetak', [PiutangController::class, 'cetak'])
            ->name('cetak');
    });
});
