<?php

namespace App\Observers;

use App\Models\Role;
use App\Traits\TenantId;

class RoleObserver
{
    use TenantId;

    public function creating(Role $role): void
    {
        $role->tenant_id = $this->getTenantId();
    }
}
