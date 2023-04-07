<?php

namespace App\Observers;

use App\Models\Product;
use App\Traits\Tenant;

class ProductObserver
{
    use Tenant;

    public function creating(Product $product): void
    {
        $product->tenant_id = $this->getTenantId();
    }
}
