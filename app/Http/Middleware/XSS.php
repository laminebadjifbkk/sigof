<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /*     public function handle(Request $request, Closure $next): Response
    {
        $userInput = $request->all();
        array_walk_recursive($userInput, function (&$userInput) {
            $userInput = strip_tags($userInput);
        });
        $request->merge($userInput);
        return $next($request);
    } */

    public function handle(Request $request, Closure $next): Response
    {
        $userInput = $request->all();

        array_walk_recursive($userInput, function (&$userInput) {
            $userInput = strip_tags($userInput);
        });
        $request->merge($userInput);
        return $next($request);
        
        /*  array_walk_recursive($userInput, function (&$value, $key) {
            // On exclut les champs qui doivent garder du HTML
            if ($key !== 'projetprofessionnel') {
                $value = strip_tags($value);
            }
        }); */

        // Champs autorisés à garder du HTML
        /* $except = ['projetprofessionnel', 'description'];

        array_walk_recursive($userInput, function (&$value, $key) use ($except) {
            if (!in_array($key, $except)) {
                $value = strip_tags($value);
            }
        });

        $request->merge($userInput);

        return $next($request); */
    }
}
