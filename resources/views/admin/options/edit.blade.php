<x-admin.admin-layout>

    <div class="flex flex-wrap my-5 -mx-2">

        <div class="w-full bg-white mt-2 mb-2 rounded-md p-4 mx-auto">

            <h1 class="text-center mb-5">Create Options</h1>

            <form action="{{ route('options.store', app()->getlocale()) }}" method="POST" class="max-w-md mx-auto"
                enctype="multipart/form-data">

                @csrf

                <!-- category_id -->

                <div class="rounded  w-full mx-auto mt-4">

                    <!-- Tabs -->



                    <div class="language-selector">

                        <ul id="tabs" class="language-selector-list">

                            @foreach (config('app.locales') as $locale)
                                @if ($locale === 'en')
                                    <li class="language-selector-item border-cyan-500">
                                    @elseif($locale === 'ka')
                                    <li class="language-selector-item border-red-600">
                                @endif

                                <a href="#locale-{{ $locale }}" class="language-selector-link">

                                    <!-- You can use small icons here for each language -->

                                    <img src="{{ $locale === 'en' ? asset('storage/flags/united-states.png') : asset('storage/flags/georgia.png') }}"
                                        alt="{{ $locale }}" class="language-icon">

                                    <span class="language-name">{{ __('admin.locale_' . $locale) }}</span>

                                </a>

                                </li>
                            @endforeach

                        </ul>



                    </div>



                </div>

                <div id="tab-contents">

                    @foreach (config('app.locales') as $locale)
                        <div id="locale-{{ $locale }}"
                            class=" @if ($locale !== app()->getLocale()) hidden @endif p-4">

                            <div class="flex flex-col w-full items-center justify-center mb-2">

                                <label for="title_{{ $locale }}" class="text-sm font-medium"><span
                                        class="text-red-500">*</span>Title (
                                    {{ __('admin.locale_' . $locale) }})</label>

                                <input type="text" name="{{ $locale }}[title]" id="title_{{ $locale }}" value="{{ $option->translate(app()->getlocale())->title }}"
                                    class="border w-full text-sm rounded-lg block p-2.5 @error('title_' . $locale) border-red-500 @enderror"
                                    placeholder="Title">

                                @error('title_' . $locale)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror

                            </div>



                            <div class="flex w-fulls items-center justify-center flex-col mb-2">

                                <label for="type_{{ $locale }}" class="text-sm font-medium"><span
                                        class="text-red-500">*</span>Type

                                    ({{ __('admin.locale_' . $locale) }})
                                </label>

                                <input type="text" name="{{ $locale }}[type]" id="type_{{ $locale }}" value="{{ old($locale . '.type', $option->translate(app()->getLocale())->type) }}"
                                class="border w-full text-sm rounded-lg block p-2.5 @error('type_' . $locale) border-red-500 @enderror"
                                placeholder="Type">
                         

                            </div>


                        </div>
                    @endforeach

                </div>



                <!-- sort -->

                <div class="flex s justify-center items-center flex-col mb-2">

                    <label for="type"
                        class="text-sm font-medium text-gray-900 dark:text-gray-400">{{ trans('admin.type') }}</label>

                    <select id="type" name="type_id"
                        class="border w-full border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-2">

                        <option value="">{{ trans('admin.page_type') }}</option>

                        @foreach (sectionTypes() as $key => $type)
                            <option value="{{ $type['id'] }}">{{ trans('sectionTypes.' . $key) }}</option>
                        @endforeach

                    </select>

                </div>

                <!-- images -->

                <div class="mt-2 border border-dashed border-gray-400 rounded-lg p-4">

                    <div class="flex flex-wrap imagePreview">

                        @foreach($option->images as $image)

                        <div class="w-1/2 md:w-1/4 lg:w-1/3 p-2">

                            <div class="trash">

                                <img src="{{ asset('storage/app/options/' . $image->image_name) }}" 

                                alt="Product Image" class="w-full h-full mr-2 object-cover">

                                {{-- Delete Button --}}

                                <button type="button" 

                                data-route="{{ route('products.images.delete', [app()->getlocale() , $image->id]) }}"

                                data-id="{{ $image->id }}" 

                                data-token="{{ csrf_token() }}" 

                                class="text-red-500 hover:text-red-700 focus:outline-none focus:text-red-700 delete-image" 

                                id="delete_image_{{ $image->id }}">

                            <i class="fas fa-trash"></i>

                        </button>

                            </div>

                        </div>

                        @endforeach

                    </div>

                </div>


                <div class="flex justify-between">

                    <div class="mb-4">

                        <button type="submit"
                            class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600">Create Options</button>

                    </div>

                    <div>

                        <a href="/{{ app()->getlocale() }}/admin/options"
                            class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600">Back</a>

                    </div>

                </div>



            </form>

        </div>

    </div>


</x-admin.admin-layout>
