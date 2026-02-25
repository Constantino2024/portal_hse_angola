<?php

namespace App\Policies;

use App\Models\CompanyProject;
use App\Models\User;

class CompanyProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isEditor() || $user->isCompany();
    }

    public function view(User $user, CompanyProject $project): bool
    {
        return $user->isEditor() || ($user->isCompany() && $project->user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->isCompany() || $user->isEditor();
    }

    public function update(User $user, CompanyProject $project): bool
    {
        return $user->isEditor() || ($user->isCompany() && $project->user_id === $user->id);
    }

    public function delete(User $user, CompanyProject $project): bool
    {
        return $user->isEditor() || ($user->isCompany() && $project->user_id === $user->id);
    }
}
