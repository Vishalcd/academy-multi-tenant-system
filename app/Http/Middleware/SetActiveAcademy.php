<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetActiveAcademy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // If user is Admin → allow switching academy via request/session
        if ($user->role === 'admin') {
            // Check if request contains a new academy ID to switch to
            if ($request->has('switch_academy_id')) {
                session(['active_academy_id' => $request->input('switch_academy_id')]);
            }

            // If no academy selected yet, default to first or null
            if (! session()->has('active_academy_id')) {
                session(['active_academy_id' => null]);
            }
        }

        // If Manager → force their assigned academy
        elseif ($user->role === 'manager') {
            session(['active_academy_id' => $user->academy_id]);
        }

        // Coach, Student → must already be scoped via academy_id in user table
        elseif (in_array($user->role, ['employee', 'student'])) {
            session(['active_academy_id' => $user->academy_id]);
        }

        return $next($request);
    }
}
