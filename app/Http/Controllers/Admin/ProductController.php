<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Page;
use App\Models\ProductOption;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Factory|View
    {
        $products = Product::orderBy('created_at', 'desc')->with('category')->paginate(5);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($page_id = null): Factory|View
    {
        $categories = Category::all(); // Optionally, list all categories for selection
        return view('admin.products.create', compact('categories', 'page_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        // Build minimal payload: price + translations only; others null
        $data = [
            'price' => $validated['price'],
            'on_sale' => $request->boolean('on_sale') ? 1 : 0,
            'sale_price' => $validated['sale_price'] ?? null,
            'active' => $request->boolean('active', true) ? 1 : 0,
            'product_identify_id' => null,
            'category_id' => $validated['category_id'] ?? null,
            'size' => null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        foreach (config('app.locales') as $locale) {
            $data[$locale] = [
                'title' => $validated[$locale]['title'] ?? null,
                'description' => $validated[$locale]['description'] ?? null,
            ];
        }

        $product = Product::create($data);

        // Handle product images
        if ($request->hasFile('images')) {
            $firstImage = null;
            foreach ($request->file('images') as $key => $image) {
                $imageName = $image->getClientOriginalName();
                $path = $image->storeAs('products', $imageName, 'public');
                $productImage = new ProductImage;
                $productImage->image_name = 'products/' . $imageName;
                $productImage->product_id = $product->id;
                $productImage->save();

                if ($key === 0) {
                    $firstImage = $imageName;
                }
            }

            foreach (config('app.locales') as $locale) {
                $seo = $product->translate($locale)->seo;
                $seo->update([
                    'title' => $data[$locale]['title'] ?? null,
                    'description' => $data[$locale]['description'] ?? null,
                    'image' => $firstImage,
                ]);
            }
        }

        // Smart redirect based on context
        if ($request->has('page_id') && $request->page_id) {
            // If coming from page management, redirect back to page management
            return redirect()->route('admin.pages.management.manage', ['page' => $request->page_id])
                ->with('success', 'Product created successfully.');
        } else {
            // If coming from standalone product management, redirect to product index
            return redirect()->route('products.index', app()->getLocale())
                ->with('success', 'Product created successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id): Factory|View
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit',
            ['categories' => $categories, 'product' => $product,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validated();

        // Update minimal base attributes
        $product->update([
            'price' => $validated['price'],
            'on_sale' => $request->boolean('on_sale') ? 1 : 0,
            'sale_price' => $validated['sale_price'] ?? null,
            'active' => $request->boolean('active', true) ? 1 : 0,
            'product_identify_id' => null,
            'category_id' => $validated['category_id'] ?? null,
            'size' => null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        // Update translations: title and description only
        foreach (config('app.locales') as $locale) {
            $translation = $product->translateOrNew($locale);
            $translation->title = $validated[$locale]['title'] ?? '';
            $translation->description = $validated[$locale]['description'] ?? '';
            $translation->save();
        }

        // Handle product images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $image->getClientOriginalName();
                $path = $image->storeAs('products', $imageName, 'public');
                $productImage = new ProductImage;
                $productImage->image_name = 'products/' . $imageName;
                $productImage->product_id = $product->id;
                $productImage->save();
            }
        }

        // Smart redirect based on context
        if ($request->has('page_id') && $request->page_id) {
            // If coming from page management, redirect back to page management
            return redirect()->route('admin.pages.management.manage', ['page' => $request->page_id])
                ->with('success', 'Product updated successfully.');
        } else {
            // If coming from standalone product management, redirect to product index
            return redirect()->route('products.index', app()->getLocale())
                ->with('success', 'Product updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the product
        $product = Product::findOrFail($id);

        // Delete associated SEO data
        if ($product->seo) {
            $product->seo->delete();
        }

        // Delete the product
        $product->delete();

        return redirect()->route('products.index', app()->getLocale());
    }

    public function deleteImage(Request $request, $image_id)
    {
        // Find the image by its ID
        $image = ProductImage::findOrFail($image_id);

        // Delete the image from storage
        Storage::delete('products/'.$image->image_name);

        // Delete the image record from the database
        $image->delete();

        // Return a JSON response with a success message
        return response()->json(['success' => 'Files Deleted']);
    }

    public function cleanupMissingImages()
    {
        $images = ProductImage::all();
        $deletedCount = 0;

        foreach ($images as $image) {
            if (!Storage::disk('public')->exists($image->image_name)) {
                $image->delete();
                $deletedCount++;
            }
        }

        return redirect()->back()->with('success', "Cleaned up {$deletedCount} missing image records.");
    }

    /**
     * Show the form for managing product pages.
     *
     * @param Product $product
     * @return View
     */
    public function managePages(Product $product): View
    {
        // Get all pages that are not already attached to the product
        $availablePages = Page::whereDoesntHave('products', function($query) use ($product) {
            $query->where('product_id', $product->id);
        })->get();

        return view('admin.product.options.index', [
            'product' => $product,
            'availablePages' => $availablePages
        ]);
    }

    /**
     * Attach a page to the product.
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function attachPage(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'page_id' => 'required|exists:pages,id',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Check if the page is already attached
        if ($product->pages()->where('page_id', $request->page_id)->exists()) {
            return back()->with('error', 'This page is already attached to the product.');
        }

        // Attach the page with sort order
        $product->pages()->attach($request->page_id, [
            'sort' => $request->sort_order ?? 0
        ]);

        return redirect()->route('admin.products.pages.manage', $product->id)
            ->with('success', 'Page has been added to the product successfully.');
    }

    /**
     * Detach a page from the product.
     *
     * @param Product $product
     * @param int $pageId
     * @return RedirectResponse
     */
    public function detachPage(Product $product, int $pageId): RedirectResponse
    {
        $product->pages()->detach($pageId);

        return redirect()->route('admin.products.pages.manage', $product->id)
            ->with('success', 'Page has been removed from the product.');
    }

    // ========== PAGE-SPECIFIC PRODUCT METHODS ==========

    /**
     * Display products for a specific page - redirects to page management.
     *
     * @param Page $page
     * @return RedirectResponse
     */
    public function indexForPage(Page $page): RedirectResponse
    {
        return redirect()->route('admin.pages.management.manage', ['page' => $page->id])
            ->with('success', 'Viewing page products in page management.');
    }

    /**
     * Show form for creating a new product for a specific page.
     *
     * @param Page $page
     * @return View
     */
    public function createForPage(Page $page): View
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories', 'page'));
    }

    /**
     * Store a new product and attach it to a specific page.
     *
     * @param ProductRequest $request
     * @param Page $page
     * @return RedirectResponse
     */
    public function storeForPage(ProductRequest $request, Page $page): RedirectResponse
    {
        $validated = $request->validated();

        // Build minimal payload and ensure product_identify_id is null if not provided
        $data = [
            'price' => $validated['price'],
            'active' => $request->boolean('active', true) ? 1 : 0,
            'product_identify_id' => null,
            'category_id' => $validated['category_id'] ?? null,
            'size' => null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        foreach (config('app.locales') as $locale) {
            $data[$locale] = [
                'title' => $validated[$locale]['title'] ?? null,
                'description' => $validated[$locale]['description'] ?? null,
            ];
        }

        $product = Product::create($data);

        // Attach the product to the page with sort order
        $sortOrder = $request->input('sort_order', 0);
        $page->products()->attach($product->id, ['sort' => $sortOrder]);

        // Handle product images
        if ($request->hasFile('images')) {
            $firstImage = null;
            foreach ($request->file('images') as $key => $image) {
                $imageName = $image->getClientOriginalName();
                $path = $image->storeAs('products', $imageName, 'public');
                $productImage = new ProductImage;
                $productImage->image_name = 'products/' . $imageName;
                $productImage->product_id = $product->id;
                $productImage->save();

                if ($key === 0) {
                    $firstImage = $imageName;
                }
            }

            foreach (config('app.locales') as $locale) {
                $seo = $product->translate($locale)->seo;
                $seoData = [
                    'title' => $data[$locale]['title'],
                    'description' => $data[$locale]['description'],
                    'image' => $firstImage,
                    'author' => $data[$locale]['author'] ?? null,
                    'robots' => $data[$locale]['robots'] ?? null,
                ];
                $seo->update($seoData);
            }
        }

        return redirect()->route('admin.pages.management.manage', ['page' => $page->id])
            ->with('success', 'Product created and attached to page successfully.');
    }

    /**
     * Attach an existing product to a page.
     *
     * @param Request $request
     * @param Page $page
     * @return RedirectResponse
     */
    public function attachToPage(Request $request, Page $page): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Check if the product is already attached
        if ($page->products()->where('product_id', $request->product_id)->exists()) {
            return back()->with('error', 'This product is already attached to the page.');
        }

        // Attach the product with sort order
        $page->products()->attach($request->product_id, [
            'sort' => $request->sort_order ?? 0
        ]);

        return redirect()->route('admin.pages.management.manage', ['page' => $page->id])
            ->with('success', 'Product has been attached to the page successfully.');
    }

    /**
     * Detach a product from a page.
     *
     * @param Page $page
     * @param Product $product
     * @return RedirectResponse
     */
    public function detachFromPage(Page $page, Product $product): RedirectResponse
    {
        $page->products()->detach($product->id);

        return redirect()->route('admin.pages.management.manage', ['page' => $page->id])
            ->with('success', 'Product has been removed from the page.');
    }
}
