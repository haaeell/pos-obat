<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\StokBatch;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produk   = Produk::with(['kategori', 'supplier'])->latest()->get();
        $kategori = Kategori::where('is_aktif', true)->orderBy('nama')->get();
        $supplier = Supplier::where('is_aktif', true)->orderBy('nama')->get();

        return view('master.produk.index', compact('produk', 'kategori', 'supplier'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategori,id',
            'supplier_id'  => 'nullable|exists:supplier,id',
            'satuan'       => 'required|string|max:30',
            'harga_jual'   => 'required|numeric|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'catatan'      => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama.required'        => 'Nama produk wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists'   => 'Kategori tidak valid.',
            'satuan.required'      => 'Satuan wajib diisi.',
            'harga_jual.required'  => 'Harga jual wajib diisi.',
            'stok_minimum.required' => 'Stok minimum wajib diisi.',
        ]);

        // Generate kode otomatis: PRD-001
        $last  = Produk::withTrashed()->orderByDesc('id')->first();
        $nomor = $last ? (int) substr($last->kode, 4) + 1 : 1;
        $kode  = 'PRD-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('produk', 'public');
        }

        $produk = Produk::create([
            'kode'         => $kode,
            'nama'         => $request->nama,
            'kategori_id'  => $request->kategori_id,
            'supplier_id'  => $request->supplier_id,
            'satuan'       => $request->satuan,
            'harga_jual'   => $request->harga_jual,
            'stok_minimum' => $request->stok_minimum,
            'stok_saat_ini' => 0,
            'catatan'      => $request->catatan,
            'foto'         => $foto,
            'is_aktif'     => true,
        ]);

        if ($request->filled('stok_awal') && $request->stok_awal > 0) {
            $hargaModal = $request->harga_modal_awal ?? 0;

            $barangMasuk = BarangMasuk::create([
                'nomor'      => 'SA-' . now()->format('Ymd') . '-' . str_pad($produk->id, 3, '0', STR_PAD_LEFT),
                'jenis'      => 'stok_awal',
                'supplier_id' => null,
                'user_id'    => auth()->id(),
                'tanggal'    => now()->toDateString(),
                'catatan'    => 'Stok awal produk: ' . $produk->nama,
            ]);

            $detail = $barangMasuk->detail()->create([
                'produk_id'   => $produk->id,
                'jumlah'      => $request->stok_awal,
                'harga_modal' => $hargaModal,
                'subtotal'    => $request->stok_awal * $hargaModal,
            ]);

            StokBatch::create([
                'produk_id'              => $produk->id,
                'barang_masuk_detail_id' => $detail->id,
                'tanggal_masuk'          => now()->toDateString(),
                'harga_modal'            => $hargaModal,
                'jumlah_awal'            => $request->stok_awal,
                'jumlah_tersisa'         => $request->stok_awal,
            ]);

            $produk->update(['stok_saat_ini' => $request->stok_awal]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama'         => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategori,id',
            'supplier_id'  => 'nullable|exists:supplier,id',
            'satuan'       => 'required|string|max:30',
            'harga_jual'   => 'required|numeric|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'catatan'      => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama.required'        => 'Nama produk wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'satuan.required'      => 'Satuan wajib diisi.',
            'harga_jual.required'  => 'Harga jual wajib diisi.',
            'stok_minimum.required' => 'Stok minimum wajib diisi.',
        ]);

        $foto = $produk->foto;
        if ($request->hasFile('foto')) {
            if ($foto) Storage::disk('public')->delete($foto);
            $foto = $request->file('foto')->store('produk', 'public');
        }

        $produk->update([
            'nama'         => $request->nama,
            'kategori_id'  => $request->kategori_id,
            'supplier_id'  => $request->supplier_id,
            'satuan'       => $request->satuan,
            'harga_jual'   => $request->harga_jual,
            'stok_minimum' => $request->stok_minimum,
            'catatan'      => $request->catatan,
            'foto'         => $foto,
            'is_aktif'     => $request->boolean('is_aktif', true),
        ]);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $produk = Produk::withCount('transaksiDetail')->findOrFail($id);

        if ($produk->transaksi_detail_count > 0) {
            return redirect()->back()->with(
                'error',
                'Produk tidak dapat dihapus karena sudah ada dalam transaksi.'
            );
        }

        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
