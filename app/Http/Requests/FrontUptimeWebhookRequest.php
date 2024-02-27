<?php

namespace App\Http\Requests;

use App\Support\Http\Requests\BaseRequest;

class FrontUptimeWebhookRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'heartbeat' => ['sometimes', 'nullable', 'required', 'array'],
            'monitor' => ['sometimes', 'nullable', 'required', 'array'],
            'msg' => ['required',],
        ];
    }
}
