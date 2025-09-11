<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Fetch the categories to ensure the IDs are correct
        $lighthouseDecorCategory = Category::whereTranslation('title', 'Lighthouse Decor')->first();
        $maritimeArtCategory = Category::whereTranslation('title', 'Maritime Art')->first();
        $coastalGiftsCategory = Category::whereTranslation('title', 'Coastal Gifts')->first();

        // Define lighthouse and art-themed products
        $products = [
            [
                'category_id' => $lighthouseDecorCategory->id,
                'price' => 149.99,
                'active' => 1,
                'translations' => [
                    'en' => ['title' => 'Lighthouse Beacon Lamp', 'slug' => 'lighthouse-beacon-lamp', 'description' => 'Handcrafted lighthouse-inspired table lamp with rotating beacon light', 'brand' => 'Coastal Crafts', 'location' => 'Gallery A', 'color' => 'White & Red'],
                    'ka' => ['title' => 'შუქურის ნათურა', 'slug' => 'შუქურის-ნათურა', 'description' => 'ხელნაკეთი შუქურის ინსპირირებული მაგიდის ნათურა მბრუნავი შუქით', 'brand' => 'სანაპირო ხელოვნება', 'location' => 'გალერეა A', 'color' => 'თეთრი და წითელი'],
                ],
            ],
            [
                'category_id' => $maritimeArtCategory->id,
                'price' => 89.99,
                'active' => 1,
                'translations' => [
                    'en' => ['title' => 'Maritime Canvas Print', 'slug' => 'maritime-canvas-print', 'description' => 'Beautiful lighthouse seascape canvas print, perfect for coastal decor', 'brand' => 'Ocean Art Studio', 'location' => 'Gallery B', 'color' => 'Blue & White'],
                    'ka' => ['title' => 'საზღვაო ტილოს ნახატი', 'slug' => 'საზღვაო-ტილოს-ნახატი', 'description' => 'ლამაზი შუქურის ზღვის პეიზაჟის ტილოს ნახატი, იდეალური სანაპირო დეკორისთვის', 'brand' => 'ოკეანის ხელოვნების სტუდია', 'location' => 'გალერეა B', 'color' => 'ლურჯი და თეთრი'],
                ],
            ],
            [
                'category_id' => $coastalGiftsCategory->id,
                'price' => 24.99,
                'active' => 1,
                'translations' => [
                    'en' => ['title' => 'Lighthouse Ceramic Mug', 'slug' => 'lighthouse-ceramic-mug', 'description' => 'Hand-painted ceramic mug featuring iconic lighthouse design', 'brand' => 'Seaside Pottery', 'location' => 'Shop Floor', 'color' => 'Navy Blue'],
                    'ka' => ['title' => 'შუქურის კერამიკული ფინჯანი', 'slug' => 'შუქურის-კერამიკული-ფინჯანი', 'description' => 'ხელით მოხატული კერამიკული ფინჯანი შუქურის დიზაინით', 'brand' => 'ზღვისპირა კერამიკა', 'location' => 'მაღაზიის დარბაზი', 'color' => 'ღია ლურჯი'],
                ],
            ],
            [
                'category_id' => $coastalGiftsCategory->id,
                'price' => 199.99,
                'active' => 1,
                'translations' => [
                    'en' => ['title' => 'Vintage Lighthouse Model', 'slug' => 'vintage-lighthouse-model', 'description' => 'Detailed wooden lighthouse model replica, perfect for collectors', 'brand' => 'Heritage Models', 'location' => 'Display Case', 'color' => 'Natural Wood'],
                    'ka' => ['title' => 'ვინტაჟური შუქურის მოდელი', 'slug' => 'ვინტაჟური-შუქურის-მოდელი', 'description' => 'დეტალური ხის შუქურის მოდელის რეპლიკა, იდეალური კოლექციონერებისთვის', 'brand' => 'მემკვიდრეობის მოდელები', 'location' => 'საჩვენებელი ვიტრინა', 'color' => 'ბუნებრივი ხე'],
                ],
            ],
            [
                'category_id' => $lighthouseDecorCategory->id,
                'price' => 45.99,
                'active' => 1,
                'translations' => [
                    'en' => ['title' => 'Coastal Breeze Candle Set', 'slug' => 'coastal-breeze-candle-set', 'description' => 'Set of 3 lighthouse-themed scented candles with ocean breeze fragrance', 'brand' => 'Lighthouse Candles Co.', 'location' => 'Gift Section', 'color' => 'Ocean Blue'],
                    'ka' => ['title' => 'სანაპირო ქარის სანთლების ნაკრები', 'slug' => 'სანაპირო-ქარის-სანთლების-ნაკრები', 'description' => '3 შუქურის თემატური სუნამო სანთლის ნაკრები ოკეანის ქარის არომატით', 'brand' => 'შუქურის სანთლების კომპანია', 'location' => 'საჩუქრების სექცია', 'color' => 'ოკეანის ლურჯი'],
                ],
            ],
            [
                'category_id' => $maritimeArtCategory->id,
                'price' => 129.99,
                'active' => 1,
                'translations' => [
                    'en' => ['title' => 'Lighthouse Photography Book', 'slug' => 'lighthouse-photography-book', 'description' => 'Coffee table book featuring stunning lighthouse photography from around the world', 'brand' => 'Beacon Publications', 'location' => 'Book Corner', 'color' => 'Hardcover'],
                    'ka' => ['title' => 'შუქურების ფოტო წიგნი', 'slug' => 'შუქურების-ფოტო-წიგნი', 'description' => 'ყავის მაგიდის წიგნი მსოფლიოს შუქურების განსაცვიფრებელი ფოტოებით', 'brand' => 'ბიკონ გამომცემლობა', 'location' => 'წიგნების კუთხე', 'color' => 'მყარი ყდა'],
                ],
            ],
            [
                'category_id' => $coastalGiftsCategory->id,
                'price' => 34.99,
                'active' => 1,
                'translations' => [
                    'en' => ['title' => 'Nautical Compass Keychain', 'slug' => 'nautical-compass-keychain', 'description' => 'Brass nautical compass keychain with lighthouse engraving', 'brand' => 'Maritime Gifts', 'location' => 'Accessories', 'color' => 'Antique Brass'],
                    'ka' => ['title' => 'საზღვაო კომპასის საკვანძო', 'slug' => 'საზღვაო-კომპასის-საკვანძო', 'description' => 'ბრინჯაოს საზღვაო კომპასის საკვანძო შუქურის გრავირებით', 'brand' => 'საზღვაო საჩუქრები', 'location' => 'აქსესუარები', 'color' => 'ანტიკური ბრინჯაო'],
                ],
            ],
        ];

        // Insert the products into the database
        foreach ($products as $productData) {
            $translations = $productData['translations'];
            unset($productData['translations']);

            $product = Product::create($productData);

            foreach ($translations as $locale => $translation) {
                $product->translateOrNew($locale)->fill($translation);
            }

            $product->save();
        }
    }
}
