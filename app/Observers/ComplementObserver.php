<?php

namespace App\Observers;

use App\Models\Complement;
use App\Traits\Tenant;

class ComplementObserver
{
    use Tenant;

    public function creating(Complement $complement): void
    {
        $complement->tenant_id = $this->getTenantId();
    }
}
