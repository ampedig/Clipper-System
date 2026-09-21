@if ($paginator->hasPages() || $paginator->total() > 0)
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">
            Menampilkan <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $paginator->total() }}</span> data
        </span>

        @if ($paginator->hasPages())
            <nav aria-label="Page navigation">
                <ul class="inline-flex items-center -space-x-px">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li>
                            <span class="flex items-center justify-center w-9 h-9 ml-0 leading-tight text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-[#111] border border-slate-200 dark:border-[#2e2e2e] rounded-l-lg cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center justify-center w-9 h-9 ml-0 leading-tight text-slate-500 dark:text-slate-400 bg-white dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-l-lg hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-slate-200 transition">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li>
                                <span class="flex items-center justify-center w-9 h-9 leading-tight text-slate-500 dark:text-slate-400 bg-white dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e]">
                                    {{ $element }}
                                </span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li>
                                        <span class="flex items-center justify-center w-9 h-9 leading-tight text-white bg-brand-500 border border-brand-500 hover:bg-brand-600 dark:bg-brand-600 dark:border-brand-600 font-semibold z-10 transition">
                                            {{ $page }}
                                        </span>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $url }}" class="flex items-center justify-center w-9 h-9 leading-tight text-slate-500 dark:text-slate-400 bg-white dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-slate-200 transition">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li>
                            <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center justify-center w-9 h-9 leading-tight text-slate-500 dark:text-slate-400 bg-white dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-r-lg hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-slate-200 transition">
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        </li>
                    @else
                        <li>
                            <span class="flex items-center justify-center w-9 h-9 leading-tight text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-[#111] border border-slate-200 dark:border-[#2e2e2e] rounded-r-lg cursor-not-allowed">
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>
@endif
