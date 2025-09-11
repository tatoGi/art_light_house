<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'icon' => 'lighthouse-icon.png',
                'active' => 1,
                'translations' => [
                    ['locale' => 'en', 'title' => 'Lighthouse Decor', 'slug' => 'lighthouse-decor', 'description' => 'Lighthouse-themed decorative items and lamps'],
                    ['locale' => 'ka', 'title' => 'შუქურის დეკორი', 'slug' => 'შუქურის-დეკორი', 'description' => 'შუქურის თემატური დეკორატიული ნივთები და ნათურები']
                ],
            ],
            [
                'icon' => 'art-icon.png',
                'active' => 1,
                'translations' => [
                    ['locale' => 'en', 'title' => 'Maritime Art', 'slug' => 'maritime-art', 'description' => 'Canvas prints, paintings and maritime artwork'],
                    ['locale' => 'ka', 'title' => 'საზღვაო ხელოვნება', 'slug' => 'საზღვაო-ხელოვნება', 'description' => 'ტილოს ნახატები, ფერწერა და საზღვაო ხელოვნების ნამუშევრები']
                ],
            ],
            [
                'icon' => 'gift-icon.png',
                'active' => 1,
                'translations' => [
                    ['locale' => 'en', 'title' => 'Coastal Gifts', 'slug' => 'coastal-gifts', 'description' => 'Nautical gifts, souvenirs and collectibles'],
                    ['locale' => 'ka', 'title' => 'სანაპირო საჩუქრები', 'slug' => 'სანაპირო-საჩუქრები', 'description' => 'საზღვაო საჩუქრები, სუვენირები და კოლექციური ნივთები']
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'icon' => $categoryData['icon'],
                'active' => $categoryData['active']
            ]);

            foreach ($categoryData['translations'] as $translation) {
                $category->translations()->create($translation);
            }
        }
    }
}
