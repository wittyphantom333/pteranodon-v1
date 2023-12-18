<?php

namespace Pteranodon\Http\Requests\Api\Application\Servers\Databases;

use Pteranodon\Services\Acl\Api\AdminAcl;

class ServerDatabaseWriteRequest extends GetServerDatabasesRequest
{
    protected int $permission = AdminAcl::WRITE;
}
