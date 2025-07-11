<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $orders = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $order->load(['orderItems.product']);
        
        return view('orders.show', compact('order'));
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if the order can be cancelled
        if (!$order->canBeCancelled()) {
            $errorMessage = 'This order cannot be cancelled.';
            
            if ($order->status === 'completed') {
                $errorMessage = 'Completed orders cannot be cancelled. If you have concerns, please contact support.';
            }
            
            return redirect()->route('orders.index')
                ->with('error', $errorMessage);
        }

        // Delete the order and its items (cascade will handle order_items)
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order has been cancelled and removed from your order history.');
    }
}
