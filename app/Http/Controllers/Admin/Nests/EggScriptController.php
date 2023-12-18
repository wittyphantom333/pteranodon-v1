<?php

namespace Pteranodon\Http\Controllers\Admin\Nests;

use Pteranodon\Models\Egg;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pteranodon\Http\Controllers\Controller;
use Illuminate\View\Factory as ViewFactory;
use Pteranodon\Services\Eggs\Scripts\InstallScriptService;
use Pteranodon\Contracts\Repository\EggRepositoryInterface;
use Pteranodon\Http\Requests\Admin\Egg\EggScriptFormRequest;

class EggScriptController extends Controller
{
    /**
     * EggScriptController constructor.
     */
    public function __construct(
        protected AlertsMessageBag $alert,
        protected EggRepositoryInterface $repository,
        protected InstallScriptService $installScriptService,
        protected ViewFactory $view
    ) {
    }

    /**
     * Handle requests to render installation script for an Egg.
     */
    public function index(int $egg): View
    {
        $egg = $this->repository->getWithCopyAttributes($egg);
        $copy = $this->repository->findWhere([
            ['copy_script_from', '=', null],
            ['nest_id', '=', $egg->nest_id],
            ['id', '!=', $egg],
        ]);

        $rely = $this->repository->findWhere([
            ['copy_script_from', '=', $egg->id],
        ]);

        return $this->view->make('admin.eggs.scripts', [
            'copyFromOptions' => $copy,
            'relyOnScript' => $rely,
            'egg' => $egg,
        ]);
    }

    /**
     * Handle a request to update the installation script for an Egg.
     *
     * @throws \Pteranodon\Exceptions\Model\DataValidationException
     * @throws \Pteranodon\Exceptions\Repository\RecordNotFoundException
     * @throws \Pteranodon\Exceptions\Service\Egg\InvalidCopyFromException
     */
    public function update(EggScriptFormRequest $request, Egg $egg): RedirectResponse
    {
        $this->installScriptService->handle($egg, $request->normalize());
        $this->alert->success(trans('admin/nests.eggs.notices.script_updated'))->flash();

        return redirect()->route('admin.nests.egg.scripts', $egg);
    }
}
