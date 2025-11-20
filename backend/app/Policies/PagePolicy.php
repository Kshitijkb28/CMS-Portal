<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;
class PagePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, Page $page): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Page $page): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, Page $page): bool
    {
        return $this->isAdmin($user);
    }

    public function restore(User $user, Page $page): bool
    {
        return false;
    }

    public function forceDelete(User $user, Page $page): bool
    {
        return false;
    }

    protected function isAdmin(User $user): bool
    {
        return (bool) $user->is_admin;
    }
}
