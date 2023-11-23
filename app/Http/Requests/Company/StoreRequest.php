<?php

namespace App\Http\Requests\Company;

use Illuminate\Validation\Rule;
use App\Enums\CompanyCountryEnum;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'filled',
                'string',
                'min:0',
            ],
            'country' => [
                'required',
                'string',
                Rule::in(CompanyCountryEnum::getKeys()),
            ],
            'city' => [
                'required',
                'string',
            ],
            'district' => [
                'nullable',
                'string',
            ],
            'address' => [
                'nullable',
                'string',
            ],
            'address2' => [
                'nullable',
                'string',
            ],
            'zipcode' => [
                'nullable',
                'string',
            ],
            'phone' => [
                'nullable',
                'unique: App\Models\Users,phone',
                'string',
            ],
            'email' => [
                'nullable',
                'unique: App\Models\Users,email',
                'string',
            ],
            'logo' => [
                'nullable',
                'mimes:jpeg,png,jpg,gif',
            ],
        ];
    }
}
