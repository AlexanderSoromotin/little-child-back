<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Проверяем, авторизован ли пользователь
        if (Auth::check()) {

            // Проверяем, является ли пользователь администратором
            if (Auth::user()->role()->name == 'admin') {
                return $next($request);
            }

            return response(['message' => 'Access denied.'], 401);
        }

        // Если пользователь не администратор, перенаправляем его или выполняем другие действия
        return response(['message' => 'Invalid access token.'], 403);
    }
}
