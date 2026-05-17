<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withCount('produk')
            ->latest()
            ->get();

        return view('master.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255|unique:kategori,nama',
            'deskripsi' => 'nullable|string',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique'   => 'Nama kategori sudah digunakan.',
        ]);

        Kategori::create([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_aktif'  => true,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:255|unique:kategori,nama,' . $id,
            'deskripsi' => 'nullable|string',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique'   => 'Nama kategori sudah digunakan.',
        ]);

        $kategori->update([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = Kategori::withCount('produk')->findOrFail($id);

        if ($kategori->produk_count > 0) {
            return redirect()->back()->with(
                'error',
                'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $kategori->produk_count . ' produk.'
            );
        }

        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
