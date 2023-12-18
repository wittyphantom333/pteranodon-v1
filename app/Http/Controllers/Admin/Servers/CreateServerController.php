<?php

namespace Pteranodon\Http\Controllers\Admin\Servers;

use Illuminate\View\View;
use Pteranodon\Models\Node;
use Pteranodon\Models\Location;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pteranodon\Http\Controllers\Controller;
use Illuminate\View\Factory as ViewFactory;
use Pteranodon\Repositories\Eloquent\NestRepository;
use Pteranodon\Repositories\Eloquent\NodeRepository;
use Pteranodon\Http\Requests\Admin\ServerFormRequest;
use Pteranodon\Services\Servers\ServerCreationService;

class CreateServerController extends Controller
{
    /**
     * CreateServerController constructor.
     */
    public function __construct(
        private AlertsMessageBag $alert,
        private NestRepository $nestRepository,
        private NodeRepository $nodeRepository,
        private ServerCreationService $creationService,
        private ViewFactory $view
    ) {
    }

    /**
     * Displays the create server page.
     *
     * @throws \Pteranodon\Exceptions\Repository\RecordNotFoundException
     */
    public function index(): View|RedirectResponse
    {
        $nodes = Node::all();
        if (count($nodes) < 1) {
            $this->alert->warning(trans('admin/server.alerts.node_required'))->flash();

            return redirect()->route('admin.nodes');
        }

        $nests = $this->nestRepository->getWithEggs();

        \JavaScript::put([
            'nodeData' => $this->nodeRepository->getNodesForServerCreation(),
            'nests' => $nests->map(function ($item) {
                return array_merge($item->toArray(), [
                    'eggs' => $item->eggs->keyBy('id')->toArray(),
                ]);
            })->keyBy('id'),
        ]);

        return $this->view->make('admin.servers.new', [
            'locations' => Location::all(),
            'nests' => $nests,
        ]);
    }

    /**
     * Create a new server on the remote system.
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Pteranodon\Exceptions\DisplayException
     * @throws \Pteranodon\Exceptions\Service\Deployment\NoViableAllocationException
     * @throws \Pteranodon\Exceptions\Service\Deployment\NoViableNodeException
     * @throws \Throwable
     */
    public function store(ServerFormRequest $request): RedirectResponse
    {
        $data = $request->except(['_token']);
        if (!empty($data['custom_image'])) {
            $data['image'] = $data['custom_image'];
            unset($data['custom_image']);
        }

        $server = $this->creationService->handle($data);

        $this->alert->success(trans('admin/server.alerts.server_created'))->flash();

        return new RedirectResponse('/admin/servers/view/' . $server->id);
    }
}
