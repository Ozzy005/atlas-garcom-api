<?php

namespace App\Observers;

use App\Models\Role;
use App\Models\User;

class RoleObserver
{
    public function creating(Role $role): void
    {
        $userAuthenticated = User::query()
            ->find(auth()->id());

        if ($userAuthenticated->is_tenant->yes()) {
            $userAuthenticated->load('tenant');
            $role->tenant_id = $userAuthenticated->tenant->id;
        } else if ($userAuthenticated->is_tenant_employee) {
            $userAuthenticated->load('employer');
            $role->tenant_id = $userAuthenticated->employer->id;
        }
    }
}
