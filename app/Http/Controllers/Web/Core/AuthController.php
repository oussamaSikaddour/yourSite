<?php

namespace App\Http\Controllers\Web\Core;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Log the user out of the application.
     *
     * @param Request $request The incoming HTTP request
     * @return \Illuminate\Http\RedirectResponse Redirects the user to the homepage
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Log the user out (clears the authentication state)

        $request->session()->invalidate(); // Invalidate the current session

        $request->session()->regenerateToken(); // Regenerate the CSRF token for security

        // Redirect the user to the home page (or any custom route if needed)
        return redirect('/');
    }


}
