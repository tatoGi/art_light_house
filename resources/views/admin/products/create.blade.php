<x-admin.admin-layout>

    <div class="flex flex-wrap my-5 -mx-2 bg-slate-100 rounded-md">

        <div class="container mx-auto">

            <div class="card-box ">

                <form
                    action="{{ isset($page) ? route('admin.pages.products.store', ['locale' => app()->getLocale(), 'page' => $page->id]) : route('products.store', app()->getlocale()) }}"
                    method="POST" enctype="multipart/form-data" data-parsley-validate novalidate>
                    @csrf
                    @if(isset($page))
                        <input type="hidden" name="page_id" value="{{ $page->id }}">
                    @endif
                    <div class="w-1/2 relative bg-white mt-2 mb-2 rounded-md p-4 mx-auto">

                        <!-- Category Selection -->
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('admin.Category') }}
                                <span class="text-gray-400 text-xs">({{ __('admin.Optional') }})</span>
                            </label>
                            <select name="category_id" id="category_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('admin.Select Category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->title ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div class="mb-4">
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('admin.Sort Order') }}
                            </label>
                            <input type="number" name="sort_order" id="sort_order" min="0" step="1"
                                   value="{{ old('sort_order', 0) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            @error('sort_order')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="rounded absolute   w-full mx-auto mt-4">

                            <!-- Tabs -->

                            <div class="language-selector">

                                <ul id="tabs" class="language-selector-list">

                                    @foreach (config('app.locales') as $locale)
                                        @if ($locale === 'en')
                                            <li
                                                class="language-selector-item mb-2   rounded-md bg-cyan-500 border-cyan-500">
                                            @elseif($locale === 'ka')
                                            <li class="language-selector-item bg-red-500   rounded-md border-red-600">
                                        @endif

                                        <a href="#locale-{{ $locale }}" class="language-selector-link">

                            

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

                                        <input type="text" name="{{ $locale }}[title]"
                                            id="title_{{ $locale }}"
                                            class="border w-full text-sm rounded-lg block p-2.5 @error('title') border-red-500 @enderror"
                                            placeholder="Title">

                                        @error('title')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror


                                    </div>
   <div class="flex flex-col w-full items-center justify-center mb-2">

                                        <label for="slug_{{ $locale }}" class="text-sm font-medium"><span
                                                class="text-red-500">*</span>SLUG (
                                            {{ __('admin.locale_' . $locale) }})</label>

                                        <input type="text" name="{{ $locale }}[slug]"
                                            id="slug_{{ $locale }}"
                                            class="border w-full text-sm rounded-lg block p-2.5 @error('slug') border-red-500 @enderror"
                                            placeholder="Slug">

                                        @error('slug')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror

                                    </div>


                                    <div class="flex w-full items-center justify-center flex-col mb-2">
                                        <label for="description_{{ $locale }}"
                                            class="text-sm font-medium text-gray-900 dark:text-gray-900">
                                            Description ({{ __('admin.locale_' . $locale) }})
                                        </label>
                                        <div id="editor" class="editor">
                                            <textarea id="description_{{ $locale }}" name="{{ $locale }}[description]"
                                                class="border w-full ckeditor text-sm text-gray-900 rounded-lg border-gray-300 focus:ring-blue-500
                                                        focus:border-blue-500 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black
                                                        dark:focus:ring-blue-500 dark:focus:border-blue-500 p-2.5 @error('description') border-red @enderror"
                                                placeholder="Description">{{ old($locale . '.description') }}</textarea>
                                            @error('description')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            @endforeach

                        </div>

                        

                        <!-- Price -->
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-4">
                            <div>
                                <label for="price" class="block font-medium text-gray-700">
                                    <span class="text-red-500">*</span>Price
                                </label>
                                <input type="text" name="price" id="price"
                                    class="border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error('price') border-red-500 @enderror"
                                    value="{{ old('price') }}" placeholder="e.g., 99.99">
                                @error('price')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- On Sale Toggle -->
                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="on_sale" id="on_sale" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('on_sale') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">On Sale</span>
                            </label>
                        </div>

                        <!-- Sale Price (shown when On Sale) -->
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-4" id="sale_price_group" style="{{ old('on_sale') ? '' : 'display:none;' }}">
                            <div>
                                <label for="sale_price" class="block font-medium text-gray-700">
                                    Sale Price
                                </label>
                                <input type="text" name="sale_price" id="sale_price"
                                    class="border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error('sale_price') border-red-500 @enderror"
                                    value="{{ old('sale_price') }}" placeholder="e.g., 79.99">
                                @error('sale_price')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>




                        <div class="mb-4">
                            <label for="active" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.Status') }}</label>
                            <select name="active" id="active" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="1" {{ old('active', 1) == '1' ? 'selected' : '' }}>{{ __('admin.Active') }}</option>
                                <option value="0" {{ old('active', 1) == '0' ? 'selected' : '' }}>{{ __('admin.Inactive') }}</option>
                            </select>
                        </div>

                        <div class="mb-4">

                            <label for="images" class="block font-medium text-gray-700">Images</label>

                            <input type="file" name="images[]" id="images" multiple
                                class="border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2">

                            @error('images')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                        </div>

                        @if ($errors->any())

                            <div class="mb-4">

                                <ul class="list-disc list-inside text-red-500">

                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>

                            </div>

                        @endif

                        <div class="flex justify-between">

                            <div class="mb-4">

                                <button type="submit"
                                    class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600">Create
                                    Product</button>

                            </div>

                            <div>

                                <a href="/{{ app()->getlocale() }}/admin/products"
                                    class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600">Back</a>

                            </div>

                        </div>



                    </div>



                </form>

            </div>

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

            // Toggle sale price visibility
            function toggleSalePrice() {
                if ($('#on_sale').is(':checked')) {
                    $('#sale_price_group').show();
                } else {
                    $('#sale_price_group').hide();
                }
            }
            $('#on_sale').on('change', toggleSalePrice);
            toggleSalePrice();
        });
    </script>

</x-admin.admin-layout>
