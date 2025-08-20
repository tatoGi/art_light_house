@php
/**
 * Reusable SEO Tools panel
 * Props:
 *  - $locale (string) required
 *  - $titleFieldId (string) required (e.g., "title_en")
 *  - $descFieldId (string) required (e.g., "description_en")
 *  - $keywordsFieldId (string) required (e.g., "keywords_en")
 *  - $topic (string) optional (e.g., entity name)
 *  - $routeName (string) optional, defaults to 'admin.seo.analyze'
 */
$locale = $locale ?? app()->getLocale();
$routeName = $routeName ?? 'admin.seo.analyze';
$topic = $topic ?? '';  
@endphp

<div class="mb-4 p-4 bg-white border border-gray-200 rounded-lg">
  <div class="flex items-center justify-between mb-2">
    <h3 class="text-sm font-semibold text-gray-800">SEO Tools ({{ strtoupper($locale) }})</h3>
    <button type="button"
      class="seo-analyze-btn inline-flex items-center gap-2 bg-emerald-600 text-white py-1.5 px-3 rounded-md hover:bg-emerald-700"
      data-locale="{{ $locale }}"
      data-title="#{{ $titleFieldId }}"
      data-desc="#{{ $descFieldId }}"
      data-kwords="#{{ $keywordsFieldId }}"
      data-topic="{{ $topic }}"
      data-url="{{ route($routeName, app()->getLocale()) }}">
      Analyze SEO
    </button>
  </div>
  <div id="seo-results-{{ $locale }}" class="text-sm text-gray-700 space-y-2 hidden">
    <div>
      <span class="font-medium">Analysis:</span>
      <div id="seo-analysis-{{ $locale }}" class="mt-1 whitespace-pre-line"></div>
    </div>
    <div>
      <span class="font-medium">Suggested Title:</span>
      <div id="seo-title-{{ $locale }}" class="mt-1"></div>
    </div>
    <div>
      <span class="font-medium">Suggested Description:</span>
      <div id="seo-desc-{{ $locale }}" class="mt-1"></div>
    </div>
    <div>
      <span class="font-medium">Suggested Keywords:</span>
      <div id="seo-keywords-{{ $locale }}" class="mt-1"></div>
    </div>
    <div class="pt-2 flex items-center gap-4">
      <button type="button" class="apply-keywords text-xs text-indigo-600 hover:underline" data-locale="{{ $locale }}">Apply Keywords</button>
      <button type="button" class="apply-meta text-xs text-indigo-600 hover:underline" data-locale="{{ $locale }}">Apply Title/Meta</button>
    </div>
  </div>

  <hr class="my-4" />

  <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
    <div>
      <label class="block text-xs text-gray-600">Tone</label>
      <select class="seo-tone w-full border-gray-300 rounded" data-locale="{{ $locale }}">
        <option value="informative">Informative</option>
        <option value="persuasive">Persuasive</option>
        <option value="casual">Casual</option>
        <option value="formal">Formal</option>
      </select>
    </div>
    <div>
      <label class="block text-xs text-gray-600">Word count</label>
      <input type="number" min="200" max="2000" step="50" class="seo-wordcount w-full border-gray-300 rounded" value="600" data-locale="{{ $locale }}" />
    </div>
    <div class="md:col-span-2">
      <label class="block text-xs text-gray-600">Target keywords (comma-separated)</label>
      <input type="text" class="seo-target-keywords w-full border-gray-300 rounded" placeholder="e.g. gallery lighting, CRI 95" data-locale="{{ $locale }}" />
    </div>
  </div>

  <div class="mt-3 flex items-center gap-2">
    <button type="button"
      class="seo-generate-btn inline-flex items-center gap-2 bg-blue-600 text-white py-1.5 px-3 rounded-md hover:bg-blue-700"
      data-locale="{{ $locale }}"
      data-title="#{{ $titleFieldId }}"
      data-desc="#{{ $descFieldId }}"
      data-topic="{{ $topic }}"
      data-url="{{ route('admin.seo.write', app()->getLocale()) }}">
      Generate Draft
    </button>
    <button type="button"
      class="seo-expand-keywords inline-flex items-center gap-2 bg-gray-100 text-gray-800 py-1.5 px-3 rounded-md hover:bg-gray-200"
      data-locale="{{ $locale }}"
      data-topic="{{ $topic }}"
      data-url="{{ route('admin.seo.keywords', app()->getLocale()) }}">
      Expand Keywords
    </button>
    <label class="ml-3 inline-flex items-center gap-2 text-xs text-gray-700">
      <input type="checkbox" class="seo-with-outline" data-locale="{{ $locale }}" checked /> Include outline
    </label>
  </div>

  <div id="seo-writer-{{ $locale }}" class="mt-3 text-sm text-gray-700 space-y-2 hidden">
    <div>
      <span class="font-medium">Draft Title:</span>
      <div id="seo-draft-title-{{ $locale }}" class="mt-1"></div>
    </div>
    <div>
      <span class="font-medium">Meta Title:</span>
      <div id="seo-draft-meta-title-{{ $locale }}" class="mt-1"></div>
    </div>
    <div>
      <span class="font-medium">Meta Description:</span>
      <div id="seo-draft-meta-desc-{{ $locale }}" class="mt-1"></div>
    </div>
    <div>
      <span class="font-medium">Slug:</span>
      <div id="seo-draft-slug-{{ $locale }}" class="mt-1"></div>
    </div>
    <div class="hidden" id="seo-draft-outline-wrap-{{ $locale }}">
      <span class="font-medium">Outline:</span>
      <ul id="seo-draft-outline-{{ $locale }}" class="mt-1 list-disc list-inside"></ul>
    </div>
    <div>
      <span class="font-medium">Body:</span>
      <div id="seo-draft-body-{{ $locale }}" class="mt-1 border border-gray-200 rounded p-2 bg-gray-50"></div>
    </div>
    <div class="pt-2 flex items-center gap-4">
      <button type="button" class="apply-draft-meta text-xs text-indigo-600 hover:underline" data-locale="{{ $locale }}">Apply Title/Meta</button>
      <button type="button" class="append-draft-body text-xs text-indigo-600 hover:underline" data-locale="{{ $locale }}">Append Draft to Description</button>
    </div>
  </div>
