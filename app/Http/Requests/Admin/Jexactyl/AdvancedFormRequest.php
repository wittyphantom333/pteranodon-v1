<?php

namespace Pteranodon\Http\Requests\Admin\Pteranodon;

use Pteranodon\Http\Requests\Admin\AdminFormRequest;

class AdvancedFormRequest extends AdminFormRequest
{
    /**
     * Return all the rules to apply to this request's data.
     */
    public function rules(): array
    {
        return [
            'pteranodon:auth:2fa_required' => 'required|integer|in:0,1,2',

            'recaptcha:enabled' => 'required|in:true,false',
            'recaptcha:secret_key' => 'required|string|max:191',
            'recaptcha:website_key' => 'required|string|max:191',
            'pteranodon:guzzle:timeout' => 'required|integer|between:1,60',
            'pteranodon:guzzle:connect_timeout' => 'required|integer|between:1,60',

            'pteranodon:client_features:allocations:enabled' => 'required|in:true,false',
            'pteranodon:client_features:allocations:range_start' => [
                'nullable',
                'required_if:pteranodon:client_features:allocations:enabled,true',
                'integer',
                'between:1024,65535',
            ],
            'pteranodon:client_features:allocations:range_end' => [
                'nullable',
                'required_if:pteranodon:client_features:allocations:enabled,true',
                'integer',
                'between:1024,65535',
                'gt:pteranodon:client_features:allocations:range_start',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'recaptcha:enabled' => 'reCAPTCHA Enabled',
            'recaptcha:secret_key' => 'reCAPTCHA Secret Key',
            'recaptcha:website_key' => 'reCAPTCHA Website Key',
            'pteranodon:guzzle:timeout' => 'HTTP Request Timeout',
            'pteranodon:guzzle:connect_timeout' => 'HTTP Connection Timeout',
            'pteranodon:client_features:allocations:enabled' => 'Auto Create Allocations Enabled',
            'pteranodon:client_features:allocations:range_start' => 'Starting Port',
            'pteranodon:client_features:allocations:range_end' => 'Ending Port',
        ];
    }
}
