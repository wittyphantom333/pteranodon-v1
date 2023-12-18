<?php

namespace Pteranodon\Http\Controllers\Admin\Pteranodon;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pteranodon\Http\Controllers\Controller;
use Pteranodon\Exceptions\Model\DataValidationException;
use Pteranodon\Exceptions\Repository\RecordNotFoundException;
use Pteranodon\Http\Requests\Admin\Pteranodon\AlertFormRequest;
use Pteranodon\Contracts\Repository\SettingsRepositoryInterface;

class AlertsController extends Controller
{
    /**
     * AppearanceController constructor.
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
        return view('admin.pteranodon.alerts', [
            'type' => $this->settings->get('pteranodon::alert:type', 'success'),
            'message' => $this->settings->get('pteranodon::alert:message'),
        ]);
    }

    /**
     * Update or create an alert.
     *
     * @throws DataValidationException|RecordNotFoundException
     */
    public function update(AlertFormRequest $request): RedirectResponse
    {
        foreach ($request->normalize() as $key => $value) {
            $this->settings->set('pteranodon::' . $key, $value);
        }

        $this->alert->success('Pteranodon Alert has been updated.')->flash();

        return redirect()->route('admin.pteranodon.alerts');
    }

    /**
     * Delete the current alert.
     */
    public function remove(): RedirectResponse
    {
        $this->settings->forget('pteranodon::alert:type');
        $this->settings->forget('pteranodon::alert:message');

        $this->alert->success('Pteranodon Alert has been removed.')->flash();

        return redirect()->route('admin.pteranodon.alerts');
    }
}
