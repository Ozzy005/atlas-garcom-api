<?php

namespace App\Http\Controllers\API;

use App\Exceptions\HttpException;
use App\Http\Requests\PersonRequest;
use App\Models\Person;
use App\Models\Tenant;
use App\Models\User;
use App\Rules\modelPersonRelationship;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class TenantController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:tenants_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:tenants_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tenants_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:tenants_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Tenant::personQuery()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->search . '%')
                    ->orWhere('nif', 'like', '%' . removeMask($request->search) . '%')
                    ->orWhere('people.email', 'like', '%' . $request->search . '%');
            })
            ->when(
                $request->filled('sortBy') && $request->filled('descending'),
                fn ($query) => $query->orderBy(
                    in_array($request->sortBy, ['email']) ? "people.$request->sortBy" : $request->sortBy,
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
    public function store(PersonRequest $request): JsonResponse
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

            $person = Person::query()
                ->updateOrCreate(['nif' => $inputs['nif']], $inputs);

            $inputs['person_id'] = $person->id;
            Tenant::query()->create($inputs);

            $inputs['name'] = $person->full_name;
            $inputs['password'] = Hash::make($inputs['nif']);
            $user = User::query()->create($inputs);
            $user->assignRole('tenant');

            DB::commit();
            return $this->sendResponse([], 'Registro criado com sucesso!', 201);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $item = Tenant::personQuery()
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
    public function update(PersonRequest $request, $id): JsonResponse
    {
        $item = Tenant::query()
            ->with('person')
            ->findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, $item->person_id)
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }

            DB::beginTransaction();

            $inputs = $request->all();
            $item->person->fill($inputs)->save();
            $item->fill($inputs)->save();

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $item = Tenant::query()
            ->with('person.user')
            ->findOrFail($id);

        try {
            DB::beginTransaction();

            $item->person->user->delete();
            $item->delete();

            DB::commit();
            return $this->sendResponse([], 'Registro deletado com sucesso!');
        } catch (\Throwable $th) {
            $msg = 'Este registro está vinculado a outra tabela. Por favor, remova o vínculo antes de excluir!';
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

    private function rules(Request $request, $primaryId = null, bool $changeMessages = false)
    {
        $rules = [
            'nif' => [new modelPersonRelationship(Tenant::class, $primaryId)],
            'status' => ['required', 'integer', new Enum(\App\Enums\TenantStatus::class)],
            'email' => [new modelPersonRelationship(Tenant::class, $primaryId)]
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
