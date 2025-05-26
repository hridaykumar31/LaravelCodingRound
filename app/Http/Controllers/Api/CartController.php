<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request, $id) {
    
    $user = auth()->user();
    $activeCart = Cart::where('user_id', $user->id)
                      ->where('status', 'active')
                      ->latest()
                      ->first();

    if (!$activeCart) {
        $activeCart = Cart::create([
            'user_id' => $user->id,
            'status' => 'active',
        ]);
    }
    $cart = $activeCart;
    $request->validate([
        'quantity' => 'sometimes|integer|min:1'
    ]);

    $product = $id;
    
    $quantity = $request->input('quantity', 1); 

    // Check if product already exists in cart
    $existingProduct = $cart->products()->where('product_id', $product)->first();

    if(!$existingProduct) {
        $cart->products()->attach($product, ['quantity' => $quantity]);
    } 
    else {
        $cart->products()->updateExistingPivot($product, [
            'quantity' => $existingProduct->pivot->quantity + $quantity
        ]);
    }

    return response()->json([
        'message' => 'Product added to cart successfully',
        'cart' => $cart->load('products') 
    ], 200);
}

    public function getCartItems() {
        
        $user = auth()->user();
        
        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();

       if(!$cart) {
        return response()->json([
            'message' => 'No active cart found for this user'
        ], 404);
    }
    // Load all products in the cart with their details
    $items = $cart->products()->get();

    return response()->json([
        'message' => 'Cart items retrieved successfully',
        'cart_items' => $items,
    ], 200);

    }

  

}
