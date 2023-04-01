<?php

namespace App\Http\Controllers\API;

use App\Enums\Recurrence;
use App\Enums\RoleType;
use App\Enums\Status;
use App\Enums\TenantStatus;
use App\Traits\TenantId;
use Illuminate\Http\JsonResponse;

class EnumController extends BaseController
{
    use TenantId;

    public function index(): JsonResponse
    {
        $enums = [];

        $enums['status'] = Status::all();
        $enums['tenant-status'] = TenantStatus::all();
        $enums['role-types'] = $this->getTenantId() ? RoleType::all([RoleType::ROLE]) : RoleType::all();
        $enums['recurrences'] = Recurrence::all();

        return $this->sendResponse($enums);
    }
}
