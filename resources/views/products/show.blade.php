<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Store - {{ $product->name }}</title>
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
                    @auth
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
                    @else
                        <a href="{{ route('login') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        Products
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Product Details -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
                <!-- Product Image -->
                <div>
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-96 object-cover rounded-lg">
                </div>

                <!-- Product Information -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                    
                    <div class="mb-4">
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    <p class="text-gray-700 text-lg mb-6">{{ $product->description }}</p>

                    <div class="mb-6">
                        <span class="text-4xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                    </div>

                    <div class="space-y-4">
                        @auth
                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <div class="flex items-center mb-4">
                                    <label for="quantity" class="mr-3 text-sm font-medium text-gray-700">Quantity:</label>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" class="border rounded w-16 text-center py-1">
                                </div>
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <div class="text-center">
                                <p class="text-gray-600 mb-4">Please login to add items to cart</p>
                                <a href="{{ route('login') }}" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg inline-block text-center">
                                    Login to Purchase
                                </a>
                            </div>
                        @endauth
                        
                        <a href="{{ route('products.index') }}" class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg text-lg text-center block">
                            Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Product Information -->
        <div class="mt-8 bg-white overflow-hidden shadow rounded-lg">
            <div class="px-6 py-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Product Information</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->category->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Price</dt>
                        <dd class="mt-1 text-sm text-gray-900">${{ number_format($product->price, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Product ID</dt>
                        <dd class="mt-1 text-sm text-gray-900">#{{ $product->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Added Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</body>
</html>
