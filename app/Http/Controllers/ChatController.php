<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Halaman Utama Chat Terpadu (Desktop Split-Pane)
     */
    public function index(Request $request, User $tukang = null)
    {
        $userId = Auth::id();

        // 1. Ambil semua percakapan unik milik user
        $chats = Message::where('user_id', $userId)
            ->orWhere('tukang_id', $userId)
            ->get()
            ->groupBy(function($message) use ($userId) {
                return $message->user_id == $userId ? $message->tukang_id : $message->user_id;
            })
            ->map(function($messages) {
                return $messages->sortByDesc('created_at')->first();
            })
            ->sortByDesc('created_at');

        // 2. Map ke dalam struktur list thread lengkap dengan info layanan teknisi
        $threads = $chats->map(function($chat) use ($userId) {
            $partnerId = $chat->user_id == $userId ? $chat->tukang_id : $chat->user_id;
            $partner = User::find($partnerId);
            if (!$partner) return null;

            // Dapatkan keahlian spesifik dari riwayat transaksi terbaru
            $recentOrder = Order::where(function($q) use ($userId, $partnerId) {
                $q->where('user_id', $userId)->where('tukang_id', $partnerId);
            })->orWhere(function($q) use ($userId, $partnerId) {
                $q->where('user_id', $partnerId)->where('tukang_id', $userId);
            })->with('service')->latest()->first();

            return [
                'partner' => $partner,
                'service_title' => $recentOrder->service->title ?? 'Layanan Umum',
                'last_message' => $chat,
                'unread_count' => Message::where('user_id', $partnerId)
                    ->where('tukang_id', $userId)
                    ->where('sender_id', $partnerId)
                    ->where('is_read', false)
                    ->count()
            ];
        })->filter(fn($thread) => $thread !== null)->values();

        // 3. Tangani jika ada parameter teknisi yang terpilih langsung (misal diklik dari order detail)
        $selectedTukang = $tukang;
        $selectedTukangService = null;
        
        if ($selectedTukang) {
            $recentOrder = Order::where(function($q) use ($userId, $selectedTukang) {
                $q->where('user_id', $userId)->where('tukang_id', $selectedTukang->id);
            })->orWhere(function($q) use ($userId, $selectedTukang) {
                $q->where('user_id', $selectedTukang->id)->where('tukang_id', $userId);
            })->with('service')->latest()->first();

            $selectedTukangService = $recentOrder->service->title ?? 'Layanan Umum';

            // Set otomatis terbaca saat chat dibuka
            Message::where('user_id', $userId)
                ->where('tukang_id', $selectedTukang->id)
                ->where('sender_id', $selectedTukang->id)
                ->update(['is_read' => true]);
        }

        return view('chat.index', compact('threads', 'selectedTukang', 'selectedTukangService'));
    }

    /**
     * Kirim Pesan Baru (Ajax)
     */
    // app/Http/Controllers/ChatController.php

    public function store(Request $request, User $tukang)
    {
        $request->validate([
            'message' => 'nullable|required_without:image|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // Max 1MB
        ]);

        $userId = Auth::id();
        $imagePath = null;

        if ($request->hasFile('image')) {
            // 1. Upload file menggunakan driver S3 ke Supabase
            $path = $request->file('image')->store('chats', 'supabase');

            // 2. Ekstrak HOST secara otomatis dari SUPABASE_ENDPOINT untuk memintas bug cache config
            $endpoint = env('SUPABASE_ENDPOINT'); // e.g., https://xxxx.supabase.co/storage/v1/s3
            $parsedUrl = parse_url($endpoint);
            $host = $parsedUrl['host'] ?? ''; // Menghasilkan: xxxx.supabase.co
            
            $bucket = env('SUPABASE_BUCKET', 'amarta-uploads');

            // 3. Susun URL CDN Publik secara manual (100% Antipeluru & Bebas 403)
            $imagePath = "https://{$host}/storage/v1/object/public/{$bucket}/{$path}";
        }

        $message = Message::create([
            'user_id' => $userId,
            'tukang_id' => $tukang->id,
            'sender_id' => $userId,
            'message' => $request->message ?? '',
            'image_path' => $imagePath, // Menyimpan URL CDN Publik langsung ke DB
            'is_read' => false
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Ambil Data Pesan (Real-time polling API)
     */
    public function getMessages(User $tukang)
    {
        $userId = Auth::id();

        $messages = Message::where(function($q) use ($userId, $tukang) {
            $q->where('user_id', $userId)->where('tukang_id', $tukang->id);
        })->orWhere(function($q) use ($userId, $tukang) {
            $q->where('user_id', $tukang->id)->where('tukang_id', $userId);
        })->orderBy('created_at', 'asc')->get();

        // Set terbaca otomatis
        Message::where('user_id', $userId)
            ->where('tukang_id', $tukang->id)
            ->where('sender_id', $tukang->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
            'current_user_id' => $userId
        ]);
    }
}