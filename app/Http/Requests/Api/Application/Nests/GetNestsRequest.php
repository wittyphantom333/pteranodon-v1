<?php

namespace Pteranodon\Http\Requests\Api\Application\Nests;

use Pteranodon\Services\Acl\Api\AdminAcl;
use Pteranodon\Http\Requests\Api\Application\ApplicationApiRequest;

class GetNestsRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_NESTS;

    protected int $permission = AdminAcl::READ;
}
