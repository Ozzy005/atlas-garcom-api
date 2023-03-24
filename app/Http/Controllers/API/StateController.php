<?php

namespace App\Http\Controllers\API;

use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StateController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:states_view', ['only' => ['show', 'index']]);
    }

    public function index(Request $request): JsonResponse
    {
        $query = State::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('letter', 'like', '%' . $request->search . '%');
            })
            ->when(
                $request->filled('sortBy') && $request->filled('descending'),
                fn ($query) => $query->orderBy(
                    $request->sortBy,
                    filter_var($request->descending, FILTER_VALIDATE_BOOLEAN) ? 'desc' : 'asc'
                )
            );

        $data = $request->filled('page') ? $query->paginate($request->rowsPerPage ?? 10) : $query->get();

        return $this->sendResponse($data);
    }

    public function show($id): JsonResponse
    {
        $item = State::query()->findOrFail($id);

        return $this->sendResponse($item);
    }
}
