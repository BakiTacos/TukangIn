@php
    // Mencari pesanan terbaru antara user yang sedang login dengan teknisi ini secara dinamis
    $recentOrder = \App\Models\Order::where(function($q) use ($tukang) {
        $q->where('user_id', auth()->id())->where('tukang_id', $tukang->id);
    })->orWhere(function($q) use ($tukang) {
        $q->where('user_id', $tukang->id)->where('tukang_id', auth()->id());
    })->with('service')->latest()->first();
@endphp

<x-app-layout>
    <div class="container mx-auto px-6 py-8 max-w-4xl h-[calc(100vh-140px)] flex flex-col"
         x-data="{
            messages: [],
            newMessage: '',
            currentUserId: null,
            
            // 1. Fungsi Ambil Pesan dari Server (Real-time update)
            async fetchMessages() {
                try {
                    let response = await fetch('{{ route('chats.messages', $tukang->id) }}');
                    let data = await response.json();
                    
                    // Deteksi jika ada pesan baru untuk memicu auto-scroll
                    let isNewMessage = data.messages.length > this.messages.length;
                    
                    this.messages = data.messages;
                    this.currentUserId = data.current_user_id;
                    
                    // Auto scroll ke bawah hanya jika ada pesan baru masuk
                    if (isNewMessage) {
                        this.$nextTick(() => { this.scrollToBottom(); });
                    }
                } catch (error) {
                    console.error('Gagal mengambil pesan:', error);
                }
            },

            // 2. Fungsi Kirim Pesan via Ajax
            async sendMessage() {
                if (this.newMessage.trim() === '') return;
                
                let formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('message', this.newMessage);

                this.newMessage = ''; // Kosongkan inputan instan di layar demi kenyamanan UX

                try {
                    await fetch('{{ route('chats.store', $tukang->id) }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    this.fetchMessages(); // Ambil ulang pesan agar langsung update di layar
                } catch (error) {
                    console.error('Gagal mengirim pesan:', error);
                }
            },

            scrollToBottom() {
                let chatContainer = this.$refs.chatBox;
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
         }"
         x-init="
            fetchMessages();
            // Polling dinamis setiap 3 detik di background
            setInterval(() => { fetchMessages() }, 3000);
         ">

        <div class="bg-white rounded-t-[2rem] border border-gray-100 p-6 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('chats.index') }}" class="text-gray-400 hover:text-orange-500 transition mr-2 md:hidden">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <img src="https://ui-avatars.com/api/?name={{ urlencode($tukang->name) }}&background=FFEDD5&color=F97316" class="w-12 h-12 rounded-2xl border border-orange-200">
                <div>
                    <h3 class="font-black text-[#0f2d50] text-sm leading-none">{{ $tukang->name }}</h3>
                    
                    <p class="text-[10px] text-gray-400 mt-2 font-bold uppercase tracking-wider">
                        Layanan: <span class="text-orange-500">{{ $recentOrder->service->title ?? 'Konsultasi Umum' }}</span>
                    </p>
                    
                    <div class="flex items-center gap-1.5 mt-1.5">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        <span class="text-[9px] text-green-500 font-bold uppercase tracking-wider">Teknisi Aktif</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="text-[10px] font-black text-gray-400 hover:text-orange-500 uppercase tracking-widest transition">
                Tutup Chat
            </a>
        </div>

        <div x-ref="chatBox" class="flex-1 bg-gray-50/50 border-x border-gray-100 overflow-y-auto p-6 space-y-4 scroll-smooth">
            <template x-for="msg in messages" :key="msg.id">
                <div class="flex animate-fade-in" :class="msg.sender_id == currentUserId ? 'justify-end' : 'justify-start'">
                    
                    <div class="max-w-md rounded-3xl px-5 py-3.5 text-xs font-semibold leading-relaxed shadow-sm"
                         :class="msg.sender_id == currentUserId 
                            ? 'bg-[#0f2d50] text-white rounded-tr-none' 
                            : 'bg-white text-gray-700 border border-gray-150 rounded-tl-none'">
                        
                        <p class="whitespace-pre-wrap break-words" x-text="msg.message"></p>
                        
                        <div class="flex items-center justify-end gap-1.5 mt-2 opacity-75">
                            <span class="text-[8px]" 
                                  x-text="new Date(msg.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})">
                            </span>
                            
                            <template x-if="msg.sender_id == currentUserId">
                                <span class="inline-flex items-center">
                                    <template x-if="msg.is_read">
                                        <i class="fas fa-check-double text-[10px] text-orange-400" title="Dibaca"></i>
                                    </template>
                                    <template x-if="!msg.is_read">
                                        <i class="fas fa-check text-[10px] text-gray-300" title="Terkirim"></i>
                                    </template>
                                </span>
                            </template>
                        </div>
                    </div>

                </div>
            </template>
        </div>

        <div class="bg-white rounded-b-[2rem] border border-gray-100 p-6 shadow-sm">
            <form @submit.prevent="sendMessage" class="flex gap-4">
                <input type="text" x-model="newMessage" placeholder="Tulis pesan Anda untuk teknisi di sini..."
                       class="flex-1 bg-gray-50 border border-gray-150 rounded-2xl py-4 px-5 text-xs font-medium text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                
                <button type="submit" 
                        class="bg-[#e67e22] hover:bg-[#d35400] text-white px-8 rounded-2xl transition shadow-lg shadow-orange-500/20 flex items-center justify-center">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>

    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>