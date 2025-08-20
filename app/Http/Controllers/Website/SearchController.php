<?php

namespace App\Http\Controllers\Website;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Post;
use App\Models\PostAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class SearchController extends Controller
{
    public static function search(Request $request)
    {
        // Validate the search query
        $validatedData = $request->validate(['que' => 'required']);
        $searchText = $validatedData['que'];
        
        // Language slugs
        $locale = app()->getLocale();
        $language_slugs = [$locale => "$locale/search?que=$searchText"];
        
        // Search in ProductTranslations
        $productTranslationIds = ProductTranslation::whereLocale($locale)
            ->where(function($query) use ($searchText) {
                $query->where('title', 'LIKE', "%{$searchText}%")
                      ->orWhere('description', 'LIKE', "%{$searchText}%");

                if (Schema::hasColumn('product_translations', 'brand')) {
                    $query->orWhere('brand', 'LIKE', "%{$searchText}%");
                }
                if (Schema::hasColumn('product_translations', 'model')) {
                    $query->orWhere('model', 'LIKE', "%{$searchText}%");
                }
            })
            ->pluck('product_id')
            ->toArray();

        // Fetch Products with translations
        $products = Product::whereIn('id', $productTranslationIds)
            ->with('translations')
            ->paginate(settings('paginate'))
            ->appends(['que' => $searchText]);

        // Prepare product data
        $productData = $products->map(function($product) use ($locale) {
            $translation = $product->translate($locale);
            return [
                'product' => $product,
                'slug' => $translation->slug ?? '#',
                'title' => $translation->title,
                'desc' => Str::limit(strip_tags($translation->description)),
                'price' => $product->price,
                'images' => $product->images,
                'model' => $translation->model,
                'brand' => $translation->brand,
            ];
        });

        // Search in CategoryTranslations
        $categoryTranslationIds = CategoryTranslation::whereLocale($locale)
            ->where('title', 'LIKE', "%{$searchText}%")
            ->pluck('category_id')
            ->toArray();

        // Fetch Categories with translations
        $categories = Category::whereIn('id', $categoryTranslationIds)
            ->with('translations')
            ->get();

        // Prepare category data
        $categoryData = $categories->map(function($category) use ($locale) {
            $translation = $category->translate($locale);
            return [
                'slug' => $translation->slug ?? '#',
                'title' => $translation->title,
                'desc' => Str::limit(strip_tags($translation->description)),
            ];
        });

        // Search in PostAttributes for this locale (translatable content)
        $postIds = PostAttribute::where('locale', $locale)
            ->where('attribute_value', 'LIKE', "%{$searchText}%")
            ->pluck('post_id')
            ->unique()
            ->toArray();

        // Fetch Posts and prepare data
        $posts = Post::whereIn('id', $postIds)
            ->active()
            ->published()
            ->ordered()
            ->get();

        $postData = $posts->map(function($post) use ($locale) {
            return [
                'slug' => $post->getAttributeForLocale('slug', $locale) ?? '#',
                'title' => $post->getAttributeForLocale('title', $locale),
                'desc' => Str::limit(strip_tags($post->getAttributeForLocale('description', $locale) ?? '')),
            ];
        });

        return response()->json([
            'products' => $productData,
            'categories' => $categoryData,
            'posts' => $postData,
        ]);
    }
}
