<?php

namespace Pteranodon\Http\Requests\Api\Client\Account;

use Pteranodon\Models\User;
use Illuminate\Container\Container;
use Illuminate\Contracts\Hashing\Hasher;
use Pteranodon\Http\Requests\Api\Client\ClientApiRequest;
use Pteranodon\Exceptions\Http\Base\InvalidPasswordProvidedException;

class UpdateUsernameRequest extends ClientApiRequest
{
    /**
     * @throws \Pteranodon\Exceptions\Http\Base\InvalidPasswordProvidedException
     */
    public function authorize(): bool
    {
        if (!parent::authorize()) {
            return false;
        }

        $hasher = Container::getInstance()->make(Hasher::class);

        // Verify password matches.
        if (!$hasher->check($this->input('password'), $this->user()->password)) {
            throw new InvalidPasswordProvidedException(trans('validation.internal.invalid_password'));
        }

        return true;
    }

    public function rules(): array
    {
        $rules = User::getRulesForUpdate($this->user());

        return ['username' => $rules['username']];
    }
}
