<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and add security headers to the response.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // X-Frame-Options: Prevent clickjacking attacks
        // SAMEORIGIN allows framing only from same origin
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // X-Content-Type-Options: Prevent MIME type sniffing
        // Forces browser to respect declared Content-Type
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection: Enable XSS filter in older browsers
        // Modern browsers use CSP instead, but this adds defense in depth
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy: Control referrer information
        // strict-origin-when-cross-origin provides good balance of privacy and functionality
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content-Security-Policy: Mitigate XSS and data injection attacks
        $csDirectives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http:",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com http:",
            "img-src 'self' data: https: blob: http:",
            "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net data:",
            "connect-src 'self' https://api.stripe.com http:",
            "frame-src 'self' https://js.stripe.com https://hooks.stripe.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ];

        // Allow Vite dev server scripts/HMR in local development without listing hosts
        if (app()->environment(['local', 'development'])) {
            $csDirectives[1] = "script-src * 'unsafe-inline' 'unsafe-eval' http: https: data: blob:";
            $csDirectives[2] = "style-src * 'unsafe-inline' http: https:";
            $csDirectives[5] = "connect-src * http: https: ws: wss:";
        }
        
        // Only upgrade insecure requests and apply strict CSP in production
        if (app()->environment('production')) {
            $csDirectives[] = "upgrade-insecure-requests";
        }
        
        $csp = implode('; ', $csDirectives);
        $response->headers->set('Content-Security-Policy', $csp);

        // Permissions-Policy: Control browser features and APIs
        // Restrict access to sensitive features
        $permissionsPolicy = implode(', ', [
            'geolocation=()',
            'microphone=()',
            'camera=()',
            'payment=(self)',
            'usb=()',
            'magnetometer=()',
            'gyroscope=()',
            'accelerometer=()'
        ]);
        $response->headers->set('Permissions-Policy', $permissionsPolicy);

        // Strict-Transport-Security (HSTS): Enforce HTTPS
        // Only enable in production with valid SSL certificate
        if (app()->environment('production') && $request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        return $response;
    }
}
