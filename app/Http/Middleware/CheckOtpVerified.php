<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckOtpVerified
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (is_null($user->otp) && is_null($user->otp_expiry)) {
            return $next($request);
        }

        return response()->json(['error' => 'OTP verification required'], 403);
    }
}
