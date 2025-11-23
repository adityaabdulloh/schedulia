<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.Mahasiswa.{id}', function ($mahasiswa, $id) {
    return (int) $mahasiswa->id === (int) $id;
});

Broadcast::channel('mahasiswa.{mahasiswa_id}', function ($mahasiswa, $mahasiswa_id) {
    return (int) $mahasiswa->id === (int) $mahasiswa_id;
});
