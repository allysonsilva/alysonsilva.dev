<?php

namespace App\Http\Requests;

use App\Support\Http\Requests\BaseRequest;

class PerPageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'required', 'integer', 'min:1'],
        ];
    }
}
