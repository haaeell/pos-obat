<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $supplier = Supplier::withCount('produk')
            ->latest()
            ->get();

        return view('master.supplier.index', compact('supplier'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'          => 'required|string|max:50|unique:supplier,kode',
            'nama'          => 'required|string|max:255',
            'kontak_person' => 'nullable|string|max:255',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'alamat'        => 'nullable|string',
            'catatan'       => 'nullable|string',
        ], [
            'kode.required' => 'Kode supplier wajib diisi.',
            'kode.unique'   => 'Kode supplier sudah digunakan.',
            'nama.required' => 'Nama supplier wajib diisi.',
            'email.email'   => 'Format email tidak valid.',
        ]);

        Supplier::create([
            'kode'          => strtoupper($request->kode),
            'nama'          => $request->nama,
            'kontak_person' => $request->kontak_person,
            'telepon'       => $request->telepon,
            'email'         => $request->email,
            'alamat'        => $request->alamat,
            'catatan'       => $request->catatan,
            'is_aktif'      => true,
        ]);

        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'kode'          => 'required|string|max:50|unique:supplier,kode,' . $id,
            'nama'          => 'required|string|max:255',
            'kontak_person' => 'nullable|string|max:255',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'alamat'        => 'nullable|string',
            'catatan'       => 'nullable|string',
        ], [
            'kode.required' => 'Kode supplier wajib diisi.',
            'kode.unique'   => 'Kode supplier sudah digunakan.',
            'nama.required' => 'Nama supplier wajib diisi.',
            'email.email'   => 'Format email tidak valid.',
        ]);

        $supplier->update([
            'kode'          => strtoupper($request->kode),
            'nama'          => $request->nama,
            'kontak_person' => $request->kontak_person,
            'telepon'       => $request->telepon,
            'email'         => $request->email,
            'alamat'        => $request->alamat,
            'catatan'       => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::withCount('produk')->findOrFail($id);

        if ($supplier->produk_count > 0) {
            return redirect()->back()->with(
                'error',
                'Supplier tidak dapat dihapus karena masih digunakan oleh ' . $supplier->produk_count . ' produk.'
            );
        }

        $supplier->delete();

        return redirect()->back()->with('success', 'Supplier berhasil dihapus.');
    }
}
