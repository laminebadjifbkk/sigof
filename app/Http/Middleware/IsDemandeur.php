<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsDemandeur
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  
     public function handle(Request $request, Closure $next)
     {
         // Vérifier si l'utilisateur est authentifié et s'il a le rôle "admin"
         if (auth()->check() && auth()->user()->hasRole('Demandeur')) {
             return $next($request); // L'utilisateur est un admin, il peut continuer
         }
 
                               // Si ce n'est pas un admin, rediriger l'utilisateur
         return redirect('/'); // Redirection vers la page d'accueil ou une autre page
     }
}
