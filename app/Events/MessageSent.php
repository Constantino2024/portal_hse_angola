<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;
    public $receiverId;

    public function __construct(Message $message, User $user, $receiverId)
    {
        $this->message = $message;
        $this->user = $user;
        $this->receiverId = $receiverId;
    }

    public function broadcastOn(): array
    {
        $ids = [$this->user->id, $this->receiverId];
        sort($ids);
        return new PrivateChannel('chat.'.implode('.', $ids));
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'body' => $this->message->body,
            'created_at' => optional($this->message->created_at)->toIso8601String(),
            'sender_id' => $this->message->sender->id,
            'sender_name' => $this->message->sender->name,
            'sender' => [
                'id' => $this->message->sender->id,
                'name' => $this->message->sender->name,
                'type' => method_exists($this->message->sender, 'isCompany') && $this->message->sender->isCompany() ? 'company' : 'user',
            ],
        ];
    }
}
