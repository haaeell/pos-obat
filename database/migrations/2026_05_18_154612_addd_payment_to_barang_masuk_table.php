<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->enum('status_bayar', ['lunas', 'sebagian', 'hutang'])->default('lunas')->after('catatan');
            $table->decimal('total_dibayar', 15, 2)->default(0)->after('status_bayar');
            $table->date('tanggal_jatuh_tempo')->nullable()->after('total_dibayar');
            $table->text('catatan_pembayaran')->nullable()->after('tanggal_jatuh_tempo');
        });

        // ── 2. Tabel riwayat cicilan / pembayaran ─────────────────────────
        Schema::create('hutang_supplier_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuk')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->enum('metode_bayar', ['tunai', 'transfer', 'cek', 'lainnya'])->default('tunai');
            $table->string('nomor_referensi')->nullable(); // no. transfer / cek
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hutang_supplier_pembayaran');

        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropColumn([
                'status_bayar',
                'total_dibayar',
                'tanggal_jatuh_tempo',
                'catatan_pembayaran',
            ]);
        });
    }
};
