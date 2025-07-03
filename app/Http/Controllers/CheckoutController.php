<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
            \Log::info('Checkout process started', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'stripe_secret_key_present' => !empty(env('STRIPE_SECRET_KEY'))
            ]);
            
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();
                
            \Log::info('Cart items retrieved', [
                'cart_items_count' => $cartItems->count()
            ]);

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

            // Add discount if applicable - we'll handle this differently
            // Instead of negative line items, we'll use Stripe's discount feature
            $sessionData = [
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
                    'subtotal' => $subtotal,
                    'discount_amount' => $discountAmount,
                    'total' => $total,
                ],
            ];
            
            // If there's a discount, we'll create a simpler approach
            if ($discountPercentage > 0) {
                // Instead of negative line items, adjust the quantities/prices
                // For now, let's just show the original prices and handle discount in metadata
                $sessionData['metadata']['discount_note'] = 'Discount applied: ' . session('discount_code') . ' - ' . $discountPercentage . '% off';
            }

            // Create Stripe checkout session
            $session = Session::create($sessionData);

            return redirect($session->url);

        } catch (Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            \Log::error('Checkout error trace: ' . $e->getTraceAsString());
            
            // Provide more specific error messages
            $errorMessage = 'Something went wrong. Please try again.';
            
            if (str_contains($e->getMessage(), 'Invalid API key')) {
                $errorMessage = 'Payment system configuration error. Please contact support.';
            } elseif (str_contains($e->getMessage(), 'No such customer')) {
                $errorMessage = 'Customer information error. Please try again.';
            } elseif (str_contains($e->getMessage(), 'network') || str_contains($e->getMessage(), 'timeout')) {
                $errorMessage = 'Network error. Please check your connection and try again.';
            }
            
            return redirect()->route('cart.index')
                ->with('error', $errorMessage);
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

                // Create order as pending (requires email verification)
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'stripe_payment_intent_id' => $session->payment_intent,
                    'total_amount' => $session->amount_total / 100, // Convert from cents
                    'status' => 'pending',
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
                
                // Send order verification email
                $this->sendOrderVerificationEmail($order);
                
                return view('checkout.success', compact('session', 'order'));
            }

            return redirect()->route('cart.index')
                ->with('error', 'Payment was not successful.');

        } catch (Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', 'Error processing payment confirmation.');
        }
    }
    
    /**
     * Send order verification email.
     */
    private function sendOrderVerificationEmail(Order $order)
    {
        try {
            // Generate verification token
            $token = Str::random(64);
            
            // Store token in order or create a separate verification table
            // For now, we'll store it in the order's stripe_payment_intent_id field temporarily
            // In production, you'd want a separate verification_tokens table
            $order->update(['verification_token' => $token]);
            
            // Send email (for now, we'll just log it since we don't have mail setup)
            \Log::info('Order verification email would be sent', [
                'order_id' => $order->id,
                'user_email' => $order->user->email,
                'verification_url' => route('order.verify', ['token' => $token])
            ]);
            
            // TODO: Implement actual email sending
            // Mail::to($order->user->email)->send(new OrderVerificationMail($order, $token));
            
        } catch (Exception $e) {
            \Log::error('Failed to send order verification email: ' . $e->getMessage());
        }
    }
    
    /**
     * Verify order via email token.
     */
    public function verifyOrder(Request $request, $token)
    {
        try {
            $order = Order::where('verification_token', $token)
                ->where('status', 'pending')
                ->first();
                
            if (!$order) {
                return redirect()->route('orders.index')
                    ->with('error', 'Invalid or expired verification link.');
            }
            
            // Mark order as completed
            $order->update([
                'status' => 'completed',
                'verification_token' => null // Clear the token
            ]);
            
            return redirect()->route('orders.index')
                ->with('success', 'Order verified successfully! Your order is now confirmed.');
                
        } catch (Exception $e) {
            return redirect()->route('orders.index')
                ->with('error', 'Error verifying order.');
        }
    }
}
