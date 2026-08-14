<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <!-- Modern Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent">Absen Pegawai</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Catat kehadiran Anda hari ini dengan mengunggah swafoto (selfie).</p>
            </div>
            <a href="{{ route('attendances.index') }}" class="group flex items-center justify-center w-12 h-12 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-emerald-50 hover:border-emerald-200 hover:shadow-emerald-500/10 transition-all duration-300 hover:scale-105">
                <svg class="w-6 h-6 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-8 rounded-2xl bg-rose-50/80 backdrop-blur-md p-5 text-sm text-rose-800 border border-rose-200/60 shadow-sm animate-fade-in-down">
                <div class="flex items-center gap-3 mb-3 font-bold text-rose-700">
                    <div class="p-1.5 bg-rose-200/50 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    Terdapat Kesalahan:
                </div>
                <ul class="list-disc pl-11 space-y-1 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] overflow-hidden relative">
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400"></div>
            
            <form action="{{ route('attendances.store') }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Nama Pegawai</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-500/70 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input type="text" value="{{ auth()->user()->name }}" disabled
                                class="w-full pl-12 rounded-2xl border border-slate-200/70 bg-slate-50/50 text-slate-600 font-semibold py-3.5 shadow-inner cursor-not-allowed">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Role / Posisi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-teal-500/70 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="text" value="{{ auth()->user()->role->name ?? '-' }}" disabled
                                class="w-full pl-12 rounded-2xl border border-slate-200/70 bg-slate-50/50 text-slate-600 font-semibold py-3.5 shadow-inner cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-bold text-slate-700">Bukti Foto Kehadiran <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="flex items-center justify-center w-full">
                            <label for="photo" id="dropzone" class="flex flex-col items-center justify-center w-full h-56 border-2 border-dashed border-emerald-200/80 rounded-3xl cursor-pointer bg-gradient-to-b from-emerald-50/30 to-teal-50/10 hover:bg-emerald-50/60 hover:border-emerald-400 transition-all duration-300 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-500/20 group-hover:shadow-[0_0_20px_rgba(16,185,129,0.08)]">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4 text-center">
                                    <div class="p-4 bg-white rounded-2xl shadow-sm text-emerald-500 mb-4 group-hover:scale-110 group-hover:-translate-y-1 group-hover:shadow-md transition-all duration-300 ring-1 ring-slate-100">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <p class="mb-2 text-sm text-slate-600 font-bold tracking-wide"><span class="text-emerald-600 group-hover:text-emerald-700 transition-colors">Klik untuk unggah</span> foto selfie</p>
                                    <p class="text-xs font-medium text-slate-400 bg-slate-100 px-3 py-1 rounded-full">JPG atau PNG (Maks 2MB)</p>
                                </div>
                                <input id="photo" type="file" name="photo" accept="image/*" required class="sr-only" onchange="updateFileName(this)" />
                            </label>
                        </div>
                        <div id="file-name-display" class="hidden absolute bottom-4 left-1/2 transform -translate-x-1/2 p-2 bg-white/95 backdrop-blur rounded-2xl border border-emerald-100 items-center justify-between shadow-lg shadow-emerald-500/10 w-11/12 max-w-sm">
                            <div class="flex items-center gap-3 overflow-hidden px-2">
                                <div class="p-1.5 bg-emerald-100 rounded-lg text-emerald-600 shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <span id="file-name-text" class="text-sm font-bold text-emerald-900 truncate"></span>
                            </div>
                            <button type="button" onclick="clearFile()" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all focus:outline-none shrink-0" title="Hapus foto">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-6 flex items-center justify-end gap-4 border-t border-slate-100/80">
                    <a href="{{ route('attendances.index') }}" class="px-6 py-3.5 rounded-2xl text-sm font-bold text-slate-500 bg-slate-50/50 hover:bg-slate-100 hover:text-slate-800 transition-all focus:outline-none focus:ring-4 focus:ring-slate-100">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 shadow-lg shadow-emerald-500/25 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/40 focus:outline-none focus:ring-4 focus:ring-emerald-500/30">
                        Kirim Kehadiran
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function updateFileName(input) {
            const fileNameDisplay = document.getElementById('file-name-display');
            const fileNameText = document.getElementById('file-name-text');
            const dropzone = document.getElementById('dropzone');
            
            if (input.files && input.files.length > 0) {
                fileNameText.textContent = input.files[0].name;
                fileNameDisplay.classList.remove('hidden');
                fileNameDisplay.classList.add('flex', 'animate-fade-in-up');
                dropzone.classList.add('border-emerald-400', 'bg-emerald-50/50');
            } else {
                fileNameDisplay.classList.add('hidden');
                fileNameDisplay.classList.remove('flex', 'animate-fade-in-up');
                dropzone.classList.remove('border-emerald-400', 'bg-emerald-50/50');
            }
        }

        function clearFile() {
            const input = document.getElementById('photo');
            input.value = '';
            updateFileName(input);
        }
        
        // Simple drag and drop effects
        const dropzone = document.getElementById('dropzone');
        
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
            dropzone.classList.add('border-emerald-500', 'bg-emerald-50');
        }

        function unhighlight(e) {
            dropzone.classList.remove('border-emerald-500', 'bg-emerald-50');
        }
    </script>

    <style>
        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fade-in-up {
            0% {
                opacity: 0;
                transform: translate(-50%, 10px);
            }
            100% {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.4s ease-out;
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.3s ease-out;
        }
    </script>
</x-app-layout>
