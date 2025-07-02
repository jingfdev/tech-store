<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Store - Payment Success</title>
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
            <div class="text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Payment Successful!</h2>
                <p class="text-lg text-gray-600 mb-4">Thank you for your purchase. Your order is currently <strong>pending verification</strong>.</p>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Email Verification Required</h3>
                            <p class="text-sm text-yellow-700 mt-1">
                                We've sent a verification email to <strong>{{ auth()->user()->email }}</strong>. 
                                Please click the verification link in the email to confirm your order. 
                                Until verified, you can still cancel this order if needed.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Order Details -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Details</h3>
                    <div class="text-left">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Payment ID:</span>
                            <span class="font-mono text-sm">{{ $session->payment_intent }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Amount Paid:</span>
                            <span class="font-semibold">${{ number_format($session->amount_total / 100, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Payment Status:</span>
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                {{ ucfirst($session->payment_status) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Order Date:</span>
                            <span>{{ now()->format('M d, Y \a\t g:i A') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('products.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            Continue Shopping
                        </a>
                        <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                            View Dashboard
                        </a>
                    </div>
                    
                    <p class="text-sm text-gray-500">
                        A confirmation email has been sent to {{ auth()->user()->email }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
