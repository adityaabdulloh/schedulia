<?php

namespace App\Events;

use App\Models\Pengumuman;
use App\Models\PengambilanMK;
use App\Models\Mahasiswa;
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
    public $mataKuliah;
    public $dosen;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Pengumuman $pengumuman)
    {
        $this->pengumuman = $pengumuman;
        $this->mataKuliah = $pengumuman->jadwalKuliah->pengampu->matakuliah->nama;
        $this->dosen = $pengumuman->dosen->nama;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $jadwalKuliah = $this->pengumuman->jadwalKuliah;
        $pengampuId = $jadwalKuliah->pengampu_id;

        // Get mahasiswa IDs from PengambilanMK where status is 'approved'
        $mahasiswaIds = PengambilanMK::where('pengampu_id', $pengampuId)
            ->where('status', 'approved')
            ->pluck('mahasiswa_id');

        // Get the user_id for each mahasiswa
        $userIds = Mahasiswa::whereIn('id', $mahasiswaIds)->pluck('user_id');

        $channels = [];
        foreach ($userIds as $userId) {
            $channels[] = new PrivateChannel('mahasiswa.' . $userId);
        }

        return $channels;
    }

    public function broadcastAs()
    {
        return 'pengumuman-baru';
    }
}
