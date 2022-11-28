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

// From the name, the public channel allows any client to subscribe to it.
Broadcast::channel('public', fn () => true);

// Only authenticated clients can join this channel.
Broadcast::channel('private.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
