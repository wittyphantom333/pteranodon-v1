<?php

namespace Pteranodon\Http\Controllers\Admin\Pteranodon;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pteranodon\Http\Controllers\Controller;
use Pteranodon\Contracts\Repository\SettingsRepositoryInterface;
use Pteranodon\Http\Requests\Admin\Pteranodon\ReferralsFormRequest;

class ReferralsController extends Controller
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
     * Render the Pteranodon referrals interface.
     */
    public function index(): View
    {
        return view('admin.pteranodon.referrals', [
            'enabled' => $this->settings->get('pteranodon::referrals:enabled', false),
            'reward' => $this->settings->get('pteranodon::referrals:reward', 250),
        ]);
    }

    /**
     * Handle settings update.
     *
     * @throws \Pteranodon\Exceptions\Model\DataValidationException
     * @throws \Pteranodon\Exceptions\Repository\RecordNotFoundException
     */
    public function update(ReferralsFormRequest $request): RedirectResponse
    {
        foreach ($request->normalize() as $key => $value) {
            $this->settings->set('pteranodon::referrals:' . $key, $value);
        }

        $this->alert->success('Referral system has been updated.')->flash();

        return redirect()->route('admin.pteranodon.referrals');
    }
}
