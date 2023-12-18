<?php

namespace Pteranodon\Http\Controllers\Admin;

use Illuminate\View\View;
use Pteranodon\Models\Location;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pteranodon\Exceptions\DisplayException;
use Pteranodon\Http\Controllers\Controller;
use Illuminate\View\Factory as ViewFactory;
use Pteranodon\Http\Requests\Admin\LocationFormRequest;
use Pteranodon\Services\Locations\LocationUpdateService;
use Pteranodon\Services\Locations\LocationCreationService;
use Pteranodon\Services\Locations\LocationDeletionService;
use Pteranodon\Contracts\Repository\LocationRepositoryInterface;

class LocationController extends Controller
{
    /**
     * LocationController constructor.
     */
    public function __construct(
        protected AlertsMessageBag $alert,
        protected LocationCreationService $creationService,
        protected LocationDeletionService $deletionService,
        protected LocationRepositoryInterface $repository,
        protected LocationUpdateService $updateService,
        protected ViewFactory $view
    ) {
    }

    /**
     * Return the location overview page.
     */
    public function index(): View
    {
        return $this->view->make('admin.locations.index', [
            'locations' => $this->repository->getAllWithDetails(),
        ]);
    }

    /**
     * Return the location view page.
     *
     * @throws \Pteranodon\Exceptions\Repository\RecordNotFoundException
     */
    public function view(int $id): View
    {
        return $this->view->make('admin.locations.view', [
            'location' => $this->repository->getWithNodes($id),
        ]);
    }

    /**
     * Handle request to create new location.
     *
     * @throws \Throwable
     */
    public function create(LocationFormRequest $request): RedirectResponse
    {
        $location = $this->creationService->handle($request->normalize());
        $this->alert->success('Location was created successfully.')->flash();

        return redirect()->route('admin.locations.view', $location->id);
    }

    /**
     * Handle request to update or delete location.
     *
     * @throws \Throwable
     */
    public function update(LocationFormRequest $request, Location $location): RedirectResponse
    {
        if ($request->input('action') === 'delete') {
            return $this->delete($location);
        }

        $this->updateService->handle($location->id, $request->normalize());
        $this->alert->success('Location was updated successfully.')->flash();

        return redirect()->route('admin.locations.view', $location->id);
    }

    /**
     * Delete a location from the system.
     *
     * @throws \Exception
     * @throws \Pteranodon\Exceptions\DisplayException
     */
    public function delete(Location $location): RedirectResponse
    {
        try {
            $this->deletionService->handle($location->id);

            return redirect()->route('admin.locations');
        } catch (DisplayException $ex) {
            $this->alert->danger($ex->getMessage())->flash();
        }

        return redirect()->route('admin.locations.view', $location->id);
    }
}
