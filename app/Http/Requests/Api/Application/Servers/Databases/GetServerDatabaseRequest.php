<?php

namespace Pteranodon\Http\Requests\Api\Application\Servers\Databases;

use Pteranodon\Services\Acl\Api\AdminAcl;
use Pteranodon\Http\Requests\Api\Application\ApplicationApiRequest;

class GetServerDatabaseRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_SERVER_DATABASES;

    protected int $permission = AdminAcl::READ;
}
