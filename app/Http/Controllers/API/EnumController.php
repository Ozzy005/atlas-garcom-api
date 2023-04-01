<?php

namespace App\Http\Controllers\API;

use App\Enums\Recurrence;
use App\Enums\RoleType;
use App\Enums\Status;
use App\Enums\TenantStatus;
use Illuminate\Http\JsonResponse;

class EnumController extends BaseController
{
    public function index(): JsonResponse
    {
        $enums = [];

        $enums['status'] = Status::all();
        $enums['tenant-status'] = TenantStatus::all();
        $enums['role-types'] = RoleType::all();
        $enums['recurrences'] = Recurrence::all();

        return $this->sendResponse($enums);
    }
}
