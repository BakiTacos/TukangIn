<x-app-layout>
    <!-- Kontainer Alpine.js Utama untuk Modal Tambah -->
    <div x-data="{ openAddModal: false }">
        
        <!-- Header Banner -->
        <div class="bg-[#0f2d50] pb-32 pt-12">
            <div class="container mx-auto px-6 text-white">
                <nav class="text-xs text-gray-400 mb-4 uppercase tracking-widest">
                    Home > Profil > Alamat Saya
                </nav>
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Alamat Saya</h1>
                        <p class="text-gray-300">Kelola lokasi pemasangan atau perbaikan rumah Anda.</p>
                    </div>
                    
                    <!-- Tombol Pemicu Modal Tambah -->
                    <button @click="openAddModal = true" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-2xl font-bold text-xs transition shadow-lg shadow-orange-500/20 transform hover:-translate-y-1 flex items-center gap-2">
                        <i class="fas fa-plus"></i> Tambah Alamat Baru
                    </button>
                </div>
            </div>
        </div>

        <!-- Daftar Alamat -->
        <div class="container mx-auto px-6 -mt-20 pb-20">
            <div class="max-w-4xl mx-auto grid gap-6">
                @forelse(auth()->user()->addresses as $address)
                <!-- Setiap Kartu Alamat memiliki Alpine.js sendiri untuk Modal Edit -->
                <div x-data="{ openEditModal: false }" class="bg-white p-8 rounded-[2.5rem] shadow-sm border {{ $address->is_primary ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-100' }} relative overflow-hidden transition-all hover:shadow-md">
                    @if($address->is_primary)
                        <div class="absolute top-0 right-0 bg-orange-500 text-white px-6 py-2 rounded-bl-3xl text-[10px] font-bold uppercase tracking-widest">Utama</div>
                    @endif
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-start gap-6">
                            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 flex-shrink-0">
                                <i class="fas {{ $address->label == 'Kantor' ? 'fa-briefcase' : 'fa-home' }} text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-[#0f2d50] mb-1">{{ $address->label }}</h4>
                                <p class="text-sm font-bold text-gray-700 mb-2">{{ $address->receiver_name }} <span class="text-gray-400 font-normal ml-2">| {{ $address->phone_number }}</span></p>
                                <p class="text-xs text-gray-500 leading-relaxed max-w-md">{{ $address->full_address }}, {{ $address->postal_code }}</p>
                                @if($address->note)
                                    <p class="text-[10px] text-orange-400 font-bold mt-2 italic"><i class="fas fa-info-circle mr-1"></i> Catatan: {{ $address->note }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex items-center gap-2 self-end md:self-center">
                            <button @click="openEditModal = true" class="p-3 text-gray-400 hover:text-orange-500 transition hover:bg-orange-50 rounded-xl" title="Edit Alamat">
                                <i class="far fa-edit text-lg"></i>
                            </button>
                            
                            <form action="{{ route('profile.address.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus alamat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-3 text-gray-400 hover:text-red-500 transition hover:bg-red-50 rounded-xl" title="Hapus Alamat">
                                    <i class="far fa-trash-alt text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- MODAL EDIT ALAMAT -->
                    <div x-show="openEditModal" class="fixed inset-0 z-[70] overflow-y-auto" x-cloak>
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="openEditModal = false"></div>
                            <div class="inline-block w-full max-w-xl p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2.5rem]">
                                <div class="flex justify-between items-center mb-8">
                                    <h3 class="text-2xl font-extrabold text-[#0f2d50]">Edit Alamat</h3>
                                    <button @click="openEditModal = false" class="text-gray-400 hover:text-gray-600 transition"><i class="fas fa-times text-xl"></i></button>
                                </div>

                                <form action="{{ route('profile.address.update', $address->id) }}" method="POST" class="space-y-5">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Label</label>
                                            <input type="text" name="label" value="{{ $address->label }}" required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Penerima</label>
                                            <input type="text" name="receiver_name" value="{{ $address->receiver_name }}" required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Telepon</label>
                                        <input type="text" name="phone_number" value="{{ $address->phone_number }}" required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Alamat Lengkap</label>
                                        <textarea name="full_address" rows="3" required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500">{{ $address->full_address }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Kode Pos</label>
                                            <input type="text" name="postal_code" value="{{ $address->postal_code }}" required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Patokan</label>
                                            <input type="text" name="note" value="{{ $address->note }}" class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500">
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 py-2">
                                        <input type="checkbox" name="is_primary" id="edit_primary_{{ $address->id }}" {{ $address->is_primary ? 'checked' : '' }} class="rounded text-orange-500 border-gray-300">
                                        <label for="edit_primary_{{ $address->id }}" class="text-xs font-bold text-gray-600">Jadikan Alamat Utama</label>
                                    </div>
                                    <div class="flex gap-4 pt-4">
                                        <button type="button" @click="openEditModal = false" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest">Batal</button>
                                        <button type="submit" class="flex-1 bg-[#0f2d50] hover:bg-orange-500 text-white py-4 rounded-2xl font-bold text-xs uppercase tracking-widest shadow-lg transition">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200 text-3xl">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-400">Belum ada alamat tersimpan</h3>
                    <p class="text-xs text-gray-300 mt-2">Tambahkan lokasi perbaikan kamu untuk mulai memesan teknisi.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- MODAL TAMBAH ALAMAT BARU -->
        <div x-show="openAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="openAddModal = false"></div>
                <div class="inline-block w-full max-w-xl p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2.5rem]">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-2xl font-extrabold text-[#0f2d50]">Tambah Alamat Baru</h3>
                        <button @click="openAddModal = false" class="text-gray-400 hover:text-gray-600 transition"><i class="fas fa-times text-xl"></i></button>
                    </div>

                    <form action="{{ route('profile.address.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Label Alamat</label>
                                <input type="text" name="label" placeholder="Rumah / Kantor" required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Nama Penerima</label>
                                <input type="text" name="receiver_name" placeholder="Nama Lengkap" required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Nomor Telepon</label>
                            <input type="text" name="phone_number" placeholder="0812..." required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Alamat Lengkap</label>
                            <textarea name="full_address" rows="3" placeholder="Jalan, No. Rumah, RT/RW..." required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Kode Pos</label>
                                <input type="text" name="postal_code" placeholder="15810" required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Patokan</label>
                                <input type="text" name="note" placeholder="Warna pagar, dll" class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-orange-500">
                            </div>
                        </div>
                        <div class="flex items-center gap-3 py-2">
                            <input type="checkbox" name="is_primary" id="is_primary" class="rounded text-orange-500 border-gray-300">
                            <label for="is_primary" class="text-xs font-bold text-gray-600">Jadikan Alamat Utama</label>
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="w-full bg-[#0f2d50] hover:bg-orange-500 text-white py-4 rounded-2xl font-bold shadow-lg transition transform hover:-translate-y-1 uppercase tracking-widest text-xs">Simpan Alamat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>