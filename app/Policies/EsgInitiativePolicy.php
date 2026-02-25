<?php

namespace App\Policies;

use App\Models\EsgInitiative;
use App\Models\User;

class EsgInitiativePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isEditor() || $user->isCompany();
    }

    public function view(User $user, EsgInitiative $initiative): bool
    {
        return $user->isEditor() || ($user->isCompany() && $initiative->user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->isCompany() || $user->isEditor();
    }

    public function update(User $user, EsgInitiative $initiative): bool
    {
        return $user->isEditor() || ($user->isCompany() && $initiative->user_id === $user->id);
    }

    public function delete(User $user, EsgInitiative $initiative): bool
    {
        return $user->isEditor() || ($user->isCompany() && $initiative->user_id === $user->id);
    }
}
