<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Store - Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold">Tech Store</h1>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
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
        <!-- Categories Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Categories</h2>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="block">
                        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 hover:text-blue-600">{{ $category->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $category->description }}</p>
                                <p class="mt-2 text-sm text-blue-600">{{ $category->products->count() }} products</p>
                                <div class="mt-3">
                                    <span class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                        View products
                                        <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Products Section -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-300">
                        <div class="aspect-w-3 aspect-h-2">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        </div>
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2">{{ $product->name }}</div>
                            <p class="text-gray-700 text-base mb-2">{{ $product->description }}</p>
                            <div class="flex items-center justify-between">
                                <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">
                                    {{ $product->category->name }}
                                </span>
                                <span class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('products.show', $product) }}" 
                                   class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
