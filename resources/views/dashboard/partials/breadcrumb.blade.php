<!--
    BREADCRUMB PARTIAL
    ==================
    Cara pakai (2 level - halaman aktif di crumb2):
    include breadcrumb.html dengan variabel:
      crumb1_label = "Dashboard", crumb1_url = "index.html"
      crumb2_label = "Project",   crumb2_url = ""
      crumb3_label = "",          crumb3_url = ""

    Cara pakai (3 level - halaman aktif di crumb3):
      crumb1_label = "Dashboard", crumb1_url = "index.html"
      crumb2_label = "Project",   crumb2_url = "project.html"
      crumb3_label = "Tambah",    crumb3_url = ""

    Catatan:
    - crumb1 selalu root (Dashboard), selalu tampil sebagai link dengan icon rumah
    - crumb2 tampil sebagai link jika crumb2_url diisi DAN crumb3_label ada, atau teks biasa (current) jika tidak
    - crumb3 opsional, hanya tampil jika crumb3_label diisi (selalu current page, tanpa link)
-->

<nav class="hidden sm:flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center text-base font-medium">

        <!-- Level 1: Root (selalu link dengan icon rumah) -->
        <li class="inline-flex items-center">
            <a href="{{ $crumb1_url ?? '#' }}"
                class="inline-flex items-center text-slate-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition-colors duration-200">
                <i class="fa-solid fa-house mr-2 text-sm text-brand-600 dark:text-brand-400"></i>
                {{ $crumb1_label ?? 'Dashboard' }}
            </a>
        </li>

        <!-- Level 2 -->
        @if (!empty($crumb2_label))
        <li class="flex items-center">
            <i class="fa-solid fa-chevron-right text-xs mx-2.5 text-slate-300 dark:text-slate-600"></i>
            @if (!empty($crumb2_url) && !empty($crumb3_label))
            <a href="{{ $crumb2_url }}"
                class="text-slate-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition-colors duration-200">
                {{ $crumb2_label }}
            </a>
            @endif
            @if (empty($crumb2_url) || empty($crumb3_label))
            <span class="text-slate-400 dark:text-slate-500">{{ $crumb2_label }}</span>
            @endif
        </li>
        @endif

        <!-- Level 3 (opsional, selalu current page) -->
        @if (!empty($crumb3_label))
        <li class="flex items-center">
            <i class="fa-solid fa-chevron-right text-xs mx-2.5 text-slate-300 dark:text-slate-600"></i>
            <span class="text-slate-400 dark:text-slate-500">{{ $crumb3_label }}</span>
        </li>
        @endif

    </ol>
</nav>
