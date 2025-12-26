<?php

namespace App\Http\Controllers\Web\Core;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    /**
     * Show the public Index (Home) Page.
     *
     * @return View The public homepage view
     */
    public function showIndexPage(): View
    {
        $title = __("pages.index.page-title"); // Localized title for the index page

        return view('pages.core.index', compact('title')); // Load the index view
    }

    /**
     * Show the Login Page for guests.
     *
     * @return View The login view
     */
    public function showLoginPage(): View
    {
        $title = __("pages.login.name"); // Localized title for login

        return view('pages.core.guest.login', compact('title')); // Load guest login view
    }

    /**
     * Show the Register Page for new users.
     *
     * @return View The register view
     */
    public function showRegisterPage()
    {
        $title = __("pages.register.name"); // Localized title for registration

        return view('pages.core.guest.register', compact('title')); // Load guest register view
    }

    /**
     * Show the Forgot Password Page.
     *
     * @return View The password reset request form view
     */
    public function showForgetPasswordPage()
    {
        $title = __("pages.forgot_password.name"); // Localized title for password reset

        return view('pages.core.guest.forgetPassword', compact('title')); // Load forget password view
    }
}
