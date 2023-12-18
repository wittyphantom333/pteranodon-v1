<?php

namespace Pteranodon\Http\Controllers\Admin\Pteranodon;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pteranodon\Http\Controllers\Controller;
use Pteranodon\Contracts\Repository\SettingsRepositoryInterface;
use Pteranodon\Http\Requests\Admin\Pteranodon\RegistrationFormRequest;

class RegistrationController extends Controller
{
    /**
     * RegistrationController constructor.
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
        return view('admin.pteranodon.registration', [
            'enabled' => $this->settings->get('pteranodon::registration:enabled', false),
            'verification' => $this->settings->get('pteranodon::registration:verification', false),

            'discord_enabled' => $this->settings->get('pteranodon::discord:enabled', false),
            'discord_id' => $this->settings->get('pteranodon::discord:id', 0),
            'discord_secret' => $this->settings->get('pteranodon::discord:secret', 0),

            'cpu' => $this->settings->get('pteranodon::registration:cpu', 100),
            'memory' => $this->settings->get('pteranodon::registration:memory', 1024),
            'disk' => $this->settings->get('pteranodon::registration:disk', 5120),
            'slot' => $this->settings->get('pteranodon::registration:slot', 1),
            'port' => $this->settings->get('pteranodon::registration:port', 1),
            'backup' => $this->settings->get('pteranodon::registration:backup', 1),
            'database' => $this->settings->get('pteranodon::registration:database', 0),
        ]);
    }

    /**
     * Handle settings update.
     *
     * @throws \Pteranodon\Exceptions\Model\DataValidationException
     * @throws \Pteranodon\Exceptions\Repository\RecordNotFoundException
     */
    public function update(RegistrationFormRequest $request): RedirectResponse
    {
        foreach ($request->normalize() as $key => $value) {
            $this->settings->set('pteranodon::' . $key, $value);
        }

        $this->alert->success('Pteranodon Registration has been updated.')->flash();

        return redirect()->route('admin.pteranodon.registration');
    }
}
