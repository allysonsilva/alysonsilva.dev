<?php

namespace App\Http\Requests\API;

use App\Models\Category;
use Illuminate\Validation\Rule;
use App\Support\Http\Requests\BaseRequest;

class StoreCategoryRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $uniqueTitleRule = Rule::unique(Category::class);

        if (! is_null($category = $this->route('categoryUuid'))) {
            $uniqueTitleRule->ignore($category);
        }

        return [
            'title' => ['required', $uniqueTitleRule],
            'description' => ['required'],
            'icon' => ['required'],
            'color' => ['sometimes', 'required', 'regex:/^#([[:xdigit:]]{3}){1,2}\b$/'],
            'order' => ['sometimes', 'required', 'integer', 'min:1', 'regex:/^\d{1,2}$/'],
        ];
    }
}
