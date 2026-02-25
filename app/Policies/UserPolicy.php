<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Ações administrativas para gestão de contas (ex.: Empresas).
     */
    public function manageCompanies(User $user): bool
    {
        return $user->isAdmin();
    }
}
