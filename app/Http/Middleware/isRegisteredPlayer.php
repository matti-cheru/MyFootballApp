<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isRegisteredPlayer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ((!isset($_SESSION['role']))||($_SESSION['role']!='registered_player')) {
            return response()->view('errors.404',['message' => 'Solo i giocatori registrati possono accedere a questa area!']);
        }
        return $next($request);
    }
}
