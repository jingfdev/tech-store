<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Store - Email Verification Simulator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('products.index') }}" class="text-xl font-semibold hover:text-blue-600">Tech Store</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Email Verification Simulator</h2>
            <p class="text-gray-600 mb-6">This page simulates the email verification process. In production, verification links would be sent via email.</p>
            
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

            <div class="space-y-4">
                <h3 class="text-lg font-semibold">Pending Orders (Require Verification)</h3>
                
                @php
                    $pendingOrders = \App\Models\Order::where('status', 'pending')->with('user')->get();
                @endphp
                
                @if($pendingOrders->isEmpty())
                    <p class="text-gray-500">No pending orders found.</p>
                @else
                    <div class="grid gap-4">
                        @foreach($pendingOrders as $order)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="font-semibold">Order #{{ $order->id }}</h4>
                                        <p class="text-sm text-gray-600">User: {{ $order->user->email }}</p>
                                        <p class="text-sm text-gray-600">Amount: ${{ number_format($order->total_amount, 2) }}</p>
                                        <p class="text-sm text-gray-600">Created: {{ $order->created_at->format('M d, Y g:i A') }}</p>
                                        @if($order->verification_token)
                                            <p class="text-xs text-gray-500">Token: {{ substr($order->verification_token, 0, 10) }}...</p>
                                        @endif
                                    </div>
                                    <div class="space-x-2">
                                        @if($order->verification_token)
                                            <a href="{{ route('order.verify', ['token' => $order->verification_token]) }}" 
                                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                Verify Order
                                            </a>
                                        @else
                                            <span class="text-red-500 text-sm">No verification token</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <div class="mt-8 pt-6 border-t">
                <a href="{{ route('orders.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Back to Orders
                </a>
            </div>
        </div>
    </div>
</body>
</html>
