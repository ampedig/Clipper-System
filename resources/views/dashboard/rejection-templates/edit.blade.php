@extends('dashboard.layouts.app')

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-md mx-auto">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                    Edit Template Penolakan
                </h2>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Template Penolakan',
                    'crumb2_url' => route('admin.rejection-templates.index'),
                    'crumb3_label' => 'Edit',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden transition-colors duration-300">
                <div class="p-5 md:p-6 border-b border-slate-100 dark:border-[#2e2e2e]">
                    <h3 class="text-lg font-medium text-slate-800 dark:text-slate-200">
                        Edit Pesan Cepat
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Perbarui rincian command dan isi pesan penolakan di bawah ini.
                    </p>
                </div>

                <form action="{{ route('admin.rejection-templates.update', $rejectionTemplate->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-5 md:p-6 space-y-6">
                        
                        <!-- Input Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Judul Template <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                name="title" 
                                id="title" 
                                value="{{ old('title', $rejectionTemplate->title) }}" 
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-[#161616] focus:border-brand-500 dark:focus:border-brand-500 outline-none transition-all @error('title') border-rose-500 focus:border-rose-500 @enderror" 
                                placeholder="Contoh: Video Blur"
                                required>
                            @error('title')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Input Command -->
                        <div>
                            <label for="command" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Command (Tanpa Garis Miring) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                name="command" 
                                id="command" 
                                value="{{ old('command', str_replace('/', '', $rejectionTemplate->command)) }}" 
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-[#161616] focus:border-brand-500 dark:focus:border-brand-500 outline-none transition-all @error('command') border-rose-500 focus:border-rose-500 @enderror" 
                                placeholder="contoh command atau spasi biasa"
                                required>
                            @error('command')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @else
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                    Sistem akan otomatis mengubahnya menjadi format command (Contoh: "video jelek" menjadi <code>/video-jelek</code>). Command saat ini: <strong>{{ $rejectionTemplate->command }}</strong>
                                </p>
                            @enderror
                        </div>

                        <!-- Input Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Isi Pesan Penolakan <span class="text-rose-500">*</span>
                            </label>
                            <textarea 
                                name="message" 
                                id="message" 
                                rows="5"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-[#161616] focus:border-brand-500 dark:focus:border-brand-500 outline-none transition-all @error('message') border-rose-500 focus:border-rose-500 @enderror" 
                                placeholder="Ketik pesan lengkap alasan penolakan di sini..."
                                required>{{ old('message', $rejectionTemplate->message) }}</textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Footer / Actions -->
                    <div class="px-5 py-4 border-t border-slate-100 dark:border-[#2e2e2e] bg-slate-50/50 dark:bg-[#1c1c1c]/50 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.rejection-templates.index') }}" 
                           class="btn bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#2a2a2a] transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save mr-1.5"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
