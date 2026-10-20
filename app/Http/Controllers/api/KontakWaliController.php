<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KontakWali;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Support\Wa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KontakWaliController extends Controller
{
    // GET /api/bk/siswa/{siswa}/kontak-wali
    // Data wali + link WA + riwayat kontak. Pesan bisa diganti lewat ?pesan=
    public function show(Request $request, Siswa $siswa): JsonResponse
    {
        $kelas = $this->namaKelasAktif($siswa);

        $pesan = $request->query('pesan')
            ? Str::limit((string) $request->query('pesan'), 1000, '')
            : $this->pesanDefault($siswa, $kelas);

        $riwayat = KontakWali::with('penghubung:id,name')
            ->where('siswa_id', $siswa->id)
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn ($k) => [
                'id' => $k->id,
                'tujuan' => $k->tujuan,
                'catatan' => $k->catatan,
                'oleh' => $k->penghubung?->name,
                'waktu' => $k->created_at->toDateTimeString(),
            ]);

        return response()->json([
            'siswa' => ['id' => $siswa->id, 'nis' => $siswa->nis, 'nama' => $siswa->nama, 'kelas' => $kelas],
            'wali' => [
                'nama' => $siswa->nama_wali,
                'hubungan' => $siswa->hubungan_wali,
                'no_hp' => $siswa->no_hp_wali,
            ],
            'ada_nomor_wali' => (bool) Wa::normalisasi($siswa->no_hp_wali),
            'wa_link' => Wa::link($siswa->no_hp_wali, $pesan),
            'riwayat' => $riwayat,
        ]);
    }

    // POST /api/bk/siswa/{siswa}/kontak-wali
    // Dipanggil aplikasi saat tombol WA ditekan, untuk mencatat riwayat
    public function store(Request $request, Siswa $siswa): JsonResponse
    {
        $data = $request->validate([
            'tujuan' => ['required', Rule::in(['absensi', 'pelanggaran', 'konseling', 'lainnya'])],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $log = KontakWali::create([
            'siswa_id' => $siswa->id,
            'dihubungi_oleh' => $request->user()->id,
            'tujuan' => $data['tujuan'],
            'catatan' => $data['catatan'] ?? null,
        ]);

        return response()->json(['message' => 'Riwayat kontak tercatat.', 'data' => $log], 201);
    }

    private function namaKelasAktif(Siswa $siswa): ?string
    {
        $ta = TahunAjaran::aktif();

        if (! $ta) {
            return null;
        }

        return DB::table('kelas_siswa')
            ->join('kelas', 'kelas.id', '=', 'kelas_siswa.kelas_id')
            ->where('kelas_siswa.siswa_id', $siswa->id)
            ->where('kelas_siswa.tahun_ajaran_id', $ta->id)
            ->value('kelas.nama');
    }

    private function pesanDefault(Siswa $siswa, ?string $kelas): string
    {
        $wali = $siswa->nama_wali ?: 'Orang Tua/Wali';
        $kelasTeks = $kelas ? " (kelas {$kelas})" : '';

        return "Assalamualaikum Bapak/Ibu {$wali}. "
            . "Kami dari Bimbingan Konseling sekolah ingin berkomunikasi mengenai "
            . "putra/putri Bapak/Ibu, {$siswa->nama}{$kelasTeks}. "
            . "Mohon kesediaan Bapak/Ibu untuk dihubungi. Terima kasih.";
    }
}