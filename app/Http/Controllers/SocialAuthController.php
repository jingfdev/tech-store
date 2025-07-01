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
            
            // Check if user already exists by email
            $user = User::where('email', $socialUser->getEmail())->first();
            $isNewUser = false;
            
            // Also check if this social account is already linked to a different user
            $existingSocialUser = User::where('provider_id', $socialUser->getId())
                ->where('provider', $provider)
                ->first();
            
            if ($existingSocialUser && $existingSocialUser->email !== $socialUser->getEmail()) {
                // This social account is linked to a different email
                return redirect('/login')->with('error', 
                    'This ' . ucfirst($provider) . ' account is already linked to a different email address. Please use the correct account or contact support.');
            }
            
            if ($user) {
                // Update provider info if user exists but doesn't have social account linked
                if (empty($user->provider_id) || $user->provider_id !== $socialUser->getId()) {
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                    ]);
                }
            } else {
                // Create new user (sign up)
                $user = User::create([
                    'name' => $socialUser->getName() ?: 'User',
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(24)), // Generate random password
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
                $isNewUser = true;
            }
            
            Auth::login($user);
            
            // Redirect with appropriate welcome message
            if ($isNewUser) {
                return redirect()->intended('/dashboard')
                    ->with('success', 'Welcome to Tech Store! Your account has been created successfully.');
            } else {
                return redirect()->intended('/dashboard')
                    ->with('success', 'Welcome back! You have been logged in successfully.');
            }
            
        } catch (Exception $e) {
            \Log::error('Social auth error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Authentication failed. Please try again. If the problem persists, please contact support.');
        }
    }
}
