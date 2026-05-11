<x-app-layout>
    <div class="container mx-auto px-6 py-12 max-w-xl" x-data="{ rating: 0, hoverRating: 0 }">
        
        <div class="mb-10">
            <a href="{{ route('orders.show', $order->id) }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-orange-500 transition flex items-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Rincian Pesanan
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 text-center space-y-8">
            
            <div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode($order->tukang->name) }}&background=FFEDD5&color=F97316" class="w-20 h-20 rounded-3xl mx-auto border-2 border-orange-200 shadow-md mb-4">
                <h1 class="text-2xl font-black text-[#0f2d50] leading-tight">Bagaimana Hasil Pekerjaan?</h1>
                <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-bold">{{ $order->tukang->name }} • {{ $order->service->title }}</p>
            </div>

            <form action="{{ route('reviews.store', $order->id) }}" method="POST" class="space-y-8 text-left">
                @csrf
                
                <div class="text-center space-y-3">
                    <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider">Berikan Rating Bintang</label>
                    
                    <div class="flex justify-center gap-3">
                        <template x-for="star in 5">
                            <button type="button" 
                                    @click="rating = star"
                                    @mouseover="hoverRating = star"
                                    @mouseleave="hoverRating = 0"
                                    class="text-4xl transition-all transform hover:scale-125 focus:outline-none">
                                <svg class="w-12 h-12 transition-all duration-150"
                                     :class="(hoverRating ? star <= hoverRating : star <= rating) ? 'text-yellow-400 fill-current scale-110' : 'text-gray-200'"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </button>
                        </template>
                    </div>

                    <p class="text-xs font-black text-orange-500 min-h-[1.5rem]"
                       x-text="rating === 5 ? 'Sangat Puas! (Sempurna)' : 
                               rating === 4 ? 'Puas! (Sesuai Ekspektasi)' : 
                               rating === 3 ? 'Cukup Baik (Ada Kekurangan)' : 
                               rating === 2 ? 'Buruk (Kurang Merekomendasikan)' : 
                               rating === 1 ? 'Sangat Buruk! (Sangat Kecewa)' : ''">
                    </p>

                    <input type="hidden" name="rating" :value="rating" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider">Tulis Ulasan Anda (Opsional)</label>
                    <textarea name="comment" maxlength="500"
                              placeholder="Bagikan pengalaman Anda mengenai ketepatan waktu, kerapihan kerja, ramah lingkungan, dll..."
                              class="w-full bg-gray-50 border border-gray-150 rounded-2xl py-4 px-5 text-xs font-medium text-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent h-32 resize-none placeholder-gray-350"></textarea>
                </div>

                <button type="submit" 
                        :disabled="rating === 0"
                        :class="rating === 0 ? 'opacity-50 cursor-not-allowed hover:transform-none' : ''"
                        class="w-full bg-green-500 hover:bg-green-600 text-white py-5 rounded-2xl font-bold text-xs uppercase tracking-widest transition shadow-lg shadow-green-500/20 transform hover:-translate-y-1 block text-center">
                    Kirim Ulasan
                </button>
            </form>
        </div>
    </div>
</x-app-layout>