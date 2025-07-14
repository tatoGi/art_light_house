

<ol class="dd-list">
  @foreach ($pages as $section)
  <li class="dd-item cursor-move border-2   border-solid rounded-2xl 
  @if (count($section->children) > 0 ) acordion @endif" data-id="{{ $section->id }}">
      <div class="dd-handle">
          {{ $section->title }}
      </div>
      <div class="change-icons">
        <a href="{{ route('pages.edit', [app()->getlocale(), $section->id]) }}" class="fas fa-pencil-alt"></a>
        <a href="{{ route('options.index', ['locale' => app()->getlocale(), 'page_id' => $section->id]) }}" class="ico fas fa-th-list"></a>
        
        <form action="{{ route('pages.destroy', [app()->getlocale(), $section->id]) }}" method="post" class="inline delete" onsubmit="return confirm('Do you want to delete this product?');">
          @csrf 
          @method('DELETE')
          <button class="!text-sm  text-center text-white p-0 rounded" type="submit">
            <i class="fas fa-trash !hover:text-red-700 !text-sm"></i>
          </button>
        </form>
      </div>
      
      @if (count($section->children) > 0 )
      @include('admin.pages.list', ['pages' => $section->children])
      @endif
  </li>
  @endforeach
</ol>


