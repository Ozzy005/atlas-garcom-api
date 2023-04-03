<?php

namespace App\Http\Controllers\API;

use App\Exceptions\HttpException;
use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CategoryController extends BaseController
{
    public function __construct()
    {
        $this->middleware('check-tenant');
        $this->middleware('permission:categories_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:categories_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:categories_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:categories_delete', ['only' => ['destroy']]);
    }

    public function publicIndex(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    public function index(Request $request): JsonResponse
    {
        $query = Category::query()
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('status'), fn (Builder $query) => $query->where('status', $request->status))
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
            $this->rules($request),
            $this->rules($request, null, true)
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }

            DB::beginTransaction();

            $inputs = $request->all();

            $inputs['image'] = Storage::disk('public')->put('categories', $inputs['image']);

            Category::query()->create($inputs);

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

    public function show(int $id): JsonResponse
    {
        $item = Category::query()
            ->findOrFail($id);

        return $this->sendResponse($item);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $item = Category::query()
            ->findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, $item->id),
            $this->rules($request, null, true)
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }

            DB::beginTransaction();

            $inputs = $request->all();

            Storage::disk('public')->delete($item->image);
            $inputs['image'] = Storage::disk('public')->put('categories', $inputs['image']);

            $item->fill($inputs)->save();

            DB::commit();
            return $this->sendResponse([], 'Registro editado com sucesso!');
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

    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'items' => ['required', 'array', 'min:1'],
                'items.*' => ['required', 'integer', Rule::exists('categories', 'id')]
            ]
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }

            DB::beginTransaction();

            $items = Category::query()
                ->whereIn('id', $request->items)
                ->get();

            $model = null;
            foreach ($items as $item) {
                $model = $item;
                Storage::disk('public')->delete($item->image);
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

    private function rules(Request $request, int | null $primaryId = null, bool $changeMessages = false): array
    {
        $rules = [
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'dimensions:ratio=1/1', 'max:1024'],
            'name' => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'integer', new Enum(\App\Enums\Status::class)]
        ];

        $messages = [
            'image.dimensions' => 'A imagem deve ser quadrada!'
        ];

        return !$changeMessages ? $rules : $messages;
    }
}
