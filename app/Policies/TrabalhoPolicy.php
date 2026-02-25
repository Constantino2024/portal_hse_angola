<?php

namespace App\Policies;

use App\Models\Trabalho;
use App\Models\User;

class TrabalhoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isEditor();
    }

    public function view(User $user, Trabalho $job): bool
    {
        return $user->isEditor();
    }

    public function create(User $user): bool
    {
        return $user->isEditor();
    }

    public function update(User $user, Trabalho $job): bool
    {
        return $user->isEditor();
    }

    public function delete(User $user, Trabalho $job): bool
    {
        return $user->isEditor();
    }
}
