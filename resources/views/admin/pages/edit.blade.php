<x-admin.admin-layout>
    <div class="flex flex-wrap my-6 -mx-2">
        <div class="w-full max-w-5xl bg-white mt-2 mb-2 rounded-xl p-6 mx-auto shadow ring-1 ring-gray-100">
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-semibold text-gray-800">Update Page</h1>
                <p class="text-sm text-gray-500 mt-1">Review and update the content across all languages.</p>
            </div>
            <form action="{{ route('pages.update', [app()->getlocale(), $page->id]) }}" method="POST"
                class="max-w-4xl mx-auto" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- page_id -->
                <div class="rounded w-full mx-auto mt-4">
                    <!-- Tabs -->

                    <div class="language-selector">
                        <ul id="tabs"
                            class="language-selector-list flex items-center gap-2 border-b border-gray-200">
                            @foreach (config('app.locales') as $locale)
                                @if ($locale === 'en')
                                    <li class="language-selector-item border-cyan-500">
                                    @elseif($locale === 'ka')
                                    <li class="language-selector-item border-red-600">
                                @endif
                                <a href="#locale-{{ $locale }}"
                                    class="language-selector-link inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-t-lg">

                                    <span class="language-name">{{ __('admin.locale_' . $locale) }}</span>
                                </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>

                </div>
                @if ($errors->any())
                    <div class="mb-4">
                        <div class="rounded-md bg-red-50 p-4 border border-red-200">
                            <div class="flex">
                                <div class="shrink-0">
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m-9.303.78a11.955 11.955 0 010-3.06C3.24 5.604 7.24 2.25 12 2.25c4.76 0 8.76 3.354 9.303 7.22.07.51.07 1.03 0 1.54-.543 3.866-4.543 7.22-9.303 7.22-4.76 0-8.76-3.354-9.303-7.22zM12 15.75h.008v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were some problems with your
                                        input</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div id="tab-contents">
                    @foreach (config('app.locales') as $locale)
                        <div id="locale-{{ $locale }}"
                            class=" @if ($locale !== app()->getLocale()) hidden @endif p-4 bg-gray-50/40 rounded-lg border border-gray-100 mt-4">
                            <div class="flex flex-col w-full items-start justify-center mb-4">
                                <label for="title_{{ $locale }}" class="text-sm font-medium text-gray-700"><span
                                        class="text-red-500">*</span> Title (
                                    {{ __('admin.locale_' . $locale) }})</label>
                                <input type="text" name="{{ $locale }}[title]" id="title_{{ $locale }}"
                                    class="mt-1 border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error('title_' . $locale) border-red-500 ring-1 ring-red-200 @enderror"
                                    placeholder="Title" value="{{ $page->translate($locale)->title ?? '' }}">
                                @error('title_' . $locale)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex w-full items-start justify-center flex-col mb-4">
                                <label for="slug_{{ $locale }}" class="text-sm font-medium text-gray-700"><span
                                        class="text-red-500">*</span> URL Keyword
                                    ({{ __('admin.locale_' . $locale) }})
                                </label>
                                <input type="text" name="{{ $locale }}[slug]" id="slug_{{ $locale }}"
                                    class="mt-1 border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error('slug_' . $locale) border-red-500 ring-1 ring-red-200 @enderror"
                                    placeholder="URL Keyword" value="{{ $page->translate($locale)->slug ?? '' }}">
                                @error('slug_' . $locale)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="keywords" class="block text-sm font-medium text-gray-700">Keywords (
                                    {{ __('admin.locale_' . $locale) }})</label>
                                <input type="text" value="{{ $page->translate($locale)->keywords ?? '' }}"
                                    id="keywords_{{ $locale }}" name="{{ $locale }}[keywords]"
                                    class="mt-1 border-gray-300 py-2
                              focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2">
                                @error('keywords')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex w-full items-start justify-center flex-col mb-2">
                                <label for="description_{{ $locale }}"
                                    class="text-sm font-medium text-gray-700"><span class="text-red-500">*</span>
                                    Description
                                    ( {{ __('admin.locale_' . $locale) }})</label>
                                <textarea id="description_{{ $locale }}" name="{{ $locale }}[description]"
                                    class="w-full border rounded-lg p-4 mt-2 bg-white focus:ring-indigo-500 focus:border-indigo-500 @error('description_' . $locale) border-red ring-1 ring-red-200 @enderror"
                                    placeholder="Description">{{ $page->translate($locale)->description ?? '' }}</textarea>
                                @error('description_' . $locale)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex flex-col mb-2 mt-4">
                                <label class="text-xl mr-2 mb-2 text-cyan-400 font-bold"> Active </label>
                                <!-- Hidden input to ensure active field is always submitted -->
                                <input type="hidden" name="{{ $locale }}[active]" value="0">
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="checkbox" name="{{ $locale }}[active]"
                                        id="{{ $locale }}-active" class="peer sr-only" value="1"
                                        @if ($page->translate($locale) !== null && $page->translate($locale)->active == 1) checked @endif />
                                    <div
                                        class="peer h-6 w-12 rounded-full border bg-gray-300 
                                    after:absolute after:left-[2px] after:top-0.5 after:h-5 after:w-5 
                                    after:rounded-full after:border after:border-gray-300 after:bg-white
                                    after:transition-all after:content-[''] 
                                    peer-checked:bg-blue-500 peer-checked:after:translate-x-full
                                    peer-checked:after:border-white peer-focus:ring-blue-300">
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- sort -->

                <!-- active -->
                <div class="flex s justify-center items-center flex-col mb-4 mt-4">
                    <label for="type"
                        class="text-sm font-medium text-gray-900 dark:text-gray-400">{{ trans('admin.type') }}</label>
                    <select id="type" name="type_id"
                        class="mt-1 border w-full border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block  p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:text-black">
                        <option value="">{{ trans('admin.type') }}</option>
                        @foreach ($sectionTypes as $key => $type)
                            <option value="{{ $type['id'] }}" {{ $type['id'] == $page->type_id ? 'selected' : '' }}>
                                {{ trans('sectionTypes.' . $key) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- images -->
                {{-- <div class="mb-4">
                <label for="icon" class="block font-medium text-gray-700">Icon</label>
                <input type="file" name="icon" id="icon" multiple class="border-gray-300 py-2 
                focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2">
                @error('images')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div> --}}
                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                    <div class="mb-4">
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd" d="M4 10a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Update Page
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('pages.index', app()->getlocale()) }}"
                            class="inline-flex items-center gap-2 bg-white text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4A1 1 0 018.707 6.707L6.414 9H17a1 1 0 110 2H6.414l2.293 2.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            Back
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    <script>
        $(document).ready(function() {

            @foreach (config('app.locales') as $locale)

                ClassicEditor

                    .create(document.querySelector('#description_{{ $locale }}'))

                    .then(editor => {

                        console.log(editor);

                    })

                    .catch(error => {

                        console.error(error);

                    });
            @endforeach

        });
    </script>
</x-admin.admin-layout>
