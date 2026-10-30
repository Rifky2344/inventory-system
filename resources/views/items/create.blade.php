<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Tambah Barang</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Isi detail di bawah untuk menambahkan barang baru ke dalam katalog.</p>
            </div>
            <a href="{{ route('items.index') }}" class="group flex items-center justify-center w-12 h-12 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-emerald-50 hover:border-emerald-200 hover:shadow-emerald-500/10 transition-all duration-300 hover:scale-105">
                <svg class="w-6 h-6 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] overflow-hidden relative">
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400"></div>
            
            <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" class="p-8 sm:p-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2" for="name">Nama Barang <span class="text-rose-500">*</span></label>
                            <input id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Kopi Bubuk Arabica"
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm" />
                            @error('name')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-bold text-slate-700" for="sku">SKU (Kode Barang) <span class="text-rose-500">*</span></label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="auto_sku" name="auto_sku" value="1" {{ old('auto_sku') ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 transition-colors"
                                        onchange="toggleSkuInput()">
                                    <span class="text-xs font-bold text-slate-600">Generate Otomatis</span>
                                </label>
                            </div>
                            <input id="sku" name="sku" value="{{ old('sku') }}" placeholder="Contoh: KOP-ARB-001"
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm uppercase placeholder:normal-case disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed" />
                            <p id="sku_help" class="mt-2 text-xs text-slate-500 font-medium hidden">SKU akan digenerate otomatis berdasarkan Kategori yang dipilih (Format: [Kode Kategori]-[Urutan]).</p>
                            @error('sku')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2" for="category_id">Kategori</label>
                            <select id="category_id" name="category_id" class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm cursor-pointer">
                                <option value="" {{ old('category_id') ? '' : 'selected' }}>Tanpa Kategori</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2" for="unit">Satuan <span class="text-rose-500">*</span></label>
                            <input id="unit" name="unit" value="{{ old('unit') }}" required placeholder="Contoh: Kg, Pcs, Dus"
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm" />
                            @error('unit')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="description">Deskripsi Lengkap</label>
                    <textarea id="description" name="description" rows="4" placeholder="Jelaskan detail barang secara lengkap..."
                        class="w-full rounded-2xl border border-slate-200/70 bg-white py-4 px-4 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm">{{ old('description') }}</textarea>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="image">Gambar Barang <span class="text-rose-500">*</span></label>
                    <div class="mt-2 flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-emerald-200/80 rounded-3xl cursor-pointer bg-gradient-to-b from-emerald-50/30 to-teal-50/10 hover:bg-emerald-50/60 hover:border-emerald-400 transition-all duration-300 relative group dropzone-container focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-500/20 overflow-hidden">
                        
                        <div class="space-y-3 text-center upload-placeholder z-10 p-6 flex flex-col items-center">
                            <div class="p-4 bg-white rounded-2xl shadow-sm text-emerald-500 mb-2 group-hover:scale-110 group-hover:-translate-y-1 group-hover:shadow-md transition-all duration-300 ring-1 ring-slate-100">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                            </div>
                            <p class="text-sm text-slate-600 font-bold tracking-wide"><span class="text-emerald-600 group-hover:text-emerald-700 transition-colors">Klik untuk unggah</span> atau tarik file ke sini</p>
                            <p class="text-xs font-medium text-slate-400 bg-slate-100 px-3 py-1 rounded-full">PNG, JPG, WEBP (Maks 2MB)</p>
                        </div>
                        
                        <!-- Preview container -->
                        <div class="preview-container hidden absolute inset-0 z-20 bg-white items-center justify-center p-4">
                            <img src="#" alt="Preview" class="image-preview max-h-full max-w-full object-contain rounded-xl drop-shadow-md hidden transition-transform duration-300 hover:scale-105" />
                            <div class="absolute bottom-4 inset-x-0 flex justify-center">
                                <span class="text-xs font-bold text-white bg-slate-900/70 backdrop-blur-md px-4 py-2 rounded-full file-name shadow-lg"></span>
                            </div>
                        </div>
                        
                        <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30" onchange="previewFile(this)" />
                    </div>
                    @error('image')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                </div>

                <div class="mt-10 pt-6 flex items-center justify-end gap-4 border-t border-slate-100/80">
                    <a href="{{ route('items.index') }}" class="px-6 py-3.5 rounded-2xl text-sm font-bold text-slate-500 bg-slate-50/50 hover:bg-slate-100 hover:text-slate-800 transition-all focus:outline-none focus:ring-4 focus:ring-slate-100">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 shadow-lg shadow-emerald-500/25 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/40 focus:outline-none focus:ring-4 focus:ring-emerald-500/30">
                        Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewFile(input) {
            const file = input.files[0];
            const container = input.closest('.dropzone-container');
            const placeholder = container.querySelector('.upload-placeholder');
            const previewContainer = container.querySelector('.preview-container');
            const imgPreview = container.querySelector('.image-preview');
            const fileName = container.querySelector('.file-name');

            if (file) {
                fileName.textContent = file.name;
                if (placeholder) placeholder.classList.add('opacity-0');
                if (previewContainer) {
                    previewContainer.classList.remove('hidden');
                    previewContainer.classList.add('flex');
                }

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (imgPreview) {
                            imgPreview.src = e.target.result;
                            imgPreview.classList.remove('hidden');
                        }
                    }
                    reader.readAsDataURL(file);
                }
            } else {
                if (previewContainer) {
                    previewContainer.classList.add('hidden');
                    previewContainer.classList.remove('flex');
                }
                if (placeholder) placeholder.classList.remove('opacity-0');
            }
        }
        
        // Drag and drop styles
        const dropzone = document.querySelector('.dropzone-container');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropzone.classList.add('border-emerald-500', 'bg-emerald-50', 'scale-[1.02]');
        }

        function unhighlight(e) {
            dropzone.classList.remove('border-emerald-500', 'bg-emerald-50', 'scale-[1.02]');
        }

        // Auto SKU Logic
        function toggleSkuInput() {
            const autoSkuCb = document.getElementById('auto_sku');
            const skuInput = document.getElementById('sku');
            const skuHelp = document.getElementById('sku_help');

            if (autoSkuCb.checked) {
                skuInput.disabled = true;
                skuInput.required = false;
                skuInput.value = '';
                skuHelp.classList.remove('hidden');
            } else {
                skuInput.disabled = false;
                skuInput.required = true;
                skuHelp.classList.add('hidden');
            }
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            toggleSkuInput();
        });
    </script>
</x-app-layout>
