<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user): void
    {
        $userAuthenticated = User::query()
            ->find(auth()->id());

        if ($userAuthenticated->is_tenant->yes()) {
            $userAuthenticated->load('tenant');
            $user->employer_id = $userAuthenticated->tenant->id;
        } else if ($userAuthenticated->is_tenant_employee) {
            $userAuthenticated->load('employer');
            $user->employer_id = $userAuthenticated->employer->id;
        }
    }
}
