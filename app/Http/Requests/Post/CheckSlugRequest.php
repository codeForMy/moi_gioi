<?php

namespace App\Http\Requests\Post;

use App\Models\Post;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CheckSlugRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'slug' => [
                'required',
                'string',
                'filled',
                Rule::unique(Post::class),
            ]
        ];
    }
}
