@extends('dashboard.layouts.app')

@section('title', 'Edit Campaign Clipper - Masum.xyz')

@push('styles')
    <!-- Quill Text Editor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/text-editor.page.css') }}">
    <!-- Select2 & Flatpickr & SweetAlert2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-2xl mx-auto space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        Edit Campaign Clipper
                    </h2>
                </div>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('dashboard'),
                    'crumb2_label' => 'List Clip',
                    'crumb2_url' => route('clip-campaigns.index'),
                    'crumb3_label' => 'Edit',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Form Card Utama -->
            <form id="editCampaignForm" action="{{ route('clip-campaigns.update', $clip_campaign) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 sm:p-8 space-y-8 transition-colors duration-300">

                    <!-- Sub-Header Form: Informasi Utama -->
                    <div
                        class="pb-4 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">
                                Informasi Campaign & Materi
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                                Perbarui thumbnail, judul, link acuan aset, dan deskripsi tujuan promosi.
                            </p>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">* Kolom wajib diisi</span>
                    </div>

                    <!-- Field: Thumbnail Campaign -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                            Thumbnail Campaign <span class="text-xs font-normal text-slate-400 lowercase">(opsional, rasio 16:9 landscape maks 5MB)</span>
                        </label>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-5 rounded-2xl border border-dashed border-slate-300 dark:border-[#2e2e2e] bg-slate-50/50 dark:bg-[#1a1a1a]/50">
                            <div class="relative group shrink-0">
                                <img id="thumbnailPreview"
                                    src="{{ $clip_campaign->thumbnail_url }}"
                                    data-original-src="{{ $clip_campaign->thumbnail_url }}"
                                    data-has-thumbnail="{{ $clip_campaign->thumbnail ? '1' : '0' }}"
                                    alt="Preview Thumbnail"
                                    class="w-44 sm:w-56 aspect-video rounded-xl object-cover border border-slate-200 dark:border-[#2e2e2e] shadow-sm bg-white dark:bg-[#222]">
                            </div>
                            <div class="flex-1 space-y-2.5">
                                <div class="flex flex-wrap items-center gap-3">
                                    <label for="thumbnailInput"
                                        class="btn btn-secondary text-xs py-2 px-3.5 cursor-pointer rounded-xl inline-flex items-center gap-2">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <span>Ganti Gambar</span>
                                    </label>
                                    @if($clip_campaign->thumbnail)
                                        <button type="button" id="btnToggleRemoveThumbnail"
                                            class="text-xs text-rose-500 hover:text-rose-600 font-medium inline-flex items-center gap-1.5 py-1.5 px-3 rounded-xl border border-rose-200 dark:border-rose-900/40 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-colors">
                                            <i class="fa-regular fa-trash-can"></i>
                                            <span id="removeThumbnailText">Hapus Thumbnail</span>
                                        </button>
                                    @endif
                                    <button type="button" id="btnResetThumbnail"
                                        class="text-xs text-slate-500 hover:text-slate-700 dark:text-slate-400 font-medium hidden">
                                        Batal Ganti
                                    </button>
                                </div>
                                <input type="hidden" name="remove_thumbnail" id="removeThumbnailInput" value="0">
                                <input type="file" name="thumbnail" id="thumbnailInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                                <p class="text-xs text-slate-400 dark:text-slate-500 leading-relaxed">
                                    Rekomendasi rasio <strong class="text-slate-600 dark:text-slate-300">16:9 (1280 x 720 px)</strong>. Format yang didukung: JPG, PNG, WEBP (maks. 5MB).
                                </p>
                                @error('thumbnail')
                                    <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Field: Judul Campaign & Link Acuan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Judul Campaign -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Judul Campaign <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-heading text-xs"></i>
                                </div>
                                <input type="text" name="title" id="campaignTitleInput"
                                    value="{{ old('title', $clip_campaign->title) }}" placeholder="Contoh: Review Aplikasi Web Clipper AI"
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 font-medium text-slate-800 dark:text-slate-200"
                                    required>
                            </div>
                            @error('title')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Link Acuan / Sumber Materi -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Link Acuan / Sumber Materi
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-link text-xs"></i>
                                </div>
                                <input type="url" name="source_url" id="campaignLinkInput"
                                    value="{{ old('source_url', $clip_campaign->source_url) }}"
                                    placeholder="https://drive.google.com/... atau link materi"
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 text-slate-800 dark:text-slate-200">
                            </div>
                            @error('source_url')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Field: Deskripsi (Textarea) -->
                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                            Deskripsi Singkat
                        </label>
                        <textarea name="description" id="campaignDescInput" rows="3"
                            placeholder="Tuliskan ringkasan singkat mengenai tujuan campaign dan konteks konten..."
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 text-slate-800 dark:text-slate-200 leading-relaxed resize-none">{{ old('description', $clip_campaign->description) }}</textarea>
                        @error('description')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Field: Brief Konten (Quill Rich Text Editor) -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Brief & Ketentuan Konten <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">WYSIWYG Rich Editor</span>
                        </div>

                        <!-- Hidden input untuk menampung isi HTML dari Quill editor -->
                        <input type="hidden" name="brief" id="briefInput" value="{{ old('brief', $clip_campaign->brief) }}">

                        <!-- Wrapper class for focus & theme styling -->
                        <div class="editor-wrapper">
                            <div id="briefEditor" style="min-height: 180px;">{!! old('brief', $clip_campaign->brief) !!}</div>
                        </div>
                        @error('brief')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">
                            Gunakan toolbar di atas untuk mengatur heading, format tebal/miring, daftar poin/list, serta link referensi.
                        </p>
                    </div>

                    <!-- Sub-Header Form: Skema Komisi & Target Kuota -->
                    <div class="pt-6 border-t border-slate-100 dark:border-[#2e2e2e]">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">
                            Skema Komisi & Kuota
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                            Atur besaran insentif per view dan alokasi batas kuota submission clipp.
                        </p>
                    </div>

                    <!-- Grid 4 Kolom: Komisi, Pencairan Penonton, Maks Views, Batas Clipp -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Jumlah Komisi -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Jumlah Komisi <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-coins text-xs"></i>
                                </div>
                                <input type="text" name="commission_amount" id="komisiInput"
                                    value="{{ old('commission_amount', 'Rp ' . number_format($clip_campaign->commission_amount, 0, ',', '.')) }}"
                                    placeholder="Contoh: Rp 5.000"
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 font-semibold text-slate-800 dark:text-slate-200"
                                    required>
                            </div>
                            @error('commission_amount')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Nilai komisi per kelipatan target penonton.</p>
                        </div>

                        <!-- Pencairan Penonton -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Pencairan Penonton <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </div>
                                <input type="number" name="view_threshold" id="penontonInput"
                                    value="{{ old('view_threshold', $clip_campaign->view_threshold) }}" placeholder="Contoh: 1000 atau 5000"
                                    min="1" step="1"
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 font-medium text-slate-800 dark:text-slate-200"
                                    required>
                            </div>
                            @error('view_threshold')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Minimal target view penonton.</p>
                        </div>

                        <!-- Maks Views -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Maks Views <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-arrow-up-right-dots text-xs"></i>
                                </div>
                                <input type="number" name="view_max" id="maksViewsInput"
                                    value="{{ old('view_max', $clip_campaign->view_max) }}" placeholder="Contoh: 50000" min="1"
                                    step="1"
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 font-medium text-slate-800 dark:text-slate-200"
                                    required>
                            </div>
                            @error('view_max')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Maksimal view yang dihitung komisi.</p>
                        </div>

                        <!-- Batas Clipp -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Batas Clipp (Kuota) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-film text-xs"></i>
                                </div>
                                <input type="number" name="clipper_limit" id="batasInput"
                                    value="{{ old('clipper_limit', $clip_campaign->clipper_limit) }}" placeholder="Contoh: 100" min="1"
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 font-semibold text-slate-800 dark:text-slate-200"
                                    required>
                            </div>
                            @error('clipper_limit')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Maksimal jumlah video clip yang diterima.</p>
                        </div>
                    </div>

                    <!-- Sub-Header Form: Jadwal Periode & Status -->
                    <div class="pt-6 border-t border-slate-100 dark:border-[#2e2e2e]">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">
                                    Periode & Status Pelaksanaan
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                                    Tentukan rentang tanggal berlangsungnya campaign serta status operasional saat ini.
                                </p>
                            </div>
                            <!-- Quick Preset Durasi -->
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span
                                    class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mr-1">Preset:</span>
                                <button type="button" data-days="7"
                                    class="btn-preset-days px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 dark:border-[#2e2e2e] bg-slate-50 dark:bg-[#161616] text-slate-600 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 dark:hover:text-brand-400 transition-colors cursor-pointer">
                                    +7 Hari
                                </button>
                                <button type="button" data-days="14"
                                    class="btn-preset-days px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 dark:border-[#2e2e2e] bg-slate-50 dark:bg-[#161616] text-slate-600 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 dark:hover:text-brand-400 transition-colors cursor-pointer">
                                    +14 Hari
                                </button>
                                <button type="button" data-days="30"
                                    class="btn-preset-days px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 dark:border-[#2e2e2e] bg-slate-50 dark:bg-[#161616] text-slate-600 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 dark:hover:text-brand-400 transition-colors cursor-pointer">
                                    +30 Hari
                                </button>
                                <button type="button" data-days="60"
                                    class="btn-preset-days px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 dark:border-[#2e2e2e] bg-slate-50 dark:bg-[#161616] text-slate-600 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 dark:hover:text-brand-400 transition-colors cursor-pointer">
                                    +60 Hari
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Grid 3 Kolom: Dimulai Pada, Diakhiri Pada, Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <!-- Dimulai Pada -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Dimulai Pada <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i class="fa-regular fa-calendar text-xs"></i>
                                </div>
                                <input type="text" name="start_at" id="startDateInput"
                                    value="{{ old('start_at', $clip_campaign->start_at ? $clip_campaign->start_at->format('Y-m-d') : '') }}"
                                    placeholder="Pilih tanggal mulai"
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 text-slate-800 dark:text-slate-200 font-medium cursor-pointer"
                                    required>
                            </div>
                            @error('start_at')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Diakhiri Pada -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Diakhiri Pada <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i class="fa-regular fa-calendar-check text-xs"></i>
                                </div>
                                <input type="text" name="end_at" id="endDateInput"
                                    value="{{ old('end_at', $clip_campaign->end_at ? $clip_campaign->end_at->format('Y-m-d') : '') }}"
                                    placeholder="Pilih tanggal selesai"
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 text-slate-800 dark:text-slate-200 font-medium cursor-pointer"
                                    required>
                            </div>
                            @error('end_at')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Status Campaign -->
                        <div class="sm:col-span-2 md:col-span-1">
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Status Campaign <span class="text-rose-500">*</span>
                            </label>
                            <select name="status" id="statusSelect" class="select2-status w-full" required>
                                @foreach (App\Enums\CampaignStatus::cases() as $status)
                                    <option value="{{ $status->value }}"
                                        {{ old('status', $clip_campaign->status->value) === $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Duration Summary Banner -->
                    <div id="durationSummaryBox"
                        class="hidden p-3.5 rounded-xl bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] flex items-center justify-between text-xs text-slate-600 dark:text-slate-300 transition-all duration-200">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days text-brand-500"></i>
                            <span id="durationSummaryText">Durasi campaign: <strong>0 Hari</strong></span>
                        </div>
                        <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500">Periode aktif clipper</span>
                    </div>

                    <!-- Tombol Aksi Bawah -->
                    <div
                        class="pt-6 border-t border-slate-100 dark:border-[#2e2e2e] flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('clip-campaigns.index') }}"
                            class="btn btn-secondary rounded-xl px-6 py-2.5 text-sm font-semibold text-center">
                            Batal
                        </a>
                        <button type="submit" id="btnSubmitCampaign"
                            class="btn btn-primary rounded-xl px-6 py-2.5 text-sm font-semibold flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-floppy-disk"></i> Perbarui Campaign
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <!-- Plugins Scripts -->
    <script src="{{ asset('assets/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/l10n/id.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>

    <script>
        let campaignQuill = null;

        function initQuillEditor() {
            if (document.getElementById('briefEditor') && typeof Quill !== 'undefined') {
                campaignQuill = new Quill('#briefEditor', {
                    theme: 'snow',
                    placeholder: "Tuliskan brief dan panduan konten di sini (ketentuan materi, do's & don'ts, pesan kunci, hook video, hashtag wajib)...",
                    modules: {
                        toolbar: [
                            [{ header: [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ color: [] }, { background: [] }],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            ['blockquote', 'code-block'],
                            ['link', 'image'],
                            ['clean']
                        ]
                    }
                });
            }
        }

        function initDatePicker() {
            if (typeof flatpickr !== 'undefined') {
                const config = {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "j F Y",
                    altInputClass: "w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 text-slate-800 dark:text-slate-200 font-medium cursor-pointer",
                    locale: typeof flatpickr.l10ns !== 'undefined' && flatpickr.l10ns.id ? flatpickr.l10ns.id : "default",
                    disableMobile: true,
                    onReady: function(selectedDates, dateStr, instance) {
                        appendCustomFooter(instance);
                    }
                };

                let startPicker = flatpickr("#startDateInput", {
                    ...config,
                    onChange: function(selectedDates) {
                        if (selectedDates[0] && endPicker) {
                            endPicker.set('minDate', selectedDates[0]);
                            if (endPicker.selectedDates[0] && endPicker.selectedDates[0] < selectedDates[0]) {
                                endPicker.clear();
                            }
                        }
                        updateDurationSummary(startPicker, endPicker);
                    }
                });

                let endPicker = flatpickr("#endDateInput", {
                    ...config,
                    onChange: function() {
                        updateDurationSummary(startPicker, endPicker);
                    }
                });

                // Preset Buttons
                const presetButtons = document.querySelectorAll('.btn-preset-days');
                presetButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const days = parseInt(this.dataset.days, 10);
                        if (isNaN(days)) return;

                        const now = new Date();
                        const endDate = new Date();
                        endDate.setDate(now.getDate() + days);

                        if (startPicker) startPicker.setDate(now, true);
                        if (endPicker) endPicker.setDate(endDate, true);

                        presetButtons.forEach(b => {
                            b.classList.remove('bg-brand-500', 'text-white', 'border-brand-500');
                            b.classList.add('bg-slate-50', 'dark:bg-[#161616]', 'text-slate-600', 'dark:text-slate-300');
                        });
                        this.classList.remove('bg-slate-50', 'dark:bg-[#161616]', 'text-slate-600', 'dark:text-slate-300');
                        this.classList.add('bg-brand-500', 'text-white', 'border-brand-500');
                    });
                });

                updateDurationSummary(startPicker, endPicker);
            }
        }

        function appendCustomFooter(fpInstance) {
            if (fpInstance.calendarContainer.querySelector('.fp-custom-footer')) return;

            const footer = document.createElement('div');
            footer.className = 'fp-custom-footer flex items-center justify-between p-2 border-t border-slate-100 dark:border-[#2e2e2e] text-xs';

            const leftGroup = document.createElement('div');
            leftGroup.className = 'flex items-center gap-1.5';

            const btnToday = document.createElement('button');
            btnToday.type = 'button';
            btnToday.className = 'text-brand-600 dark:text-brand-400 hover:underline font-semibold px-2 py-1';
            btnToday.innerHTML = '<i class="fa-solid fa-calendar-day mr-1"></i> Hari Ini';
            btnToday.addEventListener('click', (e) => {
                e.preventDefault();
                fpInstance.setDate(new Date(), true);
            });

            const btnClear = document.createElement('button');
            btnClear.type = 'button';
            btnClear.className = 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 px-2 py-1';
            btnClear.innerHTML = '<i class="fa-solid fa-rotate-left mr-1"></i> Reset';
            btnClear.addEventListener('click', (e) => {
                e.preventDefault();
                fpInstance.clear();
            });

            leftGroup.appendChild(btnToday);
            leftGroup.appendChild(btnClear);

            const btnClose = document.createElement('button');
            btnClose.type = 'button';
            btnClose.className = 'text-slate-500 dark:text-slate-400 hover:text-slate-700 px-2 py-1';
            btnClose.innerText = 'Tutup';
            btnClose.addEventListener('click', (e) => {
                e.preventDefault();
                fpInstance.close();
            });

            footer.appendChild(leftGroup);
            footer.appendChild(btnClose);
            fpInstance.calendarContainer.appendChild(footer);
        }

        function updateDurationSummary(startPicker, endPicker) {
            const summaryBox = document.getElementById('durationSummaryBox');
            const summaryText = document.getElementById('durationSummaryText');

            if (!summaryBox || !summaryText) return;

            if (startPicker && endPicker && startPicker.selectedDates[0] && endPicker.selectedDates[0]) {
                const diffTime = endPicker.selectedDates[0].getTime() - startPicker.selectedDates[0].getTime();
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays >= 0) {
                    summaryText.innerHTML = `Durasi campaign: <strong class="text-brand-600 dark:text-brand-400 font-semibold">${diffDays === 0 ? 1 : diffDays} Hari</strong> (${startPicker.input.value} &mdash; ${endPicker.input.value})`;
                    summaryBox.classList.remove('hidden');
                    return;
                }
            }
            summaryBox.classList.add('hidden');
        }

        function initSelect2() {
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('.select2-status').select2({
                    placeholder: "Pilih Status Campaign",
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });
            }
        }

        function initCurrencyFormat() {
            const komisiInput = document.getElementById('komisiInput');
            if (!komisiInput) return;

            komisiInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value) {
                    value = parseInt(value, 10).toLocaleString('id-ID');
                    this.value = 'Rp ' + value;
                } else {
                    this.value = '';
                }
            });
        }

        function initFormSubmit() {
            const form = document.getElementById('editCampaignForm');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const titleInput = document.getElementById('campaignTitleInput');
                const komisiInput = document.getElementById('komisiInput');
                const briefInput = document.getElementById('briefInput');

                if (!titleInput.value.trim()) {
                    showErrorAlert('Judul campaign wajib diisi!');
                    titleInput.focus();
                    return;
                }

                // Sync Quill editor to hidden input
                if (campaignQuill) {
                    const html = campaignQuill.root.innerHTML;
                    const text = campaignQuill.getText().trim();
                    if (!text) {
                        showErrorAlert('Brief konten wajib diisi untuk panduan para clipper!');
                        campaignQuill.focus();
                        return;
                    }
                    briefInput.value = html;
                }

                if (!komisiInput.value.trim()) {
                    showErrorAlert('Jumlah komisi wajib ditentukan!');
                    komisiInput.focus();
                    return;
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: "Konfirmasi Perubahan",
                        text: "Apakah Anda yakin ingin memperbarui data campaign ini?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Ya, Perbarui!",
                        cancelButtonText: "Periksa Kembali",
                        reverseButtons: true,
                        customClass: {
                            popup: "rounded-2xl dark:bg-[#222222] dark:text-white border dark:border-[#2e2e2e]",
                            confirmButton: "btn btn-primary rounded-xl px-5 py-2.5 font-semibold text-sm ml-2",
                            cancelButton: "btn btn-secondary rounded-xl px-5 py-2.5 font-semibold text-sm"
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    form.submit();
                }
            });
        }

        function showErrorAlert(message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: "Perhatian",
                    text: message,
                    icon: "warning",
                    confirmButtonText: "Mengerti",
                    customClass: {
                        popup: "rounded-2xl dark:bg-[#222222] dark:text-white border dark:border-[#2e2e2e]",
                        confirmButton: "btn btn-primary rounded-xl px-5 py-2.5 font-semibold text-sm"
                    },
                    buttonsStyling: false
                });
            } else {
                alert(message);
            }
        }

        function initThumbnailPreview() {
            const input = document.getElementById('thumbnailInput');
            const preview = document.getElementById('thumbnailPreview');
            const btnReset = document.getElementById('btnResetThumbnail');
            const btnToggleRemove = document.getElementById('btnToggleRemoveThumbnail');
            const removeInput = document.getElementById('removeThumbnailInput');
            const removeText = document.getElementById('removeThumbnailText');
            const titleInput = document.getElementById('campaignTitleInput');

            const originalSrc = preview ? preview.dataset.originalSrc : '';
            const hasThumbnail = preview ? preview.dataset.hasThumbnail === '1' : false;

            function getAvatarFallbackUrl() {
                const title = titleInput && titleInput.value.trim() ? titleInput.value.trim() : 'Campaign';
                return `https://ui-avatars.com/api/?name=${encodeURIComponent(title)}&background=3b82f6&color=fff&bold=true`;
            }

            if (input && preview) {
                input.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        if (file.size > 5 * 1024 * 1024) {
                            showErrorAlert('Ukuran file maksimal adalah 5MB!');
                            this.value = '';
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            if (btnReset) {
                                btnReset.classList.remove('hidden');
                            }
                            if (removeInput) {
                                removeInput.value = '0';
                            }
                            if (removeText) {
                                removeText.textContent = 'Hapus Thumbnail';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });

                if (btnReset) {
                    btnReset.addEventListener('click', function() {
                        input.value = '';
                        if (removeInput) {
                            removeInput.value = '0';
                        }
                        preview.src = originalSrc;
                        btnReset.classList.add('hidden');
                        if (removeText) {
                            removeText.textContent = 'Hapus Thumbnail';
                        }
                    });
                }

                if (btnToggleRemove && removeInput) {
                    btnToggleRemove.addEventListener('click', function() {
                        const isRemoving = removeInput.value === '1';
                        if (!isRemoving) {
                            removeInput.value = '1';
                            input.value = '';
                            preview.src = getAvatarFallbackUrl();
                            if (btnReset) {
                                btnReset.classList.add('hidden');
                            }
                            if (removeText) {
                                removeText.textContent = 'Batal Hapus';
                            }
                        } else {
                            removeInput.value = '0';
                            preview.src = originalSrc;
                            if (removeText) {
                                removeText.textContent = 'Hapus Thumbnail';
                            }
                        }
                    });
                }

                if (titleInput) {
                    titleInput.addEventListener('input', function() {
                        const isRemoving = removeInput && removeInput.value === '1';
                        const noFile = !input.files || input.files.length === 0;
                        if ((!hasThumbnail && noFile) || isRemoving) {
                            preview.src = getAvatarFallbackUrl();
                        }
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initThumbnailPreview();
            initQuillEditor();
            initDatePicker();
            initSelect2();
            initCurrencyFormat();
            initFormSubmit();
        });
    </script>
@endpush
