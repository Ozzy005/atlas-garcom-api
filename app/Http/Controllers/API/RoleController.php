<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:roles_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:roles_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Role::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules($request)
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de Validação !', $validator->errors()->toArray(), 422);
        }

        try {
            DB::beginTransaction();

            $inputs = $request->all();

            $role = Role::query()->create($inputs);
            $role->permissions()->sync($inputs['permission_ids']);

            DB::commit();
            return $this->sendResponse([], 'Registro criado com sucesso !', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $item = Role::query()
            ->with('permissions')
            ->findOrFail($id);

        $item->permission_ids = $item->permissions->pluck('id');

        return $this->sendResponse($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $item = Role::query()->findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, $item->id)
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de Validação !', $validator->errors()->toArray(), 422);
        }

        if ($item->name == 'administrator') {
            return $this->sendError('Não é possível editar a atribuição do administrador !', [], 403);
        }

        try {
            DB::beginTransaction();

            $inputs = $request->all();

            $item->fill($inputs)->save();
            $item->permissions()->sync($inputs['permission_ids']);

            DB::commit();
            return $this->sendResponse([], 'Registro editado com sucesso !');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $item = Role::query()
            ->with('permissions', 'users')
            ->findOrFail($id);

        try {
            DB::beginTransaction();

            if ($item->permissions->isNotEmpty()) {
                return $this->sendError('Não é possível deletar pois existem permissões vinculadas a esta atribuição !', [], 403);
            } else if ($item->users->isNotEmpty()) {
                return $this->sendError('Não é possível deletar pois existem usuários vinculados a esta atribuição !', [], 403);
            } else if ($item->name == 'administrator') {
                return $this->sendError('Não é possível excluir a atribuição do administrador !', [], 403);
            }

            $item->delete();

            DB::commit();
            return $this->sendResponse([], 'Registro deletado com sucesso !');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError('Registro vinculado à outra tabela, somente poderá ser excluído se retirar o vínculo !');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'string', 'max:125', Rule::unique('roles')->ignore($primaryKey)],
            'description' => ['required', 'string', 'max:125'],
            'permission_ids' => ['array', Rule::requiredIf(fn () => $request->isMethod('post'))],
            'permission_ids.*' => [Rule::requiredIf(fn () => $request->isMethod('post')), Rule::exists('permissions', 'id')],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
