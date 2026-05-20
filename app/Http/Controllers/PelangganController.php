<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::withCount('transaksi')
            ->latest()
            ->get()
            ->map(function ($p) {
                $p->total_piutang = $p->totalPiutang();
                return $p;
            });

        return view('pelanggan.index', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'telepon'  => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
            'catatan'  => 'nullable|string',
        ], [
            'nama.required' => 'Nama pelanggan wajib diisi.',
        ]);

        Pelanggan::create([
            'nama'     => $request->nama,
            'telepon'  => $request->telepon,
            'alamat'   => $request->alamat,
            'catatan'  => $request->catatan,
            'is_aktif' => true,
        ]);

        return redirect()->back()->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:255',
            'telepon'  => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
            'catatan'  => 'nullable|string',
            'is_aktif' => 'boolean',
        ], [
            'nama.required' => 'Nama pelanggan wajib diisi.',
        ]);

        $pelanggan->update([
            'nama'     => $request->nama,
            'telepon'  => $request->telepon,
            'alamat'   => $request->alamat,
            'catatan'  => $request->catatan,
            'is_aktif' => $request->boolean('is_aktif'),
        ]);

        return redirect()->back()->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::withCount('transaksi')->findOrFail($id);

        if ($pelanggan->transaksi_count > 0) {
            return redirect()->back()->with(
                'error',
                'Pelanggan tidak dapat dihapus karena memiliki ' . $pelanggan->transaksi_count . ' transaksi.'
            );
        }

        $pelanggan->delete();

        return redirect()->back()->with('success', 'Pelanggan berhasil dihapus.');
    }
}
