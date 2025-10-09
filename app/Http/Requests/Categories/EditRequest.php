<?php

namespace App\Http\Requests\Categories;

use App\Enums\Permissions\CategoryEnum;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRequest extends FormRequest
{
    protected $redirectRoute = 'admin.categories.edit';
    public function authorize(): bool
    {
        return $this->user()->can(CategoryEnum::EDIT->value);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:255', Rule::unique('categories', 'title')->ignore($this->route('category')?->id)],
            'parent_id' => ['nullable', 'numeric', 'exists:categories,id'],
        ];
    }
}
