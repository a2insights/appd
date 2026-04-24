<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordReset
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't intercept Livewire's own requests
        if ($request->hasHeader('X-Livewire')) {
            return $next($request);
        }

        if (auth()->check() && auth()->user()->force_password_reset) {
            $panelId = \Filament\Facades\Filament::getCurrentPanel()?->getId();
            $routeName = "filament.{$panelId}.pages.force-password-reset";

            if (! $request->routeIs($routeName)) {
                return redirect()->route($routeName);
            }
        }

        return $next($request);
    }
}
