<?php

namespace App\Http\Controllers\API;

use App\Exceptions\HttpException;
use App\Http\Requests\PersonRequest;
use App\Models\Person;
use App\Models\Signature;
use App\Models\Tenant;
use App\Models\User;
use App\Rules\modelPersonRelationship;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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

            $signature = Signature::query()
                ->with('modules')
                ->findOrFail($inputs['signature_id']);

            $user->syncRoles($signature->modules->map(fn ($item) => $item->name));

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
            ->with('signature.dueDays')
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

            $signature = Signature::query()
                ->with('modules')
                ->findOrFail($inputs['signature_id']);

            $item->person->user->syncRoles($signature->modules->map(fn ($item) => $item->name));

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
     * Remove all specified resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'items' => ['required', 'array', 'min:1'],
                'items.*' => ['required', 'integer', Rule::exists('tenants', 'id')]
            ]
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }

            DB::beginTransaction();

            $items = Tenant::query()
                ->with('person.user')
                ->whereIn('id', $request->items)
                ->get();

            $model = null;
            foreach ($items as $item) {
                $model = $item;
                $item->person->user->delete();
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

    private function rules(Request $request, $primaryId = null, bool $changeMessages = false)
    {
        $rules = [
            'nif' => [new modelPersonRelationship(Tenant::class, $primaryId)],
            'email' => [new modelPersonRelationship(Tenant::class, $primaryId)],
            'signature_id' => ['required', 'integer', Rule::exists('signatures', 'id')],
            'due_day_id' => ['required', 'integer', Rule::exists('due_days', 'id')],
            'status' => ['required', 'integer', new Enum(\App\Enums\TenantStatus::class)]
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
