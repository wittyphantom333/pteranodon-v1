<?php

namespace Pteranodon\Transformers\Api\Client;

use Pteranodon\Models\User;
use Pteranodon\Models\Server;
use Webmozart\Assert\Assert;
use Pteranodon\Transformers\Api\Application\BaseTransformer as BaseApplicationTransformer;

abstract class BaseClientTransformer extends BaseApplicationTransformer
{
    /**
     * Return the user model of the user requesting this transformation.
     */
    public function getUser(): User
    {
        return $this->request->user();
    }

    /**
     * Determine if the API key loaded onto the transformer has permission
     * to access a different resource. This is used when including other
     * models on a transformation request.
     *
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    protected function authorize(string $ability, Server $server = null): bool
    {
        Assert::isInstanceOf($server, Server::class);

        return $this->request->user()->can($ability, [$server]);
    }

    /**
     * {@inheritDoc}
     */
    protected function makeTransformer(string $abstract)
    {
        Assert::subclassOf($abstract, self::class);

        return parent::makeTransformer($abstract);
    }
}
