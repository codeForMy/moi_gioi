<?php

namespace App\Http\Requests\Post;

use App\Models\Post;
use Illuminate\Validation\Rule;
use App\Enums\PostRemotableEnum;
use App\Enums\PostCurrencySalaryEnum;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $rules = [
            'company' => [
                'string',
                'nullable',
            ],
            'languages' => [
                'required',
                'array',
                'filled',
            ],
            'city' => [
                'required',
                'string',
                'filled',
            ],
            'district' => [
                'required',
                'string',
                'filled',
            ],
            'currency_salary' => [
                'required',
                'numeric',
                Rule::in(PostCurrencySalaryEnum::getValues()),
            ],
            'number_applicants' => [
                'nullable',
                'numeric',
                'min:1',
            ],
            'remotable' => [
                'required',
                Rule::in(PostRemotableEnum::getArrWithoutAll()),
            ],
            'can_parttime' => [
                'nullable',
            ],
            'start_date' => [
                'nullable',
                'date',
                'before:end_date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after:start_date',
            ],
            'job_tittle' => [
                'required',
                'string',
                'filled',
                'min:3',
                'max:255',
            ],
            'slug' => [
                'required',
                'string',
                'filled',
                'min:3',
                'max:255',
                Rule::unique(Post::class),
            ],
            'requirement' => [
                'nullable',
                'string',
            ],
        ];

        $rules['min_salary'] = [
            'nullable',
            'numeric',
        ];
        if (!empty($this->max_salary)) {
            $rules['min_salary'][] = 'lt:max_salary';
        }

        $rules['max_salary'] = [
            'nullable',
            'numeric',
        ];
        if (!empty($this->min_salary)) {
            $rules['max_salary'][] = 'gt:min_salary';
        }

        return $rules;
    }
}
