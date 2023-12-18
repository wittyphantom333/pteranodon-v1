<?php

namespace Pteranodon\Http\Requests\Api\Application\Allocations;

use Pteranodon\Services\Acl\Api\AdminAcl;
use Pteranodon\Http\Requests\Api\Application\ApplicationApiRequest;

class GetAllocationsRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_ALLOCATIONS;

    protected int $permission = AdminAcl::READ;
}
