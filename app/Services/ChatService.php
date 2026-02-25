<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Exceptions\DomainException;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\Support\Sanitizer;
use Illuminate\Support\Facades\DB;

class ChatService extends BaseService
{
    /**
     * Create or return an existing private conversation between two users.
     */
    public function getOrCreatePrivateConversation(User $a, User $b): Conversation
    {
        if ($a->id === $b->id) {
            throw new DomainException('Não é possível iniciar conversa consigo mesmo.');
        }

        return $this->guard('chat.getOrCreate', function () use ($a, $b) {
            // Find conversation that has exactly these two participants.
            $conversation = Conversation::query()
                ->whereHas('participants', fn ($q) => $q->whereKey($a->id))
                ->whereHas('participants', fn ($q) => $q->whereKey($b->id))
                ->withCount('participants')
                ->get()
                ->firstWhere('participants_count', 2);

            if ($conversation) {
                return $conversation;
            }

            return DB::transaction(function () use ($a, $b) {
                $c = Conversation::create([
                    'created_by' => $a->id,
                    'last_message_at' => null,
                ]);
                $c->participants()->attach([$a->id, $b->id]);
                return $c;
            });
        });
    }

    public function sendMessage(Conversation $conversation, User $sender, string $body): Message
    {
        $body = trim($body);

        if ($body === '') {
            throw new DomainException('A mensagem não pode estar vazia.');
        }

        if (mb_strlen($body) > 2000) {
            throw new DomainException('A mensagem é muito longa (máx. 2000 caracteres).');
        }

        // Basic XSS-safe sanitization: strip tags and normalize spaces.
        $clean = Sanitizer::clean(['body' => $body], ['body']);
        $body = $clean['body'] ?? $body;

        return $this->guard('chat.sendMessage', function () use ($conversation, $sender, $body) {
            return DB::transaction(function () use ($conversation, $sender, $body) {
                $msg = Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $sender->id,
                    'body' => $body,
                ]);

                $conversation->update(['last_message_at' => now(), 'last_message_id' => $msg->id]);

                // Sender already 'read' their own message
                $conversation->participants()->updateExistingPivot($sender->id, [
                    'last_read_message_id' => $msg->id,
                    'last_read_at' => now(),
                ]);


                // Broadcast realtime
                event(new MessageSent($msg));

                return $msg;
            });
        });
    }


    /**
     * Mark a conversation as read for a given user.
     */
    public function markAsRead(Conversation $conversation, User $user): void
    {
        $this->guard('chat.markAsRead', function () use ($conversation, $user) {
            return DB::transaction(function () use ($conversation, $user) {
                $lastId = $conversation->last_message_id;

                if (!$lastId) {
                    return;
                }

                $conversation->participants()->updateExistingPivot($user->id, [
                    'last_read_message_id' => $lastId,
                    'last_read_at' => now(),
                ]);

                // Broadcast a "read" receipt so the other side can update UI.
                event(new \App\Events\MessageRead($conversation->id, $user->id, $lastId));
            });
        });
    }

    /**
     * Total unread conversations count for badge (fast query).
     */
    public function unreadConversationsCount(User $user): int
    {
        return (int) DB::table('conversations')
            ->join('conversation_user as cu', function ($join) use ($user) {
                $join->on('cu.conversation_id', '=', 'conversations.id')
                     ->where('cu.user_id', '=', $user->id);
            })
            ->leftJoin('messages as lm', 'lm.id', '=', 'conversations.last_message_id')
            ->whereNotNull('conversations.last_message_id')
            ->where(function ($q) {
                $q->whereNull('cu.last_read_message_id')
                  ->orWhereColumn('cu.last_read_message_id', '<', 'conversations.last_message_id');
            })
            ->where(function ($q) use ($user) {
                $q->whereNull('lm.sender_id')->orWhere('lm.sender_id', '!=', $user->id);
            })
            ->count();
    }

}
