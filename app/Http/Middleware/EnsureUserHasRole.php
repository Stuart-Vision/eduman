<?php

namespace App\Http\Middleware;

use App\Enums\UserStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route middleware: role:admin  |  role:admin,teacher
 * Also blocks deactivated accounts globally.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Deactivated accounts are signed out immediately, whatever their role.
        if ($user->status === UserStatus::Inactive) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been deactivated. Contact the administrator.']);
        }

        if (! empty($roles) && ! $user->hasRole(...$roles)) {
            abort(403, 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}
