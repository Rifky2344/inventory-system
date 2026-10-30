<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-teal-600 to-indigo-600 bg-clip-text text-transparent">Buat Invoice Baru</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Terbitkan tagihan ke pelanggan dengan rincian barang, total biaya, dan metode pembayaran.</p>
            </div>
            <a href="{{ route('invoices.index') }}" class="group flex items-center justify-center w-12 h-12 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-teal-50 hover:border-teal-200 hover:shadow-teal-500/10 transition-all duration-300 hover:scale-105">
                <svg class="w-6 h-6 text-slate-400 group-hover:text-teal-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
        </div>

        <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm" class="space-y-6">
            @csrf

            <!-- Informasi Utama -->
            <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative">
                <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-teal-400 via-sky-400 to-indigo-400"></div>
                <div class="p-8 sm:p-10">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2 mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Metadata & Informasi Tagihan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <!-- Customer -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Pelanggan Tertagih <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="customer_id" required
                                    class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 pl-4 pr-10 text-slate-700 font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm appearance-none cursor-pointer">
                                    <option value="" disabled {{ old('customer_id') === null ? 'selected' : '' }}>-- Pilih Pelanggan --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->city }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                </div>
                            </div>
                            @error('customer_id')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <!-- Sales Person -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Sales Person (PIC)</label>
                            <input type="hidden" name="sales_id" value="{{ auth()->user()->role?->slug === 'sales' ? auth()->id() : '' }}" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <input type="text" value="{{ auth()->user()->name }}" disabled
                                    class="w-full rounded-2xl border border-slate-200/50 bg-slate-50/50 py-3.5 pl-12 pr-4 text-slate-500 font-bold shadow-sm cursor-not-allowed" />
                            </div>
                        </div>

                        <!-- Transaksi & Dates -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 col-span-1 md:col-span-2">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Transaksi <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="type" required
                                        class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 pl-4 pr-10 text-slate-700 font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm appearance-none cursor-pointer">
                                        <option value="out" {{ old('type', 'out') == 'out' ? 'selected' : '' }}>Barang Keluar (Penjualan)</option>
                                        <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Barang Masuk (Retur/Restock)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                    </div>
                                </div>
                                @error('type')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Terbit <span class="text-rose-500">*</span></label>
                                <input type="date" name="invoice_date" value="{{ old('invoice_date', now()->toDateString()) }}" required
                                    class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm" />
                                @error('invoice_date')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Jatuh Tempo Pembayaran <span class="text-rose-500">*</span></label>
                                <input type="date" name="due_date" value="{{ old('due_date', now()->addDays(30)->toDateString()) }}" required
                                    class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm" />
                                @error('due_date')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Invoice Numbering -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 col-span-1 md:col-span-2 bg-slate-50/50 p-5 rounded-3xl border border-slate-100">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Penomoran Invoice <span class="text-rose-500">*</span></label>
                                <div class="flex items-center mt-3">
                                    <input type="hidden" name="is_auto_invoice" value="0">
                                    <input type="checkbox" id="is_auto_invoice" name="is_auto_invoice" value="1" {{ old('is_auto_invoice', '1') == '1' ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-slate-300 text-teal-600 focus:ring-teal-500 transition-all cursor-pointer">
                                    <label for="is_auto_invoice" class="ml-3 text-sm font-semibold text-slate-600 cursor-pointer">
                                        Generate Nomor Otomatis
                                    </label>
                                </div>
                                <p class="mt-2 text-xs text-slate-500 font-medium">Hapus centang untuk memasukkan nomor invoice secara manual.</p>
                                @error('is_auto_invoice')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                            </div>

                            <div id="manualInvoiceNumberWrap" class="transition-all duration-300 {{ old('is_auto_invoice', '1') == '1' ? 'hidden opacity-0 pointer-events-none' : '' }}">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Invoice Manual <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" placeholder="Contoh: INV-2023-XYZ"
                                        class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm" />
                                </div>
                                @error('invoice_number')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Invoice (Keterangan Khusus)</label>
                            <textarea name="note" rows="2" placeholder="Cantumkan instruksi bank, syarat dan ketentuan yang berlaku..."
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm resize-none">{{ old('note') }}</textarea>
                            @error('note')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Item Line -->
            <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative">
                <div class="p-8 sm:p-10 pb-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2 mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Rincian Item (Line Items)
                    </h3>

                    <div class="overflow-x-auto bg-slate-50/50 rounded-2xl border border-slate-100">
                        <table class="w-full text-sm" id="itemsTable">
                            <thead>
                                <tr class="border-b border-slate-200/80">
                                    <th class="px-5 py-4 text-left font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Item / Produk</th>
                                    <th class="px-5 py-4 text-right font-extrabold text-slate-500 uppercase tracking-wider text-[11px] w-28">Kuantitas</th>
                                    <th class="px-5 py-4 text-right font-extrabold text-slate-500 uppercase tracking-wider text-[11px] w-48">Harga Satuan</th>
                                    <th class="px-5 py-4 text-right font-extrabold text-slate-500 uppercase tracking-wider text-[11px] w-48">Subtotal Line</th>
                                    <th class="px-5 py-4 text-center font-extrabold text-slate-500 uppercase tracking-wider text-[11px] w-16">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody" class="divide-y divide-slate-100">
                                <tr class="item-row transition-colors hover:bg-slate-50">
                                    <td class="px-5 py-3">
                                        <div class="relative">
                                            <select name="items[0][item_id]" class="w-full rounded-xl border border-slate-200 bg-white py-2 pl-3 pr-8 text-slate-700 font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all shadow-sm item-select text-sm appearance-none cursor-pointer" required>
                                                <option value="" disabled selected>-- Cari Item --</option>
                                                @foreach($items as $item)
                                                    <option value="{{ $item->id }}" data-name="{{ $item->name }}">{{ $item->name }} (Stok: {{ $item->stock }})</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <input type="number" name="items[0][qty]" class="w-full text-right rounded-xl border border-slate-200 bg-white py-2 px-3 text-slate-700 font-extrabold focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all shadow-sm qty-input text-sm" min="1" value="1" required />
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 font-medium text-xs">Rp</div>
                                            <input type="number" name="items[0][unit_price]" class="w-full text-right rounded-xl border border-slate-200 bg-white py-2 pl-8 pr-3 text-slate-700 font-extrabold focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all shadow-sm price-input text-sm" step="0.01" min="0" value="0" required />
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <span class="font-extrabold text-slate-800 text-sm subtotal-cell tracking-tight">Rp 0</span>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <button type="button" class="remove-row p-1.5 rounded-lg text-rose-400 hover:text-rose-600 hover:bg-rose-50 transition-colors focus:outline-none" style="display: none;" title="Hapus Baris">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="p-3 border-t border-slate-200/80 bg-white/50">
                            <button type="button" id="addItemBtn" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold text-teal-600 bg-teal-50 border border-teal-100 hover:bg-teal-100 transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Baris Produk
                            </button>
                        </div>
                    </div>
                    @error('items')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Grand Summary & Submit -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 relative">
                <!-- Spacing -->
                <div class="hidden md:block md:col-span-5 lg:col-span-6"></div>

                <!-- Summary Box -->
                <div class="md:col-span-7 lg:col-span-6">
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2rem] shadow-xl p-8 text-white relative overflow-hidden">
                        <div class="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-gradient-to-br from-teal-500/30 to-indigo-500/30 blur-3xl pointer-events-none"></div>
                        
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 border-b border-slate-700/80 pb-2">Kalkulasi Akhir</h3>
                        
                        <div class="space-y-4 relative z-10">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-300">Total Kuantitas Barang</span>
                                <span class="text-xl font-bold font-mono" id="totalQtyDisplay">0</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-300">Subtotal</span>
                                <span class="text-xl font-bold font-mono tracking-wide" id="subtotalDisplay">Rp 0</span>
                            </div>
                            
                            <div class="h-px w-full bg-slate-700/80 my-2"></div>
                            
                            <div class="flex items-end justify-between">
                                <span class="text-base font-bold text-teal-400">Grand Total (Total Tagihan)</span>
                                <span class="text-3xl font-extrabold text-white tracking-tight" id="totalDisplay">Rp 0</span>
                            </div>
                        </div>

                        <div class="mt-10 flex gap-3">
                            <a href="{{ route('invoices.index') }}" class="flex-1 text-center py-4 rounded-2xl text-sm font-bold text-slate-300 bg-slate-800 border border-slate-700 hover:bg-slate-700 hover:text-white transition-all focus:outline-none focus:ring-4 focus:ring-slate-700">
                                Batal
                            </a>
                            <button type="submit" class="flex-[2] py-4 rounded-2xl text-sm font-bold text-slate-900 bg-gradient-to-r from-teal-400 to-teal-300 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 hover:scale-[1.02] transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-teal-500/30">
                                Terbitkan Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Scripts for logic -->
    <script>
        const itemSelect = document.querySelector('.item-select');
        const addItemBtn = document.getElementById('addItemBtn');
        const itemsBody = document.getElementById('itemsBody');
        const isInstallmentSelect = document.getElementById('isInstallmentSelect');
        const installmentCountWrap = document.getElementById('installmentCountWrap');
        let itemCount = 1;

        function updateCalculations() {
            let subtotal = 0;
            let totalQty = 0;

            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const rowSubtotal = qty * price;

                subtotal += rowSubtotal;
                totalQty += qty;

                row.querySelector('.subtotal-cell').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(rowSubtotal));
            });

            const formattedTotal = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(subtotal));
            document.getElementById('subtotalDisplay').textContent = formattedTotal;
            document.getElementById('totalDisplay').textContent = formattedTotal;
            document.getElementById('totalQtyDisplay').textContent = totalQty;
        }

        addItemBtn.addEventListener('click', function() {
            const newRow = document.createElement('tr');
            newRow.className = 'item-row transition-colors hover:bg-slate-50 border-t border-slate-100';
            newRow.innerHTML = `
                <td class="px-5 py-3">
                    <div class="relative">
                        <select name="items[${itemCount}][item_id]" class="w-full rounded-xl border border-slate-200 bg-white py-2 pl-3 pr-8 text-slate-700 font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all shadow-sm item-select text-sm appearance-none cursor-pointer" required>
                            <option value="" disabled selected>-- Cari Item --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" data-name="{{ $item->name }}">{{ $item->name }} (Stok: {{ $item->stock }})</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3">
                    <input type="number" name="items[${itemCount}][qty]" class="w-full text-right rounded-xl border border-slate-200 bg-white py-2 px-3 text-slate-700 font-extrabold focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all shadow-sm qty-input text-sm" min="1" value="1" required />
                </td>
                <td class="px-5 py-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 font-medium text-xs">Rp</div>
                        <input type="number" name="items[${itemCount}][unit_price]" class="w-full text-right rounded-xl border border-slate-200 bg-white py-2 pl-8 pr-3 text-slate-700 font-extrabold focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all shadow-sm price-input text-sm" step="0.01" min="0" value="0" required />
                    </div>
                </td>
                <td class="px-5 py-3 text-right">
                    <span class="font-extrabold text-slate-800 text-sm subtotal-cell tracking-tight">Rp 0</span>
                </td>
                <td class="px-5 py-3 text-center">
                    <button type="button" class="remove-row p-1.5 rounded-lg text-rose-400 hover:text-rose-600 hover:bg-rose-50 transition-colors focus:outline-none" title="Hapus Baris">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </td>
            `;

            itemsBody.appendChild(newRow);
            itemCount++;

            updateRowHandlers();
            updateCalculations();
        });

        function updateRowHandlers() {
            document.querySelectorAll('.qty-input, .price-input').forEach(input => {
                input.removeEventListener('input', updateCalculations);
                input.addEventListener('input', updateCalculations);
            });

            document.querySelectorAll('.remove-row').forEach(btn => {
                btn.removeEventListener('click', removeRow);
                btn.addEventListener('click', removeRow);
            });

            // Show remove button only if more than 1 row
            const rowCount = document.querySelectorAll('.item-row').length;
            document.querySelectorAll('.remove-row').forEach(btn => {
                btn.style.display = rowCount > 1 ? 'inline-block' : 'none';
            });
        }

        function removeRow(e) {
            e.preventDefault();
            const btn = e.target.closest('button');
            if(btn) {
                btn.closest('.item-row').remove();
                updateRowHandlers();
                updateCalculations();
            }
        }

        updateRowHandlers();
        updateCalculations();

        const isAutoInvoice = document.getElementById('is_auto_invoice');
        const manualInvoiceNumberWrap = document.getElementById('manualInvoiceNumberWrap');

        function toggleManualInvoiceNumber() {
            const isAuto = isAutoInvoice.checked;
            
            if (!isAuto) {
                manualInvoiceNumberWrap.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
            } else {
                manualInvoiceNumberWrap.classList.add('hidden', 'opacity-0', 'pointer-events-none');
                const invoiceInput = manualInvoiceNumberWrap.querySelector('input[name="invoice_number"]');
                if (invoiceInput) invoiceInput.value = '';
            }
        }

        isAutoInvoice.addEventListener('change', toggleManualInvoiceNumber);
        // Run once on load to handle old() validation state
        toggleManualInvoiceNumber();
    </script>
</x-app-layout>
