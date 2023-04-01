<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:dashboard_view');
    }

    public function __invoke(Request $request): JsonResponse
    {
        return $this->sendResponse($request->user());
    }
}
