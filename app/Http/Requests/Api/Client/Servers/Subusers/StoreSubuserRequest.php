<?php

namespace Pteranodon\Http\Requests\Api\Client\Servers\Subusers;

use Pteranodon\Models\Permission;

class StoreSubuserRequest extends SubuserRequest
{
    public function permission(): string
    {
        return Permission::ACTION_USER_CREATE;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|between:1,191',
            'permissions' => 'required|array',
            'permissions.*' => 'string',
        ];
    }
}
