<?php

namespace App\Http\Controllers;

use App\Exports\TemplateProduk;
use App\Http\Controllers\Controller;
use App\Imports\ProdukImport;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\Kategori;
use App\Models\Piutang;
use App\Models\PiutangPembayaran;
use App\Models\Produk;
use App\Models\StokBatch;
use App\Models\Supplier;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\TransaksiFifoLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProdukController extends Controller
{
    public function index()
    {
        $kategori = Kategori::where('is_aktif', true)->orderBy('nama')->get();
        $supplier = Supplier::where('is_aktif', true)->orderBy('nama')->get();

        return view('master.produk.index', compact('kategori', 'supplier'));
    }

    public function dataTable(Request $request)
    {
        $query = Produk::with(['kategori', 'supplier'])->latest();

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', fn($q) => $q->where('nama', $request->kategori));
        }
        if ($request->filled('supplier')) {
            $query->whereHas('supplier', fn($q) => $q->where('nama', $request->supplier));
        }
        if ($request->filled('status')) {
            $query->where('is_aktif', $request->status === 'Aktif' ? 1 : 0);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('foto_nama', function ($item) {
                $foto = $item->foto
                    ? '<img src="' . asset('storage/' . $item->foto) . '" class="w-8 h-8 rounded object-cover border flex-shrink-0">'
                    : '<div class="w-8 h-8 rounded bg-emerald-50 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-seedling text-emerald-400 text-xs"></i></div>';
                return '<div class="flex items-center gap-2">' . $foto . '<span class="font-medium text-slate-800">' . e($item->nama) . '</span></div>';
            })
            ->addColumn(
                'kategori_nama',
                fn($item) =>
                '<span class="px-2 py-1 text-xs rounded-full bg-blue-50 text-blue-700 font-medium">' . e($item->kategori->nama ?? '-') . '</span>'
            )
            ->addColumn(
                'supplier_nama',
                fn($item) =>
                '<span class="text-slate-500 text-xs">' . e($item->supplier->nama ?? '-') . '</span>'
            )
            ->addColumn(
                'harga_jual_fmt',
                fn($item) =>
                'Rp ' . number_format($item->harga_jual, 0, ',', '.')
            )
            ->addColumn('stok_badge', function ($item) {
                $rendah = $item->stok_saat_ini <= $item->stok_minimum;
                $cls    = $rendah ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700';
                $icon   = $rendah ? '<i class="fa-solid fa-triangle-exclamation text-[10px]"></i>' : '';
                return '<span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full ' . $cls . '">'
                    . $icon . $item->stok_saat_ini . ' ' . e($item->satuan) . '</span>';
            })
            ->addColumn('status_badge', function ($item) {
                return $item->is_aktif
                    ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Aktif</span>'
                    : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-slate-200 text-slate-500">Non-aktif</span>';
            })
            ->addColumn('aksi', function ($item) {
                return '
                <div class="flex items-center justify-center gap-1">
                    <button onclick="printBarcode(' . $item->id . ')"
                        class="px-3 py-1.5 bg-slate-600 text-white hover:bg-slate-700 rounded-lg text-xs font-semibold" title="Cetak Barcode">
                        <i class="fa-solid fa-barcode"></i>
                    </button>
                    <button onclick=\'openEditModal(' . json_encode($item) . ')\'
                        class="px-3 py-1.5 bg-amber-400 hover:bg-amber-500 rounded-lg text-xs font-semibold" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button onclick="deleteProduk(' . $item->id . ', \'' . addslashes($item->nama) . '\')"
                        class="px-3 py-1.5 bg-red-500 text-white hover:bg-red-600 rounded-lg text-xs font-semibold" title="Hapus">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>';
            })
            ->filterColumn('kategori_nama', fn($q, $k) => $q->whereHas('kategori', fn($q2) => $q2->where('nama', 'like', "%$k%")))
            ->filterColumn('supplier_nama', fn($q, $k) => $q->whereHas('supplier',  fn($q2) => $q2->where('nama', 'like', "%$k%")))
            ->filterColumn('status_badge',  fn($q, $k) => $q->where('is_aktif', $k === 'Aktif' ? 1 : 0))
            ->rawColumns(['foto_nama', 'kategori_nama', 'supplier_nama', 'stok_badge', 'status_badge', 'aksi'])
            ->make(true);
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

    public function destroyAll()
    {
        try {
            Produk::whereNotNull('foto')
                ->get()
                ->each(function ($p) {
                    Storage::disk('public')->delete($p->foto);
                });

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            Piutang::truncate();
            TransaksiFifoLog::truncate();
            TransaksiDetail::truncate();
            Transaksi::truncate();
            StokBatch::truncate();
            BarangMasukDetail::truncate();
            BarangMasuk::truncate();
            Produk::truncate();

            // hapus permanen yang soft delete
            Kategori::onlyTrashed()->forceDelete();
            Supplier::onlyTrashed()->forceDelete();

            return redirect()
                ->back()
                ->with('success', 'Semua data berhasil direset.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function template()
    {
        return Excel::download(new TemplateProduk(), 'template_produk.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_import' => 'required|mimes:xlsx,xls'
        ], [
            'file_import.required' => 'Silahkan pilih file terlebih dahulu',
            'file_import.mimes' => 'Format file harus .xlsx atau .xls'
        ]);

        try {
            Excel::import(new ProdukImport(), $request->file('file_import'));
            return redirect()->back()->with('success', 'Data produk berhasil di-import!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ada masalah saat import: ' . $e->getMessage());
        }
    }
}
