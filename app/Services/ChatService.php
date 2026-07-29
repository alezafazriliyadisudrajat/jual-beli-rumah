<?php

namespace App\Services;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Property;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatService
{
    /**
     * Memulai atau mendapatkan percakapan antara dua pengguna untuk suatu properti.
     */
    public function getOrCreateConversation(int $propertyId, int $participantOne, int $participantTwo)
    {
        try {
            # Urutkan peserta untuk memastikan konsistensi (participant_one < participant_two)
            $p1 = min($participantOne, $participantTwo);
            $p2 = max($participantOne, $participantTwo);

            return ChatConversation::firstOrCreate([
                'property_id' => $propertyId,
                'participant_one' => $p1,
                'participant_two' => $p2,
            ]);
        } catch (\Exception $e) {
            Log::error("Error in ChatService@getOrCreateConversation: " . $e->getMessage());
            throw $e;
        }
    }

    public function sendMessage(int $conversationId, int $senderId, ?string $messageText, ?string $imageUrl = null)
    {
        try {
            $conversation = ChatConversation::findOrFail($conversationId);

            # Pastikan pengirim adalah salah satu dari peserta percakapan
            if ($conversation->participant_one !== $senderId && $conversation->participant_two !== $senderId) {
                throw new \Exception("Anda bukan merupakan bagian dari percakapan ini.");
            }

            $message = ChatMessage::create([
                'conversation_id' => $conversationId,
                'sender_id' => $senderId,
                'message' => $messageText,
                'image_url' => $imageUrl,
            ]);

            return $message;
        } catch (\Exception $e) {
            Log::error("Error in ChatService@sendMessage: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mengambil percakapan untuk seorang pengguna.
     */
    public function getUserConversations(int $userId)
    {
        try {
            return ChatConversation::with(['property', 'userOne', 'userTwo', 'messages' => function($q) {
                $q->latest()->limit(1);
            }])
            ->where('participant_one', $userId)
            ->orWhere('participant_two', $userId)
            ->get();
        } catch (\Exception $e) {
            Log::error("Error in ChatService@getUserConversations: " . $e->getMessage());
            throw $e;
        }
    }
}
