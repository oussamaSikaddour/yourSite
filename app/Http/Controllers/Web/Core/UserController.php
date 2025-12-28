<?php

namespace App\Http\Controllers\Web\Core;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show the user home (dashboard) page.
     *
     * @return View The view for the user home area
     */
public function showDashboard(Request $request)
{
    $userName = Auth::user()->name;
    $title = __("pages.dashboard.name"); // Localized dashboard title

    return view('pages.core.user.dashboard', compact('title', 'userName'));
}

    /**
     * Show the user's profile page.
     *
     * @return View The view for editing/viewing the profile
     */
    public function showProfilePage(): View
    {
        // Localized title for the profile page
        $title = __("pages.profile.name");

        // Load the user profile view
        return view('pages.core.user.profile', compact('title'));
    }

    /**
     * Show the change password page.
     *
     * @return View The view for changing user password
     */
    public function showChangePasswordPage(): View
    {
        // Localized title for the change password page
        $title = __("pages.change_password.name");

        // Load the change password view
        return view('pages.core.user.change-password', compact('title'));
    }

    /**
     * Show the change email page.
     *
     * @return View The view for changing user email address
     */
    public function showChangeEmailPage(): View
    {
        // Localized title for the change email page
        $title = __("pages.change_email.name");

        // Load the change email view
        return view('pages.core.user.change-email', compact('title'));
    }
}
