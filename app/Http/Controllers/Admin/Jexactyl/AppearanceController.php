<?php

namespace Pteranodon\Http\Controllers\Admin\Pteranodon;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pteranodon\Http\Controllers\Controller;
use Illuminate\Contracts\Config\Repository;
use Pteranodon\Exceptions\Model\DataValidationException;
use Pteranodon\Exceptions\Repository\RecordNotFoundException;
use Pteranodon\Contracts\Repository\SettingsRepositoryInterface;
use Pteranodon\Http\Requests\Admin\Pteranodon\AppearanceFormRequest;

class AppearanceController extends Controller
{
    /**
     * AppearanceController constructor.
     */
    public function __construct(
        private Repository $config,
        private AlertsMessageBag $alert,
        private SettingsRepositoryInterface $settings
    ) {
    }

    /**
     * Render the Pteranodon settings interface.
     */
    public function index(): View
    {
        return view('admin.pteranodon.appearance', [
            'name' => config('app.name'),
            'logo' => config('app.logo'),

            'admin' => config('theme.admin'),
            'user' => ['background' => config('theme.user.background')],
        ]);
    }

    /**
     * Handle settings update.
     *
     * @throws DataValidationException|RecordNotFoundException
     */
    public function update(AppearanceFormRequest $request): RedirectResponse
    {
        foreach ($request->normalize() as $key => $value) {
            $this->settings->set('settings::' . $key, $value);
        }

        $this->alert->success('Pteranodon Appearance has been updated.')->flash();

        return redirect()->route('admin.pteranodon.appearance');
    }
}
