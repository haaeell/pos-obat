<?php

namespace App\Http\Controllers;

use App\Models\PengaturanToko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanTokoController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanToko::instance();
        return view('settings.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_toko'             => 'required|string|max:255',
            'alamat'                => 'nullable|string|max:500',
            'telepon'               => 'nullable|string|max:20',
            'email'                 => 'nullable|email|max:255',
            'logo'                  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'header_struk'          => 'nullable|string|max:500',
            'footer_struk'          => 'nullable|string|max:500',
        ]);

        $pengaturan = PengaturanToko::instance();

        $data = $request->only([
            'nama_toko',
            'alamat',
            'telepon',
            'email',
            'header_struk',
            'footer_struk',
            'stok_minimum_default',
        ]);

        if ($request->hasFile('logo')) {
            if ($pengaturan->logo && Storage::disk('public')->exists($pengaturan->logo)) {
                Storage::disk('public')->delete($pengaturan->logo);
            }
            $data['logo'] = $request->file('logo')->store('logo', 'public');
        }

        $pengaturan->fill($data);
        $pengaturan->id = 1;
        $pengaturan->save();

        return redirect()->route('settings.index')
            ->with('success', 'Konfigurasi toko berhasil disimpan.');
    }

    public function deleteLogo()
    {
        $pengaturan = PengaturanToko::instance();

        if ($pengaturan->logo && Storage::disk('public')->exists($pengaturan->logo)) {
            Storage::disk('public')->delete($pengaturan->logo);
        }

        $pengaturan->logo = null;
        $pengaturan->id   = 1;
        $pengaturan->save();

        return response()->json(['success' => true, 'message' => 'Logo berhasil dihapus.']);
    }
}
