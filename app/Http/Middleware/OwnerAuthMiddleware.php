<?php

namespace App\Http\Middleware;

use App\Models\Accommodation_details;
// use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OwnerAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {   Log::info('Unauthenticated owner access attempt', ['route' => $request->path()]);
        if (Auth::guard('owner')->guest()) {

            return response()->json(['error' => 'Unauthenticated as Owner.'], 401);
        }
        // if (!auth('owner')->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        if($request->method() == 'put' || $request->method() == 'delete') {
            $accommodation = Accommodation_details::where('accommodation_id', $request->route('id'))->first();
            if (!$accommodation) {
                return response()->json(['error' => 'Accommodation not found'], 404);
            } 
            $accommodation = $accommodation->where('owner_id', auth('owner')->id());
            if (!$accommodation) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }
        return $next($request);
    }
}
