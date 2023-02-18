<?php

namespace App\Http\Controllers\API;

use App\Enums\Status;
use App\Enums\TenantStatus;

class StatusController extends BaseController
{
    public function status()
    {
        return $this->sendResponse(Status::all());
    }

    public function tenantStatus()
    {
        return $this->sendResponse(TenantStatus::all());
    }
}
