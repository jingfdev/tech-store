<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Complete social registration
     */
    public function completeSocialRegistration(Request $request)
    {
        $socialData = session('social_user_data');
        
        if (!$socialData) {
            return redirect('/register')->with('error', 'Registration session expired. Please try again.');
        }
        
        // Double-check if email already exists (security measure)
        $existingUser = User::where('email', $socialData['email'])->first();
        if ($existingUser) {
            session()->forget('social_user_data');
            $existingProvider = ucfirst($existingUser->provider);
            return redirect('/login')
                ->with('error', 'This email is already registered with ' . $existingProvider . '. Please sign in with ' . $existingProvider . ' instead.');
        }
        
        // Create new user with social data
        $user = User::create([
            'name' => $socialData['name'],
            'email' => $socialData['email'],
            'password' => Hash::make(Str::random(24)), // Generate random password
            'provider' => $socialData['provider'],
            'provider_id' => $socialData['provider_id'],
        ]);
        
        // Clear social data from session
        session()->forget('social_user_data');
        
        // Log in the new user
        Auth::login($user);
        
        return redirect('/dashboard')
            ->with('success', 'Welcome to Tech Store! Your account has been created successfully with ' . ucfirst($socialData['provider']) . '.');
    }
    
    /**
     * Cancel social registration
     */
    public function cancelSocialRegistration()
    {
        session()->forget('social_user_data');
        return redirect('/login')->with('info', 'Registration cancelled. You can try again anytime.');
    }
}
