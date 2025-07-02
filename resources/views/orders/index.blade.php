<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Store - Order History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('products.index') }}" class="text-xl font-semibold hover:text-blue-600">Tech Store</a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, {{ auth()->user()->name }}!</span>
                    <a href="{{ route('cart.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Cart
                    </a>
                    <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Order History</h2>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Cancellation Policy:</strong> Pending orders can be cancelled anytime. Completed orders can be cancelled within 24 hours of placement.
                        </p>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($orders->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-600 mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('products.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Start Shopping
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->id }}</h3>
                                    <p class="text-sm text-gray-600">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Order Items</h4>
                                    <div class="space-y-1">
                                        @foreach($order->orderItems as $item)
                                            <div class="flex justify-between text-sm">
                                                <span>{{ $item->product->name }} ({{ $item->quantity }}x)</span>
                                                <span>${{ number_format($item->total, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Order Summary</h4>
                                    @if($order->discount_code)
                                        <div class="flex justify-between text-sm">
                                            <span>Discount ({{ $order->discount_code }}):</span>
                                            <span class="text-green-600">-{{ $order->discount_percentage }}%</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between text-sm font-semibold border-t pt-1 mt-1">
                                        <span>Total:</span>
                                        <span>${{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center">
                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                    View Details
                                </a>
                                @if($order->canBeCancelled())
                                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                                            Cancel Order
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>
