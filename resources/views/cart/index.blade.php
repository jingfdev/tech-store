<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Store - Cart</title>
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
        <div class="mx-auto max-w-3xl bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Shopping Cart</h2>

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

            @if($cartItems->isEmpty())
                <p class="text-gray-600">Your cart is currently empty.</p>
            @else
                <table class="min-w-full divide-y divide-gray-200 mb-6">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Remove</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="h-10 w-10 rounded-full object-cover">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->product->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" class="border rounded w-12 text-center" min="1" max="10">
                                        <button type="submit" class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-semibold py-1 px-2 rounded">
                                            Update
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        ${{ number_format($item->product->price, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        ${{ number_format($item->total, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('cart.remove', $item) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex justify-between items-center mb-4">
                    <div class="w-full md:w-1/2">
                        <form action="{{ route('cart.apply-coupon') }}" method="POST" class="flex">
                            @csrf
                            <input type="text" name="coupon_code" placeholder="Enter coupon code" class="border rounded-l py-2 px-4 w-full" required>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-r">
                                Apply
                            </button>
                        </form>
                        @if(session('discount_code'))
                            <div class="mt-2">
                                <p class="text-green-600 font-semibold">Discount Applied: {{ session('discount_code') }} - {{ session('discount_percentage') }}% off</p>
                                <form action="{{ route('cart.remove-coupon') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Remove Discount
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="text-lg leading-normal font-semibold text-gray-700">
                            Subtotal: ${{ number_format($subtotal, 2) }}
                        </div>
                        @if(session('discount_percentage'))
                            <div class="text-lg leading-normal font-semibold text-green-600">
                                Discount: -${{ number_format(($subtotal * session('discount_percentage') / 100), 2) }}
                            </div>
                        @endif
                        <div class="text-xl font-bold text-gray-900">
                            Total: ${{ number_format($subtotal - ($subtotal * session('discount_percentage', 0) / 100), 2) }}
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <form action="{{ route('checkout.create') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                            Proceed to Checkout
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</body>
</html>
