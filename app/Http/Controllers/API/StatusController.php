<?php

namespace App\Http\Controllers\API;

use App\Enums\Recurrence;
use App\Enums\RoleType;
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

    public function roleTypes()
    {
        return $this->sendResponse(RoleType::all());
    }

    public function recurrences()
    {
        return $this->sendResponse(Recurrence::all());
    }
}
