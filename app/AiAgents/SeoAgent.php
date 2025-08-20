<?php

namespace App\AiAgents;

use LarAgent\Agent;

class SeoAgent extends Agent
{
    protected $model = 'gpt-4.1-nano';

    protected $history = 'in_memory';

    protected $provider = 'default';

    public function instructions(): string
    {
        return "You are an expert SEO assistant. 
        Analyze provided web content and return actionable improvements 
        including keywords, meta tags, headings, and readability suggestions.";
    }

    public function prompt(string $content): string
    {
        return "Analyze this content for SEO:\n\n" . $content;
    }

    /**
     * Lightweight local analysis to avoid external LLM dependency.
     */
    public function analyzeContent(string $content): string
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

    public function generateMetaTags(string $topic): array
    {
        return [
            'title' => "Best $topic Guide | 2025 SEO Optimized",
            'description' => "Learn all about $topic in this comprehensive guide. Updated for 2025 with SEO best practices."
        ];
    }

    public function suggestKeywords(string $topic): array
    {
        return [
            "$topic tips",
            "$topic best practices",
            "how to improve $topic",
            "$topic SEO",
        ];
    }
}
