<x-app-layout>
    @section('title', 'Detail Pesanan')

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('warehouse-orders.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Invoice: {{ $warehouse_order->invoice_number }}</h1>
                @if($warehouse_order->status === 'pending')
                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 border border-slate-200">Pending</span>
                @elseif($warehouse_order->status === 'processing')
                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700 border border-amber-200">Dalam Proses</span>
                @else
                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">Selesai</span>
                @endif
            </div>
            <p class="text-sm text-slate-500 ml-12">Detail pesanan untuk pelanggan {{ $warehouse_order->customer?->name }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-teal-50 border border-teal-100 text-teal-700 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Daftar Barang</h2>
                
                <form action="{{ route('warehouse-orders.process', $warehouse_order) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        @foreach($warehouse_order->items as $item)
                            <label class="flex items-start gap-4 p-4 rounded-xl border {{ $item->is_processed ? 'bg-slate-50 border-emerald-200 opacity-75' : 'bg-white border-slate-200 hover:border-emerald-300' }} transition-colors cursor-pointer relative">
                                <div class="pt-1">
                                    <input type="checkbox" name="processed_items[]" value="{{ $item->id }}" 
                                        class="w-5 h-5 text-emerald-500 rounded border-slate-300 focus:ring-emerald-500"
                                        {{ $item->is_processed ? 'checked disabled' : '' }}>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-bold text-slate-800">{{ $item->item?->name }}</h3>
                                            <p class="text-xs text-slate-500 mt-1">Kode: {{ $item->item?->code }} &bull; SKU: {{ $item->item?->sku }}</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-bold text-slate-800">{{ $item->qty }} {{ $item->item?->unit }}</div>
                                            @if($item->is_processed)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full mt-2">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    SUDAH DIPROSES
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @if($warehouse_order->status !== 'completed')
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-emerald-500 hover:bg-emerald-600 shadow-sm shadow-emerald-500/20 transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Update Status Barang
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
                <h3 class="font-bold text-slate-800 mb-4">Informasi Tambahan</h3>
                <div class="space-y-4">
                    <div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Jenis Transaksi</div>
                        <div class="font-medium text-slate-800">
                            @if($warehouse_order->type === 'out')
                                <span class="text-rose-600">Barang Keluar (Penjualan)</span>
                            @else
                                <span class="text-teal-600">Barang Masuk (Pembelian)</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Sales</div>
                        <div class="font-medium text-slate-800">{{ $warehouse_order->sales?->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tanggal Pesanan</div>
                        <div class="font-medium text-slate-800">{{ $warehouse_order->invoice_date->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
