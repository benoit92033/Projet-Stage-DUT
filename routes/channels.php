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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('game.{id}', function ($user, $id) {
    return true; //$user->id === $id;
});

Broadcast::channel('chat.{id}', function ($user, $id) {
    return true; //$user->id === $id;
});

Broadcast::channel('client-candidate.{id}', function ($user, $id) {
    return true; //$user->id === $id;
});

Broadcast::channel('client-sdp.{id}', function ($user, $id) {
    return true; //$user->id === $id;
});

Broadcast::channel('client-answer.{id}', function ($user, $id) {
    return true; //$user->id === $id;
});

Broadcast::channel('client-endcall.{id}', function ($user, $id) {
    return true; //$user->id === $id;
});