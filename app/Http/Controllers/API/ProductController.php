<?php

namespace App\Http\Controllers\API;

use App\Exceptions\HttpException;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ProductController extends BaseController
{
    public function __construct()
    {
        $this->middleware('check-tenant');
        $this->middleware('permission:products_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:products_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:products_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:products_delete', ['only' => ['destroy']]);
    }

    public function publicIndex(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    public function index(Request $request): JsonResponse
    {
        $query = Product::query()
            ->tenantQuery()
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('with'), fn (Builder $query) => $query->with($request->with))
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

            foreach ($inputs['product_prices'] as &$productPrice) {
                $productPrice['quantity'] = moneyToFloat($productPrice['quantity']);
                $productPrice['cost_price'] = moneyToFloat($productPrice['cost_price']);
                $productPrice['price'] = moneyToFloat($productPrice['price']);
            }

            if (!empty($request->image)) {

                $extension = explode('/', mime_content_type($request->image))[1];
                $inputs['image'] = 'products/' . uniqid() . '.' . $extension;
                $file = substr($request->image, strpos($request->image, ',') + 1);

                Storage::disk('public')->put($inputs['image'], base64_decode($file));
            }

            $product = Product::query()->create($inputs);
            $product->productPrices()->createMany($inputs['product_prices']);
            $product->complements()->attach($inputs['complements_ids']);

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
        $item = Product::query()
            ->tenantQuery()
            ->when($request->filled('with'), fn (Builder $query) => $query->with($request->with))
            ->findOrFail($id);

        return $this->sendResponse($item);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $item = Product::query()
            ->tenantQuery()
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

            if (!empty($request->image)) {

                Storage::disk('public')->delete($item->image);
                $extension = explode('/', mime_content_type($request->image))[1];
                $inputs['image'] = 'products/' . uniqid() . '.' . $extension;
                $file = substr($request->image, strpos($request->image, ',') + 1);

                Storage::disk('public')->put($inputs['image'], base64_decode($file));
            }

            $item->fill($inputs)->save();

            foreach ($inputs['product_prices'] as &$productPrice) {
                $productPrice['product_id'] = $item->id;
                $productPrice['quantity'] = moneyToFloat($productPrice['quantity']);
                $productPrice['cost_price'] = moneyToFloat($productPrice['cost_price']);
                $productPrice['price'] = moneyToFloat($productPrice['price']);
                unset($productPrice['created_at']);
                unset($productPrice['updated_at']);
            }

            $item->productPrices()->whereNotIn('id', array_column($productPrice, 'id'))->delete();

            ProductPrice::upsert($inputs['product_prices'], ['id']);

            $item->complements()->sync($inputs['complements_ids']);

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
                'items.*' => ['required', 'integer', Rule::exists('products', 'id')]
            ]
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }

            DB::beginTransaction();

            $items = Product::query()
                ->tenantQuery()
                ->whereIn('id', $request->items)
                ->get();

            $model = null;
            foreach ($items as $item) {
                $model = $item;
                Storage::disk('public')->delete($item->image);
                $item->productPrices()->delete();
                $item->complements()->detach();
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
            'image' => ['required', 'base64image', 'base64mimes:jpg,jpeg,png', 'base64dimensions:ratio=1/1', 'base64max:1024'],
            'name' => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:100'],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'status' => ['required', 'integer', new Enum(\App\Enums\Status::class)],
            'product_prices' => ['required', 'array', 'min:1'],
            'product_prices.*.id' => ['nullable', 'integer', Rule::exists('product_prices', 'id')],
            'product_prices.*.name' => ['required', 'string', 'max:30'],
            'product_prices.*.measurement_unit_id' => ['required', 'integer', Rule::exists('measurement_units', 'id')],
            'product_prices.*.quantity' => ['required', 'string', 'max:12'],
            'product_prices.*.cost_price' => ['required', 'string', 'max:12'],
            'product_prices.*.price' => ['required', 'string', 'max:12'],
            'product_prices.*.status' => ['required', 'integer', new Enum(\App\Enums\Status::class)],
            'complements_ids' => ['required', 'array', 'min:1'],
            'complements_ids.*' => ['required', 'integer', Rule::exists('complements', 'id')]
        ];

        $messages = [
            'image.base64dimensions' => 'A imagem deve ser quadrada!'
        ];

        return !$changeMessages ? $rules : $messages;
    }
}