</div>

@once
  <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
  <script>
    (function(){
      $(document).on('click', '.seo-analyze-btn', function () {
        const $btn = $(this);
        const locale = $btn.data('locale');
        const titleSel = $btn.data('title');
        const descSel = $btn.data('desc');
        const kSel = $btn.data('kwords');
        const topic = $btn.data('topic') || '';
        const url = $btn.data('url');

        const title = $(titleSel).val() || '';
        const desc = $(descSel).val() || '';
        const content = `Title: ${title}\n\nDescription: ${desc}`;

        $btn.prop('disabled', true).addClass('opacity-70');
        $.ajax({
          url: url,
          method: 'POST',
          data: { _token: `{{ csrf_token() }}`, content: content, topic: topic },
          success: function (res) {
            $btn.prop('disabled', false).removeClass('opacity-70');
            $(`#seo-results-${locale}`).removeClass('hidden');
            $(`#seo-analysis-${locale}`).text(res.analysis ?? '');
            if (res.meta) {
              $(`#seo-title-${locale}`).text(res.meta.title ?? '');
              $(`#seo-desc-${locale}`).text(res.meta.description ?? '');
            }
            if (res.keywords) {
              const kws = Array.isArray(res.keywords) ? res.keywords : [];
              $(`#seo-keywords-${locale}`).text(kws.join(', '));
              if (kSel) $(kSel).val(kws.join(', '));
            }
          },
          error: function () {
            $btn.prop('disabled', false).removeClass('opacity-70');
            alert('SEO analysis failed.');
          }
        });
      });

      $(document).on('click', '.apply-keywords', function () {
        const locale = $(this).data('locale');
        const kws = $(`#seo-keywords-${locale}`).text();
        const $triggerBtn = $(`.seo-analyze-btn[data-locale="${locale}"]`).first();
        const kSel = $triggerBtn.data('kwords');
        if (kSel && kws) $(kSel).val(kws);
      });

      $(document).on('click', '.apply-meta', function () {
        const locale = $(this).data('locale');
        const title = $(`#seo-title-${locale}`).text();
        const desc = $(`#seo-desc-${locale}`).text();
        const $triggerBtn = $(`.seo-analyze-btn[data-locale="${locale}"]`).first();
        const titleSel = $triggerBtn.data('title');
        const descSel = $triggerBtn.data('desc');
        if (titleSel && title) $(titleSel).val(title);
        if (descSel && desc) $(descSel).val(desc);
      });

      $(document).on('click', '.seo-expand-keywords', function () {
        const $btn = $(this);
        const locale = $btn.data('locale');
        const topic = $btn.data('topic') || '';
        const url = $btn.data('url');
        $btn.prop('disabled', true).addClass('opacity-70');
        $.ajax({
          url: url,
          method: 'POST',
          data: { _token: `{{ csrf_token() }}`, topic: topic, locale: locale },
          success: function (res) {
            $btn.prop('disabled', false).removeClass('opacity-70');
            const kws = Array.isArray(res.keywords) ? res.keywords : [];
            const $kwInput = $(`.seo-target-keywords[data-locale="${locale}"]`);
            $kwInput.val(kws.join(', '));
          },
          error: function(){
            $btn.prop('disabled', false).removeClass('opacity-70');
            alert('Keyword expansion failed.');
          }
        });
      });

      $(document).on('click', '.seo-generate-btn', function () {
        const $btn = $(this);
        const locale = $btn.data('locale');
        const titleSel = $btn.data('title');
        const descSel = $btn.data('desc');
        const topic = $btn.data('topic') || '';
        const url = $btn.data('url');
        const tone = $(`.seo-tone[data-locale="${locale}"]`).val() || 'informative';
        const wc = parseInt($(`.seo-wordcount[data-locale="${locale}"]`).val() || '600', 10);
        const kwStr = $(`.seo-target-keywords[data-locale="${locale}"]`).val() || '';
        const withOutline = $(`.seo-with-outline[data-locale="${locale}"]`).is(':checked');
        const keywords = kwStr.split(',').map(s => s.trim()).filter(Boolean);

        $btn.prop('disabled', true).addClass('opacity-70');
        $.ajax({
          url: url,
          method: 'POST',
          data: { _token: `{{ csrf_token() }}`, topic: topic, keywords: keywords, tone: tone, word_count: wc, locale: locale, outline: withOutline },
          success: function (res) {
            $btn.prop('disabled', false).removeClass('opacity-70');
            const wrapSel = `#seo-writer-${locale}`;
            $(wrapSel).removeClass('hidden');
            $(`#seo-draft-title-${locale}`).text(res.title || '');
            $(`#seo-draft-meta-title-${locale}`).text(res.meta_title || '');
            $(`#seo-draft-meta-desc-${locale}`).text(res.meta_description || '');
            $(`#seo-draft-slug-${locale}`).text(res.slug || '');
            const outline = Array.isArray(res.outline) ? res.outline : [];
            if (outline.length) {
              $(`#seo-draft-outline-wrap-${locale}`).removeClass('hidden');
              const $ul = $(`#seo-draft-outline-${locale}`).empty();
              outline.forEach(item => $ul.append(`<li>${item}</li>`));
            } else {
              $(`#seo-draft-outline-wrap-${locale}`).addClass('hidden');
            }
            $(`#seo-draft-body-${locale}`).html(res.body || '');
          },
          error: function(){
            $btn.prop('disabled', false).removeClass('opacity-70');
            alert('Draft generation failed.');
          }
        });
      });

      $(document).on('click', '.apply-draft-meta', function(){
        const locale = $(this).data('locale');
        const title = $(`#seo-draft-meta-title-${locale}`).text() || $(`#seo-draft-title-${locale}`).text();
        const desc = $(`#seo-draft-meta-desc-${locale}`).text();
        const $triggerBtn = $(`.seo-analyze-btn[data-locale="${locale}"]`).first();
        const titleSel = $triggerBtn.data('title');
        const descSel = $triggerBtn.data('desc');
        if (titleSel && title) $(titleSel).val(title);
        if (descSel && desc) $(descSel).val(desc);
      });

      $(document).on('click', '.append-draft-body', function(){
        const locale = $(this).data('locale');
        const bodyHtml = $(`#seo-draft-body-${locale}`).html();
        const $triggerBtn = $(`.seo-analyze-btn[data-locale="${locale}"]`).first();
        const descSel = $triggerBtn.data('desc');
        if (descSel && bodyHtml) {
          const current = $(descSel).val() || '';
          $(descSel).val(current + "\n\n" + bodyHtml);
        }
      });
    })();
  </script>
@endonce
