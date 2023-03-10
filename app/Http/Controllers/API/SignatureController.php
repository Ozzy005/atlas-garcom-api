<?php

namespace App\Http\Controllers\API;

use App\Exceptions\HttpException;
use App\Models\Signature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class SignatureController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:signatures_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:signatures_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:signatures_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:signatures_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Signature::query()
            ->with('dueDays', 'modules')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
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

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }

            DB::beginTransaction();

            $inputs = $request->all();
            $inputs['price'] = moneyToFloat($inputs['price']);
            $inputs['discount'] = moneyToFloat($inputs['discount']);
            $inputs['discounted_price'] = moneyToFloat($inputs['discounted_price']);
            $inputs['total_price'] = moneyToFloat($inputs['total_price']);
            $item = Signature::query()->create($inputs);
            $item->dueDays()->sync($inputs['due_days']);
            $item->modules()->sync($inputs['modules']);

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $item = Signature::query()
            ->with('dueDays', 'modules')
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
        $item = Signature::query()
            ->findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, $item->id)
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }

            DB::beginTransaction();

            $inputs = $request->all();
            $inputs['price'] = moneyToFloat($inputs['price']);
            $inputs['discount'] = moneyToFloat($inputs['discount']);
            $inputs['discounted_price'] = moneyToFloat($inputs['discounted_price']);
            $inputs['total_price'] = moneyToFloat($inputs['total_price']);
            $item->fill($inputs)->save();
            $item->dueDays()->sync($inputs['due_days']);
            $item->modules()->sync($inputs['modules']);

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
                'items.*' => ['required', 'integer', Rule::exists('signatures', 'id')]
            ]
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }

            DB::beginTransaction();

            $items = Signature::query()
                ->whereIn('id', $request->items)
                ->get();

            $model = null;
            foreach ($items as $item) {
                $model = $item;
                $item->dueDays()->detach();
                $item->modules()->detach();
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
            'name' => ['required', 'string', 'max:30'],
            'description' => ['required', 'string', 'max:100'],
            'recurrence' => ['required', 'integer', new Enum(\App\Enums\Recurrence::class)],
            'price' => ['required', 'string', 'max:12'],
            'hasDiscount' => ['required', 'boolean'],
            'discount' => ['nullable', 'string', 'max:5'],
            'discounted_price' => ['nullable', 'string', 'max:12'],
            'total_price' => ['required', 'string', 'max:12'],
            'due_days' => ['required', 'array'],
            'due_days.*' => ['required', 'integer', Rule::exists('due_days', 'id')],
            'modules' => ['required', 'array'],
            'modules.*' => ['required', 'integer', Rule::exists('roles', 'id')],
            'status' => ['required', 'integer', new Enum(\App\Enums\Status::class)]
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
