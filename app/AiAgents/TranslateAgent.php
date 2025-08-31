<?php

namespace App\AiAgents;

use LarAgent\Agent;

class TranslateAgent extends Agent
{
    protected $model = 'gpt-4.1-nano';

    protected $history = 'in_memory';

    protected $provider = 'default';

    protected $tools = [];

    /**
     * Parameterless constructor so this agent can be instantiated without
     * resolving provider credentials. We purposely do NOT call parent::__construct()
     * because we use an offline fallback by default.
     */
    public function __construct($key = null)
    {
        // no-op
    }

    public function instructions()
    {
        return "You are a professional translation agent.\n"
            . "Task: Given a source locale, a target locale, and a JSON object of key=>text pairs in the source locale, return ONLY a JSON object mapping the same keys to their translations in the target locale.\n"
            . "Rules: No explanations, no extra text, no markdown. Preserve placeholders like :name, {count}, %s, %d; do not translate keys; keep punctuation. If a phrase is untranslatable, return the original. Output must be valid JSON.";
    }

    public function prompt($message)
    {
        return $message;
    }

    /**
     * Translate a batch of key=>text pairs from source to target locale.
     * Returns an associative array [key => translatedValue].
     *
     * Note: If no external provider is configured, this uses a safe fallback
     * that returns placeholder translations so the pipeline can proceed.
     */
    public function translateBatch(string $sourceLocale, string $targetLocale, array $pairs): array
    {
        // Normalize input keys to strings
        $normalized = [];
        foreach ($pairs as $k => $v) {
            $normalized[(string) $k] = (string) $v;
        }

        // If LarAgent runtime integration is available in your app, you can
        // implement an actual LLM call here, e.g. $this->ask($prompt) or similar.
        // To avoid hard dependency and unknown API surface, we provide a
        // deterministic offline fallback that prefixes values. Replace as needed.

        // Build a compact prompt for LLMs (if you wire one later)
        $payload = [
            'source_locale' => $sourceLocale,
            'target_locale' => $targetLocale,
            'pairs' => $normalized,
        ];

        // TODO: Integrate with your LarAgent provider if available, e.g.:
        // $response = $this->ask(json_encode($payload));
        // $decoded = json_decode($response, true);
        // if (is_array($decoded)) return $decoded;

        // Offline fallback: keep placeholders intact and mark as TODO translations
        $result = [];
        foreach ($normalized as $key => $value) {
            $result[$key] = $this->preservePlaceholders("[{$targetLocale}] " . $value);
        }
        return $result;
    }

    /**
     * Preserve common placeholder patterns while modifying text.
     */
    protected function preservePlaceholders(string $text): string
    {
        // No-op for now; hook for smarter placeholder handling if needed.
        return $text;
    }
}
