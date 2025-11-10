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

class PengumumanCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pengumuman;

    /**
     * Create a new event instance.
     */
    public function __construct(Pengumuman $pengumuman)
    {
        $this->pengumuman = $pengumuman;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('kelas.' . $this->pengumuman->jadwalKuliah->pengampu->kelas_id),
        ];
    }

    public function broadcastAs()
    {
        return 'pengumuman.created';
    }

    public function broadcastWith(): array
{
    return [
        'pengumuman' => [
            'tipe' => $this->pengumuman->tipe,
            'pesan' => $this->pengumuman->pesan,
            'matakuliah' => $this->pengumuman->jadwalKuliah->pengampu->matakuliah->nama_matakuliah,
            'dosen' => $this->pengumuman->dosen->nama,
            'created_at' => $this->pengumuman->created_at->toFormattedDateString(),
        ],
    ];
}
}