<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::orderBy('nama');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('nama', 'like', "%{$q}%")
                    ->orWhere('telepon', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_aktif', $request->status === 'aktif');
        }

        $pelanggan = $query->paginate(20)->withQueryString();

        return view('pelanggan.index', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat'  => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:500',
        ]);

        $pelanggan = Pelanggan::create([
            'nama'     => $request->nama,
            'telepon'  => $request->telepon,
            'alamat'   => $request->alamat,
            'catatan'  => $request->catatan,
            'is_aktif' => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'id'      => $pelanggan->id,
                'nama'    => $pelanggan->nama,
                'telepon' => $pelanggan->telepon,
            ]);
        }

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $request->validate([
            'nama'    => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat'  => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:500',
        ]);

        $pelanggan->update([
            'nama'    => $request->nama,
            'telepon' => $request->telepon,
            'alamat'  => $request->alamat,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }

    public function restore($id)
    {
        $pelanggan = Pelanggan::withTrashed()->findOrFail($id);
        $pelanggan->restore();
        $pelanggan->update(['is_aktif' => true]);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dipulihkan.');
    }
}
