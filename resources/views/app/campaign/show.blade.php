@include('app.partials.head', [
    'title' => $campaign->title . ' - Detail Campaign',
])

@push('styles')
    <style>
        .brief-content {
            font-size: 0.8125rem;
            line-height: 1.625;
            color: #475569;
        }
        .brief-content > *:first-child {
            margin-top: 0 !important;
        }
        .brief-content > *:last-child {
            margin-bottom: 0 !important;
        }
        .brief-content p {
            margin-bottom: 0.625rem;
        }
        .brief-content h1, .brief-content h2, .brief-content h3 {
            font-weight: 700;
            color: #0f172a;
            margin-top: 0.875rem;
            margin-bottom: 0.375rem;
        }
        .brief-content h1 { font-size: 1.125rem; }
        .brief-content h2 { font-size: 1rem; }
        .brief-content h3 { font-size: 0.875rem; }
        .brief-content strong, .brief-content b {
            font-weight: 700;
            color: #0f172a;
        }
        .brief-content ul {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin-bottom: 0.625rem;
        }
        .brief-content ol {
            list-style-type: decimal;
            padding-left: 1.25rem;
            margin-bottom: 0.625rem;
        }
        .brief-content li {
            margin-bottom: 0.25rem;
        }
        .brief-content blockquote {
            border-left: 3px solid #6366f1;
            padding-left: 0.875rem;
            font-style: italic;
            color: #64748b;
            background-color: #f8fafc;
            border-radius: 0 0.75rem 0.75rem 0;
            margin: 0.75rem 0;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }
    </style>
@endpush

<!-- Top App Bar -->
<div class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200 px-5 py-4 flex items-center justify-between">
    <a href="{{ route('app.campaigns') }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors cursor-pointer" aria-label="Kembali">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <h1 class="text-sm font-bold text-slate-800">Detail Campaign</h1>
    <div class="w-8"></div>
</div>

<div class="px-5 pt-6 pb-28 space-y-6">

    <!-- Main Campaign Info Panel (Title, Desc, Brief) -->
    <div class="bg-white border border-slate-200 rounded-[1.5rem] overflow-hidden">
        <!-- Thumbnail -->
        <div class="relative w-full aspect-video bg-slate-100">
            <img src="{{ $campaign->thumbnail_url }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover">
        </div>

        <!-- Title & Description Header -->
        <div class="p-5 border-b border-slate-100 bg-slate-50/30">
            <h2 class="text-lg font-bold text-slate-900 leading-snug tracking-tight mb-1.5">{{ $campaign->title }}</h2>
            @if ($campaign->description)
                <p class="text-xs text-slate-500 leading-relaxed max-w-md">
                    {{ $campaign->description }}
                </p>
            @endif
        </div>
        
        <!-- Brief Campaign Content (WYSIWYG) -->
        <div class="p-5 text-[13px] text-slate-700 leading-relaxed space-y-4">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Brief Campaign</h3>
            
            @if ($campaign->brief)
                <div class="brief-content space-y-3">
                    {!! $campaign->brief !!}
                </div>
            @else
                <p class="text-xs text-slate-400 italic">Belum ada brief detail untuk campaign ini.</p>
            @endif
        </div>
    </div>

    <!-- Metrik Utama Panel -->
    <div class="bg-white rounded-[1.5rem] p-5 border border-slate-200">
        <div class="space-y-4">
            <!-- Komisi -->
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-coins text-sm"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Komisi</span>
                    <span class="text-sm font-bold text-slate-900">Rp{{ number_format($campaign->commission_amount, 0, ',', '.') }} <span class="text-[11px] font-medium text-slate-500">/ {{ $campaign->view_threshold >= 1000 ? ($campaign->view_threshold / 1000) . 'K' : $campaign->view_threshold }} views</span></span>
                </div>
            </div>

            <!-- Divider -->
            <div class="h-px bg-slate-100 ml-14"></div>

            <!-- Periode -->
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-calendar-days text-sm"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Periode</span>
                    <span class="text-sm font-bold text-slate-900">
                        {{ $campaign->start_at ? $campaign->start_at->translatedFormat('d M') : '-' }} - {{ $campaign->end_at ? $campaign->end_at->translatedFormat('d M Y') : 'Selesai' }}
                    </span>
                </div>
            </div>

            <!-- Divider -->
            <div class="h-px bg-slate-100 ml-14"></div>

            <!-- Sisa Kuota -->
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sisa Kuota</span>
                        <span class="text-xs font-bold text-indigo-600">
                            {{ $campaign->clipper_limit !== null ? $campaign->clipper_limit . ' slot' : 'Tanpa Batas' }}
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-indigo-500 h-1.5 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sumber Konten (Simplified Link Card) -->
    @if ($campaign->source_url)
        <a href="{{ $campaign->source_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 p-3.5 bg-white border border-slate-200 rounded-[1.5rem] hover:border-indigo-300 hover:shadow-sm transition-all group active:scale-[0.99]">
            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-100 group-hover:text-indigo-700 transition-colors shrink-0">
                <i class="fa-solid fa-folder-open text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-xs font-bold text-slate-900 group-hover:text-indigo-600 transition-colors truncate">Buka Sumber Konten</h3>
                <p class="text-[10px] text-indigo-500/80 truncate mt-0.5 font-medium">{{ \Illuminate\Support\Str::limit($campaign->source_url, 45) }}</p>
            </div>
            <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-600 group-hover:text-white transition-colors shrink-0">
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </div>
        </a>
    @endif

