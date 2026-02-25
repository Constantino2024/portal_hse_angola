<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports.
|
*/

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    return \App\Models\Conversation::query()
        ->whereKey($conversationId)
        ->whereHas('participants', fn ($q) => $q->whereKey($user->id))
        ->exists();
});

Broadcast::channel('chat.{id1}.{id2}', function ($user, $id1, $id2) {
    // Verifica se o usuário autenticado é um dos dois IDs
    return (int) $user->id === (int) $id1 || (int) $user->id === (int) $id2;
});
