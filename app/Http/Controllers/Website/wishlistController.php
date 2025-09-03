<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class wishlistController extends Controller
{
    public function addToWishlist(Request $request)
    {
        
        $productId = $request->input('product_id');
    
        $wishlist = $request->session()->get('wishlist', []);
    
        // Check if the product already exists in the wishlist
        if (in_array($productId, $wishlist)) {
            // Return JSON response indicating that the product is already in the wishlist
            return response()->json(['exists' => true]);
        }
    
        // Add the product ID to the wishlist array
        $wishlist[] = $productId;
        $request->session()->put('wishlist', $wishlist);
    
        // Return JSON response with updated wishlist count
        return response()->json(['exists' => false, 'wishlistCount' => count($wishlist)]);
    }
    
    public function wishlist(Request $request)
    {
        $wishlist = $request->session()->get('wishlist', []);
    
        // Retrieve product details based on wishlist IDs
        $products = Product::whereIn('id', $wishlist)
        ->with('translations')
        ->with('images')
        ->get();
    
        return response()->json(['products' => $products]);
    }
    public function removeFromWishlist(Request $request)
    {
        $productId = $request->input('productId');
        
        $wishlist = $request->session()->get('wishlist', []);
       
        // Remove the product ID from the wishlist array
        $wishlist = array_diff($wishlist, [$productId]);
        
        $request->session()->put('wishlist', $wishlist);
        
        // Return JSON response indicating success
        return response()->json(['success' => true]);
    }
    
public function addToCart(Request $request)
{
    if (Auth::guard('webuser')->check()) {
        $productId = $request->input('productId');
        $userId = Auth::guard('webuser')->user()->id;

        // Check if product already exists in cart
        $existingCartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingCartItem) {
            // Update quantity if product already in cart
            $existingCartItem->increment('quantity');
        } else {
            // Add new product to cart
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        // Get updated cart data
        $cartData = $this->fetchCartData($userId)->original;
        
        // Update session cart data
        session()->put('cart', $cartData);

        // Return success response with cart data
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart' => $cartData,
            'cart_count' => count($cartData['cart'] ?? [])
        ]);
    } else {
        // User is not authenticated
        return response()->json([
            'success' => false,
            'message' => 'Authentication required',
            'redirect' => route('login') // Make sure you have a named route for login
        ], 401);
    }
}



public function removeFromCart($productId)
{
    // Get the authenticated user's ID
    $userId = Auth::guard('webuser')->user()->id;

    // Find and delete the cart item for the specified user and product
    Cart::where('user_id', $userId)
        ->where('product_id', $productId)
        ->delete();

    // Update session cart data
    session()->put('cart', $this->fetchCartData($userId)->original);

    // Return updated cart data
    return $this->fetchCartData($userId);
}
    public function fetchCartData($userId)
    {
        $cartItems = Cart::where('user_id', $userId)
            ->with(['product' => function($query) {
                $query->with(['images' => function($q) {
                    $q->orderBy('sort_order')->take(1);
                }]);
            }])
            ->get();
    
        // Prepare array to store formatted product data
        $products = [];
        $subtotal = 0;
    
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            if (!$product) continue; // Skip if product not found
            
            // Get first image or use a default image
            $imageUrl = null;
            if ($product->images->isNotEmpty()) {
                $imageUrl = asset('storage/' . $product->images->first()->image_name);
            } else {
                // Fallback to a default image if no product images exist
                $imageUrl = asset('images/default-product.jpg'); // Make sure this image exists in your public directory
            }
    
            // Calculate item total
            $itemTotal = $product->price * $cartItem->quantity;
            $subtotal += $itemTotal;
            
            // Add to products array
            $products[] = [
                'id' => $product->id,
                'name' => $product->title,
                'price' => $product->price,
                'quantity' => $cartItem->quantity,
                'image' => $imageUrl,
                'item_total' => $itemTotal
            ];
        }
       
        return response()->json([
            'items' => $products,
            'subtotal' => $subtotal,
            'cartCount' => $cartItems->count(),
        ]);
    }
    
    
    

        
}
