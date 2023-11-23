<?php

namespace App\Http\Requests\Applicant\HomePage;

use Illuminate\Validation\Rule;
use App\Enums\PostRemotableEnum;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'cities' => [
                'array',
            ],
            'min_salary' => [
                'integer',
            ],
            'max_salary' => [
                'integer',
            ],
            'remotable' => [
                'nullable',
                Rule::in(PostRemotableEnum::getValues()),
            ],
        ];
    }
}
