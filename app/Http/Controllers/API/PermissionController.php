<?php

namespace App\Http\Controllers\API;

use App\Exceptions\HttpException;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:permissions_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permissions_view', ['only' => ['show', 'index']]);
    }

    public function index(Request $request): JsonResponse
    {
        $query = Permission::query()
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
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

    public function permissionsToTree(): JsonResponse
    {
        $user = User::query()->findOrFail(auth()->id());

        $data = Permission::query()
            ->whereIn('id', $user->getAllPermissions()->pluck('id')->toArray())
            ->get()
            ->toTree();

        return $this->sendResponse($data);
    }

    public function userPermissions(): JsonResponse
    {
        $user = User::query()->findOrFail(auth()->id());

        $permissions = $user->getPermissionsViaRoles();

        return $this->sendResponse($permissions);
    }

    public function show(int $id): JsonResponse
    {
        $item = Permission::query()->findOrFail($id);

        return $this->sendResponse($item);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $item = Permission::query()->findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, $item->id)
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }

            DB::beginTransaction();

            $item->fill(['description' => $request->description])->save();

            DB::commit();
            return $this->sendResponse([], 'Registro editado com sucesso!');
        } catch (\Throwable $th) {
            $msg = 'Erro interno do servidor!';
            $code = 500;
            $errors = [];

            if ($th instanceof HttpException) {
                $msg = $th->getMessage();
                $code = $th->getCode();
                $errors = $th->getErrors();
            }

            return $this->sendError($msg, $errors, $code);
        }
    }

    private function rules(Request $request, int | null $primaryId = null, bool $changeMessages = false)
    {
        $rules = [
            'description' => ['required', 'string', 'max:125']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
