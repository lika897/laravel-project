<?php

namespace App\Http\Requests\Categories;

use App\Enums\Permissions\CategoryEnum;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(CategoryEnum::PUBLISH->value);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:255', 'unique:categories'],
            'parent_id' => ['nullable', 'numeric', 'exists:categories,id'],
        ];
    }
}
