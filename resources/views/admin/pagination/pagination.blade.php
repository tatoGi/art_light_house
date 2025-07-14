<div class="flex items-center justify-between border-t border-gray-200  px-4 py-3 sm:px-6">
    <div class="flex flex-1 justify-between sm:hidden">
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 cursor-not-allowed">
                Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Previous
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Next
            </a>
        @else
            <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 cursor-not-allowed">
                Next
            </span>
        @endif
    </div>

    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                to
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                of
                <span class="font-medium">{{ $paginator->total() }}</span>
                results
            </p>
        </div>
        <div>
            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white cursor-not-allowed">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <a href="{{ $url }}" class="{{ $page == $paginator->currentPage() ? 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 ring-1 ring-inset ring-gray-300 focus:outline-offset-0 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-50' : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-offset-0 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-opacity-50' }}">
                                {{ $page }}
                            </a>
                        @endforeach
                    @endif
                @endforeach
            </nav>
        </div>
    </div>
</div>
