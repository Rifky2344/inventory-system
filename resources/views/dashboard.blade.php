<x-app-layout>
    @php
        $lowStockCount = ($lowStockItems ?? collect())->count();
        $weeklyCount = ($weekly ?? collect())->count();
    @endphp

    <div class="relative isolate space-y-8">
        
        <!-- Welcome Hero Widget -->
        <div class="relative overflow-hidden rounded-[2.5rem] border border-white/60 bg-white/40 p-8 sm:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-2xl">
            <!-- Decorative gradient mesh -->
            <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-gradient-to-br from-emerald-300/40 to-teal-300/40 blur-3xl mix-blend-multiply"></div>
            <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-gradient-to-tr from-cyan-300/40 to-emerald-300/40 blur-3xl mix-blend-multiply"></div>
            
            <div class="relative flex flex-col gap-6 md:flex-row md:items-end md:justify-between z-10">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200/50 bg-emerald-50/80 px-4 py-1.5 text-xs font-bold tracking-wide text-emerald-700 shadow-sm">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        SISTEM AKTIF
                    </div>
                    <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-800">
                        Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600">{{ auth()->user()?->name }}</span> 👋
                    </h1>
                    <p class="mt-2 max-w-2xl text-base font-medium text-slate-500">
                        Pantau aktivitas stok, pergerakan barang, dan metrik operasional Gudang Hub dari satu tampilan terpadu.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 md:min-w-[340px]">
                    <div class="rounded-2xl border border-white/60 bg-white/60 p-5 shadow-sm backdrop-blur-xl hover:scale-[1.02] transition-transform duration-300">
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aktivitas Minggu Ini</div>
                        <div class="mt-2 text-3xl font-extrabold text-slate-800">{{ $weeklyCount }} <span class="text-sm font-semibold text-slate-400">baris</span></div>
                    </div>
                    <div class="rounded-2xl border border-rose-100/60 bg-gradient-to-br from-rose-50/80 to-red-50/50 p-5 shadow-sm backdrop-blur-xl hover:scale-[1.02] transition-transform duration-300 relative overflow-hidden">
                        <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-1/4 translate-y-1/4">
                            <svg class="w-24 h-24 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="text-xs font-bold text-rose-500 uppercase tracking-wider">Stok Rendah</div>
                        <div class="mt-2 text-3xl font-extrabold text-rose-700">{{ $lowStockCount }} <span class="text-sm font-semibold text-rose-400">item</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats 4 Columns -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Barang -->
            <div class="group relative overflow-hidden rounded-[2rem] border border-white/60 bg-white/40 p-6 shadow-sm backdrop-blur-xl hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div class="text-xs font-extrabold tracking-wider text-slate-500 uppercase">Total Barang</div>
                    <div class="rounded-2xl bg-emerald-100/80 p-3 text-emerald-600 shadow-sm border border-emerald-200/50">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                </div>
                <div class="relative z-10 mt-4 text-4xl font-extrabold tracking-tight text-slate-800">{{ $totalItems ?? 0 }}</div>
                <div class="relative z-10 mt-1 text-sm font-medium text-slate-400">Seluruh item aktif</div>
            </div>

            <!-- Total Kategori -->
            <div class="group relative overflow-hidden rounded-[2rem] border border-white/60 bg-white/40 p-6 shadow-sm backdrop-blur-xl hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div class="text-xs font-extrabold tracking-wider text-slate-500 uppercase">Total Kategori</div>
                    <div class="rounded-2xl bg-sky-100/80 p-3 text-sky-600 shadow-sm border border-sky-200/50">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </div>
                </div>
                <div class="relative z-10 mt-4 text-4xl font-extrabold tracking-tight text-slate-800">{{ $totalCategories ?? 0 }}</div>
                <div class="relative z-10 mt-1 text-sm font-medium text-slate-400">Struktur katalog</div>
            </div>

            <!-- Barang Masuk -->
            <div class="group relative overflow-hidden rounded-[2rem] border border-white/60 bg-white/40 p-6 shadow-sm backdrop-blur-xl hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div class="text-xs font-extrabold tracking-wider text-slate-500 uppercase">Barang Masuk (HARI INI)</div>
                    <div class="rounded-2xl bg-teal-100/80 p-3 text-teal-600 shadow-sm border border-teal-200/50">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m0 0l6-6m-6 6l-6-6"/></svg>
                    </div>
                </div>
                <div class="relative z-10 mt-4 text-4xl font-extrabold tracking-tight text-slate-800">{{ $inToday ?? 0 }}</div>
                <div class="relative z-10 mt-1 text-sm font-medium text-slate-400">Transaksi stok masuk</div>
            </div>

            <!-- Barang Keluar -->
            <div class="group relative overflow-hidden rounded-[2rem] border border-white/60 bg-white/40 p-6 shadow-sm backdrop-blur-xl hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div class="text-xs font-extrabold tracking-wider text-slate-500 uppercase">Barang Keluar (HARI INI)</div>
                    <div class="rounded-2xl bg-orange-100/80 p-3 text-orange-600 shadow-sm border border-orange-200/50">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 0l-6 6m6-6l6 6"/></svg>
                    </div>
                </div>
                <div class="relative z-10 mt-4 text-4xl font-extrabold tracking-tight text-slate-800">{{ $outToday ?? 0 }}</div>
                <div class="relative z-10 mt-1 text-sm font-medium text-slate-400">Transaksi stok keluar</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Stok Rendah Widget -->
            <section class="rounded-[2rem] border border-white/60 bg-white/50 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl flex flex-col h-full">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-800">Barang Stok Rendah</h2>
                        <p class="mt-1 text-sm font-medium text-slate-500">Ambang peringatan: {{ $lowStockThreshold ?? 0 }} item</p>
                    </div>
                    <span class="rounded-xl bg-rose-50 border border-rose-100 px-4 py-2 text-xs font-bold text-rose-600 shadow-sm">
                        {{ $lowStockCount }} PERLU RESTOCK
                    </span>
                </div>

                <div class="flex-1 overflow-y-auto space-y-3 pr-2 custom-scrollbar max-h-[350px]">
                    @forelse ($lowStockItems ?? [] as $item)
                        <div class="group flex items-center justify-between gap-4 rounded-2xl border border-slate-100 bg-white/60 p-4 transition-all hover:bg-white hover:shadow-md hover:scale-[1.01]">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="truncate text-sm font-bold text-slate-800 group-hover:text-emerald-700 transition-colors">{{ $item->name }}</div>
                                    <div class="mt-0.5 text-xs font-medium text-slate-400">SKU: {{ $item->sku ?? '-' }}</div>
                                </div>
                            </div>
                            <span class="inline-flex shrink-0 items-center rounded-xl border px-3.5 py-1.5 text-xs font-bold shadow-sm {{ $item->stock <= ($lowStockThreshold ?? 0) ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-amber-200 bg-amber-50 text-amber-700' }}">
                                Stok: {{ $item->stock }}
                            </span>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full min-h-[200px] text-center">
                            <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700">Stok Aman</h3>
                            <p class="text-xs text-slate-500 mt-1 max-w-[200px]">Tidak ada barang yang mendekati batas minimum stok saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Chart Widget -->
            <section class="rounded-[2rem] border border-white/60 bg-white/50 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl flex flex-col h-full">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-800">Analitik Aktivitas</h2>
                        <p class="mt-1 text-sm font-medium text-slate-500">Tinjauan pergerakan data</p>
                    </div>
                    <span class="rounded-xl bg-teal-50 border border-teal-100 px-4 py-2 text-xs font-bold text-teal-600 shadow-sm flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                        LIVE
                    </span>
                </div>

                <div class="flex-1 flex flex-col gap-5">
                    @if(isset($weeklySales) || isset($paymentsByMethod))
                        @if(isset($weeklySales))
                            <div class="flex-1 min-h-[200px] rounded-2xl border border-slate-100 bg-white p-4 shadow-inner relative">
                                <canvas id="weeklySalesChart"></canvas>
                            </div>
                        @endif

                        @if(isset($paymentsByMethod))
                            <div class="h-64 rounded-2xl border border-slate-100 bg-white p-4 shadow-inner relative">
                                <canvas id="paymentsByMethodChart"></canvas>
                            </div>
                        @endif
                    @else
                        <div class="flex-1 flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50 p-8 text-center min-h-[300px]">
                            <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                            <h3 class="text-sm font-bold text-slate-700">Belum Ada Data</h3>
                            <p class="text-xs text-slate-500 mt-2 max-w-[250px]">Grafik analitik akan otomatis muncul di sini setelah ada transaksi yang tercatat.</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>

        @if(in_array($role ?? null, ['admin','super-admin']))
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                <!-- Cicilan Terlambat Widget -->
                <section class="rounded-[2rem] border border-rose-100/60 bg-gradient-to-br from-white/60 to-rose-50/30 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl flex flex-col justify-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 flex items-center justify-center text-rose-600 shadow-sm border border-rose-200/50">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="rounded-xl bg-rose-50 border border-rose-200 px-3 py-1.5 text-[10px] font-extrabold text-rose-600 uppercase tracking-widest">Prioritas</span>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-800">Cicilan Terlambat</h3>
                    <div class="mt-4 flex items-baseline gap-2">
                        <div class="text-5xl font-extrabold tracking-tight text-rose-600 drop-shadow-sm">{{ $overdueInstallments ?? 0 }}</div>
                        <div class="text-sm font-bold text-slate-500 uppercase">Tagihan</div>
                    </div>
                    <div class="mt-4 inline-flex items-center gap-2 rounded-xl bg-white p-3 border border-slate-100 shadow-sm">
                        <div class="text-xs font-bold text-slate-400 uppercase">Total Rp</div>
                        <div class="text-lg font-extrabold text-slate-700">{{ number_format($overdueAmount ?? 0, 0, ',', '.') }}</div>
                    </div>
                </section>

                <!-- Sales Teratas Widget -->
                <section class="rounded-[2rem] border border-white/60 bg-white/50 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl xl:col-span-2 flex flex-col">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-800">Sales Teratas</h3>
                            <p class="mt-1 text-sm font-medium text-slate-500">Berdasarkan total nilai invoice yang dicetak</p>
                        </div>
                        <div class="p-2 bg-amber-50 rounded-xl border border-amber-100 text-amber-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                    </div>

                    <div class="flex-1 overflow-hidden rounded-2xl border border-slate-100 bg-white/60 shadow-inner">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm whitespace-nowrap">
                                <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-500 uppercase tracking-wider text-[11px] font-extrabold">
                                    <tr>
                                        <th class="px-6 py-4">Nama Pegawai / Sales</th>
                                        <th class="px-6 py-4 text-center">Jumlah Invoice</th>
                                        <th class="px-6 py-4 text-right">Total Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($topSales ?? [] as $index => $s)
                                        <tr class="hover:bg-slate-50/50 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs shadow-sm border border-slate-200">
                                                        #{{ $index + 1 }}
                                                    </div>
                                                    <span class="font-bold text-slate-800 group-hover:text-emerald-700 transition-colors">{{ $s->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 font-bold">
                                                    {{ $s->invoice_count }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="font-extrabold text-slate-700">Rp {{ number_format($s->total_sales, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-slate-400 font-medium">
                                                Belum ada data penjualan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        @endif
    </div>

    <!-- Custom scrollbar for small lists -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Setup global chart defaults for modern look
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.scale.grid.color = '#f1f5f9';

            @if(isset($weeklySales))
                const weeklyLabels = {!! json_encode($weeklySales->pluck('date')->toArray()) !!};
                const weeklyAmounts = {!! json_encode($weeklySales->pluck('amount')->map(fn($v)=> (float)$v)->toArray()) !!};
                
                const ctxSales = document.getElementById('weeklySalesChart').getContext('2d');
                // Create gradient for line chart
                const gradientSales = ctxSales.createLinearGradient(0, 0, 0, 400);
                gradientSales.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
                gradientSales.addColorStop(1, 'rgba(16, 185, 129, 0)');

                new Chart(ctxSales, {
                    type: 'line',
                    data: { 
                        labels: weeklyLabels, 
                        datasets: [{ 
                            label: 'Pendapatan', 
                            data: weeklyAmounts, 
                            borderColor: '#10b981', 
                            backgroundColor: gradientSales, 
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4 
                        }] 
                    },
                    options: { 
                        responsive:true, 
                        maintainAspectRatio:false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 12,
                                titleFont: { size: 13, family: "'Plus Jakarta Sans', sans-serif" },
                                bodyFont: { size: 14, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: { border: { display: false }, grid: { display: false } },
                            y: { 
                                border: { display: false },
                                ticks: {
                                    callback: function(value, index, values) {
                                        if (value >= 1000000) {
                                            return (value / 1000000) + 'M';
                                        } else if (value >= 1000) {
                                            return (value / 1000) + 'K';
                                        }
                                        return value;
                                    }
                                }
                            }
                        }
                    }
                });
            @endif

            @if(isset($paymentsByMethod))
                const payLabels = {!! json_encode($paymentsByMethod->pluck('payment_method')->toArray()) !!};
                const payTotals = {!! json_encode($paymentsByMethod->pluck('total')->map(fn($v)=> (float)$v)->toArray()) !!};
                new Chart(document.getElementById('paymentsByMethodChart'), {
                    type: 'doughnut',
                    data: { 
                        labels: payLabels, 
                        datasets: [{ 
                            data: payTotals, 
                            backgroundColor: ['#10b981', '#0ea5e9', '#f59e0b', '#8b5cf6', '#ec4899'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }] 
                    },
                    options: { 
                        responsive:true, 
                        maintainAspectRatio:false,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: { usePointStyle: true, padding: 20, font: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: '600' } }
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 12,
                                bodyFont: { size: 14, weight: 'bold' },
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed);
                                    }
                                }
                            }
                        }
                    }
                });
            @endif
        </script>
    @endpush
</x-app-layout>
