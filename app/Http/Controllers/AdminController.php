<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        $antrianHariIni = Antrian::whereDate('tanggal_antrian', $today)
            ->orderBy('created_at', 'asc')
            ->get();

        $statistik = [
            'menunggu' => Antrian::whereDate('tanggal_antrian', $today)->where('status', 'menunggu')->count(),
            'dipanggil' => Antrian::whereDate('tanggal_antrian', $today)->where('status', 'dipanggil')->count(),
            'selesai' => Antrian::whereDate('tanggal_antrian', $today)->where('status', 'selesai')->count(),
            'total' => Antrian::whereDate('tanggal_antrian', $today)->count(),
        ];

        $antrianSekarang = Antrian::whereDate('tanggal_antrian', $today)
            ->where('status', 'dipanggil')
            ->first();

        return view('admin.dashboard', compact('antrianHariIni', 'statistik', 'antrianSekarang'));
    }

    public function panggil($id)
    {
        try {
            // Cari antrian
            $antrian = Antrian::find($id);

            if (!$antrian) {
                return response()->json(['success' => false, 'message' => 'Antrian tidak ditemukan'], 404);
            }

            // Reset status dipanggil sebelumnya
            Antrian::where('status', 'dipanggil')->update(['status' => 'menunggu']);

            // Update antrian yang dipanggil
            $antrian->status = 'dipanggil';
            $antrian->waktu_panggil = Carbon::now();
            $antrian->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil memanggil antrian',
                'antrian' => $antrian
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function selesai($id)
    {
        try {
            $antrian = Antrian::find($id);

            if (!$antrian) {
                return response()->json(['success' => false, 'message' => 'Antrian tidak ditemukan'], 404);
            }

            $antrian->status = 'selesai';
            $antrian->waktu_selesai = Carbon::now();
            $antrian->save();

            return response()->json(['success' => true, 'message' => 'Antrian selesai']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function batal($id)
    {
        try {
            $antrian = Antrian::find($id);

            if (!$antrian) {
                return response()->json(['success' => false, 'message' => 'Antrian tidak ditemukan'], 404);
            }

            $antrian->status = 'batal';
            $antrian->save();

            return response()->json(['success' => true, 'message' => 'Antrian dibatalkan']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getAntrianRealtime()
    {
        try {
            $today = Carbon::today();

            $antrian = Antrian::whereDate('tanggal_antrian', $today)
                ->orderBy('created_at', 'asc')
                ->get();

            $statistik = [
                'menunggu' => Antrian::whereDate('tanggal_antrian', $today)->where('status', 'menunggu')->count(),
                'dipanggil' => Antrian::whereDate('tanggal_antrian', $today)->where('status', 'dipanggil')->count(),
                'selesai' => Antrian::whereDate('tanggal_antrian', $today)->where('status', 'selesai')->count(),
                'total' => Antrian::whereDate('tanggal_antrian', $today)->count(),
            ];

            $antrianSekarang = Antrian::whereDate('tanggal_antrian', $today)
                ->where('status', 'dipanggil')
                ->first();

            return response()->json([
                'success' => true,
                'antrian' => $antrian,
                'statistik' => $statistik,
                'antrian_sekarang' => $antrianSekarang
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan halaman kalender antrian
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
   public function kalender(Request $request)
{
    // Ambil tanggal dari request, jika ada parse ke Carbon, jika tidak pakai hari ini
    if ($request->get('tanggal')) {
        $tanggal = Carbon::parse($request->get('tanggal'));
    } else {
        $tanggal = Carbon::today();
    }

    // Ambil antrian berdasarkan tanggal yang dipilih
    $antrian = Antrian::whereDate('tanggal_antrian', $tanggal)
        ->orderBy('created_at', 'asc')
        ->get();

    // Ambil semua tanggal yang memiliki antrian
    $tanggalDenganAntrian = Antrian::selectRaw('DATE(tanggal_antrian) as tanggal')
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'desc')
        ->get()
        ->pluck('tanggal')
        ->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        });

    return view('admin.kalender', compact('antrian', 'tanggal', 'tanggalDenganAntrian'));
}
}
