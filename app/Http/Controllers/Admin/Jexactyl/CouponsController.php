<?php

namespace Pteranodon\Http\Controllers\Admin\Pteranodon;

use Carbon\Carbon;
use Illuminate\View\View;
use Pteranodon\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pteranodon\Exceptions\DisplayException;
use Pteranodon\Http\Controllers\Controller;
use Pteranodon\Exceptions\Model\DataValidationException;
use Pteranodon\Exceptions\Repository\RecordNotFoundException;
use Pteranodon\Contracts\Repository\SettingsRepositoryInterface;
use Pteranodon\Http\Requests\Admin\Pteranodon\Coupons\IndexFormRequest;
use Pteranodon\Http\Requests\Admin\Pteranodon\Coupons\StoreFormRequest;

class CouponsController extends Controller
{
    public function __construct(private AlertsMessageBag $alert, private SettingsRepositoryInterface $settings)
    {
    }

    public function index(): View
    {
        return view('admin.pteranodon.coupons', [
            'coupons' => Coupon::all(),
            'enabled' => $this->settings->get('pteranodon::coupons:enabled'),
        ]);
    }

    /**
     * @throws DataValidationException
     * @throws RecordNotFoundException
     */
    public function update(IndexFormRequest $request): RedirectResponse
    {
        foreach ($request->normalize() as $key => $value) {
            $this->settings->set('pteranodon::coupons:' . $key, $value);
        }

        $this->alert->success('The coupons system has been successfully updated.')->flash();

        return redirect()->route('admin.pteranodon.coupons');
    }

    /**
     * @throws DisplayException
     */
    public function store(StoreFormRequest $request): RedirectResponse
    {
        if ($request->input('expires')) {
            $expires_at = Carbon::now()->addHours($request->input('expires'));
        } else {
            $expires_at = null;
        }

        if (Coupon::where(['code' => $request->input('code')])->exists()) {
            throw new DisplayException('You cannot create a coupon with an already existing code.');
        }

        Coupon::query()->insert([
            'expires' => $expires_at,
            'created_at' => Carbon::now(),
            'code' => $request->input('code'),
            'uses' => $request->input('uses'),
            'cr_amount' => $request->input('credits'),
        ]);

        $this->alert->success('Successfully created a coupon.')->flash();

        return redirect()->route('admin.pteranodon.coupons');
    }
}
