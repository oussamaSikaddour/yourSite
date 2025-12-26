<?php

namespace App\Policies\Core;

use App\Models\User;

class UserPolicy
{
    /**
     * Check if the user is the owner of the given model.
     */
private function onlyOwner(User $user, User $model): bool
    {
        return $user->is($model);
    }

    /**
     * Check if the user has a specific role.
     */
    private function hasRole(User $user, string $roleSlug): bool
    {
        return $user->roles->contains('slug', $roleSlug);
    }

    /**
     * Check if the user has any of the specified roles.
     */
    private function hasAnyRole(User $user, array $rolesSlugs): bool
    {
        return $user->roles->pluck('slug')->intersect($rolesSlugs)->isNotEmpty();
    }

    /**
     * Specific role-checking methods for convenience.
     */
    public function isAdmin(User $user): bool
    {
        return $this->hasRole($user, 'admin');
    }
    public function isAuthor(User $user): bool
    {
        return $this->hasRole($user, 'author');
    }

    public function isUser(User $user): bool
    {
        return $this->hasRole($user, 'user');
    }

    public function isSuperAdmin(User $user): bool
    {
        return $this->hasRole($user, 'super_admin');
    }


    public function isApprover(User $user): bool
    {
        return $this->hasRole($user, 'approver');
    }
    public function isSocialAdmin(User $user): bool
    {
        return $this->hasRole($user, 'social_admin');
    }
    public function isAdminOrAuthor(User $user):bool{
    $roles=['admin', 'author'];
     return $this->hasAnyRole($user,$roles);
    }


}
