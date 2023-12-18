<?php

namespace Pteranodon\Http\Controllers\Api\Application\Nodes;

use Pteranodon\Models\Node;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Pteranodon\Services\Nodes\NodeUpdateService;
use Pteranodon\Services\Nodes\NodeCreationService;
use Pteranodon\Services\Nodes\NodeDeletionService;
use Pteranodon\Transformers\Api\Application\NodeTransformer;
use Pteranodon\Http\Requests\Api\Application\Nodes\GetNodeRequest;
use Pteranodon\Http\Requests\Api\Application\Nodes\GetNodesRequest;
use Pteranodon\Http\Requests\Api\Application\Nodes\StoreNodeRequest;
use Pteranodon\Http\Requests\Api\Application\Nodes\DeleteNodeRequest;
use Pteranodon\Http\Requests\Api\Application\Nodes\UpdateNodeRequest;
use Pteranodon\Http\Controllers\Api\Application\ApplicationApiController;

class NodeController extends ApplicationApiController
{
    /**
     * NodeController constructor.
     */
    public function __construct(
        private NodeCreationService $creationService,
        private NodeDeletionService $deletionService,
        private NodeUpdateService $updateService
    ) {
        parent::__construct();
    }

    /**
     * Return all the nodes currently available on the Panel.
     */
    public function index(GetNodesRequest $request): array
    {
        $nodes = QueryBuilder::for(Node::query())
            ->allowedFilters(['uuid', 'name', 'fqdn', 'daemon_token_id'])
            ->allowedSorts(['id', 'uuid', 'memory', 'disk'])
            ->paginate($request->query('per_page') ?? 50);

        return $this->fractal->collection($nodes)
            ->transformWith($this->getTransformer(NodeTransformer::class))
            ->toArray();
    }

    /**
     * Return data for a single instance of a node.
     */
    public function view(GetNodeRequest $request, Node $node): array
    {
        return $this->fractal->item($node)
            ->transformWith($this->getTransformer(NodeTransformer::class))
            ->toArray();
    }

    /**
     * Create a new node on the Panel. Returns the created node and an HTTP/201
     * status response on success.
     *
     * @throws \Pteranodon\Exceptions\Model\DataValidationException
     */
    public function store(StoreNodeRequest $request): JsonResponse
    {
        $node = $this->creationService->handle($request->validated());

        return $this->fractal->item($node)
            ->transformWith($this->getTransformer(NodeTransformer::class))
            ->addMeta([
                'resource' => route('api.application.nodes.view', [
                    'node' => $node->id,
                ]),
            ])
            ->respond(201);
    }

    /**
     * Update an existing node on the Panel.
     *
     * @throws \Throwable
     */
    public function update(UpdateNodeRequest $request, Node $node): array
    {
        $node = $this->updateService->handle(
            $node,
            $request->validated(),
            $request->input('reset_secret') === true
        );

        return $this->fractal->item($node)
            ->transformWith($this->getTransformer(NodeTransformer::class))
            ->toArray();
    }

    /**
     * Deletes a given node from the Panel as long as there are no servers
     * currently attached to it.
     *
     * @throws \Pteranodon\Exceptions\Service\HasActiveServersException
     */
    public function delete(DeleteNodeRequest $request, Node $node): JsonResponse
    {
        $this->deletionService->handle($node);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
