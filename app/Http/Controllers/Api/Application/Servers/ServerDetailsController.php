<?php

namespace Pteranodon\Http\Controllers\Api\Application\Servers;

use Pteranodon\Models\Server;
use Pteranodon\Services\Servers\BuildModificationService;
use Pteranodon\Services\Servers\DetailsModificationService;
use Pteranodon\Transformers\Api\Application\ServerTransformer;
use Pteranodon\Http\Controllers\Api\Application\ApplicationApiController;
use Pteranodon\Http\Requests\Api\Application\Servers\UpdateServerDetailsRequest;
use Pteranodon\Http\Requests\Api\Application\Servers\UpdateServerBuildConfigurationRequest;

class ServerDetailsController extends ApplicationApiController
{
    /**
     * ServerDetailsController constructor.
     */
    public function __construct(
        private BuildModificationService $buildModificationService,
        private DetailsModificationService $detailsModificationService
    ) {
        parent::__construct();
    }

    /**
     * Update the details for a specific server.
     *
     * @throws \Pteranodon\Exceptions\DisplayException
     * @throws \Pteranodon\Exceptions\Model\DataValidationException
     * @throws \Pteranodon\Exceptions\Repository\RecordNotFoundException
     */
    public function details(UpdateServerDetailsRequest $request, Server $server): array
    {
        $updated = $this->detailsModificationService->returnUpdatedModel()->handle(
            $server,
            $request->validated()
        );

        return $this->fractal->item($updated)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }

    /**
     * Update the build details for a specific server.
     *
     * @throws \Pteranodon\Exceptions\DisplayException
     * @throws \Pteranodon\Exceptions\Model\DataValidationException
     * @throws \Pteranodon\Exceptions\Repository\RecordNotFoundException
     */
    public function build(UpdateServerBuildConfigurationRequest $request, Server $server): array
    {
        $server = $this->buildModificationService->handle($server, $request->validated());

        return $this->fractal->item($server)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }
}
