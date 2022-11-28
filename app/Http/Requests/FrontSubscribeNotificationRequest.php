<?php

namespace App\Http\Requests;

use App\Support\Http\Requests\BaseRequest;

class FrontSubscribeNotificationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'endpoint' => 'required',
            'auth_token' => 'required',
            'public_key' => 'required',
            'encoding' => 'required',
        ];
    }
}
