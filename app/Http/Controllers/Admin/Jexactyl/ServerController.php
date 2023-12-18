<?php

namespace Pteranodon\Http\Controllers\Admin\Pteranodon;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pteranodon\Http\Controllers\Controller;
use Pteranodon\Http\Requests\Admin\Pteranodon\ServerFormRequest;
use Pteranodon\Contracts\Repository\SettingsRepositoryInterface;

class ServerController extends Controller
{
    /**
     * StoreController constructor.
     */
    public function __construct(
        private AlertsMessageBag $alert,
        private SettingsRepositoryInterface $settings
    ) {
    }

    /**
     * Render the Pteranodon settings interface.
     */
    public function index(): View
    {
        $prefix = 'pteranodon::renewal:';

        return view('admin.pteranodon.server', [
            'enabled' => $this->settings->get($prefix . 'enabled', false),
            'default' => $this->settings->get($prefix . 'default', 7),
            'cost' => $this->settings->get($prefix . 'cost', 20),
            'editing' => $this->settings->get($prefix . 'editing', false),
            'deletion' => $this->settings->get($prefix . 'deletion', true),
        ]);
    }

    /**
     * Handle settings update.
     *
     * @throws \Pteranodon\Exceptions\Model\DataValidationException
     * @throws \Pteranodon\Exceptions\Repository\RecordNotFoundException
     */
    public function update(ServerFormRequest $request): RedirectResponse
    {
        foreach ($request->normalize() as $key => $value) {
            $this->settings->set('pteranodon::renewal:' . $key, $value);
        }

        $this->alert->success('Pteranodon Server settings has been updated.')->flash();

        return redirect()->route('admin.pteranodon.server');
    }
}
