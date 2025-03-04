<?php

namespace App\Http\Controllers\API;

use App\Enums\RoleType;
use App\Exceptions\HttpException;
use App\Models\Role;
use App\Traits\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class RoleController extends BaseController
{
    use Tenant;

    public function __construct()
    {
        $this->middleware('permission:roles_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:roles_delete', ['only' => ['destroy']]);
    }

    public function publicIndex(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    public function index(Request $request): JsonResponse
    {
        $query = Role::query()
            ->tenantQuery()
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('type'), fn (Builder $query) => $query->where('type', $request->type))
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

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules($request)
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }

            DB::beginTransaction();

            $inputs = $request->all();
            $role = Role::query()->create($inputs);
            $role->permissions()->sync($inputs['permissions_ids']);

            DB::commit();
            return $this->sendResponse([], 'Registro criado com sucesso!', 201);
        } catch (\Throwable $th) {
            $msg = 'Erro interno do servidor!';
            $msg = $th->getMessage();
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

    public function show(Request $request, int $id): JsonResponse
    {
        $item = Role::query()
            ->when($request->filled('with'), fn (Builder $query) => $query->with($request->with))
            ->findOrFail($id);

        return $this->sendResponse($item);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $item = Role::query()
            ->tenantQuery()
            ->findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, $item->id)
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }
            if ($item->name == 'administrator') {
                throw new HttpException('Não é possível editar a atribuição do administrador!', [], 403);
            }

            DB::beginTransaction();

            $inputs = $request->all();
            $item->fill($inputs)->save();
            $item->permissions()->sync($inputs['permissions_ids']);

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

    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'items' => ['required', 'array', 'min:1'],
                'items.*' => ['required', 'integer', Rule::exists('roles', 'id')]
            ]
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }
            if (in_array(1, $request->items)) {
                throw new HttpException('Não é possível excluir a atribuição do administrador!', [], 403);
            }

            DB::beginTransaction();

            $items = Role::query()
                ->tenantQuery()
                ->with('permissions', 'users')
                ->whereIn('id', $request->items)
                ->get();

            $model = null;
            foreach ($items as $item) {
                $model = $item;
                if ($item->permissions->isNotEmpty()) {
                    throw new HttpException("Não é possível deletar pois existem permissões vinculadas a atribuição $model->id!", [], 403);
                }
                if ($item->users->isNotEmpty()) {
                    throw new HttpException("Não é possível deletar pois existem usuários vinculados a atribuição $model->id!", [], 403);
                }
                $item->delete();
            }

            DB::commit();
            return $this->sendResponse([], 'Registros deletados com sucesso!');
        } catch (\Throwable $th) {
            $model = $model->id ?? null;
            $msg = "O registro $model está vinculado a outra tabela. Por favor, remova o vínculo antes de excluir!";
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
            'name' => [
                'required', 'string', 'max:125',
                Rule::unique('roles')
                    ->where('tenant_id', $this->getTenantId())
                    ->ignore($primaryId)
            ],
            'description' => ['required', 'string', 'max:125'],
            'type' => ['required', 'integer', new Enum(RoleType::class)],
            'permissions_ids' => ['array', Rule::requiredIf(fn () => $request->isMethod('post'))],
            'permissions_ids.*' => ['integer', Rule::requiredIf(fn () => $request->isMethod('post')), Rule::exists('permissions', 'id')]
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
