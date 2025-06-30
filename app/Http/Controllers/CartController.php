<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the cart.
     */
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();
            
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
        
        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update quantity if item already exists
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
            ]);
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        // Ensure the cart item belongs to the authenticated user
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('cart.index')
            ->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove item from cart.
     */
    public function remove(Cart $cart)
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Item removed from cart!');
    }

    /**
     * Apply discount coupon.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $discountPercentage = 0;
        
        // Check for valid coupon codes
        if (strtoupper($request->coupon_code) === 'TECH10') {
            $discountPercentage = 10;
            session(['discount_code' => 'TECH10', 'discount_percentage' => 10]);
            $message = 'Coupon applied! 10% discount activated.';
        } else {
            $message = 'Invalid coupon code.';
        }

        return redirect()->route('cart.index')
            ->with($discountPercentage > 0 ? 'success' : 'error', $message);
    }

    /**
     * Remove applied coupon.
     */
    public function removeCoupon()
    {
        session()->forget(['discount_code', 'discount_percentage']);
        
        return redirect()->route('cart.index')
            ->with('success', 'Coupon removed successfully!');
    }
}
