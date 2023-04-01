<?php

namespace App\Http\Controllers\API;

use App\Exceptions\HttpException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\CurrentPasswordCheckRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ChangePasswordController extends BaseController
{
    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => ['required', 'string', 'min:8', new CurrentPasswordCheckRule],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]
        );

        try {
            if ($validator->fails()) {
                throw new HttpException('Erro de validação!', $validator->errors()->toArray(), 422);
            }

            DB::beginTransaction();

            User::query()
                ->where('id', auth()->id())
                ->update(['password' => Hash::make($request->password)]);

            DB::commit();
            return $this->sendResponse([], 'Senha alterada com sucesso!');
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
}
