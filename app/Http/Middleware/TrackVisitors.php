<?php

namespace App\Http\Middleware;
use App\Models\VisitorModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;


class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->is('livewire/*') || $request->is('admin/*' || $request->is('/assets/*') || $request->is('/storage/*'))){
            return $next($request);
        }

        if (request()->hasCookie('uuid')) {
            $uuid = request()->cookie('uuid');
        }
         else {
            $uuid = Str::uuid()->toString();
            Cookie::queue('uuid', $uuid, 60 * 24 * 30);
        }

        // Ensure complete uniqueness for visitors 
        $fingerprint = $request->cookie('visitor_fingerprint');
        
      
        if (!$fingerprint) {

    // Generates a unique fingerprint that's stored in both 
    // the browser's cookies and in the database 
     $fingerprint = md5(
        $request->ip() .
        $request->server('HTTP_USER_AGENT') .
        $request->getRequestUri() .
        Str::uuid()->toString()
    );

    // Store fingerprint in cookie
    Cookie::queue('visitor_fingerprint', $fingerprint, 60 * 24 * 30); // Cookie valid for 30 days
}


            $existingVisitor = VisitorModel::where('unique_identifier', $fingerprint)->exists();
        
        if(!$existingVisitor){

        VisitorModel::create([
            'visitor_uuid'=>Str::uuid(),
            'visitor_ip_address'=>Crypt::encryptString($request->ip()),
            'visitor_user_agent'=>$request->server('HTTP_USER_AGENT') ?? '?',
            'visited_url'=>Str::before($request->getRequestUri(), '?'),
            'visitor_referrer'=>request()->headers->get('referrer'),
            'visited_at'=>Carbon::now(),
            'unique_identifier' => $fingerprint
        ]);
            }


        return $next($request);
    }
}
