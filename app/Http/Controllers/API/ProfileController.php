<?php

namespace App\Http\Controllers\API;

use App\Exceptions\HttpException;
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
        $item = User::query()
            ->with('person.city.state')
            ->findOrFail(auth()->id());

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
        $item = User::query()
            ->with('person')
            ->findOrFail(auth()->id());

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, $item)
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
            return $this->sendResponse([], 'Perfil editado com sucesso!');
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

    private function rules(Request $request, $item = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'string', 'max:60'],
            'email' => ['required', 'string', 'max:100', Rule::unique('users')->ignore($item->id ?? null)]
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
