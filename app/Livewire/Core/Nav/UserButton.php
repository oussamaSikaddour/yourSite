<?php

namespace App\Livewire\Core\Nav;


use App\Models\Image;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserButton extends Component
{
    public ?string $userProfilePicUrl = null;
    public string $userName = '';
    public array $routes = [];
    public string $userDropdownLink = '';

    public function mount()
    {
        $this->initializeUserData();
    }

    public function refreshUserInfo()
    {
        // Clear the cache for the user profile picture
        cache()->forget('user_profile_pic_' . auth()->id());

        // Re-fetch and update the properties
        $this->initializeUserData();
    }

    protected function initializeUserData()
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        $this->routes = $this->getUserRoutes();
          $this->userName = $user->name ?? 'Hopeless Wanderer'; ;
        $this->userProfilePicUrl = $this->getUserProfilePicUrl();
        $this->userDropdownLink = $this->buildUserDropdownLink();
    }

    protected function getUserRoutes(): array
    {
        return [
            [
                'route' =>"profile",
                'label' => __('pages.profile.name'),
                'icon' => 'profile',
            ],
            [
                'route' => 'change_password',
                'label' => __('pages.change_password.name'),
            ],
            [
                'route' =>'change_email' ,
                'label' => __('pages.change_email.name'),
            ],
            [
                'route' =>'log_out' ,
                'label' => __('pages.logout'),
                'icon' => 'logout',
            ],
        ];
    }

    protected function getUserProfilePicUrl(): string
    {
        return cache()->remember('user_profile_pic_' . auth()->id(), 3600, function () {
            $profilePic = Image::where('imageable_id', auth()->id())
                ->where('imageable_type', 'App\Models\User')
                ->where('use_case', 'avatar')
                ->first();

            return $profilePic?->url ?? asset("assets/core/images/utils/avatar.png");
        });
    }

    protected function buildUserDropdownLink(): string
    {
        $userImage = '<img src="' . $this->userProfilePicUrl . '" alt="' . __('Profile Picture') . '">';
        return $this->userName . $userImage;
    }

    public function render()
    {
        return view('livewire.core.nav.user-button');
    }
}
