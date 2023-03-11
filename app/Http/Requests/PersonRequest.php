<?php

namespace App\Http\Requests;

use App\Rules\CpfCnpj;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nif' => ['required', 'string', new CpfCnpj],
            'full_name' => ['required', 'string', 'max:100'],
            'name' => ['nullable', 'string', 'max:50'],
            'state_registration' => ['required', 'string', 'max:15'],
            'city_registration' => ['nullable', 'string', 'max:12'],
            'birthdate' => ['required', 'string', 'date'],
            'email' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:11'],
            'city_id' => ['required', 'integer', Rule::exists('cities', 'id')],
            'zip_code' => ['required', 'string', 'max:8'],
            'address' => ['required', 'string', 'max:60'],
            'district' => ['nullable', 'string', 'max:30'],
            'number' => ['nullable', 'string', 'max:10'],
            'complement' => ['nullable', 'string', 'max:30']
        ];
    }
}
