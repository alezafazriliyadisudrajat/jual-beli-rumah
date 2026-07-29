<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\Property;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    # Memulai percakapan chat baru untuk properti tertentu.
    public function startConversation(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengirim pesan.');
            }

            $validator = Validator::make($request->all(), [
                'property_id' => 'required|exists:properties,id',
                'message' => 'required|string',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }

            $property = Property::findOrFail($request->property_id);

            if ($property->user_id === $user->id) {
                return back()->with('error', 'Anda tidak dapat mengirim pesan kepada diri sendiri.');
            }

            # Dapatkan atau buat percakapan antara pembeli (pengguna saat ini) dan pemilik/agen
            $conversation = $this->chatService->getOrCreateConversation($property->id, $user->id, $property->user_id);

            # Kirim pesan awal
            $this->chatService->sendMessage($conversation->id, $user->id, $request->message);

            return redirect()->route('dashboard', ['tab' => 'chat', 'active_chat' => $conversation->id])
                ->with('success', 'Pesan berhasil dikirim.');
        } catch (\Exception $e) {
            Log::error("Error in ChatController@startConversation: " . $e->getMessage());
            return back()->with('error', 'Gagal mengirim pesan.');
        }
    }

    # Mengambil daftar percakapan aktif untuk pengguna saat ini.
    public function getUserConversations()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
            }

            $conversations = $this->chatService->getUserConversations($user->id);

            // Format ke format snake_case agar sesuai dengan javascript di frontend
            $formatted = $conversations->map(function ($conv) {
                return [
                    'id' => $conv->id,
                    'participant_one' => $conv->participant_one,
                    'participant_two' => $conv->participant_two,
                    'user_one' => $conv->userOne,
                    'user_two' => $conv->userTwo,
                    'property' => $conv->property,
                    'messages' => $conv->messages
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formatted
            ]);
        } catch (\Exception $e) {
            Log::error("Error in ChatController@getUserConversations: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil percakapan.'], 500);
        }
    }

    # Mengirim pesan dalam percakapan yang ada (respons JSON untuk polling AJAX/Alpine.js).
    public function sendMessage(Request $request, $id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
            }

            $validator = Validator::make($request->all(), [
                'message' => 'required_without:image|nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp|max:5120',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $imageUrl = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('chat_images', 'public');
                $imageUrl = '/storage/' . $imagePath;
            }

            $message = $this->chatService->sendMessage($id, $user->id, $request->message, $imageUrl);

            return response()->json([
                'success' => true,
                'message' => 'Pesan terkirim',
                'data' => $message
            ]);
        } catch (\Exception $e) {
            Log::error("Error in ChatController@sendMessage: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    # Mengambil semua pesan untuk percakapan tertentu (respons JSON untuk polling).
    public function getMessages($id)
    {
        try {
            $user = Auth::user();
            $conversation = ChatConversation::findOrFail($id);

            if ($conversation->participant_one !== $user->id && $conversation->participant_two !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }

            # Tandai pesan yang belum dibaca sebagai sudah dibaca
            $conversation->messages()
                ->where('sender_id', '!=', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            $messages = $conversation->messages()->with('sender')->get();

            return response()->json([
                'success' => true,
                'data' => $messages
            ]);
        } catch (\Exception $e) {
            Log::error("Error in ChatController@getMessages: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }

    # Melakukan update status presensi aktif (heartbeat ping) pengguna.
    public function ping()
    {
        try {
            $user = Auth::user();
            if ($user) {
                \Illuminate\Support\Facades\DB::table('users')
                    ->where('id', $user->id)
                    ->update(['last_seen_at' => now()]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
