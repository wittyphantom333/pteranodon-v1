<?php

namespace Pteranodon\Http\Requests\Api\Client\Servers;

use Pteranodon\Models\Permission;
use Pteranodon\Http\Requests\Api\Client\ClientApiRequest;

class ShareConsoleRequest extends ClientApiRequest
{
    /**
     * Determine if the API user has permission to perform this action.
     */
    public function permission(): string
    {
        return Permission::ACTION_CONTROL_CONSOLE;
    }
}
