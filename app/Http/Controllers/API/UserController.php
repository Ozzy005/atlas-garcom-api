<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:users_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:users_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
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
            $inputs['password'] = Hash::make($inputs['password']);

            $user = User::query()->create($inputs);
            $user->roles()->sync($inputs['roles']);

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
        $item = User::query()
            ->with('roles')
            ->findOrFail($id);

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
        $item = User::query()->findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, $item->id)
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de Validação !', $validator->errors()->toArray(), 422);
        }

        try {
            DB::beginTransaction();

            $inputs = $request->all();

            $item->fill($inputs)->save();
            $item->roles()->sync($inputs['roles']);

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
        $item = User::query()->findOrFail($id);

        try {
            DB::beginTransaction();

            if ($item->id == auth()->id()) {
                return $this->sendError('Não é possível excluir seu próprio usuário !', [], 403);
            } else if ($item->id == 1) {
                return $this->sendError('Não é possível excluir o usuário administrador !', [], 403);
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
            'name' => ['required', 'string', 'max:60'],
            'email' => ['required', 'string', 'max:100', Rule::unique('users')->ignore($primaryKey)],
            'password' => ['confirmed', Rule::requiredIf(fn () => $request->isMethod('post')), Rules\Password::defaults()],
            'roles' => ['required', 'array'],
            'roles.*' => ['required', Rule::exists('roles', 'id')]
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
