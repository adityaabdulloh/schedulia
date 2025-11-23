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
        $kelasId = $this->pengumuman->jadwalKuliah->kelas_id;
        return new PrivateChannel('kelas.' . $kelasId);
    }

    public function broadcastAs()
    {
        return 'pengumuman-baru';
    }
}
