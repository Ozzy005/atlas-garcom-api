<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends BaseController
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(): JsonResponse
    {
        $item = User::query()->findOrFail(auth()->id());

        return $this->sendResponse($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $item = User::query()->findOrFail(auth()->id());

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

            DB::commit();
            return $this->sendResponse([], 'Perfil editado com sucesso !');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'string', 'max:60'],
            'email' => ['required', 'string', 'max:100', Rule::unique('users')->ignore($primaryKey)]
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
