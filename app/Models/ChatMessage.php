<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['conversation_id', 'sender_id', 'message', 'read_at', 'image_url'])]
class ChatMessage extends Model
{
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
