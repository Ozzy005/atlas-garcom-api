<?php

namespace App\Observers;

use App\Models\Category;
use App\Traits\Tenant;

class CategoryObserver
{
    use Tenant;

    public function creating(Category $category): void
    {
        $category->tenant_id = $this->getTenantId();
    }
}
