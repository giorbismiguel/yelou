<?php

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

use App\TransportationAvailable;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('requestServices', function ($user) {
    $isAvailable = (bool) TransportationAvailable::where([
        'user_id' => $user->id,
        'active'  => 1
    ])->exists();

    return $isAvailable;
});

Broadcast::channel('servicesAccepted.{clientId}', function ($user, $clientId) {
    return $user->id === $clientId;
});
