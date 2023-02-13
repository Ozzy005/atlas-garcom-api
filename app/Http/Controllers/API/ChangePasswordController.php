<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\CurrentPasswordCheckRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ChangePasswordController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => ['required', 'min:8', new CurrentPasswordCheckRule],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de Validação !', $validator->errors()->toArray(), 422);
        }

        try {
            DB::beginTransaction();

            User::query()
                ->where('id', auth()->id())
                ->update(['password' => Hash::make($request->password)]);

            DB::commit();
            return $this->sendResponse([], 'Senha alterada com sucesso !');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }
}
