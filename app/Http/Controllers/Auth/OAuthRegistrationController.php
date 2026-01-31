<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OAuthRegistrationController extends Controller
{
    /**
     * Show role selection form
     */
    public function selectRoleForm()
    {
        $oauthUser = session('oauth_user');

        if (!$oauthUser) {
            return redirect()->route('login')
                ->with('flash.banner', 'Please try OAuth login again.');
        }

        return view('auth.oauth-select-role', ['oauthUser' => $oauthUser]);
    }

    /**
     * Handle role selection and create user
     */
    public function storeWithRole(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:seller,buyer',
            'age' => 'required|integer|min:13|max:150',
            'city' => 'required|string|max:30',
            'street' => 'required|string|max:100',
        ]);

        $oauthUser = session('oauth_user');

        if (!$oauthUser) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please try again.');
        }

        try {
            // Create new user
            $user = User::create([
                'name' => $oauthUser['name'],
                'email' => $oauthUser['email'],
                'google_id' => $oauthUser['google_id'],
                'provider_name' => 'google',
                'role' => $validated['role'],
                'age' => $validated['age'],
                'city' => $validated['city'],
                'street' => $validated['street'],
                'email_verified_at' => now(),
                'password' => bcrypt(str()->random(32)), // Random password for OAuth users
            ]);

            // Download and store profile photo to Jetstream's profile_photo_path
            $this->downloadProfilePhoto($user, $oauthUser['avatar_url']);

            // Log user in
            auth()->login($user, remember: true);

            // Clear OAuth session
            session()->forget('oauth_user');

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Account created successfully!');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to create account. Please try again.')
                ->withInput();
        }
    }

    /**
     * Download and store Google profile photo to user's profile_photo_path
     */
    private function downloadProfilePhoto(User $user, ?string $avatarUrl): void
    {
        if (!$avatarUrl) {
            return;
        }

        try {
            // Download image from Google
            $context = stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ],
                'ssl' => [
                    'verify_peer' => false,
                ]
            ]);

            $imageContent = @file_get_contents($avatarUrl, false, $context);
            if (!$imageContent) {
                return;
            }

            // Generate filename and store in profile-photos directory (Jetstream standard)
            $filename = $user->id . '-' . time() . '.jpg';
            $path = 'profile-photos/' . $filename;

            Storage::disk(config('filesystems.default'))->put($path, $imageContent);

            // Update user's profile_photo_path (Jetstream field)
            $user->update(['profile_photo_path' => $path]);

        } catch (\Exception $e) {
            // Silently fail - user can upload photo later
        }
    }
}
