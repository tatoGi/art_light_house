<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SeoHelper;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function analyze(Request $request)
    {
       
        $content = (string) $request->input('content', '');
        $topic = (string) $request->input('topic', '');
        
        try {
            $analysis = SeoHelper::analyzeContent($content);
        } catch (\Throwable $e) {
            $analysis = 'SEO analysis temporarily unavailable.';
        }

        $subject = $topic ?: 'General';
        $meta = SeoHelper::generateMetaTags($subject);
        $keywords = SeoHelper::suggestKeywords($subject);

        return response()->json([
            'analysis' => $analysis,
            'meta' => $meta,
            'keywords' => $keywords,
        ]);
    }

    public function write(Request $request)
    {
        $topic = (string) $request->input('topic', '');
        $keywords = (array) $request->input('keywords', []);
        $tone = (string) $request->input('tone', 'informative');
        $wordCount = (int) $request->input('word_count', 600);
        $locale = (string) $request->input('locale', app()->getLocale());
        $withOutline = (bool) $request->boolean('outline', true);

        $draft = SeoHelper::writeDraft($topic, $keywords, $tone, $wordCount, $locale, $withOutline);
        return response()->json($draft);
    }

    public function keywords(Request $request)
    {
        $topic = (string) $request->input('topic', '');
        $locale = (string) $request->input('locale', app()->getLocale());
        return response()->json([
            'keywords' => SeoHelper::expandKeywords($topic, $locale),
        ]);
    }

    public function auto(Request $request)
    {
        $type = (string) $request->input('page_type', '');
        $locale = (string) $request->input('locale', app()->getLocale());
        // Basic data extraction from request; front-end can pass extra keys as needed
        $data = [
            'brand' => (string) $request->input('brand', config('app.name')),
            'title' => (string) $request->input('title', ''),
            'summary' => (string) $request->input('summary', ''),
            'tagline' => (string) $request->input('tagline', ''),
            'focus' => (string) $request->input('focus', ''),
            'category' => (string) $request->input('category', ''),
            'product' => (string) $request->input('product', ''),
        ];

        if ($type === '') {
            return response()->json(['message' => 'Missing page_type'], 422);
        }

        $result = SeoHelper::generateForType($type, $data, $locale);
        return response()->json($result);
    }
}
