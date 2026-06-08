<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;
use App\Models\Country;

class DetectUserCountry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user already has a preferred country cookie
        $countrySlug = $request->cookie('user_country');
        $userCountry = null;

        if ($countrySlug) {
            $userCountry = Country::where('slug', $countrySlug)->first();
        }

        // 2. If not, try to detect by IP
        if (!$userCountry) {
            $ip = $request->ip();
            
            // For local testing, simulate an IP or just default to Argentina/US
            if (in_array($ip, ['127.0.0.1', '::1'])) {
                 // Default to Argentina for testing as per context
                 $userCountry = Country::where('code', 'AR')->first(); 
            } else {
                try {
                    // Use ip-api.com (free, no key required for low volume)
                    $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}");
                    if ($response->successful()) {
                        $data = $response->json();
                        if (isset($data['countryCode'])) {
                            $userCountry = Country::where('code', $data['countryCode'])->first();
                        }
                    }
                } catch (\Exception $e) {
                    // Fail silently
                }
            }
        }

        // 3. Share with all views and Request
        if ($userCountry) {
            View::share('userCountry', $userCountry);
            $request->attributes->set('userCountry', $userCountry);
        }

        return $next($request);
    }
}
