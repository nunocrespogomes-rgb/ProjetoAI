<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;

// app/Http/Middleware/AdminOrEmployee.php
class AdminOrEmployee
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->isAdmin() || $user->isEmployee()) {
            return $next($request);
        }

        throw new AuthorizationException();
    }
}
