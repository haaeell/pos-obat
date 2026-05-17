<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // 1. USERS & PENGATURAN
        // ============================================================

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['owner', 'admin', 'kasir'])->default('kasir');
            $table->boolean('is_aktif')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('pengaturan_toko', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');
            $table->text('alamat')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();               // path file logo
            $table->text('header_struk')->nullable();
            $table->text('footer_struk')->nullable();
            $table->integer('stok_minimum_default')->default(5);
            $table->timestamps();
        });

        // ============================================================
        // 2. MASTER DATA
        // ============================================================

        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('supplier', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();                 // SUP-001
            $table->string('nama');
            $table->string('kontak_person')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();                 // PRD-001
            $table->string('nama');
            $table->foreignId('kategori_id')->constrained('kategori')->restrictOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('supplier')->nullOnDelete();
            $table->string('satuan', 30);                     // kg, liter, botol, karung, dll
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->integer('stok_minimum')->default(5);
            $table->integer('stok_saat_ini')->default(0);     // dihitung otomatis
            $table->string('foto')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // ============================================================
        // 3. BARANG MASUK & STOK (FIFO)
        // ============================================================

        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();                // BM-20250101-001 / SA-20250101-001
            $table->enum('jenis', ['stok_awal', 'masuk_normal'])->default('masuk_normal');
            // jenis = 'stok_awal'    → input saat setup produk pertama kali, supplier bisa null
            // jenis = 'masuk_normal' → penerimaan barang rutin dari supplier
            $table->foreignId('supplier_id')->nullable()->constrained('supplier')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->date('tanggal');
            $table->string('nomor_faktur_supplier')->nullable(); // null untuk stok_awal
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('barang_masuk_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuk')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->restrictOnDelete();
            $table->integer('jumlah');
            $table->decimal('harga_modal', 15, 2);            // harga beli per satuan (estimasi untuk stok_awal)
            $table->decimal('subtotal', 15, 2);               // jumlah * harga_modal
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Tabel batch FIFO — setiap detail barang masuk membuat satu entri batch.
        // Saat penjualan, sistem mengambil dari batch dengan tanggal masuk paling lama.
        // Stok awal pun masuk ke sini sehingga FIFO langsung berjalan konsisten.
        Schema::create('stok_batch', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->restrictOnDelete();
            $table->foreignId('barang_masuk_detail_id')->constrained('barang_masuk_detail')->restrictOnDelete();
            $table->date('tanggal_masuk');
            $table->decimal('harga_modal', 15, 2);            // harga modal batch ini
            $table->integer('jumlah_awal');                   // jumlah saat diterima
            $table->integer('jumlah_tersisa');                // sisa stok batch ini
            $table->timestamps();

            $table->index(['produk_id', 'tanggal_masuk']);    // index untuk query FIFO
        });

        // ============================================================
        // 4. PELANGGAN
        // ============================================================

        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // ============================================================
        // 5. TRANSAKSI PENJUALAN
        // ============================================================

        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();                // TRX-20250101-001
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggan')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete(); // kasir
            $table->date('tanggal');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('diskon_nominal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);              // subtotal - diskon
            $table->decimal('total_hpp', 15, 2)->default(0);          // total HPP berdasarkan FIFO
            $table->enum('status_bayar', ['lunas', 'sebagian', 'belum_bayar'])->default('lunas');
            $table->decimal('jumlah_bayar', 15, 2)->default(0);       // yang sudah dibayar
            $table->decimal('sisa_tagihan', 15, 2)->default(0);       // total - jumlah_bayar
            $table->decimal('kembalian', 15, 2)->default(0);          // khusus transaksi lunas
            $table->text('catatan')->nullable();
            $table->enum('status', ['aktif', 'dibatalkan'])->default('aktif');
            $table->timestamp('dibatalkan_pada')->nullable();
            $table->foreignId('dibatalkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->text('alasan_batal')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->restrictOnDelete();
            $table->integer('jumlah');
            $table->decimal('harga_jual', 15, 2);             // harga saat transaksi
            $table->decimal('diskon_item', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2);               // (harga_jual - diskon_item) * jumlah
            $table->decimal('hpp', 15, 2)->default(0);        // HPP item ini berdasarkan FIFO
            $table->timestamps();
        });

        // Log FIFO — mencatat batch mana yang dipakai per item transaksi
        Schema::create('transaksi_fifo_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_detail_id')->constrained('transaksi_detail')->cascadeOnDelete();
            $table->foreignId('stok_batch_id')->constrained('stok_batch')->restrictOnDelete();
            $table->integer('jumlah_dipakai');
            $table->decimal('harga_modal', 15, 2);            // harga modal batch yang dipakai
            $table->decimal('subtotal_hpp', 15, 2);           // jumlah_dipakai * harga_modal
            $table->timestamps();
        });

        // ============================================================
        // 6. PIUTANG PELANGGAN
        // ============================================================

        Schema::create('piutang', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();                // PIU-20250101-001
            $table->foreignId('transaksi_id')->constrained('transaksi')->restrictOnDelete();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->restrictOnDelete();
            $table->decimal('total_tagihan', 15, 2);
            $table->decimal('sudah_dibayar', 15, 2)->default(0);
            $table->decimal('sisa_tagihan', 15, 2);           // total_tagihan - sudah_dibayar
            $table->enum('status', ['belum_bayar', 'sebagian', 'lunas'])->default('belum_bayar');
            $table->date('tanggal_transaksi');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->timestamp('dilunasi_pada')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('piutang_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piutang_id')->constrained('piutang')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->date('tanggal');
            $table->decimal('jumlah', 15, 2);
            $table->string('metode_bayar', 50)->default('tunai'); // tunai, transfer, dll
            $table->string('referensi')->nullable();           // nomor bukti transfer, dll
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // ============================================================
        // 7. MODAL & HUTANG TOKO
        // ============================================================

        Schema::create('modal_hutang', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();                // MOD-001 / HUT-001
            $table->enum('jenis', ['modal_sendiri', 'pinjaman']);
            $table->string('nama_sumber');                    // "Modal Awal", "Bank BRI", "Koperasi X"
            $table->string('nama_kreditur')->nullable();      // khusus pinjaman
            $table->string('telepon_kreditur', 20)->nullable();
            $table->decimal('jumlah_total', 15, 2);
            $table->date('tanggal_diterima');
            // --- khusus pinjaman ---
            $table->integer('jangka_bulan')->nullable();      // durasi pinjaman dalam bulan
            $table->decimal('bunga_persen', 5, 2)->nullable()->default(0); // % per tahun
            $table->decimal('cicilan_per_bulan', 15, 2)->nullable()->default(0);
            $table->date('tanggal_jatuh_tempo')->nullable();
            // --- rekapitulasi (khusus pinjaman) ---
            $table->decimal('sudah_dibayar', 15, 2)->default(0);
            $table->decimal('sisa_hutang', 15, 2)->default(0);
            $table->enum('status', ['aktif', 'lunas'])->default('aktif');
            $table->timestamp('dilunasi_pada')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('modal_hutang_cicilan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modal_hutang_id')->constrained('modal_hutang')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->date('tanggal');
            $table->decimal('jumlah', 15, 2);
            $table->string('metode_bayar', 50)->default('tunai');
            $table->string('referensi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // ============================================================
        // 8. CACHE & JOBS (Laravel default)
        // ============================================================

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');

        Schema::dropIfExists('modal_hutang_cicilan');
        Schema::dropIfExists('modal_hutang');

        Schema::dropIfExists('piutang_pembayaran');
        Schema::dropIfExists('piutang');

        Schema::dropIfExists('transaksi_fifo_log');
        Schema::dropIfExists('transaksi_detail');
        Schema::dropIfExists('transaksi');

        Schema::dropIfExists('pelanggan');

        Schema::dropIfExists('stok_batch');
        Schema::dropIfExists('barang_masuk_detail');
        Schema::dropIfExists('barang_masuk');

        Schema::dropIfExists('produk');
        Schema::dropIfExists('supplier');
        Schema::dropIfExists('kategori');

        Schema::dropIfExists('pengaturan_toko');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
