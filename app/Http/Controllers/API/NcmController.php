<?php

namespace App\Http\Controllers\API;

use App\Models\Ncm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NcmController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:ncms_view', ['only' => ['show', 'index']]);
    }

    public function index(Request $request): JsonResponse
    {
        $query = Ncm::query()
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $query->where('code', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->when(
                $request->filled('sortBy') && $request->filled('descending'),
                fn (Builder $query) => $query->orderBy(
                    $request->sortBy,
                    filter_var($request->descending, FILTER_VALIDATE_BOOLEAN) ? 'desc' : 'asc'
                )
            );

        $data = $request->filled('page') ? $query->paginate($request->rowsPerPage ?? 10) : $query->get();

        return $this->sendResponse($data);
    }

    public function show($id): JsonResponse
    {
        $item = Ncm::query()->findOrFail($id);

        return $this->sendResponse($item);
    }
}
