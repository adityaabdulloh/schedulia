<?php

use App\Models\User;
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

Broadcast::channel('kelas.{kelasId}', function (User $user, int $kelasId) {
    // Check if the user is a student and belongs to the specified class
    if ($user->role === 'mahasiswa' && $user->mahasiswa->kelas_id === $kelasId) {
        return true;
    }
    return false;
});