<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\User;
class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, Media $media): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Media $media): bool
    {
        return false;
    }

    public function delete(User $user, Media $media): bool
    {
        return $this->isAdmin($user);
    }

    public function restore(User $user, Media $media): bool
    {
        return false;
    }

    public function forceDelete(User $user, Media $media): bool
    {
        return false;
    }

    protected function isAdmin(User $user): bool
    {
        return (bool) $user->is_admin;
    }
}
