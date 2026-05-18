<?php

namespace App\Http\Controllers;

use App\Models\PengaturanToko;
use App\Models\Piutang;
use App\Models\PiutangPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PiutangController extends Controller
{
    public function index(Request $request)
    {
        $query = Piutang::with([
            'pelanggan',
            'transaksi',
            'pembayaran.user'
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor', 'like', '%' . $request->keyword . '%')
                    ->orWhereHas('pelanggan', function ($qq) use ($request) {
                        $qq->where('nama', 'like', '%' . $request->keyword . '%');
                    });
            });
        }

        if ($request->filled('dari')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->sampai);
        }

        $piutang = $query->paginate(15);

        $totalPiutang = Piutang::sum('sisa_tagihan');

        $totalDibayar = Piutang::sum('sudah_dibayar');

        return view('piutang.index', compact(
            'piutang',
            'totalPiutang',
            'totalDibayar'
        ));
    }

    public function show($id)
    {
        $piutang = Piutang::with([
            'pelanggan',
            'transaksi',
            'pembayaran.user'
        ])->findOrFail($id);

        return view('piutang.show', compact('piutang'));
    }

    public function bayar(Request $request, $id)
    {
        $piutang = Piutang::findOrFail($id);

        if ($piutang->status === 'lunas') {
            return back()->with('error', 'Piutang sudah lunas.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:1|max:' . $piutang->sisa_tagihan,
            'metode_bayar' => 'required|string|max:50',
            'referensi' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
        ], [
            'tanggal.required' => 'Tanggal pembayaran wajib diisi.',
            'jumlah.required' => 'Jumlah pembayaran wajib diisi.',
            'jumlah.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'jumlah.min' => 'Jumlah pembayaran minimal Rp1.',
            'jumlah.max' => 'Jumlah pembayaran melebihi sisa tagihan.',
            'metode_bayar.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        DB::beginTransaction();

        try {

            PiutangPembayaran::create([
                'piutang_id' => $piutang->id,
                'user_id' => auth()->id(),
                'tanggal' => $request->tanggal,
                'jumlah' => $request->jumlah,
                'metode_bayar' => $request->metode_bayar,
                'referensi' => $request->referensi,
                'catatan' => $request->catatan,
            ]);

            $sudahDibayar = $piutang->sudah_dibayar + $request->jumlah;
            $sisa = $piutang->total_tagihan - $sudahDibayar;

            $status = 'sebagian';

            if ($sisa <= 0) {
                $status = 'lunas';
                $sisa = 0;
            }

            $piutang->update([
                'sudah_dibayar' => $sudahDibayar,
                'sisa_tagihan' => $sisa,
                'status' => $status,
                'dilunasi_pada' => $status === 'lunas' ? now() : null,
            ]);

            $piutang->transaksi->update([
                'jumlah_bayar' => $sudahDibayar,
                'sisa_tagihan' => $sisa,
                'status_bayar' => $status,
            ]);

            DB::commit();

            return back()->with('success', 'Pembayaran berhasil disimpan.');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function print($id)
    {
        $piutang = Piutang::with([
            'pelanggan',
            'transaksi',
            'pembayaran.user'
        ])->findOrFail($id);

        $toko = PengaturanToko::first();

        return view('piutang.print', compact('piutang', 'toko'));
    }
}
