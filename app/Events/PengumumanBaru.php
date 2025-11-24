<?php

namespace App\Events;

use App\Models\Pengumuman;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PengumumanBaru implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pengumuman;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Pengumuman $pengumuman)
    {
        $this->pengumuman = $pengumuman;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $jadwalKuliah = $this->pengumuman->jadwalKuliah;
        $kelasId = $jadwalKuliah->kelas_id;
        $matakuliahId = $jadwalKuliah->pengampu->matakuliah_id;

        $approvedMahasiswaIds = \App\Models\PengambilanMK::where('matakuliah_id', $matakuliahId)
            ->where('status', 'approved')
            ->join('mahasiswa', 'pengambilan_mk.mahasiswa_id', '=', 'mahasiswa.id')
            ->where('mahasiswa.kelas_id', $kelasId)
            ->pluck('pengambilan_mk.mahasiswa_id')
            ->unique()
            ->toArray();

        $channels = [];
        foreach ($approvedMahasiswaIds as $mahasiswaId) {
            $channels[] = new PrivateChannel('mahasiswa.' . $mahasiswaId);
        }

        return $channels;
    }

    public function broadcastAs()
    {
        return 'pengumuman-baru';
    }
}
