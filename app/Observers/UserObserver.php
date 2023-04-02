<?php

namespace App\Observers;

use App\Models\User;
use App\Traits\Tenant;

class UserObserver
{
    use Tenant;

    public function creating(User $user): void
    {
        $user->employer_id = $this->getTenantId();
    }
}
