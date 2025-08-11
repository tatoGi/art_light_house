<x-admin.admin-layout>


    <div class="flex flex-wrap my-6 -mx-2">

        <div class="w-full max-w-5xl bg-white mt-2 mb-2 rounded-xl p-6 mx-auto shadow ring-1 ring-gray-100">

            <div class="mb-6 text-center">
                <h1 class="text-2xl font-semibold text-gray-800">Edit Banner</h1>
                <p class="text-sm text-gray-500 mt-1">Update the fields below for all languages as needed.</p>
            </div>

            <form action="{{ route('banners.update', [app()->getlocale() , $banner->id]) }}" method="POST"
                class="max-w-4xl mx-auto" enctype="multipart/form-data">

                @csrf

                @method('PUT')

                <!-- category_id -->

                <div class="rounded w-full mx-auto mt-4">

                    <!-- Tabs -->



                    <div class="language-selector">

                        <ul id="tabs" class="language-selector-list flex items-center gap-2 border-b border-gray-200">

                            @foreach (config('app.locales') as $locale)

                            @if($locale === 'en')

                            <li class="language-selector-item border-cyan-500">

                                @elseif($locale === 'ka')

                            <li class="language-selector-item border-red-600">

                                @endif

                                <a href="#locale-{{ $locale }}" class="language-selector-link inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-t-lg">


                                    <span class="language-name">{{ __('admin.locale_' . $locale) }}</span>

                                </a>

                            </li>

                            @endforeach

                        </ul>



                    </div>



                </div>

                <div id="tab-contents">

                    @foreach (config('app.locales') as $locale)

                    <div id="locale-{{ $locale }}" class=" @if ($locale !== app()->getLocale()) hidden @endif p-4 bg-gray-50/40 rounded-lg border border-gray-100 mt-4">

                        <div class="flex flex-col w-full items-start justify-center mb-4">

                            <label for="title_{{ $locale }}" class="text-sm font-medium"><span
                                    class="text-red-500">*</span>Title ( {{ __('admin.locale_' . $locale) }})</label>

                            <input type="text" name="{{ $locale }}[title]" id="title_{{ $locale }}"
                                value="{{ $banner->title }}"
                                class="mt-1 border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error('title_' . $locale) border-red-500 ring-1 ring-red-200 @enderror"
                                placeholder="Title">

                            @error('title_' . $locale)

                            <span class="text-red-500 text-sm">{{ $message }}</span>

                            @enderror

                        </div>

                        <div class="flex w-full items-start justify-center flex-col mb-4">
                            <label for="btn_text_{{ $locale }}" class="text-sm font-medium text-gray-700">Button Text ( {{ __('admin.locale_' . $locale) }} )</label>
                            <input type="text" name="{{ $locale }}[btn_text]" id="btn_text_{{ $locale }}"
                                   class="mt-1 border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error($locale . '.btn_text') border-red-500 ring-1 ring-red-200 @enderror"
                                   placeholder="e.g., Learn more" value="{{ old($locale.'.btn_text', optional($banner->translate($locale))->btn_text ?? '') }}">
                            @error($locale . '.btn_text')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex w-full items-start justify-center flex-col mb-2">
                            <label for="btn_url_{{ $locale }}" class="text-sm font-medium text-gray-700">Redirect Link ( {{ __('admin.locale_' . $locale) }} )</label>
                            <input type="text" name="{{ $locale }}[btn_url]" id="btn_url_{{ $locale }}"
                                   class="mt-1 border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error($locale . '.btn_url') border-red-500 ring-1 ring-red-200 @enderror"
                                   placeholder="https://example.com/path" value="{{ old($locale.'.btn_url', optional($banner->translate($locale))->btn_url ?? '') }}">
                            @error($locale . '.btn_url')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>



                        <div class="flex w-full items-start justify-center flex-col mb-4">

                            <label for="slug_{{ $locale }}" class="text-sm font-medium"><span
                                    class="text-red-500">*</span>URL Keyword

                                ( {{ __('admin.locale_' . $locale) }})</label>

                            <input type="text" name="{{ $locale }}[slug]" id="slug_{{ $locale }}"
                                value="{{ $banner->slug }}"
                                class="mt-1 border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error('slug_' . $locale) border-red-500 ring-1 ring-red-200 @enderror"
                                placeholder="URL Keyword">

                            @error('slug_' . $locale)

                            <span class="text-red-500 text-sm">{{ $message }}</span>

                            @enderror

                        </div>



                        <div class="flex w-full items-start justify-center flex-col mb-2">

                            <label for="description_{{ $locale }}"
                                class="text-sm font-medium text-gray-900 dark:text-gray-400"><span
                                    class="text-red-500">*</span>Description

                                ( {{ __('admin.locale_' . $locale) }})</label>

                            <textarea id="description_{{ $locale }}" name="{{ $locale }}[desc]" rows="4" class="w-full border rounded-lg p-4 mt-2 bg-white focus:ring-indigo-500 focus:border-indigo-500 @error('description_' . $locale) border-red ring-1 ring-red-200 @enderror"
                                 placeholder="Description">{!! optional($banner->translate($locale))->desc !!}</textarea>

                            @error('description_' . $locale)

                            <span class="text-red-500 text-sm">{{ $message }}</span>

                            @enderror

                        </div>

                    </div>

                    @endforeach

                </div>

                <div class="flex flex-col w-full items-center justify-center mb-2">

                    <label for="title_{{ $locale }}" class="text-sm font-medium"><span
                            class="text-red-500">*</span>{{ __('admin.type') }}</label>

                    <select class="border w-full text-sm rounded-lg block  p-2.5 @error('type') danger @enderror "
                        name="type_id" id="typeselect">

                        @foreach ($bannerTypes as $key => $type)

                        <option value="{{ $type['id'] }}" id="typeoption">{{ __('bannerTypes.'.$key) }}</option>

                        @endforeach

                    </select>

                </div>



                <!-- sort -->



                <!-- Banner Images -->
                <div class="mt-4 border border-dashed border-gray-400 rounded-lg p-4">
                    <label for="images" class="block font-medium text-gray-700 mb-2">Banner Images</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 mb-3">
                    <p class="text-sm text-gray-500 mb-3">Add more images to the banner</p>
                    
                    @if($banner->image)
                        <div class="flex flex-wrap imagePreview">
                            <p class="w-full text-sm text-gray-600 mb-2">Current banner image:</p>
                          
                            <div class="w-1/2 md:w-1/4 lg:w-1/3 p-2">
                                <div class="trash relative">
                                    <img src="{{ asset('storage/' . $banner->thumb) }}" alt="Banner Image" class="w-full h-24 object-cover rounded border">
                                    {{-- Delete Button --}}
                                    <button type="button" data-route="{{ route('banners.images.delete', [app()->getLocale(), $banner->id]) }}" data-id="{{ $banner->id }}" data-token="{{ csrf_token() }}" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 delete-image" id="delete_image_{{ $banner->id }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">No banner images yet.</p>
                    @endif
                    
                    @error('images.*')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                


                <div class="flex justify-between items-center pt-4 border-t border-gray-100">

                    <div class="mb-4">

                        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h9a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            Update Banner
                        </button>

                    </div>

                    <div>

                        @if(request('page_id'))
                            <a href="{{ route('admin.pages.management.manage', ['locale' => app()->getLocale(), 'page' => request('page_id')]) }}"
                               class="inline-flex items-center gap-2 bg-white text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm">Back to Page</a>
                        @else
                            <a href="{{ route('banners.index', ['locale' => app()->getLocale()]) }}"
                               class="inline-flex items-center gap-2 bg-white text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm">Back</a>
                        @endif

                    </div>

                </div>

                <input type="hidden" name="author_id" value="{{ auth()->user()->id }}">
                @if(request('page_id'))
                    <input type="hidden" name="page_id" value="{{ request('page_id') }}">
                @endif

            </form>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    <script>
        $(document).ready(function () {

            @foreach(config('app.locales') as $locale)

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
