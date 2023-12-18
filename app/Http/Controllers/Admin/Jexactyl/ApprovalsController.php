<?php

namespace Pteranodon\Http\Controllers\Admin\Pteranodon;

use Illuminate\View\View;
use Pteranodon\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pteranodon\Http\Controllers\Controller;
use Pteranodon\Contracts\Repository\SettingsRepositoryInterface;
use Pteranodon\Http\Requests\Admin\Pteranodon\ApprovalFormRequest;

class ApprovalsController extends Controller
{
    /**
     * ApprovalsController constructor.
     */
    public function __construct(
        private AlertsMessageBag $alert,
        private SettingsRepositoryInterface $settings,
    ) {
    }

    /**
     * Render the Pteranodon referrals interface.
     */
    public function index(): View
    {
        $users = User::where('approved', false)->get();

        return view('admin.pteranodon.approvals', [
            'enabled' => $this->settings->get('pteranodon::approvals:enabled', false),
            'webhook' => $this->settings->get('pteranodon::approvals:webhook'),
            'users' => $users,
        ]);
    }

    /**
     * Updates the settings for approvals.
     *
     * @throws \Pteranodon\Exceptions\Model\DataValidationException
     * @throws \Pteranodon\Exceptions\Repository\RecordNotFoundException
     */
    public function update(ApprovalFormRequest $request): RedirectResponse
    {
        foreach ($request->normalize() as $key => $value) {
            $this->settings->set('pteranodon::approvals:' . $key, $value);
        }

        $this->alert->success('Pteranodon Approval settings have been updated.')->flash();

        return redirect()->route('admin.pteranodon.approvals');
    }

    /**
     * Perform a bulk action for approval status.
     */
    public function bulkAction(Request $request, string $action): RedirectResponse
    {
        if ($action === 'approve') {
            User::query()->where('approved', false)->update(['approved' => true]);
        } else {
            try {
                User::query()->where('approved', false)->delete();
            } catch (DisplayException $ex) {
                throw new DisplayException('Unable to complete action: ' . $ex->getMessage());
            }
        }

        $this->alert->success('All users have been ' . $action === 'approve' ? 'approved ' : 'denied successfully.')->flash();

        return redirect()->route('admin.pteranodon.approvals');
    }

    /**
     * Approve an incoming approval request.
     */
    public function approve(Request $request, int $id): RedirectResponse
    {
        $user = User::where('id', $id)->first();
        $user->update(['approved' => true]);
        // This gives the user access to the frontend.

        $this->alert->success($user->username . ' has been approved.')->flash();

        return redirect()->route('admin.pteranodon.approvals');
    }

    /**
     * Deny an incoming approval request.
     */
    public function deny(Request $request, int $id): RedirectResponse
    {
        $user = User::where('id', $id)->first();
        $user->delete();
        // While typically we should look for associated servers, there
        // shouldn't be any present - as the user has been waiting for approval.

        $this->alert->success($user->username . ' has been denied.')->flash();

        return redirect()->route('admin.pteranodon.approvals');
    }
}