</div>

<!-- Bottom Action Bar (Floating) -->
<div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto px-5 pb-6 pt-2 z-40 pb-safe">
    <button type="button" onclick="openSubmissionSheet()" class="w-full bg-indigo-600 text-white font-bold text-sm py-3.5 rounded-2xl hover:bg-indigo-700 active:scale-[0.98] transition-all shadow-[0_8px_30px_rgb(0,0,0,0.12)] flex items-center justify-center gap-2 cursor-pointer">
        <i class="fa-solid fa-video text-xs"></i> Submit Video
    </button>
</div>

<!-- Modal Bottom Sheet: Submit Video -->
<div id="submitSheetModal" class="fixed inset-0 z-[60] flex items-end justify-center invisible pointer-events-none transition-all duration-300" aria-modal="true" role="dialog">
    <div id="submitBackdrop" onclick="closeSubmissionSheet()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-[2px] opacity-0 transition-opacity duration-300 pointer-events-auto cursor-pointer"></div>
    
    <div id="submitSheet" class="relative w-full max-w-md bg-white rounded-t-[28px] border-t border-slate-100 p-6 pb-8 transition-transform duration-300 ease-out transform translate-y-full z-10 select-none touch-pan-y pointer-events-auto flex flex-col shadow-[0_-10px_40px_rgba(0,0,0,0.1)]">
        
        <div id="submitDragHandle" class="w-12 h-1.5 bg-slate-300/80 hover:bg-slate-400 rounded-full mx-auto mb-5 cursor-grab active:cursor-grabbing transition-colors shrink-0"></div>

        <div class="text-center mb-5">
            <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-3 text-indigo-600">
                <i class="fa-solid fa-link text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Submit URL Video</h3>
            <p class="text-xs text-slate-500 mt-1">Masukkan link video TikTok, Reels, atau Shorts yang sudah Anda buat.</p>
        </div>

        <form action="{{ route('app.campaigns.submissions.store', $campaign->slug) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Link Video</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-link text-slate-400 text-sm"></i>
                        </div>
                        <input type="url" name="submitted_url" id="videoUrlInput" class="w-full pl-10 pr-4 py-3.5 bg-slate-50 border @error('submitted_url') border-red-400 @else border-slate-200 @enderror rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" placeholder="https://tiktok.com/@user/video/..." required>
                    </div>
                    @error('submitted_url')
                        <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" onclick="closeSubmissionSheet()" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm active:scale-[0.98] transition-all cursor-pointer">
                    Batal
                </button>
                <button type="submit" onclick="showLoadingState(this)" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm active:scale-[0.98] transition-all cursor-pointer relative overflow-hidden">
                    <span class="btn-text">Kirim</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // --- Bottom Sheet Logic ---
    const modal = document.getElementById('submitSheetModal');
    const backdrop = document.getElementById('submitBackdrop');
    const sheet = document.getElementById('submitSheet');
    
    let startY = 0;
    let currentY = 0;
    let isDragging = false;

    function openSubmissionSheet() {
        document.getElementById('videoUrlInput').value = '';
        sheet.style.transform = '';
        sheet.style.transition = '';
        backdrop.style.opacity = '';
        modal.classList.remove('invisible', 'pointer-events-none');
        
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            sheet.classList.remove('translate-y-full');
            sheet.classList.add('translate-y-0');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeSubmissionSheet() {
        sheet.style.transition = 'transform 0.25s cubic-bezier(0.16, 1, 0.3, 1)';
        sheet.style.transform = 'translateY(100%)';
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        
        setTimeout(() => {
            modal.classList.add('invisible', 'pointer-events-none');
            sheet.classList.remove('translate-y-0');
            sheet.classList.add('translate-y-full');
            sheet.style.transform = '';
            backdrop.style.opacity = '';
            document.body.style.overflow = '';
        }, 260);
    }

    function showLoadingState(btn) {
        // Hanya jalan jika form valid HTML5
        const form = btn.closest('form');
        if (form && form.checkValidity()) {
            const span = btn.querySelector('.btn-text');
            span.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
            btn.classList.add('opacity-80', 'cursor-not-allowed');
        }
    }

    // --- Flash Message Handling ---
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            openSubmissionSheet();
            setTimeout(() => { closeSubmissionSheet(); }, 100); // Hack to reset UI if needed or just let it be
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#4f46e5',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl font-bold px-5 py-2.5 text-xs'
                    }
                });
            }
        @endif

        @if(session('error') || $errors->any())
            openSubmissionSheet();
            @if(session('error'))
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: '{{ session('error') }}',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#4f46e5',
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl font-bold px-5 py-2.5 text-xs'
                        }
                    });
                }
            @endif
        @endif
    });

    // --- Drag to Dismiss Logic ---
    function onDragStart(clientY) {
        startY = clientY;
        currentY = clientY;
        isDragging = true;
        sheet.style.transition = 'none';
    }
    function onDragMove(clientY) {
        if (!isDragging) return;
        const deltaY = clientY - startY;
        if (deltaY > 0) {
            currentY = clientY;
            sheet.style.transform = `translateY(${deltaY}px)`;
            backdrop.style.opacity = `${Math.max(0.2, 1 - (deltaY/250))}`;
        }
    }
    function onDragEnd() {
        if (!isDragging) return;
        isDragging = false;
        if (currentY - startY > 75) {
            closeSubmissionSheet();
        } else {
            sheet.style.transition = 'transform 0.25s cubic-bezier(0.16, 1, 0.3, 1)';
            backdrop.style.transition = 'opacity 0.25s ease';
            sheet.style.transform = 'translateY(0)';
            backdrop.style.opacity = '1';
        }
    }

    // Touch Events
    sheet.addEventListener('touchstart', e => onDragStart(e.touches[0].clientY), { passive: true });
    sheet.addEventListener('touchmove', e => onDragMove(e.touches[0].clientY), { passive: true });
    sheet.addEventListener('touchend', onDragEnd);

    // Mouse Events
    let isMouseDragging = false;
    sheet.addEventListener('mousedown', e => {
        if (e.target.closest('button') || e.target.closest('input') || e.target.closest('a')) return;
        isMouseDragging = true;
        onDragStart(e.clientY);
    });
    window.addEventListener('mousemove', e => {
        if (isMouseDragging) onDragMove(e.clientY);
    });
    window.addEventListener('mouseup', () => {
        if (isMouseDragging) {
            isMouseDragging = false;
            onDragEnd();
        }
    });
</script>

@include('app.partials.vendor-script')
