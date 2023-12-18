<?php

namespace Pteranodon\Http\Requests\Api\Application\Users;

use Pteranodon\Services\Acl\Api\AdminAcl;
use Pteranodon\Http\Requests\Api\Application\ApplicationApiRequest;

class DeleteUserRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_USERS;

    protected int $permission = AdminAcl::WRITE;
}
