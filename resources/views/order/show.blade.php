<x-app-layout>
    <div class="container mx-auto px-6 py-12 max-w-5xl"
         x-data="{ 
            showComplainModal: false,
            showCancelModal: false,
            showComplainModal: false,
            showCompleteModal: false,
            complaintReason: '',
            complaintDescription: '',
            cancelReason: '',
            cancelDescription: '',
            completePhoto: ''
         }">
        
        <nav class="flex justify-between items-center mb-10">
            <a href="{{ route('dashboard') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-orange-500 transition flex items-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Dashboard
            </a>
            <span class="bg-gray-150 text-gray-500 text-[9px] font-black px-4 py-2 rounded-xl uppercase tracking-widest border border-gray-200">
                Invoice Resmi
            </span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <div class="lg:col-span-2 space-y-8">
                @include('order.partials.status-alerts')
                @include('order.partials.progress-tracker')
                @include('order.partials.review-card')
                @include('order.partials.service-details')
                @include('order.partials.location-card')
            </div>

            <div class="lg:col-span-1">
                @include('order.partials.invoice-summary')
            </div>

        </div>

        @include('order.partials.modals')

    </div>
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
</style>