<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateApiTokenOnLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Revoke all previous tokens
        $user->tokens()->delete();

        // Assign role-based scopes
        $abilities = $user->role === 'seller'
            ? [
                // Seller abilities
                'products:read',
                'products:create',
                'products:update',
                'products:delete',
                'orders:read',
                'orders:update-status',
                'dashboard:read',
                'profile:read',
                'profile:update',
            ]
            : [
                // Buyer abilities
                'products:read',
                'cart:read',
                'cart:write',
                'orders:read',
                'checkout:process',
                'profile:read',
                'profile:update',
            ];

        // Create token with role-based abilities
        $token = $user->createToken('web-session-token', $abilities);

        // Store token in session
        Session::put('api_token', $token->plainTextToken);
    }
}
