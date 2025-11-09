<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Hari;
use App\Models\JadwalKuliah;
use App\Models\Jam;
use App\Models\Kelas;
use App\Models\Matakuliah;
use App\Models\Pengampu;
use App\Models\Ruang;
use Illuminate\Http\Request;

class JadwalKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalKuliah::with(['pengampu.matakuliah', 'pengampu.dosen', 'ruang', 'hari', 'jam', 'pengampu.kelas', 'pengampu.prodi']);

        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->whereHas('pengampu.matakuliah', function ($q) use ($searchTerm) {
                $q->where('nama', 'like', '%'.$searchTerm.'%');
            })->orWhereHas('pengampu.dosen', function ($q) use ($searchTerm) {
                $q->where('nama', 'like', '%'.$searchTerm.'%');
            })->orWhereHas('ruang', function ($q) use ($searchTerm) {
                $q->where('nama_ruang', 'like', '%'.$searchTerm.'%');
            })->orWhereHas('hari', function ($q) use ($searchTerm) {
                $q->where('nama_hari', 'like', '%'.$searchTerm.'%');
            })->orWhereHas('jam', function ($q) use ($searchTerm) {
                $q->where('jam_mulai', 'like', '%'.$searchTerm.'%');
            })->orWhereHas('pengampu.kelas', function ($q) use ($searchTerm) {
                $q->where('nama_kelas', 'like', '%'.$searchTerm.'%');
            });
        }

        $jadwal = $query->paginate(10);
        $jadwal->appends($request->all()); // Preserve query parameters in pagination

        return view('jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $pengampus = Pengampu::with(['matakuliah', 'dosen', 'kelas'])->get();
        $ruangs = Ruang::all();
        $haris = Hari::all();
        $jams = Jam::all();

        return view('jadwal.create', compact('pengampus', 'ruangs', 'haris', 'jams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengampu_id' => 'required|exists:pengampu,id',
            'ruang_id' => 'required|exists:ruang,id',
            'hari_id' => 'required|exists:hari,id',
            'jam_id' => 'required|exists:jam,id',
            'tahun_akademik' => 'required|string',
        ]);

        // Cek apakah sudah ada jadwal dengan ruang, hari, dan jam yang sama
        $existingJadwal = JadwalKuliah::where('ruang_id', $request->ruang_id)
            ->where('hari_id', $request->hari_id)
            ->where('jam_id', $request->jam_id)
            ->first();

        if ($existingJadwal) {
            return redirect()->back()->with('error', 'Jadwal dengan ruang, hari, dan jam yang sama sudah ada.');
        }

        JadwalKuliah::create($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jadwal = JadwalKuliah::with(['pengampu.matakuliah', 'pengampu.dosen', 'ruang', 'hari', 'jam', 'pengampu.kelas', 'pengampu.prodi'])
            ->findOrFail($id);

        $pengampu = Pengampu::with(['matakuliah', 'dosen'])->get();
        $ruang = Ruang::all();
        $hari = Hari::all();
        $jam = Jam::all();
        $kelas = Kelas::all();

        return view('jadwal.edit', compact('jadwal', 'pengampu', 'ruang', 'hari', 'jam', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pengampu_id' => 'required',
            'ruang_id' => 'required',
            'hari_id' => 'required',
            'jam_id' => 'required',
            'tahun_akademik' => 'required',
        ]);

        // Cek apakah sudah ada jadwal dengan ruang, hari, dan jam yang sama
        $existingJadwal = JadwalKuliah::where('ruang_id', $request->ruang_id)
            ->where('hari_id', $request->hari_id)
            ->where('jam_id', $request->jam_id)
            ->where('id', '!=', $id) // Exclude the current record
            ->first();

        if ($existingJadwal) {
            return redirect()->back()->with('error', 'Jadwal dengan ruang, hari, dan jam yang sama sudah ada.');
        }

        $jadwal = JadwalKuliah::findOrFail($id);
        $jadwal->update($request->only([
            'pengampu_id',
            'ruang_id',
            'hari_id',
            'jam_id',
            'tahun_akademik',
        ]));

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jadwal = JadwalKuliah::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus');
    }

    // Fungsi untuk menghasilkan jadwal kuliah berdasarkan permintaan
    public function generateJadwal(Request $request)
    {
        // Hapus jadwal yang sudah ada untuk tahun akademik yang diberikan
        JadwalKuliah::where('tahun_akademik', $request->tahun_akademik)->delete();

        // Ambil data pengampu mata kuliah beserta relasi dengan matakuliah dan dosen
        $pengampuList = Pengampu::with(['matakuliah', 'dosen'])
            ->where('tahun_akademik', $request->tahun_akademik)
            ->get();

        // Jika tidak ada data pengampu, kembalikan dengan pesan
        if ($pengampuList->isEmpty()) {
            return redirect()->route('jadwal.index')->with('warning', 'Tidak ada data pengampu untuk tahun akademik yang dipilih.');
        }

        // Ambil daftar semua ruangan
        $ruangList = Ruang::all();

        // Ambil daftar semua hari
        $hariList = Hari::all();

        // Ambil daftar semua kelas
        $kelasList = Kelas::all();

        // Variabel untuk melacak hari yang sudah digunakan untuk setiap mata kuliah
        $courseScheduledDays = [];

        // Variabel untuk melacak pengampu yang gagal dijadwalkan
        $jadwalGagal = [];
        $jadwalSuksesCount = 0;

        // Loop untuk setiap pengampu mata kuliah
        foreach ($pengampuList as $pengampu) {
            $jadwalTersedia = false; // Indikator apakah jadwal telah ditemukan

            // Ambil daftar jam berdasarkan jumlah SKS mata kuliah
            $jamList = Jam::getJamBySKS($pengampu->matakuliah->sks);

            // Acak daftar hari untuk memberikan variasi jadwal
            $shuffledHariList = $hariList->shuffle();

            // Loop melalui daftar hari yang telah diacak
            foreach ($shuffledHariList as $hari) {
                // Lewati jika mata kuliah ini sudah dijadwalkan pada hari tersebut
                if (isset($courseScheduledDays[$pengampu->matakuliah->id]) &&
                    in_array($hari->id, $courseScheduledDays[$pengampu->matakuliah->id])) {
                    continue;
                }

                if ($jadwalTersedia) {
                    break;
                } // Keluar jika jadwal sudah ditemukan

                // Loop melalui daftar jam
                foreach ($jamList as $jam) {
                    // Loop melalui daftar ruangan
                    foreach ($ruangList as $ruang) {
                        // Cek apakah kapasitas ruangan memenuhi syarat
                        if ($ruang->kapasitas < $pengampu->matakuliah->kapasitas_minimum) {
                            continue; // Lewati jika kapasitas tidak mencukupi
                        }

                        // Loop melalui daftar kelas
                        foreach ($kelasList as $kelas) {
                            // Pastikan kelas berasal dari program studi yang sesuai
                            if ($kelas->prodi_id == $pengampu->matakuliah->prodi_id) {
                                $newStartTime = $jam->jam_mulai;
                                $newEndTime = $jam->jam_selesai;

                                // Cek apakah ada bentrokan jadwal untuk dosen, ruangan, atau kelas pada slot waktu yang tumpang tindih
                                $isConflict = JadwalKuliah::where('hari_id', $hari->id)
                                    ->whereHas('jam', function ($q) use ($newStartTime, $newEndTime) {
                                        $q->where('jam_mulai', '<', $newEndTime)
                                            ->where('jam_selesai', '>', $newStartTime);
                                    })
                                    ->where(function ($q) use ($ruang, $pengampu, $kelas) {
                                        $dosenIds = $pengampu->dosen->pluck('id');
                                        $q->where('ruang_id', $ruang->id)
                                            ->orWhereHas('pengampu', function ($q) use ($dosenIds) {
                                                if ($dosenIds->isNotEmpty()) {
                                                    $q->whereHas('dosen', function ($q2) use ($dosenIds) {
                                                        $q2->whereIn('dosen.id', $dosenIds);
                                                    });
                                                }
                                            })
                                            ->orWhereHas('pengampu', function ($q) use ($kelas) {
                                                $q->where('kelas_id', $kelas->id);
                                            });
                                    })
                                    ->exists();

                                if (! $isConflict) {
                                    // Buat entri jadwal baru
                                    JadwalKuliah::create([
                                        'pengampu_id' => $pengampu->id,
                                        'ruang_id' => $ruang->id,
                                        'hari_id' => $hari->id,
                                        'jam_id' => $jam->id,
                                        'tahun_akademik' => $request->tahun_akademik,
                                        'semester' => $pengampu->matakuliah->semester,
                                        'kelas_id' => $pengampu->kelas_id,
                                    ]);

                                    // Tandai hari untuk mata kuliah ini
                                    $courseScheduledDays[$pengampu->matakuliah->id][] = $hari->id;

                                    $jadwalTersedia = true; // Jadwal telah ditemukan
                                    $jadwalSuksesCount++;
                                    break;
                                }
                            }
                        }

                        if ($jadwalTersedia) {
                            break;
                        } // Keluar jika jadwal ditemukan
                    }
                    if ($jadwalTersedia) {
                        break;
                    } // Keluar jika jadwal ditemukan
                }
            }

            // Jika tidak ada jadwal tersedia, tambahkan ke daftar gagal
            if (! $jadwalTersedia) {
                $jadwalGagal[] = $pengampu;
            }
        }

        // Jika ada pengampu yang gagal dijadwalkan, tambahkan notifikasi
        if ($jadwalSuksesCount == 0) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Gagal men-generate jadwal. Tidak ada slot waktu yang tersedia atau terjadi konflik.');
        } elseif (! empty($jadwalGagal)) {
            return redirect()->route('jadwal.index')
                ->with('warning', 'Beberapa mata kuliah gagal dijadwalkan. Silakan cek kembali.');
        }

        // Redirect ke halaman jadwal dengan pesan sukses
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil digenerate!');
    }
}
