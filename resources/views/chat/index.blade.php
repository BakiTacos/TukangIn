<x-app-layout>
    <div class="container mx-auto px-6 py-12 max-w-6xl">
        
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-150 flex overflow-hidden h-[calc(100vh-180px)]"
             x-data="{
                activePartnerId: {{ $selectedTukang ? $selectedTukang->id : 'null' }},
                activePartnerName: '{{ $selectedTukang ? $selectedTukang->name : '' }}',
                activePartnerService: '{{ $selectedTukangService ? $selectedTukangService : '' }}',
                activePartnerAvatar: '{{ $selectedTukang ? 'https://ui-avatars.com/api/?name=' . urlencode($selectedTukang->name) . '&background=FFEDD5&color=F97316' : '' }}',
                messages: [],
                newMessage: '',
                currentUserId: {{ auth()->id() }},
                showMobileList: {{ $selectedTukang ? 'false' : 'true' }},
                pollingTimer: null,
                searchQuery: '',

                // State File Upload & Preview Gambar
                selectedImageFile: null,
                imagePreviewUrl: '',
                imageError: '',

                // State Loading & Race Condition Guard (Baru)
                isLoading: false,

                // 1. Fungsi Ganti Jendela Percakapan secara Instan
                selectPartner(id, name, service, avatar) {
                    this.activePartnerId = id;
                    this.activePartnerName = name;
                    this.activePartnerService = service;
                    this.activePartnerAvatar = avatar;
                    this.showMobileList = false;
                    this.messages = [];
                    this.isLoading = true; // <--- AKTIFKAN LOADING SAAT KLIK MITRA BARU
                    this.clearSelectedImage();
                    
                    this.fetchMessages();
                    this.startPolling();
                },

                // 2. Tangani Pemilihan Gambar & Validasi Batasan Ukuran Maks 1MB
                handleImageSelect(event) {
                    let file = event.target.files[0];
                    if (!file) return;

                    if (file.size > 1048576) {
                        this.imageError = 'Ukuran berkas terlalu besar! Maksimal batas gambar adalah 1MB.';
                        this.clearSelectedImage();
                        return;
                    }

                    this.imageError = '';
                    this.selectedImageFile = file;
                    this.imagePreviewUrl = URL.createObjectURL(file);
                },

                clearSelectedImage() {
                    this.selectedImageFile = null;
                    this.imagePreviewUrl = '';
                    if (this.$refs.imageInput) {
                        this.$refs.imageInput.value = '';
                    }
                },

                // 3. Ambil Pesan dari Database dengan Pengaman Tumpang Tindih (Race Condition Guard)
                async fetchMessages() {
                    if (!this.activePartnerId) return;
                    
                    // Catat ID mitra saat request ini mulai dikirim
                    let partnerIdAtRequestTime = this.activePartnerId; 

                    try {
                        let response = await fetch(`/chats/${partnerIdAtRequestTime}/messages`);
                        let data = await response.json();
                        
                        // PENGAMAN: Hanya update jika user belum beralih ke mitra lain
                        if (partnerIdAtRequestTime === this.activePartnerId) {
                            let isNewMessage = data.messages.length > this.messages.length;
                            this.messages = data.messages;
                            this.isLoading = false; // <--- MATIKAN LOADING SETELAH DATA AMAN

                            if (isNewMessage) {
                                this.$nextTick(() => { this.scrollToBottom(); });
                            }
                        }
                    } catch (error) {
                        console.error('Gagal mengambil pesan:', error);
                        if (partnerIdAtRequestTime === this.activePartnerId) {
                            this.isLoading = false;
                        }
                    }
                },

                // 4. Kirim Pesan Cepat via AJAX
                async sendMessage() {
                    if (this.newMessage.trim() === '' && !this.selectedImageFile) return;
                    
                    let formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    if (this.newMessage.trim() !== '') {
                        formData.append('message', this.newMessage);
                    }
                    if (this.selectedImageFile) {
                        formData.append('image', this.selectedImageFile);
                    }

                    this.newMessage = ''; 
                    this.clearSelectedImage();

                    try {
                        await fetch(`/chats/${this.activePartnerId}`, {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        this.fetchMessages();
                    } catch (error) {
                        console.error('Gagal mengirim pesan:', error);
                    }
                },

                startPolling() {
                    if (this.pollingTimer) clearInterval(this.pollingTimer);
                    this.pollingTimer = setInterval(() => {
                        this.fetchMessages();
                    }, 3000);
                },

                scrollToBottom() {
                    let chatBox = this.$refs.chatContainer;
                    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
                }
             }"
             x-init="
                if (activePartnerId) {
                    isLoading = true;
                    fetchMessages();
                    startPolling();
                }
             ">

            <div class="w-full md:w-80 lg:w-96 border-r border-gray-150 flex flex-col shrink-0"
                 :class="showMobileList ? 'block' : 'hidden md:flex'">
                
                <div class="p-6 border-b border-gray-100 space-y-4">
                    <h2 class="text-2xl font-black text-[#0f2d50]">Messages</h2>
                    <div class="relative">
                        <input type="text" x-model="searchQuery" placeholder="Cari percakapan..."
                               class="w-full bg-gray-50 border border-gray-150 rounded-2xl py-3 pl-11 pr-5 text-xs font-medium text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto divide-y divide-gray-50/50 p-4 space-y-2">
                    @forelse($threads as $thread)
                        <div @click="selectPartner({{ $thread['partner']->id }}, '{{ $thread['partner']->name }}', '{{ $thread['service_title'] }}', 'https://ui-avatars.com/api/?name={{ urlencode($thread['partner']->name) }}&background=FFEDD5&color=F97316')"
                             x-show="searchQuery === '' || '{{ strtolower($thread['partner']->name) }}'.includes(searchQuery.toLowerCase())"
                             class="flex items-center gap-4 p-4 rounded-3xl cursor-pointer transition-all duration-200 group"
                             :class="activePartnerId == {{ $thread['partner']->id }} ? 'bg-orange-50/70 border border-orange-100' : 'bg-white hover:bg-gray-50/50 border border-transparent'">
                            
                            <div class="relative shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($thread['partner']->name) }}&background=FFEDD5&color=F97316"
                                     class="w-12 h-12 rounded-xl border border-orange-100 group-hover:scale-105 transition-all">
                                
                                @if($thread['unread_count'] > 0)
                                    <span class="absolute -top-1 -right-1 flex h-4.5 w-4.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4.5 w-4.5 bg-red-500 text-[8px] text-white font-bold items-center justify-center">
                                            {{ $thread['unread_count'] }}
                                        </span>
                                    </span>
                                @endif
                            </div>

                            <div class="overflow-hidden flex-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-black text-xs text-[#0f2d50] truncate group-hover:text-orange-500 transition-colors">
                                        {{ $thread['partner']->name }}
                                    </h4>
                                    <span class="text-[8px] font-bold text-gray-400 ml-2 shrink-0">
                                        {{ $thread['last_message']->created_at->format('H:i') }}
                                    </span>
                                </div>
                                <p class="text-[9px] text-orange-500 font-bold uppercase tracking-wider mt-0.5">{{ $thread['service_title'] }}</p>
                                <p class="text-[11px] truncate mt-1 leading-normal"
                                   :class="activePartnerId == {{ $thread['partner']->id }} ? 'text-gray-600 font-semibold' : 'text-gray-450'">
                                    <template x-if="{{ $thread['last_message']->image_path ? 'true' : 'false' }}">
                                        <span class="text-orange-500 font-bold"><i class="far fa-image mr-1"></i> Mengirim foto</span>
                                    </template>
                                    <template x-if="{{ !$thread['last_message']->image_path ? 'true' : 'false' }}">
                                        <span>{{ $thread['last_message']->message }}</span>
                                    </template>
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400 text-xs font-medium">Belum ada riwayat pesan</div>
                    @endforelse
                </div>
            </div>

            <div class="flex-1 flex flex-col bg-gray-50/50 relative"
                 :class="!showMobileList ? 'flex' : 'hidden md:flex'">
                
                <template x-if="activePartnerId">
                    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
                        
                        <div class="bg-white p-6 border-b border-gray-150 flex items-center justify-between shadow-sm z-10">
                            <div class="flex items-center gap-4">
                                <button @click="showMobileList = true" class="text-gray-400 hover:text-orange-500 transition mr-2 md:hidden">
                                    <i class="fas fa-arrow-left text-lg"></i>
                                </button>
                                
                                <img :src="activePartnerAvatar" class="w-12 h-12 rounded-xl border border-orange-100">
                                <div>
                                    <h3 class="font-black text-[#0f2d50] text-sm leading-none" x-text="activePartnerName"></h3>
                                    <p class="text-[9px] text-gray-400 mt-2 font-bold uppercase tracking-wider">
                                        Layanan Jasa: <span class="text-orange-500 font-extrabold" x-text="activePartnerService"></span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 text-gray-400 text-sm">
                                <button class="hover:text-[#0f2d50] transition"><i class="fas fa-ellipsis-v"></i></button>
                            </div>
                        </div>

                        <div class="flex-1 overflow-hidden flex flex-col relative">
                            
                            <div x-show="isLoading" 
                                 class="absolute inset-0 bg-white/80 backdrop-blur-sm z-30 flex flex-col items-center justify-center gap-3 transition-opacity">
                                <div class="w-10 h-10 border-4 border-orange-500 border-t-transparent rounded-full animate-spin"></div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Memuat Pesan...</p>
                            </div>

                            <div x-ref="chatContainer" class="flex-1 overflow-y-auto p-6 space-y-4 scroll-smooth">
                                <template x-for="msg in messages" :key="msg.id">
                                    <div class="flex" :class="msg.sender_id == currentUserId ? 'justify-end' : 'justify-start'">
                                        
                                        <div class="max-w-md rounded-3xl px-5 py-3.5 text-xs font-semibold leading-relaxed shadow-sm"
                                             :class="msg.sender_id == currentUserId 
                                                ? 'bg-[#0f2d50] text-white rounded-tr-none' 
                                                : 'bg-white text-gray-700 border border-gray-150 rounded-tl-none'">
                                            
                                            <template x-if="msg.image_path">
                                                <div class="mb-2.5 max-w-xs rounded-2xl overflow-hidden border border-gray-100 shadow-sm cursor-pointer">
                                                    <img :src="msg.image_path.startsWith('http') ? msg.image_path : '{{ asset('storage') }}/' + msg.image_path" 
                                                        class="w-full h-auto object-cover hover:opacity-90 transition-opacity"
                                                        @click="window.open(msg.image_path.startsWith('http') ? msg.image_path : '{{ asset('storage') }}/' + msg.image_path, '_blank')">
                                                </div>
                                            </template>

                                            <p class="whitespace-pre-wrap break-words" x-show="msg.message" x-text="msg.message"></p>
                                            
                                            <div class="flex items-center justify-end gap-1.5 mt-2 opacity-75 text-[8px]">
                                                <span x-text="new Date(msg.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})"></span>
                                                
                                                <template x-if="msg.sender_id == currentUserId">
                                                    <span class="inline-flex">
                                                        <template x-if="msg.is_read">
                                                            <i class="fas fa-check-double text-[9px] text-orange-400"></i>
                                                        </template>
                                                        <template x-if="!msg.is_read">
                                                            <i class="fas fa-check text-[9px] text-gray-300"></i>
                                                        </template>
                                                    </span>
                                                </template>
                                            </div>
                                        </div>

                                    </div>
                                </template>
                            </div>
                        </div>

                        <template x-if="imagePreviewUrl">
                            <div class="p-4 bg-gray-50 border-t border-gray-150 flex items-center gap-4 relative">
                                <div class="relative w-16 h-16 rounded-xl overflow-hidden border border-gray-200 shadow-sm shrink-0">
                                    <img :src="imagePreviewUrl" class="w-full h-full object-cover">
                                    <button type="button" @click="clearSelectedImage" 
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[9px] hover:bg-red-600 shadow-md">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="text-[10px]">
                                    <p class="font-bold text-gray-700" x-text="selectedImageFile?.name"></p>
                                    <p class="text-gray-400 mt-0.5" x-text="(selectedImageFile?.size / 1024).toFixed(1) + ' KB'"></p>
                                </div>
                            </div>
                        </template>

                        <template x-if="imageError">
                            <div class="px-6 py-2.5 bg-red-50 text-red-500 text-[10px] font-bold border-t border-red-100 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i> <span x-text="imageError"></span>
                            </div>
                        </template>

                        <div class="bg-white p-6 border-t border-gray-150 shadow-sm z-10">
                            <form @submit.prevent="sendMessage" class="flex items-center gap-4">
                                
                                <input type="file" x-ref="imageInput" @change="handleImageSelect" accept="image/*" class="hidden">

                                <div class="flex items-center gap-3 text-gray-400 text-lg">
                                    <button type="button" @click="$refs.imageInput.click()" class="hover:text-orange-500 transition">
                                        <i class="far fa-image"></i>
                                    </button>
                                </div>

                                <input type="text" x-model="newMessage" placeholder="Type your message here..."
                                       class="flex-1 bg-gray-50 border border-gray-150 rounded-2xl py-4 px-5 text-xs font-medium text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                
                                <button type="submit" 
                                        :disabled="newMessage.trim() === '' && !selectedImageFile"
                                        :class="(newMessage.trim() === '' && !selectedImageFile) ? 'opacity-50 cursor-not-allowed' : ''"
                                        class="bg-[#e67e22] hover:bg-[#d35400] text-white h-12 w-12 rounded-full transition shadow-lg shadow-orange-500/20 flex items-center justify-center shrink-0">
                                    <i class="fas fa-paper-plane text-sm"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                </template>

                <template x-if="!activePartnerId">
                    <div class="flex-1 flex flex-col items-center justify-center text-center p-8">
                        <div class="w-20 h-20 bg-orange-50 text-orange-500 rounded-[2rem] flex items-center justify-center text-3xl mb-4 shadow-sm">
                            <i class="far fa-comments"></i>
                        </div>
                        <h3 class="text-xl font-black text-[#0f2d50]">Kotak Obrolan Aktif</h3>
                        <p class="text-xs text-gray-400 max-w-sm mt-2 leading-relaxed">Pilih salah satu mitra di samping kiri untuk memulai koordinasi pengerjaan jasa Anda.</p>
                    </div>
                </template>

            </div>

        </div>
    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 99px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>