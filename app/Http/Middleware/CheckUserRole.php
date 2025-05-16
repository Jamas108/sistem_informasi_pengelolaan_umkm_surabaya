<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|array  $roles
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Get the authenticated user
        $user = Auth::user();

        // Convert single role to array if needed
        $roles = is_array($roles) ? $roles : [$roles];

        // Check if the user's role matches any of the allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect based on user's actual role
        return $this->redirectBasedOnRole($user->role);
    }

    /**
     * Redirect user to their appropriate dashboard based on role
     *
     * @param string $role
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnRole(string $role)
    {
        // Use route names from LoginController's redirectPath method
        return match($role) {
            'adminkantor' => redirect()->route('dashboard.admin_kantor')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini'),
            'adminlapangan' => redirect()->route('dashboard.admin_lapangan')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini'),
            'pelakuumkm' => redirect()->route('dashboard.pelaku_umkm')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini'),
            default => redirect('/login')
                ->with('error', 'Akses tidak diizinkan')
        };
    }
}