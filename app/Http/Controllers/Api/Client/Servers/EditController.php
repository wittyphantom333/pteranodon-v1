<?php

namespace Pteranodon\Http\Controllers\Api\Client\Servers;

use Pteranodon\Models\Server;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Pteranodon\Exceptions\DisplayException;
use Pteranodon\Services\Servers\ServerEditService;
use Pteranodon\Http\Controllers\Api\Client\ClientApiController;
use Pteranodon\Http\Requests\Api\Client\Servers\EditServerRequest;

class EditController extends ClientApiController
{
    /**
     * PowerController constructor.
     */
    public function __construct(private ServerEditService $editService)
    {
        parent::__construct();
    }

    /**
     * Edit a server's resource limits.
     *
     * @throws DisplayException
     */
    public function index(EditServerRequest $request, Server $server): JsonResponse
    {
        if ($this->settings->get('pteranodon::renewal:editing') != 'true') {
            throw new DisplayException('Server editing is currently disabled.');
        }

        if ($request->user()->id != $server->owner_id) {
            throw new DisplayException('You do not own this server, so you cannot edit the resources.');
        }

        $this->editService->handle($request, $server);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
