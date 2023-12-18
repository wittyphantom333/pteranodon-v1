<?php

namespace Pteranodon\Http\Requests\Admin\Users;

use Pteranodon\Models\User;
use Illuminate\Support\Collection;
use Pteranodon\Http\Requests\Admin\AdminFormRequest;

class UserFormRequest extends AdminFormRequest
{
    /**
     * Rules to apply to requests for updating or creating a user
     * in the Admin CP.
     */
    public function rules(): array
    {
        return Collection::make(
            User::getRulesForUpdate($this->route()->parameter('user'))
        )->only([
            'email',
            'username',
            'name_first',
            'name_last',
            'password',
            'language',
            'root_admin',
        ])->toArray();
    }
}
