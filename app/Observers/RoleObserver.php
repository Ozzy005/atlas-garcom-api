<?php

namespace App\Observers;

use App\Models\Role;
use App\Traits\Tenant;

class RoleObserver
{
    use Tenant;

    public function creating(Role $role): void
    {
        $role->tenant_id = $this->getTenantId();
    }
}
