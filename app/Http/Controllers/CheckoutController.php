<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Exception;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Set Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    /**
     * Create Stripe checkout session.
     */
    public function createSession(Request $request)
    {
        try {
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Your cart is empty!');
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $discountPercentage = session('discount_percentage', 0);
            $discountAmount = ($subtotal * $discountPercentage) / 100;
            $total = $subtotal - $discountAmount;

            // Prepare line items for Stripe
            $lineItems = [];
            foreach ($cartItems as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->product->name,
                            'description' => $item->product->description,
                        ],
                        'unit_amount' => $item->product->price * 100, // Stripe expects amount in cents
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            // Add discount if applicable
            $discounts = [];
            if ($discountPercentage > 0) {
                // For simplicity, we'll apply the discount as a negative line item
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Discount (' . session('discount_code') . ')',
                        ],
                        'unit_amount' => -($discountAmount * 100), // Negative amount for discount
                    ],
                    'quantity' => 1,
                ];
            }

            // Create Stripe checkout session
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cart.index'),
                'customer_email' => Auth::user()->email,
                'metadata' => [
                    'user_id' => Auth::id(),
                    'discount_code' => session('discount_code', ''),
                    'discount_percentage' => session('discount_percentage', 0),
                ],
            ]);

            return redirect($session->url);

        } catch (Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Handle successful payment.
     */
    public function success(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            $session = Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                // Get cart items before clearing
                $cartItems = Cart::with('product')
                    ->where('user_id', Auth::id())
                    ->get();

                // Create order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'stripe_payment_intent_id' => $session->payment_intent,
                    'total_amount' => $session->amount_total / 100, // Convert from cents
                    'status' => 'completed',
                    'discount_code' => $session->metadata->discount_code ?? null,
                    'discount_percentage' => $session->metadata->discount_percentage ?? 0,
                ]);

                // Create order items
                foreach ($cartItems as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->product->price, // Store current price
                    ]);
                }

                // Clear user's cart
                Cart::where('user_id', Auth::id())->delete();
                
                // Clear discount session
                session()->forget(['discount_code', 'discount_percentage']);
                
                return view('checkout.success', compact('session', 'order'));
            }

            return redirect()->route('cart.index')
                ->with('error', 'Payment was not successful.');

        } catch (Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', 'Error processing payment confirmation.');
        }
    }
}
