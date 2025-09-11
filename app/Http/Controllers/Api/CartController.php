<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->input('product_id');
        $userId = Auth::id();

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
        $cartData = $this->getCartData($userId);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart' => $cartData,
            'cart_count' => count($cartData['items'] ?? [])
        ]);
    }

    public function removeFromCart(Request $request, $productId)
    {
        $userId = Auth::id();

        // Find and delete the cart item for the specified user and product
        $deleted = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ], 404);
        }

        // Return updated cart data
        $cartData = $this->getCartData($userId);

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart',
            'cart' => $cartData,
            'cart_count' => count($cartData['items'] ?? [])
        ]);
    }

    public function getCart()
    {
        $userId = Auth::id();
        $cartData = $this->getCartData($userId);

        return response()->json([
            'success' => true,
            'cart' => $cartData
        ]);
    }

    public function updateQuantity(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $quantity = $request->input('quantity');

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ], 404);
        }

        $cartItem->update(['quantity' => $quantity]);

        $cartData = $this->getCartData($userId);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart' => $cartData,
            'cart_count' => count($cartData['items'] ?? [])
        ]);
    }

    public function clearCart()
    {
        $userId = Auth::id();

        Cart::where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully',
            'cart' => ['items' => [], 'subtotal' => 0, 'cart_count' => 0]
        ]);
    }

    private function getCartData($userId)
    {
        $cartItems = Cart::where('user_id', $userId)
            ->with(['product' => function($query) {
                $query->with(['images' => function($q) {
                    $q->orderBy('id', 'asc')->take(1);
                }]);
            }])
            ->get();

        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            if (!$product) continue;

            // Get first image or use a default image
            $imageUrl = null;
            if ($product->images->isNotEmpty()) {
                $imageUrl = asset('storage/' . $product->images->first()->image_name);
            } else {
                $imageUrl = asset('images/default-product.jpg');
            }

            // Calculate item total
            $itemTotal = $product->price * $cartItem->quantity;
            $subtotal += $itemTotal;

            $items[] = [
                'id' => $product->id,
                'name' => $product->title,
                'price' => $product->price,
                'quantity' => $cartItem->quantity,
                'image' => $imageUrl,
                'item_total' => $itemTotal
            ];
        }

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'cart_count' => $cartItems->count(),
        ];
    }
}
