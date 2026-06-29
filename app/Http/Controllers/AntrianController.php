<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AntrianController extends Controller
{
    public function form()
    {
        return view('antrian.form');
    }

    /**
     * Get prefix huruf berdasarkan keperluan
     */
    private function getPrefixByKeperluan($keperluan)
    {
        $prefixMap = [
            'SKCK' => 'A',
            'Surat Keterangan Tidak Mampu (SKTM)' => 'B',
            'Konsultasi' => 'C',
            'Kehilangan' => 'D',
            'Administrasi' => 'E',
            'Lainnya' => 'F',
        ];

        return $prefixMap[$keperluan] ?? 'X';
    }

    /**
     * Get nomor urut terakhir untuk prefix dan tanggal tertentu
     */
    private function getLastNumber($prefix, $today)
    {
        $lastAntrian = Antrian::whereDate('tanggal_antrian', $today)
            ->where('nomor_antrian', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastAntrian) {
            // Ambil angka dari nomor antrian (contoh: A001 -> 1)
            $lastNumber = (int) substr($lastAntrian->nomor_antrian, 1);
            return $lastNumber + 1;
        }

        return 1;
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required',
            'nik' => 'required|size:16',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'keperluan' => 'required',
        ]);

        $today = Carbon::today();

        // Dapatkan prefix berdasarkan keperluan
        $prefix = $this->getPrefixByKeperluan($request->keperluan);

        // Dapatkan nomor urut terakhir untuk prefix dan hari ini
        $lastNumber = $this->getLastNumber($prefix, $today);

        // Format nomor antrian: PREFIX + 3 digit angka (A001, B002, C003, dst)
        $nomorAntrian = $prefix . str_pad($lastNumber, 3, '0', STR_PAD_LEFT);

        // Simpan data
        $antrian = Antrian::create([
            'nomor_antrian' => $nomorAntrian,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'keperluan' => $request->keperluan,
            'status' => 'menunggu',
            'tanggal_antrian' => $today,
            'waktu_daftar' => Carbon::now(),
        ]);

        return redirect()->route('antrian.cetak', $antrian->id)
            ->with('success', 'Pendaftaran berhasil! Nomor antrian: ' . $nomorAntrian);
    }

    public function cetak($id)
    {
        $antrian = Antrian::findOrFail($id);
        return view('antrian.cetak', compact('antrian'));
    }

    public function layar()
    {
        return view('antrian.layar');
    }

    public function getAntrianLayar()
    {
        $today = Carbon::today();

        $antrianSekarang = Antrian::whereDate('tanggal_antrian', $today)
            ->where('status', 'dipanggil')
            ->first();

        $antrianMenunggu = Antrian::whereDate('tanggal_antrian', $today)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        $antrianSelesai = Antrian::whereDate('tanggal_antrian', $today)
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'antrian_sekarang' => $antrianSekarang,
            'antrian_menunggu' => $antrianMenunggu,
            'antrian_selesai' => $antrianSelesai,
            'total_menunggu' => Antrian::whereDate('tanggal_antrian', $today)->where('status', 'menunggu')->count()
        ]);
    }
}
