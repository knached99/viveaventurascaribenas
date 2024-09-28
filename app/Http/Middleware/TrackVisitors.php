<?php

namespace App\Http\Middleware;

use App\Models\VisitorModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for authentication
        if (Auth::check()) {
            return $next($request);
        }

        // Skip logging for excluded paths
        $excludedPaths = [
           // 'livewire/*',
            'admin/*',
            'assets/*',
            'storage/*',
            'livewire/*',
            'api/*',
            'login',
            'register',
            'password.request',
            'password.reset',
            'verification.notice',
            'verification.verify',
            'password.confirm',
            '*.css', '*.js', '*.png', '*.jpg', '*.jpeg', '*.gif', '*.svg', '*.woff', '*.woff2', '*.ttf', '*.ico',
            'node_modules',

        ];

        if($request->is($excludedPaths) || strpos(url()->previous(), '/admin') !== false){
            return $next($request);
        }
     

        // Retrieve or create the UUID for the visitor
        $uuid = $request->hasCookie('uuid') ? $request->cookie('uuid') : Str::uuid()->toString();
        Cookie::queue('uuid', $uuid, 60 * 24 * 30);

        // Ensure complete uniqueness for visitors
        $fingerprint = $request->cookie('visitor_fingerprint');
        if (!$fingerprint) {
            $fingerprint = md5(
                $request->ip() .
                $request->server('HTTP_USER_AGENT') .
                $request->getRequestUri() .
                Str::uuid()->toString()
            );
            Cookie::queue('visitor_fingerprint', $fingerprint, 60 * 24 * 30); // Cookie valid for 30 days
        }

        // Detect the device type from the user agent string
        $userAgent = $request->server('HTTP_USER_AGENT') ?? '?';

        // Check if visitor already exists
        $existingVisitor = VisitorModel::where('unique_identifier', $fingerprint)->exists();
        if (!$existingVisitor) {
            VisitorModel::create([
                'visitor_uuid' => Str::uuid(),
                'visitor_ip_address' => Crypt::encryptString($request->ip()),
                'visitor_user_agent' => $userAgent,
                'visited_url' => Str::before($request->getRequestUri(), '?'),
                'visitor_referrer' => $request->headers->get('referer'),
                'visited_at' => Carbon::now(),
                'unique_identifier' => $fingerprint,
                //'device_type' => $deviceType,  // Add the device type here
            ]);
        }

        return $next($request);
    }
}
