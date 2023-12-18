<?php

namespace Pteranodon\Http\Requests\Api\Client\Store\Gateways;

use Pteranodon\Http\Requests\Api\Client\ClientApiRequest;

class PayPalRequest extends ClientApiRequest
{
    /**
     * Rules to validate this request against.
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|int',
        ];
    }
}
