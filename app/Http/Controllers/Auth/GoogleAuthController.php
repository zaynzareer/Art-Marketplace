<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google for authentication
     */
    public function redirect()
    {
        try {
            return Socialite::driver('google')
                ->stateless()
                ->scopes(['email', 'profile', 'openid'])
                ->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login');
        }
    }

    /**
     * Handle Google callback
     */
    public function callback()
    {
        try {
            // Handle Google errors
            if (request()->has('error')) {
                return redirect()->route('login');
            }

            // Verify authorization code
            if (!request()->has('code')) {
                return redirect()->route('login');
            }

            // Get user info from Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Store OAuth data in session for role selection
            session([
                'oauth_user' => [
                    'google_id' => $googleUser->getId(),
                    'email' => $googleUser->getEmail(),
                    'name' => $googleUser->getName(),
                    'avatar_url' => $googleUser->getAvatar(),
                ]
            ]);

            // Check if user already exists
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                // Update profile photo if not set or outdated
                $this->updateProfilePhotoIfNeeded($user, $googleUser->getAvatar());
                
                auth()->login($user, remember: true);
                return redirect()->intended(route('dashboard'));
            }

            // New user - redirect to role selection
            return redirect()->route('oauth.select-role');

        } catch (\Exception $e) {
            return redirect()->route('login');
        }
    }

    /**
     * Update user's profile photo from Google if not already set
     */
    private function updateProfilePhotoIfNeeded(User $user, ?string $avatarUrl): void
    {
        // Only update if user has no profile photo and Google provides one
        if ($user->profile_photo_path || !$avatarUrl) {
            return;
        }

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ],
                'ssl' => ['verify_peer' => false]
            ]);

            $imageContent = @file_get_contents($avatarUrl, false, $context);
            if (!$imageContent) {
                return;
            }

            $filename = $user->id . '-' . time() . '.jpg';
            $path = 'profile-photos/' . $filename;

            Storage::put($path, $imageContent);
            $user->update(['profile_photo_path' => $path]);

        } catch (\Exception $e) {
            // Silently fail
        }
    }
}