<?php

namespace App\Traits;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

trait JwtHandler
{
    /**
     * Handle Exception of JWT
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    protected function handleJwtException($exception)
    {
        if ($exception instanceof TokenExpiredException) {
            return $this->errorResponse('Token has expired', 401);
        }

        if ($exception instanceof TokenInvalidException) {
            return $this->errorResponse('Token is invalid', 401);
        }

        if ($exception instanceof JWTException) {
            return $this->errorResponse('Token is not provided', 401);
        }

        return $this->errorResponse('Unauthorized', 401);
    }
}
