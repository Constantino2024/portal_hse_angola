<?php

namespace App\Policies;

use App\Models\EducationalContent;
use App\Models\User;

class EducationalContentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isEditor();
    }

    public function view(User $user, EducationalContent $content): bool
    {
        return $user->isEditor();
    }

    public function create(User $user): bool
    {
        return $user->isEditor();
    }

    public function update(User $user, EducationalContent $content): bool
    {
        return $user->isEditor();
    }

    public function delete(User $user, EducationalContent $content): bool
    {
        return $user->isEditor();
    }
}
