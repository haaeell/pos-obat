<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\Produk;
use App\Models\StokBatch;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index()
    {
        $barangMasuk = BarangMasuk::with(['supplier', 'user', 'detail.produk'])
            ->where('jenis', 'masuk_normal')
            ->latest()
            ->get();

        $produk   = Produk::where('is_aktif', true)->orderBy('nama')->get();
        $supplier = Supplier::where('is_aktif', true)->orderBy('nama')->get();

        return view('inventory.stock-in.index', compact('barangMasuk', 'produk', 'supplier'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'             => 'nullable|exists:supplier,id',
            'tanggal'                 => 'required|date',
            'nomor_faktur_supplier'   => 'nullable|string|max:255',
            'catatan'                 => 'nullable|string',
            'items'                   => 'required|array|min:1',
            'items.*.produk_id'       => 'required|exists:produk,id',
            'items.*.jumlah'          => 'required|integer|min:1',
            'items.*.harga_modal'     => 'required|numeric|min:0',
        ], [
            'items.required'              => 'Minimal satu produk harus diisi.',
            'items.*.produk_id.required'  => 'Produk wajib dipilih.',
            'items.*.jumlah.required'     => 'Jumlah wajib diisi.',
            'items.*.jumlah.min'          => 'Jumlah minimal 1.',
            'items.*.harga_modal.required' => 'Harga modal wajib diisi.',
        ]);

        DB::transaction(function () use ($request) {
            // Generate nomor otomatis: BM-20250101-001
            $prefix  = 'BM-' . now()->format('Ymd') . '-';
            $last    = BarangMasuk::where('nomor', 'like', $prefix . '%')->count();
            $nomor   = $prefix . str_pad($last + 1, 3, '0', STR_PAD_LEFT);

            $barangMasuk = BarangMasuk::create([
                'nomor'                  => $nomor,
                'jenis'                  => 'masuk_normal',
                'supplier_id'            => $request->supplier_id,
                'user_id'                => auth()->id(),
                'tanggal'                => $request->tanggal,
                'nomor_faktur_supplier'  => $request->nomor_faktur_supplier,
                'catatan'                => $request->catatan,
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_modal'];

                $detail = BarangMasukDetail::create([
                    'barang_masuk_id'     => $barangMasuk->id,
                    'produk_id'           => $item['produk_id'],
                    'jumlah'              => $item['jumlah'],
                    'harga_modal'         => $item['harga_modal'],
                    'subtotal'            => $subtotal,
                ]);

                // Buat stok batch untuk FIFO
                StokBatch::create([
                    'produk_id'              => $item['produk_id'],
                    'barang_masuk_detail_id' => $detail->id,
                    'tanggal_masuk'          => $request->tanggal,
                    'harga_modal'            => $item['harga_modal'],
                    'jumlah_awal'            => $item['jumlah'],
                    'jumlah_tersisa'         => $item['jumlah'],
                ]);

                // Update stok produk
                Produk::where('id', $item['produk_id'])
                    ->increment('stok_saat_ini', $item['jumlah']);
            }
        });

        return redirect()->back()->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function destroy($id)
    {
        $barangMasuk = BarangMasuk::with('detail')->findOrFail($id);

        DB::transaction(function () use ($barangMasuk) {
            foreach ($barangMasuk->detail as $detail) {
                $batch = StokBatch::where('barang_masuk_detail_id', $detail->id)->first();

                if ($batch) {
                    $terpakai = $batch->jumlah_awal - $batch->jumlah_tersisa;
                    if ($terpakai > 0) {
                        throw new \Exception('Barang masuk tidak dapat dihapus karena sebagian stok sudah terjual.');
                    }
                    $batch->delete();
                }

                Produk::where('id', $detail->produk_id)
                    ->decrement('stok_saat_ini', $detail->jumlah);

                $detail->delete();
            }

            $barangMasuk->delete();
        });

        return redirect()->back()->with('success', 'Data barang masuk berhasil dihapus.');
    }
}
