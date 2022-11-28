<?php

namespace App\Http\Requests;

use App\Support\Http\Requests\BaseRequest;

class FrontSearchRequest extends BaseRequest
{
    /**
     * The URI that users should be redirected to if validation fails.
     *
     * @var string
     */
    protected $redirect = '/';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'tag' => ['sometimes', 'required_without:search', 'min:3'],
            'search' => ['sometimes', 'required_without:tag', 'min:3'],
        ];
    }
}
