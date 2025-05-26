<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    public function placeOrder(Request $request) {

       try {
            $request->validate([
                'address' => 'required|string|max:255',
                'phone' => 'required|max:15',
                'payment_method' => 'nullable|in:credit_card,paypal,cash_on_delivery,sslcommerz',
             ]);

            $user = auth()->user();

            $cart = $user->carts()->where('status', 'active')->latest()->first();

            if(!$cart || $cart->products->isEmpty()) {
                return response()->json([
                    'message' => 'Cart is empty ot does not exist',
                ], 400);
            }

            $totalAmount = 0;

            foreach($cart->products as $product) {
                $totalAmount += $product->price * $product->pivot->quantity;
            }
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $user->id,
                'cart_id'=> $cart->id,
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'payment_method' => $request->input('payment_method'),
                'transaction_id' => $request->input('transaction_id', null),
            ]);

            foreach($cart->products as $product) {
                $order->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity,
                    'price' => $product->price,
                    'total' => $product->price * $product->pivot->quantity,
                ]);
            }

            $cart->update(['status' => 'completed']);
            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $cart->load('products'),
            ]);

       }catch(Exception $e) {
            DB::rollBack();
            Log::error('Order placement failed:' . $e->getMessage());
            return response()->json([
                'message' => 'Order placement failed',
                'error' => $e->getMessage(),
            ], 500);
       }
        
    }
}
