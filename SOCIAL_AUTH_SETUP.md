# Social Authentication Setup Guide

This guide will help you set up Google and GitHub OAuth authentication for your Tech Store application.

## 🚀 Quick Start

Your application now has:
- ✅ A dedicated sign-up page at `/register`
- ✅ Social authentication with Google and GitHub
- ✅ Automatic account creation on first login
- ✅ Secure OAuth 2.0 implementation

## 📋 Prerequisites

1. Your Laravel application is running
2. You have access to:
   - Google Cloud Console
   - GitHub Developer Settings

## 🔧 Setup Instructions

### 1. Google OAuth Setup

#### Step 1: Create a Google Cloud Project
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Google+ API or Google People API

#### Step 2: Create OAuth Credentials
1. Go to "APIs & Services" > "Credentials"
2. Click "Create Credentials" > "OAuth client ID"
3. Choose "Web application"
4. Set the following:
   - **Authorized JavaScript origins**: `http://localhost:8000`
   - **Authorized redirect URIs**: `http://localhost:8000/auth/google/callback`

#### Step 3: Update Environment Variables
Replace these values in your `.env` file:
```env
GOOGLE_CLIENT_ID=your_actual_google_client_id
GOOGLE_CLIENT_SECRET=your_actual_google_client_secret
```

### 2. GitHub OAuth Setup

#### Step 1: Create a GitHub OAuth App
1. Go to [GitHub Developer Settings](https://github.com/settings/developers)
2. Click "New OAuth App"
3. Fill in the details:
   - **Application name**: Tech Store
   - **Homepage URL**: `http://localhost:8000`
   - **Authorization callback URL**: `http://localhost:8000/auth/github/callback`

#### Step 2: Update Environment Variables
Replace these values in your `.env` file:
```env
GITHUB_CLIENT_ID=your_actual_github_client_id
GITHUB_CLIENT_SECRET=your_actual_github_client_secret
```

### 3. Database Migration

Make sure your users table has the necessary columns for social authentication:

```bash
php artisan migrate
```

The migration should include:
- `provider` (nullable string)
- `provider_id` (nullable string)

## 🔍 Testing Your Setup

### 1. Start Your Application
```bash
composer run dev
```

### 2. Visit the Sign-up Page
Navigate to: `http://localhost:8000/register`

### 3. Test Social Login
1. Click "Continue with Google" or "Continue with GitHub"
2. Complete the OAuth flow
3. Verify that:
   - Account is created automatically
   - User is logged in
   - Redirected to dashboard with success message

## 🗂️ File Structure

Your authentication system includes:

```
resources/views/auth/
├── login.blade.php      # Login page (existing)
└── register.blade.php   # New sign-up page

app/Http/Controllers/
└── SocialAuthController.php  # Handles OAuth logic

config/
└── services.php        # OAuth configuration

routes/
└── web.php            # Authentication routes
```

## 🔐 Security Features

- ✅ OAuth 2.0 secure authentication
- ✅ CSRF protection
- ✅ Duplicate account prevention
- ✅ Error handling and user feedback
- ✅ Secure session management

## 🎯 Key Features

### New User Registration
- Automatic account creation on first social login
- Welcome message for new users
- Profile information from social providers

### Existing User Login
- Seamless login for returning users
- Provider information updates
- Welcome back messages

### Error Handling
- Clear error messages for failed authentication
- Fallback for authentication errors
- User-friendly error displays

## 🚀 Usage

### Access the Sign-up Page
- Direct URL: `http://localhost:8000/register`
- From login page: Click "Sign up here" link
- From any page: Navigate to register route

### User Flow
1. User visits `/register`
2. Clicks "Continue with Google" or "Continue with GitHub"
3. Completes OAuth flow with provider
4. Returns to app with account created/logged in
5. Redirected to dashboard with success message

## 🔧 Customization

### Styling
The sign-up page uses Tailwind CSS and includes:
- Responsive design
- Loading animations
- Modern UI components
- Brand consistency

### Messages
You can customize success/error messages in:
- `SocialAuthController.php`
- View files (`register.blade.php`, `login.blade.php`)

### Redirect URLs
Modify redirect URLs in:
- `.env` file for OAuth providers
- `SocialAuthController.php` for post-login redirects

## 🐛 Troubleshooting

### Common Issues

#### 1. "Invalid redirect URI" Error
- Check that callback URLs in provider settings match your `.env` exactly
- Ensure no trailing slashes in URLs

#### 2. "Client ID not found" Error
- Verify client ID and secret in `.env` file
- Check that OAuth app is enabled in provider console

#### 3. Database Errors
- Run `php artisan migrate` to ensure tables exist
- Check database connection in `.env`

#### 4. Session Issues
- Clear application cache: `php artisan cache:clear`
- Clear config cache: `php artisan config:clear`

### Development vs Production

For production deployment, update:
- Redirect URLs to your domain
- App URLs in `.env`
- HTTPS configuration
- Environment-specific credentials

## 📞 Support

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify provider console settings
3. Test with different browsers
4. Check network/firewall settings

## 🎉 You're Ready!

Your sign-up page with Google and GitHub authentication is now ready to use! Users can create accounts seamlessly using their existing social media accounts.
