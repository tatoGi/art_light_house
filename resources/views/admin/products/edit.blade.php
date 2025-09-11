<x-admin.admin-layout>
    <div class="max-w-4xl mx-auto py-6 px-4">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-900">Edit Product</h1>
            </div>

            <form action="{{ route('products.update', [app()->getlocale(), $product->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @if(request()->has('page_id'))
                    <input type="hidden" name="page_id" value="{{ request()->page_id }}">
                @endif

                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
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
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                   value="{{ old('sort_order', $product->sort_order ?? 0) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            @error('sort_order')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Product Identify ID -->
                        <div class="mb-4">
                            <label for="product_identify_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Product Identify ID <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="product_identify_id" id="product_identify_id"
                                value="{{ old('product_identify_id', $product->product_identify_id) }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('product_identify_id') border-red-500 @enderror"
                                placeholder="e.g., PROD-ABC123">
                            @error('product_identify_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Price, Sort Order, and Status -->
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-4">
                            <div>
                                <label for="price" class="block font-medium text-gray-700">
                                    <span class="text-red-500">*</span>Price
                                </label>
                                <input type="text" name="price" id="price"
                                    class="border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error('price') border-red-500 @enderror"
                                    value="{{ old('price', $product->price) }}" placeholder="e.g., 99.99">
                                @error('price')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- On Sale Toggle -->
                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="on_sale" id="on_sale" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('on_sale', $product->on_sale ?? 0) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">On Sale</span>
                            </label>
                        </div>

                        <!-- Sale Price (shown when On Sale) -->
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-4" id="sale_price_group" style="{{ old('on_sale', $product->on_sale ?? 0) ? '' : 'display:none;' }}">
                            <div>
                                <label for="sale_price" class="block font-medium text-gray-700">
                                    Sale Price
                                </label>
                                <input type="text" name="sale_price" id="sale_price"
                                    class="border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border rounded-md shadow-sm p-2 @error('sale_price') border-red-500 @enderror"
                                    value="{{ old('sale_price', $product->sale_price ?? '') }}" placeholder="e.g., 79.99">
                                @error('sale_price')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="active" id="active" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="1" {{ old('active', $product->active) == '1' ? 'selected' : '' }}>{{ __('admin.Active') }}</option>
                                <option value="0" {{ old('active', $product->active) == '0' ? 'selected' : '' }}>{{ __('admin.Inactive') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Translatable Content -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.Content') }}</h3>
                        
                        <!-- Language Tabs -->
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="-mb-px flex space-x-8">
                                @foreach(config('app.locales') as $locale)
                                    <a href="#" 
                                       class="language-tab py-2 px-1 border-b-2 font-medium text-sm {{ $loop->first ? 'border-green-500 text-green-600 bg-green-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                       data-locale="{{ $locale }}"
                                       onclick="event.preventDefault();">
                                        {{ __('admin.locale_' . $locale) }}
                                        <img src="{{ $locale === 'en' ? asset('storage/flags/united-states.png') : asset('storage/flags/georgia.png') }}" 
                                             alt="{{ $locale }}" class="inline w-4 h-4 ml-1">
                                    </a>
                                @endforeach
                            </nav>
                        </div>

                        @foreach(config('app.locales') as $locale)
                            <div class="language-content {{ !$loop->first ? 'hidden' : '' }}" data-locale="{{ $locale }}">
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Title -->
                                    <div>
                                        <label for="{{ $locale }}_title" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('admin.Title') }} ({{ __('admin.locale_' . $locale) }})
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="{{ $locale }}[title]" 
                                               id="{{ $locale }}_title"
                                               value="{{ old($locale . '.title', $product->translate($locale)->title ?? '') }}"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error($locale . '.title') border-red-500 @enderror"
                                               placeholder="{{ __('admin.Title') }}">
                                        @error($locale . '.title')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="{{ $locale }}_slug" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('admin.Slug') }} ({{ __('admin.locale_' . $locale) }})
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="{{ $locale }}[slug]" 
                                               id="{{ $locale }}_slug"
                                               value="{{ old($locale . '.slug', $product->translate($locale)->slug ?? '') }}"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error($locale . '.slug') border-red-500 @enderror"
                                               placeholder="{{ __('admin.Slug') }}">
                                        @error($locale . '.slug')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- Description -->
                                    <div>
                                        <label for="{{ $locale }}_description" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('admin.Description') }} ({{ __('admin.locale_' . $locale) }})
                                        </label>
                                        <textarea name="{{ $locale }}[description]" 
                                                  id="{{ $locale }}_description"
                                                  rows="6"
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error($locale . '.description') border-red-500 @enderror"
                                                  placeholder="{{ __('admin.Description') }}">{{ old($locale . '.description', $product->translate($locale)->description ?? '') }}</textarea>
                                        @error($locale . '.description')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Product Images -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Images</h3>
                        
                        <!-- Current Images -->
                        @if($product->images->count() > 0)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Current Images</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($product->images as $image)
                                   
                                        <div class="relative group">
                                            <img src="{{ asset('storage/products/' . $image->image_name) }}" 
                                                 alt="Product Image" 
                                                 class="w-full h-32 object-cover rounded-lg border">
                                            <button type="button"
                                                    data-route="{{ route('products.images.delete', [app()->getlocale(), $image->id]) }}"
                                                    data-id="{{ $image->id }}" 
                                                    data-token="{{ csrf_token() }}"
                                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity delete-image">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Upload New Images -->
                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Add New Images</label>
                            <input type="file" 
                                   name="images[]" 
                                   id="images" 
                                   multiple
                                   accept="image/*"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('images')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <a href="{{ route('products.index', app()->getlocale()) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                            {{ __('admin.Back') }}
                        </a>
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition-colors">
                            {{ __('admin.Update Product') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Language Tabs and Image Deletion -->
    <script>
        // Language tab switching
        function switchLanguageTab(locale) {
            // Remove active classes from all tabs
            document.querySelectorAll('.language-tab').forEach(tab => {
                tab.classList.remove('border-green-500', 'text-green-600', 'bg-green-50');
                tab.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Add active classes to selected tab
            document.querySelectorAll(`.language-tab[data-locale="${locale}"]`).forEach(tab => {
                tab.classList.add('border-green-500', 'text-green-600', 'bg-green-50');
                tab.classList.remove('border-transparent', 'text-gray-500');
            });
            
            // Hide all content sections
            document.querySelectorAll('.language-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show selected content section
            document.querySelectorAll(`.language-content[data-locale="${locale}"]`).forEach(content => {
                content.classList.remove('hidden');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Language tab switching
            document.querySelectorAll('.language-tab').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    switchLanguageTab(this.dataset.locale);
                });
            });
            
            // Initialize first language tab as active
            const firstTab = document.querySelector('.language-tab');
            if (firstTab) {
                switchLanguageTab(firstTab.dataset.locale);
            }

            // Image deletion
            document.querySelectorAll('.delete-image').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this image?')) {
                        const route = this.dataset.route;
                        const token = this.dataset.token;
                        
                        fetch(route, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.closest('.relative').remove();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error deleting image');
                        });
                    }
                });
            });
        });
    </script>

            </div>

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
