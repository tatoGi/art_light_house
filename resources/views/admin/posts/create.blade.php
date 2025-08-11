<x-admin.admin-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ __('admin.Create') }} {{ $pageTypeConfig['name'] ?? 'Post' }}
                </h1>
                <p class="text-gray-600 mt-1">{{ $page->title }}</p>
            </div>
            <a href="{{ route('admin.pages.posts.index', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('admin.Back') }}
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pages.posts.store', ['locale' => app()->getLocale(), 'page' => $page->id]) }}" 
              method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.Basic Information') }}</h3>
                
                
                {{-- Category Selection for Blog Posts --}}
                @if($page->type_id == 2)
                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-folder mr-2"></i>{{ __('admin.Category') }}
                    </label>
                    <select name="category_id" id="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">{{ __('admin.Select Category') }} ({{ __('admin.Optional') }})</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ __('admin.Select a category to organize your blog post') }}
                    </p>
                </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.Status') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">{{ __('admin.Select Status') }}</option>
                            @if(isset($nonTranslatableAttributes['status']['options']))
                                @foreach($nonTranslatableAttributes['status']['options'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $nonTranslatableAttributes['status']['default'] ?? '') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            @else
                                {{-- Fallback for backward compatibility --}}
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>{{ __('admin.Active') }}</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.Inactive') }}</option>
                            @endif
                        </select>
                    </div>
                    
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.Sort Order') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 1) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.Publish Date') }}
                        </label>
                        <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>

            {{-- Translatable Content --}}
            @if(!empty($translatableAttributes))
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.Content') }}</h3>
                
                <!-- Language Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        @foreach(config('app.locales') as $locale)
                            <button type="button" 
                                    class="language-tab py-3 px-4 border-b-2 font-medium text-sm cursor-pointer transition-colors duration-200 {{ $loop->first ? 'border-green-500 text-green-600 bg-green-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}"
                                    data-locale="{{ $locale }}"
                                    onclick="switchLanguageTab('{{ $locale }}')">
                                {{ __('admin.locale_' . $locale) }}
                               
                               
                            </button>
                        @endforeach
                    </nav>
                </div>

                @foreach(config('app.locales') as $locale)
                    <div class="language-content {{ !$loop->first ? 'hidden' : '' }}" data-locale="{{ $locale }}">
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($translatableAttributes as $key => $config)
                                <div class="field-group">
                                    <label for="{{ $locale }}_{{ $key }}" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $config['label'] ?? ucfirst($key) }}
                                        @if($config['required'] ?? false) <span class="text-red-500">*</span> @endif
                                    </label>
                                    
                                    @if($config['type'] === 'text')
                                        <input type="text" 
                                               name="{{ $locale }}[{{ $key }}]" 
                                               id="{{ $locale }}_{{ $key }}"
                                               value="{{ old($locale . '.' . $key) }}"
                                               placeholder="{{ $config['placeholder'] ?? '' }}"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    
                                    @elseif($config['type'] === 'textarea')
                                        <textarea name="{{ $locale }}[{{ $key }}]" 
                                                  id="{{ $locale }}_{{ $key }}"
                                                  rows="{{ $config['rows'] ?? 3 }}"
                                                  placeholder="{{ $config['placeholder'] ?? '' }}"
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old($locale . '.' . $key) }}</textarea>
                                    
                                    @elseif($config['type'] === 'editor')
                                        <textarea name="{{ $locale }}[{{ $key }}]" 
                                                  id="{{ $locale }}_{{ $key }}"
                                                  class="editor w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                  rows="6">{{ old($locale . '.' . $key) }}</textarea>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- Additional Information --}}
            @if(!empty($nonTranslatableAttributes))
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.Additional Information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($nonTranslatableAttributes as $key => $config)
                        @php $skipKeys = ['status', 'sort_order']; @endphp
                        @if(in_array($key, $skipKeys))
                            @continue
                        @endif
                        <div class="field-group {{ in_array($config['type'], ['textarea', 'editor']) ? 'md:col-span-2' : '' }}">
                            <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $config['label'] ?? ucfirst($key) }}
                                @if($config['required'] ?? false) <span class="text-red-500">*</span> @endif
                            </label>
                            
                            @if($config['type'] === 'text')
                                <input type="text" name="{{ $key }}" id="{{ $key }}"
                                       value="{{ old($key) }}"
                                       placeholder="{{ $config['placeholder'] ?? '' }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            
                            @elseif($config['type'] === 'textarea')
                                <textarea name="{{ $key }}" id="{{ $key }}"
                                          rows="{{ $config['rows'] ?? 3 }}"
                                          placeholder="{{ $config['placeholder'] ?? '' }}"
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old($key) }}</textarea>
                            
                            @elseif($config['type'] === 'editor')
                                <textarea name="{{ $key }}" id="{{ $key }}"
                                          class="editor w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                          rows="6">{{ old($key) }}</textarea>
                            
                            @elseif($config['type'] === 'select')
                                <select name="{{ $key }}" id="{{ $key }}"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">{{ __('admin.Select') }} {{ $config['label'] ?? ucfirst($key) }}</option>
                                    @if(isset($config['options']))
                                        @foreach($config['options'] as $value => $label)
                                            <option value="{{ $value }}" {{ old($key) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            
                            @elseif($config['type'] === 'image')
                                <input type="file" name="{{ $key }}" id="{{ $key }}"
                                       accept="image/*"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <p class="text-sm text-gray-500 mt-1">{{ __('admin.Supported formats: JPG, PNG, GIF, SVG, WEBP') }}</p>
                            
                            @elseif($config['type'] === 'number')
                                <input type="number" name="{{ $key }}" id="{{ $key }}"
                                       value="{{ old($key) }}"
                                       placeholder="{{ $config['placeholder'] ?? '' }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            
                            @elseif($config['type'] === 'datetime-local')
                                <input type="datetime-local" name="{{ $key }}" id="{{ $key }}"
                                       value="{{ old($key) }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.pages.posts.index', ['locale' => app()->getLocale(), 'page' => $page->id]) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                    {{ __('admin.Cancel') }}
                </a>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('admin.Create Post') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize language tabs
            const languageTabs = document.querySelectorAll('.language-tab');
            if (languageTabs.length > 0) {
                // Initialize first tab as active if none is active
                const activeTabs = document.querySelectorAll('.language-tab.border-green-500');
                if (activeTabs.length === 0 && languageTabs.length > 0) {
                    const firstTab = languageTabs[0];
                    const locale = firstTab.getAttribute('data-locale');
                    if (locale) {
                        switchLanguageTab(locale);
                    }
                }
            }
        });
        
        function switchLanguageTab(locale) {
            console.log('Switching to locale:', locale);
            
            // Find all section fields and check their visibility
            const allSections = document.querySelectorAll('.section-fields');
            console.log('All sections found:', allSections.length);
            
            const visibleSections = [];
            allSections.forEach(section => {
                const computedStyle = window.getComputedStyle(section);
                const isVisible = computedStyle.display !== 'none' && section.style.display !== 'none';
                console.log('Section', section.id, 'display style:', section.style.display, 'computed:', computedStyle.display, 'visible:', isVisible);
                if (isVisible) {
                    visibleSections.push(section);
                }
            });
            
            if (visibleSections.length === 0) {
                console.log('No visible sections found - trying to find any language tabs');
                // Fallback: find any language tabs in the document
                const allTabs = document.querySelectorAll('.language-tab');
                const allContents = document.querySelectorAll('.language-content');
                
                console.log('Found fallback tabs:', allTabs.length, 'contents:', allContents.length);
                
                if (allTabs.length > 0) {
                    // Reset all tabs
                    allTabs.forEach(tab => {
                        tab.classList.remove('border-green-500', 'text-green-600', 'bg-green-50',
                                           'border-blue-500', 'text-blue-600', 'bg-blue-50',
                                           'border-purple-500', 'text-purple-600', 'bg-purple-50');
                        tab.classList.add('border-transparent', 'text-gray-500');
                    });
                    
                    // Activate clicked tabs
                    const activeTabsForLocale = document.querySelectorAll(`[data-locale="${locale}"].language-tab`);
                    activeTabsForLocale.forEach(tab => {
                        tab.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
                        tab.classList.remove('border-transparent', 'text-gray-500');
                    });
                    
                    // Hide all content
                    allContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Show target content
                    const targetContents = document.querySelectorAll(`[data-locale="${locale}"].language-content`);
                    targetContents.forEach(content => {
                        content.classList.remove('hidden');
                    });
                    
                    console.log('Used fallback method for locale:', locale);
                }
                return;
            }
            
            // Process each visible section
            visibleSections.forEach(section => {
                const sectionId = section.id;
                console.log('Processing section:', sectionId);
                
                // Determine color scheme based on section
                let activeColor, activeBg, activeBorder;
                if (sectionId === 'join_us_fields') {
                    activeColor = 'text-blue-600';
                    activeBg = 'bg-blue-50';
                    activeBorder = 'border-blue-500';
                } else if (sectionId === 'rental_steps_fields') {
                    activeColor = 'text-purple-600';
                    activeBg = 'bg-purple-50';
                    activeBorder = 'border-purple-500';
                } else {
                    activeColor = 'text-green-600';
                    activeBg = 'bg-green-50';
                    activeBorder = 'border-green-500';
                }
                
                // Update tab styles in this section
                const tabs = section.querySelectorAll('.language-tab');
                console.log('Found tabs in section ' + sectionId + ':', tabs.length);
                
                tabs.forEach(tab => {
                    // Remove all color classes
                    tab.classList.remove('border-green-500', 'text-green-600', 'bg-green-50',
                                       'border-blue-500', 'text-blue-600', 'bg-blue-50',
                                       'border-purple-500', 'text-purple-600', 'bg-purple-50');
                    tab.classList.add('border-transparent', 'text-gray-500');
                });
                
                // Activate clicked tab in this section
                const activeTab = section.querySelector(`[data-locale="${locale}"].language-tab`);
                if (activeTab) {
                    activeTab.classList.add(activeBorder, activeColor, activeBg);
                    activeTab.classList.remove('border-transparent', 'text-gray-500');
                    console.log('Activated tab for locale:', locale, 'in section:', sectionId);
                }
                
                // Show/hide content in this section
                const contents = section.querySelectorAll('.language-content');
                contents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                const targetContent = section.querySelector(`[data-locale="${locale}"].language-content`);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                    console.log('Content switched to:', locale, 'in section:', sectionId);
                } else {
                    console.log('Target content not found for locale:', locale, 'in section:', sectionId);
                }
            });
        }
        

    </script>
</x-admin.admin-layout>
