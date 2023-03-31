<?php

namespace App\Http\Controllers\API;

use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:cities_view', ['only' => ['show', 'index']]);
    }

    public function publicIndex(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    public function index(Request $request): JsonResponse
    {
        $query = City::stateQuery()
            ->when($request->filled('search'), fn (Builder $query) =>  $query->where('cities.title', 'like', '%' . $request->search . '%'))
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
        $item = City::stateQuery()->findOrFail($id);

        return $this->sendResponse($item);
    }
}
