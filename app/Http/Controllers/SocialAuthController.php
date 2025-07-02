<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirect to provider for authentication
     */
    public function redirectToProvider(string $provider)
    {
        // Validate provider
        if (!in_array($provider, ['google', 'github'])) {
            return redirect('/login')->with('error', 'Unsupported authentication provider.');
        }
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle provider callback
     */
    public function handleProviderCallback(string $provider)
    {
        // Validate provider
        if (!in_array($provider, ['google', 'github'])) {
            return redirect('/login')->with('error', 'Unsupported authentication provider.');
        }
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Check if this exact social account exists (provider + provider_id)
            $existingUser = User::where('provider_id', $socialUser->getId())
                ->where('provider', $provider)
                ->first();
            
            if ($existingUser) {
                // User exists with this social account - sign them in
                Auth::login($existingUser);
                return redirect()->intended('/dashboard')
                    ->with('success', 'Welcome back! You have been logged in successfully.');
            } else {
                // Check if email already exists with a different provider
                $existingEmailUser = User::where('email', $socialUser->getEmail())->first();
                
                if ($existingEmailUser) {
                    // Email exists with different provider
                    $existingProvider = ucfirst($existingEmailUser->provider);
                    return redirect('/login')
                        ->with('error', 'This email is already registered with ' . $existingProvider . '. Please sign in with ' . $existingProvider . ' instead.');
                }
                
                // User doesn't exist - redirect to register with message
                session([
                    'social_user_data' => [
                        'name' => $socialUser->getName() ?: 'User',
                        'email' => $socialUser->getEmail(),
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                    ]
                ]);
                
                return redirect('/register')
                    ->with('info', 'You don\'t have an account yet. Let\'s create one for you with your ' . ucfirst($provider) . ' information.');
            }
            
        } catch (Exception $e) {
            \Log::error('Social auth error: ' . $e->getMessage());
            
            // Handle specific error cases
            $errorMessage = $e->getMessage();
            
            // Check if it's a user cancellation (user clicked "Cancel" on OAuth screen)
            if (str_contains($errorMessage, 'access_denied') || str_contains($errorMessage, 'user_denied')) {
                return redirect('/login')->with('info', 'Sign in was cancelled. Please try again when you\'re ready.');
            }
            
            // Check if it's a network/OAuth provider issue
            if (str_contains($errorMessage, 'cURL') || str_contains($errorMessage, 'timeout') || str_contains($errorMessage, 'SSL')) {
                return redirect('/login')->with('error', 'Unable to connect to ' . ucfirst($provider) . '. Please check your internet connection and try again.');
            }
            
            // For any other authentication errors, provide a more helpful message
            return redirect('/login')->with('error', 'We couldn\'t sign you in with ' . ucfirst($provider) . '. Please try again or contact support if the problem continues.');
        }
    }
}
