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
use Carbon\Carbon;

class JadwalKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalKuliah::with(['pengampu.matakuliah', 'pengampu.dosen', 'ruang', 'hari', 'pengampu.kelas', 'pengampu.prodi']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = strtolower($request->input('search'));
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('pengampu.matakuliah', function ($q2) use ($searchTerm) {
                    $q2->whereRaw('LOWER(nama) LIKE ?', ["%{$searchTerm}%"]);
                })->orWhereHas('pengampu.dosen', function ($q2) use ($searchTerm) {
                    $q2->whereRaw('LOWER(nama) LIKE ?', ["%{$searchTerm}%"]);
                })->orWhereHas('ruang', function ($q2) use ($searchTerm) {
                    $q2->whereRaw('LOWER(nama_ruang) LIKE ?', ["%{$searchTerm}%"]);
                })->orWhereHas('hari', function ($q2) use ($searchTerm) {
                    $q2->whereRaw('LOWER(nama_hari) LIKE ?', ["%{$searchTerm}%"]);
                })->orWhereRaw('LOWER(jam_mulai) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereHas('pengampu.kelas', function ($q2) use ($searchTerm) {
                    $q2->whereRaw('LOWER(nama_kelas) LIKE ?', ["%{$searchTerm}%"]);
                });
            });
        }

        $jadwal = $query->paginate(10);
        $jadwal->appends($request->all()); // Preserve query parameters in pagination

        return view('jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $existingPengampuIds = JadwalKuliah::pluck('pengampu_id')->unique();
        $pengampus = Pengampu::with(['matakuliah', 'dosen', 'kelas'])
            ->whereNotIn('id', $existingPengampuIds)
            ->get();
        $ruangs = Ruang::all();
        $haris = Hari::all();
        
        // Membuat pemetaan dari pengampu_id ke sks
        $sksData = $pengampus->keyBy('id')->map(function ($pengampu) {
            return $pengampu->matakuliah->sks;
        });

        return view('jadwal.create', compact('pengampus', 'ruangs', 'haris', 'sksData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengampu_id' => 'required|exists:pengampu,id',
            'ruang_id' => 'required|exists:ruang,id',
            'hari_id' => 'required|exists:hari,id',
            'tahun_akademik' => 'required|string',
            'metode_penjadwalan' => 'required|in:otomatis,manual',
            'jam_mulai_manual' => 'exclude_if:metode_penjadwalan,otomatis|required|date_format:H:i',
        ]);

        $pengampu = Pengampu::with(['matakuliah', 'dosen'])->findOrFail($request->pengampu_id);
        $sks = $pengampu->matakuliah->sks;
        $durasiMenit = $sks * 50;

        if ($request->metode_penjadwalan == 'manual') {
            $jamMulai = Carbon::createFromTimeString($request->jam_mulai_manual);
            $jamSelesai = $jamMulai->copy()->addMinutes($durasiMenit);

            // Cek apakah ada bentrokan jadwal untuk dosen, ruangan, atau kelas
            $isConflict = JadwalKuliah::where('hari_id', $request->hari_id)
                ->where(function ($query) use ($jamMulai, $jamSelesai) {
                    $query->where('jam_mulai', '<', $jamSelesai->format('H:i:s'))
                          ->where('jam_selesai', '>', $jamMulai->format('H:i:s'));
                })
                ->where(function ($q) use ($request, $pengampu) {
                    $dosenIds = $pengampu->dosen->pluck('id');
                    $q->where('ruang_id', $request->ruang_id)
                        ->orWhereHas('pengampu', function ($q) use ($dosenIds) {
                            if ($dosenIds->isNotEmpty()) {
                                $q->whereHas('dosen', function ($q2) use ($dosenIds) {
                                    $q2->whereIn('dosen.id', $dosenIds);
                                });
                            }
                        })
                        ->orWhere('kelas_id', $pengampu->kelas_id);
                })
                ->exists();

            if ($isConflict) {
                return redirect()->back()->with('error', 'Jadwal bentrok dengan jadwal lain.');
            }

            // Simpan data
            JadwalKuliah::create([
                'pengampu_id' => $request->pengampu_id,
                'ruang_id' => $request->ruang_id,
                'hari_id' => $request->hari_id,
                'jam_mulai' => $jamMulai->format('H:i:s'),
                'jam_selesai' => $jamSelesai->format('H:i:s'),
                'tahun_akademik' => $request->tahun_akademik,
                'semester' => $pengampu->matakuliah->semester,
                'kelas_id' => $pengampu->kelas_id,
            ]);

            return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
        } else {
            // Definisikan slot waktu mulai
            $jamList = [];
            $startTime = Carbon::createFromTimeString('07:00:00');
            $endTime = Carbon::createFromTimeString('17:00:00');
            while ($startTime <= $endTime) {
                $jamList[] = $startTime->copy();
                $startTime->addMinutes(60);
            }

            foreach ($jamList as $jamMulai) {
                $jamSelesai = $jamMulai->copy()->addMinutes($durasiMenit);

                // Cek apakah ada bentrokan jadwal untuk dosen, ruangan, atau kelas
                $isConflict = JadwalKuliah::where('hari_id', $request->hari_id)
                    ->where(function ($query) use ($jamMulai, $jamSelesai) {
                        $query->where('jam_mulai', '<', $jamSelesai->format('H:i:s'))
                              ->where('jam_selesai', '>', $jamMulai->format('H:i:s'));
                    })
                    ->where(function ($q) use ($request, $pengampu) {
                        $dosenIds = $pengampu->dosen->pluck('id');
                        $q->where('ruang_id', $request->ruang_id)
                            ->orWhereHas('pengampu', function ($q) use ($dosenIds) {
                                if ($dosenIds->isNotEmpty()) {
                                    $q->whereHas('dosen', function ($q2) use ($dosenIds) {
                                        $q2->whereIn('dosen.id', $dosenIds);
                                    });
                                }
                            })
                            ->orWhere('kelas_id', $pengampu->kelas_id);
                    })
                    ->exists();

                if (! $isConflict) {
                    // Simpan data
                    JadwalKuliah::create([
                        'pengampu_id' => $request->pengampu_id,
                        'ruang_id' => $request->ruang_id,
                        'hari_id' => $request->hari_id,
                        'jam_mulai' => $jamMulai->format('H:i:s'),
                        'jam_selesai' => $jamSelesai->format('H:i:s'),
                        'tahun_akademik' => $request->tahun_akademik,
                        'semester' => $pengampu->matakuliah->semester,
                        'kelas_id' => $pengampu->kelas_id,
                    ]);

                    return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
                }
            }

            return redirect()->back()->with('error', 'Tidak dapat menemukan slot waktu yang tersedia untuk jadwal ini pada hari dan ruangan yang dipilih.');
        }
    }

    public function edit($id)
    {
        $jadwal = JadwalKuliah::with(['pengampu.matakuliah', 'pengampu.dosen', 'ruang', 'hari', 'pengampu.kelas', 'pengampu.prodi'])
            ->findOrFail($id);

        $pengampu = Pengampu::with(['matakuliah', 'dosen'])->get();
        $ruang = Ruang::all();
        $hari = Hari::all();
        $jam = Jam::orderBy('jam_mulai')->get();
        $kelas = Kelas::all();

        return view('jadwal.edit', compact('jadwal', 'pengampu', 'ruang', 'hari', 'jam', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pengampu_id' => 'required',
            'ruang_id' => 'required',
            'hari_id' => 'required',
            'jam_mulai' => 'required',
            'tahun_akademik' => 'required',
        ]);

        $pengampu = Pengampu::with('matakuliah')->findOrFail($request->pengampu_id);
        $sks = $pengampu->matakuliah->sks;
        $durasi = $sks * 50;

        $jamMulai = Carbon::parse($request->jam_mulai);
        $jamSelesai = $jamMulai->copy()->addMinutes($durasi);

        // Cek apakah sudah ada jadwal dengan ruang, hari, dan jam yang sama
        $existingJadwal = JadwalKuliah::where('ruang_id', $request->ruang_id)
            ->where('hari_id', $request->hari_id)
            ->where('id', '!=', $id) // Exclude the current record
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->where('jam_mulai', '<', $jamSelesai->format('H:i:s'))
                      ->where('jam_selesai', '>', $jamMulai->format('H:i:s'));
            })
            ->first();

        if ($existingJadwal) {
            return redirect()->back()->with('error', 'Jadwal dengan ruang, hari, dan jam yang sama sudah ada.');
        }

        $jadwal = JadwalKuliah::findOrFail($id);
        $data = $request->only([
            'pengampu_id',
            'ruang_id',
            'hari_id',
            'tahun_akademik',
        ]);
        $data['jam_mulai'] = $jamMulai->format('H:i:s');
        $data['jam_selesai'] = $jamSelesai->format('H:i:s');

        $jadwal->update($data);

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
        
        // Definisikan slot waktu mulai secara manual
        $jamList = [];
        $startTime = Carbon::createFromTimeString('07:00:00');
        $endTime = Carbon::createFromTimeString('17:00:00');
        while ($startTime <= $endTime) {
            $jamList[] = $startTime->copy();
            $startTime->addMinutes(60); // Berpindah ke slot berikutnya setiap 60 menit
        }


        // Variabel untuk melacak hari yang sudah digunakan untuk setiap mata kuliah
        $courseScheduledDays = [];

        // Variabel untuk melacak pengampu yang gagal dijadwalkan
        $jadwalGagal = [];
        $jadwalSuksesCount = 0;

        // Loop untuk setiap pengampu mata kuliah
        foreach ($pengampuList as $pengampu) {
            $jadwalTersedia = false; // Indikator apakah jadwal telah ditemukan
            
            $sks = $pengampu->matakuliah->sks;
            $durasiMenit = $sks * 50;

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
                foreach ($jamList as $jamMulai) {
                    // Loop melalui daftar ruangan
                    foreach ($ruangList as $ruang) {
                        // Cek apakah kapasitas ruangan memenuhi syarat
                        if ($ruang->kapasitas < $pengampu->matakuliah->kapasitas_minimum) {
                            continue; // Lewati jika kapasitas tidak mencukupi
                        }

                        $jamSelesai = $jamMulai->copy()->addMinutes($durasiMenit);

                        // Cek apakah ada bentrokan jadwal untuk dosen, ruangan, atau kelas pada slot waktu yang tumpang tindih
                        $isConflict = JadwalKuliah::where('hari_id', $hari->id)
                            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                                $query->where('jam_mulai', '<', $jamSelesai->format('H:i:s'))
                                      ->where('jam_selesai', '>', $jamMulai->format('H:i:s'));
                            })
                            ->where(function ($q) use ($ruang, $pengampu) {
                                $dosenIds = $pengampu->dosen->pluck('id');
                                $q->where('ruang_id', $ruang->id)
                                    ->orWhereHas('pengampu', function ($q) use ($dosenIds) {
                                        if ($dosenIds->isNotEmpty()) {
                                            $q->whereHas('dosen', function ($q2) use ($dosenIds) {
                                                $q2->whereIn('dosen.id', $dosenIds);
                                            });
                                        }
                                    })
                                    ->orWhere('kelas_id', $pengampu->kelas_id);
                            })
                            ->exists();

                        if (! $isConflict) {
                            // Buat entri jadwal baru
                            JadwalKuliah::create([
                                'pengampu_id' => $pengampu->id,
                                'ruang_id' => $ruang->id,
                                'hari_id' => $hari->id,
                                'jam_mulai' => $jamMulai->format('H:i:s'),
                                'jam_selesai' => $jamSelesai->format('H:i:s'),
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
