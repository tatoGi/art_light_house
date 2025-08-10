<?php

return [
    'id' => 1,
    'type' => 1,
    'name' => 'Home Page',
    'slug' => 'home',
    'has_posts' => true,
    // Enable single fixed post behavior for homepage
    'single_post' => true,

    // Single-post attributes for homepage
    'post_attributes' => [
        'translatable' => [
            'title' => [
                'type' => 'text',
                'required' => true,
                'label' => 'Home Title',
                'placeholder' => 'Welcome to Our Platform'
            ],
            'description' => [
                'type' => 'textarea',
                'required' => true,
                'label' => 'Home Description',
                'rows' => 4,
                'placeholder' => 'Describe your platform briefly...'
            ]
        ],
        'non_translatable' => [
            'projects_count' => [
                'type' => 'number',
                'required' => true,
                'label' => 'Number of Projects',
                'default' => 0
            ],
            'partners_count' => [
                'type' => 'number',
                'required' => true,
                'label' => 'Number of Partners',
                'default' => 0
            ],
            'shops_count' => [
                'type' => 'number',
                'required' => true,
                'label' => 'Number of Shops',
                'default' => 0
            ]
        ]
    ]
];