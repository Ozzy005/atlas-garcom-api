<?php

namespace App\Traits;

use App\Models\User;

trait TenantId
{
    public function getTenantId(): int | null
    {
        $user = User::query()
            ->find(auth()->id());

        if ($user->is_tenant->yes()) {
            $user->load('tenant');
            return $user->tenant->id;
        }

        if ($user->is_tenant_employee) {
            $user->load('employer');
            return $user->employer->id;
        }

        return null;
    }
}
