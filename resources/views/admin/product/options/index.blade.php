<x-Admin.AdminLayout>

    <div class="flex flex-wrap my-5 -mx-2">
        
    
        <div class="flex justify-between w-full px-2">
            <h3 class="text-xl font-bold text-center ">{{ __('admin.options') }}</h3>
            <a href="/{{ app()->getlocale() }}/products/{{ $product->id }}/options/create" class="btn  flex overflow-hidden relative w-40 bg-blue-500 text-white py-2 px-4 rounded-xl font-bold uppercase 
            before:block before:absolute before:h-full before:w-1/2 before:rounded-full
            before:bg-orange-400 before:top-0 before:left-1/4 before:transition-transform before:opacity-0 
            before:hover:opacity-100 hover:text-orange-200 hover:before:animate-ping transition-all duration-300"
            style="font-size: 0.8rem;" >
                <span class="relative">{{ __('admin.Create_option') }}</span>
                <i class="material-icons-outlined ml-2">add</i>
            </a>
        </div>
    
    
    <table class="w-full mx-auto">
    
    <!-- alert -->
    @if(Session()->has('message'))
        <div id="alert-2" class="w-3/4 mx-auto flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
    
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div class="ml-3 text-sm font-medium">
                {{ Session::get('message') }}
            </div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-2" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
    @endif
    
    <!-- end alert  -->
    <div class="w-3/4 mx-auto">
        {{-- {{ $options->links()  }} --}}
    </div>
        <thead>
            <tr>
                <th class="px-4 py-2">{{ __('admin.ID') }}</th>
                <th class="px-4 py-2">{{ __('admin.Title') }}</th>
                <th class="px-4 py-2">{{ __('admin.Type') }}</th>
                <th class="px-4 py-2">{{ __('admin.Actions') }}</th>
            </tr>
        </thead>
    
        <tbody>
            @if($options)
            @foreach($options as $key => $option)
                <tr class="text-center">
                    <td class="border px-4 py-2">{{ $option->id }}</td>
                    <td class="border px-4 py-2">{{ $option->title }}</td>
                    <td class="border px-4 py-2">{{ $option->type }}</td>
                    <td class="border px-4 py-2">
                        <button class="bg-blue-500 text-white py-1 px-2 rounded">
                            <a href="/{{ app()->getLocale() }}/admin/options/{{ $option->id }}/edit" class="text-white">
                                <i class="fas fa-edit"></i>{{ __('admin.Edit') }}
                            </a>
                        </button>
                        
                        <form action="{{ route('options.destroy', [app()->getlocale(), $option->id]) }}" method="post" class="inline delete" onsubmit="return confirm('Do you want to delete this option?');">
                            @csrf 
                            @method('DELETE')
                            <button class="bg-red-500 text-white py-1 px-2 rounded" type="submit">
                                <i class="fas fa-trash"></i> {{ __('admin.Delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="text-center">{{ __('No options found.') }}</td>
            </tr>
        @endif
        
       
        </tbody>
       
    </table>
    
    </div>
    </x-Admin.AdminLayout> 