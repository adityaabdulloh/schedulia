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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('kelas.{kelasId}', function ($user, $kelasId) {
    // Check if the user is authenticated and is a mahasiswa
    if ($user->role === 'mahasiswa' && $user->mahasiswa) {
        // Check if the mahasiswa's kelas_id matches the channel's kelasId
        return (int) $user->mahasiswa->kelas_id === (int) $kelasId;
    }
    return false;
});