<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['property_id', 'participant_one', 'participant_two'])]
class ChatConversation extends Model
{
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function userOne()
    {
        return $this->belongsTo(User::class, 'participant_one');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'participant_two');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }
}
