<?php

namespace App\Http\Requests;

use App\Support\Http\Requests\BaseRequest;

class IndexCategoryRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $perPageRequest = (new PerPageRequest())->rules();

        return array_merge($perPageRequest, [
            'title' => ['sometimes', 'required',],
            'description' => ['sometimes', 'required',],
        ]);
    }
}
