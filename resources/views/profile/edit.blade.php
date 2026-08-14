<x-app-layout>
    @section('title', 'Profil Akun')

    <div class="max-w-4xl mx-auto space-y-8">
        
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden p-8">
            <h2 class="text-xl font-bold text-slate-800 mb-6">Informasi Profil</h2>
            
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="flex flex-col sm:flex-row gap-8">
                    <!-- Avatar section -->
                    <div class="shrink-0 flex flex-col items-center w-full sm:w-auto">
                        <div class="w-32 h-32 shrink-0 aspect-square rounded-full bg-slate-100 mb-4 overflow-hidden shadow-inner border border-slate-200">
                            @if(auth()->user()->avatar)
                                <img id="avatar-preview" src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <div id="avatar-preview-fallback" class="w-full h-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-4xl font-bold text-white">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <img id="avatar-preview" class="w-full h-full object-cover hidden" alt="Avatar">
                            @endif
                        </div>
                        <label for="avatar" class="cursor-pointer bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-sm font-medium transition-colors text-center w-full">
                            Pilih Foto Baru
                        </label>
                        <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="previewImage(event)">
                        @error('avatar') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Info section -->
                    <div class="flex-1 space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all text-slate-700 font-medium py-2.5 px-4" required>
                            @error('name') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all text-slate-700 font-medium py-2.5 px-4" required>
                            @error('email') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-emerald-500 hover:bg-emerald-600 shadow-sm shadow-emerald-500/20 transition-all">Simpan Profil</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden p-8">
            <h2 class="text-xl font-bold text-slate-800 mb-6">Ubah Kata Sandi</h2>
            
            <form action="{{ route('profile.password') }}" method="POST" class="max-w-xl">
                @csrf
                @method('PUT')
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all py-2.5 px-4" required>
                        @error('current_password') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi Baru</label>
                        <input type="password" name="password" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all py-2.5 px-4" required>
                        @error('password') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all py-2.5 px-4" required>
                    </div>
                    
                    <div class="pt-2">
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-slate-800 hover:bg-slate-700 shadow-sm shadow-slate-500/20 transition-all">Perbarui Kata Sandi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('avatar-preview');
                const fallback = document.getElementById('avatar-preview-fallback');
                output.src = reader.result;
                output.classList.remove('hidden');
                if(fallback) {
                    fallback.classList.add('hidden');
                }
            };
            if(event.target.files[0]){
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
    @endpush
</x-app-layout>
