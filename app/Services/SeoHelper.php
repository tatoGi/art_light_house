<?php

namespace App\Services;

class SeoHelper
{
    public static function analyzeContent(string $content): string
    {
        $content = trim($content);
        if ($content === '') {
            return 'No content provided. Add a clear keyword-rich title, a 150–160 char meta description, and 5–8 relevant keywords.';
        }

        $length = mb_strlen($content);
        $hasHeading = preg_match('/\b(h1|h2|title)\b/i', $content) ? 'Yes' : 'No';
        $hasLinks = preg_match('/https?:\/\//i', $content) ? 'Yes' : 'No';

        return "Heuristic SEO analysis (offline):\n"
            . "- Content length: {$length} chars (aim for > 300 words).\n"
            . "- Headings present: {$hasHeading}.\n"
            . "- Links detected: {$hasLinks} (add internal links where useful).\n"
            . "- Recommendations: use a primary keyword in the title and first paragraph; add H2/H3 with variations; write a 150–160 char meta description; add image alt text; include 1–3 internal links and 1–2 external authoritative links.";
    }

    public static function generateMetaTags(string $topic): array
    {
        $topic = trim($topic) !== '' ? $topic : 'Content';
        return [
            'title' => "Best $topic Guide | 2025 SEO Optimized",
            'description' => "Learn all about $topic in this comprehensive guide. Updated for 2025 with SEO best practices."
        ];
    }

    public static function suggestKeywords(string $topic): array
    {
        $topic = trim($topic) !== '' ? $topic : 'content';
        return [
            "$topic tips",
            "$topic best practices",
            "how to improve $topic",
            "$topic SEO",
        ];
    }

    public static function suggestSlug(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9\s-]/i', '', $slug);
        $slug = preg_replace('/\s+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    public static function expandKeywords(string $topic, string $locale = 'en'): array
    {
        $base = self::suggestKeywords($topic);
        // Heuristic expansion: add long-tail and locale hint
        $topicLower = strtolower($topic ?: 'content');
        return array_values(array_unique(array_merge($base, [
            "$topicLower guide",
            "$topicLower checklist",
            "$topicLower examples",
            "$topicLower mistakes to avoid",
            "$topicLower 2025",
        ])));
    }

    public static function writeDraft(string $topic, array $keywords = [], string $tone = 'informative', int $wordCount = 600, string $locale = 'en', bool $withOutline = true): array
    {
        $topic = trim($topic) !== '' ? $topic : 'Content';
        $keywords = array_values(array_filter(array_map('trim', $keywords)));
        $kwStr = implode(', ', $keywords);
        $title = "{$topic}: A Practical {$tone} Guide";
        $metaTitle = $title;
        $metaDesc = substr("{$topic} — {$tone} guide covering key concepts, best practices, and tips. Keywords: {$kwStr}", 0, 155);
        $slug = self::suggestSlug($title);

        $outline = [
            'Introduction',
            'What is ' . $topic . '?',
            'Benefits and Key Concepts',
            'Best Practices',
            'Common Mistakes',
            'Checklist and Next Steps',
            'Conclusion',
        ];

        $para = function (string $text) { return '<p>' . e($text) . '</p>'; };
        $body = [];
        $body[] = '<h2>Introduction</h2>' . $para("This {$tone} guide to {$topic} provides an overview, best practices, and practical tips.");
        $body[] = '<h2>What is ' . e($topic) . '?</h2>' . $para("Define the topic, its context, and why it matters.");
        $body[] = '<h2>Benefits and Key Concepts</h2>' . $para("Summarize key benefits, metrics, and core ideas users should know.");
        $body[] = '<h2>Best Practices</h2>' . $para("List proven tactics and guidelines. Include examples if possible.");
        $body[] = '<h2>Common Mistakes</h2>' . $para("Highlight pitfalls to avoid and how to mitigate them.");
        $body[] = '<h2>Checklist and Next Steps</h2>' . $para("Provide a short actionable checklist and suggested next steps.");
        $body[] = '<h2>Conclusion</h2>' . $para("Reinforce key takeaways and prompt a call to action.");

        // Roughly scale paragraphs to target word count
        if ($wordCount > 800) {
            $body[] = $para("Additional details: add case studies, data points, or FAQs to reach the target length.");
        }

        return [
            'title' => $title,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDesc,
            'slug' => $slug,
            'outline' => $withOutline ? $outline : [],
            'body' => implode("\n", $body),
            'keywords' => $keywords ?: self::expandKeywords($topic, $locale),
        ];
    }

    /**
     * Apply a simple token template: replaces {var} by value from $vars
     */
    public static function applyTemplate(string $pattern, array $vars): string
    {
        $out = $pattern;
        foreach ($vars as $k => $v) {
            $out = str_replace('{'.$k.'}', (string) $v, $out);
        }
        return trim($out);
    }

    /**
     * Generate meta for a page type using config/seo_types.php
     */
    public static function generateForType(string $type, array $data, string $locale = 'en'): array
    {
        $types = config('seo_types.types', []);
        $tpl = $types[$type] ?? null;
        $brand = $data['brand'] ?? ($data['site_name'] ?? 'Website');
        $vars = array_merge([
            'brand' => $brand,
            'tagline' => $data['tagline'] ?? ($data['summary'] ?? ''),
            'focus' => $data['focus'] ?? ($data['category'] ?? ''),
            'category' => $data['category'] ?? '',
            'product' => $data['product'] ?? '',
            'title' => $data['title'] ?? '',
            'summary' => $data['summary'] ?? '',
        ], $data);

        if ($tpl) {
            $metaTitle = self::applyTemplate($tpl['title'] ?? '{title} | '.$brand, $vars);
            $metaDesc = self::applyTemplate($tpl['meta_description'] ?? ($vars['summary'] ?? ''), $vars);
            $keywords = $tpl['default_keywords'] ?? [];
        } else {
            // Fallback generic
            $metaTitle = ($vars['title'] ?: ($vars['category'] ?: ($vars['product'] ?: ''))) . ' | ' . $brand;
            $metaDesc = $vars['summary'] ?: ($vars['tagline'] ?: '');
            $keywords = self::expandKeywords($vars['title'] ?: ($vars['category'] ?: ($vars['product'] ?: 'content')), $locale);
        }

        $slugSource = $vars['title'] ?: ($vars['category'] ?: ($vars['product'] ?: $type));
        $slug = self::suggestSlug($slugSource);

        return [
            'meta_title' => trim($metaTitle),
            'meta_description' => trim($metaDesc),
            'keywords' => $keywords,
            'slug' => $slug,
        ];
    }
}
