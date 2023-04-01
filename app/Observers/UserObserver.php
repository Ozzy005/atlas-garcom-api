<?php

namespace App\Observers;

use App\Models\User;
use App\Traits\TenantId;

class UserObserver
{
    use TenantId;

    public function creating(User $user): void
    {
        $user->employer_id = $this->getTenantId();
    }
}
