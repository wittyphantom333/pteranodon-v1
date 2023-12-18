<?php

namespace Pteranodon\Http\Requests\Api\Application\Locations;

use Pteranodon\Services\Acl\Api\AdminAcl;
use Pteranodon\Http\Requests\Api\Application\ApplicationApiRequest;

class DeleteLocationRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_LOCATIONS;

    protected int $permission = AdminAcl::WRITE;
}
