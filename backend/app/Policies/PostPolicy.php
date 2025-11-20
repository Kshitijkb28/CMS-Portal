<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, Post $post): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Post $post): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, Post $post): bool
    {
        return $this->isAdmin($user);
    }

    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }

    protected function isAdmin(User $user): bool
    {
        return (bool) $user->is_admin;
    }
}
