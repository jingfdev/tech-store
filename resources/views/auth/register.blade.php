<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Store - Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-12 w-12 bg-indigo-600 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-store text-white text-xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Join Tech Store
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Create your account to start shopping for the latest tech
                </p>
                <p class="mt-1 text-xs text-gray-500">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Sign in here
                    </a>
                </p>
            </div>
            
            <!-- Error Messages -->
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Success Messages -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Social Sign Up Form -->
            <div class="mt-8 bg-white py-8 px-6 shadow-lg rounded-xl border border-gray-200">
                <div class="space-y-4">
                    <!-- Benefits Section -->
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Why join Tech Store?</h3>
                        <div class="grid grid-cols-1 gap-2 text-sm text-gray-600">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-shipping-fast text-indigo-500 mr-2"></i>
                                <span>Fast & Free Shipping</span>
                            </div>
                            <div class="flex items-center justify-center">
                                <i class="fas fa-shield-alt text-indigo-500 mr-2"></i>
                                <span>Secure Shopping Experience</span>
                            </div>
                            <div class="flex items-center justify-center">
                                <i class="fas fa-star text-indigo-500 mr-2"></i>
                                <span>Exclusive Member Deals</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Sign up with</span>
                        </div>
                    </div>
                    
                    <!-- Google Sign Up Button -->
                    <a href="{{ route('social.redirect', 'google') }}" 
                       class="group relative w-full flex justify-center py-3 px-4 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span>Continue with Google</span>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-gray-600 transition-colors duration-200"></i>
                        </div>
                    </a>

                    <!-- GitHub Sign Up Button -->
                    <a href="{{ route('social.redirect', 'github') }}" 
                       class="group relative w-full flex justify-center py-3 px-4 border border-gray-800 text-sm font-medium rounded-lg text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            <span>Continue with GitHub</span>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-white transition-colors duration-200"></i>
                        </div>
                    </a>
                </div>

                <!-- Privacy Notice -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        By signing up, you agree to our 
                        <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms of Service</a>
                        and 
                        <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
                    </p>
                </div>

                <!-- Security Notice -->
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-blue-700">
                                <strong>Secure Sign-up:</strong> We use OAuth 2.0 authentication to keep your account safe. 
                                We never store your social media passwords.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Links -->
            <div class="text-center">
                <div class="space-y-2">
                    <a href="{{ route('products.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Continue browsing without an account
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const socialButtons = document.querySelectorAll('a[href*="auth/"]');
            
            socialButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const originalText = this.querySelector('span').textContent;
                    const span = this.querySelector('span');
                    const arrow = this.querySelector('.fa-arrow-right');
                    
                    // Change button appearance
                    span.textContent = 'Redirecting...';
                    arrow.className = 'fas fa-spinner fa-spin text-gray-400';
                    this.style.pointerEvents = 'none';
                    this.style.opacity = '0.7';
                    
                    // Reset after 5 seconds in case of error
                    setTimeout(() => {
                        span.textContent = originalText;
                        arrow.className = 'fas fa-arrow-right text-gray-400 group-hover:text-gray-600 transition-colors duration-200';
                        this.style.pointerEvents = 'auto';
                        this.style.opacity = '1';
                    }, 5000);
                });
            });
        });
    </script>
</body>
</html>
