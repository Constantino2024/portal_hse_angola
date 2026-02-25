<?php

namespace App\Policies;

use App\Models\AgendaItem;
use App\Models\User;

class AgendaItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isEditor();
    }

    public function view(User $user, AgendaItem $item): bool
    {
        return $user->isEditor();
    }

    public function create(User $user): bool
    {
        return $user->isEditor();
    }

    public function update(User $user, AgendaItem $item): bool
    {
        return $user->isEditor();
    }

    public function delete(User $user, AgendaItem $item): bool
    {
        return $user->isEditor();
    }
}
