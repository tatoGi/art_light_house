<?php

return [
    'types' => [
        'home' => [
            'title' => '{brand} | {tagline}',
            'meta_description' => 'Discover {brand}: {tagline}. Explore {focus} with high quality and expert guidance.',
            'default_keywords' => ['home', 'welcome', 'overview'],
        ],
        'category' => [
            'title' => '{category} | {brand}',
            'meta_description' => 'Browse {category} at {brand}. {summary}',
            'default_keywords' => ['category', 'guide', 'best'],
        ],
        'product' => [
            'title' => '{product} | {brand}',
            'meta_description' => '{product}: {summary}. Buy at {brand}.',
            'default_keywords' => ['features', 'specs', 'buy'],
        ],
        'blog' => [
            'title' => '{title} | {brand} Blog',
            'meta_description' => '{summary}',
            'default_keywords' => ['blog', 'tutorial', 'guide'],
        ],
        'contact' => [
            'title' => 'Contact {brand}',
            'meta_description' => 'Get in touch with {brand}: address, phone, email.',
            'default_keywords' => ['contact', 'support'],
        ],
    ],
];
